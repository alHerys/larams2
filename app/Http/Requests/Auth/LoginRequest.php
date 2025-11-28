<?php

// filepath: app/Http/Requests/Auth/LoginRequest.php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

/**
 * FORM REQUEST: LoginRequest
 *
 * Menangani validasi dan proses autentikasi login
 *
 * FITUR:
 * - Validasi input email dan password
 * - Rate limiting untuk mencegah brute force attack
 * - Custom error messages dalam bahasa Indonesia
 *
 * BEST PRACTICE:
 * - Implementasi rate limiting untuk keamanan
 * - Pisahkan logic autentikasi dari controller
 */
class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'string',
                'email',
            ],
            'password' => [
                'required',
                'string',
            ],
        ];
    }

    /**
     * Custom error messages
     */
    public function messages(): array
    {
        return [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * FLOW:
     * 1. Cek apakah sudah terlalu banyak percobaan gagal (rate limit)
     * 2. Coba autentikasi dengan email dan password
     * 3. Jika gagal, tambah counter rate limit
     * 4. Jika berhasil, reset counter rate limit
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        // Coba login dengan credentials yang diberikan
        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            // Jika gagal, tambah hit ke rate limiter
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => __('Email atau password salah.'),
            ]);
        }

        // Jika berhasil, reset rate limiter
        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * Mencegah brute force attack dengan membatasi
     * maksimal 5 percobaan login gagal per menit
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => __('Terlalu banyak percobaan login. Silakan coba lagi dalam :seconds detik.', [
                'seconds' => $seconds,
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     *
     * Key dibuat dari kombinasi email + IP address
     * untuk mencegah bypass dengan ganti email
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}
