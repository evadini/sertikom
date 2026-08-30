<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UkmSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ukms = [
            ['nama_ukm' => 'HMTI'],
            ['nama_ukm' => 'PAHAM (Pecinta Alam & Hijau Almamater)'],
            ['nama_ukm' => 'KORPS (Kerohanian Islam)'],
            ['nama_ukm' => 'Polibatam English Club (PEC)'],
            ['nama_ukm' => 'UKM Olahraga (Futsal, Basket, Badminton)'],
            ['nama_ukm' => 'UKM Seni & Budaya (Kancil, Tari, Musik)'],
            ['nama_ukm' => 'Polibatam Rescue'],
            ['nama_ukm' => 'Resimen Mahasiswa (Menwa)'],
            ['nama_ukm' => 'UKM Wirausaha / Pro-Preneur'],
        ];

        DB::table('ukms')->insert($ukms);
    }
}
