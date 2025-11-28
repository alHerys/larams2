<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * MIGRATION: Membuat tabel announcements (pengumuman)
     *
     * RELASI:
     * - announcements belongsTo users (setiap pengumuman dibuat oleh satu guru)
     * - users hasMany announcements (satu guru bisa membuat banyak pengumuman)
     */
    public function up(): void
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();

            // Foreign key ke tabel users (guru yang membuat pengumuman)
            // constrained() = otomatis referensi ke users.id
            // onDelete('cascade') = jika user dihapus, pengumumannya juga terhapus
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->string('title');        // Judul pengumuman
            $table->text('content');        // Isi pengumuman

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
