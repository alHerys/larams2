<?php

// filepath: app/Http/Requests/Auth/RegisterRequest.php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

/**
 * FORM REQUEST: RegisterRequest
 *
 * Menangani validasi untuk registrasi user baru
 *
 * BEST PRACTICE:
 * - Pisahkan validasi dari controller
 * - Gunakan class Password untuk aturan password yang kuat
 * - Sediakan pesan error yang jelas dalam bahasa Indonesia
 */
class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * Return true karena registrasi terbuka untuk siapa saja
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * ATURAN VALIDASI:
     * - name: wajib, string, maksimal 255 karakter
     * - email: wajib, format email valid, unik di tabel users
     * - password: wajib, minimal 8 karakter, harus dikonfirmasi
     * - role: opsional, harus 'teacher' atau 'student'
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users,email',
            ],
            'password' => [
                'required',
                'string',
                Password::min(8),
                'confirmed', // Membutuhkan field password_confirmation
            ],
            'role' => [
                'sometimes', // Opsional
                'in:teacher,student',
            ],
        ];
    }

    /**
     * Get custom error messages for validation rules.
     *
     * BEST PRACTICE: Sediakan pesan error yang user-friendly
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama wajib diisi.',
            'name.max' => 'Nama maksimal 255 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'role.in' => 'Role harus teacher atau student.',
        ];
    }
}
