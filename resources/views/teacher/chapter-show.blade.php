@extends('layouts.teacher')

@section('title', 'Chapter: ' . $chapter->title)

@section('content')
    <div class="breadcrumb animate-in">
        <a href="{{ route('teacher.dashboard') }}" class="text-decoration-none">Dashboard</a>
        <i class="bi bi-chevron-right"></i>
        <a href="{{ route('teacher.book.show', $chapter->book) }}"
            class="text-decoration-none">{{ $chapter->book->title }}</a>
        <i class="bi bi-chevron-right"></i>
        <span>Chapter {{ $chapter->chapter_number }}: {{ $chapter->title }}</span>
    </div>

    <div class="page-header animate-in">
        <div>
            <h1>{{ $chapter->title }}</h1>
            <p>Chapter {{ $chapter->chapter_number }} • {{ $questions->count() }}
                {{ $filteredByJob ? 'New ' : '' }}Questions</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('teacher.chapter.append', $chapter) }}" class="btn btn-ghost border">
                <i class="bi bi-plus-lg"></i> Append Content
            </a>
            <button class="btn btn-primary" onclick="window.print()">
                <i class="bi bi-download"></i> Export PDF
            </button>
        </div>
    </div>

    @if ($filteredByJob)
        <div class="alert alert-info d-flex justify-content-between align-items-center animate-in mb-4">
            <span><i class="bi bi-info-circle"></i> Showing only results from this AI Job.</span>
            <a href="{{ route('teacher.chapter.show', $chapter) }}" class="btn btn-sm btn-info text-white">View All Chapter
                Content</a>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <!-- AI Generated Content -->
            <div class="section-title"><i class="bi bi-stars"></i>
                {{ $filteredByJob ? 'Generated Content' : 'Generated Material' }}</div>
            @foreach ($topics as $topic)
                <div class="book-card mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="mb-0">{{ $topic->title }}</h4>
                        <form action="{{ route('teacher.topic.delete', $topic) }}" method="POST"
                            onsubmit="return confirm('Delete this topic?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </div>
                    <div class="content-preview">
                        {!! nl2br(e($topic->content)) !!}
                    </div>
                </div>
            @endforeach

            <!-- Question List -->
            <div class="section-title mt-5 d-flex justify-content-between">
                <span><i class="bi bi-question-circle"></i> Question List</span>
                <span class="badge bg-primary">{{ $questions->count() }} total</span>
            </div>

            @foreach ($questions as $question)
                <div class="book-card mb-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="badge bg-light text-dark">{{ strtoupper($question->type) }}</span>
                        <span
                            class="badge bg-{{ $question->difficulty == 'hard' ? 'danger' : ($question->difficulty == 'medium' ? 'warning' : 'success') }}">
                            {{ ucfirst($question->difficulty) }}
                        </span>
                    </div>
                    <p class="fw-bold">{{ $loop->iteration }}. {{ $question->question }}</p>
                    @if ($question->type === 'mcq')
                        <div class="row g-2 mb-3">
                            <div class="col-6 small">A. {{ $question->option_a }}</div>
                            <div class="col-6 small">B. {{ $question->option_b }}</div>
                            <div class="col-6 small">C. {{ $question->option_c }}</div>
                            <div class="col-6 small">D. {{ $question->option_d }}</div>
                        </div>
                    @endif
                    <div class="p-2 bg-light rounded small">
                        <strong>{{ $question->type === 'qa' ? 'Answer:' : 'Correct:' }}</strong>
                        {{ $question->correct_answer }}
                    </div>
                    @if ($question->explanation)
                        <div class="mt-2 small text-muted">
                            <strong>Explanation:</strong> {{ $question->explanation }}
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="col-lg-4">
            <div class="book-card sticky-top" style="top: 2rem;">
                <h4>Edit Tools</h4>
                <hr>
                <form action="{{ route('teacher.chapter.update', $chapter) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="mb-3">
                        <label class="form-label">Chapter Title</label>
                        <input type="text" name="title" class="form-control" value="{{ $chapter->title }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Difficulty Mix</label>
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar bg-success" style="width: 40%"></div>
                            <div class="progress-bar bg-warning" style="width: 40%"></div>
                            <div class="progress-bar bg-danger" style="width: 20%"></div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Save Changes</button>
                </form>
                <hr>
                <form action="{{ route('teacher.chapter.delete', $chapter) }}" method="POST"
                    onsubmit="return confirm('Delete entire chapter?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-outline-danger w-100">Delete Chapter</button>
                </form>
            </div>
        </div>
    </div>
@endsection
