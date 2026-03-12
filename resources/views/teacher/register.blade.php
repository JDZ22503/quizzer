@extends('layouts.teacher')
@section('title', 'Teacher Registration')

@section('content')
    <div class="auth-wrap"
        style="--auth-primary: #6366f1; --auth-secondary: #a855f7; --primary-rgb: 99, 102, 241; --secondary-rgb: 168, 85, 247; --auth-bg: #f8fafc;">
        <div class="auth-card animate-in">
            <div class="auth-header">
                <div class="auth-icon"><i class="bi bi-mortarboard"></i></div>
                <h1 class="auth-title">Join as Teacher</h1>
                <p class="auth-sub">Start your AI-powered teaching journey today.</p>
            </div>

            <form method="POST" action="{{ route('teacher.register') }}">
                @csrf

                <div class="section-title">Professional Identity</div>

                <div class="mb-4">
                    <label class="form-label"><i class="bi bi-person"></i> Full Name</label>
                    <input type="text" name="name" class="form-control" placeholder="e.g. John Doe"
                        value="{{ old('name') }}" required>
                    @error('name')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label"><i class="bi bi-envelope"></i> Email Address</label>
                        <input type="email" name="email" class="form-control" placeholder="teacher@school.com"
                            value="{{ old('email') }}" required>
                        @error('email')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label"><i class="bi bi-telephone"></i> Phone</label>
                        <input type="text" name="phone" class="form-control" placeholder="+91"
                            value="{{ old('phone') }}">
                        @error('phone')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label"><i class="bi bi-book"></i> Subject Specialization</label>
                    <input type="text" name="subject_specialization" class="form-control"
                        placeholder="e.g. Mathematics, Physics" value="{{ old('subject_specialization') }}" required>
                    @error('subject_specialization')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="section-title">Security</div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label"><i class="bi bi-lock"></i> Password</label>
                        <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Repeat</label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="••••••••"
                            required>
                    </div>
                    @error('password')
                        <p class="form-error px-2">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="btn-register mt-4">
                    <span>Create Account</span>
                    <i class="bi bi-arrow-right"></i>
                </button>
            </form>

            <div class="auth-footer">
                Already have an account? <a href="{{ route('teacher.login') }}">Sign in here</a>
            </div>
        </div>
    </div>
@endsection
