@extends('layouts.app')

@section('content')
<div class="container-fluid py-3">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div>
            <div class="text-uppercase text-muted small fw-semibold mb-1">Student Workspace</div>
            <h4 class="fw-bold mb-0 text-dark">Welcome back, {{ $student->student_name ?? 'Student' }}</h4>
            <p class="text-muted mb-0">{{ $programName ?? 'Your program' }} @if($departmentName) | {{ $departmentName }} @endif</p>
        </div>
        <span class="badge rounded-pill {{ $attendanceRate >= 75 ? 'text-bg-success' : 'text-bg-warning' }} px-3 py-2">
            {{ $attendanceStatus }}
        </span>
    </div>

    <div class="row g-2 mb-3">
        <div class="col-6 col-md-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-3">
                    <div class="text-muted small text-uppercase fw-semibold mb-1">Attendance</div>
                    <div class="fs-3 fw-bold text-dark">{{ $attendanceRate }}%</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-3">
                    <div class="text-muted small text-uppercase fw-semibold mb-1">Modules</div>
                    <div class="fs-3 fw-bold text-dark">{{ $totalModules }}</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-3">
                    <div class="text-muted small text-uppercase fw-semibold mb-1">Sessions</div>
                    <div class="fs-3 fw-bold text-dark">{{ $totalRecords }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-3">
        <div class="card-body p-3">
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('studentmodules') }}" class="btn btn-dark btn-sm rounded-pill px-3">My Modules</a>
                <a href="{{ route('studenttimetable') }}" class="btn btn-outline-dark btn-sm rounded-pill px-3">Timetable</a>
                <a href="{{ route('profile.edit') }}" class="btn btn-outline-dark btn-sm rounded-pill px-3">Profile</a>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-3">
            <div class="d-flex justify-content-between align-items-center gap-2 mb-2">
                <div>
                    <h6 class="fw-bold mb-1 text-dark">Recent Attendance</h6>
                    <p class="text-muted mb-0">Latest records from your sessions.</p>
                </div>
                <span class="badge text-bg-primary rounded-pill px-3 py-2">{{ $recentAttendance->count() }} shown</span>
            </div>

            <div class="d-grid gap-2">
                @forelse($recentAttendance as $attendance)
                    <div class="d-flex justify-content-between align-items-center border rounded-3 px-3 py-2">
                        <div>
                            <div class="fw-semibold text-dark">{{ $attendance->module_name }}</div>
                            <div class="text-muted small">{{ $attendance->date }}</div>
                        </div>
                        <span class="badge rounded-pill {{ $attendance->is_present ? 'text-bg-success' : 'text-bg-danger' }} px-3 py-2">
                            {{ $attendance->is_present ? 'Present' : 'Absent' }}
                        </span>
                    </div>
                @empty
                    <div class="text-center py-3 text-muted">No attendance records found.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
