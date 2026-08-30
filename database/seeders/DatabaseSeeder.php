<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin User',
            'nim' => '3312501067',
            'email' => 'admin@ticketify.test',
            'password' => bcrypt('123456789'),
            'role' => 'admin',
        ]);

        User::factory()->create([
            'name' => 'Panitia User',
            'nim' => '3312501066',
            'email' => 'panitia@ticketify.test',
            'password' => bcrypt('123456789'),
            'role' => 'panitia',
        ]);

        User::factory()->create([
            'name' => 'Pembeli User',
            'nim' => '3312501065',
            'email' => 'pembeli@ticketify.test',
            'password' => bcrypt('123456789'),
            'role' => 'pembeli',
        ]);

        $this->call([
            UkmSeeder::class,
            RoleSettingsSeeder::class,
            CategorySeeder::class,
            DummyPendingEventsSeeder::class,
            PanitiaProfileSeeder::class,
        ]);
    }
}
