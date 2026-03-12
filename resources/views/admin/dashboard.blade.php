@extends('layouts.admin')

@section('content')
    <div class="mb-5">
        <h2 class="fw-bold">Platform Overview</h2>
        <p class="text-muted">Real-time statistics for your learning platform.</p>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="text-muted small fw-bold text-uppercase mb-2">Total Users</div>
                <div class="h3 fw-bold mb-0">{{ $stats['total_users'] }}</div>
                <div class="mt-2 small text-primary"><i class="bi bi-arrow-up"></i> 12% increase</div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card border-start border-primary border-4">
                <div class="text-muted small fw-bold text-uppercase mb-2">Teachers</div>
                <div class="h3 fw-bold mb-0">{{ $stats['total_teachers'] }}</div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card border-start border-success border-4">
                <div class="text-muted small fw-bold text-uppercase mb-2">Students</div>
                <div class="h3 fw-bold mb-0">{{ $stats['total_students'] }}</div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card border-start border-warning border-4">
                <div class="text-muted small fw-bold text-uppercase mb-2">Total Tokens</div>
                <div class="h3 fw-bold mb-0">Coming Soon</div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <h5 class="fw-bold mb-4">Active AI Generation Jobs</h5>
                <div class="table-responsive">
                    <table class="table table-sm table-hover align-middle">
                        <thead class="bg-light small">
                            <tr>
                                <th>Teacher</th>
                                <th>Chapter</th>
                                <th>Status</th>
                                <th>Started</th>
                            </tr>
                        </thead>
                        <tbody class="small">
                            @forelse($recentJobs as $job)
                                <tr>
                                    <td>{{ $job->teacher->name ?? 'N/A' }}</td>
                                    <td>{{ mb_substr($job->chapter->title ?? 'N/A', 0, 15) }}...</td>
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
                                        <span class="badge {{ $statusClass }}" style="font-size: 0.7rem;">
                                            {{ ucfirst($job->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $job->created_at->diffForHumans() }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-3 text-muted">No recent jobs found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    <a href="{{ route('admin.ai-jobs') }}" class="btn btn-outline-primary btn-sm w-100">View Full Job
                        Queue</a>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                <h5 class="fw-bold mb-4">Quick Actions</h5>
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.users') }}" class="btn btn-light border">Manage Users</a>
                    <a href="{{ route('admin.settings') }}" class="btn btn-light border">System Configuration</a>
                </div>
            </div>
        </div>
    </div>
@endsection
