<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'student.streak' => \App\Http\Middleware\SyncStudentStreak::class,
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
        ]);

        $middleware->redirectGuestsTo(fn (Request $request) => match (true) {
            $request->is('teacher*') => route('teacher.login'),
            $request->is('admin*') => route('admin.login'),
            default => route('student.login'),
        });

        $middleware->redirectUsersTo(fn (Request $request) => match (true) {
            $request->is('teacher*') => route('teacher.dashboard'),
            default => route('student.subjects'),
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
