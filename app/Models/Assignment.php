<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    /**
     * Assignment dimiliki oleh satu User (guru)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Assignment memiliki banyak Submission
     */
    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }
}
