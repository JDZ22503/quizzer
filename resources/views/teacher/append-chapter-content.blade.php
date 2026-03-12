    @extends('layouts.teacher')
    @section('title', 'Add More Content to Chapter')

    @section('content')
        <div class="page-container">
            <div class="bg-glow"></div>

            <div class="breadcrumb animate-in">
                <a href="{{ route('teacher.dashboard') }}">Dashboard</a>
                <i class="bi bi-chevron-right"></i>
                <a href="{{ route('teacher.dashboard', ['standard' => $chapter->book->subject->class]) }}">Class
                    {{ $chapter->book->subject->class }}</a>
                <i class="bi bi-chevron-right"></i>
                <a
                    href="{{ route('teacher.dashboard', ['standard' => $chapter->book->subject->class, 'subject_id' => $chapter->book->subject_id]) }}">{{ $chapter->book->subject->name }}</a>
                <i class="bi bi-chevron-right"></i>
                <a href="{{ route('teacher.book.show', $chapter->book_id) }}">{{ $chapter->book->title }}</a>
                <i class="bi bi-chevron-right"></i>
                <span>Add Content to {{ $chapter->title }}</span>
            </div>

            <div class="page-header animate-in">
                <div>
                    <h1 style="font-size: 2.25rem; font-weight: 800; margin-bottom: 0.5rem; color: var(--text);"><i
                            class="bi bi-plus-lg"></i> Add More
                        Content</h1>
                    <p style="color: var(--muted); font-size: 1.05rem;">
                        Expanding <span style="color: var(--text); font-weight: 600;">{{ $chapter->title }}</span> with
                        fresh
                        AI-generated questions.
                    </p>
                </div>
                <a href="{{ route('teacher.book.show', $chapter->book_id) }}" class="btn btn-ghost">
                    <i class="fas fa-arrow-left"></i> Back to Book
                </a>
            </div>

            <div class="card manual-card animate-in">
                <form action="{{ route('teacher.chapter.append.store', $chapter) }}" method="POST">
                    @csrf

                    <div class="form-section" style="border: none; padding: 0; background: transparent; margin-bottom: 0;">
                        <div class="form-section-title">
                            <i class="fas fa-pen-nib"></i> Additional Content
                        </div>
                        <div id="content_container" class="form-group animate-in"
                            style="display: {{ old('question_format', $chapter->book->question_preference) == 'topic' ? 'none' : 'block' }};">
                            <label
                                style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--muted); margin-bottom: 0.75rem; display: block; font-weight: 700;">
                                Paste text from your book or notes
                            </label>
                            <textarea name="content" id="append_content" class="form-control"
                                placeholder="Start typing or paste your content here..."></textarea>
                        </div>

                        <div style="margin-top: 1.5rem; max-width: 400px;">
                            <label
                                style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.08em; color: var(--muted); margin-bottom: 0.5rem; display: block; font-weight: 700;">Question
                                Format</label>
                            <div class="segmented-control">
                                <input type="radio" name="question_format" id="fmt_mcq" value="mcq"
                                    {{ $chapter->book->question_preference == 'mcq' ? 'checked' : '' }}>
                                <label for="fmt_mcq"><i class="bi bi-journal-text"></i> MCQ</label>

                                <input type="radio" name="question_format" id="fmt_qa" value="qa"
                                    {{ $chapter->book->question_preference == 'qa' ? 'checked' : '' }}>
                                <label for="fmt_qa"><i class="bi bi-pencil-square"></i> Q&A</label>

                                <input type="radio" name="question_format" id="fmt_topic" value="topic"
                                    {{ $chapter->book->question_preference == 'topic' ? 'checked' : '' }}>
                                <label for="fmt_topic"><i class="bi bi-book"></i> Topic</label>
                            </div>
                        </div>

                        <div id="topic_title_container" class="form-group animate-in"
                            style="display: {{ old('question_format', $chapter->book->question_preference) == 'topic' ? 'block' : 'none' }}; margin-top: 1.5rem;">
                            <label
                                style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.08em; color: var(--muted); margin-bottom: 0.5rem; display: block; font-weight: 700;">Topic
                                Title (Optional)</label>
                            <input type="text" name="topic_title" id="topic_title" class="form-control"
                                style="background: var(--bg); border: 2px solid var(--border); border-radius: var(--radius-md); padding: 0.75rem 1rem; color: var(--text);"
                                placeholder="e.g. Types of Real Numbers" value="{{ old('topic_title') }}">
                            <div class="helper-text" style="font-size: .75rem; color: var(--muted); margin-top: .4rem;">Give
                                this specific segment a title.</div>
                        </div>
                    </div>

                    <div class="mt-4" style="display:flex; justify-content: flex-end;">
                        <button type="submit" id="submit_btn" class="btn-generate">
                            {{ old('question_format', $chapter->book->question_preference) == 'topic' ? 'Generate Topic Material' : 'Generate Questions' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @push('scripts')
            <script>
                const bookSubjectName = "{{ strtolower($chapter->book->subject->name ?? '') }}";
                // For existing chapters, assume medium based on subject or book context
                const medium = (bookSubjectName === 'gujarati') ? 'gujarati' : 'english';

                ConversionManager.initAutoConversion(
                    ['append_content'],
                    '', // No subject selector
                    '' // No medium selector
                );

                // Override determineLanguage
                ConversionManager.determineLanguage = function() {
                    if (bookSubjectName === 'hindi') return 'hindi';
                    if (bookSubjectName === 'gujarati' || medium === 'gujarati') return 'gujarati';
                    return null;
                };

                function handleConversion(fieldId) {
                    ConversionManager.handleField(fieldId, bookSubjectName, medium);
                }

                // Toggle Topic UI logic
                document.querySelectorAll('input[name="question_format"]').forEach(radio => {
                    radio.addEventListener('change', function() {
                        const topicContainer = document.getElementById('topic_title_container');
                        const contentContainer = document.getElementById('content_container');
                        const topicInput = document.getElementById('topic_title');
                        const submitBtn = document.getElementById('submit_btn');

                        if (this.value === 'topic') {
                            topicContainer.style.display = 'block';
                            contentContainer.style.display = 'none';
                            topicInput.required = true;
                            submitBtn.innerText = '✨ Generate Topic Material';
                        } else {
                            topicContainer.style.display = 'none';
                            contentContainer.style.display = 'block';
                            topicInput.required = false;
                            submitBtn.innerText = '✨ Generate Questions';
                        }
                    });
                });

                // Add topic_title to AutoConversion
                ConversionManager.initAutoConversion(
                    ['append_content', 'topic_title'],
                    '',
                    ''
                );
            </script>
        @endpush
    @endsection
