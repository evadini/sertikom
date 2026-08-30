<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Ticket;
use App\Services\StatisticsService;
use Illuminate\Http\Request;

class EventStatisticController extends Controller
{
    public function __construct(
        protected StatisticsService $statisticsService
    ) {}

    public function index(Request $request)
    {
        $query = Event::with(['tickets' => fn($q) => $q->whereNull('order_id')])
            ->withCount(['tickets as tickets_sold' => fn($q) => $q->whereNotNull('order_id')]);

        // Search
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        // Status filter
        $activeFilter = $request->input('status', 'all');
        if ($activeFilter !== 'all') {
            $query->where('status', $activeFilter);
        }

        $events = $query->orderByDesc('date_start')->get();

        return view('Admin.EventStatistic', compact('events', 'activeFilter', 'search'));
    }

    public function show($id)
    {
        $event = Event::with('user')->findOrFail($id);
        $stats = $this->statisticsService->getEventStats($id);

        return view('Admin.DetailEvent', array_merge(
            compact('event'),
            $stats
        ));
    }

    public function attendees($id)
    {
        $event = Event::findOrFail($id);

        $attendees = Ticket::where('event_id', $id)
            ->whereNotNull('order_id')
            ->with('user:nim,name,phone_number')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('Admin.EventAttendees', compact('event', 'attendees'));
    }
}
