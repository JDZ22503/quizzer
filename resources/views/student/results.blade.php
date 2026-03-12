@extends('layouts.student')
@section('title', 'Quiz Results')

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
