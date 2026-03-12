@extends('layouts.student')
@section('title', 'Your Progress — Revizo')

@section('content')
    <div class="dashboard-container">
        <div class="page-header animate-in mt-4 mb-4">
            <h1 class="fs-4"><i class="bi bi-rocket-takeoff"></i> Your Growth Journey</h1>
            <p class="text-muted mb-0">Visualize your progress and celebrate your learning milestones.</p>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-12">
                <div class="rounded-4 p-4 text-white position-relative overflow-hidden animate-in d-flex flex-column flex-md-row justify-content-between align-items-md-start gap-4"
                    style="--delay: 0.1s; background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);">
                    <div class="position-relative z-2 w-100" style="max-width: 600px;">
                        @php
                            $leagueColor = match ($student->league) {
                                'bronze' => '#cd7f32',
                                'silver' => '#c0c0c0',
                                'diamond' => '#b9f2ff',
                                'champion' => '#ffd700',
                                default => '#ffffff',
                            };
                            $textColor =
                                $student->league == 'champion' || $student->league == 'diamond' ? '#1a1a1a' : '#ffffff';
                        @endphp
                        <div class="badge rounded-pill px-3 py-2 mb-3 fw-bold shadow-sm"
                            style="background: {{ $leagueColor }}; color: {{ $textColor }}; font-size: 0.85rem; border: 1px solid rgba(255,255,255,0.2);">
                            <i class="bi bi-trophy-fill me-1"></i>
                            {{ strtoupper($student->league) }} {{ $student->league_level }}
                        </div>

                        <p class="fw-bold mb-4" style="font-size: 1.1rem; opacity: 0.95;">
                            @php
                                // Calculate next threshold for progress bar
                                $leagues = ['bronze', 'silver', 'diamond', 'champion'];
                                $levels = [5, 4, 3, 2, 1];
                                $nextThreshold = 0;
                                $found = false;
                                foreach ($leagues as $l) {
                                    foreach ($levels as $lv) {
                                        $key = "league_{$l}_{$lv}_xp";
                                        $threshold = \App\Models\Setting::get($key, 0);
                                        if ($threshold > $student->xp) {
                                            $nextThreshold = $threshold;
                                            $nextName = ucfirst($l) . ' ' . $lv;
                                            $found = true;
                                            break 2;
                                        }
                                    }
                                }

                                if ($found) {
                                    $remaining = $nextThreshold - $student->xp;
                                    $progressPercent =
                                        $student->xp > 0 ? min(100, ($student->xp / $nextThreshold) * 100) : 0;
                                    $text = "$remaining XP to $nextName! Keep pushing!";
                                } else {
                                    $progressPercent = 100;
                                    $text = "You've reached the Max League! You are a Champion!";
                                }
                            @endphp
                            {{ $text }}
                        </p>
                        <div class="rounded-pill position-relative overflow-hidden bg-black bg-opacity-25"
                            style="height: 16px; border: 2px solid rgba(255,255,255,0.3);">
                            <div class="h-100 rounded-pill shadow-sm"
                                style="background: linear-gradient(90deg, #FFD700 0%, #FF8C00 100%); width: {{ $progressPercent }}%; transition: width 1s ease-in-out;">
                            </div>
                        </div>
                    </div>

                    <div class="text-md-end text-start position-relative z-3 flex-shrink-0">
                        <div class="fw-bolder" style="font-size: 3rem; line-height: 1;">{{ $student->xp }}</div>
                        <div class="fw-bold text-white-50" style="letter-spacing: 0.05em; font-size: 0.9rem;">TOTAL XP</div>
                    </div>

                    <div class="position-absolute text-white"
                        style="font-size: 10rem; left: -20px; bottom: -60px; transform: rotate(15deg); opacity: 0.1;"><i
                            class="bi bi-mortarboard"></i></div>
                </div>
            </div>

            <div class="col-lg-7 col-xl-8">
                <div class="card border-0 shadow-sm rounded-4 p-4 animate-in h-100" style="--delay: 0.2s">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
                        <div>
                            <div class="card-title fs-5 fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                                <i class="bi bi-calendar-event"></i>
                                {{ $currentMonthName }} {{ $currentYear }} Commitment
                            </div>
                            <p class="small text-muted fw-bold mt-1 mb-0">
                                Daily Streak Tracking</p>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <a href="{{ $prevUrl }}" class="btn btn-light btn-sm px-2 py-1">&lt;</a>
                            <div class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2 fw-bold"
                                style="font-size: 0.85rem;">
                                {{ $student->streak }} Day Streak
                            </div>
                            <a href="{{ $nextUrl }}" class="btn btn-light btn-sm px-2 py-1">&gt;</a>
                        </div>
                    </div>

                    <div class="d-grid w-100 flex-grow-1"
                        style="grid-template-columns: repeat(7, 1fr); gap: 0.5rem; align-content: center;">
                        @foreach (['S', 'M', 'T', 'W', 'T', 'F', 'S'] as $h)
                            <div class="text-center small fw-bold text-muted pb-1">
                                {{ $h }}</div>
                        @endforeach

                        @foreach ($calendarData as $day)
                            @if ($day['padding'])
                                <div class="bg-transparent border-0"></div>
                            @else
                                @php
                                    $cellBg = 'bg-light';
                                    $cellBorder = 'border';
                                    $cellText = 'text-muted';
                                    $cellShadow = '';
                                    if ($day['active']) {
                                        $cellBg = 'bg-success';
                                        $cellBorder = 'border-0';
                                        $cellText = 'text-white';
                                        $cellShadow = 'shadow-sm';
                                    } elseif ($day['isPast']) {
                                        $cellBg = 'bg-danger-subtle';
                                        $cellBorder = 'border border-danger-subtle';
                                        $cellText = 'text-danger';
                                    }

                                    if ($day['isToday'] && !$day['active']) {
                                        $cellBorder = 'border border-2 border-primary';
                                        $cellText = 'text-primary';
                                    }
                                @endphp
                                <div class="w-100 mx-auto rounded-3 d-flex align-items-center justify-content-center fw-bold position-relative {{ $cellBg }} {{ $cellBorder }} {{ $cellText }} {{ $cellShadow }}"
                                    style="max-width: 60px; aspect-ratio: 1; font-size: 0.85rem; transition: all 0.2s;">
                                    {{ $day['day'] }}
                                    @if ($day['isToday'])
                                        <div class="position-absolute rounded-circle"
                                            style="bottom: 4px; width: 4px; height: 4px; background-color: {{ $day['active'] ? '#fff' : 'var(--bs-primary)' }};">
                                        </div>
                                    @endif
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <div class="d-flex align-items-center gap-3 mt-4 pt-3 border-top w-100 flex-wrap mt-auto">
                        <div class="d-flex align-items-center gap-2">
                            <div class="bg-success rounded" style="width: 12px; height: 12px;"></div>
                            <span class="small fw-bold text-muted" style="font-size: 0.7rem;">Active</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <div class="bg-danger-subtle border border-danger-subtle rounded"
                                style="width: 12px; height: 12px;"></div>
                            <span class="small fw-bold text-muted" style="font-size: 0.7rem;">Missed</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <div class="bg-white border border-2 border-primary rounded" style="width: 12px; height: 12px;">
                            </div>
                            <span class="small fw-bold text-muted" style="font-size: 0.7rem;">Today</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5 col-xl-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 animate-in h-100" style="--delay: 0.2s">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
                        <div class="card-title fs-5 fw-bold text-dark mb-0 d-flex align-items-center gap-2"><i
                                class="bi bi-target"></i> Mastery Level</div>
                        <a href="{{ route('student.history') }}"
                            class="btn btn-outline-secondary btn-sm fw-bold px-3 py-2 rounded-3">
                            <i class="bi bi-clock-history"></i> History
                        </a>
                    </div>
                    <div class="progress-circle"
                        style="background: conic-gradient(var(--primary) calc({{ $student->accuracy }} * 1%), var(--border) 0);">
                        <div class="percent-label">{{ round($student->accuracy) }}%</div>
                    </div>
                    <div class="text-center mt-4 mb-4">
                        @php
                            $acc = $student->accuracy;
                            $label = 'Beginner';
                            $colorClass = 'text-secondary';
                            if ($acc >= 90) {
                                $label = 'Elite Expert';
                                $colorClass = 'text-success';
                            } elseif ($acc >= 80) {
                                $label = 'High Proficiency';
                                $colorClass = 'text-primary';
                            } elseif ($acc >= 60) {
                                $label = 'Average Learner';
                                $colorClass = 'text-warning';
                            } else {
                                $label = 'Needs Focus';
                                $colorClass = 'text-danger';
                            }
                        @endphp
                        <div class="fw-bold fs-5 {{ $colorClass }}">{{ $label }}
                        </div>
                    </div>

                    @if ($recentQuizzes->isNotEmpty())
                        <div class="border-top pt-4 mt-auto">
                            <div class="small fw-bold text-muted text-uppercase mb-3">
                                Recent Activity
                            </div>
                            <div class="d-flex flex-column gap-3">
                                @foreach ($recentQuizzes as $quiz)
                                    <a href="{{ route('student.quiz.results', $quiz->id) }}"
                                        class="text-decoration-none text-dark bg-light border rounded-4 p-3 d-flex align-items-center justify-content-between transition-all"
                                        onmouseover="this.classList.add('border-primary', 'shadow-sm')"
                                        onmouseout="this.classList.remove('border-primary', 'shadow-sm')">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="rounded-3 d-flex align-items-center justify-content-center border"
                                                style="width: 40px; height: 40px; font-size: 1.25rem; {{ $quiz->type === 'daily' ? 'background: linear-gradient(135deg, #EEF2FF, #E0E7FF); color: #4F46E5; border-color: #C7D2FE !important;' : 'background: #fff; color: var(--bs-primary);' }}">
                                                {!! $quiz->type === 'daily' ? '<i class="bi bi-lightning-fill"></i>' : '<i class="bi bi-journal-text"></i>' !!}
                                            </div>
                                            <div>
                                                <div class="fw-bold" style="font-size: 0.95rem;">
                                                    {{ $quiz->type === 'daily' ? 'Daily Challenge' : 'Chapter Quiz' }}
                                                </div>
                                                <div class="small fw-semibold text-muted">
                                                    {{ $quiz->created_at->diffForHumans() }}</div>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <div class="fw-bold fs-5">
                                                {{ $quiz->score }}<span class="text-muted"
                                                    style="font-size: 0.8rem;">/{{ $quiz->total }}</span>
                                            </div>
                                            <div class="small fw-bold text-success">
                                                {{ round($quiz->accuracy) }}%</div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 p-4 animate-in" style="--delay: 0.4s;">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
                        <div class="card-title fs-5 fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                            <i class="bi bi-bar-chart-fill"></i> Learning Momentum ({{ $days }} Days)
                        </div>
                        <div class="d-flex flex-wrap align-items-center gap-3">
                            <form method="GET" action="{{ route('student.dashboard') }}">
                                <input type="hidden" name="month" value="{{ $currentMonth }}">
                                <input type="hidden" name="year" value="{{ $currentYear }}">
                                <select name="days" onchange="this.form.submit()"
                                    class="form-select form-select-sm fw-bold border" style="font-size: 0.8rem;">
                                    <option value="7" {{ $days == 7 ? 'selected' : '' }}>Last 7 Days</option>
                                    <option value="14" {{ $days == 14 ? 'selected' : '' }}>Last 14 Days</option>
                                    <option value="30" {{ $days == 30 ? 'selected' : '' }}>Last 30 Days</option>
                                </select>
                            </form>
                            <div class="small fw-bold text-muted">
                                Total Quizzes: {{ collect($activityData)->sum('count') }}
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-end gap-2 overflow-x-auto w-100 pb-2"
                        style="height: 220px; padding-top: 40px;">
                        @php
                            $maxCount = collect($activityData)->max('count') ?: 1;
                        @endphp
                        @foreach ($activityData as $data)
                            @php
                                $h = ($data['count'] / $maxCount) * 100;
                                if ($data['count'] > 0) {
                                    $h = max($h, 15);
                                }
                            @endphp
                            <div class="d-flex flex-column justify-content-end align-items-center gap-2 h-100 flex-grow-1"
                                style="min-width: 20px;">
                                <div class="activity-bar position-relative transition-all {{ $data['count'] > 0 ? 'bg-primary' : 'bg-light' }}"
                                    style="height: {{ $h == 0 ? '4px' : $h . '%' }};"
                                    title="{{ $data['date'] }}: {{ $data['count'] }} Quizzes">
                                    @if ($data['count'] > 0)
                                        <div class="bar-tooltip">
                                            @if ($data['count'] == 1)
                                                Quiz: {{ $data['count'] }}
                                            @else
                                                Quizzes: {{ $data['count'] }}
                                            @endif
                                        </div>
                                    @endif
                                </div>
                                <span class="fw-bold text-muted"
                                    style="font-size: 0.7rem;">{{ date('j', strtotime($data['date'])) }}</span>
                            </div>
                        @endforeach
                    </div>
                    <div
                        class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top small fw-bold text-muted w-100 flex-wrap">
                        <span><i class="bi bi-calendar3"></i> {{ $days }} DAYS AGO</span>
                        <span class="text-primary">TODAY ({{ date('M j') }})</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
