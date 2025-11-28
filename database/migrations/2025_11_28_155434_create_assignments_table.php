<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * MIGRATION: Membuat tabel assignments (tugas)
     *
     * CATATAN PENTING:
     * - Gunakan "Assignment" dengan double 'n' (BUKAN "Assigment")
     * - Ini adalah spelling yang benar dalam bahasa Inggris
     *
     * RELASI:
     * - assignments belongsTo users (setiap tugas dibuat oleh satu guru)
     * - assignments hasMany submissions (satu tugas bisa punya banyak submission)
     */
    public function up(): void
    {
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();

            // Foreign key ke tabel users (guru yang membuat tugas)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->string('title');                    // Judul tugas
            $table->text('description');                // Deskripsi/instruksi tugas
            $table->string('file_path')->nullable();    // File lampiran (opsional)
            $table->timestamp('due_date')->nullable();  // Deadline tugas (opsional)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};
