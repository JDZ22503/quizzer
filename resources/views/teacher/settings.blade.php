@extends('layouts.teacher')

@section('title', 'Settings')

@section('content')
    <div class="breadcrumb animate-in">
        <a href="{{ route('teacher.dashboard') }}" class="text-decoration-none">Dashboard</a>
        <i class="bi bi-chevron-right"></i>
        <span>Settings</span>
    </div>

    <div class="page-header animate-in">
        <div>
            <h1>Account Settings</h1>
            <p>Manage your profile, preferences, and AI limits.</p>
        </div>
    </div>

    <div class="row animate-in">
        <div class="col-lg-8">
            <div class="book-card mb-4">
                <h4>Profile Information</h4>
                <hr>
                <form action="{{ route('teacher.settings.update') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-control" value="{{ $teacher->name }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control" value="{{ $teacher->email }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Preferred AI Model</label>
                            <select class="form-select">
                                <option value="gpt-4o" selected>GPT-4o (Smartest)</option>
                                <option value="gpt-4o-mini">GPT-4o Mini (Fastest)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Preferred Generation Language</label>
                            <select name="preferred_language" class="form-select">
                                <option value="english" {{ $teacher->preferred_language == 'english' ? 'selected' : '' }}>
                                    English</option>
                                <option value="gujarati" {{ $teacher->preferred_language == 'gujarati' ? 'selected' : '' }}>
                                    Gujarati</option>
                                <option value="hindi" {{ $teacher->preferred_language == 'hindi' ? 'selected' : '' }}>Hindi
                                </option>
                            </select>
                        </div>
                        <div class="col-12 mt-4 text-end">
                            <button type="submit" class="btn btn-primary px-4">Save Changes</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="book-card bg-light border-0">
                <h4 class="text-danger">Danger Zone</h4>
                <p class="text-muted small">Deleting your account is permanent and cannot be undone.</p>
                <button class="btn btn-outline-danger">Delete Account</button>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="book-card mb-4">
                <h4>AI Credits</h4>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="small">Token Usage</span>
                    <span class="small fw-bold">75%</span>
                </div>
                <div class="progress mb-3" style="height: 8px;">
                    <div class="progress-bar bg-primary" role="progressbar" style="width: 75%"></div>
                </div>
                <p class="small text-muted mb-4">You have used 7,500 of your 10,000 monthly AI tokens.</p>
                <button class="btn btn-outline-primary w-100">Buy Credits</button>
            </div>
        </div>
    </div>
@endsection
