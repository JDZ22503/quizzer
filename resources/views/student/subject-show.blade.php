@extends('layouts.student')
@section('title', $subject->name . ' — Chapters')

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
