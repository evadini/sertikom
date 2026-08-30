<?php
// File: app/Http/Middleware/VerifyCsrfToken.php
// Tambahkan '/payment/notification' ke array $except

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     */
    protected $except = [
        '/payment/notification', // Webhook dari Midtrans (server-to-server)
    ];
}
