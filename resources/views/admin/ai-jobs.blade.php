@extends('layouts.admin')

@section('content')
    <div class="mb-5">
        <h2 class="fw-bold">Global AI Job Monitor</h2>
        <p class="text-muted">Monitor all background processing tasks across the platform.</p>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Teacher</th>
                        <th>Book / Chapter</th>
                        <th>Status</th>
                        <th>Tokens</th>
                        <th>Runtime</th>
                        <th class="pe-4">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($jobs as $job)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold">{{ $job->teacher->name }}</div>
                                <div class="small text-muted">{{ $job->teacher->email }}</div>
                            </td>
                            <td>
                                <div class="small fw-bold">{{ $job->chapter->book->title ?? 'N/A' }}</div>
                                <div class="small text-muted">{{ $job->chapter->title ?? 'N/A' }}</div>
                            </td>
                            <td>
                                <span
                                    class="badge {{ $job->status == 'completed' ? 'bg-success' : ($job->status == 'failed' ? 'bg-danger' : 'bg-primary') }}">
                                    {{ ucfirst($job->status) }}
                                </span>
                            </td>
                            <td>{{ number_format($job->tokens_used) }}</td>
                            <td>
                                @if ($job->completed_at && $job->started_at)
                                    {{ $job->completed_at->diffInSeconds($job->started_at) }}s
                                @else
                                    -
                                @endif
                            </td>
                            <td class="pe-4 small text-muted">{{ $job->created_at->format('M d, H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
