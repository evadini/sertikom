<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleSetting extends Model
{
    protected $table = 'role_settings';

    protected $fillable = [
        'role',
        'key',
        'value',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Scope: Get settings by role
     */
    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    /**
     * Scope: Get active settings only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get setting value by key and role
     */
    public static function getSetting($role, $key, $default = null)
    {
        $setting = self::where('role', $role)
            ->where('key', $key)
            ->where('is_active', true)
            ->first();

        return $setting ? $setting->value : $default;
    }

    /**
     * Get all settings for a role
     */
    public static function getSettingsByRole($role)
    {
        return self::byRole($role)
            ->active()
            ->pluck('value', 'key');
    }
}
