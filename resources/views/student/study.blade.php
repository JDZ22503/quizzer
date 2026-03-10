@extends('layouts.student')
@section('title', 'Study Mode — Revizo')

@push('styles')
    <style>
        .study-container {
            max-width: 800px;
            margin: 0 auto;
        }

        .study-header {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: var(--radius-lg);
            padding: 2.5rem;
            margin-bottom: 2.5rem;
            color: #fff;
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow-lg);
        }

        .study-header::after {
            content: "\F192";
            /* bi-book */
            font-family: "bootstrap-icons";
            position: absolute;
            right: -10px;
            bottom: -20px;
            font-size: 8rem;
            opacity: 0.1;
            transform: rotate(-15deg);
        }

        .study-header h1 {
            font-size: 2rem;
            font-weight: 800;
            margin-top: 0.5rem;
        }

        .header-meta {
            font-size: 0.85rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            opacity: 0.9;
        }

        .qa-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 2.25rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow-sm);
            transition: all 0.3s ease;
        }

        .qa-card:hover {
            border-color: var(--primary);
            box-shadow: var(--shadow-md);
        }

        .q-text {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text);
            line-height: 1.5;
            margin-bottom: 1.75rem;
            display: flex;
            gap: 1rem;
        }

        .q-badge {
            background: var(--primary-light);
            color: var(--primary);
            width: 38px;
            height: 38px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            font-weight: 800;
            flex-shrink: 0;
            font-family: 'Poppins', sans-serif;
        }

        .a-box {
            background: #F0FDF4;
            /* Very light success green */
            border-left: 5px solid var(--success);
            padding: 1.5rem 1.75rem;
            border-radius: 0 var(--radius-md) var(--radius-md) 0;
        }

        .a-label {
            font-size: 0.8rem;
            font-weight: 800;
            color: var(--success);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.5rem;
            display: block;
        }

        .a-text {
            font-size: 1.1rem;
            color: var(--text);
            line-height: 1.7;
            font-weight: 500;
        }

        .info-box {
            margin-top: 1.5rem;
            padding: 1.25rem;
            background: var(--bg);
            border-radius: var(--radius-md);
            font-size: 0.95rem;
            color: var(--text-light);
            display: flex;
            gap: 1rem;
            align-items: flex-start;
            border: 1px solid var(--border);
        }

        .empty-study {
            text-align: center;
            padding: 6rem 2rem;
            background: var(--surface);
            border-radius: var(--radius-lg);
            border: 1px dashed var(--border);
        }

        .back-nav {
            margin-bottom: 1.5rem;
        }
    </style>
@endpush

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
