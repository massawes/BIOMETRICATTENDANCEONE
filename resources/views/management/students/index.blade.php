@extends('layouts.app')

@section('content')
    <div class="container py-4">
        @include('management.partials.messages')
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <div>
                <h3 class="mb-0">Students Management</h3>
                <p class="text-muted mb-0">Import, export, and manage student records for your department.</p>
            </div>
            <div class="d-flex flex-wrap align-items-center gap-2">
                <button
                    type="button"
                    class="btn btn-outline-success rounded-pill px-3"
                    data-excel-export
                    data-export-url="{{ route('students.export', request()->only('search')) }}"
                    data-export-filename="students-export.xlsx"
                    data-export-sheet="Students"
                >
                    <i class='bx bx-download me-1'></i> Export Excel
                </button>

                <x-import-actions
                    :import-url="route('spreadsheets.import', 'students')"
                    import-entity="students"
                    :template-fields="['student_name', 'admin_number', 'email', 'password', 'intake', 'program_name', 'program_id', 'fingerprint_id']"
                    template-filename="students-template.xlsx"
                    hint="student_name, admin_number, email, password, intake, program_name/program_id, fingerprint_id"
                />

                <a href="{{ route('students.create') }}" class="btn btn-primary rounded-pill px-3">Add Student</a>
            </div>
        </div>
        <form method="GET" action="{{ route('students.index') }}" class="row g-2 mb-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search student, admin number, email, intake, program" value="{{ request('search') }}">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-outline-primary">Search</button>
            </div>
        </form>
        <div class="card shadow-sm">
            <div class="card-body table-responsive">
                <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Admin Number</th>
                            <th>Email</th>
                            <th>Intake</th>
                            <th>Program</th>
                            <th>Fingerprint ID</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($students as $student)
                            <tr>
                                <td>{{ $student->student_name }}</td>
                                <td>{{ $student->admin_number ?? '-' }}</td>
                                <td>{{ $student->user?->email }}</td>
                                <td>{{ $student->intake }}</td>
                                <td>{{ $student->program?->program_name }}</td>
                                <td>{{ $student->fingerprint_id ?? '-' }}</td>
                                <td class="text-end">
                                    <a href="{{ route('students.edit', $student->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('students.destroy', $student->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this student?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No students found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-3">{{ $students->withQueryString()->links() }}</div>
    </div>
@endsection
