@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4 align-items-center no-print">
        <div class="col">
            <h3 class="mb-0 text-primary fw-bold">Faculties & Departments</h3>
            <p class="text-muted mb-0">Manage and oversee all academic departments</p>
        </div>
    </div>

    <div class="printable-area">
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center rounded-top-4">
                <h6 class="mb-0 fw-bold"><i class='bx bx-list-ul me-2'></i> Department List</h6>
                <div class="d-flex align-items-center gap-2 no-print">
                    <button class="btn btn-sm btn-light border"><i class='bx bx-filter-alt'></i> Filter</button>
                    <button type="button" class="btn btn-sm btn-outline-dark rounded-pill px-3" data-report-print>
                        <i class='bx bx-printer me-1'></i> Print
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 text-uppercase text-muted small fw-bold tracking-wide">ID</th>
                                <th class="text-uppercase text-muted small fw-bold tracking-wide">Department Name</th>
                                <th class="text-uppercase text-muted small fw-bold tracking-wide">Total Modules</th>
                                <th class="text-uppercase text-muted small fw-bold tracking-wide text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($departments as $dept)
                            <tr class="transition-all hover-bg-light">
                                <td class="ps-4 text-muted">#{{ str_pad($dept->id, 3, '0', STR_PAD_LEFT) }}</td>
                                <td class="fw-medium text-dark">{{ $dept->department_name ?? 'N/A' }}</td>
                                <td><span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">{{ $dept->total_modules }} Modules</span></td>
                                <td class="text-end pe-4">
                                    <button class="btn btn-sm btn-icon btn-light rounded-circle text-primary"><i class='bx bx-show'></i></button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">No departments found in the system.</td>
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
