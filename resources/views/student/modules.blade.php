@extends('layouts.app')

@section('content')
<div class="container-fluid py-4 student-shell">
    <div class="student-hero p-4 p-md-5 mb-4">
        <div class="row align-items-center g-3">
            <div class="col-lg-8">
                <div class="d-inline-flex align-items-center gap-2 student-badge bg-white bg-opacity-10 mb-3">
                    <i class='bx bx-book-open'></i>
                    <span>My Modules</span>
                </div>
                <h2 class="fw-bold mb-2">Your Academic Modules</h2>
                <p class="mb-0" style="opacity: .88;">
                    Browse the modules assigned to your program together with lecturer details.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <div class="d-inline-flex flex-wrap justify-content-end gap-2 no-print">
                    <a href="{{ route('studenttimetable') }}" class="btn btn-light btn-lg rounded-pill px-4 shadow-sm fw-semibold">
                        <i class='bx bx-calendar-event me-1'></i> View Timetable
                    </a>
                    <button type="button" class="btn btn-outline-light btn-lg rounded-pill px-4 shadow-sm fw-semibold" data-report-print>
                        <i class='bx bx-printer me-1'></i> Print
                    </button>
                </div>
            </div>
        </div>
    </div>

    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm rounded-4">{{ session('error') }}</div>
    @endif

    <div class="printable-area">
        <div class="mb-3">
            <h3 class="fw-bold mb-1 text-dark">Your Academic Modules</h3>
            <p class="text-muted mb-0">Modules assigned to your program together with lecturer details.</p>
        </div>

        <div class="student-card card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table student-table table-hover align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th class="ps-4">#</th>
                                <th>Code</th>
                                <th>Module Name</th>
                                <th>Program</th>
                                <th>Semester</th>
                                <th>NTA</th>
                                <th>Lecturer</th>
                                <th class="pe-4">Credits</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($modules as $index => $module)
                                <tr>
                                    <td class="ps-4 fw-semibold">{{ $index + 1 }}</td>
                                    <td>{{ $module->module_code }}</td>
                                    <td class="fw-semibold">{{ $module->module_name }}</td>
                                    <td>{{ $module->program_name }}</td>
                                    <td><span class="badge bg-primary student-badge">{{ $module->semester }}</span></td>
                                    <td><span class="badge bg-info text-dark student-badge">NTA {{ $module->nta_level }}</span></td>
                                    <td>{{ $module->lecturer_name }}</td>
                                    <td class="pe-4">{{ $module->module_credit }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div class="student-empty p-4 mx-3">
                                            <h6 class="fw-bold mb-2">No modules found for your account</h6>
                                            <p class="student-muted mb-0">Once your program is assigned, your modules will appear here.</p>
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
