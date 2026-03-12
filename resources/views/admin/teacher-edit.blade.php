@extends('layouts.admin')

@section('content')
    <div class="mb-5">
        <h2 class="fw-bold">Edit Teacher</h2>
        <p class="text-muted">Update teacher profile and account details.</p>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <form action="{{ route('admin.users.teachers.update', $teacher) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="form-label fw-bold">Full Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $teacher->name) }}"
                            required>
                        @error('name')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Email Address</label>
                        <input type="email" name="email" class="form-control"
                            value="{{ old('email', $teacher->email) }}" required>
                        @error('email')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary px-4">Update Teacher</button>
                        <a href="{{ route('admin.users') }}" class="btn btn-ghost border">Cancel</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <h5 class="fw-bold mb-3">Teacher Stats</h5>
                <div class="mb-3">
                    <label class="text-muted small d-block">Books Created</label>
                    <span class="fw-bold fs-5 text-primary">{{ $teacher->books()->count() }}</span>
                </div>
                <hr>
                <div class="small">
                    Joined on {{ $teacher->created_at->format('M d, Y') }}
                </div>
            </div>
        </div>
    </div>
@endsection
