@extends('layouts.admin')

@section('content')
    <div class="mb-5">
        <h2 class="fw-bold">User Management</h2>
        <p class="text-muted">Manage all teachers and students on the platform.</p>
    </div>

    <ul class="nav nav-tabs mb-4" id="userTabs" role="tablist">
        <li class="nav-item">
            <button class="nav-link active" id="teachers-tab" data-bs-toggle="tab" data-bs-target="#teachers">Teachers</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="students-tab" data-bs-toggle="tab" data-bs-target="#students">Students</button>
        </li>
    </ul>

    <div class="tab-content" id="userTabsContent">
        <!-- Teachers Tab -->
        <div class="tab-pane fade show active" id="teachers">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Name</th>
                                <th>Email</th>
                                <th>Books</th>
                                <th>Joined</th>
                                <th class="pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($teachers as $teacher)
                                <tr>
                                    <td class="ps-4 fw-bold">{{ $teacher->name }}</td>
                                    <td>{{ $teacher->email }}</td>
                                    <td>{{ $teacher->books()->count() ?? 0 }}</td>
                                    <td>{{ $teacher->created_at->format('M d, Y') }}</td>
                                    <td class="pe-4">
                                        <a href="{{ route('admin.users.teachers.edit', $teacher) }}"
                                            class="btn btn-sm btn-outline-primary">Edit</a>
                                        {{-- <button class="btn btn-sm btn-outline-danger">Suspend</button> --}}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-3">{{ $teachers->links() }}</div>
            </div>
        </div>

        <!-- Students Tab -->
        <div class="tab-pane fade" id="students">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Name</th>
                                <th>Standard</th>
                                <th>Gender</th>
                                <th>XP</th>
                                <th>Streak</th>
                                <th class="pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($students as $student)
                                <tr>
                                    <td class="ps-4 fw-bold">{{ $student->name }}</td>
                                    <td>{{ $student->class }} ({{ $student->medium }})</td>
                                    <td>
                                        @if ($student->gender == 'boy')
                                            <span class="badge bg-info-subtle text-info">Boy</span>
                                        @elseif($student->gender == 'girl')
                                            <span class="badge bg-danger-subtle text-danger">Girl</span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary">Hero</span>
                                        @endif
                                    </td>
                                    <td>{{ number_format($student->xp) }}</td>
                                    <td>{{ $student->streak }} days</td>
                                    <td class="pe-4">
                                        <a href="{{ route('admin.users.students.edit', $student) }}"
                                            class="btn btn-sm btn-outline-primary">Edit</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-3">{{ $students->links() }}</div>
            </div>
        </div>
    </div>
@endsection
