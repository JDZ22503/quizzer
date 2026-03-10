@extends('layouts.student')
@section('title', 'Your Progress — Revizo')

@push('styles')
    <style>
        .dashboard-container {
            max-width: 1000px;
            margin: 0 auto;
        }

        .dash-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 1.5rem;
        }

        .stat-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            box-shadow: var(--shadow-sm);
        }

        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }

        .card-title {
            font-size: 1.15rem;
            font-weight: 800;
            color: var(--text);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .xp-vignette {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            color: #fff;
            position: relative;
            overflow: hidden;
            grid-column: span 2;
            margin-bottom: 1rem;
        }

        .vignette-content {
            position: relative;
            z-index: 2;
        }

        .xp-display {
            position: absolute;
            top: 1.5rem;
            right: 1.5rem;
            text-align: right;
            z-index: 3;
        }

        .xp-large {
            font-size: 2.5rem;
            font-weight: 800;
            line-height: 1;
            margin: 0;
        }

        .xp-label {
            font-size: 0.9rem;
            font-weight: 700;
            opacity: 0.8;
            letter-spacing: 0.05em;
        }

        .vignette-icon {
            position: absolute;
            left: -20px;
            bottom: -20px;
            font-size: 8rem;
            opacity: 0.1;
            transform: rotate(15deg);
        }

        .lvl-badge {
            background: rgba(255, 255, 255, 0.2);
            padding: 0.4rem 1rem;
            border-radius: 30px;
            font-size: 0.85rem;
            font-weight: 800;
            display: inline-block;
            margin-bottom: 1rem;
            backdrop-filter: blur(4px);
        }

        .streak-calendar {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 0.75rem;
        }

        .day-box {
            aspect-ratio: 1;
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--text-light);
            transition: all 0.2s;
        }

        .day-box.active {
            background: var(--success);
            color: #fff;
            border-color: var(--success);
            box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
        }

        .progress-circle {
            width: 110px;
            height: 110px;
            border-radius: 50%;
            background: conic-gradient(var(--primary) calc({{ $student->accuracy }} * 1%), var(--border) 0);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            position: relative;
        }

        .progress-circle::after {
            content: '{{ $student->accuracy }}%';
            width: 85px;
            height: 85px;
            background: var(--surface);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--text);
        }

        @media (max-width: 850px) {
            .dash-grid {
                grid-template-columns: 1fr;
            }

            .xp-vignette {
                grid-column: span 1;
            }
        }
    </style>
@endpush

