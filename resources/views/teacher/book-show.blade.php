@extends('layouts.teacher')
@section('title', $book->title)

@section('content')
    <div class="breadcrumb animate-in">
        <a href="{{ route('teacher.dashboard') }}">Dashboard</a>
        <i class="bi bi-chevron-right"></i>
        <a href="{{ route('teacher.dashboard', ['standard' => $book->subject->class]) }}">Class
            {{ $book->subject->class }}</a>
        <i class="bi bi-chevron-right"></i>
        <a
            href="{{ route('teacher.dashboard', ['standard' => $book->subject->class, 'subject_id' => $book->subject_id]) }}">{{ $book->subject->name }}</a>
        <i class="bi bi-chevron-right"></i>
        <span>{{ $book->title }}</span>
    </div>

    <div class="book-hero animate-in">
        <div>
            <h1>{{ $book->title }}</h1>
            <div class="meta">
                <span><i class="bi bi-book"></i> {{ $book->subject->name ?? 'N/A' }}</span>
                <span><i class="bi bi-folder"></i> {{ $book->chapters->count() }} Chapters</span>
                <span><i class="bi bi-question-circle"></i>
                    {{ $book->chapters->sum(fn($c) => $c->questions->where('type', $book->question_preference)->count()) }}
                    Questions</span>

                <form action="{{ route('teacher.book.update-preference', $book) }}" method="POST"
                    style="display:inline-block; vertical-align: middle;">
                    @csrf
                    @method('PATCH')
                    <div class="segmented-control">
                        <input type="radio" name="question_preference" id="fmt_mcq" value="mcq"
                            {{ $book->question_preference == 'mcq' ? 'checked' : '' }} onchange="this.form.submit()">
                        <label for="fmt_mcq"><i class="bi bi-journal-text"></i> MCQ</label>

                        <input type="radio" name="question_preference" id="fmt_qa" value="qa"
                            {{ $book->question_preference == 'qa' ? 'checked' : '' }} onchange="this.form.submit()">
                        <label for="fmt_qa"><i class="bi bi-pencil-square"></i> Q&A</label>

                        <input type="radio" name="question_preference" id="fmt_topic" value="topic"
                            {{ $book->question_preference == 'topic' ? 'checked' : '' }} onchange="this.form.submit()">
                        <label for="fmt_topic"><i class="bi bi-book"></i> Topic</label>
                    </div>
                </form>
            </div>
        </div>
        <div style="display:flex; gap:.75rem;">
            <a href="{{ route('teacher.book.chapter.add', $book) }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i>
                Add More Content</a>
        </div>
    </div>



    @if ($book->chapters->isEmpty())
        <div style="text-align:center;padding:3rem;color:var(--muted);">
            <div style="font-size:2.5rem;margin-bottom:1rem;"><i class="bi bi-search"></i></div>
            <p>No chapters detected in this book.</p>
        </div>
    @else
        <div class="chapter-list">
            @foreach ($book->chapters as $chapter)
                <div class="chapter-card animate-in">
                    <div class="chapter-header" onclick="toggleChapter(this)">
                        <div style="display:flex;align-items:center;gap:.75rem;">
                            <span class="chapter-num">Ch {{ $chapter->chapter_number }}</span>
                            <h3>{{ $chapter->title }}</h3>
                        </div>
                        <div class="ch-meta" style="display:flex;align-items:center;gap:1.5rem;">
                            @if ($book->question_preference === 'topic')
                                <span><i class="bi bi-book"></i>
                                    {{ $chapter->topics->count() > 0 ? 'Explained' : 'No explanation' }}</span>
                            @else
                                <span><i class="bi bi-question-circle"></i>
                                    {{ $chapter->questions->where('type', $book->question_preference)->count() }}
                                    questions</span>
                            @endif

                            <!-- Action Buttons -->
                            <div style="display:flex; gap:.5rem; align-items:center;" onclick="event.stopPropagation()">
                                <a href="{{ route('teacher.chapter.append', $chapter) }}" class="btn btn-primary"
                                    style="padding: 0.2rem 0.6rem; font-size: 0.75rem; border-radius: 6px;">
                                    <i class="bi bi-plus-lg"></i> Add More
                                </a>
                                @if ($book->question_preference !== 'topic')
                                    <form
                                        action="{{ route('teacher.chapter.questions.delete', [$chapter, $book->question_preference]) }}"
                                        method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete all {{ strtoupper($book->question_preference) }} questions in this chapter?');"
                                        style="margin:0;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn"
                                            style="padding: 0.2rem 0.6rem; font-size: 0.75rem; border-radius: 6px; background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.3);">
                                            <i class="bi bi-trash"></i> Delete {{ strtoupper($book->question_preference) }}
                                        </button>
                                    </form>
                                @endif
                            </div>

                            <span class="toggle-icon"><i class="bi bi-chevron-right"></i></span>
                        </div>
                    </div>
                    <div class="chapter-body">
                        @if ($book->question_preference === 'topic')
                            @if ($chapter->topics->isEmpty())
                                <p style="color:var(--muted);font-size:.85rem;padding:.5rem 0;"><i
                                        class="bi bi-hourglass-split"></i> Topic explanations are
                                    being generated...</p>
                            @else
                                <div class="topics-container" style="display: flex; flex-direction: column; gap: 1.5rem;">
                                    @foreach ($chapter->topics as $topic)
                                        <div class="topic-item"
                                            style="background: #fff; border-radius: 12px; border: 1px solid var(--border); border-left: 4px solid var(--primary); overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.04); margin-bottom: 0.75rem; transition: all 0.3s ease;">
                                            <div class="topic-header" onclick="toggleTopic(this)"
                                                style="padding: 1.1rem 1.5rem; background: #fff; display: flex; justify-content: space-between; align-items: center; cursor: pointer;">
                                                <div style="display: flex; align-items: center; gap: 1.25rem;">
                                                    <span class="toggle-indicator"
                                                        style="color: var(--primary); font-size: 0.7rem; transition: transform 0.3s ease;">▶</span>
                                                    <h4
                                                        style="margin: 0; font-size: 1.05rem; color: var(--text); font-weight: 700; letter-spacing: -0.01em;">
                                                        <i class="bi bi-book"></i> {{ $topic->title }}
                                                    </h4>
                                                </div>
                                                <form action="{{ route('teacher.topic.delete', $topic) }}" method="POST"
                                                    onsubmit="return confirm('Are you sure you want to delete this topic explanation?');"
                                                    style="margin: 0;" onclick="event.stopPropagation()">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn"
                                                        style="padding: 0.3rem 0.75rem; font-size: 0.75rem; border-radius: 8px; background: rgba(239, 68, 68, 0.08); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.2); font-weight: 600; transition: all 0.2s ease;">
                                                        <i class="bi bi-trash"></i> Delete
                                                    </button>
                                                </form>
                                            </div>
                                            <div class="topic-content"
                                                style="padding: 0 1.5rem 1.5rem 3.75rem; border-top: none;">
                                                <div
                                                    style="font-size: 0.98rem; line-height: 1.8; color: var(--text); opacity: 0.9;">
                                                    {!! Str::markdown($topic->content) !!}
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        @else
                            @php
                                $filteredQuestions = $chapter->questions->where('type', $book->question_preference);
                            @endphp

                            @if ($chapter->questions->isEmpty())
                                <p style="color:var(--muted);font-size:.85rem;padding:.5rem 0;"><i
                                        class="bi bi-hourglass-split"></i> Questions are being
                                    generated...</p>
                            @elseif($filteredQuestions->isEmpty())
                                <div class="empty-format-state">
                                    <i class="bi bi-search"></i> No {{ strtoupper($book->question_preference) }} questions
                                    found in this chapter.
                                    <br><small>Try generating content with this format selected.</small>
                                </div>
                            @else
                                <ul class="q-list">
                                    @foreach ($filteredQuestions as $q)
                                        <li class="q-item">
                                            <div class="question">{{ $loop->iteration }}. {{ $q->question }}</div>
                                            @if ($q->type === 'qa')
                                                <div class="qa-answer">
                                                    <strong>Answer:</strong> {{ $q->correct_answer }}
                                                </div>
                                            @else
                                                <div class="options">
                                                    <span
                                                        class="{{ strtoupper($q->correct_answer) === 'A' ? 'correct' : '' }}">A.
                                                        {{ $q->option_a }}</span>
                                                    <span
                                                        class="{{ strtoupper($q->correct_answer) === 'B' ? 'correct' : '' }}">B.
                                                        {{ $q->option_b }}</span>
                                                    <span
                                                        class="{{ strtoupper($q->correct_answer) === 'C' ? 'correct' : '' }}">C.
                                                        {{ $q->option_c }}</span>
                                                    <span
                                                        class="{{ strtoupper($q->correct_answer) === 'D' ? 'correct' : '' }}">D.
                                                        {{ $q->option_d }}</span>
                                                </div>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection

@push('scripts')
    <script>
        function toggleChapter(header) {
            header.classList.toggle('open');
            const body = header.nextElementSibling;
            body.classList.toggle('open');
        }

        function toggleTopic(header) {
            header.classList.toggle('open');
            const content = header.nextElementSibling;
            content.classList.toggle('open');
        }
    </script>
@endpush
