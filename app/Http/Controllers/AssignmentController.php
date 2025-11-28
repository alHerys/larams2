<?php

// filepath: app/Http/Controllers/AssignmentController.php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Submission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

/**
 * CONTROLLER: AssignmentController
 *
 * Menangani CRUD tugas
 *
 * ROUTES yang ditangani:
 * - GET    /teacher/assignments            -> index()
 * - GET    /teacher/assignments/create     -> create()
 * - POST   /teacher/assignments            -> store()
 * - GET    /teacher/assignments/{id}       -> show() (dengan daftar submission)
 * - DELETE /teacher/assignments/{id}       -> destroy()
 */
class AssignmentController extends Controller
{
    /**
     * Menampilkan daftar semua tugas (untuk guru)
     */
    public function index(): View
    {
        $assignments = Assignment::withCount('submissions')
            ->latest()
            ->paginate(10);

        return view('teacher.assignments.index', [
            'assignments' => $assignments,
        ]);
    }

    /**
     * Menampilkan form buat tugas baru
     */
    public function create(): View
    {
        return view('teacher.assignments.create');
    }

    /**
     * Menyimpan tugas baru
     */
    public function store(Request $request): RedirectResponse
    {
        // Validasi input
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'file_path' => 'nullable|file|mimes:pdf,doc,docx,zip,png,jpg|max:2048',
            'due_date' => 'nullable|date|after:today',
        ]);

        // Handle file upload jika ada
        $filePath = null;
        if ($request->hasFile('file_path')) {
            $filePath = $request->file('file_path')->store('assignments', 'public');
        }

        // Simpan ke database
        Assignment::create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'file_path' => $filePath,
            'due_date' => $validated['due_date'] ?? null,
        ]);

        return redirect()
            ->route('teacher.assignments.index')
            ->with('success', 'Tugas berhasil dibuat!');
    }

    /**
     * Menampilkan detail tugas beserta submission (untuk guru)
     */
    public function show(Assignment $assignment): View
    {
        // Eager load submissions dengan data user
        $assignment->load(['submissions.user']);

        return view('teacher.assignments.show', [
            'assignment' => $assignment,
        ]);
    }

    /**
     * Menghapus tugas
     */
    public function destroy(Assignment $assignment): RedirectResponse
    {
        // Hapus file jika ada
        if ($assignment->file_path) {
            Storage::disk('public')->delete($assignment->file_path);
        }

        // Hapus assignment (submissions akan terhapus otomatis karena cascade)
        $assignment->delete();

        return redirect()
            ->route('teacher.assignments.index')
            ->with('success', 'Tugas berhasil dihapus!');
    }
}
