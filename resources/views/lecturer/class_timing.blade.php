@extends('layouts.app')

@section('content')
<div class="container-fluid py-3 lecturer-shell">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div>
            <div class="text-uppercase text-muted small fw-semibold mb-1">Teaching Timetable</div>
            <h4 class="fw-bold mb-0 text-dark">My Teaching Timetable</h4>
        </div>
        <div class="d-flex flex-wrap align-items-center gap-2 no-print">
            <a href="{{ route('attendanceindex') }}" class="btn btn-dark btn-sm rounded-pill px-3">
                <i class='bx bx-check-circle me-1'></i> Take Attendance
            </a>
            <button type="button" class="btn btn-outline-dark btn-sm rounded-pill px-3" data-report-print>
                <i class='bx bx-printer me-1'></i> Print
            </button>
        </div>
    </div>

    <div class="printable-area">
        <div class="lecturer-card card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table lecturer-table table-sm align-middle text-center mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th class="ps-3">Time</th>
                                @foreach($days as $day)
                                    <th>{{ ucfirst($day) }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($formatted as $time => $row)
                                <tr>
                                    <td class="ps-3 fw-semibold">{{ $time }}</td>
                                    @foreach($days as $day)
                                        <td class="bg-light">
                                            @if(isset($row[$day]))
                                                <div class="fw-semibold text-primary">{{ $row[$day]->module_name }}</div>
                                                <div class="text-muted small">Room: {{ $row[$day]->room }}</div>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ count($days) + 1 }}" class="text-center py-5">
                                        <div class="lecturer-empty py-4 mx-3">
                                            <h6 class="fw-bold mb-2">No timetable entries</h6>
                                            <p class="lecturer-muted mb-0">Timetable rows will appear here once they are added.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
