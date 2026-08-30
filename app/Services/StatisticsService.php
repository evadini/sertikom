<?php

namespace App\Services;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StatisticsService
{
    public function getEventStats(int $eventId): array
    {
        $tiketTerjual = DB::table('tickets')
            ->where('event_id', $eventId)
            ->whereNotNull('order_id')
            ->count();

        $totalKuota = DB::table('tickets')
            ->where('event_id', $eventId)
            ->whereNull('order_id')
            ->sum('stock');

        $totalHadir = DB::table('tickets')
            ->where('event_id', $eventId)
            ->whereNotNull('date_used')
            ->count();

        $totalPendapatan = DB::table('orders')
            ->where('event_id', $eventId)
            ->where('status', 'paid')
            ->sum('total_amount') ?? 0;

        $kategoriTiket = DB::table('tickets')
            ->select('ticket_type', DB::raw('COUNT(*) as total'))
            ->where('event_id', $eventId)
            ->whereNotNull('order_id')
            ->groupBy('ticket_type')
            ->get();

        $dailySales = $this->getDailySales($eventId);

        return compact('tiketTerjual', 'totalKuota', 'totalHadir', 'totalPendapatan', 'kategoriTiket', 'dailySales');
    }

    public function getDailySales(int $eventId): array
    {
        $rows = DB::table('tickets')
            ->select(DB::raw('DATE(purchase_date) as date'), DB::raw('COUNT(*) as total'))
            ->where('event_id', $eventId)
            ->whereNotNull('order_id')
            ->groupBy(DB::raw('DATE(purchase_date)'))
            ->orderBy('date')
            ->pluck('total', 'date');

        $days = [];
        $labels = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $key = $date->format('Y-m-d');
            $days[] = $rows->get($key, 0);
            $labels[] = $date->isoFormat('dd');
        }

        return ['data' => $days, 'labels' => $labels];
    }

    public function getAttendanceStats(int $eventId): array
    {
        $totalTickets = DB::table('tickets')
            ->where('event_id', $eventId)
            ->whereNotNull('order_id')
            ->count();

        $attendedTickets = DB::table('tickets')
            ->where('event_id', $eventId)
            ->whereNotNull('order_id')
            ->where('is_attended', true)
            ->count();

        $attendanceRate = $totalTickets > 0 ? round(($attendedTickets / $totalTickets) * 100, 1) : 0;

        return [
            'total_tickets' => $totalTickets,
            'attended_tickets' => $attendedTickets,
            'attendance_rate' => $attendanceRate,
        ];
    }
}
