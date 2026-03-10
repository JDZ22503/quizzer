@extends('layouts.student')
@section('title', 'Quiz Results')

@push('styles')
    <style>
        .result-hero {
            text-align: center;
            padding: 3rem 2rem;
            background: linear-gradient(135deg, rgba(59, 130, 246, .1), rgba(34, 211, 238, .06));
            border: 1px solid var(--border);
            border-radius: 20px;
            margin-bottom: 2rem;
        }

        .score-ring {
            width: 140px;
            height: 140px;
            margin: 0 auto 1.5rem;
            position: relative;
        }

        .score-ring svg {
            transform: rotate(-90deg);
        }

        .score-ring .bg {
            fill: none;
            stroke: var(--border);
            stroke-width: 10;
        }

        .score-ring .fg {
            fill: none;
            stroke-width: 10;
            stroke-linecap: round;
            stroke-dasharray: 351.68;
            transition: stroke-dashoffset 1s ease;
        }

        .score-center {
            position: absolute;
            inset: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .score-center .pct {
            font-size: 1.75rem;
            font-weight: 800;
        }

        .score-center .lbl {
            font-size: .7rem;
            color: var(--muted);
            font-weight: 600;
        }

        .score-badge {
            display: inline-block;
            font-size: 1rem;
            font-weight: 700;
            padding: .4rem 1.25rem;
            border-radius: 20px;
            margin-bottom: 1rem;
        }

        .result-hero p {
            color: var(--muted);
            font-size: .9rem;
        }

        .results-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .r-card {
            background: var(--card);
            border-radius: 14px;
            border: 1px solid var(--border);
            overflow: hidden;
        }

        .r-card.correct {
            border-color: rgba(52, 211, 153, .4);
        }

        .r-card.wrong {
            border-color: rgba(248, 113, 113, .4);
        }

        .r-header {
            padding: 1rem 1.25rem;
            display: flex;
            align-items: flex-start;
            gap: .75rem;
        }

        .r-icon {
            width: 28px;
            height: 28px;
            border-radius: 8px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .9rem;
            margin-top: .1rem;
        }

        .r-icon.c {
            background: rgba(52, 211, 153, .15);
        }

        .r-icon.w {
            background: rgba(248, 113, 113, .15);
        }

        .r-question {
            font-size: .95rem;
            font-weight: 600;
            line-height: 1.5;
        }

        .r-body {
            padding: .75rem 1.25rem 1.25rem;
            border-top: 1px solid var(--border);
        }

        .r-opts {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: .4rem;
            margin-bottom: .75rem;
        }

        .r-opt {
            padding: .4rem .75rem;
            border-radius: 8px;
            font-size: .8rem;
            background: var(--surface);
        }

        .r-opt.your {
            background: rgba(248, 113, 113, .1);
            border: 1px solid rgba(248, 113, 113, .3);
            color: var(--danger);
        }

        .r-opt.right {
            background: rgba(52, 211, 153, .1);
            border: 1px solid rgba(52, 211, 153, .3);
            color: var(--success);
        }

        .explanation {
            background: rgba(59, 130, 246, .08);
            border: 1px solid rgba(59, 130, 246, .2);
            border-radius: 10px;
            padding: .75rem 1rem;
            font-size: .82rem;
            color: var(--muted);
            line-height: 1.6;
        }

        .explanation strong {
            color: var(--primary);
        }
    </style>
@endpush

@section('content')
    @php
        $pct = $total > 0 ? round(($score / $total) * 100) : 0;
        $offset = 351.68 * (1 - $pct / 100);
        $color = $pct >= 70 ? '#34d399' : ($pct >= 40 ? '#3b82f6' : '#f87171');
        $grade =
            $pct >= 80
                ? '<i class="bi bi-award-fill"></i> Excellent!'
                : ($pct >= 60
                    ? '<i class="bi bi-hand-thumbs-up-fill"></i> Good Job!'
                    : ($pct >= 40
                        ? '<i class="bi bi-lightning-charge-fill"></i> Keep Trying!'
                        : '<i class="bi bi-book-half"></i> Review Needed'));
        $gradeBg = $pct >= 70 ? 'rgba(52,211,153,.1)' : ($pct >= 40 ? 'rgba(59,130,246,.1)' : 'rgba(248,113,113,.1)');
    @endphp

    <div class="result-hero animate-in">
        <div class="score-ring">
            <svg viewBox="0 0 120 120" width="140" height="140">
                <circle class="bg" cx="60" cy="60" r="56" />
                <circle class="fg" cx="60" cy="60" r="56" stroke="{{ $color }}"
                    style="stroke-dashoffset:{{ $offset }}" id="progressCircle" />
            </svg>
            <div class="score-center">
                <div class="pct" style="color:{{ $color }}">{{ $pct }}%</div>
                <div class="lbl">SCORE</div>
            </div>
        </div>
        <div class="score-badge" style="background:{{ $gradeBg }};color:{{ $color }}">{!! $grade !!}
        </div>
        <h1 style="font-size:1.5rem;font-weight:800;margin-bottom:.4rem;">{{ $score }} / {{ $total }} Correct
        </h1>
        <p>Here's a detailed breakdown of your answers with explanations.</p>
        <div style="margin-top:1.25rem;display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
            <a href="{{ route('student.subjects') }}" class="btn btn-primary"><i class="bi bi-journal-text"></i> Take
                Another Quiz</a>
        </div>
    </div>

    <div class="results-list">
        @foreach ($results as $i => $r)
            <div class="r-card {{ $r['is_correct'] ? 'correct' : 'wrong' }} animate-in">
                <div class="r-header">
                    <div class="r-icon {{ $r['is_correct'] ? 'c' : 'w' }}"><i
                            class="bi {{ $r['is_correct'] ? 'bi-check-lg' : 'bi-x-lg' }}"></i></div>
                    <div class="r-question">{{ $i + 1 }}. {{ $r['question'] }}</div>
                </div>
                <div class="r-body">
                    <div class="r-opts">
                        @foreach (['A' => $r['option_a'], 'B' => $r['option_b'], 'C' => $r['option_c'], 'D' => $r['option_d']] as $k => $v)
                            <div
                                class="r-opt
                    {{ $k === $r['correct_answer'] ? 'right' : '' }}
                    {{ $k === $r['your_answer'] && !$r['is_correct'] ? 'your' : '' }}">
                                <strong>{{ $k }}.</strong> {{ $v }}
                                @if ($k === $r['correct_answer'])
                                    <i class="bi bi-check-lg"></i>
                                @endif
                                @if ($k === $r['your_answer'] && !$r['is_correct'])
                                    <i class="bi bi-arrow-left"></i> Your answer
                                @endif
                            </div>
                        @endforeach
                    </div>
                    <div class="explanation"><strong><i class="bi bi-lightbulb-fill"></i> Explanation:</strong>
                        {{ $r['explanation'] }}</div>
                </div>
            </div>
        @endforeach
    </div>
@endsection

@push('scripts')
    <script>
        // Animate the score ring on page load
        document.addEventListener('DOMContentLoaded', () => {
            const circle = document.getElementById('progressCircle');
            circle.style.strokeDashoffset = '351.68';
            setTimeout(() => {
                circle.style.strokeDashoffset = '{{ $offset }}';
            }, 100);
        });
    </script>
@endpush
