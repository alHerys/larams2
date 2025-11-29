<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Assignment;
use App\Models\Submission;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        if ($user->isTeacher()) {
            return $this->teacherDashboard();
        }

        return $this->studentDashboard();
    }

    private function teacherDashboard(): View
    {
        $announcements = Announcement::with('user')
            ->latest()
            ->paginate(5);

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

    public function studentDashboard(): View
    {
        $announcements = Announcement::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return view('dashboard.student', compact('announcements'));
    }
}
