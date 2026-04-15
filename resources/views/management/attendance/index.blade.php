@extends('layouts.app')

@section('content')
    <div class="container py-4">
        @include('management.partials.messages')
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h3 class="mb-0">Manual Attendance Records</h3>
                <small class="text-muted">Manage lecturer attendance records from this page.</small>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('attendance.records.create') }}" class="btn btn-primary">Add Attendance</a>
            </div>
        </div>
        <form method="GET" action="{{ route('attendance.records.index') }}" class="row g-2 mb-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search student, module, year, date" value="{{ request('search') }}">
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
                            <th>Student</th>
                            <th>Admin Number</th>
                            <th>Module</th>
                            <th>Academic Year</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($attendances as $attendance)
                            <tr>
                                <td>{{ $attendance->student?->student_name }}</td>
                                <td>{{ $attendance->student?->admin_number ?? '-' }}</td>
                                <td>{{ $attendance->moduleDistribution?->module?->module_name }}</td>
                                <td>{{ $attendance->academic_year }}</td>
                                <td>{{ $attendance->date }}</td>
                                <td>
                                    <span class="badge bg-{{ $attendance->is_present ? 'success' : 'danger' }}">
                                        {{ $attendance->is_present ? 'Present' : 'Absent' }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('attendance.records.edit', $attendance->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('attendance.records.destroy', $attendance->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this attendance record?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No manual attendance records found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-3">{{ $attendances->withQueryString()->links() }}</div>
    </div>
@endsection
