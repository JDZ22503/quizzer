<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SyncStudentStreak
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->guard('student')->check()) {
            auth()->guard('student')->user()->checkAndResetStreak();
        }

        return $next($request);
    }
}
