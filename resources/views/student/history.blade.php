@extends('layouts.student')

@section('title', 'Quiz History — Revizo')

@push('styles')
@endpush

@section('content')
    <div class="history-container">
        <div class="page-header animate-in mt-4"
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
