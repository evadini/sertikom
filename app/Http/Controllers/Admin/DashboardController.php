<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\User;
use App\Models\RoleApplication;

class DashboardController extends Controller
{
    public function index()
    {
        // Get published events count
        $publishedEventsCount = Event::where('status', 'published')->count();

        // Get pending events count
        $pendingEventsCount = Event::where('status', 'pending')->count();

        // Get total active users (excluding admins or role-based filtering)
        $usersCount = User::count();

        // Get events grouped by category for the chart
     $eventsByCategory = \App\Models\Category::withCount([
        'events' => function ($query) {
        $query->where('status', 'published');
    }
        ])
        ->orderByDesc('events_count')
        ->get();

        $categories = $eventsByCategory->pluck('name')->toArray();
        $categoryCounts = $eventsByCategory->pluck('events_count')->toArray();
        // Calculate ratio for pie chart
        $totalEvents = $publishedEventsCount + $pendingEventsCount;
        $publishedPercentage = $totalEvents > 0 ? ($publishedEventsCount / $totalEvents) * 100 : 0;
        $pendingPercentage = $totalEvents > 0 ? ($pendingEventsCount / $totalEvents) * 100 : 0;

        // Get recent pending events for the sidebar
        $upcomingPendingEvents = Event::where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Get recent published events
        $recentPublishedEvents = Event::where('status', 'published')
            ->orderBy('date_start', 'desc')
            ->take(5)
            ->get();

        return view('Admin.Dashboard', [
            'publishedEventsCount' => $publishedEventsCount,
            'pendingEventsCount' => $pendingEventsCount,
            'usersCount' => $usersCount,
            'categories' => $categories,
            'categoryCounts' => $categoryCounts,
            'publishedPercentage' => $publishedPercentage,
            'pendingPercentage' => $pendingPercentage,
            'totalEvents' => $totalEvents,
            'upcomingPendingEvents' => $upcomingPendingEvents,
            'recentPublishedEvents' => $recentPublishedEvents,
        ]);
    }
}
