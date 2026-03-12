@extends('layouts.teacher')

@section('title', 'AI Generation Monitor')

@section('content')
    <div class="breadcrumb animate-in">
        <a href="{{ route('teacher.dashboard') }}" class="text-decoration-none">Dashboard</a>
        <i class="bi bi-chevron-right"></i>
        <span>AI Monitor</span>
    </div>

    <div class="page-header animate-in">
        <div>
            <h1>AI Generation Monitor</h1>
            <p>Track background processing for your chapters and topics.</p>
        </div>
        @if ($jobs->whereIn('status', ['pending', 'processing'])->count() > 0)
            <div class="text-end">
                <span class="badge bg-light text-dark border">
                    <span class="spinner-grow spinner-grow-sm text-primary me-1" role="status"></span>
                    Auto-refreshing... (10s)
                </span>
            </div>
            <meta http-equiv="refresh" content="10">
        @endif
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden animate-in">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Job ID</th>
                        <th>Book / Chapter</th>
                        <th>Status</th>
                        <th>Tokens</th>
                        <th>Started</th>
                        <th class="pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jobs as $job)
                        <tr>
                            <td class="ps-4 text-muted small">#{{ $job->job_id }}</td>
                            <td>
                                <div class="fw-bold">{{ $job->chapter->book->title ?? 'N/A' }}</div>
                                <div class="small text-muted">{{ $job->chapter->title ?? 'N/A' }}</div>
                            </td>
                            <td>
                                @php
                                    $statusClass = match ($job->status) {
                                        'pending' => 'bg-secondary',
                                        'processing' => 'bg-primary',
                                        'completed' => 'bg-success',
                                        'failed' => 'bg-danger',
                                        default => 'bg-secondary',
                                    };
                                @endphp
                                <span class="badge {{ $statusClass }}">
                                    @if ($job->status == 'processing')
                                        <span class="spinner-border spinner-border-sm me-1" role="status"></span>
                                    @endif
                                    {{ ucfirst($job->status) }}
                                </span>
                            </td>
                            <td>{{ number_format($job->tokens_used) }}</td>
                            <td class="small">{{ $job->started_at?->diffForHumans() ?? 'Not started' }}</td>
                            <td class="pe-4">
                                @if ($job->status == 'completed')
                                    <a href="{{ route('teacher.chapter.show', $job->chapter_id) }}?job_id={{ $job->job_id }}"
                                        class="btn btn-sm btn-ghost border text-primary fw-bold">View Result</a>
                                @elseif($job->status == 'failed')
                                    <button class="btn btn-sm btn-outline-danger" title="{{ $job->errors }}">View
                                        Error</button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="text-muted mb-2"><i class="bi bi-cpu display-4"></i></div>
                                <h6>No AI jobs found</h6>
                                <p class="small">Add a chapter to trigger AI generation.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($jobs->hasPages())
            <div class="card-footer bg-white py-3">
                {{ $jobs->links() }}
            </div>
        @endif
    </div>
@endsection
