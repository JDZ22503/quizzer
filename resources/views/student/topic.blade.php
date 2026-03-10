@extends('layouts.student')
@section('title', $topic->title . ' — Topic Detail')

@push('styles')
    <style>
        .topic-container {
            max-width: 900px;
            margin: 0 auto;
            padding-bottom: 4rem;
        }

        .topic-header {
            background: linear-gradient(135deg, #6366f1, #a855f7);
            border-radius: 24px;
            padding: 3rem;
            margin-bottom: 2.5rem;
            color: #fff;
            position: relative;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(99, 102, 241, 0.2);
        }

        .topic-header::after {
            content: "\F52A";
            /* bi-search */
            font-family: "bootstrap-icons";
            position: absolute;
            right: -20px;
            bottom: -30px;
            font-size: 10rem;
            opacity: 0.15;
            transform: rotate(-15deg);
        }

        .chapter-label {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(255, 255, 255, 0.15);
            padding: 0.5rem 1rem;
            border-radius: 100px;
            font-size: 0.85rem;
            font-weight: 700;
            backdrop-filter: blur(4px);
            margin-bottom: 1.5rem;
        }

        .topic-title {
            font-size: 2.5rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 1rem;
        }

        .content-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 24px;
            padding: 3rem;
            box-shadow: var(--shadow-sm);
        }

        /* Markdown Styling */
        .markdown-content {
            font-size: 1.15rem;
            line-height: 1.8;
            color: var(--text);
        }

        .markdown-content h1,
        .markdown-content h2,
        .markdown-content h3 {
            color: var(--text);
            font-weight: 800;
            margin-top: 2rem;
            margin-bottom: 1rem;
        }

        .markdown-content h3 {
            font-size: 1.5rem;
        }

        .markdown-content h4 {
            font-size: 1.25rem;
            font-weight: 700;
        }

        .markdown-content p {
            margin-bottom: 1.5rem;
        }

        .markdown-content ul,
        .markdown-content ol {
            margin-bottom: 1.5rem;
            padding-left: 1.5rem;
        }

        .markdown-content li {
            margin-bottom: 0.5rem;
        }

        .markdown-content strong {
            color: var(--primary);
        }

        .markdown-content code {
            background: var(--bg);
            padding: 0.2rem 0.4rem;
            border-radius: 6px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.9em;
            color: #e83e8c;
        }

        .action-bar {
            margin-top: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: var(--text-light);
            font-weight: 700;
            transition: all 0.2s;
        }

        .back-btn:hover {
            color: var(--primary);
            transform: translateX(-5px);
        }
    </style>
@endpush

@section('content')
    <div class="topic-container">
        <div class="topic-header animate-in">
            <div class="chapter-label">
                <i class="bi bi-book"></i> {{ $topic->chapter->title }}
            </div>
            <h1 class="topic-title">{{ $topic->title }}</h1>
        </div>

        <div class="content-card animate-in" style="--delay: 0.1s">
            <div class="markdown-content">
                {!! Str::markdown($topic->content) !!}
            </div>

            <div class="action-bar">
                <a href="{{ route('student.subject.show', $topic->chapter->book->subject_id) }}" class="back-btn">
                    <i class="bi bi-arrow-left"></i> Back to Chapter List
                </a>
            </div>
        </div>
    </div>
@endsection
