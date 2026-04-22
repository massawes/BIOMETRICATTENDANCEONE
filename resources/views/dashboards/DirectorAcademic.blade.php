@extends('layouts.app')

@section('content')
<div class="container-fluid py-3">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div>
            <div class="text-uppercase text-muted small fw-semibold mb-1">Academic Leadership</div>
            <h4 class="fw-bold mb-0 text-dark">Director Academic</h4>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('director.faculties') }}" class="btn btn-outline-dark btn-sm rounded-pill px-3">Faculties</a>
            <a href="{{ route('hods.index') }}" class="btn btn-dark btn-sm rounded-pill px-3">HODs</a>
        </div>
    </div>

    <div class="row g-2 mb-3">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-3">
                    <div class="text-muted small text-uppercase fw-semibold mb-1">Departments</div>
                    <div class="fs-3 fw-bold text-dark">{{ $totalDepartments }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-3">
                    <div class="text-muted small text-uppercase fw-semibold mb-1">Programs</div>
                    <div class="fs-3 fw-bold text-dark">{{ $totalPrograms }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-3">
                    <div class="text-muted small text-uppercase fw-semibold mb-1">Students</div>
                    <div class="fs-3 fw-bold text-dark">{{ $totalStudents }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-3">
                    <div class="text-muted small text-uppercase fw-semibold mb-1">Attendance</div>
                    <div class="fs-3 fw-bold text-dark">{{ $overallAttendanceRate }}%</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-3">
            <div class="d-flex justify-content-between align-items-center gap-2 mb-2">
                <div>
                    <h6 class="fw-bold mb-1 text-dark">Top Departments</h6>
                    <p class="text-muted mb-0">Three departments by attendance.</p>
                </div>
                <span class="badge text-bg-primary rounded-pill px-3 py-2">3 shown</span>
            </div>

            <div class="d-grid gap-2">
                @forelse($departmentPerformance as $department)
                    <div class="d-flex justify-content-between align-items-center border rounded-3 px-3 py-2">
                        <div>
                            <div class="fw-semibold text-dark">{{ $department->department_name }}</div>
                            <div class="text-muted small">{{ $department->total_programs }} programs • {{ $department->total_students }} students</div>
                        </div>
                        <span class="badge rounded-pill {{ $department->attendance_rate >= 75 ? 'text-bg-success' : 'text-bg-danger' }} px-3 py-2">
                            {{ $department->attendance_rate }}%
                        </span>
                    </div>
                @empty
                    <div class="text-center py-3 text-muted">No department data available.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
