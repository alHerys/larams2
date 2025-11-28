<?php

// filepath: app/Models/Submission.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * MODEL: Submission
 *
 * Merepresentasikan pengumpulan tugas oleh murid
 *
 * ATRIBUT:
 * - id: Primary key
 * - user_id: Foreign key ke murid
 * - assignment_id: Foreign key ke tugas
 * - file_path: File jawaban
 * - score: Nilai (nullable)
 * - feedback: Komentar guru (nullable)
 *
 * RELASI:
 * - belongsTo User (murid pengumpul)
 * - belongsTo Assignment (tugas yang dikerjakan)
 */
class Submission extends Model
{
    use HasFactory;

    /**
     * Kolom yang boleh diisi secara mass assignment
     */
    protected $fillable = [
        'user_id',
        'assignment_id',
        'file_path',
        'score',
        'feedback',
    ];

    // =========================================
    // RELASI
    // =========================================

    /**
     * Submission dimiliki oleh satu User (murid)
     *
     * PENGGUNAAN:
     * $submission->user->name // Nama murid
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Submission terkait dengan satu Assignment
     *
     * PENGGUNAAN:
     * $submission->assignment->title // Judul tugas
     */
    public function assignment(): BelongsTo
    {
        return $this->belongsTo(Assignment::class);
    }

    // =========================================
    // HELPER METHODS
    // =========================================

    /**
     * Cek apakah submission sudah dinilai
     */
    public function isGraded(): bool
    {
        return $this->score !== null;
    }
}
