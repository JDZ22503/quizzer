@extends('layouts.admin')

@section('content')
    <div class="mb-5">
        <h2 class="fw-bold">League Management</h2>
        <p class="text-muted">Define the EXP thresholds for each league and level.</p>
    </div>

    <form action="{{ route('admin.league.settings.update') }}" method="POST">
        @csrf
        <div class="row g-4">
            @foreach ($leagues as $league)
                <div class="col-md-6 col-lg-3">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-header bg-dark text-white rounded-top-4 py-3">
                            <h5 class="mb-0 fw-bold"><i class="bi bi-trophy-fill me-2"></i> {{ ucfirst($league) }}</h5>
                        </div>
                        <div class="card-body p-4">
                            @foreach ($levels as $level)
                                @php $key = "league_{$league}_{$level}_xp"; @endphp
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-muted">Level {{ $level }} Threshold
                                        (XP)</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0"><i
                                                class="bi bi-lightning-fill text-warning"></i></span>
                                        <input type="number" name="thresholds[{{ $key }}]"
                                            value="{{ $thresholds[$key] ?? 0 }}" class="form-control border-0 bg-light"
                                            required min="0">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-5 mb-5">
            <button type="submit" class="btn btn-primary btn-lg px-5 rounded-pill shadow-sm">
                <i class="bi bi-save2 me-2"></i> Save League Settings
            </button>
        </div>
    </form>
@endsection
