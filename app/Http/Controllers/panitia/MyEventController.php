<?php

namespace App\Http\Controllers\Panitia;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class MyEventController extends Controller
{
    /**
     * Display a listing of the current user's events.
     */
    public function index()
    {
        $events = Event::where('user_id', Auth::id())
            ->with(['tickets' => function ($q) {
                $q->whereNull('order_id');
            }])
            ->withCount(['tickets as tickets_sold' => function ($q) {
                $q->whereNotNull('order_id');
            }])
            ->orderByDesc('date_start')
            ->get();

        return view('Panitia.MyEvent', compact('events'));
    }
}
