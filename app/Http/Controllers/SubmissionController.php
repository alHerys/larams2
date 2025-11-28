<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubmissionController extends Controller
{
    /**
     * Store a new submission (for student)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'assignment_id' => 'required|exists:assignments,id',
            'file_path' => 'required|file|max:5120|mimes:pdf,doc,docx,zip,png,jpg,jpeg',
        ]);

        // Cek apakah sudah pernah submit
        $existingSubmission = Submission::where('user_id', Auth::id())
            ->where('assignment_id', $validated['assignment_id'])
            ->first();

        if ($existingSubmission) {
            return back()->with('error', 'Anda sudah mengumpulkan tugas ini.');
        }

        $submission = new Submission;
        $submission->user_id = Auth::id();
        $submission->assignment_id = $validated['assignment_id'];

        // Handle file upload
        $submission->file_path = $request->file('file_path')->store('submissions', 'public');

        $submission->save();

        return back()->with('success', 'Tugas berhasil dikumpulkan!');
    }

    /**
     * Grade a single submission (for teacher)
     */
    public function grade(Request $request, $id)
    {
        $validated = $request->validate([
            'score' => 'required|integer|min:0|max:100',
        ]);

        $submission = Submission::findOrFail($id);

        // Verifikasi bahwa teacher adalah pemilik assignment
        $assignment = Assignment::where('id', $submission->assignment_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $submission->score = $validated['score'];
        $submission->save();

        return back()->with('success', 'Nilai berhasil disimpan!');
    }

    /**
     * Grade all submissions at once (for teacher)
     */
    public function gradeAll(Request $request)
    {
        $validated = $request->validate([
            'assignment_id' => 'required|exists:assignments,id',
            'submissions' => 'required|array',
            'submissions.*.id' => 'required|exists:submissions,id',
            'submissions.*.score' => 'nullable|integer|min:0|max:100',
        ]);

        // Verifikasi bahwa teacher adalah pemilik assignment
        $assignment = Assignment::where('id', $validated['assignment_id'])
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Update semua nilai
        foreach ($validated['submissions'] as $submissionData) {
            if (isset($submissionData['score']) && $submissionData['score'] !== null && $submissionData['score'] !== '') {
                $submission = Submission::find($submissionData['id']);
                if ($submission && $submission->assignment_id == $assignment->id) {
                    $submission->score = $submissionData['score'];
                    $submission->save();
                }
            }
        }

        return redirect()->route('teacher.assignments.index', ['id' => $assignment->id, 'mode' => 'view'])
            ->with('success', 'Semua nilai berhasil disimpan!');
    }
}
