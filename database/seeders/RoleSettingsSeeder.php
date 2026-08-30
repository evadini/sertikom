<?php

namespace Database\Seeders;

use App\Models\RoleSetting;
use Illuminate\Database\Seeder;

class RoleSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin Settings
        $adminSettings = [
            [
                'role' => 'admin',
                'key' => 'can_manage_users',
                'value' => '1',
                'description' => 'Admin dapat mengelola pengguna',
                'is_active' => true,
            ],
            [
                'role' => 'admin',
                'key' => 'can_manage_roles',
                'value' => '1',
                'description' => 'Admin dapat mengelola role dan permission',
                'is_active' => true,
            ],
            [
                'role' => 'admin',
                'key' => 'can_approve_events',
                'value' => '1',
                'description' => 'Admin dapat menyetujui event',
                'is_active' => true,
            ],
            [
                'role' => 'admin',
                'key' => 'can_reject_events',
                'value' => '1',
                'description' => 'Admin dapat menolak event',
                'is_active' => true,
            ],
            [
                'role' => 'admin',
                'key' => 'can_view_analytics',
                'value' => '1',
                'description' => 'Admin dapat melihat analytics',
                'is_active' => true,
            ],
            [
                'role' => 'admin',
                'key' => 'can_manage_settings',
                'value' => '1',
                'description' => 'Admin dapat mengatur sistem settings',
                'is_active' => true,
            ],
        ];

        // Panitia (Organizer) Settings
        $panitiaSettings = [
            [
                'role' => 'panitia',
                'key' => 'can_create_event',
                'value' => '1',
                'description' => 'Panitia dapat membuat event',
                'is_active' => true,
            ],
            [
                'role' => 'panitia',
                'key' => 'can_edit_own_event',
                'value' => '1',
                'description' => 'Panitia dapat mengedit event miliknya',
                'is_active' => true,
            ],
            [
                'role' => 'panitia',
                'key' => 'can_delete_own_event',
                'value' => '1',
                'description' => 'Panitia dapat menghapus event miliknya',
                'is_active' => true,
            ],
            [
                'role' => 'panitia',
                'key' => 'can_manage_tickets',
                'value' => '1',
                'description' => 'Panitia dapat mengelola tiket event',
                'is_active' => true,
            ],
            [
                'role' => 'panitia',
                'key' => 'can_view_attendees',
                'value' => '1',
                'description' => 'Panitia dapat melihat daftar peserta',
                'is_active' => true,
            ],
            [
                'role' => 'panitia',
                'key' => 'can_manage_attendance',
                'value' => '1',
                'description' => 'Panitia dapat mengelola kehadiran peserta',
                'is_active' => true,
            ],
            [
                'role' => 'panitia',
                'key' => 'event_limit',
                'value' => 'unlimited',
                'description' => 'Batas jumlah event yang dapat dibuat',
                'is_active' => true,
            ],
        ];

        // Pembeli (Buyer/Customer) Settings
        $pembeliSettings = [
            [
                'role' => 'pembeli',
                'key' => 'can_view_events',
                'value' => '1',
                'description' => 'Pembeli dapat melihat event',
                'is_active' => true,
            ],
            [
                'role' => 'pembeli',
                'key' => 'can_buy_ticket',
                'value' => '1',
                'description' => 'Pembeli dapat membeli tiket',
                'is_active' => true,
            ],
            [
                'role' => 'pembeli',
                'key' => 'can_apply_organizer',
                'value' => '1',
                'description' => 'Pembeli dapat mengajukan menjadi organizer',
                'is_active' => true,
            ],
            [
                'role' => 'pembeli',
                'key' => 'can_view_my_tickets',
                'value' => '1',
                'description' => 'Pembeli dapat melihat tiket miliknya',
                'is_active' => true,
            ],
            [
                'role' => 'pembeli',
                'key' => 'can_update_profile',
                'value' => '1',
                'description' => 'Pembeli dapat mengupdate profil',
                'is_active' => true,
            ],
        ];

        // Insert all settings
        foreach ($adminSettings as $setting) {
            RoleSetting::updateOrCreate(
                ['role' => $setting['role'], 'key' => $setting['key']],
                $setting
            );
        }

        foreach ($panitiaSettings as $setting) {
            RoleSetting::updateOrCreate(
                ['role' => $setting['role'], 'key' => $setting['key']],
                $setting
            );
        }

        foreach ($pembeliSettings as $setting) {
            RoleSetting::updateOrCreate(
                ['role' => $setting['role'], 'key' => $setting['key']],
                $setting
            );
        }
    }
}
