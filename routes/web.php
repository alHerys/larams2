<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SubmissionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware('guest')->group(function () {
    // Register
    Route::get('/register', [AuthController::class, 'showRegisterForm'])
        ->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    // Login
    Route::get('/login', [AuthController::class, 'showLoginForm'])
        ->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    // Logout (harus POST untuk keamanan)
    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');
});

Route::middleware(['auth', 'role:teacher'])
    ->prefix('teacher')
    ->name('teacher.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        // Announcements (Pengumuman)
        Route::prefix('announcements')->name('announcements.')->group(function () {
            Route::get('/', [AnnouncementController::class, 'index'])->name('index');
            Route::post('/', [AnnouncementController::class, 'store'])->name('store');
            Route::delete('/{announcement}', [AnnouncementController::class, 'destroy'])->name('destroy');
        });

        // Assignments (Tugas)
        Route::prefix('assignments')->name('assignments.')->group(function () {
            Route::get('/', [AssignmentController::class, 'index'])->name('index');
            Route::get('/create', [AssignmentController::class, 'create'])->name('create');
            Route::post('/', [AssignmentController::class, 'store'])->name('store');
            Route::get('/{assignment}', [AssignmentController::class, 'show'])->name('show');
            Route::delete('/{assignment}', [AssignmentController::class, 'destroy'])->name('destroy');
            Route::put('/{id}', [AssignmentController::class, 'update'])->name('update');
        });

        // Grading (Penilaian)
        Route::put('/submissions/{submission}/grade', [SubmissionController::class, 'grade'])
            ->name('submissions.grade');
        Route::post('/submissions/grade-all', [SubmissionController::class, 'gradeAll'])->name('submissions.gradeAll');
    });

Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'studentDashboard'])->name('dashboard');

    // Assignments
    Route::get('/assignments', [AssignmentController::class, 'studentIndex'])->name('assignments.index');
    Route::get('/assignments/{id}', [AssignmentController::class, 'studentShow'])->name('assignments.show');

    // Submissions
    Route::post('/submissions', [SubmissionController::class, 'store'])->name('submissions.store');
});
