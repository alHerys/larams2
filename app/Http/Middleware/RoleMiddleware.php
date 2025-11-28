<?php

// filepath: app/Http/Middleware/RoleMiddleware.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * MIDDLEWARE: RoleMiddleware
 *
 * Middleware universal untuk mengecek role user
 * Bisa digunakan untuk teacher, student, atau role lainnya
 *
 * PENGGUNAAN DI ROUTE:
 * ->middleware('role:teacher')  // Hanya untuk guru
 * ->middleware('role:student')  // Hanya untuk murid
 *
 * KEUNGGULAN:
 * - Satu middleware untuk semua role (tidak perlu buat banyak middleware)
 * - Lebih fleksibel dan mudah di-maintain
 * - Mengikuti prinsip DRY (Don't Repeat Yourself)
 */
class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $role  - Role yang diizinkan ('teacher' atau 'student')
     */
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
