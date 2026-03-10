@extends('layouts.student')

@section('content')
    <div class="results-wrapper animate-in">
        <!-- Header -->
        <div class="page-header header-container">
            <div>
                <h1 style="margin-bottom: 0.25rem;">Quiz Results</h1>
                <p style="margin: 0; color: var(--text-light);">
                    Detailed performance report for your
                    {{ $attempt->type === 'daily' ? 'Daily Challenge' : 'Chapter Quiz' }}
                </p>
            </div>
            <div>
                <a href="{{ route('student.subjects') }}" class="btn btn-ghost"
                    style="display: flex; align-items: center; gap: 0.5rem;">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
            </div>
        </div>

        @if (empty($results))
            <div class="card" style="text-align: center; padding: 4rem 2rem;">
                <div style="font-size: 3rem; margin-bottom: 1rem;"><i class="bi bi-info-circle"></i></div>
                <h3 style="color: var(--text);">No detailed breakdown available</h3>
                <p style="color: var(--text-light); max-width: 400px; margin: 0 auto 1.5rem;">
                    This attempt only recorded the final score. Individual question tracking wasn't available for this
                    session.
                </p>
                <a href="{{ route('student.dashboard') }}" class="btn btn-primary">Go to Dashboard</a>
            </div>
        @else
            <!-- KPI Cards -->
            <div class="grid-3" style="margin-bottom: 3rem;">
                <!-- Card 1 -->
                <div class="card stat-card">
                    <div class="stat-icon" style="background: var(--primary-light); color: var(--primary);">
                        <i class="bi bi-bullseye"></i>
                    </div>
                    <div>
                        <p class="stat-label">Score</p>
                        <h2 class="stat-value">
                            {{ $attempt->score }} <span class="stat-value-sub">/ {{ $attempt->total }}</span>
                        </h2>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="card stat-card">
                    <div class="stat-icon" style="background: rgba(34, 197, 94, 0.1); color: var(--success);">
                        <i class="bi bi-graph-up-arrow"></i>
                    </div>
                    <div>
                        <p class="stat-label">Accuracy</p>
                        <h2 class="stat-value">{{ round($attempt->accuracy) }}%</h2>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="card stat-card">
                    <div class="stat-icon" style="background: rgba(245, 158, 11, 0.1); color: var(--warning);">
                        <i class="bi bi-lightning-charge-fill"></i>
                    </div>
                    <div>
                        <p class="stat-label">XP Earned</p>
                        <h2 class="stat-value">+{{ $attempt->score * 2 }}</h2>
                    </div>
                </div>
            </div>

            <!-- Question Analysis -->
            <div class="card" style="padding: 0; overflow: hidden;">
                <div class="analysis-header">
                    <h3 style="margin: 0; font-size: 1.25rem; font-weight: 700; color: var(--text);">Question Breakdown</h3>
                    <div class="theme-btn-group">
                        <button type="button" class="theme-btn active" id="btn-filter-all"
                            onclick="filterQuestions('all')">All</button>
                        <button type="button" class="theme-btn text-success" id="btn-filter-correct"
                            onclick="filterQuestions('correct')">
                            <i class="bi bi-check-circle" style="margin-right: 4px;"></i> Correct
                        </button>
                        <button type="button" class="theme-btn text-danger" id="btn-filter-incorrect"
                            onclick="filterQuestions('incorrect')">
                            <i class="bi bi-x-circle" style="margin-right: 4px;"></i> Incorrect
                        </button>
                    </div>
                </div>

                <div class="analysis-body">
                    @foreach ($results as $index => $result)
                        <div
                            class="question-list-item question-item {{ $result['is_correct'] ? 'is-correct' : 'is-incorrect' }}">

                            <!-- Question Header -->
                            <div class="question-header">
                                <div class="question-icon">
                                    @if ($result['is_correct'])
                                        <i class="bi bi-check-circle-fill" style="color: var(--success);"></i>
                                    @else
                                        <i class="bi bi-x-circle-fill" style="color: var(--danger);"></i>
                                    @endif
                                </div>
                                <div class="question-text-wrapper">
                                    <div style="margin-bottom: 0.5rem;">
                                        <span class="question-badge"
                                            style="background: {{ $result['is_correct'] ? 'var(--success)' : 'var(--danger)' }};">
                                            Q{{ $index + 1 }}
                                        </span>
                                    </div>
                                    <h4 class="question-text">{{ $result['question'] }}</h4>
                                </div>
                            </div>

                            <!-- Options -->
                            <div class="options-grid">
                                @foreach ($result['options'] as $key => $val)
                                    @php
                                        $isCorrect = $key === $result['correct_answer'];
                                        $isUser = $key === $result['user_answer'];

                                        $borderCol = 'var(--border)';
                                        $bgCol = 'var(--surface)';
                                        $textCol = 'var(--text-light)';
                                        $fontWeight = '500';
                                        $icon = '';

                                        if ($isCorrect) {
                                            $borderCol = 'var(--success)';
                                            $bgCol = 'rgba(34, 197, 94, 0.05)';
                                            $textCol = 'var(--success)';
                                            $fontWeight = '700';
                                            $icon =
                                                '<i class="bi bi-check-lg" style="margin-left: auto; font-size: 1.25rem;"></i>';
                                        } elseif ($isUser && !$isCorrect) {
                                            $borderCol = 'var(--danger)';
                                            $bgCol = 'rgba(239, 68, 68, 0.05)';
                                            $textCol = 'var(--danger)';
                                            $fontWeight = '700';
                                            $icon =
                                                '<i class="bi bi-x-lg" style="margin-left: auto; font-size: 1.25rem;"></i>';
                                        }
                                    @endphp
                                    <div class="option-box"
                                        style="border-color: {{ $borderCol }}; background: {{ $bgCol }}; color: {{ $textCol }}; font-weight: {{ $fontWeight }};">
                                        <span class="option-key"
                                            style="color: {{ $isCorrect || ($isUser && !$isCorrect) ? $textCol : 'var(--text-light)' }};">{{ $key }}</span>
                                        <span class="option-val">{{ $val }}</span>
                                        {!! $icon !!}
                                    </div>
                                @endforeach
                            </div>

                            <!-- Explanation -->
                            @if ($result['explanation'])
                                <div class="explanation-box">
                                    <h5
                                        style="font-weight: 700; color: var(--primary-dark); margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem; font-size: 0.95rem; margin-top: 0;">
                                        <i class="bi bi-lightbulb-fill"></i> Solution
                                    </h5>
                                    <div style="color: var(--text); font-size: 0.95rem; line-height: 1.7; margin: 0;">
                                        {!! nl2br(e($result['explanation'])) !!}</div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>


    <script>
        function filterQuestions(filterType) {
            // Reset button states
            const buttons = document.querySelectorAll('.theme-btn');
            buttons.forEach(btn => btn.classList.remove('active'));
            document.getElementById('btn-filter-' + filterType).classList.add('active');

            // Filter list items
            const items = document.querySelectorAll('.question-item');
            items.forEach(item => {
                if (filterType === 'all') {
                    item.style.display = 'block';
                } else if (filterType === 'correct') {
                    item.style.display = item.classList.contains('is-correct') ? 'block' : 'none';
                } else if (filterType === 'incorrect') {
                    item.style.display = item.classList.contains('is-incorrect') ? 'block' : 'none';
                }
            });
        }
    </script>
@endsection
