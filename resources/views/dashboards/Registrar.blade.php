@extends('layouts.app')

@section('content')
<div class="container-fluid py-3">
    <div class="card exec-hero mb-3">
        <div class="card-body p-3 p-lg-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <span class="exec-kicker mb-2">
                        <i class='bx bx-id-card'></i>
                        Registrar Office
                    </span>
                    <h1 class="exec-title mb-0" style="font-size: 1.9rem;">Registrar Dashboard</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 row-cols-1 row-cols-md-3">
        <div class="col">
            <div class="card exec-stat h-100">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <span class="stat-icon"><i class='bx bx-group'></i></span>
                    <div>
                        <div class="text-uppercase small fw-bold text-muted">Students</div>
                        <div class="exec-metric text-dark" style="font-size: 1.5rem;">{{ $totalStudents }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card exec-stat h-100">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <span class="stat-icon is-success"><i class='bx bx-book-content'></i></span>
                    <div>
                        <div class="text-uppercase small fw-bold text-muted">Programs</div>
                        <div class="exec-metric text-dark" style="font-size: 1.5rem;">{{ $totalPrograms }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card exec-stat h-100">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <span class="stat-icon is-warning"><i class='bx bx-buildings'></i></span>
                    <div>
                        <div class="text-uppercase small fw-bold text-muted">Departments</div>
                        <div class="exec-metric text-dark" style="font-size: 1.5rem;">{{ $totalDepartments }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
