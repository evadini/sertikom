<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSnapTokenRequest;
use App\Http\Requests\HandleSuccessRequest;
use App\Models\Order;
use App\Models\Event;
use App\Models\Ticket;
use App\Services\PaymentService;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class PaymentController extends Controller
{
    public function __construct(
        protected PaymentService $paymentService
    ) {
        Config::$serverKey    = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized  = config('midtrans.is_sanitized');
        Config::$is3ds        = config('midtrans.is_3ds');
        Config::$curlOptions[CURLOPT_IPRESOLVE] = CURL_IPRESOLVE_V4;
        Config::$curlOptions[CURLOPT_HTTPHEADER] = [];
    }

    public function createSnapToken(CreateSnapTokenRequest $request, Event $event)
    {
        if ($event->status !== 'published') {
            return response()->json(['error' => 'Event tidak tersedia untuk pembelian.'], 400);
        }

        $eventEndDate = $event->date_end ?? $event->date_start;
        if ($eventEndDate < now()->format('Y-m-d')) {
            return response()->json(['error' => 'Event sudah lewat. Tidak dapat membeli tiket.'], 400);
        }

        $user      = auth()->user();
        $items     = $request->items;

        $templateTickets = $event->tickets()->whereNull('order_id')->get()->keyBy('ticket_type');

        foreach ($items as $item) {
            $template = $templateTickets->get($item['ticket_type']);
            if (!$template) {
                return response()->json(['error' => 'Tipe tiket ' . $item['ticket_type'] . ' tidak ditemukan.'], 400);
            }
            if ((int) $item['quantity'] > (int) $template->stock) {
                return response()->json(['error' => 'Stok tiket ' . $item['ticket_type'] . ' tidak mencukupi. Tersedia: ' . $template->stock], 400);
            }
        }

        $orderCode = Order::generateOrderCode();

        $totalAmount = 0;
        $itemDetails = [];
        $ticketItems = [];

        foreach ($items as $item) {
            $qty  = (int) $item['quantity'];
            $price = (float) $item['price'];
            $subtotal = $qty * $price;
            $totalAmount += $subtotal;

            $ticketItems[] = [
                'ticket_type' => $item['ticket_type'],
                'quantity'    => $qty,
                'price'       => $price,
            ];

            $itemDetails[] = [
                'id'       => $item['ticket_type'],
                'price'    => (int) $price,
                'quantity' => $qty,
                'name'     => $event->name . ' - ' . $item['ticket_type'],
            ];
        }

        $order = Order::create([
            'user_id'      => $user->getKey(),
            'event_id'     => $event->id,
            'order_code'   => $orderCode,
            'total_amount' => $totalAmount,
            'ticket_items' => $ticketItems,
            'status'       => 'pending',
            'expired_at'   => now()->addHours(24),
        ]);

        $params = [
            'transaction_details' => [
                'order_id'     => $orderCode,
                'gross_amount' => (int) $totalAmount,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email'      => $user->email,
            ],
            'item_details' => $itemDetails,
            'callbacks' => [
                'finish'  => route('payment.success'),
                'unfinish'=> route('payment.failed'),
                'error'   => route('payment.failed'),
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            $order->update(['snap_token' => $snapToken]);

            return response()->json([
                'snap_token' => $snapToken,
                'order_id'   => $order->id,
            ]);
        } catch (\Exception $e) {
            $order->update(['status' => 'failed']);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function handleSuccess(HandleSuccessRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $order = Order::where('id', $request->order_id)
                ->where('user_id', auth()->id())
                ->lockForUpdate()
                ->firstOrFail();

            if ($order->status === 'paid') {
                return response()->json(['success' => true, 'already_paid' => true]);
            }

            $templateTickets = Ticket::where('event_id', $order->event_id)
                ->whereNull('order_id')
                ->lockForUpdate()
                ->get()
                ->keyBy('ticket_type');

            foreach ($order->ticket_items as $item) {
                $template = $templateTickets->get($item['ticket_type']);
                if (!$template || (int) $template->stock < (int) $item['quantity']) {
                    $order->update(['status' => 'failed']);
                    return response()->json(['error' => 'Stok tiket ' . $item['ticket_type'] . ' sudah habis.'], 400);
                }
            }

            $order->update([
                'status'         => 'paid',
                'transaction_id' => $request->transaction_id,
                'payment_type'   => $request->payment_type,
                'paid_at'        => now(),
            ]);

            $tickets = $this->paymentService->createTickets($order);

            return response()->json([
                'success'    => true,
                'order_code' => $order->order_code,
                'tickets'    => $tickets,
            ]);
        });
    }

    public function notification()
    {
        try {
            $notification = new Notification();

            $orderId           = $notification->order_id;
            $transactionStatus = $notification->transaction_status;
            $fraudStatus       = $notification->fraud_status;
            $paymentType       = $notification->payment_type;
            $transactionId     = $notification->transaction_id;

            return DB::transaction(function () use ($orderId, $transactionStatus, $fraudStatus, $paymentType, $transactionId) {
                $order = Order::where('order_code', $orderId)->lockForUpdate()->first();

                if (!$order) {
                    return response()->json(['message' => 'Order not found'], 404);
                }

                if ($transactionStatus === 'capture') {
                    $status = ($fraudStatus === 'accept') ? 'paid' : 'failed';
                } elseif ($transactionStatus === 'settlement') {
                    $status = 'paid';
                } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
                    $status = $transactionStatus === 'expire' ? 'expired' : 'failed';
                } elseif ($transactionStatus === 'pending') {
                    $status = 'pending';
                } else {
                    $status = 'failed';
                }

                if ($status === 'paid' && $order->tickets()->count() > 0) {
                    return response()->json(['message' => 'OK, already processed']);
                }

                $order->update([
                    'status'         => $status,
                    'transaction_id' => $transactionId,
                    'payment_type'   => $paymentType,
                    'paid_at'        => $status === 'paid' ? now() : null,
                ]);

                if ($status === 'paid') {
                    $templateTickets = Ticket::where('event_id', $order->event_id)
                        ->whereNull('order_id')
                        ->lockForUpdate()
                        ->get()
                        ->keyBy('ticket_type');

                    foreach ($order->ticket_items as $item) {
                        $template = $templateTickets->get($item['ticket_type']);
                        if (!$template || (int) $template->stock < (int) $item['quantity']) {
                            $order->update(['status' => 'failed']);
                            return response()->json(['message' => 'Stok habis'], 400);
                        }
                    }

                    $this->paymentService->createTickets($order);
                }

                return response()->json(['message' => 'OK']);
            });
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function success()
    {
        return redirect()->route('pembeli.myticket')
            ->with('success', 'Pembayaran berhasil! Tiket kamu sudah tersedia.');
    }

    public function failed()
    {
        return redirect()->route('pembeli.event')
            ->with('error', 'Pembayaran gagal atau dibatalkan.');
    }
}
