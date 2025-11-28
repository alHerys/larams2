<?php

// filepath: app/Http/Controllers/SubmissionController.php

namespace App\Http\Controllers;

use App\Models\Submission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * CONTROLLER: SubmissionController
 *
 * Menangani pengumpulan dan penilaian tugas
 *
 * ROUTES yang ditangani:
 * - POST /student/submissions              -> store() (murid submit)
 * - PUT  /teacher/submissions/{id}/grade   -> grade() (guru memberi nilai)
 */
class SubmissionController extends Controller
{
    /**
     * Murid mengumpulkan tugas
     */
    public function store(Request $request): RedirectResponse
    {
        // Validasi input
        $validated = $request->validate([
            'assignment_id' => 'required|exists:assignments,id',
            'file_path' => 'required|file|mimes:pdf,doc,docx,zip,jpg,png|max:5120',
        ]);

        $userId = Auth::id();
        $assignmentId = $validated['assignment_id'];

        // Cek apakah sudah pernah submit
        $existingSubmission = Submission::where('user_id', $userId)
            ->where('assignment_id', $assignmentId)
            ->exists();

        if ($existingSubmission) {
            return redirect()
                ->back()
                ->with('error', 'Anda sudah mengumpulkan tugas ini sebelumnya.');
        }

        // Upload file
        $filePath = $request->file('file_path')->store('submissions', 'public');

        // Simpan ke database
        Submission::create([
            'user_id' => $userId,
            'assignment_id' => $assignmentId,
            'file_path' => $filePath,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Tugas berhasil dikumpulkan!');
    }

    /**
     * Guru memberi nilai pada submission
     */
    public function grade(Request $request, Submission $submission): RedirectResponse
    {
        // Validasi input
        $validated = $request->validate([
            'score' => 'required|integer|min:0|max:100',
            'feedback' => 'nullable|string|max:1000',
        ]);

        // Update submission
        $submission->update([
            'score' => $validated['score'],
            'feedback' => $validated['feedback'] ?? null,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Nilai berhasil disimpan!');
    }
}
