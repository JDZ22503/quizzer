@extends('layouts.student')
@section('title', 'Chapters — Revizo')

@section('content')
    <div class="chapters-container">
        <div class="page-header animate-in"
            style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1.5rem;">
            <div>
                <h1><i class="bi bi-folder2-open"></i> Choose a Chapter</h1>
                <p>Select your learning path: Study the concepts or test yourself.</p>
            </div>
            <a href="{{ route('student.subjects') }}" class="btn btn-ghost" style="padding: 0.5rem 1rem;">
                ← Back to Subjects
            </a>
        </div>

        <div class="chapters-grid">
            @forelse($chapters as $chapter)
                <div class="ch-card animate-in" style="--delay: {{ $loop->index * 0.1 }}s">
                    <div class="ch-main">
                        <div class="ch-num">{{ $chapter->chapter_number }}</div>
                        <div class="ch-info">
                            <h3>{{ $chapter->title }}</h3>
                            <div class="ch-stats" style="margin-bottom: 1rem;">
                                <span class="stat-tag {{ $chapter->mcq_count > 0 ? 'active' : '' }}">
                                    <i class="bi bi-rocket-takeoff"></i> {{ $chapter->mcq_count }} MCQs
                                </span>
                                <span class="stat-tag {{ $chapter->qa_count > 0 ? 'active' : '' }}">
                                    <i class="bi bi-journal-text"></i> {{ $chapter->qa_count }} Q&A
                                </span>
                            </div>
                            <div class="ch-actions">
                                <a href="{{ route('student.read-mcqs', $chapter->id) }}"
                                    class="btn-action btn-read {{ $chapter->mcq_count == 0 ? 'disabled' : '' }}"
                                    title="Read all MCQs">
                                    <i class="bi bi-book"></i> Read MCQs
                                </a>
                                <a href="{{ route('student.study', $chapter->id) }}"
                                    class="btn-action btn-study {{ $chapter->qa_count == 0 ? 'disabled' : '' }}"
                                    title="Study Q&A">
                                    <i class="bi bi-pencil-square"></i> Study
                                </a>
                                <a href="{{ route('student.quiz', $chapter->id) }}"
                                    class="btn-action btn-quiz {{ $chapter->mcq_count == 0 ? 'disabled' : '' }}"
                                    title="Start MCQ Quiz">
                                    <i class="bi bi-rocket-takeoff"></i> Start Quiz
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="card animate-in" style="text-align:center; padding:5rem;">
                    <div style="font-size:3rem; margin-bottom:1rem;"><i class="bi bi-search"></i></div>
                    <h3>No chapters found</h3>
                    <p>Check back later for new content.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
