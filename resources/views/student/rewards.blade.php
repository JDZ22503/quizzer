@extends('layouts.student')

@section('title', 'Your Rewards')

@section('content')
    <div class="page-header animate-in mt-4">
        <h1><i class="bi bi-medal text-primary"></i> Badges & Rewards</h1>
        <p>Complete challenges and level up to unlock special badges.</p>
    </div>

    <div class="row g-4 animate-in">
        {{-- Badges now passed from Controller --}}

        @foreach ($badges as $badge)
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-4 p-4 text-center {{ $badge['locked'] ? 'opacity-50' : '' }}">
                    <div class="mb-3">
                        <i class="bi {{ $badge['icon'] }} display-4 text-{{ $badge['color'] }}"></i>
                    </div>
                    <h5 class="fw-bold">{{ $badge['name'] }}</h5>
                    <p class="small text-muted mb-0">{{ $badge['desc'] }}</p>
                    @if ($badge['locked'])
                        <div class="mt-2 small text-danger"><i class="bi bi-lock-fill"></i> Locked</div>
                    @else
                        <div class="mt-2 small text-success"><i class="bi bi-check-circle-fill"></i> Unlocked</div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@endsection
