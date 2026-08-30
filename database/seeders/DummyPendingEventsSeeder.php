<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Ticket;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DummyPendingEventsSeeder extends Seeder
{
    public function run(): void
    {
        $panitiaNims = User::where('role', 'panitia')->pluck('nim')->toArray();
        if (empty($panitiaNims)) {
            $this->command->warn('No panitia users found. Create at least one panitia first.');
            return;
        }

        $cats = \App\Models\Category::pluck('id', 'name');

        $banners = [
            'banner_1780059079.jpg',
            'banner_1780060795.jpg',
            'banner_1780062759.jpeg',
            'banner_1780212548.jpeg',
            'banner_1780246287.jpg',
        ];

        $dummies = [
            [
                'user_id' => $panitiaNims[array_rand($panitiaNims)],
                'name' => 'Seminar Furab 2026',
                'category_id' => $cats['Seminar'] ?? 1,
                'location' => 'Gedung Utama Lt. 7, Politeknik Negeri Batam',
                'date_start' => '2026-07-15',
                'date_end' => '2026-07-15',
                'time_start' => '09:00:00',
                'time_end' => '15:00:00',
                'description' => 'Seminar nasional membahas inovasi dan teknologi terkini di bidang furab. Menghadirkan pembicara dari berbagai universitas ternama di Indonesia.',
                'terms' => 'Peserta wajib hadir tepat waktu. Tiket tidak bisa refund. Peserta mendapatkan e-sertifikat.',
                'tickets' => [
                    ['ticket_type' => 'VIP', 'price' => 75000, 'stock' => 50],
                    ['ticket_type' => 'Reguler', 'price' => 25000, 'stock' => 200],
                    ['ticket_type' => 'Online', 'price' => 10000, 'stock' => 500],
                ],
            ],
            [
                'user_id' => $panitiaNims[array_rand($panitiaNims)],
                'name' => 'Workshop UI/UX Design',
                'category_id' => $cats['Workshop'] ?? 1,
                'location' => 'Creative Hub, Batam Center',
                'date_start' => '2026-07-20',
                'date_end' => '2026-07-20',
                'time_start' => '13:00:00',
                'time_end' => '17:00:00',
                'description' => 'Workshop intensif UI/UX Design menggunakan Figma. Cocok untuk pemula yang ingin belajar desain antarmuka pengguna.',
                'terms' => 'Peserta wajib membawa laptop. Tiket sudah termasuk modul dan template Figma. Kuota terbatas.',
                'tickets' => [
                    ['ticket_type' => 'Early Bird', 'price' => 50000, 'stock' => 30],
                    ['ticket_type' => 'Normal', 'price' => 100000, 'stock' => 70],
                ],
            ],
            [
                'user_id' => $panitiaNims[array_rand($panitiaNims)],
                'name' => 'Konser Amal Gemuruh Nusantara',
                'category_id' => $cats['Konser'] ?? 2,
                'location' => 'Lapangan Parkir Polda Kepri, Batam',
                'date_start' => '2026-08-10',
                'date_end' => '2026-08-10',
                'time_start' => '19:00:00',
                'time_end' => '23:59:00',
                'description' => 'Konser amal menghadirkan musisi-musisi ternama tanah air. Seluruh hasil penjualan tiket akan disalurkan untuk korban bencana alam.',
                'terms' => 'Dilarang membawa minuman keras. Anak-anak di bawah 12 tahun harus didampingi orang tua. Tiket non-refundable.',
                'tickets' => [
                    ['ticket_type' => 'Festival', 'price' => 100000, 'stock' => 500],
                    ['ticket_type' => 'VIP', 'price' => 250000, 'stock' => 100],
                    ['ticket_type' => 'VVIP', 'price' => 500000, 'stock' => 30],
                ],
            ],
            [
                'user_id' => $panitiaNims[array_rand($panitiaNims)],
                'name' => 'Hackathon AI 2026',
                'category_id' => $cats['Lomba'] ?? 3,
                'location' => 'Politeknik Negeri Batam, Gedung Teknik',
                'date_start' => '2026-08-25',
                'date_end' => '2026-08-25',
                'time_start' => '08:00:00',
                'time_end' => '18:00:00',
                'description' => 'Kompetisi hackathon tingkat mahasiswa se-Indonesia dengan tema Artificial Intelligence. Total hadiah puluhan juta rupiah!',
                'terms' => 'Peserta maksimal 3 orang per tim. Wajib membawa laptop sendiri. Keputusan juri bersifat mutlak.',
                'tickets' => [
                    ['ticket_type' => 'Reguler', 'price' => 50000, 'stock' => 50],
                ],
            ],
            [
                'user_id' => $panitiaNims[array_rand($panitiaNims)],
                'name' => 'Pameran Startup & Inovasi Digital',
                'category_id' => $cats['Pameran'] ?? 1,
                'location' => 'Nagoya Hill Mall, Batam',
                'date_start' => '2026-09-05',
                'date_end' => '2026-09-05',
                'time_start' => '10:00:00',
                'time_end' => '21:00:00',
                'description' => 'Pameran startup digital terbesar di Batam. Menampilkan inovasi terbaru dari 50+ startup lokal dan nasional. Ada talkshow, networking, dan demo produk.',
                'terms' => 'Tiket berlaku sepanjang hari. Dilarang membawa makanan dari luar. Parkir tersedia di basement mall.',
                'tickets' => [
                    ['ticket_type' => 'Reguler', 'price' => 20000, 'stock' => 1000],
                    ['ticket_type' => 'VIP', 'price' => 100000, 'stock' => 100],
                ],
            ],
        ];

        foreach ($dummies as $i => $data) {
            $ticketsData = $data['tickets'];
            unset($data['tickets']);

            $data['banner'] = $banners[$i % count($banners)];
            $data['organiser_description'] = 'Panitia penyelenggara event mahasiswa Politeknik Negeri Batam yang berkomitmen menghadirkan acara berkualitas.';
            $data['organiser_photo'] = null;
            $data['status'] = 'pending';

            $event = Event::create($data);

            foreach ($ticketsData as $ticket) {
                Ticket::create([
                    'user_id' => $event->user_id,
                    'event_id' => $event->id,
                    'ticket_type' => $ticket['ticket_type'],
                    'price' => $ticket['price'],
                    'stock' => $ticket['stock'],
                    'status' => 'Active',
                    'purchase_date' => Carbon::now(),
                ]);
            }

            $this->command->info("Created: {$event->name}");
        }
    }
}
