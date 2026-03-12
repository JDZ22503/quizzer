@extends('layouts.student')
@section('title', 'Student Login')



@section('content')
    <div class="auth-wrap"
        style="--auth-primary: #4f46e5; --auth-secondary: #0ea5e9; --primary-rgb: 79, 70, 229; --secondary-rgb: 14, 165, 233;">
        <div class="auth-card animate-in">
            <div class="auth-header">
                <div class="auth-icon">🎒</div>
                <h1 class="auth-title">Welcome Back</h1>
                <p class="auth-sub">Sign in to continue your learning journey!</p>
            </div>

            <form method="POST" action="{{ route('student.login') }}">
                @csrf
                <div class="mb-4">
                    <label class="form-label"><i class="bi bi-envelope"></i> Email Address</label>
                    <input type="email" name="email" class="form-control" placeholder="student@school.com"
                        value="{{ old('email') }}" required autofocus>
                    @error('email')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label"><i class="bi bi-lock"></i> Password</label>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
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
                New curious mind? <a href="{{ route('student.register') }}">Create an account</a>
            </div>
        </div>
    </div>
@endsection
