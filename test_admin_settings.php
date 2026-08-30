<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Test routing - check if route exists
$routes = app('router')->getRoutes();
$route = $routes->getByName('admin.settings.update-profile');
echo 'Route found: ' . ($route ? 'YES' : 'NO') . PHP_EOL;
if ($route) {
    echo 'Methods: ' . implode(', ', $route->methods()) . PHP_EOL;
    echo 'URI: ' . $route->uri() . PHP_EOL;
}
