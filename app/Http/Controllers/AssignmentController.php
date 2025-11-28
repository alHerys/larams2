<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AssignmentController extends Controller
{
    /**
     * Display a listing of assignments with optional edit/view panel
     */
    public function index(Request $request)
    {
        // Ambil semua assignment milik teacher yang login
        $assignments = Assignment::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        $selectedAssignment = null;
        $mode = null;

        // Cek apakah ada parameter id dan mode
        if ($request->has('id') && $request->has('mode')) {
            $selectedAssignment = Assignment::where('id', $request->id)
                ->where('user_id', Auth::id())
                ->with(['submissions.user']) // Eager load submissions dengan user
                ->first();
            $mode = $request->mode; // 'edit' atau 'view'
        }

        return view('teacher.assignments.index', compact('assignments', 'selectedAssignment', 'mode'));
    }

    /**
     * Show the form for creating a new assignment
     */
    public function create()
    {
        return view('teacher.assignments.create');
    }

    /**
     * Store a newly created assignment
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'file_path' => 'nullable|file|max:2048|mimes:pdf,doc,docx,zip,png,jpg',
            'due_date' => 'nullable|date|after:now',
        ]);

        $assignment = new Assignment;
        $assignment->user_id = Auth::id();
        $assignment->title = $validated['title'];
        $assignment->description = $validated['description'];
        $assignment->due_date = $validated['due_date'] ?? null;

        // Handle file upload
        if ($request->hasFile('file_path')) {
            $assignment->file_path = $request->file('file_path')->store('assignments', 'public');
        }

        $assignment->save();

        return redirect()->route('teacher.assignments.index')
            ->with('success', 'Assignment berhasil dibuat!');
    }

    /**
     * Update the specified assignment
     */
    public function update(Request $request, $id)
    {
        $assignment = Assignment::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'file_path' => 'nullable|file|max:2048|mimes:pdf,doc,docx,zip,png,jpg',
        ]);

        $assignment->title = $validated['title'];
        $assignment->description = $validated['description'];

        // Handle file upload
        if ($request->hasFile('file_path')) {
            // Hapus file lama jika ada
            if ($assignment->file_path) {
                Storage::disk('public')->delete($assignment->file_path);
            }
            $assignment->file_path = $request->file('file_path')->store('assignments', 'public');
        }

        $assignment->save();

        return redirect()->route('teacher.assignments.index')
            ->with('success', 'Assignment berhasil diupdate!');
    }

    /**
     * Remove the specified assignment
     */
    public function destroy($id)
    {
        $assignment = Assignment::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Hapus file jika ada
        if ($assignment->file_path) {
            Storage::disk('public')->delete($assignment->file_path);
        }

        $assignment->delete();

        return redirect()->route('teacher.assignments.index')
            ->with('success', 'Assignment berhasil dihapus!');
    }

    public function studentIndex()
    {
        $assignments = Assignment::orderBy('created_at', 'desc')->get();

        return view('student.assignments.index', compact('assignments'));
    }

    /**
     * Show assignment detail for student (with submission form)
     */
    public function studentShow($id)
    {
        $assignment = Assignment::findOrFail($id);

        // Cek apakah student sudah submit
        $submission = Submission::where('user_id', Auth::id())
            ->where('assignment_id', $id)
            ->first();

        $hasSubmitted = $submission !== null;

        return view('student.assignments.show', compact('assignment', 'submission', 'hasSubmitted'));
    }
}
