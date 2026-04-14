@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-3">
        <div>
            <h3 class="fw-bold mb-1 text-dark">Timetable</h3>
            <p class="text-muted mb-0">Live schedule pulled from the class_timings table in the database.</p>
        </div>
        <div class="d-flex flex-wrap align-items-center gap-2 no-print">
            <span class="badge rounded-pill text-bg-primary px-3 py-2">
                {{ $examData->total() }} records
            </span>
            <button type="button" class="btn btn-outline-dark btn-sm rounded-pill px-3" data-report-print>
                <i class='bx bx-printer me-1'></i> Print
            </button>
        </div>
    </div>

    <div class="printable-area">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Subject</th>
                            <th>Program</th>
                            <th>Week</th>
                            <th>Day</th>
                            <th>Time</th>
                            <th>Room</th>
                            <th>Lecturer</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($examData as $exam)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-semibold text-dark">{{ $exam->subject ?? $exam->moduleDistribution?->module?->module_name ?? 'N/A' }}</div>
                                </td>
                                <td class="text-muted">{{ $exam->moduleDistribution?->module?->program?->program_name ?? 'N/A' }}</td>
                                <td>
                                    <div class="fw-semibold text-dark">{{ $exam->week?->week_name ?? 'All Weeks' }}</div>
                                </td>
                                <td class="text-muted">{{ $exam->day }}</td>
                                <td class="text-muted">{{ $exam->time }}</td>
                                <td class="text-muted">{{ $exam->room }}</td>
                                <td class="text-muted">{{ $exam->moduleDistribution?->lecturer?->name ?? 'N/A' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">No timetable records available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($examData->hasPages())
            <div class="card-footer bg-white border-0 d-flex flex-wrap justify-content-between align-items-center gap-2 py-3 px-4 no-print">
                <small class="text-muted">
                    Showing {{ $examData->firstItem() }} to {{ $examData->lastItem() }} of {{ $examData->total() }} records
                </small>
                {{ $examData->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>
@endsection
