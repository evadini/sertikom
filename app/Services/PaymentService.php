<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PaymentService
{
    public function createTickets(Order $order): array
    {
        $tickets = [];

        foreach ($order->ticket_items as $item) {
            for ($i = 0; $i < $item['quantity']; $i++) {
                do {
                    $qrCode = Str::random(12);
                } while (Ticket::where('qr_code', $qrCode)->exists());

                $ticket = Ticket::create([
                    'user_id' => $order->user_id,
                    'event_id' => $order->event_id,
                    'order_id' => $order->id,
                    'ticket_type' => $item['ticket_type'],
                    'status' => 'Active',
                    'purchase_date' => now(),
                    'qr_code' => $qrCode,
                ]);

                $tickets[] = $ticket;
            }

            $this->decrementStock($order->event_id, $item['ticket_type'], $item['quantity']);
        }

        return $tickets;
    }

    public function decrementStock(int $eventId, string $ticketType, int $quantity): void
    {
        DB::transaction(function () use ($eventId, $ticketType, $quantity) {
            $template = Ticket::where('event_id', $eventId)
                ->where('ticket_type', $ticketType)
                ->whereNull('order_id')
                ->lockForUpdate()
                ->first();

            if ($template && (int) $template->stock >= $quantity) {
                $template->decrement('stock', $quantity);
            }
        });
    }
}
