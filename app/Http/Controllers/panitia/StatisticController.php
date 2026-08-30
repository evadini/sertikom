<?php

namespace App\Http\Controllers\Panitia;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Services\StatisticsService;
use Illuminate\Support\Facades\Auth;

class StatisticController extends Controller
{
    public function __construct(
        protected StatisticsService $statisticsService
    ) {}

    public function index()
    {
        $events = Event::where('user_id', Auth::id())
            ->where('status', 'published')
            ->with(['tickets' => fn($q) => $q->whereNull('order_id')])
            ->withCount(['tickets as tickets_sold' => fn($q) => $q->whereNotNull('order_id')])
            ->orderByDesc('date_start')
            ->get();

        return view('Panitia.Statistic', compact('events'));
    }

    public function show($id)
    {
        $event = Event::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $stats = $this->statisticsService->getEventStats($id);

        return view('Panitia.Statistic2', array_merge(
            compact('event'),
            $stats
        ));
    }
}
