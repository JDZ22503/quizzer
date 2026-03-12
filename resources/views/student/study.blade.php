@extends('layouts.student')
@section('title', 'Study Mode — Revizo')

@section('content')
    <div class="study-container">
        <div class="study-header animate-in"
            style="display: flex; align-items: flex-start; justify-content: space-between; flex-wrap: wrap; gap: 1.5rem;">
            <div style="position: relative; z-index: 2;">
                <div class="header-meta">Study Session</div>
                <h1>{{ $chapter->title }}</h1>
                <p style="margin-top: 0.5rem; opacity: 0.9;">Take your time to read and understand these core concepts.</p>
            </div>
            <a href="{{ route('student.subject.show', $chapter->book->subject_id) }}" class="btn btn-ghost"
                style="padding: 0.5rem 1rem; position: relative; z-index: 2; border-color: rgba(255,255,255,0.3); color: white;">
                <i class="bi bi-arrow-left"></i> Back to Chapters
            </a>
        </div>

        <div class="qa-list">
            @forelse($questions as $i => $q)
                <div class="qa-card animate-in" style="--delay: {{ ($i + 1) * 0.1 }}s">
                    <div class="q-text">
                        <span class="q-badge">{{ $i + 1 }}</span>
                        {{ $q->question }}
                    </div>
                    <div class="a-box">
                        <span class="a-label">The Answer</span>
                        <div class="a-text">{{ $q->correct_answer }}</div>
                    </div>
                    @if ($q->explanation)
                        <div class="info-box">
                            <span style="font-size: 1.25rem;"><i class="bi bi-lightbulb"></i></span>
                            <div>{{ $q->explanation }}</div>
                        </div>
                    @endif
                </div>
            @empty
                <div class="empty-study animate-in">
                    <div style="font-size: 4rem; margin-bottom: 1.5rem; color: var(--text-light);"><i
                            class="bi bi-journal-x"></i></div>
                    <h3>No study material available</h3>
                    <p style="color: var(--text-light);">The teacher is still finalizing the professional content for this
                        chapter.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
