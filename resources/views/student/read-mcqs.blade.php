@extends('layouts.student')
@section('title', 'Read MCQs — Revizo')

@push('styles')
    <style>
        .read-container {
            max-width: 900px;
            margin: 0 auto;
        }

        .read-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow-sm);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .read-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .q-num {
            background: var(--primary-light);
            color: var(--primary);
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 0.85rem;
        }

        .q-text {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text);
            line-height: 1.5;
            margin-bottom: 1.5rem;
        }

        .options-list {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.75rem;
        }

        @media (max-width: 640px) {
            .options-list {
                grid-template-columns: 1fr;
            }
        }

        .option-item {
            padding: 0.85rem 1.25rem;
            background: var(--bg);
            border: 2px solid var(--border);
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            gap: 1rem;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.2s;
        }

        .option-item.correct {
            border-color: var(--success);
            background: #F0FDF4;
            color: #166534;
        }

        .opt-key {
            width: 28px;
            height: 28px;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 0.8rem;
            color: var(--text-light);
            flex-shrink: 0;
        }

        .option-item.correct .opt-key {
            background: var(--success);
            border-color: var(--success);
            color: #fff;
        }

        .explanation-section {
            display: none;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px dashed var(--border);
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .explanation-content {
            background: #F8FAFC;
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 1.25rem;
        }

        .difficulty-badge {
            font-size: 0.7rem;
            font-weight: 800;
            text-transform: uppercase;
            padding: 0.2rem 0.6rem;
            border-radius: 50px;
        }

        .diff-easy {
            background: #DCFCE7;
            color: #166534;
        }

        .diff-medium {
            background: #FEF9C3;
            color: #854D0E;
        }

        .diff-hard {
            background: #FEE2E2;
            color: #991B1B;
        }

        .header-section {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 3rem;
        }

        .header-info h1 {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--text);
        }
    </style>
@endpush

@section('content')
    <div class="read-container">
        <div class="header-section animate-in">
            <div class="header-info">
                <h1><i class="bi bi-journal-text"></i> MCQs: {{ $chapter->title }}</h1>
                <p style="color: var(--text-light); font-weight: 600;">Chapter {{ $chapter->chapter_number }} •
                    {{ $questions->count() }} Questions</p>
            </div>
            <a href="{{ route('student.subject.show', ['id' => $chapter->book->subject_id]) }}" class="btn btn-ghost">
                <i class="bi bi-arrow-left"></i> Back to Chapters
            </a>
        </div>

        @forelse($questions as $i => $q)
            <div class="read-card animate-in" id="card-{{ $i }}" style="--delay: {{ 0.1 + $i * 0.05 }}s">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem;">
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <div class="q-num">{{ $i + 1 }}</div>
                        <span
                            style="font-size: 0.75rem; font-weight: 800; color: var(--text-light); letter-spacing: 0.05em;">QUESTION</span>
                    </div>
                    @if ($q->difficulty)
                        <div class="difficulty-badge diff-{{ strtolower($q->difficulty) }}">
                            {{ $q->difficulty }}
                        </div>
                    @endif
                </div>
                <div class="q-text">{{ $q->question }}</div>

                <div class="options-list">
                    @foreach (['A' => $q->option_a, 'B' => $q->option_b, 'C' => $q->option_c, 'D' => $q->option_d] as $key => $val)
                        <div class="option-item {{ strtoupper($q->correct_answer) === $key ? 'correct' : '' }}">
                            <div class="opt-key">{{ $key }}</div>
                            <div>{{ $val }}</div>
                            @if (strtoupper($q->correct_answer) === $key)
                                <span style="margin-left: auto; font-size: 0.8rem;"><i class="bi bi-check-lg"></i>
                                    Correct</span>
                            @endif
                        </div>
                    @endforeach
                </div>

                <div style="margin-top: 1.5rem; display: flex; justify-content: flex-end;">
                    <button type="button" class="btn btn-ghost expand-btn"
                        style="color: var(--primary); font-size: 0.85rem; font-weight: 800; gap: 0.5rem;"
                        onclick="toggleExplanation({{ $i }})">
                        <span class="btn-icon"><i class="bi bi-lightbulb"></i></span>
                        <span class="btn-text">View Explanation</span>
                    </button>
                </div>

                <div class="explanation-section" id="explanation-{{ $i }}">
                    <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
                        <span style="font-weight: 800; color: var(--text); font-size: 0.95rem;">Solution &
                            Explanation</span>
                    </div>

                    <div class="explanation-content">
                        <div
                            style="font-size: 0.8rem; font-weight: 800; color: var(--success); text-transform: uppercase; margin-bottom: 0.5rem;">
                            Correct Answer: {{ $q->correct_answer }}</div>
                        <div style="color: var(--text); line-height: 1.6; font-size: 0.95rem;">
                            {{ $q->explanation ?? 'No detailed explanation provided for this question.' }}
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="card animate-in" style="text-align:center; padding:5rem;">
                <div style="font-size:4rem; margin-bottom:1rem;"><i class="bi bi-journal-x"></i></div>
                <h3>No MCQs found for this chapter</h3>
                <p>Check back later or try a different mode.</p>
            </div>
        @endforelse
    </div>

    @push('scripts')
        <script>
            function toggleExplanation(idx) {
                const section = document.getElementById(`explanation-${idx}`);
                const btn = document.querySelector(`#card-${idx} .expand-btn`);
                const btnText = btn.querySelector('.btn-text');

                if (section.style.display === 'block') {
                    section.style.display = 'none';
                    btnText.innerText = 'View Explanation';
                    btn.style.background = 'transparent';
                } else {
                    section.style.display = 'block';
                    btnText.innerText = 'Hide Explanation';
                    btn.style.background = 'var(--primary-light)';
                }
            }
        </script>
    @endpush
@endsection
