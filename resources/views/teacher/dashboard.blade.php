@extends('layouts.teacher')
@section('title', 'Dashboard')


@section('content')
    <div class="breadcrumb animate-in">
        <a href="{{ route('teacher.dashboard') }}" class="text-decoration-none">Dashboard</a>
        @if ($selectedStandard)
            <i class="bi bi-chevron-right"></i>
            <a href="{{ route('teacher.dashboard', ['standard' => $selectedStandard]) }}" class="text-decoration-none">Class
                {{ $selectedStandard }}</a>
        @endif
        @if ($selectedSubjectId)
            <i class="bi bi-chevron-right"></i>
            @php $currentSubject = $subjects->firstWhere('id', $selectedSubjectId); @endphp
            <a href="{{ route('teacher.dashboard', ['standard' => $selectedStandard, 'subject_id' => $selectedSubjectId]) }}"
                class="text-decoration-none">{{ $currentSubject->name ?? 'Subject' }}</a>
        @endif
    </div>

    <div class="page-header animate-in" style="--delay: 0.05s">
        <div>
            <h1><i class="bi bi-emoji-smile"></i> Welcome, {{ $teacher->name }}</h1>
            <p>Manage your books and AI-generated quizzes.</p>
        </div>
        @if ($selectedStandard && $subjects->isNotEmpty())
            <a href="{{ route('teacher.dashboard', ['standard' => $selectedStandard]) }}" class="btn btn-ghost">
                ← Back to Subjects
            </a>
        @endif
    </div>

    <!-- START NEW: Standards Selection Section -->
    <div class="standards-section animate-in">
        <div class="section-title"><i class="bi bi-stars"></i> Select Standard</div>
        <div class="standards-grid">
            @foreach (['9', '10', '11', '12'] as $std)
                @php
                    $isActive = $selectedStandard == $std;
                    $url = route('teacher.dashboard', ['standard' => $isActive ? null : $std]);
                @endphp
                <a href="{{ $url }}"
                    class="standard-card text-decoration-none card-{{ $std }} {{ $isActive ? 'active' : '' }}">
                    <div class="standard-icon">
                        @if ($std == 9)
                            <i class="bi bi-mortarboard-fill"></i>
                        @elseif($std == 10)
                            <i class="bi bi-trophy-fill"></i>
                        @elseif($std == 11)
                            <i class="bi bi-flag-fill"></i>
                        @else
                            <i class="bi bi-rocket-takeoff-fill"></i>
                        @endif
                    </div>
                    <div>
                        <div class="standard-number">Class {{ $std }}</div>
                        <div class="standard-label">{{ $isActive ? 'Selected' : 'Select Standard' }}</div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
    <!-- END NEW: Standards Selection Section -->

    @if ($selectedStandard)
        @if (!$selectedSubjectId)
            <!-- State 1: Standard Selected, Subject NOT Selected -->
            <div class="animate-in" style="--delay: 0.2s">
                <div class="section-title"><i class="bi bi-book"></i> Select Subject (Class {{ $selectedStandard }})</div>

                @if ($subjects->isEmpty())
                    <div class="empty">
                        <div class="icon"><i class="bi bi-inbox"></i></div>
                        <h2>No Subjects Found</h2>
                        <p>You haven't added any books or subjects for Class {{ $selectedStandard }} yet.</p>
                        <a href="{{ route('teacher.manual-mcq.create', ['standard' => $selectedStandard]) }}"
                            class="btn btn-primary">Create Your First MCQ</a>
                    </div>
                @else
                    <div class="subject-grid">
                        @foreach ($subjects as $subject)
                            <a href="{{ route('teacher.dashboard', ['standard' => $selectedStandard, 'subject_id' => $subject->id]) }}"
                                class="subject-card text-decoration-none">
                                <div class="subject-color-bar"
                                    style="background: hsl({{ ($loop->index * 45) % 360 }}, 70%, 60%)"></div>
                                <div class="subject-icon-circle">
                                    @php
                                        $name = strtolower($subject->name);
                                        $icon = 'bi-book';
                                        if (str_contains($name, 'math')) {
                                            $icon = 'bi-calculator-fill';
                                        } elseif (str_contains($name, 'science')) {
                                            $icon = 'bi-flask-fill';
                                        } elseif (str_contains($name, 'hindi')) {
                                            $icon = 'bi-journal-text';
                                        } elseif (str_contains($name, 'gujarati')) {
                                            $icon = 'bi-journal-richtext';
                                        } elseif (
                                            str_contains($name, 'social') ||
                                            str_contains($name, 'ss') ||
                                            str_contains($name, 'પરિચય')
                                        ) {
                                            $icon = 'bi-globe-asia-australia';
                                        } elseif (str_contains($name, 'english')) {
                                            $icon = 'bi-alphabet-uppercase';
                                        }
                                    @endphp
                                    <i class="bi {{ $icon }}"></i>
                                </div>
                                <div class="subject-info">
                                    <h3>{{ $subject->name }}</h3>
                                    <div>
                                        <p>{{ $subject->books_count ?? $subject->books()->where('teacher_id', $teacher->id)->count() }}
                                            Books available</p>
                                    </div>
                                </div>
                            </a>
                        @endforeach

                        <!-- "Add New" placeholder card -->
                        <a href="{{ route('teacher.manual-mcq.create', ['standard' => $selectedStandard]) }}"
                            class="subject-card text-decoration-none"
                            style="border-style: dashed; background: transparent; opacity: 0.7;">
                            <i class="bi bi-plus-circle"></i>
                            <div class="subject-info">
                                <h3 style="color: var(--text);">Add New Subject</h3>
                                <p style="color: var(--text-light);">Create a new quiz/book</p>
                            </div>
                        </a>
                    </div>
                @endif
            </div>
        @else
            <!-- State 2: Standard AND Subject Selected -->
            @php
                $currentSubject = $subjects->firstWhere('id', $selectedSubjectId);
            @endphp

            <div class="header-with-action animate-in" style="--delay: 0.1s">
                <div>
                    <h2>
                        {{ $currentSubject->name ?? 'Subject' }} <span style="font-weight: 400; opacity: 0.6;">(Class
                            {{ $selectedStandard }})</span>
                    </h2>
                </div>
                <a href="{{ route('teacher.manual-mcq.create', ['standard' => $selectedStandard, 'subject_id' => $selectedSubjectId]) }}"
                    class="btn btn-primary btn-xl card-{{ $selectedStandard }}">
                    <i class="bi bi-plus-lg"></i> Add New {{ $currentSubject->name ?? 'Chapter' }}
                </a>
            </div>

            <!-- STATS -->
            <div class="stats animate-in" style="--delay: 0.15s">
                <div class="stat-card purple">
                    <div class="label">Total Books</div>
                    <div class="value">{{ $books->count() }}</div>
                </div>
                <div class="stat-card teal">
                    <div class="label">Total Chapters</div>
                    <div class="value">{{ $books->sum(fn($b) => $b->chapters->count()) }}</div>
                </div>
                <div class="stat-card warn">
                    <div class="label">Questions Generated</div>
                    <div class="value">{{ $books->sum(fn($b) => $b->chapters->sum(fn($c) => $c->questions->count())) }}
                    </div>
                </div>
            </div>


            <!-- BOOKS LIST -->
            <div class="section-title animate-in"><i class="bi bi-journal-check"></i> My Quizzes</div>
            @if ($books->isEmpty())
                <div class="empty">
                    <div class="icon"><i class="bi bi-search"></i></div>
                    <h2>No Books Found</h2>
                    <p>You haven't created any content for this subject yet.</p>
                </div>
            @else
                <div class="grid-3">
                    @foreach ($books as $book)
                        <a href="{{ route('teacher.book.show', $book) }}" class="text-decoration-none">
                            <div class="book-card animate-in">
                                <div class="meta">
                                    <span
                                        class="badge badge-{{ $book->status }}">{{ ucfirst(str_replace('_', ' ', $book->status)) }}</span>
                                    <span><i class="bi bi-book"></i> {{ $book->subject->name ?? 'Unknown' }}</span>
                                </div>
                                <h3>{{ $book->title }}</h3>
                                <div class="meta">
                                    <i class="bi bi-folder"></i> {{ $book->chapters->count() }} chapter(s) &nbsp;|&nbsp;
                                    <i class="bi bi-question-circle"></i>
                                    {{ $book->chapters->sum(fn($c) => $c->questions->count()) }} question(s)
                                </div>
                                <div class="footer">
                                    <span
                                        style="font-size:.75rem;color:var(--muted);">{{ $book->created_at->diffForHumans() }}</span>
                                    <div
                                        style="display: flex; align-items: center; gap: 0.5rem; color: var(--primary); font-weight: 600;">
                                        View Details <i class="bi bi-arrow-right"></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        @endif
    @else
        <div class="empty animate-in" style="padding: 2rem;">
            <div class="icon"><i class="bi bi-hand-index-thumb"></i></div>
            <h2>Please Select a Standard</h2>
            <p>Select a grade above to manage chapters, questions, and view analytics.</p>
        </div>
    @endif
@endsection
