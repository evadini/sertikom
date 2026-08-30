# Role Settings Configuration Documentation

## Overview
Role settings telah diaktifkan untuk semua role dalam aplikasi Ticketify: **Admin**, **Panitia**, dan **Pembeli**.

Database table `role_settings` menyimpan konfigurasi permission untuk setiap role dengan status aktivasi.

---

## Database Structure

### Table: `role_settings`
| Column | Type | Description |
|--------|------|-------------|
| id | INT (PK) | Primary Key |
| role | ENUM | Role: admin, panitia, pembeli |
| key | VARCHAR | Setting key (unique per role) |
| value | TEXT | Setting value |
| description | TEXT | Human-readable description |
| is_active | BOOLEAN | Status aktivasi setting |
| created_at | TIMESTAMP | Created timestamp |
| updated_at | TIMESTAMP | Updated timestamp |

---

## Available Settings

### ADMIN (6 settings) ✓ AKTIF
- **can_manage_users**: Mengelola pengguna
- **can_manage_roles**: Mengelola role dan permission
- **can_approve_events**: Menyetujui event
- **can_reject_events**: Menolak event
- **can_view_analytics**: Melihat analytics
- **can_manage_settings**: Mengatur sistem settings

### PANITIA (7 settings) ✓ AKTIF
- **can_create_event**: Membuat event
- **can_edit_own_event**: Mengedit event miliknya
- **can_delete_own_event**: Menghapus event miliknya
- **can_manage_tickets**: Mengelola tiket event
- **can_view_attendees**: Melihat daftar peserta
- **can_manage_attendance**: Mengelola kehadiran peserta
- **event_limit**: Batas jumlah event (unlimited)

### PEMBELI (5 settings) ✓ AKTIF
- **can_view_events**: Melihat event
- **can_buy_ticket**: Membeli tiket
- **can_apply_organizer**: Mengajukan menjadi organizer
- **can_view_my_tickets**: Melihat tiket miliknya
- **can_update_profile**: Mengupdate profil

---

## Usage Examples

### 1. Check User Permission (Controller)
```php
<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class EventController extends Controller
{
    public function store(Request $request)
    {
        // Check if user has permission to create event
        if (!Auth::user()->can('can_create_event')) {
            return response()->json(['error' => 'Tidak memiliki akses'], 403);
        }
        
        // Create event logic...
    }
}
```

### 2. Check Feature in View (Blade)
```blade
@if(Auth::user()->hasFeature('create_event'))
    <button class="btn btn-primary">Buat Event Baru</button>
@endif
```

### 3. Get Permission Value (Controller)
```php
<?php
$canCreateEvent = Auth::user()->hasPermission('can_create_event', false);
$eventLimit = Auth::user()->hasPermission('event_limit', 'unlimited');
```

### 4. Get All Role Settings
```php
<?php
$settings = Auth::user()->getRoleSettings();
// Returns Collection: { 'can_create_event' => '1', 'event_limit' => 'unlimited', ... }
```

### 5. Direct Model Usage
```php
<?php
use App\Models\RoleSetting;

// Get specific setting
$setting = RoleSetting::getSetting('panitia', 'can_create_event', false);

// Get all settings for role
$panitiaSettings = RoleSetting::getSettingsByRole('panitia');

// Query builder approach
$activeSettings = RoleSetting::where('role', 'pembeli')
    ->where('is_active', true)
    ->get();
```

---

## Role Settings Model Methods

### Static Methods
```php
RoleSetting::getSetting($role, $key, $default = null)
// Returns specific setting value

RoleSetting::getSettingsByRole($role)
// Returns Collection of all settings for role as key => value pairs
```

### Scopes
```php
RoleSetting::byRole('admin')->get()
RoleSetting::active()->get()
RoleSetting::byRole('panitia')->active()->get()
```

---

## User Model Helper Methods

### Methods Available (via HasRoleSettings Trait)
```php
// Check if has permission
$user->hasPermission('can_create_event', false)

// Simple can() check
$user->can('can_approve_events')  // Returns true/false

// Get all role settings
$user->getRoleSettings()

// Check feature (auto-prefixes with 'can_')
$user->hasFeature('create_event')  // Same as can('can_create_event')
```

---

## Middleware Protection Example

### Create Role Middleware
```php
<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRolePermission
{
    public function handle(Request $request, Closure $next, $permission)
    {
        if (!auth()->check() || !auth()->user()->can($permission)) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
```

### Register in Kernel
```php
// app/Http/Kernel.php
protected $routeMiddleware = [
    // ... other middleware
    'role.permission' => \App\Http\Middleware\CheckRolePermission::class,
];
```

### Use in Routes
```php
Route::post('/events', [EventController::class, 'store'])
    ->middleware('role.permission:can_create_event');
```

---

## Management

### Add New Setting
```php
<?php
use App\Models\RoleSetting;

RoleSetting::create([
    'role' => 'panitia',
    'key' => 'can_export_reports',
    'value' => '1',
    'description' => 'Panitia dapat export laporan event',
    'is_active' => true,
]);
```

### Update Setting
```php
<?php
use App\Models\RoleSetting;

RoleSetting::where('role', 'pembeli')
    ->where('key', 'can_buy_ticket')
    ->update(['is_active' => false]);
```

### Deactivate Setting
```php
<?php
RoleSetting::where('role', 'pembeli')
    ->where('key', 'can_apply_organizer')
    ->update(['is_active' => false]);
```

### Get Inactive Settings
```php
<?php
$inactive = RoleSetting::where('is_active', false)->get();
```

---

## Files Created

1. **Migration**: `database/migrations/2026_05_22_000000_create_role_settings_table.php`
   - Creates role_settings table

2. **Model**: `app/Models/RoleSetting.php`
   - RoleSetting model with helpful methods

3. **Seeder**: `database/seeders/RoleSettingsSeeder.php`
   - Seeds default settings for all roles

4. **Trait**: `app/Traits/HasRoleSettings.php`
   - Provides helper methods for permission checking

5. **Updated**: `app/Models/User.php`
   - Added HasRoleSettings trait

---

## Testing

Run check settings display:
```bash
php check_settings.php
```

Query from Tinker:
```bash
php artisan tinker
> App\Models\RoleSetting::where('role', 'admin')->get()
> Auth::user()->getRoleSettings()
```

---

## Notes

- Semua 18 settings telah **AKTIF** secara default
- Settings dapat diaktifkan/dinonaktifkan kapan saja
- Gunakan migration untuk perubahan struktur
- Gunakan seeder untuk perubahan data default
- Trigger ke cache jika perlu untuk performa lebih baik

---

## Troubleshooting

**Settings tidak muncul?**
- Pastikan migration sudah dijalankan: `php artisan migrate`
- Pastikan seeder sudah dijalankan: `php artisan db:seed --class=RoleSettingsSeeder`

**Permission always false?**
- Pastikan `is_active = true` di database
- Pastikan value setting adalah `'1'` (string) untuk boolean checks

**Query performance issues?**
- Tambahkan query caching ke RoleSetting model
- Atau load settings saat authentication

---

**Created**: May 22, 2026  
**Version**: 1.0  
**Status**: ✓ Production Ready
