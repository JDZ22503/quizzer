@extends('layouts.student')

@section('title', 'Quiz History — Revizo')

@push('styles')
    <style>
        .history-container {
            max-width: 900px;
            margin: 0 auto;
        }

        .history-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            margin-bottom: 1rem;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: space-between;
            text-decoration: none;
            color: inherit;
        }

        .history-card:hover {
            border-color: var(--primary);
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
        }

        .quiz-type-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-right: 1.5rem;
        }

        .type-daily {
            background: linear-gradient(135deg, #EEF2FF, #E0E7FF);
            color: #4F46E5;
            border: 1px solid #C7D2FE;
        }

        .type-chapter {
            background: var(--bg);
            color: var(--primary);
            border: 1px solid var(--border);
        }

        .quiz-info {
            flex-grow: 1;
        }

        .quiz-title {
            font-size: 1.1rem;
            font-weight: 800;
            color: var(--text);
            margin-bottom: 0.25rem;
        }

        .quiz-meta {
            font-size: 0.85rem;
            color: var(--text-light);
            font-weight: 600;
            display: flex;
            gap: 1rem;
        }

        .score-pill {
            text-align: right;
        }

        .score-value {
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--text);
        }

        .accuracy-badge {
            font-size: 0.8rem;
            font-weight: 700;
            color: var(--success);
        }

        .pagination-container {
            margin-top: 2rem;
            display: flex;
            justify-content: center;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: var(--surface);
            border: 1px dashed var(--border);
            border-radius: var(--radius-lg);
        }
    </style>
@endpush

@section('content')
    <div class="history-container">
        <div class="page-header animate-in"
            style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <div>
                <h1 style="font-size: 2rem;"><i class="bi bi-clock-history"></i> Quiz History</h1>
                <p style="color: var(--text-light); font-weight: 600;">Track all your past performances and improvements.</p>
            </div>
            <div>
                <a href="{{ route('student.dashboard') }}" class="btn btn-ghost"
                    style="display: flex; align-items: center; gap: 0.5rem;">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
            </div>
        </div>

        @if ($attempts->isEmpty())
            <div class="empty-state animate-in">
                <div style="font-size: 4rem; margin-bottom: 1rem;"><i class="bi bi-journal-text"></i></div>
                <h3>No quizzes taken yet</h3>
                <p style="color: var(--text-light);">Start your learning journey by taking a quiz or daily challenge!</p>
                <a href="{{ route('student.subjects') }}" class="btn btn-primary" style="margin-top: 1.5rem;">Browse
                    Subjects</a>
            </div>
        @else
            <div class="animate-in">
                @foreach ($attempts as $attempt)
                    <a href="{{ route('student.quiz.results', $attempt->id) }}" class="history-card">
                        <div class="quiz-type-icon {{ $attempt->type === 'daily' ? 'type-daily' : 'type-chapter' }}">
                            {!! $attempt->type === 'daily' ? '<i class="bi bi-lightning-fill"></i>' : '<i class="bi bi-journal-text"></i>' !!}
                        </div>
                        <div class="quiz-info">
                            <div class="quiz-title">
                                @if ($attempt->type === 'daily')
                                    Daily Challenge
                                @else
                                    {{ $attempt->chapter->book->name ?? 'Deleted Book' }} —
                                    {{ $attempt->chapter->name ?? 'Deleted Chapter' }}
                                @endif
                            </div>
                            <div class="quiz-meta">
                                <span><i class="bi bi-calendar3"></i> {{ $attempt->created_at->format('M d, Y') }}</span>
                                <span><i class="bi bi-clock"></i> {{ $attempt->created_at->format('h:i A') }}</span>
                            </div>
                        </div>
                        <div class="score-pill">
                            <div class="score-value">{{ $attempt->score }}/{{ $attempt->total }}</div>
                            <div class="accuracy-badge">{{ round($attempt->accuracy) }}%</div>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="pagination-container">
                {{ $attempts->links() }}
            </div>
        @endif
    </div>
@endsection
