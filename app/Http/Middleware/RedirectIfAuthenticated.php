<?php

// filepath: app/Http/Middleware/RedirectIfAuthenticated.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * MIDDLEWARE: RedirectIfAuthenticated
 *
 * Redirect user yang sudah login jika mencoba akses
 * halaman login atau register
 *
 * PENGGUNAAN:
 * ->middleware('guest') // Di route login dan register
 *
 * BEST PRACTICE:
 * - User yang sudah login tidak perlu melihat halaman login
 * - Redirect ke dashboard sesuai role
 */
class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::user();

                // Redirect berdasarkan role
                if ($user->role === 'teacher') {
                    return redirect()->route('teacher.dashboard');
                }

                return redirect()->route('student.dashboard');
            }
        }

        return $next($request);
    }
}
