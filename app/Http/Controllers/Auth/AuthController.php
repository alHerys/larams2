<?php

// filepath: app/Http/Controllers/Auth/AuthController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * CONTROLLER: AuthController
 *
 * Menangani semua proses autentikasi:
 * - Register (tampilkan form & proses)
 * - Login (tampilkan form & proses)
 * - Logout
 *
 * BEST PRACTICE:
 * - Gunakan Form Request untuk validasi
 * - Gunakan type hints untuk parameter dan return type
 * - Pisahkan tampilan form (create) dan proses (store)
 * - Redirect berdasarkan role setelah login
 */
class AuthController extends Controller
{
    // =========================================
    // REGISTER
    // =========================================

    /**
     * Menampilkan halaman form registrasi
     *
     * HTTP Method: GET
     * URL: /register
     */
    public function showRegisterForm(): View
    {
        return view('auth.register');
    }

    /**
     * Memproses registrasi user baru
     *
     * HTTP Method: POST
     * URL: /register
     *
     * FLOW:
     * 1. Validasi input (dilakukan otomatis oleh RegisterRequest)
     * 2. Buat user baru di database
     * 3. Login user yang baru dibuat
     * 4. Redirect ke dashboard sesuai role
     *
     * @param  RegisterRequest  $request  - Form Request dengan validasi
     */
    public function register(RegisterRequest $request): RedirectResponse
    {
        // Data sudah tervalidasi oleh RegisterRequest
        // Password otomatis di-hash karena cast 'hashed' di Model
        $user = User::create([
            'name' => $request->validated('name'),
            'email' => $request->validated('email'),
            'password' => $request->validated('password'),
            'role' => $request->validated('role', 'student'), // Default: student
        ]);

        // Login user yang baru dibuat
        Auth::login($user);

        // Redirect ke dashboard sesuai role
        return $this->redirectToDashboard($user);
    }

    // =========================================
    // LOGIN
    // =========================================

    /**
     * Menampilkan halaman form login
     *
     * HTTP Method: GET
     * URL: /login
     */
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    /**
     * Memproses login user
     *
     * HTTP Method: POST
     * URL: /login
     *
     * FLOW:
     * 1. Validasi input (dilakukan oleh LoginRequest)
     * 2. Autentikasi credentials (dilakukan oleh LoginRequest::authenticate())
     * 3. Regenerate session untuk keamanan
     * 4. Redirect ke dashboard sesuai role
     *
     * @param  LoginRequest  $request  - Form Request dengan validasi & autentikasi
     */
    public function login(LoginRequest $request): RedirectResponse
    {
        // Proses autentikasi dilakukan di LoginRequest
        $request->authenticate();

        // Regenerate session ID untuk mencegah session fixation attack
        $request->session()->regenerate();

        // Redirect ke dashboard sesuai role
        return $this->redirectToDashboard(Auth::user());
    }

    // =========================================
    // LOGOUT
    // =========================================

    /**
     * Memproses logout user
     *
     * HTTP Method: POST
     * URL: /logout
     *
     * FLOW:
     * 1. Logout dari guard 'web'
     * 2. Invalidate session (hapus semua data session)
     * 3. Regenerate CSRF token untuk keamanan
     * 4. Redirect ke halaman utama
     *
     * BEST PRACTICE:
     * - Logout harus menggunakan POST, bukan GET
     * - Invalidate session setelah logout
     * - Regenerate CSRF token
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    // =========================================
    // HELPER METHODS
    // =========================================

    /**
     * Redirect user ke dashboard berdasarkan role
     *
     * BEST PRACTICE:
     * - Gunakan helper method untuk logic yang dipakai berulang
     * - Gunakan named routes, bukan hardcoded URL
     */
    protected function redirectToDashboard(User $user): RedirectResponse
    {
        if ($user->isTeacher()) {
            return redirect()->intended(route('teacher.dashboard'));
        }

        return redirect()->intended(route('student.dashboard'));
    }
}
