<?php

// filepath: app/Models/User.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * MODEL: User
 *
 * Merepresentasikan pengguna sistem (guru atau murid)
 *
 * ATRIBUT:
 * - id: Primary key
 * - name: Nama lengkap
 * - email: Email (unique)
 * - role: 'teacher' atau 'student'
 * - password: Password terenkripsi
 *
 * RELASI:
 * - hasMany Announcement (jika teacher)
 * - hasMany Assignment (jika teacher)
 * - hasMany Submission (jika student)
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Kolom yang boleh diisi secara mass assignment
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * Kolom yang disembunyikan saat serialisasi (JSON)
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting tipe data
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // =========================================
    // RELASI
    // =========================================

    /**
     * User (teacher) memiliki banyak Announcement
     *
     * PENGGUNAAN:
     * $teacher->announcements // Collection of Announcement
     */
    public function announcements(): HasMany
    {
        return $this->hasMany(Announcement::class);
    }

    /**
     * User (teacher) memiliki banyak Assignment
     *
     * PENGGUNAAN:
     * $teacher->assignments // Collection of Assignment
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class);
    }

    /**
     * User (student) memiliki banyak Submission
     *
     * PENGGUNAAN:
     * $student->submissions // Collection of Submission
     */
    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }

    // =========================================
    // HELPER METHODS
    // =========================================

    /**
     * Cek apakah user adalah guru
     *
     * PENGGUNAAN:
     * if ($user->isTeacher()) { ... }
     */
    public function isTeacher(): bool
    {
        return $this->role === 'teacher';
    }

    /**
     * Cek apakah user adalah murid
     *
     * PENGGUNAAN:
     * if ($user->isStudent()) { ... }
     */
    public function isStudent(): bool
    {
        return $this->role === 'student';
    }
}
