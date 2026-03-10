@extends('layouts.student')
@section('title', $subject->name . ' — Chapters')

@push('styles')
    <style>
        .page-header {
            margin-bottom: 2rem;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-light);
            text-decoration: none;
            font-weight: 600;
            margin-bottom: 1rem;
            transition: color 0.2s;
        }

        .back-link:hover {
            color: var(--primary);
        }

        .book-section {
            margin-bottom: 2.5rem;
        }

        .book-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1.25rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid var(--border);
        }

        .book-header h3 {
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--text);
        }

        .chapters-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .chapter-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .chapter-card:hover {
            border-color: var(--primary);
            box-shadow: var(--shadow-sm);
        }

        .chapter-header {
            padding: 1.25rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            cursor: pointer;
            user-select: none;
        }

        .chapter-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .chapter-number {
            width: 32px;
            height: 32px;
            background: var(--primary-light);
            color: var(--primary);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 0.85rem;
        }

        .chapter-title {
            font-size: 1rem;
            font-weight: 700;
            color: var(--text);
        }

        .chapter-arrow {
            color: var(--text-light);
            transition: transform 0.3s ease;
        }

        .chapter-card.active .chapter-arrow {
            transform: rotate(180deg);
            color: var(--primary);
        }

        .chapter-body {
            max-height: 0;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: #f8fafc;
        }

        .chapter-card.active .chapter-body {
            max-height: 800px;
            /* Allow more space for topics */
            border-top: 1px solid var(--border);
        }

        .chapter-content {
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        .action-row {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1.5rem;
            flex-wrap: wrap;
        }

        .topics-section {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .topics-title {
            font-size: 0.9rem;
            font-weight: 800;
            color: var(--text-light);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .topics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 0.75rem;
        }

        .topic-item {
            background: #fff;
            border: 1px solid var(--border);
            padding: 0.75rem 1rem;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: var(--text);
            transition: all 0.2s;
        }

        .topic-item:hover {
            border-color: var(--primary);
            background: var(--primary-light);
            transform: translateY(-2px);
        }

        .topic-bullet {
            width: 8px;
            height: 8px;
            background: var(--primary);
            border-radius: 50%;
            flex-shrink: 0;
        }

        .topic-name {
            font-size: 0.85rem;
            font-weight: 700;
        }

        .action-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            color: var(--text);
            padding: 1rem;
            border-radius: var(--radius-sm);
            transition: all 0.2s;
            min-width: 100px;
        }

        .action-btn .icon {
            font-size: 1.5rem;
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            transition: all 0.2s;
        }

        .action-btn .label {
            font-size: 0.85rem;
            font-weight: 700;
        }

        /* Read MCQs Button */
        .btn-read .icon {
            background: #fff;
            border: 1px solid var(--border);
            color: #64748b;
        }

        .btn-read:hover .icon {
            border-color: var(--primary);
            color: var(--primary);
            transform: translateY(-3px);
        }

        /* Study Button */
        .btn-study .icon {
            background: rgba(79, 70, 229, 0.08);
            border: 1px solid rgba(79, 70, 229, 0.15);
            color: var(--primary);
        }

        .btn-study:hover .icon {
            background: var(--primary);
            color: #fff;
            transform: translateY(-3px);
        }

        /* Quiz Button */
        .btn-quiz .icon {
            background: #fff;
            border: 1px solid #c7d2fe;
            color: #4338ca;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .btn-quiz:hover .icon {
            background: #4338ca;
            color: #fff;
            border-color: #4338ca;
            transform: translateY(-3px);
            box-shadow: 0 10px 15px -3px rgba(67, 56, 202, 0.2);
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: var(--surface);
            border-radius: var(--radius-lg);
            border: 2px dashed var(--border);
        }
    </style>
@endpush

@section('content')
    <div class="animate-in">
        <a href="{{ route('student.subjects') }}" class="back-link">
            <i class="bi bi-arrow-left"></i> Back to Dashboard
        </a>

        <div class="page-header">
            <h1>{{ $subject->name }}</h1>
            <p>Class {{ $subject->class }} • Select a chapter to begin your study session.</p>
        </div>

        @php $hasContent = false; @endphp

        @foreach ($subject->books as $book)
            @if ($book->chapters->isNotEmpty())
                @php $hasContent = true; @endphp
                <div class="book-section animate-in" style="--delay: {{ 0.1 + $loop->index * 0.1 }}s">
                    <div class="book-header">
                        <span style="font-size: 1.5rem; color: var(--primary);"><i
                                class="bi bi-journal-bookmark-fill"></i></span>
                        <h3>{{ $book->title }}</h3>
                    </div>

                    <div class="chapters-list">
                        @foreach ($book->chapters as $chapter)
                            <div class="chapter-card" id="chapter-{{ $chapter->id }}">
                                <div class="chapter-header" onclick="toggleChapter({{ $chapter->id }})">
                                    <div class="chapter-info">
                                        <div class="chapter-number">{{ $chapter->chapter_number }}</div>
                                        <div class="chapter-title">{{ $chapter->title }}</div>
                                    </div>
                                    <div class="chapter-arrow">
                                        <i class="bi bi-chevron-down"></i>
                                    </div>
                                </div>
                                <div class="chapter-body">
                                    <div class="chapter-content">
                                        <div class="action-row">
                                            @if ($chapter->mcq_count > 0)
                                                <a href="{{ route('student.read-mcqs', $chapter->id) }}"
                                                    class="action-btn btn-read">
                                                    <div class="icon"><i class="bi bi-book"></i></div>
                                                    <div class="label">Read MCQs</div>
                                                </a>
                                            @endif

                                            @if ($chapter->qa_count > 0)
                                                <a href="{{ route('student.study', $chapter->id) }}"
                                                    class="action-btn btn-study">
                                                    <div class="icon"><i class="bi bi-pencil-square"></i></div>
                                                    <div class="label">Study</div>
                                                </a>
                                            @endif

                                            @if ($chapter->mcq_count > 0)
                                                <a href="{{ route('student.quiz', $chapter->id) }}"
                                                    class="action-btn btn-quiz">
                                                    <div class="icon"><i class="bi bi-rocket-takeoff"></i></div>
                                                    <div class="label">Start Quiz</div>
                                                </a>
                                            @endif
                                        </div>

                                        @if ($chapter->topics->isNotEmpty())
                                            <div class="topics-section">
                                                <div class="topics-title">
                                                    <span><i class="bi bi-search"></i></span> Topics in this Chapter
                                                </div>
                                                <div class="topics-grid">
                                                    @foreach ($chapter->topics as $topic)
                                                        <a href="{{ route('student.topic', $topic->id) }}"
                                                            class="topic-item">
                                                            <div class="topic-bullet"></div>
                                                            <div class="topic-name">{{ $topic->title }}</div>
                                                        </a>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach

        @if (!$hasContent)
            <div class="empty-state animate-in">
                <div style="font-size: 3rem; margin-bottom: 1rem; color: var(--text-light);"><i class="bi bi-journal-x"></i>
                </div>
                <h3>No materials found for this subject</h3>
                <p>Wait for your teacher to add books and chapters.</p>
                <a href="{{ route('student.subjects') }}" class="btn btn-primary" style="margin-top: 1.5rem;">
                    Back to Dashboard
                </a>
            </div>
        @endif
    </div>

    <script>
        function toggleChapter(id) {
            const card = document.getElementById(`chapter-${id}`);

            // Optional: Close other chapters in the same section
            // const allCards = card.parentElement.querySelectorAll('.chapter-card');
            // allCards.forEach(c => {
            //     if (c !== card) c.classList.remove('active');
            // });

            card.classList.toggle('active');
        }
    </script>
@endsection
