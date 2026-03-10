@extends('layouts.student')
@section('title', 'Student Login')

@push('styles')
    <style>
        .auth-wrap {
            min-height: calc(100vh - 72px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background: radial-gradient(ellipse 70% 50% at 50% 0%, rgba(59, 130, 246, .18) 0%, transparent 70%);
            margin-top: -3rem;
            /* Offset the main p-5 (3rem) to hit the nav */
            width: calc(100% + 6rem);
            /* Expand to edges */
            margin-left: -3rem;
            margin-bottom: -3rem;
        }

        .auth-card {
            width: 100%;
            max-width: 440px;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 24px 80px rgba(0, 0, 0, .6);
        }

        .auth-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--primary), var(--accent));
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
    </style>
@endpush

@section('content')
    <div class="auth-wrap">
        <div class="auth-card animate-in">
            <div class="auth-icon">🎒</div>
            <h1 class="auth-title">Student Login</h1>
            <p class="auth-sub">Sign in to start your quiz journey!</p>

            <form method="POST" action="{{ route('student.login') }}">
                @csrf
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" class="form-control" placeholder="student@school.com"
                        value="{{ old('email') }}" required autofocus>
                    @error('email')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>
                <div class="form-group" style="display:flex;align-items:center;gap:.5rem;">
                    <input type="checkbox" name="remember" id="remember" style="accent-color:var(--primary);">
                    <label for="remember"
                        style="text-transform:none;letter-spacing:0;font-size:.9rem;color:var(--muted);margin:0;">Remember
                        me</label>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;">Sign In →</button>
            </form>

            <div class="auth-footer">
                New student? <a href="{{ route('student.register') }}">Create an account</a>
            </div>
        </div>
    </div>
@endsection
