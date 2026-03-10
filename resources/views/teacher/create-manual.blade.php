@extends('layouts.teacher')
@section('title', 'Manual Text MCQ')

@push('styles')
    <style>
        .manual-card {
            max-width: 900px;
            margin: 0 auto;
            padding: 2.5rem;
        }

        .form-section {
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid var(--border);
        }

        .form-section:last-child {
            border-bottom: none;
        }

        .form-section-title {
            font-size: 1rem;
            font-weight: 700;
            margin-bottom: 1.25rem;
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        textarea.form-control {
            min-height: 300px;
            line-height: 1.6;
            font-size: 1rem;
        }

        .helper-text {
            margin-top: .5rem;
            font-size: .8rem;
            color: var(--muted);
        }

        .btn-large {
            padding: .85rem 2rem;
            font-size: 1rem;
        }
    </style>
@endpush

@section('content')
    <div class="breadcrumb animate-in">
        <a href="{{ route('teacher.dashboard') }}">Dashboard</a>
        <i class="bi bi-chevron-right"></i>
        <span>Create from Text</span>
    </div>

    <div class="page-header animate-in" style="--delay: 0.05s">
        <div>
            <h1><i class="bi bi-pencil-square"></i> Create from Text</h1>
            <p>Paste your content below and our AI will generate premium MCQs in the same language.</p>
        </div>
        <a href="{{ route('teacher.dashboard') }}" class="btn btn-ghost">← Back</a>
    </div>

    <div class="card manual-card animate-in">
        <form action="{{ route('teacher.manual-mcq.store') }}" method="POST">
            @csrf

            <div class="form-section">
                <div class="form-section-title"><i class="bi bi-pin-angle-fill"></i> Basic Information</div>
                <div class="grid-3" style="grid-template-columns: repeat(3, 1fr);">
                    <div class="form-group">
                        <label>Subject</label>
                        <select name="subject_id" id="subject_id" class="form-control" required>
                            <option value="">Select Subject</option>
                            @foreach ($subjects as $subject)
                                <option value="{{ $subject->id }}" data-name="{{ $subject->name }}"
                                    data-class="{{ $subject->class }}"
                                    {{ isset($selectedSubjectId) && $selectedSubjectId == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->name }} (Class {{ $subject->class }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Question Medium</label>
                        <select name="medium" id="medium" class="form-control" required>
                            <option value="english">English Medium</option>
                            <option value="gujarati">Gujarati Medium</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Standard / Grade</label>
                        <input type="text" name="standard" id="standard" class="form-control"
                            placeholder="e.g. 9 or 10 or 12" value="{{ $selectedStandard ?? '9' }}" required>
                    </div>
                </div>

                <div class="grid-2 mt-1">
                    <div class="form-group">
                        <label style="font-weight: 700; color: var(--text);">Question Format</label>
                        <div class="segmented-control"
                            style="display:flex; background:rgba(0,0,0,0.05); padding:5px; border-radius:14px; border:1px solid var(--border); margin-top:.5rem;">
                            <input type="radio" name="question_format" id="fmt_mcq" value="mcq" checked
                                style="display:none;">
                            <label for="fmt_mcq"
                                style="flex:1; padding:12px; text-align:center; cursor:pointer; border-radius:10px; font-weight:700; display:flex; align-items:center; justify-content:center; gap:.6rem; transition:.3s;"><i
                                    class="bi bi-journal-text"></i> MCQ</label>

                            <input type="radio" name="question_format" id="fmt_qa" value="qa"
                                style="display:none;">
                            <label for="fmt_qa"
                                style="flex:1; padding:12px; text-align:center; cursor:pointer; border-radius:10px; font-weight:700; display:flex; align-items:center; justify-content:center; gap:.6rem; transition:.3s;"><i
                                    class="bi bi-pencil-square"></i>
                                Short Q&A</label>
                        </div>
                        <div class="helper-text">Choose how you want the AI to generate questions for this content.</div>

                        <style>
                            .segmented-control input[type="radio"]:checked+label {
                                background: var(--primary);
                                color: #fff;
                                box-shadow: 0 4px 15px rgba(var(--primary-rgb), 0.3);
                            }

                            .segmented-control label {
                                color: var(--muted);
                            }

                            .segmented-control label:hover {
                                color: var(--primary);
                                background: rgba(var(--primary-rgb), 0.05);
                            }
                        </style>
                    </div>
                    <div class="form-group">
                        <label>Book / Topic Title</label>
                        <input type="text" name="title" id="book_title" class="form-control"
                            placeholder="e.g. Science Chapter 5" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Chapter Name</label>
                    <input type="text" name="chapter_title" id="chapter_title" class="form-control"
                        placeholder="e.g. Plant Growth" required>
                </div>
            </div>

            <div class="form-section">
                <div class="form-section-title"><i class="bi bi-pencil-square"></i> Content</div>
                <div class="form-group">
                    <label>Paste Content (Any Language)</label>
                    <textarea name="content" id="chapter_content" class="form-control"
                        placeholder="Paste the text from your book or notes here..." required></textarea>
                    <div style="margin-top: 0.5rem;">
                        <div class="helper-text" style="margin-top: 0;"><i class="bi bi-lightbulb"></i> Tip: Use around
                            500-2000 words for the best
                            quality questions.</div>
                    </div>
                    <div style="margin-top: 0.4rem; display: flex; justify-content: space-between; align-items: center;">
                        <span id="char_count" style="font-size: 0.8rem; color: var(--muted);">0 / 2500 characters</span>
                        <span id="char_warning" style="font-size: 0.8rem; color: var(--danger); display: none;"><i
                                class="bi bi-exclamation-triangle"></i> Content
                            too long! Max 2500 characters allowed.</span>
                    </div>
                </div>
            </div>

            <div class="mt-2" style="display:flex; justify-content: flex-end;">
                <button type="submit" id="submit_btn" class="btn btn-primary btn-large">
                    <i class="bi bi-rocket-takeoff"></i> Generate Questions
                </button>
            </div>
        </form>
    </div>
    @push('scripts')
        <script>
            // --- Auto-Conversion Manager ---
            ConversionManager.initAutoConversion(
                ['book_title', 'chapter_title', 'chapter_content'],
                '#subject_id',
                '#medium'
            );

            // --- Individual Button Handlers ---
            function handleConversion(fieldId) {
                const subjectEl = document.getElementById('subject_id');
                const mediumEl = document.getElementById('medium');

                const opt = subjectEl.options[subjectEl.selectedIndex];
                const subjectName = opt ? (opt.getAttribute('data-name') || opt.textContent) : '';
                const medium = mediumEl ? mediumEl.value : '';

                ConversionManager.handleField(fieldId, subjectName, medium);
            }

            // --- Character Count & Limit Logic ---
            const MAX_CHARS = 2500;
            const contentField = document.getElementById('chapter_content');
            const charCountEl = document.getElementById('char_count');
            const charWarningEl = document.getElementById('char_warning');
            const submitBtn = document.getElementById('submit_btn');

            function updateCharCount() {
                const len = contentField.value.length;
                charCountEl.textContent = len + ' / ' + MAX_CHARS + ' characters';

                if (len > MAX_CHARS) {
                    charCountEl.style.color = 'var(--danger)';
                    charWarningEl.style.display = 'inline';
                    submitBtn.disabled = true;
                    submitBtn.style.opacity = '0.5';
                    submitBtn.style.cursor = 'not-allowed';
                } else {
                    charCountEl.style.color = 'var(--muted)';
                    charWarningEl.style.display = 'none';
                    submitBtn.disabled = false;
                    submitBtn.style.opacity = '1';
                    submitBtn.style.cursor = 'pointer';
                }
            }

            contentField.addEventListener('input', updateCharCount);
            contentField.addEventListener('paste', () => setTimeout(updateCharCount, 10));
            window.addEventListener('load', updateCharCount);

            // --- Grade-based Subject Filtering ---
            const standardInput = document.getElementById('standard');
            const subjectSelect = document.getElementById('subject_id');
            const subjectOptions = Array.from(subjectSelect.options).slice(1); // Skip the "Select Subject" option

            function filterSubjects() {
                const selectedGrade = standardInput.value.trim();
                let matchingFound = false;

                subjectOptions.forEach(option => {
                    const optionGrade = option.getAttribute('data-class');
                    if (selectedGrade === "" || optionGrade === selectedGrade) {
                        option.hidden = false;
                        option.disabled = false;
                        matchingFound = true;
                    } else {
                        option.hidden = true;
                        option.disabled = true;
                        if (option.selected) {
                            subjectSelect.value = "";
                        }
                    }
                });

                // If currently selected subject is not in the list for this grade, reset it
                const currentOption = subjectSelect.options[subjectSelect.selectedIndex];
                if (currentOption && currentOption.value !== "" && (currentOption.getAttribute('data-class') !==
                        selectedGrade && selectedGrade !== "")) {
                    subjectSelect.value = "";
                }
            }

            standardInput.addEventListener('input', filterSubjects);
            window.addEventListener('load', filterSubjects);
        </script>
    @endpush
@endsection
