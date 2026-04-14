@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-white py-3 border-0 d-flex align-items-center justify-content-between border-bottom">
                    <h5 class="mb-0 fw-bold text-primary">
                        <i class='bx bx-edit-alt me-2'></i>Edit Module Assignment
                    </h5>
                    <a href="{{ route('hodreport') }}" class="btn btn-light btn-sm rounded-pill px-3 shadow-sm">
                        <i class='bx bx-arrow-back me-1'></i> Back to Report
                    </a>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('moduledistribute.update', $distribution->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-4 mb-4">
                            <!-- Module Selection -->
                            <div class="col-md-12">
                                <label class="form-label small fw-bold text-muted text-uppercase">Module / Course</label>
                                <select name="module_id" class="form-select border-0 bg-light rounded-3 py-2 shadow-sm @error('module_id') is-invalid @enderror">
                                    <option value="">-- Select Module --</option>
                                    @foreach($modules as $module)
                                        <option value="{{ $module->id }}" {{ $distribution->module_id == $module->id ? 'selected' : '' }}>
                                            {{ $module->module_name }} ( Level {{ $module->nta_level }} )
                                        </option>
                                    @endforeach
                                </select>
                                @error('module_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Lecturer Selection -->
                            <div class="col-md-12">
                                <label class="form-label small fw-bold text-muted text-uppercase tracking-wider">Assign Lecturer</label>
                                <select name="user_id" class="form-select border-0 bg-light rounded-3 py-2 shadow-sm @error('user_id') is-invalid @enderror">
                                    <option value="">-- Select Lecturer --</option>
                                    @foreach($lecturers as $lecturer)
                                        <option value="{{ $lecturer->id }}" {{ $distribution->user_id == $lecturer->id ? 'selected' : '' }}>
                                            {{ $lecturer->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Academic Year -->
                            <div class="col-md-12">
                                <label class="form-label small fw-bold text-muted text-uppercase tracking-wider">Academic Year</label>
                                <input type="text" name="academic_year" class="form-control border-0 bg-light rounded-3 py-2 shadow-sm @error('academic_year') is-invalid @enderror" 
                                       value="{{ old('academic_year', $distribution->academic_year) }}" placeholder="e.g. 2025/2026">
                                @error('academic_year')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-5">
                            <a href="{{ route('hodreport') }}" class="btn btn-light rounded-3 px-4 shadow-sm fw-bold text-muted">Cancel</a>
                            <button type="submit" class="btn btn-primary rounded-3 px-4 shadow-sm fw-bold">
                                <i class='bx bx-check-circle me-1'></i> Update Assignment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
