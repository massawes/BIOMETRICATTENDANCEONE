@extends('layouts.app')

@section('content')

<div class="container mt-4">

    <!-- PAGE HEADER -->
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-body text-center">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                <div class="text-start">
                    <h3 class="fw-bold text-primary mb-1">Assign Lecturers to Modules</h3>
                    <p class="text-muted mb-0">Manage module allocation for your department</p>
                </div>
                <div class="d-flex flex-wrap align-items-center gap-2">
                    <button
                        type="button"
                        class="btn btn-outline-success btn-sm rounded-pill px-3"
                        data-excel-export
                        data-export-url="{{ route('moduledistribute.export') }}"
                        data-export-filename="module-distributions.xlsx"
                        data-export-sheet="Module Distributions"
                    >
                        <i class='bx bx-download me-1'></i> Export Excel
                    </button>

                    <x-import-actions
                        :import-url="route('spreadsheets.import', 'module_distributions')"
                        import-entity="module_distributions"
                        :template-fields="['module_code', 'lecturer_name', 'academic_year']"
                        template-filename="module-distributions-template.xlsx"
                        hint="module_code, lecturer_name, academic_year"
                    />
                </div>
            </div>
        </div>
    </div>

    <!-- ALERTS -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- FORM -->
    <form action="{{ route('moduledistribute.store') }}" method="POST">
        @csrf

        <div class="card shadow-sm mb-4 border-0">
            <div class="card-body">
                <div class="row g-3 align-items-end">
                    <div class="col-lg-6">
                        <label class="form-label fw-bold">Academic Year</label>
                        <input
                            type="text"
                            name="academic_year"
                            class="form-control @error('academic_year') is-invalid @enderror"
                            value="{{ old('academic_year', $selectedAcademicYear) }}"
                            placeholder="e.g. 2025/2026"
                            required
                        >
                        @error('academic_year')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-lg-6">
                        <div class="d-flex flex-wrap gap-2 justify-content-lg-end">
                            <a
                                href="{{ route('moduledistribute.index', ['academic_year' => old('academic_year', $selectedAcademicYear)]) }}"
                                class="btn btn-outline-primary px-4"
                            >
                                View Saved Distributions
                            </a>
                            <a
                                href="{{ route('moduledistribute.create', ['academic_year' => old('academic_year', $selectedAcademicYear)]) }}"
                                class="btn btn-outline-secondary px-4"
                            >
                                Refresh Year
                            </a>
                            <button type="submit" class="btn btn-success px-4 shadow-sm">
                                Save Distributions
                            </button>
                        </div>
                        <small class="text-muted d-block mt-2 text-lg-end">
                            Current view is loading assignments for {{ old('academic_year', $selectedAcademicYear) }}.
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- MODULES -->
        <div class="row">
            @foreach($modules as $module)
                @php
                    $selectedLecturerId = old(
                        'distributions.' . $module->id,
                        $existingDistributions->get($module->id)?->user_id
                    );
                @endphp

                <div class="col-md-6 mb-4">
                    <div class="card h-100 shadow-sm border-0">

                        <div class="card-body">

                            <!-- MODULE NAME -->
                            <h5 class="fw-bold text-dark">
                                {{ $module->module_name }}
                            </h5>

                            <!-- MODULE CODE -->
                            <span class="badge bg-primary mb-2">
                                {{ $module->module_code }}
                            </span>

                            <!-- DETAILS -->
                    <p class="text-muted mb-2">
                        Level: <strong>{{ $module->nta_level }}</strong> |
                        Semester: <strong>{{ $module->semester }}</strong>
                    </p>

                            <!-- SELECT LECTURER -->
                            <label class="form-label fw-semibold">
                                Assign Lecturer
                            </label>

                            <select 
                                name="distributions[{{ $module->id }}]" 
                                class="form-select"
                            >
                                <option value="">-- Select Lecturer --</option>

                                @foreach($lecturers as $lecturer)
                                    <option 
                                        value="{{ $lecturer->id }}"
                                        {{ (string) $selectedLecturerId === (string) $lecturer->id ? 'selected' : '' }}
                                    >
                                        {{ $lecturer->name }}
                                    </option>
                                @endforeach

                            </select>

                        </div>

                    </div>
                </div>

            @endforeach
        </div>

        <!-- PAGINATION -->
        <div class="d-flex justify-content-center mt-3">
            {{ $modules->appends(['academic_year' => old('academic_year', $selectedAcademicYear)])->links('pagination::bootstrap-5') }}
        </div>

        <!-- SUBMIT BUTTON -->
        <div class="text-center mt-4">
            <button type="submit" class="btn btn-success px-5 py-2 shadow-sm">
                Save Distributions
            </button>
        </div>

    </form>

</div>

@endsection
