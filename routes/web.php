<?php

// filepath: routes/web.php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubmissionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
| Route yang bisa diakses tanpa login
*/
Route::get('/', function () {
    return view('welcome');
})->name('home');

/*
|--------------------------------------------------------------------------
| Teacher Routes
|--------------------------------------------------------------------------
| Route khusus untuk guru
| Middleware: auth (harus login) + role:teacher (harus guru)
*/
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
        });

        // Grading (Penilaian)
        Route::put('/submissions/{submission}/grade', [SubmissionController::class, 'grade'])
            ->name('submissions.grade');
    });

/*
|--------------------------------------------------------------------------
| Student Routes
|--------------------------------------------------------------------------
| Route khusus untuk murid
| Middleware: auth (harus login) + role:student (harus murid)
*/
Route::middleware(['auth', 'role:student'])
    ->prefix('student')
    ->name('student.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        // Submissions (Pengumpulan Tugas)
        Route::post('/submissions', [SubmissionController::class, 'store'])
            ->name('submissions.store');
    });

/*
|--------------------------------------------------------------------------
| Profile Routes (Semua User)
|--------------------------------------------------------------------------
*/
// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

/*
|--------------------------------------------------------------------------
| Auth Routes (Breeze)
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';
