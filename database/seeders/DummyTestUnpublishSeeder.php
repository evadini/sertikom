<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Event;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Seeder;

class DummyTestUnpublishSeeder extends Seeder
{
    public function run(): void
    {
        $pembeli = User::firstOrCreate(
            ['email' => 'testpembeli@gmail.com'],
            [
                'name' => 'Test Pembeli',
                'nim' => 'TEST001',
                'phone_number' => '081234567890',
                'password' => bcrypt('password'),
                'role' => 'pembeli',
            ]
        );
        $this->command->info("Pembeli: {$pembeli->getKey()} - {$pembeli->email}");

        $event = Event::where('status', 'published')->first();
        if (!$event) {
            $panitia = User::firstOrCreate(
                ['email' => 'testpanitia@gmail.com'],
                [
                    'name' => 'Test Panitia',
                    'nim' => 'TEST002',
                    'phone_number' => '081234567891',
                    'password' => bcrypt('password'),
                    'role' => 'panitia',
                ]
            );
            $cat = Category::first() ?? Category::create(['name' => 'Umum']);

            $event = Event::create([
                'user_id' => $panitia->getKey(),
                'name' => 'Test Event untuk Simulasi',
                'category_id' => $cat->id,
                'location' => 'Gedung Serbaguna',
                'date' => now()->addDays(30)->format('Y-m-d'),
                'time_start' => '09:00:00',
                'time_end' => '15:00:00',
                'description' => 'Event untuk testing notifikasi unpublish',
                'ticket_type' => 'paid',
                'price' => 50000,
                'stock' => 100,
                'status' => 'published',
            ]);

            Ticket::create([
                'user_id' => $panitia->getKey(),
                'event_id' => $event->id,
                'ticket_type' => 'Reguler',
                'price' => 50000,
                'stock' => 100,
                'status' => 'Active',
            ]);
        }
        $this->command->info("Event: {$event->id} - {$event->name}");

        $order = Order::firstOrCreate(
            [
                'user_id' => $pembeli->getKey(),
                'event_id' => $event->id,
                'status' => 'paid',
            ],
            [
                'order_code' => Order::generateOrderCode(),
                'ticket_type' => 'Reguler',
                'quantity' => 2,
                'price_per_ticket' => 50000,
                'total_amount' => 100000,
                'paid_at' => now(),
            ]
        );
        $this->command->info("Order: {$order->id} - {$order->order_code}");

        Ticket::firstOrCreate(
            [
                'user_id' => $pembeli->getKey(),
                'event_id' => $event->id,
                'order_id' => $order->id,
                'ticket_type' => 'Reguler',
                'price' => 50000,
            ],
            [
                'status' => 'Active',
                'purchase_date' => now(),
                'qr_code' => 'QR-TEST-' . uniqid(),
            ]
        );
        Ticket::firstOrCreate(
            [
                'user_id' => $pembeli->getKey(),
                'event_id' => $event->id,
                'order_id' => $order->id,
                'ticket_type' => 'Reguler',
                'price' => 50000,
                'qr_code' => 'QR-TEST-' . uniqid(),
            ],
            [
                'status' => 'Active',
                'purchase_date' => now(),
                'qr_code' => 'QR-TEST-' . uniqid(),
            ]
        );

        $count = Ticket::where('event_id', $event->id)->whereNotNull('order_id')->count();
        $this->command->info('');
        $this->command->info("✓ DATA READY!");
        $this->command->info("Event: {$event->name} (ID: {$event->id})");
        $this->command->info("Pembeli: {$pembeli->name} <{$pembeli->email}>");
        $this->command->info("Tiket terbeli: {$count}");
    }
}
