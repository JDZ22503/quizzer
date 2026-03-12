@extends('layouts.admin')

@section('content')
    <div class="mb-5 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-4">
        <div>
            <h2 class="fw-bold">Platform Analytics</h2>
            <p class="text-muted">Global performance and growth metrics.</p>
        </div>
        <div class="bg-white p-3 rounded-4 shadow-sm border-0" style="min-width: 300px; max-width: 100%;">
            <form action="{{ route('admin.analytics') }}" method="GET" class="row g-2 align-items-center">
                <div class="col">
                    <label class="small text-muted mb-1 d-block">Filter by Standard</label>
                    <select name="standard" class="form-select border-0 bg-light" onchange="this.form.submit()">
                        <option value="">All Standards</option>
                        @foreach ($standards as $std)
                            <option value="{{ $std }}" {{ $selectedStandard == $std ? 'selected' : '' }}>
                                Standard {{ $std }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="fw-bold mb-0">Content Breakdown by Standard & Subject</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Standard</th>
                                    <th>Subject</th>
                                    <th class="text-center">Total Books</th>
                                    <th class="text-center">Total Chapters</th>
                                    <th class="text-center">Total Questions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($analytics as $item)
                                    <tr>
                                        <td class="ps-4">
                                            <span class="badge bg-primary text-white rounded-pill px-3">Standard
                                                {{ $item->standard }}</span>
                                        </td>
                                        <td class="fw-medium text-dark">{{ $item->name }}</td>
                                        <td class="text-center">{{ number_format($item->total_books) }}</td>
                                        <td class="text-center">{{ number_format($item->total_chapters) }}</td>
                                        <td class="text-center">
                                            <span
                                                class="fw-bold text-accent">{{ number_format($item->total_questions) }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">
                                            <i class="bi bi-bar-chart d-block mb-2 fs-1 opacity-25"></i>
                                            No analytics data available yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
