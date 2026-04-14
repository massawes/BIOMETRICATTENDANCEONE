@extends('layouts.app')

@section('content')
<div class="container-fluid py-3">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div>
            <div class="text-uppercase text-muted small fw-semibold mb-1">Lecturer Workspace</div>
            <h4 class="fw-bold mb-0 text-dark">Welcome back, {{ auth()->user()->name }}</h4>
            <p class="text-muted mb-0">Manage classes and attendance from one clean view.</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('attendanceindex') }}" class="btn btn-dark btn-sm rounded-pill px-3">
                Manual Attendance
            </a>
        </div>
    </div>

    <div class="row g-2 mb-3">
        <div class="col-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-3">
                    <div class="text-muted small text-uppercase fw-semibold mb-1">Modules</div>
                    <div class="fs-3 fw-bold text-dark">{{ $totalModules }}</div>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-3">
                    <div class="text-muted small text-uppercase fw-semibold mb-1">Classes</div>
                    <div class="fs-3 fw-bold text-dark">{{ $totalClasses }}</div>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-3">
                    <div class="text-muted small text-uppercase fw-semibold mb-1">Students</div>
                    <div class="fs-3 fw-bold text-dark">{{ $totalStudents }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-3">
        <div class="card-body p-3">
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('lecturerireport') }}" class="btn btn-outline-dark btn-sm rounded-pill px-3">Analysis</a>
                <a href="{{ route('lecturerclasstiming') }}" class="btn btn-outline-dark btn-sm rounded-pill px-3">Timetable</a>
                <a href="{{ route('attendanceindex') }}" class="btn btn-outline-dark btn-sm rounded-pill px-3">Manual Attendance</a>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-3">
            <div class="d-flex justify-content-between align-items-center gap-2 mb-2">
                <div>
                    <h6 class="fw-bold mb-1 text-dark">Teaching Snapshot</h6>
                    <p class="text-muted mb-0">Your assigned modules at a glance.</p>
                </div>
                <span class="badge text-bg-primary rounded-pill px-3 py-2">{{ $recentModules->count() }} shown</span>
            </div>

            <div class="d-grid gap-2">
                @forelse ($recentModules as $dist)
                    <div class="d-flex justify-content-between align-items-center border rounded-3 px-3 py-2">
                        <div>
                            <div class="fw-semibold text-dark">{{ $dist->module->module_name ?? 'N/A' }}</div>
                            <div class="text-muted small">{{ $dist->module->program->program_name ?? 'N/A' }}</div>
                        </div>
                        <span class="badge rounded-pill text-bg-primary px-3 py-2">
                            NTA {{ $dist->module->nta_level ?? 'N/A' }}
                        </span>
                    </div>
                @empty
                    <div class="text-center py-3 text-muted">No modules assigned yet.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
