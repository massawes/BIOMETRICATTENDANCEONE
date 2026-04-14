@extends('layouts.app')

@section('content')
<div class="container-fluid py-3 exec-shell">
    <div class="card exec-hero mb-3">
        <div class="card-body p-3 p-lg-4">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div>
                    <span class="exec-kicker mb-2">
                        <i class='bx bx-check-shield'></i>
                        Examination & Compliance
                    </span>
                    <h1 class="exec-title mb-1" style="font-size: 2rem;">Examination Officer Dashboard</h1>
                    <p class="exec-copy mb-0" style="max-width: 42rem;">
                        Simple overview of exam eligibility and attendance.
                    </p>
                </div>

                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('exam.eligibility') }}" class="btn btn-dark rounded-pill fw-bold">
                        <i class='bx bx-list-check me-2'></i> Eligibility
                    </a>
                    <a href="{{ route('exam.reports') }}" class="btn btn-outline-dark rounded-pill fw-bold">
                        Reports
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-3 row-cols-1 row-cols-md-2 row-cols-xl-4">
        <div class="col">
            <div class="card exec-stat h-100">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <span class="stat-icon"><i class='bx bx-group'></i></span>
                    <div>
                        <div class="text-uppercase small fw-bold text-muted">Students</div>
                        <div class="exec-metric text-dark" style="font-size: 1.4rem;">{{ $totalStudents }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card exec-stat h-100">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <span class="stat-icon is-success"><i class='bx bx-book-content'></i></span>
                    <div>
                        <div class="text-uppercase small fw-bold text-muted">Modules</div>
                        <div class="exec-metric text-dark" style="font-size: 1.4rem;">{{ $totalModules }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card exec-stat h-100">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <span class="stat-icon is-warning"><i class='bx bx-line-chart'></i></span>
                    <div>
                        <div class="text-uppercase small fw-bold text-muted">Attendance</div>
                        <div class="exec-metric text-dark" style="font-size: 1.4rem;">{{ $attendanceRate }}%</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card exec-stat h-100">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <span class="stat-icon is-danger"><i class='bx bx-error-circle'></i></span>
                    <div>
                        <div class="text-uppercase small fw-bold text-muted">At Risk</div>
                        <div class="exec-metric text-dark" style="font-size: 1.4rem;">{{ $studentRiskCount }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card exec-card h-100">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h5 class="exec-section-title mb-1">Eligibility Snapshot</h5>
                            <div class="exec-muted">Summary of exam clearance status.</div>
                        </div>
                        <span class="badge text-bg-primary exec-badge">75% rule</span>
                    </div>

                    <div class="row g-2 row-cols-1 row-cols-sm-3">
                        <div class="col">
                            <div class="exec-soft-card p-3">
                                <div class="small text-muted text-uppercase fw-bold">Cleared</div>
                                <div class="fw-bold text-dark" style="font-size: 1.35rem;">{{ $clearedCount }}</div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="exec-soft-card p-3">
                                <div class="small text-muted text-uppercase fw-bold">Not Cleared</div>
                                <div class="fw-bold text-dark" style="font-size: 1.35rem;">{{ $notClearedCount }}</div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="exec-soft-card p-3">
                                <div class="small text-muted text-uppercase fw-bold">System Rate</div>
                                <div class="fw-bold text-dark" style="font-size: 1.35rem;">{{ $attendanceRate }}%</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card exec-card h-100">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h5 class="exec-section-title mb-1">Immediate Focus</h5>
                            <div class="exec-muted">Short watchlist.</div>
                        </div>
                        <span class="badge text-bg-dark exec-badge">{{ $lowAttendanceModules->count() }}</span>
                    </div>

                    <div class="d-grid gap-2">
                        @forelse($lowAttendanceModules->take(3) as $module)
                            <div class="exec-soft-card p-2 px-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="fw-semibold text-dark">{{ $module->module_name }}</div>
                                        <div class="small text-muted">{{ $module->program_name ?? 'General' }}</div>
                                    </div>
                                    <span class="badge rounded-pill {{ $module->attendance_rate >= 75 ? 'text-bg-success' : 'text-bg-danger' }}">
                                        {{ $module->attendance_rate }}%
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="exec-empty p-4 text-center">
                                <div class="fw-semibold text-dark">No risk items</div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
