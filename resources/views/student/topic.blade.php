@extends('layouts.student')
@section('title', $topic->title . ' — Topic Detail')

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
