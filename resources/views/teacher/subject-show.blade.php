@extends('layouts.teacher')

@section('title', 'Subject: ' . $subject->name)

@section('content')
    <div class="breadcrumb animate-in">
        <a href="{{ route('teacher.dashboard') }}" class="text-decoration-none">Dashboard</a>
        <i class="bi bi-chevron-right"></i>
        <span>Class {{ $subject->class }}</span>
        <i class="bi bi-chevron-right"></i>
        <span>{{ $subject->name }}</span>
    </div>

    <div class="page-header animate-in">
        <div>
            <h1>{{ $subject->name }}</h1>
            <p>Class {{ $subject->class }} • {{ $subject->medium }} Medium</p>
        </div>
        <a href="{{ route('teacher.manual-mcq.create', ['standard' => $subject->class, 'subject_id' => $subject->id]) }}"
            class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Add New Book
        </a>
    </div>

    <div class="section-title animate-in"><i class="bi bi-journal-text"></i> Books in this Subject</div>

    @if ($books->isEmpty())
        <div class="empty animate-in">
            <div class="icon"><i class="bi bi-journal-x"></i></div>
            <h2>No Books Found</h2>
            <p>You haven't added any books for this subject yet.</p>
        </div>
    @else
        <div class="grid-3">
            @foreach ($books as $book)
                <a href="{{ route('teacher.book.show', $book) }}" class="text-decoration-none">
                    <div class="book-card animate-in">
                        <div class="meta">
                            <span class="badge badge-primary">{{ $book->chapters_count }} Chapters</span>
                        </div>
                        <h3>{{ $book->title }}</h3>
                        <p class="text-muted small">Last updated {{ $book->updated_at->diffForHumans() }}</p>
                        <div class="footer mt-3">
                            <div style="color: var(--primary); font-weight: 600;">View Book <i
                                    class="bi bi-arrow-right"></i></div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
@endsection