@section('content')
    <div class="dashboard-container">
        <div class="page-header animate-in" style="margin-bottom: 1.5rem;">
            <h1 style="font-size: 1.75rem;"><i class="bi bi-rocket-takeoff"></i> Your Growth Journey</h1>
            <p style="font-size: 1rem; color: var(--text-light);">Visualize your progress and celebrate your learning
                milestones.</p>
        </div>

        <div class="dash-grid">
            <div class="xp-vignette animate-in"
                style="--delay: 0.1s; background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);">
                <div class="vignette-content">
                    <div class="lvl-badge">LEVEL {{ $student->level }} STUDENT</div>
                    <div class="xp-display">
                        <div class="xp-large">{{ $student->xp }}</div>
                        <div class="xp-label">TOTAL XP</div>
                    </div>
                    <p style="font-weight: 600; font-size: 1.1rem; opacity: 0.95; max-width: 60%;">
                        @php
                            $nextLevelXp = $student->level * 100;
                            $currentLevelBase = ($student->level - 1) * 100;
                            $progressInLevel = $student->xp - $currentLevelBase;
                            $remaining = 100 - $progressInLevel;
                            $progressPercent = ($progressInLevel / 100) * 100;
                        @endphp
                        {{ $remaining }} XP to Level {{ $student->level + 1 }}! Keep pushing!
                    </p>
                    <div class="progress-container"
                        style="background: rgba(0,0,0,0.25); height: 16px; margin-top: 1.5rem; border: 2px solid rgba(255,255,255,0.3); border-radius: 20px; max-width: 60%; overflow: hidden; position: relative;">
                        <!-- Highlight Glow -->
                        <div class="progress-fill"
                            style="background: linear-gradient(90deg, #FFD700 0%, #FF8C00 100%); width: {{ $progressPercent }}%; height: 100%; box-shadow: 0 0 15px rgba(255, 215, 0, 0.6); border-radius: 20px; transition: width 1s ease-in-out;">
                        </div>
                    </div>
                </div>
                <div class="vignette-icon"><i class="bi bi-mortarboard"></i></div>
            </div>

            <div class="stat-card animate-in" style="--delay: 0.2s">
                <div class="card-header" style="margin-bottom: 1rem;">
                    <div>
                        <div class="card-title" style="font-size: 1.05rem;"><i class="bi bi-calendar-event"></i>
                            {{ $currentMonthName }} {{ $currentYear }}
                            Commitment</div>
                        <p style="font-size: 0.7rem; color: var(--text-light); font-weight: 700; margin-top: 0.15rem;">
                            Daily Streak Tracking</p>
                    </div>
                    <div style="display: flex; gap: 0.5rem; align-items: center;">
                        <a href="{{ $prevUrl }}" class="btn btn-ghost"
                            style="padding: 0.25rem 0.5rem; height: auto;">&lt;</a>
                        <div
                            style="font-weight: 800; color: var(--primary); background: var(--primary-light); padding: 0.25rem 0.75rem; border-radius: 8px; font-size: 0.8rem;">
                            {{ $student->streak }} Day Streak
                        </div>
                        <a href="{{ $nextUrl }}" class="btn btn-ghost"
                            style="padding: 0.25rem 0.5rem; height: auto;">&gt;</a>
                    </div>
                </div>

                <div class="calendar-grid"
                    style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 8px; width: 100%;">
                    @foreach (['S', 'M', 'T', 'W', 'T', 'F', 'S'] as $h)
                        <div
                            style="text-align: center; font-size: 0.75rem; font-weight: 900; color: var(--text-light); padding-bottom: 4px;">
                            {{ $h }}</div>
                    @endforeach

                    @foreach ($calendarData as $day)
                        @if ($day['padding'])
                            <div style="width: 100%; max-width: 60px; aspect-ratio: 1; margin: 0 auto;"></div>
                        @else
                            @php
                                $cellBg = '#F8FAFC';
                                $border = '1px solid var(--border)';
                                $color = 'var(--text-light)';
                                if ($day['active']) {
                                    $cellBg = 'var(--success)';
                                    $border = 'none';
                                    $color = '#fff';
                                } elseif ($day['isPast']) {
                                    $cellBg = '#FEF2F2';
                                    $border = '1px solid #FECACA';
                                    $color = '#DC2626';
                                }

                                if ($day['isToday'] && !$day['active']) {
                                    $border = '2px solid var(--primary)';
                                    $color = 'var(--primary)';
                                }
                            @endphp
                            <div class="calendar-day"
                                style="width: 100%; max-width: 60px; aspect-ratio: 1; margin: 0 auto; border-radius: 8px; background: {{ $cellBg }}; 
                                        display: flex; align-items: center; justify-content: center; font-size: 0.85rem; font-weight: 800; 
                                        color: {{ $color }}; border: {{ $border }}; transition: all 0.2s; position: relative;">
                                {{ $day['day'] }}
                                @if ($day['isToday'])
                                    <div
                                        style="position: absolute; bottom: 2px; width: 4px; height: 4px; border-radius: 50%; background: {{ $day['active'] ? '#fff' : 'var(--primary)' }};">
                                    </div>
                                @endif
                            </div>
                        @endif
                    @endforeach
                </div>

                <div
                    style="display: flex; align-items: center; gap: 1rem; margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid var(--border);">
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <div style="width: 12px; height: 12px; background: var(--success); border-radius: 3px;"></div>
                        <span style="font-size: 0.7rem; font-weight: 700; color: var(--text-light);">Active</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <div
                            style="width: 12px; height: 12px; background: #FEF2F2; border: 1px solid #FECACA; border-radius: 3px;">
                        </div>
                        <span style="font-size: 0.7rem; font-weight: 700; color: var(--text-light);">Missed</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <div
                            style="width: 12px; height: 12px; background: #fff; border: 2px solid var(--primary); border-radius: 3px;">
                        </div>
                        <span style="font-size: 0.7rem; font-weight: 700; color: var(--text-light);">Today</span>
                    </div>
                </div>
            </div>

            <div class="stat-card animate-in" style="--delay: 0.3s">
                <div class="card-header">
                    <div class="card-title" style="font-size: 1.05rem;"><i class="bi bi-target"></i> Mastery Level</div>
                    <a href="{{ route('student.history') }}" class="btn btn-ghost"
                        style="font-size: 0.75rem; font-weight: 800; padding: 0.4rem 0.8rem; border: 1px solid var(--border); border-radius: 8px;">
                        <i class="bi bi-clock-history"></i> History
                    </a>
                </div>
                <div class="progress-circle"></div>
                <div style="text-align: center; margin-top: 1.5rem; margin-bottom: 2rem;">
                    @php
                        $acc = $student->accuracy;
                        $label = 'Beginner';
                        $color = '#94a3b8';
                        if ($acc >= 90) {
                            $label = 'Elite Expert';
                            $color = '#10b981';
                        } elseif ($acc >= 80) {
                            $label = 'High Proficiency';
                            $color = '#3b82f6';
                        } elseif ($acc >= 60) {
                            $label = 'Average Learner';
                            $color = '#f59e0b';
                        } else {
                            $label = 'Needs Focus';
                            $color = '#ef4444';
                        }
                    @endphp
                    <div style="font-weight: 800; color: {{ $color }}; font-size: 1.1rem;">{{ $label }}
                    </div>
                </div>

                @if ($recentQuizzes->isNotEmpty())
                    <div style="border-top: 1px solid var(--border); padding-top: 1.5rem;">
                        <div
                            style="font-size: 0.85rem; font-weight: 800; color: var(--text-light); text-transform: uppercase; margin-bottom: 1rem;">
                            Recent Activity
                        </div>
                        <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                            @foreach ($recentQuizzes as $quiz)
                                <a href="{{ route('student.quiz.results', $quiz->id) }}"
                                    style="display: flex; align-items: center; justify-content: space-between; padding: 0.75rem 1rem; background: var(--bg); border: 1px solid var(--border); border-radius: 10px; text-decoration: none; color: inherit; transition: all 0.2s;"
                                    onmouseover="this.style.borderColor='var(--primary)'"
                                    onmouseout="this.style.borderColor='var(--border)'">
                                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                                        <div
                                            style="width: 36px; height: 36px; border-radius: 8px; background: {{ $quiz->type === 'daily' ? 'linear-gradient(135deg, #EEF2FF, #E0E7FF)' : 'var(--surface)' }}; color: {{ $quiz->type === 'daily' ? '#4F46E5' : 'var(--primary)' }}; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; border: 1px solid {{ $quiz->type === 'daily' ? '#C7D2FE' : 'var(--border)' }};">
                                            {!! $quiz->type === 'daily' ? '<i class="bi bi-lightning-fill"></i>' : '<i class="bi bi-journal-text"></i>' !!}
                                        </div>
                                        <div>
                                            <div style="font-size: 0.9rem; font-weight: 700; color: var(--text);">
                                                {{ $quiz->type === 'daily' ? 'Daily Challenge' : 'Chapter Quiz' }}</div>
                                            <div style="font-size: 0.75rem; color: var(--text-light); font-weight: 600;">
                                                {{ $quiz->created_at->diffForHumans() }}</div>
                                        </div>
                                    </div>
                                    <div style="text-align: right;">
                                        <div style="font-size: 1.1rem; font-weight: 800; color: var(--text);">
                                            {{ $quiz->score }}<span
                                                style="font-size: 0.8rem; color: var(--text-light);">/{{ $quiz->total }}</span>
                                        </div>
                                        <div style="font-size: 0.75rem; color: var(--success); font-weight: 700;">
                                            {{ round($quiz->accuracy) }}%</div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <div class="stat-card animate-in" style="--delay: 0.4s; grid-column: span 2;">
                <div class="card-header">
                    <div class="card-title"><i class="bi bi-bar-chart-fill"></i> Learning Momentum ({{ $days }}
                        Days)</div>
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <form method="GET" action="{{ route('student.dashboard') }}">
                            <input type="hidden" name="month" value="{{ $currentMonth }}">
                            <input type="hidden" name="year" value="{{ $currentYear }}">
                            <select name="days" onchange="this.form.submit()"
                                style="padding: 0.25rem 0.5rem; border: 1px solid var(--border); border-radius: 6px; font-size: 0.75rem; font-weight: 700;">
                                <option value="7" {{ $days == 7 ? 'selected' : '' }}>Last 7 Days</option>
                                <option value="14" {{ $days == 14 ? 'selected' : '' }}>Last 14 Days</option>
                                <option value="30" {{ $days == 30 ? 'selected' : '' }}>Last 30 Days</option>
                            </select>
                        </form>
                        <div style="font-size: 0.8rem; font-weight: 700; color: var(--text-light);">
                            Total Quizzes: {{ collect($activityData)->sum('count') }}
                        </div>
                    </div>
                </div>
                <div
                    style="height: 220px; box-sizing: border-box; display: flex; align-items: flex-end; gap: 0.5rem; padding: 40px 2.5rem 0.5rem 0.5rem; margin-top: 1rem; overflow-x: auto;">
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
                        <div class="activity-bar-container"
                            style="min-width: 30px; flex: 1; height: 100%; display: flex; flex-direction: column; justify-content: flex-end; align-items: center; gap: 0.5rem;">
                            <div class="activity-bar"
                                style="width: 100%; background: {{ $data['count'] > 0 ? 'var(--primary)' : '#F1F5F9' }}; 
                                        height: {{ $h == 0 ? '4px' : $h . '%' }}; 
                                        border-radius: 8px 8px 4px 4px; 
                                        transition: all 0.5s ease;
                                        position: relative;"
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
                            <span
                                style="font-size: 0.65rem; font-weight: 800; color: var(--text-light);">{{ date('j', strtotime($data['date'])) }}</span>
                        </div>
                    @endforeach
                </div>
                <div
                    style="display: flex; justify-content: space-between; font-size: 0.75rem; color: var(--text-light); font-weight: 700; margin-top: 1.5rem; border-top: 1px solid var(--border); padding-top: 1rem;">
                    <span><i class="bi bi-calendar3"></i> {{ $days }} DAYS AGO</span>
                    <span style="color: var(--primary);">TODAY ({{ date('M j') }})</span>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .activity-bar:hover {
            background: var(--primary-dark) !important;
            transform: scaleX(1.1);
        }

        .bar-tooltip {
            position: absolute;
            top: -25px;
            left: 50%;
            transform: translateX(-50%);
            background: var(--text);
            color: #fff;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 0.6rem;
            opacity: 0;
            transition: opacity 0.2s;
            pointer-events: none;
        }

        .activity-bar:hover .bar-tooltip {
            opacity: 1;
        }
    </style>
@endpush
