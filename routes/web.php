<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\Student\AuthController as StudentAuthController;
use App\Http\Controllers\Student\QuizWebController;
use App\Http\Controllers\Teacher\AuthController as TeacherAuthController;
use App\Http\Controllers\Teacher\DashboardController;
use App\Http\Controllers\Teacher\ManualMcqController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Home
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| Public Website
|--------------------------------------------------------------------------
*/
Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/features', [PublicController::class, 'features'])->name('features');
Route::get('/pricing', [PublicController::class, 'pricing'])->name('pricing');
Route::get('/documentation', [PublicController::class, 'documentation'])->name('documentation');
Route::get('/tutorials', [PublicController::class, 'tutorials'])->name('tutorials');

/*
|--------------------------------------------------------------------------
| Teacher — Guest routes (no auth required)
|--------------------------------------------------------------------------
*/
Route::prefix('teacher')->name('teacher.')->middleware('guest:teacher')->group(function () {
    Route::get('/login', [TeacherAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [TeacherAuthController::class, 'login']);
    Route::get('/register', [TeacherAuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [TeacherAuthController::class, 'register']);
});

/*
|--------------------------------------------------------------------------
| Teacher — Authenticated routes
|--------------------------------------------------------------------------
*/
Route::prefix('teacher')->name('teacher.')->middleware('auth:teacher')->group(function () {
    Route::post('/logout', [TeacherAuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/books/{book}', [DashboardController::class, 'bookShow'])->name('book.show');
    Route::patch('/books/{book}/preference', [DashboardController::class, 'updatePreference'])->name('book.update-preference');
    Route::get('/books/{book}/add-chapter', [DashboardController::class, 'addChapterForm'])->name('book.chapter.add');
    Route::post('/books/{book}/add-chapter', [DashboardController::class, 'storeChapter'])->name('book.chapter.store');

    Route::delete('/chapters/{chapter}', [DashboardController::class, 'deleteChapter'])->name('chapter.delete');
    Route::patch('/chapters/{chapter}', [DashboardController::class, 'updateChapter'])->name('chapter.update');
    Route::delete('/chapters/{chapter}/questions/{type}', [DashboardController::class, 'deleteQuestions'])->name('chapter.questions.delete');
    Route::delete('/topics/{topic}', [DashboardController::class, 'deleteTopic'])->name('topic.delete');
    Route::get('/chapters/{chapter}/append', [DashboardController::class, 'appendContentForm'])->name('chapter.append');
    Route::post('/chapters/{chapter}/append', [DashboardController::class, 'appendContentStore'])->name('chapter.append.store');

    // Manual Entry Routes
    Route::get('/manual-create', [ManualMcqController::class, 'create'])->name('manual-mcq.create');
    Route::post('/manual-create', [ManualMcqController::class, 'store'])->name('manual-mcq.store');

    // New Teacher App Routes
    Route::get('/subjects/{subject}', [DashboardController::class, 'subjectShow'])->name('subject.show');
    Route::get('/chapters/{chapter}', [DashboardController::class, 'chapterShow'])->name('chapter.show');
    Route::get('/ai-monitor', [DashboardController::class, 'aiMonitor'])->name('ai-monitor');
    Route::get('/analytics', [DashboardController::class, 'analytics'])->name('analytics');
    Route::get('/settings', [DashboardController::class, 'settings'])->name('settings');
    Route::post('/settings', [DashboardController::class, 'updateSettings'])->name('settings.update');

    Route::get('/chapters/{chapter}/export-pdf', [DashboardController::class, 'exportPdf'])->name('chapter.export-pdf');
    Route::post('/manual-bulk-import', [ManualMcqController::class, 'bulkImport'])->name('manual-mcq.bulk-import');
});

/*
|--------------------------------------------------------------------------
| Student — Guest routes
|--------------------------------------------------------------------------
*/
Route::prefix('student')->name('student.')->middleware('guest:student')->group(function () {
    Route::get('/login', [StudentAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [StudentAuthController::class, 'login']);
    Route::get('/register', [StudentAuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [StudentAuthController::class, 'register']);
});

/*
|--------------------------------------------------------------------------
| Student — Authenticated routes
|--------------------------------------------------------------------------
*/
Route::prefix('student')->name('student.')->middleware(['auth:student', 'student.streak'])->group(function () {
    Route::post('/logout', [StudentAuthController::class, 'logout'])->name('logout');
    Route::get('/subjects', [QuizWebController::class, 'subjects'])->name('subjects');
    Route::get('/dashboard', [QuizWebController::class, 'dashboard'])->name('dashboard');
    Route::get('/subject/{id}', [QuizWebController::class, 'subjectShow'])->name('subject.show');
    Route::get('/chapters/{book_id}', [QuizWebController::class, 'chapters'])->name('chapters');
    Route::get('/quiz/{chapter_id}', [QuizWebController::class, 'quiz'])->name('quiz');
    Route::get('/study/{chapter_id}', [QuizWebController::class, 'study'])->name('study');
    Route::get('/read-mcqs/{chapter_id}', [QuizWebController::class, 'readMcqs'])->name('read-mcqs');
    Route::get('/topic/{id}', [QuizWebController::class, 'topic'])->name('topic');

    Route::get('/daily-challenge', [QuizWebController::class, 'dailyChallenge'])->name('daily-challenge');
    Route::post('/daily-challenge/submit', [QuizWebController::class, 'submitDailyChallenge'])->name('daily-challenge.submit');

    Route::get('/leaderboard', [QuizWebController::class, 'leaderboard'])->name('leaderboard');
    Route::get('/rewards', [QuizWebController::class, 'rewards'])->name('rewards');
    Route::get('/profile', [QuizWebController::class, 'profile'])->name('profile');

    Route::get('/history', [QuizWebController::class, 'history'])->name('history');
    Route::post('/quiz/submit', [QuizWebController::class, 'submit'])->name('quiz.submit');
    Route::get('/quiz/results/{attempt_id}', [QuizWebController::class, 'results'])->name('quiz.results');
});
/*
|--------------------------------------------------------------------------
| Admin Panel
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest:web')->group(function () {
        Route::get('/login', [AdminController::class, 'showLogin'])->name('login');
        Route::post('/login', [AdminController::class, 'login']);
    });

    Route::middleware(['auth:web', 'admin'])->group(function () {
        Route::post('/logout', [AdminController::class, 'logout'])->name('logout');
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::get('/users/teachers/{teacher}/edit', [AdminController::class, 'editTeacher'])->name('users.teachers.edit');
        Route::put('/users/teachers/{teacher}', [AdminController::class, 'updateTeacher'])->name('users.teachers.update');
        Route::get('/users/students/{student}/edit', [AdminController::class, 'editStudent'])->name('users.students.edit');
        Route::put('/users/students/{student}', [AdminController::class, 'updateStudent'])->name('users.students.update');
        Route::get('/ai-jobs', [AdminController::class, 'aiJobs'])->name('ai-jobs');

        // Shared Features
        Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
        Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
        Route::get('/search', [SearchController::class, 'search'])->name('global.search');
        Route::get('/analytics', [AdminController::class, 'analytics'])->name('analytics');
        Route::get('/league-settings', [AdminController::class, 'leagueSettings'])->name('league.settings');
        Route::post('/league-settings', [AdminController::class, 'updateLeagueSettings'])->name('league.settings.update');
        Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
        Route::post('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');
    });
});
