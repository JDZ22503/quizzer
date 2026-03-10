<?php

use App\Http\Controllers\Teacher\AuthController as TeacherAuthController;
use App\Http\Controllers\Teacher\DashboardController;
use App\Http\Controllers\Teacher\ManualMcqController;
use App\Http\Controllers\Student\AuthController as StudentAuthController;
use App\Http\Controllers\Student\QuizWebController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Home
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect()->route('student.login');
});

/*
|--------------------------------------------------------------------------
| Teacher — Guest routes (no auth required)
|--------------------------------------------------------------------------
*/
Route::prefix('teacher')->name('teacher.')->middleware('guest:teacher')->group(function () {
    Route::get('/login',    [TeacherAuthController::class, 'showLogin'])->name('login');
    Route::post('/login',   [TeacherAuthController::class, 'login']);
    Route::get('/register', [TeacherAuthController::class, 'showRegister'])->name('register');
    Route::post('/register',[TeacherAuthController::class, 'register']);
});

/*
|--------------------------------------------------------------------------
| Teacher — Authenticated routes
|--------------------------------------------------------------------------
*/
Route::prefix('teacher')->name('teacher.')->middleware('auth:teacher')->group(function () {
    Route::post('/logout',              [TeacherAuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard',            [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/books/{book}',         [DashboardController::class, 'bookShow'])->name('book.show');
    Route::patch('/books/{book}/preference', [DashboardController::class, 'updatePreference'])->name('book.update-preference');
    Route::get('/books/{book}/add-chapter',  [DashboardController::class, 'addChapterForm'])->name('book.chapter.add');
    Route::post('/books/{book}/add-chapter', [DashboardController::class, 'storeChapter'])->name('book.chapter.store');

    Route::delete('/chapters/{chapter}', [DashboardController::class, 'deleteChapter'])->name('chapter.delete');
    Route::delete('/chapters/{chapter}/questions/{type}', [DashboardController::class, 'deleteQuestions'])->name('chapter.questions.delete');
    Route::delete('/topics/{topic}', [DashboardController::class, 'deleteTopic'])->name('topic.delete');
    Route::get('/chapters/{chapter}/append', [DashboardController::class, 'appendContentForm'])->name('chapter.append');
    Route::post('/chapters/{chapter}/append', [DashboardController::class, 'appendContentStore'])->name('chapter.append.store');

    // Manual Entry Routes
    Route::get('/manual-create', [ManualMcqController::class, 'create'])->name('manual-mcq.create');
    Route::post('/manual-create', [ManualMcqController::class, 'store'])->name('manual-mcq.store');
});

/*
|--------------------------------------------------------------------------
| Student — Guest routes
|--------------------------------------------------------------------------
*/
Route::prefix('student')->name('student.')->middleware('guest:student')->group(function () {
    Route::get('/login',    [StudentAuthController::class, 'showLogin'])->name('login');
    Route::post('/login',   [StudentAuthController::class, 'login']);
    Route::get('/register', [StudentAuthController::class, 'showRegister'])->name('register');
    Route::post('/register',[StudentAuthController::class, 'register']);
});

/*
|--------------------------------------------------------------------------
| Student — Authenticated routes
|--------------------------------------------------------------------------
*/
Route::prefix('student')->name('student.')->middleware(['auth:student', 'student.streak'])->group(function () {
    Route::post('/logout',              [StudentAuthController::class, 'logout'])->name('logout');
    Route::get('/subjects',             [QuizWebController::class, 'subjects'])->name('subjects');
    Route::get('/dashboard',            [QuizWebController::class, 'dashboard'])->name('dashboard');
    Route::get('/subject/{id}',         [QuizWebController::class, 'subjectShow'])->name('subject.show');
    Route::get('/chapters/{book_id}',   [QuizWebController::class, 'chapters'])->name('chapters');
    Route::get('/quiz/{chapter_id}',    [QuizWebController::class, 'quiz'])->name('quiz');
    Route::get('/study/{chapter_id}',   [QuizWebController::class, 'study'])->name('study');
    Route::get('/read-mcqs/{chapter_id}', [QuizWebController::class, 'readMcqs'])->name('read-mcqs');
    Route::get('/topic/{id}',           [QuizWebController::class, 'topic'])->name('topic');
    
    Route::get('/daily-challenge', [QuizWebController::class, 'dailyChallenge'])->name('daily-challenge');
    Route::post('/daily-challenge/submit', [QuizWebController::class, 'submitDailyChallenge'])->name('daily-challenge.submit');

    Route::get('/history',              [QuizWebController::class, 'history'])->name('history');
    Route::post('/quiz/submit',         [QuizWebController::class, 'submit'])->name('quiz.submit');
    Route::get('/quiz/results/{attempt_id}', [QuizWebController::class, 'results'])->name('quiz.results');
});
