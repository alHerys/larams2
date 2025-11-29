<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Cek apakah user sudah login
        if (! Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        // Cek apakah role user sesuai dengan yang diizinkan
        if (Auth::user()->role !== $role) {
            // Jika tidak sesuai, tampilkan error 403
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        // Jika lolos semua pengecekan, lanjutkan request
        return $next($request);
    }
}
