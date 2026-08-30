<?php

namespace App\Traits;

use App\Models\RoleSetting;

/**
 * Trait HasRoleSettings
 *
 * Provides role-based permission checking for models.
 *
 * @property string $role The user's role (should be defined in the using class)
 */
trait HasRoleSettings
{
    /**
     * Check if user role has a specific permission/setting
     *
     * @param string $key The setting key to check
     * @param mixed $default Default value if setting not found
     * @return mixed
     */
    public function getPermission($key, $default = false)
    {
        if (!isset($this->role)) {
            return $default;
        }

        $setting = RoleSetting::where('role', $this->role)
            ->where('key', $key)
            ->where('is_active', true)
            ->first();

        return $setting ? $setting->value : $default;
    }

    /**
     * Check if user role can perform an action
     *
     * @param string $action The action key to check
     * @return bool
     */
    public function hasRolePermission($action)
    {
        if (!isset($this->role)) {
            return false;
        }

        $permission = RoleSetting::where('role', $this->role)
            ->where('key', $action)
            ->where('is_active', true)
            ->first();

        return $permission && ($permission->value == '1' || $permission->value === true);
    }

    /**
     * Get all settings for user's role
     *
     * @return \Illuminate\Support\Collection
     */
    public function getRoleSettings()
    {
        if (!isset($this->role)) {
            return collect();
        }

        return RoleSetting::where('role', $this->role)
            ->where('is_active', true)
            ->pluck('value', 'key');
    }

    /**
     * Check if user's role has specific feature
     *
     * @param string $feature Feature name to check
     * @return bool
     */
    public function hasFeature($feature)
    {
        return $this->hasRolePermission('can_' . $feature);
    }
}
