@extends('layouts.teacher')

@section('title', 'Teacher Analytics')

@section('content')
    <div class="breadcrumb animate-in">
        <a href="{{ route('teacher.dashboard') }}" class="text-decoration-none">Dashboard</a>
        <i class="bi bi-chevron-right"></i>
        <span>Analytics</span>
    </div>

    <div class="page-header animate-in">
        <div>
            <h1>Performance Analytics</h1>
            <p>Insights into your content performance and student engagement.</p>
        </div>
    </div>

    <div class="row g-4 mb-5 animate-in">
        <div class="col-md-3">
            <div class="stat-card blue">
                <div class="label">Average Score</div>
                <div class="value">74%</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card purple">
                <div class="label">Quizzes Taken</div>
                <div class="value">1,240</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card teal">
                <div class="label">Active Students</div>
                <div class="value">450</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card warn">
                <div class="label">AI Token Savings</div>
                <div class="value">$142</div>
            </div>
        </div>
    </div>

    <div class="row g-4 animate-in">
        <div class="col-lg-8">
            <div class="book-card">
                <h4>Content Usage (Last 30 Days)</h4>
                <div style="height: 300px; display: flex; align-items: flex-end; gap: 1rem; padding-top: 2rem;">
                    @foreach ([40, 60, 45, 90, 65, 80, 50] as $h)
                        <div style="flex: 1; background: var(--primary-gradient); height: {{ $h }}%; border-radius: 8px 8px 0 0; position: relative;"
                            title="{{ $h }}%">
                            <span
                                style="position: absolute; bottom: -25px; left: 50%; transform: translateX(-50%); font-size: 0.7rem; color: var(--muted);">Day
                                {{ $loop->iteration }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="book-card">
                <h4>Top Performing Books</h4>
                <ul class="list-group list-group-flush mt-3">
                    <li
                        class="list-group-item d-flex justify-content-between align-items-center bg-transparent border-0 px-0">
                        <div>
                            <div class="fw-bold">Physics Vol 1</div>
                            <small class="text-muted">450 enrollments</small>
                        </div>
                        <span class="badge bg-success rounded-pill">8.4</span>
                    </li>
                    <li
                        class="list-group-item d-flex justify-content-between align-items-center bg-transparent border-0 px-0">
                        <div>
                            <div class="fw-bold">Chemistry Basics</div>
                            <small class="text-muted">320 enrollments</small>
                        </div>
                        <span class="badge bg-success rounded-pill">7.9</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
@endsection
