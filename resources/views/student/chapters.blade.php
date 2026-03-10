@extends('layouts.student')
@section('title', 'Chapters — Revizo')

@push('styles')
    <style>
        .chapters-container {
            max-width: 900px;
            margin: 0 auto;
        }

        .ch-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 1.5rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 2rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            margin-bottom: 1.25rem;
            box-shadow: var(--shadow-sm);
            position: relative;
            overflow: hidden;
        }

        .ch-card:hover {
            border-color: var(--primary);
            box-shadow: var(--shadow-md);
            transform: translateX(6px);
        }

        .ch-main {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            flex: 1;
        }

        .ch-num {
            width: 52px;
            height: 52px;
            background: var(--primary-light);
            color: var(--primary);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            font-weight: 800;
            flex-shrink: 0;
            font-family: 'Poppins', sans-serif;
        }

        .ch-info h3 {
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 0.4rem;
        }

        .ch-stats {
            display: flex;
            gap: 1rem;
        }

        .stat-tag {
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--text-light);
            background: var(--bg);
            padding: 0.25rem 0.65rem;
            border-radius: 6px;
            display: flex;
            align-items: center;
            gap: 0.35rem;
            border: 1px solid var(--border);
        }

        .stat-tag.active {
            color: var(--primary);
            background: var(--primary-light);
            border-color: rgba(59, 91, 255, 0.2);
        }

        .ch-actions {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .btn-action {
            padding: 0.6rem 1rem;
            font-size: 0.85rem;
            font-weight: 700;
            border-radius: 10px;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s;
            font-family: 'Poppins', sans-serif;
        }

        .btn-read {
            background: #F1F5F9;
            color: #475569;
            border: 1px solid var(--border);
        }

        .btn-read:hover:not(.disabled) {
            background: #E2E8F0;
            color: var(--text);
        }

        .btn-study {
            background: var(--primary-light);
            color: var(--primary);
            border: 1px solid rgba(59, 91, 255, 0.2);
        }

        .btn-study:hover:not(.disabled) {
            background: var(--primary);
            color: #fff;
        }

        .btn-quiz {
            background: var(--primary);
            color: #fff;
            box-shadow: 0 4px 12px rgba(59, 91, 255, 0.2);
        }

        .btn-quiz:hover:not(.disabled) {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }

        .btn-action.disabled {
            opacity: 0.5;
            cursor: not-allowed;
            pointer-events: none;
            filter: grayscale(1);
        }

        @media (max-width: 768px) {
            .ch-card {
                flex-direction: column;
                align-items: stretch;
            }

            .ch-actions {
                display: grid;
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

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
