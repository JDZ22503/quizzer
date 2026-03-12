@extends('layouts.public')

@section('title', 'Search Results')

@section('content')
    <div class="container py-5">
        <h2 class="mb-4">Search Results for "{{ $query }}"</h2>

        @if ($books->isNotEmpty())
            <h4>Books</h4>
            <div class="list-group mb-4">
                @foreach ($books as $book)
                    <a href="#" class="list-group-item list-group-item-action">{{ $book->title }}</a>
                @endforeach
            </div>
        @endif

        @if ($chapters->isNotEmpty())
            <h4>Chapters</h4>
            <div class="list-group mb-4">
                @foreach ($chapters as $chapter)
                    <a href="#" class="list-group-item list-group-item-action">{{ $chapter->title }}</a>
                @endforeach
            </div>
        @endif

        @if ($questions->isNotEmpty())
            <h4>Questions</h4>
            <div class="list-group">
                @foreach ($questions as $question)
                    <div class="list-group-item">
                        <p class="mb-1">{{ $question->question }}</p>
                        <small class="text-muted">Chapter: {{ $question->chapter->title ?? 'N/A' }}</small>
                    </div>
                @endforeach
            </div>
        @endif

        @if ($books->isEmpty() && $chapters->isEmpty() && $questions->isEmpty())
            <div class="alert alert-warning">No results found for your search.</div>
        @endif
    </div>
@endsection
