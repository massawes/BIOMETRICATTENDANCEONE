@extends('layouts.app')

@section('content')
<div class="container-fluid py-3 lecturer-shell">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div>
            <div class="text-uppercase text-muted small fw-semibold mb-1">Device Management</div>
            <h4 class="fw-bold mb-0 text-dark">Biometric Devices</h4>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-4">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger border-0 shadow-sm rounded-4">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="lecturer-card card mb-3">
        <div class="card-body p-3 p-md-4">
            <form method="POST" action="{{ route('devices.store') }}" class="lecturer-device-form">
                @csrf
                <div class="row g-2 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label filter-label mb-1">Device Name</label>
                        <input type="text" name="device_name" class="form-control form-control-lg" placeholder="e.g. Lab Scanner 01" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label filter-label mb-1">Department</label>
                        <input type="text" name="device_dep" class="form-control form-control-lg" placeholder="e.g. ICT Department" required>
                    </div>
                    <div class="col-md-4 d-grid">
                        <button class="btn btn-success btn-lg rounded-pill px-4 shadow-sm">
                            <i class='bx bx-plus-circle me-1'></i> New Device
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="lecturer-card card mb-3">
        <div class="card-body p-3 p-md-4">
            <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-3">
                <div>
                    <h5 class="lecturer-section-title mb-1">Start Enrollment</h5>
                    <p class="lecturer-muted mb-0">Choose the ESP32, select a student, and assign a fingerprint slot.</p>
                </div>
                <span class="badge bg-warning text-dark rounded-pill px-3 py-2">Enrollment Mode Required</span>
            </div>

            @if(session('error'))
                <div class="alert alert-danger border-0 shadow-sm rounded-4">{{ session('error') }}</div>
            @endif

            @if($devices->isEmpty())
                <div class="alert alert-info border-0 shadow-sm rounded-4 mb-0">
                    Register a device first before starting enrollment.
                </div>
            @elseif($students->isEmpty())
                <div class="alert alert-info border-0 shadow-sm rounded-4 mb-0">
                    No students available for your assigned courses with an empty fingerprint slot.
                </div>
            @elseif($availableFingerprintIds->isEmpty())
                <div class="alert alert-warning border-0 shadow-sm rounded-4 mb-0">
                    All fingerprint slots are already used.
                </div>
            @else
                <form method="POST" action="{{ route('devices.enrollment.request') }}">
                    @csrf
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label filter-label mb-1">Device</label>
                            @if($devices->count() === 1)
                                <input type="hidden" name="device_id" value="{{ $devices->first()->id }}">
                                <div class="form-control form-control-lg bg-light d-flex justify-content-between align-items-center">
                                    <span>{{ $devices->first()->device_name }}</span>
                                    <span class="badge bg-success rounded-pill">Auto-selected</span>
                                </div>
                            @else
                                <select name="device_id" class="form-select form-select-lg" required>
                                    <option value="">Select Device</option>
                                    @foreach($devices as $device)
                                        <option value="{{ $device->id }}" @selected(old('device_id') == $device->id)>{{ $device->device_name }} ({{ $device->device_uid }})</option>
                                    @endforeach
                                </select>
                            @endif
                        </div>
                        <div class="col-md-4">
                            <label class="form-label filter-label mb-1">Student</label>
                            <select name="student_id" class="form-select form-select-lg" required>
                                <option value="">Select Student</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}" @selected(old('student_id') == $student->id)>
                                        {{ $student->student_name }} - {{ $student->program?->program_name ?? 'Program' }} - Intake {{ $student->intake }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label filter-label mb-1">Fingerprint ID</label>
                            <select name="fingerprint_id" class="form-select form-select-lg" required>
                                <option value="">Select ID</option>
                                @foreach($availableFingerprintIds as $fingerprintId)
                                    <option value="{{ $fingerprintId }}" @selected(old('fingerprint_id') == $fingerprintId)>
                                        ID {{ $fingerprintId }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 d-grid">
                            <button class="btn btn-dark btn-lg rounded-pill px-4 shadow-sm">
                                Start Enrollment
                            </button>
                        </div>
                    </div>
                </form>
            @endif
        </div>
    </div>

    <div class="lecturer-device-card card">
        <div class="card-header bg-white border-0 pt-3 px-3 pb-0">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h5 class="lecturer-section-title mb-1">Registered Devices</h5>
                </div>
                <span class="badge bg-primary lecturer-badge">{{ $devices->count() }} devices</span>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table lecturer-table table-sm table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-3">Name</th>
                            <th>Department</th>
                            <th>UID</th>
                            <th>Date</th>
                            <th>Mode</th>
                            <th class="pe-3">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($devices as $d)
                            <tr>
                                <td class="ps-3 fw-semibold">{{ $d->device_name }}</td>
                                <td>{{ $d->device_dep }}</td>
                                <td>
                                    <form method="POST" action="{{ route('devices.uid', $d->id) }}" class="d-flex align-items-center gap-2">
                                        @csrf
                                        <button class="btn btn-warning btn-sm rounded-pill px-3">
                                            <i class='bx bx-refresh'></i>
                                        </button>
                                        <span class="font-monospace small">{{ $d->device_uid }}</span>
                                    </form>
                                </td>
                                <td>{{ $d->device_date }}</td>
                                <td style="min-width: 180px;">
                                    <form method="POST" action="{{ route('devices.mode', $d->id) }}">
                                        @csrf
                                        <select name="mode" onchange="this.form.submit()" class="form-select">
                                            <option value="0" {{ $d->device_mode == 0 ? 'selected' : '' }}>Enrollment</option>
                                            <option value="1" {{ $d->device_mode == 1 ? 'selected' : '' }}>Attendance</option>
                                        </select>
                                    </form>
                                </td>
                                <td class="pe-3">
                                    <form method="POST" action="{{ route('devices.delete', $d->id) }}" onsubmit="return confirm('Delete this device?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-outline-danger rounded-pill px-3">
                                            <i class='bx bx-trash'></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="lecturer-empty py-4 mx-3">
                                        <h6 class="fw-bold mb-2">No devices registered</h6>
                                        <p class="lecturer-muted mb-0">Add a device above and it will show up here.</p>
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
@endsection
