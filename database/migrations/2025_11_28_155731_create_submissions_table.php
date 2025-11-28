<?php

// filepath: database/migrations/xxxx_xx_xx_create_submissions_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * MIGRATION: Membuat tabel submissions (pengumpulan tugas)
     *
     * CATATAN:
     * - Nama tabel 'submissions' lebih deskriptif daripada 'tasks'
     * - 'tasks' bisa membingungkan karena mirip dengan 'assignments'
     *
     * RELASI:
     * - submissions belongsTo users (setiap submission dari satu murid)
     * - submissions belongsTo assignments (setiap submission untuk satu tugas)
     */
    public function up(): void
    {
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();

            // Foreign key ke tabel users (murid yang mengumpulkan)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Foreign key ke tabel assignments (tugas yang dikerjakan)
            $table->foreignId('assignment_id')->constrained()->onDelete('cascade');

            $table->string('file_path');                // File jawaban yang diupload
            $table->integer('score')->nullable();       // Nilai (null = belum dinilai)
            $table->text('feedback')->nullable();       // Feedback dari guru (opsional)
            $table->timestamps();

            // CONSTRAINT: Satu murid hanya bisa submit sekali per tugas
            // Ini mencegah duplikasi di level database
            $table->unique(['user_id', 'assignment_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};
