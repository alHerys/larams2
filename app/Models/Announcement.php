<?php

// filepath: app/Models/Announcement.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * MODEL: Announcement
 *
 * Merepresentasikan pengumuman yang dibuat oleh guru
 *
 * ATRIBUT:
 * - id: Primary key
 * - user_id: Foreign key ke guru pembuat
 * - title: Judul pengumuman
 * - content: Isi pengumuman
 *
 * RELASI:
 * - belongsTo User (guru pembuat)
 */
class Announcement extends Model
{
    use HasFactory;

    /**
     * Kolom yang boleh diisi secara mass assignment
     */
    protected $fillable = [
        'user_id',
        'title',
        'content',
    ];

    // =========================================
    // RELASI
    // =========================================

    /**
     * Announcement dimiliki oleh satu User (guru)
     *
     * PENGGUNAAN:
     * $announcement->user->name // Nama guru pembuat
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
