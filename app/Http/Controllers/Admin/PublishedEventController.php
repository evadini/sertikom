<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\EventUnpublishedMail;
use App\Models\Category;
use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PublishedEventController extends Controller
{
    public function index(Request $request)
    {
        $today = Carbon::today();

        $query = Event::with('tickets')->where('status', 'published')
            ->where(function ($q) use ($today) {
                $q->whereDate('date_end', '>=', $today)
                  ->orWhere(function ($q) use ($today) {
                      $q->whereNull('date_end')->whereDate('date_start', '>=', $today);
                  });
            });

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('date')) {
            $query->where(function ($q) use ($request) {
                $q->whereDate('date_start', '<=', $request->date)
                  ->where(function ($q) use ($request) {
                      $q->whereDate('date_end', '>=', $request->date)
                        ->orWhereNull('date_end');
                  });
            });
        }

        $publishedEvents = $query->orderBy('updated_at', 'desc')->get();
        $events          = Event::with('tickets')->where('status', 'published')
            ->where(function ($q) use ($today) {
                $q->whereDate('date_end', '>=', $today)
                  ->orWhere(function ($q) use ($today) {
                      $q->whereNull('date_end')->whereDate('date_start', '>=', $today);
                  });
            })
            ->orderBy('updated_at', 'desc')
            ->take(5)->get();
        $upcomingEvents  = Event::with('tickets')->where('status', 'published')
            ->where(function ($q) use ($today) {
                $q->whereDate('date_end', '>=', $today)
                  ->orWhere(function ($q) use ($today) {
                      $q->whereNull('date_end')->whereDate('date_start', '>=', $today);
                  });
            })
            ->orderBy('date_start', 'asc')
            ->take(4)
            ->get();

        $categories = Category::all();

        return view('Admin.PublishedEvent', compact('publishedEvents', 'events', 'upcomingEvents', 'categories'));
    }

    public function unpublish(Request $request, Event $event)
    {
        $request->validate([
            'reason' => 'required|string|min:10|max:500',
            'refund_date' => 'nullable|date',
            'refund_location' => 'nullable|string|max:255',
            'refund_info' => 'nullable|string|max:1000',
        ]);

        $event->update([
            'status'           => 'unpublished',
            'unpublish_reason' => $request->reason,
            'unpublished_at'   => now(),
            'refund_date'      => $request->refund_date,
            'refund_location'  => $request->refund_location,
            'refund_info'      => $request->refund_info,
        ]);

        $event->tickets()->whereNotNull('order_id')->update(['status' => 'Canceled']);

        $buyers = User::whereHas('tickets', function ($q) use ($event) {
            $q->where('event_id', $event->id)->whereNotNull('order_id');
        })->get();

        foreach ($buyers as $buyer) {
            if ($buyer->email) {
                try {
                    Mail::to($buyer->email)->send(new EventUnpublishedMail($event, $buyer));
                } catch (\Exception $e) {
                    Log::error('Gagal kirim email unpublish ke ' . $buyer->email . ': ' . $e->getMessage());
                }
            }
        }

        return redirect()->route('admin.PublishedEvent')->with('success', 'Event berhasil di-unpublish dan tiket pembeli telah di-cancel.');
    }
}
