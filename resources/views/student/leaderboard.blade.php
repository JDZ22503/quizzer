@extends('layouts.student')

@section('title', 'Leaderboard')

@section('content')
    <div class="page-header animate-in mt-4">
        <h1><i class="bi bi-trophy text-warning"></i> Standard {{ $student->class }} Leaderboard</h1>
        <p>See how you stack up against the best learners in your grade.</p>
    </div>

    <div class="row animate-in">
        <div class="col-lg-8 mx-auto">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Rank</th>
                                <th>Student</th>
                                <th>Level</th>
                                <th class="pe-4 text-end">Total XP</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($topStudents as $topStudent)
                                <tr class="{{ auth()->guard('student')->id() == $topStudent->id ? 'table-primary' : '' }}">
                                    <td class="ps-4">
                                        @if ($loop->index == 0)
                                            <span class="badge bg-warning text-dark"><i class="bi bi-award-fill"></i>
                                                1st</span>
                                        @elseif($loop->index == 1)
                                            <span class="badge bg-secondary">2nd</span>
                                        @elseif($loop->index == 2)
                                            <span class="badge bg-bronze"
                                                style="background: #cd7f32; color: white;">3rd</span>
                                        @else
                                            <span class="fw-bold">{{ $loop->iteration }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($topStudent->name) }}&background=random"
                                                class="rounded-circle me-3" width="32">
                                            <div>
                                                <div class="fw-bold">{{ $topStudent->name }}</div>
                                                <div class="small text-muted">{{ $topStudent->class }} Standard</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                            $leagueColor = match ($topStudent->league) {
                                                'bronze' => '#cd7f32',
                                                'silver' => '#a8a8a8',
                                                'diamond' => '#70d1f4',
                                                'champion' => '#ffd700',
                                                default => '#6c757d',
                                            };
                                        @endphp
                                        <span class="badge"
                                            style="background: {{ $leagueColor }}; color: {{ $topStudent->league == 'champion' || $topStudent->league == 'diamond' ? '#1a1a1a' : 'white' }}">
                                            <i class="bi bi-trophy-fill me-1"></i>
                                            {{ ucfirst($topStudent->league) }} {{ $topStudent->league_level }}
                                        </span>
                                    </td>
                                    <td class="pe-4 text-end fw-bold">{{ number_format($topStudent->xp) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
