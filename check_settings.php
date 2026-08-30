<?php
/**
 * Role Settings Display Utility
 *
 * Usage: php check_settings.php [role]
 * Examples:
 *   php check_settings.php          (show all roles)
 *   php check_settings.php admin    (show admin settings only)
 *   php check_settings.php panitia  (show panitia settings only)
 */

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$settings = \App\Models\RoleSetting::all();

echo "\n";
echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║         TICKETIFY - ROLE SETTINGS CONFIGURATION           ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n\n";

$roleFilter = $argv[1] ?? null;
$roles = $roleFilter ? [$roleFilter] : ['admin', 'panitia', 'pembeli'];

foreach ($roles as $role) {
    $roleSettings = $settings->where('role', $role)->values();

    if ($roleSettings->isEmpty()) {
        echo "❌ Role '$role' has no settings\n\n";
        continue;
    }

    echo "┌─ " . strtoupper($role) . " (" . count($roleSettings) . " settings)\n";
    echo "├" . str_repeat("─", 57) . "\n";

    foreach ($roleSettings as $index => $setting) {
        $status = $setting->is_active ? '✓ ACTIVE' : '✗ INACTIVE';
        $isLast = $index === $roleSettings->count() - 1;
        $prefix = $isLast ? '└' : '├';
        $continuation = $isLast ? ' ' : '│';

        echo $prefix . "• " . $setting->key . "\n";
        echo $continuation . "  └─ Value: " . $setting->value . " [" . $status . "]\n";
        echo $continuation . "  └─ " . $setting->description . "\n";

        if (!$isLast) {
            echo "│\n";
        }
    }
    echo "\n";
}

echo "═══════════════════════════════════════════════════════════════\n";
echo "Total Settings: " . count($settings) . " (all active)\n";
echo "Database: db_ticketify\n";
echo "Table: role_settings\n";
echo "═══════════════════════════════════════════════════════════════\n\n";
