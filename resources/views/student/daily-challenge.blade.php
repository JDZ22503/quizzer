@extends('layouts.student')
@section('title', 'Daily Challenge — Revizo')

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
            <input type="hidden" name="time_taken" id="time_taken_input" value="0">
        </form>

        <div class="footer-actions animate-in" style="display: flex; justify-content: center; --delay: 0.3s">
            <button id="next-btn" class="btn btn-primary btn-lg" style="width: 200px;" onclick="nextQuestion()" disabled>
                Next Question <i class="bi bi-arrow-right"></i>
            </button>
            <button id="submit-btn" class="btn btn-primary btn-lg" style="width: 200px; display:none;"
                onclick="submitChallenge()">
                Finish Challenge! <i class="bi bi-fire"></i>
            </button>
        </div>
    </div>

    @push('scripts')
        <script>
            let currentIdx = 0;
            const total = {{ $questions->count() }};
            let startTime = Date.now();



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

            function submitChallenge() {
                const submitBtn = document.getElementById('submit-btn');
                submitBtn.disabled = true;
                submitBtn.innerHTML = "Submitting...";
                document.getElementById('time_taken_input').value = Math.floor((Date.now() - startTime) / 1000);
                document.getElementById('quiz-form').submit();
            }
        </script>
    @endpush
@endsection
