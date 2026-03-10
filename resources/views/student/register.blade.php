@extends('layouts.student')
@section('title', 'Student Register')

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
            max-width: 480px;
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
            <div class="auth-icon">🎒</div>
            <h1 class="auth-title">Join as Student</h1>
            <p class="auth-sub">Create your account and start learning.</p>

            <form method="POST" action="{{ route('student.register') }}">
                @csrf
                <div class="row-2">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="name" class="form-control" placeholder="Ali Ahmed"
                            value="{{ old('name') }}" required>
                        @error('name')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="row-2">
                        <div class="form-group">
                            <label>Phone Number</label>
                            <input type="text" name="phone" class="form-control" placeholder="+91 9000000000"
                                value="{{ old('phone') }}">
                        </div>
                        <div class="form-group">
                            <label>WhatsApp Number</label>
                            <input type="text" name="whatsapp_number" class="form-control" placeholder="+91 9000000000"
                                value="{{ old('whatsapp_number') }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>School</label>
                        <input type="text" name="school" class="form-control" placeholder="e.g. ABC Vidyalaya"
                            value="{{ old('school') }}">
                    </div>
                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" name="email" class="form-control" placeholder="student@school.com"
                            value="{{ old('email') }}" required>
                        @error('email')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="row-2">
                        <div class="form-group">
                            <label>Class</label>
                            <input type="text" name="class" class="form-control" placeholder="e.g. 10th"
                                value="{{ old('class') }}">
                        </div>
                        <div class="form-group">
                            <label>Roll Number</label>
                            <input type="text" name="roll_number" class="form-control" placeholder="e.g. 42"
                                value="{{ old('roll_number') }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Medium (Language)</label>
                        <select name="medium" class="form-control" required style="cursor: pointer;">
                            <option value="english" {{ old('medium') == 'english' ? 'selected' : '' }}>English</option>
                            <option value="gujarati" {{ old('medium') == 'gujarati' ? 'selected' : '' }}>Gujarati ગુજરાતી
                            </option>
                        </select>
                        @error('medium')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
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
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat"
                                required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;">Create Account
                        →</button>
            </form>
            <div class="auth-footer">Already registered? <a href="{{ route('student.login') }}">Sign in</a></div>
        </div>
    </div>
@endsection
