@extends('layouts.app')

@section('content')
<div class="container-fluid py-3 lecturer-shell">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div>
            <div class="text-uppercase text-muted small fw-semibold mb-1">My Classes</div>
            <h4 class="fw-bold mb-0 text-dark">Assigned Classes</h4>
        </div>
        <div class="d-flex flex-wrap align-items-center gap-2 no-print">
            <a href="{{ route('lecturerreport') }}" class="btn btn-dark btn-sm rounded-pill px-3">
                <i class='bx bx-book-open me-1'></i> My Modules
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
                    <table class="table lecturer-table table-sm align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th class="ps-3">Module</th>
                                <th>Program</th>
                                <th>NTA Level</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data as $row)
                                <tr>
                                    <td class="ps-3 fw-semibold">{{ $row->module->module_name }}</td>
                                    <td>{{ $row->module->program->program_name }}</td>
                                    <td><span class="badge bg-info text-dark lecturer-badge">NTA {{ $row->module->nta_level }}</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-5">
                                        <div class="lecturer-empty py-4 mx-3">
                                            <h6 class="fw-bold mb-2">No classes found</h6>
                                            <p class="lecturer-muted mb-0">Assigned modules will show here once they are available.</p>
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
