<?php

namespace App\Http\Controllers\Pembeli;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Category;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $today = Carbon::today();

        // 1. Query untuk event utama dengan filter
        $query = Event::with(['tickets', 'category'])->where('status', 'published')
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

        $publicEvents = $query->latest()->get();

        // 2. Query untuk carousel & sidebar
        $events = Event::where('status', 'published')
            ->where(function ($q) use ($today) {
                $q->whereDate('date_end', '>=', $today)
                  ->orWhere(function ($q) use ($today) {
                      $q->whereNull('date_end')->whereDate('date_start', '>=', $today);
                  });
            })->latest()->take(5)->get();
        $upcomingEvents = Event::where('status', 'published')
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

        return view('pembeli.event', compact('publicEvents', 'events', 'upcomingEvents', 'categories'));
    }

        public function show(Event $event)
        {
            $event->load(['tickets', 'category']);

            return view('Pembeli.detail', compact('event'));
        }
}
