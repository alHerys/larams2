<?php

// filepath: database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\Assignment;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        // Buat akun guru
        $teacher = User::create([
            'name' => 'Guru Demo',
            'email' => 'guru@example.com',
            'password' => 'password', // Akan otomatis di-hash
            'role' => 'teacher',
        ]);

        // Buat akun murid
        User::create([
            'name' => 'Murid Demo',
            'email' => 'murid@example.com',
            'password' => 'password',
            'role' => 'student',
        ]);

        // Buat 5 murid tambahan dengan factory
        User::factory()->count(5)->create([
            'role' => 'student',
        ]);

        // =========================================
        // BUAT PENGUMUMAN CONTOH
        // =========================================
        Announcement::create([
            'user_id' => $teacher->id,
            'title' => 'Selamat Datang di LaraMS',
            'content' => 'Selamat datang di Learning Management System. Silakan cek tugas-tugas yang tersedia.',
        ]);

        // =========================================
        // BUAT TUGAS CONTOH
        // =========================================
        Assignment::create([
            'user_id' => $teacher->id,
            'title' => 'Tugas 1 - Pengantar Laravel',
            'description' => 'Buatlah aplikasi Laravel sederhana yang menampilkan "Hello World".',
        ]);

        Assignment::create([
            'user_id' => $teacher->id,
            'title' => 'Tugas 2 - CRUD dengan Eloquent',
            'description' => 'Implementasikan operasi CRUD menggunakan Eloquent ORM.',
        ]);
    }
}
