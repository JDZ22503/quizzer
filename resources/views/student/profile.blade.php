@extends('layouts.student')

@section('title', 'Your Profile')

@section('content')
    <div class="page-header animate-in mt-4">
        <h1><i class="bi bi-person-circle"></i> Student Profile</h1>
        <p>View your stats and manage your account details.</p>
    </div>

    <div class="row g-4 animate-in">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 text-center">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($student->name) }}&size=128&background=random"
                    class="rounded-circle mx-auto mb-3" width="128">
                <h3>{{ $student->name }}</h3>
                <p class="text-muted">Class {{ $student->class }} • {{ $student->medium }} Medium</p>
                <div class="mb-3">
                    @php
                        $leagueColor = match ($student->league) {
                            'bronze' => '#cd7f32',
                            'silver' => '#a8a8a8',
                            'diamond' => '#70d1f4',
                            'champion' => '#ffd700',
                            default => '#6c757d',
                        };
                    @endphp
                    <span class="badge profile-league-badge"
                        style="background: {{ $leagueColor }}; color: {{ $student->league == 'champion' || $student->league == 'diamond' ? '#1a1a1a' : 'white' }};">
                        <i class="bi bi-trophy-fill me-2"></i>
                        {{ ucfirst($student->league) }} {{ $student->league_level }}
                    </span>
                </div>
                <div class="d-flex justify-content-center gap-3 mt-3">
                    <div class="text-center">
                        <div class="h4 fw-bold mb-0">{{ $student->xp }}</div>
                        <small class="text-muted">XP</small>
                    </div>
                    <div class="vr"></div>
                    <div class="text-center">
                        <div class="h4 fw-bold mb-0">{{ $student->streak }}</div>
                        <small class="text-muted">Streak</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <h5 class="fw-bold mb-4">Account Details</h5>
                <form>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Email Address</label>
                            <input type="text" class="form-control" value="{{ $student->email }}" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone Number</label>
                            <input type="text" class="form-control" value="{{ $student->phone }}" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">WhatsApp Number</label>
                            <input type="text" class="form-control" value="{{ $student->whatsapp_number ?? 'N/A' }}"
                                disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Gender</label>
                            <input type="text" class="form-control" value="{{ ucfirst($student->gender ?? 'N/A') }}"
                                disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Roll Number</label>
                            <input type="text" class="form-control" value="{{ $student->roll_number ?? 'N/A' }}"
                                disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">School</label>
                            <input type="text" class="form-control" value="{{ $student->school ?? 'N/A' }}" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Joined On</label>
                            <input type="text" class="form-control" value="{{ $student->created_at->format('M d, Y') }}"
                                disabled>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
