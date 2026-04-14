@extends('layouts.app')

@section('content')
<div class="container-fluid py-3">
    <div class="d-flex flex-wrap justify-content-between align-items-end gap-2 mb-3">
        <div>
            <div class="text-uppercase text-muted small fw-semibold mb-1">Quality Control</div>
            <h4 class="fw-bold mb-1 text-dark">Quality Assurance</h4>
            <p class="text-muted mb-0">Short view of module coverage and flagged issues.</p>
        </div>
        <span class="badge rounded-pill text-bg-primary px-3 py-2">
            {{ $pendingReviews }} pending
        </span>
    </div>

    <div class="row g-2 mb-3">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-3">
                    <div class="text-muted small text-uppercase fw-semibold mb-2">Modules</div>
                    <div class="fs-3 fw-bold text-dark">{{ $totalModules }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-3">
                    <div class="text-muted small text-uppercase fw-semibold mb-2">Coverage</div>
                    <div class="fs-3 fw-bold text-dark">{{ $coverageRate }}%</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-3">
                    <div class="text-muted small text-uppercase fw-semibold mb-2">Pending</div>
                    <div class="fs-3 fw-bold text-dark">{{ $pendingReviews }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-3">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-2">
                <div>
                    <h6 class="fw-bold mb-1 text-dark">Modules Needing Attention</h6>
                    <p class="text-muted mb-0">Low attendance modules and timetable gaps.</p>
                </div>
                <span class="badge text-bg-dark rounded-pill px-3 py-2">{{ $modulesWithoutTimetables }} gaps</span>
            </div>

            <div class="d-grid gap-2">
                @forelse($lowAttendanceModules as $module)
                    <div class="d-flex justify-content-between align-items-center border rounded-3 px-3 py-2">
                        <div>
                            <div class="fw-semibold text-dark">{{ $module->module_name }}</div>
                            <div class="text-muted small">{{ $module->program_name ?? 'General' }} • {{ $module->total_records }} records</div>
                        </div>
                        <span class="badge rounded-pill text-bg-danger px-3 py-2">{{ $module->attendance_rate }}%</span>
                    </div>
                @empty
                    <div class="text-center py-3 text-muted">No modules currently flagged.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
