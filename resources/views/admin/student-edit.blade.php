@extends('layouts.admin')

@section('content')
    <div class="mb-5">
        <h2 class="fw-bold">Edit Student</h2>
        <p class="text-muted">Update student profile and academic details.</p>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <form action="{{ route('admin.users.students.update', $student) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="form-label fw-bold">Full Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $student->name) }}"
                            required>
                        @error('name')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Email Address</label>
                        <input type="email" name="email" class="form-control"
                            value="{{ old('email', $student->email) }}" required>
                        @error('email')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Class / Standard</label>
                            <select name="class" class="form-select" required>
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}"
                                        {{ old('class', $student->class) == $i ? 'selected' : '' }}>Standard
                                        {{ $i }}</option>
                                @endfor
                            </select>
                            @error('class')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Medium</label>
                            <select name="medium" class="form-select" required>
                                <option value="english"
                                    {{ old('medium', $student->medium) == 'english' ? 'selected' : '' }}>English</option>
                                <option value="gujarati"
                                    {{ old('medium', $student->medium) == 'gujarati' ? 'selected' : '' }}>Gujarati</option>
                                <option value="hindi" {{ old('medium', $student->medium) == 'hindi' ? 'selected' : '' }}>
                                    Hindi</option>
                            </select>
                            @error('medium')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Gender</label>
                            <select name="gender" class="form-select" required>
                                <option value="boy" {{ old('gender', $student->gender) == 'boy' ? 'selected' : '' }}>Boy
                                </option>
                                <option value="girl" {{ old('gender', $student->gender) == 'girl' ? 'selected' : '' }}>
                                    Girl</option>
                                <option value="other" {{ old('gender', $student->gender) == 'other' ? 'selected' : '' }}>
                                    Hero</option>
                            </select>
                            @error('gender')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary px-4">Update Student</button>
                        <a href="{{ route('admin.users') }}" class="btn btn-ghost border">Cancel</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <h5 class="fw-bold mb-3">Quick Stats</h5>
                <div class="mb-3">
                    <label class="text-muted small d-block">Total XP</label>
                    <span class="fw-bold fs-5 text-primary">{{ number_format($student->xp) }}</span>
                </div>
                <div class="mb-3">
                    <label class="text-muted small d-block">Current Streak</label>
                    <span class="fw-bold fs-5 text-success">{{ $student->streak }} Days</span>
                </div>
                <hr>
                <div class="small">
                    Joined on {{ $student->created_at->format('M d, Y') }}
                </div>
            </div>
        </div>
    </div>
@endsection
