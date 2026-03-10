<?php

use App\Http\Controllers\Teacher\BookController;
use App\Http\Controllers\Student\QuizController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Teacher Routes
|--------------------------------------------------------------------------
*/
Route::prefix('teacher')->middleware('auth:sanctum')->group(function () {
    Route::post('/upload-book', [BookController::class, 'upload']);
    Route::get('/book/{book}',  [BookController::class, 'show']);
});

/*
|--------------------------------------------------------------------------
| Student Routes
|--------------------------------------------------------------------------
*/
Route::prefix('student')->group(function () {
    // Browse subjects → books → chapters
    Route::get('/subjects',          [QuizController::class, 'subjects']);

    // Get quiz questions for a chapter (auth optional — protect if needed)
    Route::get('/quiz/{chapter_id}', [QuizController::class, 'getQuiz']);

    // Submit quiz answers → score + explanations
    Route::post('/quiz/submit',      [QuizController::class, 'submit']);
});
