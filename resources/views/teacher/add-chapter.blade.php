@extends('layouts.teacher')
@section('title', 'Add Content')

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
        <a href="{{ route('teacher.book.show', $book) }}">{{ $book->title }}</a>
        <i class="bi bi-chevron-right"></i>
        <span>Add Content</span>
    </div>

    <div class="page-header animate-in" style="--delay: 0.05s">
        <div>
            <h1><i class="bi bi-plus-lg"></i> Add More Content</h1>
            <p>Add a new chapter or topic to <strong>{{ $book->title }}</strong>.</p>
        </div>
        <a href="{{ route('teacher.book.show', $book) }}" class="btn btn-ghost">← Back</a>
    </div>

    <div class="card add-chapter-card animate-in">
        <div class="instruction-box">
            <strong><i class="bi bi-lightbulb"></i> Recommendation:</strong> Paste one chapter or topic at a time. The AI
            generates the best MCQs when
            the content is focused and clear.
        </div>

        <form action="{{ route('teacher.book.chapter.store', $book) }}" method="POST">
            @csrf

            <div class="grid-2">
                <div class="form-group">
                    <label>Chapter / Topic Title</label>
                    <input type="text" name="title" id="chapter_title"
                        class="form-control @error('title') is-invalid @enderror" placeholder="e.g. Real Numbers"
                        value="{{ old('title') }}" required>
                    @error('title')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Chapter Number</label>
                    <input type="number" name="chapter_number"
                        class="form-control @error('chapter_number') is-invalid @enderror" placeholder="e.g. 2"
                        value="{{ old('chapter_number', $book->chapters->count() + 1) }}" required>
                    @error('chapter_number')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label style="font-weight: 700; color: var(--text);">Question Format</label>
                <div class="segmented-control">
                    <input type="radio" name="question_format" id="fmt_mcq" value="mcq"
                        {{ $book->question_preference == 'mcq' ? 'checked' : '' }}>
                    <label for="fmt_mcq"><i class="bi bi-journal-text"></i> એમસીક્યુ (MCQ)</label>

                    <input type="radio" name="question_format" id="fmt_qa" value="qa"
                        {{ $book->question_preference == 'qa' ? 'checked' : '' }}>
                    <label for="fmt_qa"><i class="bi bi-pencil-square"></i> પ્રશ્ન-જવાબ (Q&A)</label>

                    <input type="radio" name="question_format" id="fmt_topic" value="topic"
                        {{ $book->question_preference == 'topic' ? 'checked' : '' }}>
                    <label for="fmt_topic"><i class="bi bi-book"></i> વિષય સમજૂતી (Topic)</label>
                </div>
                <div class="helper-text" style="font-size: .8rem; color: var(--muted); margin-top: .6rem;">Choose how the AI
                    should generate questions for this new chapter.</div>
            </div>

            <div id="topic_title_container" class="form-group animate-in"
                style="display: {{ old('question_format', $book->question_preference) == 'topic' ? 'block' : 'none' }};">
                <label>Topic Title (Optional)</label>
                <input type="text" name="topic_title" id="topic_title"
                    class="form-control @error('topic_title') is-invalid @enderror"
                    placeholder="e.g. Introduction to Rational Numbers" value="{{ old('topic_title') }}">
                <div class="helper-text" style="font-size: .8rem; color: var(--muted); margin-top: .4rem;">If left blank,
                    the
                    AI will decide the title.</div>
                @error('topic_title')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div id="content_container" class="form-group animate-in"
                style="display: {{ old('question_format', $book->question_preference) == 'topic' ? 'none' : 'block' }};">
                <label>Content (Paste here)</label>
                <textarea name="content" id="chapter_content" class="form-control @error('content') is-invalid @enderror"
                    placeholder="Paste your text content here...">{{ old('content') }}</textarea>
                @error('content')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-2" style="display:flex; justify-content: flex-end;">
                <button type="submit" id="submit_btn" class="btn btn-primary btn-large">
                    {{ old('question_format', $book->question_preference) == 'topic' ? 'Generate Topic Study Material' : 'Save & Generate Questions' }}
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            const bookSubjectId = "{{ $book->subject_id }}";
            const bookSubjectName = "{{ strtolower($book->subject->name ?? '') }}";
            // For existing books, we don't have a 'medium' field yet, but we can assume English or base it on subject
            const medium = (bookSubjectName === 'gujarati') ? 'gujarati' : 'english';

            ConversionManager.initAutoConversion(
                ['chapter_title', 'chapter_content'],
                '', // No subject selector needed here as we have bookSubjectName
                '' // No medium selector needed here
            );

            // Override determineLanguage to use fixed book properties
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
                        submitBtn.innerText = '✨ Generate Topic Study Material';
                    } else {
                        topicContainer.style.display = 'none';
                        contentContainer.style.display = 'block';
                        topicInput.required = false;
                        submitBtn.innerText = '🚀 Save & Generate Questions';
                    }
                });
            });

            // Add topic_title to AutoConversion
            ConversionManager.initAutoConversion(
                ['chapter_title', 'chapter_content', 'topic_title'],
                '',
                ''
            );
        </script>
    @endpush
@endsection
