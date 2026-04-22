@extends('layouts.app')

@section('content')
<div class="container-fluid py-3">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div>
            <div class="text-uppercase text-muted small fw-semibold mb-1">Academic Leadership</div>
            <h4 class="fw-bold mb-0 text-dark">Head of Department</h4>
        </div>
        <span class="badge rounded-pill text-bg-primary px-3 py-2">{{ $department->department_name ?? 'Department' }}</span>
    </div>

    <div class="row g-2 mb-3">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-3">
                    <div class="text-muted small text-uppercase fw-semibold mb-1">Lecturers</div>
                    <div class="fs-3 fw-bold text-dark">{{ $lecturersCount }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-3">
                    <div class="text-muted small text-uppercase fw-semibold mb-1">Modules</div>
                    <div class="fs-3 fw-bold text-dark">{{ $modulesCount }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-3">
                    <div class="text-muted small text-uppercase fw-semibold mb-1">Programs</div>
                    <div class="fs-3 fw-bold text-dark">{{ $programsCount }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-3">
                    <div class="text-muted small text-uppercase fw-semibold mb-1">Students</div>
                    <div class="fs-3 fw-bold text-dark">{{ $studentsCount }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-3">
        <div class="card-body p-3">
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('hodreport') }}" class="btn btn-dark btn-sm rounded-pill px-3">Module Report</a>
                <a href="{{ route('hod.analysis') }}" class="btn btn-outline-dark btn-sm rounded-pill px-3">Attendance Analysis</a>
                <a href="{{ route('lecturers.index') }}" class="btn btn-outline-dark btn-sm rounded-pill px-3">Lecturers</a>
                <a href="{{ route('modules.index') }}" class="btn btn-outline-dark btn-sm rounded-pill px-3">Modules</a>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-3">
            <div class="d-flex justify-content-between align-items-center gap-2 mb-2">
                <div>
                    <h6 class="fw-bold mb-1 text-dark">Recent Assignments</h6>
                    <p class="text-muted mb-0">Latest module allocations in your department.</p>
                </div>
                <span class="badge text-bg-primary rounded-pill px-3 py-2">{{ $moduleDistributions->count() }} shown</span>
            </div>

            <div class="d-grid gap-2">
                @forelse($moduleDistributions as $distribution)
                    <div class="d-flex justify-content-between align-items-center border rounded-3 px-3 py-2">
                        <div>
                            <div class="fw-semibold text-dark">{{ $distribution->module_name }}</div>
                            <div class="text-muted small">{{ $distribution->program_name }} • {{ $distribution->lecturer_name }}</div>
                        </div>
                        <span class="badge rounded-pill text-bg-secondary px-3 py-2">NTA {{ $distribution->nta_level }}</span>
                    </div>
                @empty
                    <div class="text-center py-3 text-muted">No assignments yet.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
