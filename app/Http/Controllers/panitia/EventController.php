<?php

namespace App\Http\Controllers\Panitia;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEventRequest;
use App\Models\Category;
use App\Models\Event;
use App\Services\EventService;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class EventController extends Controller
{
    public function __construct(
        protected EventService $eventService
    ) {}

    public function index()
    {
        $categories = Category::all();

        $today = Carbon::today();

        $publicEvents = Event::with('tickets', 'category')
            ->where('status', 'published')
            ->where(function ($q) use ($today) {
                $q->whereDate('date_end', '>=', $today)
                  ->orWhere(function ($q) use ($today) {
                      $q->whereNull('date_end')->whereDate('date_start', '>=', $today);
                  });
            })
            ->latest()
            ->get();

        $upcomingEvents = Event::where('status', 'published')
            ->where(function ($q) use ($today) {
                $q->whereDate('date_end', '>=', $today)
                  ->orWhere(function ($q) use ($today) {
                      $q->whereNull('date_end')->whereDate('date_start', '>=', $today);
                  });
            })
            ->orderBy('date_start')
            ->take(4)
            ->get();

        $events = $publicEvents;

        return view('panitia.Event', compact('categories', 'publicEvents', 'upcomingEvents', 'events'));
    }

    public function show($id)
    {
        $event = Event::with('tickets')->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('panitia.DetailEvent', compact('event'));
    }

    public function store(StoreEventRequest $request)
    {
        $this->eventService->createEvent($request);

        return redirect()->route('panitia.myevent')
            ->with('success', 'Event berhasil diajukan!');
    }

    public function edit($id)
    {
        $event = Event::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $categories = Category::all();
        $ticketTypes = $event->tickets()
            ->whereNull('order_id')
            ->get(['ticket_type', 'price', 'stock']);

        return view('panitia.EditEvent', compact('event', 'categories', 'ticketTypes'));
    }

    public function update(StoreEventRequest $request, $id)
    {
        $event = Event::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $this->eventService->updateEvent($request, $event);

        return redirect()->route('panitia.myevent')
            ->with('success', 'Perubahan berhasil disimpan!');
    }

}
