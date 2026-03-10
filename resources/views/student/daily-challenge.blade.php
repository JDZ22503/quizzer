@extends('layouts.student')
@section('title', 'Daily Challenge — Revizo')

@push('styles')
    <style>
        .quiz-container {
            max-width: 800px;
            margin: 0 auto;
        }

        .quiz-header {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 2rem;
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .q-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-xl);
            padding: 2.25rem 2.5rem;
            margin-bottom: 2rem;
            display: none;
            box-shadow: var(--shadow-md);
        }

        .q-card.active {
            display: block;
            animation: slide-up 0.4s ease-out;
        }

        @keyframes slide-up {
            from {
                opacity: 0;
                transform: translateY(15px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .q-text {
            font-size: 1.25rem;
            font-weight: 800;
            line-height: 1.4;
            margin-bottom: 1.5rem;
        }

        .options-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }

        @media (max-width: 640px) {
            .options-grid {
                grid-template-columns: 1fr;
            }
        }

        .option-card {
            padding: 1.25rem 1.5rem;
            background: var(--bg);
            border: 2px solid var(--border);
            border-radius: var(--radius-lg);
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 1.25rem;
            font-weight: 700;
        }

        .option-card:hover {
            border-color: var(--primary);
            background: var(--surface);
        }

        .option-card.selected {
            border-color: var(--primary);
            background: var(--primary-light);
            color: var(--primary);
        }

        .prog-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--primary);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
        }

        .mission-badge {
            background: #fee2e2;
            color: #ef4444;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-weight: 800;
            font-size: 0.8rem;
        }
    </style>
@endpush

@section('content')
    <div class="quiz-container">
        <div class="quiz-header animate-in">
            <div>
                <span class="mission-badge"><i class="bi bi-lightning-fill"></i> DAILY MISSION</span>
                <h1 style="margin-top: 0.5rem; font-weight: 800;">5-Question Sprint</h1>
            </div>
            <div id="progress-text" style="font-weight: 800; color: var(--text-light);">Question 1 of 5</div>
        </div>

        <form action="{{ route('student.daily-challenge.submit') }}" method="POST" id="quiz-form">
            @csrf
            @foreach ($questions as $index => $q)
                <div class="q-card {{ $index === 0 ? 'active' : '' }}" id="q-{{ $index }}">
                    <div class="q-text">{{ $q->question }}</div>

                    <input type="hidden" name="answers[{{ $index }}][question_id]" value="{{ $q->id }}">
                    <input type="hidden" name="answers[{{ $index }}][answer]" id="input-{{ $index }}">

                    <div class="options-grid">
                        @foreach (['A' => $q->option_a, 'B' => $q->option_b, 'C' => $q->option_c, 'D' => $q->option_d] as $key => $val)
                            <div class="option-card" onclick="selectOption({{ $index }}, '{{ $key }}')">
                                <div class="prog-circle"
                                    style="background: var(--border); color: var(--text-light); border: 1px solid var(--border); width: 32px; height: 32px; font-size: 0.9rem;">
                                    {{ $key }}</div>
                                <span style="font-size: 0.95rem;">{{ $val }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </form>

        <div class="footer-actions animate-in" style="display: flex; justify-content: center; --delay: 0.3s">
            <button id="next-btn" class="btn btn-primary btn-lg" style="width: 200px;" onclick="nextQuestion()" disabled>
                Next Question <i class="bi bi-arrow-right"></i>
            </button>
            <button id="submit-btn" class="btn btn-primary btn-lg" style="width: 200px; display:none;"
                onclick="document.getElementById('quiz-form').submit()">
                Finish Challenge! <i class="bi bi-fire"></i>
            </button>
        </div>
    </div>

    @push('scripts')
        <script>
            let currentIdx = 0;
            const total = {{ $questions->count() }};



            function selectOption(qIdx, key) {
                // UI
                const cards = document.querySelectorAll(`#q-${qIdx} .option-card`);
                cards.forEach(c => c.classList.remove('selected'));
                event.currentTarget.classList.add('selected');

                // Data
                document.getElementById(`input-${qIdx}`).value = key;
                document.getElementById('next-btn').disabled = false;
            }

            function nextQuestion() {
                if (currentIdx < total - 1) {
                    document.getElementById(`q-${currentIdx}`).classList.remove('active');
                    currentIdx++;
                    document.getElementById(`q-${currentIdx}`).classList.add('active');
                    document.getElementById('next-btn').disabled = true;
                    document.getElementById('progress-text').innerText = `Question ${currentIdx + 1} of 5`;

                    if (currentIdx === total - 1) {
                        document.getElementById('next-btn').style.display = 'none';
                        document.getElementById('submit-btn').style.display = 'block';
                    }
                }
            }
        </script>
    @endpush
@endsection
