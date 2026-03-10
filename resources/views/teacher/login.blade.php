@extends('layouts.teacher')
@section('title', 'Teacher Login')

@push('styles')
    <style>
        .auth-wrap {
            min-height: calc(100vh - 72px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background: radial-gradient(ellipse 80% 60% at 50% -10%, rgba(108, 99, 255, .2) 0%, transparent 70%);
            margin-top: -1rem;
            width: calc(100% + 2rem);
            margin-left: -1rem;
            margin-bottom: -1rem;
        }

        .auth-card {
            width: 100%;
            max-width: 440px;
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

        .auth-footer a:hover {
            text-decoration: underline;
        }
    </style>
@endpush

@section('content')
    <div class="auth-wrap">
        <div class="auth-card animate-in">
            <div class="auth-icon"><i class="bi bi-mortarboard" style="color: white;"></i></div>
            <h1 class="auth-title">Teacher Login</h1>
            <p class="auth-sub">Welcome back! Sign in to manage your books.</p>

            <form method="POST" action="{{ route('teacher.login') }}">
                @csrf
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" class="form-control" placeholder="you@example.com"
                        value="{{ old('email') }}" required autofocus>
                    @error('email')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                    @error('password')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
                <div class="form-group" style="display:flex;align-items:center;gap:.5rem;">
                    <input type="checkbox" name="remember" id="remember" style="accent-color:var(--primary);">
                    <label for="remember"
                        style="text-transform:none;letter-spacing:0;font-size:.9rem;color:var(--muted);margin:0;">Remember
                        me</label>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;">Sign In <i
                        class="bi bi-arrow-right"></i></button>
            </form>

            <div class="auth-footer">
                Don't have an account? <a href="{{ route('teacher.register') }}">Register here</a>
            </div>
        </div>
    </div>
@endsection
