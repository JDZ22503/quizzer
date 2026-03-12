@extends('layouts.teacher')
@section('title', 'Teacher Login')

@section('content')
    <div class="auth-wrap"
        style="--auth-primary: #6366f1; --auth-secondary: #a855f7; --primary-rgb: 99, 102, 241; --secondary-rgb: 168, 85, 247; --auth-bg: #f8fafc;">
        <div class="auth-card animate-in">
            <div class="auth-header">
                <div class="auth-icon"><i class="bi bi-mortarboard"></i></div>
                <h1 class="auth-title">Teacher Login</h1>
                <p class="auth-sub">Welcome back! Sign in to manage your lessons.</p>
            </div>

            <form method="POST" action="{{ route('teacher.login') }}">
                @csrf
                <div class="mb-4">
                    <label class="form-label"><i class="bi bi-envelope"></i> Email Address</label>
                    <input type="email" name="email" class="form-control" placeholder="teacher@school.com"
                        value="{{ old('email') }}" required autofocus>
                    @error('email')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label"><i class="bi bi-lock"></i> Password</label>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                    @error('password')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div class="form-check">
                        <input type="checkbox" name="remember" class="form-check-input" id="remember">
                        <label class="form-check-label small text-muted" for="remember">Remember me</label>
                    </div>
                </div>

                <button type="submit" class="btn-login">
                    <span>Sign In</span>
                    <i class="bi bi-arrow-right"></i>
                </button>
            </form>

            <div class="auth-footer">
                Need a dashboard? <a href="{{ route('teacher.register') }}">Join as Teacher</a>
            </div>
        </div>
    </div>
@endsection
