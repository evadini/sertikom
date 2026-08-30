<?php

namespace App\Services;

use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;

class AttendanceService
{
    public function verify(string $qrCode, int $eventId): array
    {
        $ticket = Ticket::where('qr_code', $qrCode)
            ->where('event_id', $eventId)
            ->with(['user:nim,name,email', 'event:id,name'])
            ->first();

        if (!$ticket) {
            return [
                'success' => false,
                'message' => 'Ticket tidak ditemukan atau invalid',
                'type' => 'error',
            ];
        }

        if ($ticket->is_attended) {
            return [
                'success' => false,
                'message' => 'Peserta sudah melakukan checkin sebelumnya',
                'type' => 'warning',
                'data' => [
                    'name' => $ticket->user->name,
                    'email' => $ticket->user->email,
                    'event' => $ticket->event->name,
                    'ticket_type' => $ticket->ticket_type,
                    'attended_at' => $ticket->attended_at?->format('H:i:s') ?? 'N/A',
                ],
            ];
        }

        $ticket->update([
            'is_attended' => true,
            'attended_at' => now(),
            'status' => 'Used',
            'date_used' => now(),
        ]);

        return [
            'success' => true,
            'message' => 'Checkin berhasil!',
            'type' => 'success',
            'data' => [
                'name' => $ticket->user->name,
                'email' => $ticket->user->email,
                'event' => $ticket->event->name,
                'ticket_type' => $ticket->ticket_type,
                'attended_at' => now()->format('H:i:s'),
            ],
        ];
    }
}
