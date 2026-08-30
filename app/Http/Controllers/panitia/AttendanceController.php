<?php

namespace App\Http\Controllers\Panitia;

use App\Http\Controllers\Controller;
use App\Http\Requests\VerifyTicketRequest;
use App\Models\Ticket;
use App\Services\AttendanceService;
use App\Services\StatisticsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class AttendanceController extends Controller
{
    public function __construct(
        protected AttendanceService $attendanceService,
        protected StatisticsService $statisticsService,
    ) {}

    public function index()
    {
        $panitia = Auth::user();
        $userEvents = $panitia->events()->where('status', 'published')->get() ?? collect();

        $recentAttendances = collect();
        if ($userEvents->count() > 0) {
            $recentAttendances = Ticket::whereIn('event_id', $userEvents->pluck('id'))
                ->where('is_attended', true)
                ->with('user:nim,name')
                ->orderBy('attended_at', 'desc')
                ->take(20)
                ->get();
        }

        return view('Panitia.Attendance', compact('userEvents', 'recentAttendances'));
    }

    public function verifyTicket(VerifyTicketRequest $request)
    {
        try {
            $result = $this->attendanceService->verify(
                $request->ticket_id,
                $request->event_id
            );

            $statusCode = $result['success'] ? 200 : ($result['type'] === 'error' ? 404 : 200);

            return response()->json($result, $statusCode);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'type' => 'error',
            ], 500);
        }
    }

    public function getStatistics(Request $request)
    {
        $panitia = Auth::user();
        $eventId = $request->query('event_id');
        $panitia->events()->findOrFail($eventId);

        $stats = $this->statisticsService->getAttendanceStats($eventId);

        return response()->json($stats);
    }

    public function showAttendees($id)
    {
        $event = Auth::user()->events()->findOrFail($id);

        $attendees = Ticket::where('event_id', $id)
            ->whereNotNull('order_id')
            ->with('user:nim,name,email,phone_number')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('panitia.customerdata', compact('event', 'attendees'));
    }

    public function exportAttendees($id)
    {
        $event = Auth::user()->events()->findOrFail($id);

        $attendees = Ticket::where('event_id', $id)
            ->whereNotNull('order_id')
            ->with('user:nim,name,email,phone_number')
            ->orderBy('created_at', 'desc')
            ->get();

        $filename = 'peserta-' . str_replace(' ', '-', $event->name) . '.xls';

        $headers = [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return Response::make(
            view('panitia.customerdata-export', compact('event', 'attendees')),
            200,
            $headers
        );
    }
}
