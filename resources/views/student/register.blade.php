@extends('layouts.student')
@section('title', 'Student Register')

@section('content')
    <div class="auth-wrap"
        style="--auth-primary: #4f46e5; --auth-secondary: #0ea5e9; --primary-rgb: 79, 70, 229; --secondary-rgb: 14, 165, 233;">
        <div class="auth-card animate-in">
            <div class="auth-header">
                <div class="auth-icon">🚀</div>
                <h1 class="auth-title">Join the Adventure</h1>
                <p class="auth-sub">Create your safe student profile today.</p>
            </div>

            <form method="POST" action="{{ route('student.register') }}">
                @csrf

                <div class="section-title">Personal Identity</div>

                <div class="mb-4">
                    <label class="form-label"><i class="bi bi-person"></i> Full Name</label>
                    <input type="text" name="name" class="form-control" placeholder="e.g. Ali Ahmed"
                        value="{{ old('name') }}" required>
                    @error('name')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-5 text-center">
                    <label class="form-label"><i class="bi bi-gender-ambiguous"></i> Who are
                        you?</label>
                    <div class="gender-group">
                        <label class="gender-option">
                            <input type="radio" name="gender" value="boy"
                                {{ old('gender') == 'boy' ? 'checked' : '' }} required>
                            <div class="gender-card">
                                <i class="bi bi-gender-male"></i>
                                <span>Boy</span>
                            </div>
                        </label>
                        <label class="gender-option">
                            <input type="radio" name="gender" value="girl"
                                {{ old('gender') == 'girl' ? 'checked' : '' }}>
                            <div class="gender-card">
                                <i class="bi bi-gender-female"></i>
                                <span>Girl</span>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label"><i class="bi bi-telephone"></i> Phone</label>
                        <input type="text" name="phone" class="form-control" placeholder="+91"
                            value="{{ old('phone') }}">
                        @error('phone')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label"><i class="bi bi-whatsapp"></i> WhatsApp</label>
                        <input type="text" name="whatsapp_number" class="form-control" placeholder="+91"
                            value="{{ old('whatsapp_number') }}">
                        @error('whatsapp_number')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="section-title">Academic Details</div>

                <div class="mb-4">
                    <label class="form-label"><i class="bi bi-building"></i> School Name</label>
                    <input type="text" name="school" class="form-control" placeholder="e.g. ABC Vidyalaya"
                        value="{{ old('school') }}">
                    @error('school')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="form-label">Grade</label>
                        <input type="text" name="class" class="form-control" placeholder="e.g. 10"
                            value="{{ old('class') }}">
                        @error('class')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Medium</label>
                        <select name="medium" class="form-control" required>
                            <option value="english" {{ old('medium') == 'english' ? 'selected' : '' }}>English</option>
                            <option value="gujarati" {{ old('medium') == 'gujarati' ? 'selected' : '' }}>Gujarati (ગુજરાતી)
                            </option>
                        </select>
                    </div>
                </div>

                <div class="section-title">Security</div>

                <div class="mb-4">
                    <label class="form-label"><i class="bi bi-envelope"></i> Email Address</label>
                    <input type="email" name="email" class="form-control" placeholder="student@school.com"
                        value="{{ old('email') }}" required>
                    @error('email')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

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
                Already registered? <a href="{{ route('student.login') }}">Sign in here</a>
            </div>
        </div>
    </div>
@endsection
