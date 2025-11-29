<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    /**
     * Announcement dimiliki oleh satu User (guru)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
