@extends('layouts.app')

@section('content')
<div class="container-fluid py-3">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div>
            <div class="text-uppercase text-muted small fw-semibold mb-1">HOD Report</div>
            <h4 class="fw-bold mb-0 text-dark">Module Distribution</h4>
        </div>
        <div class="d-flex flex-wrap align-items-center gap-2 no-print">
            <a href="{{ route('hod.analysis') }}" class="btn btn-dark btn-sm rounded-pill px-3">Analysis</a>
            <x-report-actions
                :export-url="request()->fullUrlWithQuery(['export' => 1])"
                export-filename="hod-report.xlsx"
                export-sheet="HOD Report"
            />
        </div>
    </div>

    <form method="GET" action="{{ route('hodreport') }}" class="row g-2 mb-3 no-print">
        <div class="col-md-4">
            <input
                type="text"
                name="academic_year"
                class="form-control"
                placeholder="Filter by academic year e.g. 2025/2026"
                value="{{ request('academic_year') }}"
            >
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-outline-primary">Filter</button>
        </div>
        @if (request()->filled('academic_year'))
            <div class="col-auto">
                <a href="{{ route('hodreport') }}" class="btn btn-outline-secondary">Clear</a>
            </div>
        @endif
    </form>

    <div class="printable-area">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Module</th>
                            <th>Programme</th>
                            <th>Lecturer</th>
                            <th>NTA</th>
                            <th>Academic Year</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reports as $report)
                            <tr>
                                <td class="ps-4 fw-semibold text-dark">{{ $report->module_name }}</td>
                                <td class="text-muted">{{ $report->program_name }}</td>
                                <td class="text-muted">{{ $report->lecturer_name }}</td>
                                <td><span class="badge rounded-pill text-bg-primary px-3 py-2">NTA {{ $report->nta_level }}</span></td>
                                <td class="text-muted">{{ $report->academic_year }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">No module distribution found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="card-footer bg-white border-0 px-4 py-3 d-flex justify-content-center no-print">
                {{ $reports->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
