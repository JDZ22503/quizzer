@extends('layouts.student')
@section('title', 'Welcome to Revizo')


@section('content')
    <div class="home-header animate-in">
        <div class="greeting">
            <div class="user-meta">
                @php
                    $hour = date('H');
                    $greet = 'Good Morning';
                    if ($hour >= 12 && $hour < 17) {
                        $greet = 'Good Afternoon';
                    }
                    if ($hour >= 17) {
                        $greet = 'Good Evening';
                    }
                @endphp
                <h1>{{ $greet }}, {{ auth()->guard('student')->user()->name }} <i class="bi bi-emoji-smile"></i></h1>
                <p>Ready to level up your knowledge today?</p>
            </div>
            <a href="{{ route('student.dashboard') }}" class="btn btn-ghost text-decoration-none">
                View Performance
            </a>
        </div>

        @php
            $student = auth()->guard('student')->user();
            $challengeDone = $student->last_streak_at && $student->last_streak_at->isToday();
        @endphp

        <div class="daily-challenge-banner animate-in" style="--delay: 0.1s">
            <div class="banner-content">
                <div class="banner-text">
                    {{-- <span class="badge {{ $challengeDone ? 'badge-success' : 'badge-primary' }}">
                        {{ $challengeDone ? '✓ Completed' : '⚡ Daily Mission' }}
                    </span> --}}
                    <h2>Weekly Goal: Keep the Flame Alive! <i class="bi bi-fire"></i></h2>
                    <p>{{ $challengeDone ? 'You have maintained your streak for today. Great job!' : 'Complete today\'s 5-question challenge to maintain your streak.' }}
                    </p>
                </div>
                <div class="banner-cta">
                    @if ($challengeDone)
                        <button class="btn {{ $challengeDone ? 'badge-success' : 'badge-primary' }}" disabled>
                            ✓ Done for Today
                        </button>
                    @else
                        <a href="{{ route('student.daily-challenge') }}"
                            class="btn btn-primary shadow-lg text-decoration-none">
                            <i class="bi bi-rocket-takeoff"></i> Start Challenge
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="stats-bar">
            <div class="stat-item">
                <div class="stat-icon text-warning"><i class="bi bi-fire"></i></div>
                <div class="stat-info">
                    <div class="label">Daily Streak</div>
                    <div class="value">{{ auth()->guard('student')->user()->streak }} Days</div>
                </div>
            </div>

            <div class="xp-progress">
                <div class="stat-info" style="display:flex; justify-content:space-between; align-items:flex-end;">
                    <div>
                        <div class="label">Level {{ auth()->guard('student')->user()->level }}</div>
                        <div class="value">{{ auth()->guard('student')->user()->xp }} XP</div>
                    </div>
                    @php
                        $nextLevelXp = auth()->guard('student')->user()->level * 100;
                        $progress = (auth()->guard('student')->user()->xp / $nextLevelXp) * 100;
                    @endphp
                    <div style="font-size: 0.8rem; font-weight: 700; color: var(--text-light);">
                        {{ round($progress) }}% to Level {{ auth()->guard('student')->user()->level + 1 }}
                    </div>
                </div>
                <div class="progress-container">
                    <div class="progress-fill" style="width: {{ $progress }}%"></div>
                </div>
            </div>

            <div class="stat-item">
                <div class="stat-icon text-primary"><i class="bi bi-trophy"></i></div>
                <div class="stat-info">
                    <div class="label">Accuracy</div>
                    <div class="value">{{ auth()->guard('student')->user()->accuracy }}%</div>
                </div>
            </div>
        </div>
    </div>

    <h2 class="section-title animate-in" style="--delay: 0.2s">
        <i class="bi bi-journal-check"></i> Your Subjects
    </h2>

    @if ($subjects->isEmpty())
        <div class="card animate-in" style="text-align:center; padding:5rem; --delay: 0.3s">
            <div style="font-size:4rem; margin-bottom:1rem;"><i class="bi bi-cup-hot"></i></div>
            <h3>No subjects assigned yet</h3>
            <p>Check with your teacher to get started.</p>
        </div>
    @else
        <div class="grid-3">
            @php
                $icons = [
                    'bi-calculator-fill',
                    'bi-flag-fill',
                    'bi-journal-richtext',
                    'bi-globe-asia-australia',
                    'bi-journal-text',
                    'bi-palette-fill',
                    'bi-laptop',
                    'bi-activity',
                    'bi-bar-chart-line',
                    'bi-bank',
                ];
                $i = 0;
            @endphp
            @foreach ($subjects as $subject)
                <div class="animate-in" style="--delay: {{ 0.2 + $loop->index * 0.1 }}s">
                    <a href="{{ route('student.subject.show', $subject->id) }}" class="subject-card text-decoration-none">
                        <div class="sub-header">
                            <div class="sub-icon"><i class="bi {{ $icons[$i++ % count($icons)] }}"></i></div>
                            <div class="sub-info">
                                <div class="sub-name">{{ $subject->name }}</div>
                                <div class="sub-class">Class {{ $subject->class }}</div>
                            </div>
                        </div>
                        <div class="sub-arrow-fixed">
                            <i class="bi bi-arrow-right-short" style="font-size: 1.5rem;"></i>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    @endif
@endsection
