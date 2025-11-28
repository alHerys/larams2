<?php

// filepath: app/Http/Controllers/AnnouncementController.php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * CONTROLLER: AnnouncementController
 *
 * Menangani CRUD pengumuman (hanya untuk guru)
 *
 * ROUTES yang ditangani:
 * - GET    /teacher/announcements          -> index()
 * - POST   /teacher/announcements          -> store()
 * - DELETE /teacher/announcements/{id}     -> destroy()
 */
class AnnouncementController extends Controller
{
    /**
     * Menampilkan halaman pengumuman dengan form dan daftar
     */
    public function index(): View
    {
        $announcements = Announcement::with('user')
            ->latest()
            ->paginate(10);

        return view('teacher.announcements.index', [
            'announcements' => $announcements,
        ]);
    }

    /**
     * Menyimpan pengumuman baru
     */
    public function store(Request $request): RedirectResponse
    {
        // Validasi input
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        // Simpan ke database
        Announcement::create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'content' => $validated['content'],
        ]);

        return redirect()
            ->route('teacher.announcements.index')
            ->with('success', 'Pengumuman berhasil dibuat!');
    }

    /**
     * Menghapus pengumuman
     */
    public function destroy(Announcement $announcement): RedirectResponse
    {
        $announcement->delete();

        return redirect()
            ->route('teacher.announcements.index')
            ->with('success', 'Pengumuman berhasil dihapus!');
    }
}
