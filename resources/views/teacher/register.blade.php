@extends('layouts.teacher')
@section('title', 'Register as Teacher')

@push('styles')
    <style>
        .auth-wrap {
            min-height: calc(100vh - 64px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background: radial-gradient(ellipse 80% 60% at 50% -10%, rgba(108, 99, 255, .2) 0%, transparent 70%);
        }

        .auth-card {
            width: 100%;
            max-width: 480px;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 24px 80px rgba(0, 0, 0, .5);
        }

        .auth-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--primary), #a855f7);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            margin-bottom: 1.5rem;
        }

        .auth-title {
            font-size: 1.6rem;
            font-weight: 800;
            margin-bottom: .3rem;
        }

        .auth-sub {
            color: var(--muted);
            font-size: .9rem;
            margin-bottom: 2rem;
        }

        .auth-footer {
            text-align: center;
            margin-top: 1.5rem;
            color: var(--muted);
            font-size: .85rem;
        }

        .auth-footer a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }

        .row-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }
    </style>
@endpush

@section('content')
    <div class="auth-wrap">
        <div class="auth-card animate-in">
            <div class="auth-icon"><i class="bi bi-mortarboard" style="color: white;"></i></div>
            <h1 class="auth-title">Create Teacher Account</h1>
            <p class="auth-sub">Join QuizPlatform and start generating AI-powered quizzes.</p>

            <form method="POST" action="{{ route('teacher.register') }}">
                @csrf
                <div class="row-2">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="name" class="form-control" placeholder="John Doe"
                            value="{{ old('name') }}" required>
                        @error('name')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Phone</label>
                        <input type="text" name="phone" class="form-control" placeholder="+91 9000000000"
                            value="{{ old('phone') }}">
                    </div>
                </div>
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" class="form-control" placeholder="teacher@school.com"
                        value="{{ old('email') }}" required>
                    @error('email')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Subject Specialization</label>
                    <input type="text" name="subject_specialization" class="form-control"
                        placeholder="e.g. Mathematics, Physics" value="{{ old('subject_specialization') }}">
                </div>
                <div class="row-2">
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Min 8 chars" required>
                        @error('password')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control"
                            placeholder="Repeat password" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;">Create Account
                    <i class="bi bi-arrow-right"></i></button>
            </form>
            <div class="auth-footer">Already have an account? <a href="{{ route('teacher.login') }}">Sign in</a></div>
        </div>
    </div>
@endsection
