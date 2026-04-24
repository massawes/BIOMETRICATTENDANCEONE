@extends('layouts.app')

@section('content')
<div class="container-fluid py-3">
    <div class="card exec-hero mb-3">
        <div class="card-body p-3 p-lg-4">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div>
                    <span class="exec-kicker mb-2">
                        <i class='bx bx-file-blank'></i>
                        Exam Reports
                    </span>
                    <h1 class="exec-title mb-1" style="font-size: 1.9rem;">Attendance Analysis Reports</h1>
                    <p class="exec-copy mb-0" style="max-width: 44rem;">
                        Simple program summary for exam planning.
                    </p>
                </div>
                <div class="d-flex flex-wrap align-items-center gap-2 no-print">
                    <a href="{{ route('examdashboard') }}" class="btn btn-outline-dark rounded-pill fw-bold">
                        <i class='bx bx-arrow-back me-2'></i> Dashboard
                    </a>
                    <x-report-actions
                        :export-url="request()->fullUrlWithQuery(['export' => 1])"
                        export-filename="exam-reports.xlsx"
                        export-sheet="Exam Reports"
                    />
                </div>
            </div>
        </div>
    </div>

    <div class="printable-area">
        <div class="row g-3 mb-3 row-cols-1 row-cols-md-3">
            <div class="col">
                <div class="card exec-stat h-100">
                    <div class="card-body p-3">
                        <div class="small text-muted text-uppercase fw-bold">Programs</div>
                        <div class="exec-metric text-dark" style="font-size: 1.6rem;">{{ $programStats->total() }}</div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card exec-stat h-100" data-print-hide>
                    <div class="card-body p-3">
                        <div class="small text-muted text-uppercase fw-bold">Current Page</div>
                        <div class="exec-metric text-dark" style="font-size: 1.6rem;">{{ $programStats->currentPage() }}</div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card exec-stat h-100">
                    <div class="card-body p-3">
                        <div class="small text-muted text-uppercase fw-bold">Avg Attendance</div>
                        <div class="exec-metric text-dark" style="font-size: 1.6rem;">
                            {{ round($programStats->getCollection()->avg('attendance_rate') ?? 0, 1) }}%
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card exec-card">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="exec-section-title mb-1">Program Summary</h5>
                        <div class="exec-muted">Attendance by program.</div>
                    </div>
                    <span class="badge text-bg-primary exec-badge">{{ $programStats->total() }} records</span>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle exec-table mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Program</th>
                                <th class="text-center">Students</th>
                                <th class="text-center">Records</th>
                                <th class="text-center">Presence</th>
                                <th class="text-center">Attendance</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($programStats as $stat)
                                <tr>
                                    <td class="fw-semibold text-dark">{{ $stat->program_name }}</td>
                                    <td class="text-center">{{ $stat->total_students }}</td>
                                    <td class="text-center">{{ $stat->total_records }}</td>
                                    <td class="text-center text-success fw-bold">{{ $stat->total_present }}</td>
                                    <td class="text-center">
                                        <span class="badge rounded-pill {{ $stat->attendance_rate >= 75 ? 'text-bg-success' : 'text-bg-warning' }}">
                                            {{ $stat->attendance_rate }}%
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <div class="text-muted">No program reports yet.</div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-end mt-3 no-print">
                    {{ $programStats->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
