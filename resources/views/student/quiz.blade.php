@extends('layouts.student')
@section('title', 'Quiz — Revizo')

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
            padding: 1.25rem 2rem;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: var(--shadow-sm);
        }

        .header-left h1 {
            font-size: 1.1rem;
            font-weight: 800;
            color: var(--text);
            margin-bottom: 0.15rem;
            text-transform: uppercase;
            letter-spacing: 0.02em;
        }

        .header-meta {
            font-size: 0.85rem;
            color: var(--text-light);
            display: flex;
            align-items: center;
            gap: 1rem;
            font-weight: 600;
        }

        .live-score {
            color: var(--success);
            background: #F0FDF4;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            border: 1px solid rgba(34, 197, 94, 0.2);
        }

        .timer {
            background: #FEF2F2;
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: var(--error);
            padding: 0.6rem 1.25rem;
            border-radius: var(--radius-md);
            font-size: 1.25rem;
            font-weight: 800;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-family: 'Poppins', sans-serif;
            min-width: 100px;
            justify-content: center;
        }

        .timer.warning {
            animation: pulse-danger 0.5s infinite alternate;
        }

        @keyframes pulse-danger {
            from {
                background: #FEF2F2;
                transform: scale(1);
            }

            to {
                background: #fee2e2;
                transform: scale(1.05);
            }
        }

        .progress-container {
            height: 8px;
            background: var(--border);
            border-radius: 10px;
            margin-bottom: 2.5rem;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(to right, var(--primary), var(--secondary));
            transition: width 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .q-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
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

        .q-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
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
            font-size: 0.85rem;
            font-weight: 800;
        }

        .q-text {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text);
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

        .opt-label {
            display: flex;
            align-items: center;
            gap: 1rem;
            background: #F8FAFC;
            border: 2px solid var(--border);
            border-radius: 12px;
            padding: 1rem 1.25rem;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }

        .opt-label:hover:not(.disabled) {
            border-color: var(--primary);
            background: var(--surface);
            transform: translateX(4px);
        }

        .opt-label input {
            display: none;
        }

        .opt-key {
            width: 36px;
            height: 36px;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            color: var(--text-light);
            transition: all 0.2s;
        }

        .opt-label:has(input:checked) {
            border-color: var(--primary);
            background: var(--primary-light);
        }

        .opt-label input:checked~.opt-key {
            background: var(--primary);
            border-color: var(--primary);
            color: #fff;
        }

        /* Result States */
        .opt-label.is-correct {
            border-color: var(--success) !important;
            background: #F0FDF4 !important;
        }

        .opt-label.is-correct .opt-key {
            background: var(--success) !important;
            border-color: var(--success) !important;
            color: #fff !important;
        }

        .opt-label.is-wrong {
            border-color: var(--error) !important;
            background: #FEF2F2 !important;
        }

        .opt-label.is-wrong .opt-key {
            background: var(--error) !important;
            border-color: var(--error) !important;
            color: #fff !important;
        }

        .opt-label.disabled {
            cursor: default;
            opacity: 0.8;
        }

        .quiz-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 2rem;
        }

        #feedbackMsg {
            font-size: 1.15rem;
            font-weight: 800;
            font-family: 'Poppins', sans-serif;
        }

        .btn-quiz {
            padding: 1rem 2rem;
            font-size: 1.1rem;
            min-width: 180px;
        }

        /* Modal Overhaul */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(15, 23, 42, 0.4);
            backdrop-filter: blur(8px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            padding: 1.5rem;
            transition: all 0.3s ease;
        }

        .modal-overlay.show {
            display: flex;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .modal-content {
            background: var(--surface);
            border: none;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            padding: 2.5rem;
            border-radius: 24px;
            max-width: 400px;
            width: 100%;
            text-align: center;
            transform: scale(0.9);
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .modal-overlay.show .modal-content {
            transform: scale(1);
        }
    </style>
@endpush

@section('content')
    <div class="quiz-container">
        <div class="quiz-header animate-in">
            <div class="header-left">
                <div
                    style="font-size: 0.75rem; font-weight: 700; color: var(--primary); text-transform: uppercase; margin-bottom: 0.25rem;">
                    Chapter {{ $chapter->chapter_number }}</div>
                <h1>{{ $chapter->title }}</h1>
                <div class="header-meta">
                    <span>{{ $questions->count() }} Questions</span>
                    <span class="live-score" id="scoreDisplay">Score: 0 / {{ $questions->count() }}</span>
                </div>
            </div>
            <div class="timer" id="timerContainer">
                <i class="bi bi-stopwatch"></i> <span id="timerDisplay">10s</span>
            </div>
        </div>

        <div class="progress-container animate-in">
            <div class="progress-fill" id="progressFill" style="width: 0%"></div>
        </div>

        <form method="POST" action="{{ route('student.quiz.submit') }}" id="quizForm">
            @csrf
            <input type="hidden" name="chapter_id" value="{{ $chapter->id }}">
            @foreach ($questions as $i => $q)
                <div class="q-card" id="qcard-{{ $i }}" data-index="{{ $i }}"
                    data-correct="{{ strtoupper($q->correct_answer) }}">
                    <div class="q-header" style="justify-content: space-between;">
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <span class="q-num">{{ $i + 1 }}</span>
                            <span
                                style="font-size: 0.75rem; font-weight: 800; color: var(--text-light); letter-spacing: 0.05em;">QUESTION</span>
                        </div>
                        @if ($q->difficulty)
                            <span
                                style="font-size: 0.7rem; font-weight: 800; background: #ebfef2; color: #22c55e; padding: 4px 10px; border-radius: 50px;">{{ strtoupper($q->difficulty) }}</span>
                        @endif
                    </div>
                    <div class="q-text">{{ $q->question }}</div>
                    <div class="options-grid">
                        @foreach (['A' => $q->option_a, 'B' => $q->option_b, 'C' => $q->option_c, 'D' => $q->option_d] as $key => $val)
                            <label class="opt-label" id="label-{{ $i }}-{{ $key }}">
                                <input type="radio" name="answers[{{ $i }}][answer]"
                                    value="{{ $key }}" onchange="onAnswerSelected({{ $i }})">
                                <span class="opt-key">{{ $key }}</span>
                                <span style="font-weight: 600; font-size: 0.95rem;">{{ $val }}</span>
                            </label>
                        @endforeach
                    </div>
                    <input type="hidden" name="answers[{{ $i }}][question_id]" value="{{ $q->id }}">
                    <input type="hidden" name="answers[{{ $i }}][answer]" value=""
                        id="fallback-{{ $i }}" disabled>
                </div>
            @endforeach

            <div class="quiz-footer animate-in">
                <div id="feedbackMsg" class="feedback-msg"></div>
                <button type="button" class="btn btn-primary btn-quiz" id="actionBtn" onclick="handleAction()">
                    Check Answer <i class="bi bi-arrow-right"></i>
                </button>
            </div>
        </form>
    </div>

    <!-- Alert Modal -->
    <div id="alertModal" class="modal-overlay">
        <div class="modal-content">
            <div style="font-size: 4rem; margin-bottom: 1.5rem;"><i class="bi bi-question-circle"></i></div>
            <h3>Select an Option!</h3>
            <p>You need to choose an answer before we can check if it's correct.</p>
            <button type="button" class="btn btn-primary btn-block" onclick="closeAlertModal()">Got it!</button>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const totalQuestions = {{ $questions->count() }};
        let currentIndex = 0;
        let timeLeft = 10;
        let timerInterval;
        let score = 0;
        let isAnswerChecked = false;
        let isTimerPaused = false;
        let autoAdvanceTimeout;

        const timerDisplay = document.getElementById('timerDisplay');
        const timerContainer = document.getElementById('timerContainer');
        const progressFill = document.getElementById('progressFill');
        const actionBtn = document.getElementById('actionBtn');
        const quizForm = document.getElementById('quizForm');
        const feedbackMsg = document.getElementById('feedbackMsg');
        const scoreDisplay = document.getElementById('scoreDisplay');
        const alertModal = document.getElementById('alertModal');

        function startQuiz() {
            showQuestion(0);
        }



        function showQuestion(index) {
            isAnswerChecked = false;
            hideFeedback();
            clearTimeout(autoAdvanceTimeout);

            document.querySelectorAll('.q-card').forEach(card => card.classList.remove('active'));
            const activeCard = document.getElementById(`qcard-${index}`);
            if (activeCard) {
                activeCard.classList.add('active');
            }

            const progress = ((index) / totalQuestions) * 100;
            progressFill.style.width = `${progress}%`;

            actionBtn.innerHTML = "Check Answer";
            actionBtn.style.display = 'inline-block';
            actionBtn.classList.remove('btn-success', 'btn-secondary');
            actionBtn.classList.add('btn-primary');
            actionBtn.disabled = false;

            resetTimer();
        }

        function resetTimer() {
            clearInterval(timerInterval);
            timeLeft = 10;
            isTimerPaused = false;
            updateTimerUI();

            timerInterval = setInterval(() => {
                if (isTimerPaused) return;

                timeLeft--;
                updateTimerUI();

                if (timeLeft <= 0) {
                    handleTimeout();
                }
            }, 1000);
        }

        function updateTimerUI() {
            timerDisplay.textContent = `${Math.max(0, timeLeft)}s`;
            if (timeLeft <= 3 && !isAnswerChecked && !isTimerPaused) {
                timerContainer.classList.add('warning');
            } else {
                timerContainer.classList.remove('warning');
            }
        }

        function onAnswerSelected() {
            // Optional: You could auto-check here, but the user requested clicking the button.
            // Leaving this empty so they *must* click "Check Answer" or wait for timeout.
        }

        function checkAnswer() {
            const activeCard = document.getElementById(`qcard-${currentIndex}`);
            const selectedOption = activeCard.querySelector('input[type=radio]:checked');

            if (!selectedOption) return; // shouldn't happen, handled by handleAction

            clearInterval(timerInterval);
            isAnswerChecked = true;
            disableOptions(activeCard);

            const correctAnswer = activeCard.getAttribute('data-correct');
            const selectedValue = selectedOption.value;

            const correctLabel = document.getElementById(`label-${currentIndex}-${correctAnswer}`);
            if (correctLabel) correctLabel.classList.add('is-correct');

            if (selectedValue === correctAnswer) {
                score++;
                scoreDisplay.textContent = `Score: ${score} / ${totalQuestions}`;
                showFeedback('Correct! <i class="bi bi-check-circle-fill"></i>', 'success');
            } else {
                const selectedLabel = document.getElementById(`label-${currentIndex}-${selectedValue}`);
                if (selectedLabel) selectedLabel.classList.add('is-wrong');
                showFeedback('Incorrect.', 'error');
            }

            prepareNextState();

            // Auto advance after 1.5 seconds
            autoAdvanceTimeout = setTimeout(() => {
                goToNext();
            }, 1500);
        }

        function prepareNextState() {
            timerContainer.classList.remove('warning');

            if (currentIndex === totalQuestions - 1) {
                actionBtn.innerHTML = '<i class="bi bi-check-all"></i> Submit Quiz';
                actionBtn.classList.remove('btn-primary', 'btn-secondary');
                actionBtn.classList.add('btn-success');
            } else {
                actionBtn.innerHTML = 'Next Question <i class="bi bi-arrow-right"></i>';
                actionBtn.classList.remove('btn-primary', 'btn-success');
                actionBtn.classList.add('btn-secondary');
            }
        }

        function handleAction() {
            if (isAnswerChecked) {
                // If already checked, this button acts as "Next Question"
                goToNext();
                return;
            }

            const activeCard = document.getElementById(`qcard-${currentIndex}`);
            const selectedOption = activeCard.querySelector('input[type=radio]:checked');

            if (!selectedOption) {
                isTimerPaused = true;
                timerContainer.classList.remove('warning');
                alertModal.classList.add('show');
            } else {
                checkAnswer();
            }
        }

        function closeAlertModal() {
            alertModal.classList.remove('show');
            isTimerPaused = false;
        }

        function handleTimeout() {
            clearInterval(timerInterval);
            if (isAnswerChecked) return;

            isAnswerChecked = true;
            const activeCard = document.getElementById(`qcard-${currentIndex}`);
            disableOptions(activeCard);
            actionBtn.style.display = 'none';

            const correctAnswer = activeCard.getAttribute('data-correct');

            const correctLabel = document.getElementById(`label-${currentIndex}-${correctAnswer}`);
            if (correctLabel) correctLabel.classList.add('is-correct');

            const fallbackInput = document.getElementById(`fallback-${currentIndex}`);
            if (fallbackInput) fallbackInput.disabled = false;

            showFeedback("Time's up! Unanswered.", 'error');

            setTimeout(() => {
                goToNext();
            }, 2000);
        }

        function disableOptions(card) {
            card.querySelectorAll('.opt-label').forEach(label => {
                label.classList.add('disabled');
            });
        }

        function showFeedback(msg, type) {
            feedbackMsg.textContent = msg;
            feedbackMsg.className = `feedback-msg show ${type}`;
        }

        function hideFeedback() {
            feedbackMsg.className = 'feedback-msg';
            feedbackMsg.textContent = '';
        }

        function goToNext() {
            if (currentIndex < totalQuestions - 1) {
                currentIndex++;
                showQuestion(currentIndex);
            } else {
                actionBtn.style.display = 'inline-block';
                actionBtn.disabled = true;
                actionBtn.innerHTML = "Submitting...";
                quizForm.submit();
            }
        }

        document.addEventListener('DOMContentLoaded', startQuiz);
    </script>
@endpush
