@extends('layouts.app')

@section('content')
<div class="container-fluid py-4 student-shell">
    <div class="student-hero p-4 p-md-5 mb-4">
        <div class="row align-items-center g-3">
            <div class="col-lg-8">
                <div class="d-inline-flex align-items-center gap-2 student-badge bg-white bg-opacity-10 mb-3">
                    <i class='bx bx-calendar-week'></i>
                    <span>Weekly Timetable</span>
                </div>
                <h2 class="fw-bold mb-2">My Class Timetable</h2>
                <p class="mb-0" style="opacity: .88;">
                    View your weekly classes in a clean timetable layout.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <div class="d-inline-flex flex-wrap justify-content-end gap-2 no-print">
                    <a href="{{ route('studentmodules') }}" class="btn btn-light btn-lg rounded-pill px-4 shadow-sm fw-semibold">
                        <i class='bx bx-book-open me-1'></i> My Modules
                    </a>
                    <button type="button" class="btn btn-outline-light btn-lg rounded-pill px-4 shadow-sm fw-semibold" data-report-print>
                        <i class='bx bx-printer me-1'></i> Print
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="printable-area">
        <div class="mb-3">
            <h3 class="fw-bold mb-1 text-dark">My Class Timetable</h3>
            <p class="text-muted mb-0">Weekly classes in a timetable layout.</p>
        </div>

        <div class="student-card card">
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table student-table table-bordered align-middle text-center mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th class="ps-4">Time</th>
                                @foreach($days as $day)
                                    <th>{{ ucfirst($day) }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($formatted as $time => $row)
                                <tr>
                                    <td class="ps-4 fw-bold">{{ $time }}</td>
                                    @foreach($days as $day)
                                        <td class="bg-light">
                                            @if(isset($row[$day]))
                                                <div class="student-panel p-3 text-start student-tilt">
                                                    <div class="fw-bold text-primary mb-1">{{ $row[$day]->module_name }}</div>
                                                    <div class="student-muted small mb-1">Code: {{ $row[$day]->module_code }}</div>
                                                    <div class="student-muted small">Room: {{ $row[$day]->room }}</div>
                                                </div>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ count($days) + 1 }}" class="text-center py-5">
                                        <div class="student-empty p-4 mx-3">
                                            <h6 class="fw-bold mb-2">No timetable entries</h6>
                                            <p class="student-muted mb-0">Your timetable will appear once class timings are published.</p>
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
