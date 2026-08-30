# 🎯 Role Settings - Quick Reference

## ⚡ Most Common Usage

### Check If User Can Do Something
```php
// In Controller
if (Auth::user()->can('can_create_event')) {
    // Let them create event
}

// In Blade
@if(Auth::user()->hasFeature('create_event'))
    <button>Create Event</button>
@endif
```

### Get Permission Value
```php
$canCreate = Auth::user()->can('can_create_event');           // Boolean
$eventLimit = Auth::user()->hasPermission('event_limit');    // Value or null
$settings = Auth::user()->getRoleSettings();                 // All settings for role
```

---

## 📋 Available Permissions

### For ADMIN Role
```
can_manage_users        can_manage_roles        can_approve_events
can_reject_events       can_view_analytics      can_manage_settings
```

### For PANITIA Role
```
can_create_event        can_edit_own_event      can_delete_own_event
can_manage_tickets      can_view_attendees      can_manage_attendance
event_limit = unlimited
```

### For PEMBELI Role
```
can_view_events         can_buy_ticket          can_apply_organizer
can_view_my_tickets     can_update_profile
```

---

## 🛠️ Administration

### Check All Settings
```bash
php check_settings.php
php check_settings.php admin
```

### Add New Setting
```php
use App\Models\RoleSetting;

RoleSetting::create([
    'role' => 'admin',
    'key' => 'can_send_emails',
    'value' => '1',
    'description' => 'Can send emails',
    'is_active' => true,
]);
```

### Disable Feature
```php
RoleSetting::where('role', 'pembeli')
    ->where('key', 'can_apply_organizer')
    ->update(['is_active' => false]);
```

### Update Permission Value
```php
RoleSetting::where('role', 'panitia')
    ->where('key', 'event_limit')
    ->update(['value' => '10']);
```

---

## 📖 Query Examples

### Get Specific Setting
```php
RoleSetting::getSetting('admin', 'can_manage_users', false);
```

### Get All Settings for Role
```php
RoleSetting::getSettingsByRole('panitia');
// Returns: Collection of key => value
```

### Get Active Settings Only
```php
RoleSetting::where('role', 'admin')->where('is_active', true)->get();
RoleSetting::byRole('admin')->active()->get();  // Using scopes
```

### Filter by Role
```php
RoleSetting::where('role', 'pembeli')->get();
RoleSetting::byRole('pembeli')->get();  // Using scope
```

---

## 🚀 Middleware Example

```php
// In Kernel.php
protected $routeMiddleware = [
    'check.permission' => \App\Http\Middleware\CheckPermission::class,
];

// In your middleware
public function handle($request, Closure $next, $permission)
{
    if (!auth()->user()->can($permission)) {
        abort(403);
    }
    return $next($request);
}

// Use in routes
Route::post('/events', [EventController::class, 'store'])
    ->middleware('check.permission:can_create_event');
```

---

## 📊 Database Info

**Table**: `role_settings`  
**Database**: `db_ticketify`  
**Total Records**: 18  
**Status**: All ACTIVE ✓

### Table Structure
```
id (PK)
role (ENUM: admin, panitia, pembeli)
key (VARCHAR, unique per role)
value (TEXT)
description (TEXT)
is_active (BOOLEAN)
timestamps
```

---

## 🔑 Key Methods

### From HasRoleSettings Trait
```php
$user->can('permission_key')                    // Boolean check
$user->hasPermission('key', $default)           // Get value
$user->getRoleSettings()                        // All settings
$user->hasFeature('feature_name')               // Feature check
```

### From RoleSetting Model
```php
RoleSetting::getSetting($role, $key, $default)
RoleSetting::getSettingsByRole($role)
RoleSetting::byRole($role)
RoleSetting::active()
```

---

## ✅ Verification

```bash
# Display all settings
php check_settings.php

# Display admin settings
php check_settings.php admin

# Check count in tinker
php artisan tinker
> App\Models\RoleSetting::count()
```

---

## 🎓 Examples

### Controller
```php
public function store(Request $request)
{
    if (!Auth::user()->hasFeature('create_event')) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }
    // Process...
}
```

### Blade
```blade
@if(Auth::user()->can('can_manage_users'))
    <div class="admin-panel">...</div>
@endif
```

### Middleware
```php
if (!Auth::user()->hasFeature('approve_events')) {
    abort(403, 'You cannot approve events');
}
```

---

## 💾 File Locations

```
app/Models/RoleSetting.php              ← Model
app/Traits/HasRoleSettings.php          ← Helper methods
database/migrations/...create_role_settings_table.php
database/seeders/RoleSettingsSeeder.php
check_settings.php                      ← Verification script
```

---

## 🚨 Troubleshooting

| Issue | Solution |
|-------|----------|
| Permission returns false | Check `is_active = true` in DB |
| "Call undefined method" | Add `use HasRoleSettings;` in User model |
| Settings missing | Run `php check_settings.php` to verify |
| Cache issues | Run `php artisan cache:clear` |

---

**Version**: 1.0  
**Last Updated**: May 22, 2026  
**Status**: ✅ Production Ready
