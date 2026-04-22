@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-3">
        <div>
            <h3 class="fw-bold mb-1 text-dark">Exam Eligibility</h3>
            <p class="text-muted mb-0">Simple clearance list based on attendance threshold.</p>
        </div>
        <div class="d-flex flex-wrap align-items-center gap-2 no-print">
            <span class="badge rounded-pill text-bg-primary px-3 py-2">
                {{ $eligibilityData->total() }} records
            </span>
            <x-report-actions
                :export-url="request()->fullUrlWithQuery(['export' => 1])"
                export-filename="exam-eligibility.xlsx"
                export-sheet="Exam Eligibility"
            />
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-3 p-md-4">
            <form action="{{ route('exam.eligibility') }}" method="GET">
                <div class="row g-3 align-items-end">
                    <div class="col-md-5">
                        <label class="form-label fw-semibold text-muted small text-uppercase">Subject / Module</label>
                        <select name="module_id" class="form-select">
                            <option value="">All Subjects</option>
                            @foreach($modules as $module)
                                <option value="{{ $module->id }}" {{ request('module_id') == $module->id ? 'selected' : '' }}>
                                    {{ $module->module_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-muted small text-uppercase">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Statuses</option>
                            <option value="cleared" {{ request('status') == 'cleared' ? 'selected' : '' }}>Cleared</option>
                            <option value="not_cleared" {{ request('status') == 'not_cleared' ? 'selected' : '' }}>Not Cleared</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100">
                            Filter
                        </button>
                        <a href="{{ route('exam.eligibility') }}" class="btn btn-outline-secondary w-100">
                            Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="printable-area">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Student</th>
                            <th>Module</th>
                            <th>Sessions</th>
                            <th>Attendance</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($eligibilityData as $data)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-semibold text-dark">{{ $data->student_name }}</div>
                                    <small class="text-muted">STU-{{ str_pad($data->student_id, 4, '0', STR_PAD_LEFT) }}</small>
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark">{{ $data->module_name }}</div>
                                </td>
                                <td class="text-muted">
                                    {{ $data->attended_sessions }} / {{ $data->total_sessions }}
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="progress flex-grow-1" style="height: 8px;">
                                            <div class="progress-bar {{ $data->attendance_percentage >= 75 ? 'bg-success' : 'bg-danger' }}"
                                                style="width: {{ $data->attendance_percentage }}%"></div>
                                        </div>
                                        <span class="fw-semibold">{{ $data->attendance_percentage }}%</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge rounded-pill px-3 py-2 {{ $data->attendance_percentage >= 75 ? 'text-bg-success' : 'text-bg-danger' }}">
                                        {{ $data->attendance_percentage >= 75 ? 'Cleared' : 'Not Cleared' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    No eligibility data found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($eligibilityData->hasPages())
                <div class="card-footer bg-white border-0 px-4 py-3 d-flex flex-wrap justify-content-between align-items-center gap-2 no-print">
                    <small class="text-muted">
                        Showing {{ $eligibilityData->firstItem() }} to {{ $eligibilityData->lastItem() }} of {{ $eligibilityData->total() }} records
                    </small>
                    {{ $eligibilityData->withQueryString()->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
