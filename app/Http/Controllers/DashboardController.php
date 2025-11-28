<?php

// filepath: app/Http/Controllers/DashboardController.php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Assignment;
use App\Models\Submission;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * CONTROLLER: DashboardController
 *
 * Menangani tampilan dashboard untuk guru dan murid
 *
 * PRINSIP:
 * - Satu controller untuk dashboard (bukan TeacherDashboardController & StudentDashboardController)
 * - Logic pemisahan berdasarkan role ada di dalam method
 * - Lebih mudah di-maintain dan konsisten
 */
class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard sesuai role user
     *
     * FLOW:
     * 1. Ambil user yang login
     * 2. Cek role user
     * 3. Query data yang relevan
     * 4. Return view yang sesuai
     */
    public function index(): View
    {
        $user = Auth::user();

        if ($user->isTeacher()) {
            return $this->teacherDashboard();
        }

        return $this->studentDashboard();
    }

    /**
     * Dashboard untuk guru
     *
     * DATA YANG DITAMPILKAN:
     * - Daftar pengumuman terbaru
     * - Daftar tugas yang dibuat
     * - Daftar submission terbaru dari murid
     */
    private function teacherDashboard(): View
    {
        $announcements = Announcement::with('user')
            ->latest()
            ->take(5)
            ->get();

        $assignments = Assignment::with('submissions')
            ->latest()
            ->take(5)
            ->get();

        $recentSubmissions = Submission::with(['user', 'assignment'])
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard.teacher', [
            'announcements' => $announcements,
            'assignments' => $assignments,
            'recentSubmissions' => $recentSubmissions,
        ]);
    }

    /**
     * Dashboard untuk murid
     *
     * DATA YANG DITAMPILKAN:
     * - Daftar pengumuman
     * - Daftar tugas yang tersedia
     * - Daftar submission dan nilai
     */
    private function studentDashboard(): View
    {
        $user = Auth::user();

        $announcements = Announcement::with('user')
            ->latest()
            ->take(5)
            ->get();

        $assignments = Assignment::latest()->get();

        // Ambil ID assignment yang sudah di-submit oleh murid ini
        $submittedAssignmentIds = Submission::where('user_id', $user->id)
            ->pluck('assignment_id')
            ->toArray();

        // Submission murid ini beserta nilainya
        $mySubmissions = Submission::with('assignment')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        return view('dashboard.student', [
            'announcements' => $announcements,
            'assignments' => $assignments,
            'submittedAssignmentIds' => $submittedAssignmentIds,
            'mySubmissions' => $mySubmissions,
        ]);
    }
}
