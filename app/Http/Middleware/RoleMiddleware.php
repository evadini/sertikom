<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // 1. Pastikan user sudah login
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // 2. Filter kecocokan role akun
        if (auth()->user()->role !== $role) {
            abort(403, 'Akses ditolak. Halaman ini bukan untuk role Anda.');
        }

        return $next($request);
    }
}
