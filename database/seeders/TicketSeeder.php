<?php

namespace Database\Seeders;

use App\Models\Ticket;
use App\Models\User;
use App\Models\Event;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil user pembeli (gunakan user pertama atau buat baru)
        $user = User::where('role', 'pembeli')->first() ?? User::factory()->create(['role' => 'pembeli']);

        // Ambil events yang tersedia
        $events = Event::where('status', 'published')->limit(2)->get();

        if ($events->count() < 2) {
            // Jika tidak ada events, buat dummy events
            $events = Event::factory(2)->create(['status' => 'published', 'user_id' => User::where('role', 'panitia')->first()?->getKey() ?? User::factory()->create(['role' => 'panitia'])->getKey()]);
        }

        // Buat 2 tickets dengan status berbeda
        Ticket::create([
            'user_id' => $user->getKey(),
            'event_id' => $events->first()->id,
            'ticket_type' => 'VIP',
            'status' => 'Active',
            'purchase_date' => Carbon::now()->subDays(5),
            'date_used' => null,
            'qr_code' => 'QR-' . uniqid(),
        ]);

        Ticket::create([
            'user_id' => $user->getKey(),
            'event_id' => $events->last()->id,
            'ticket_type' => 'Regular',
            'status' => 'Used',
            'purchase_date' => Carbon::now()->subMonth(),
            'date_used' => Carbon::now()->subDays(2),
            'qr_code' => 'QR-' . uniqid(),
        ]);
    }
}
