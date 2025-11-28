<?php

// filepath: app/Models/Assignment.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * MODEL: Assignment
 *
 * Merepresentasikan tugas yang dibuat oleh guru
 *
 * CATATAN: Spelling yang benar adalah "Assignment" (double 'n')
 *
 * ATRIBUT:
 * - id: Primary key
 * - user_id: Foreign key ke guru pembuat
 * - title: Judul tugas
 * - description: Deskripsi/instruksi
 * - file_path: File lampiran (nullable)
 * - due_date: Deadline (nullable)
 *
 * RELASI:
 * - belongsTo User (guru pembuat)
 * - hasMany Submission (pengumpulan dari murid)
 */
class Assignment extends Model
{
    use HasFactory;

    /**
     * Kolom yang boleh diisi secara mass assignment
     */
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'file_path',
        'due_date',
    ];

    /**
     * Casting tipe data
     */
    protected function casts(): array
    {
        return [
            'due_date' => 'datetime',
        ];
    }

    // =========================================
    // RELASI
    // =========================================

    /**
     * Assignment dimiliki oleh satu User (guru)
     *
     * PENGGUNAAN:
     * $assignment->user->name // Nama guru pembuat
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Assignment memiliki banyak Submission
     *
     * PENGGUNAAN:
     * $assignment->submissions // Collection of Submission
     * $assignment->submissions()->count() // Jumlah yang sudah submit
     */
    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }
}
