@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="card border-0 shadow-sm mb-4 overflow-hidden">
            <div class="card-body p-4" style="background: linear-gradient(135deg, #0f172a 0%, #1d4ed8 55%, #38bdf8 100%);">
                <div class="row align-items-center g-4">
                    <div class="col-lg-8 text-white">
                        <div class="text-uppercase small fw-semibold opacity-75 mb-2">University Attendance Intelligence</div>
                        <h2 class="fw-bold mb-2">Analytics Dashboard</h2>
                        <p class="mb-0 opacity-75">Real attendance patterns for departments, programs, modules, and student risk tracking.</p>
                    </div>
                    <div class="col-lg-4">
                        <div class="bg-white bg-opacity-10 text-white rounded-4 p-3">
                            <div class="small text-uppercase opacity-75">Current Scope</div>
                            <div class="fs-5 fw-bold">{{ $scopeLabel ?: 'Department Scope' }}</div>
                            <div class="small opacity-75">
                                @if ($isHod)
                                    HOD view is locked to your department only.
                                @else
                                    Use filters below to drill into department and program data.
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <form method="GET" action="{{ route('analytics.dashboard') }}" class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <div class="row g-3 align-items-end">
                    @if ($canFilterDepartment)
                        <div class="col-md-4 col-lg-3">
                            <label for="department_id" class="form-label fw-semibold">Department</label>
                            <select name="department_id" id="department_id" class="form-select">
                                <option value="">All Departments</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}" @selected((string) $selectedDepartmentId === (string) $department->id)>{{ $department->department_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    <div class="col-md-4 col-lg-3">
                        <label for="program_id" class="form-label fw-semibold">Program</label>
                        <select name="program_id" id="program_id" class="form-select">
                            <option value="">All Programs</option>
                            @foreach ($programs as $program)
                                <option value="{{ $program->id }}"
                                    data-department-id="{{ $program->department_id }}"
                                    @selected((string) $selectedProgramId === (string) $program->id)>
                                    {{ $program->program_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 col-lg-3">
                        <label for="module_id" class="form-label fw-semibold">Subject / Module</label>
                        <select name="module_id" id="module_id" class="form-select">
                            <option value="">All Subjects</option>
                            @foreach ($modules as $module)
                                <option value="{{ $module->id }}"
                                    data-program-id="{{ $module->program_id }}"
                                    data-department-id="{{ $module->department_id }}"
                                    @selected((string) $selectedModuleId === (string) $module->id)>
                                    {{ $module->module_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 col-lg-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100">Apply</button>
                        <a href="{{ route('analytics.dashboard') }}" class="btn btn-outline-secondary w-100">Reset</a>
                    </div>
                </div>
            </div>
        </form>

        <div class="row g-4 mb-4">
            <div class="col-md-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="text-muted text-uppercase small fw-bold mb-2">Students Recorded</div>
                        <h2 class="fw-bold mb-1">{{ $totalStudents }}</h2>
                        <div class="text-muted small">Students found in current attendance records</div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="text-muted text-uppercase small fw-bold mb-2">Present Records</div>
                        <h2 class="fw-bold mb-1 text-success">{{ $present }}</h2>
                        <div class="text-muted small">{{ $attendanceRate }}% attendance from {{ $totalRecords }} records</div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="text-muted text-uppercase small fw-bold mb-2">Absent Records</div>
                        <h2 class="fw-bold mb-1 text-danger">{{ $absent }}</h2>
                        <div class="text-muted small">Attendance records marked absent</div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="text-muted text-uppercase small fw-bold mb-2">At Risk Students</div>
                        <h2 class="fw-bold mb-1 text-warning">{{ $atRiskStudents }}</h2>
                        <div class="text-muted small">Students below 75% attendance</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-xl-8">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h5 class="mb-1 fw-bold">Weekly Attendance Trend</h5>
                        <div class="text-muted small">Attendance movement across teaching weeks</div>
                    </div>
                    <div class="card-body px-4 pb-4">
                        <canvas id="weeklyChart" height="115"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h5 class="mb-1 fw-bold">Presence vs Absence</h5>
                        <div class="text-muted small">Current filtered attendance split</div>
                    </div>
                    <div class="card-body px-4 pb-4 d-flex align-items-center justify-content-center">
                        <canvas id="summaryChart" height="220"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-xl-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h5 class="mb-1 fw-bold">Program Performance</h5>
                        <div class="text-muted small">Best attendance rates by program</div>
                    </div>
                    <div class="card-body px-4 pb-4">
                        <canvas id="programChart" height="130"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h5 class="mb-1 fw-bold">Module Performance</h5>
                        <div class="text-muted small">Attendance strength by subject</div>
                    </div>
                    <div class="card-body px-4 pb-4">
                        <canvas id="moduleChart" height="130"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            @if ($canFilterDepartment)
                <div class="col-xl-5">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white border-0 pt-4 px-4">
                            <h5 class="mb-1 fw-bold">Department Comparison</h5>
                            <div class="text-muted small">Attendance standings across departments</div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="px-4">Department</th>
                                            <th>Records</th>
                                            <th class="text-end px-4">Attendance %</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($departmentStats as $department)
                                            <tr>
                                                <td class="px-4 fw-semibold">{{ $department->department_name }}</td>
                                                <td>{{ $department->total_records }}</td>
                                                <td class="text-end px-4">
                                                    <span class="badge bg-{{ $department->attendance_percentage >= 75 ? 'success' : 'danger' }}">
                                                        {{ $department->attendance_percentage }}%
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center py-4">No department analytics found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-7">
            @else
                <div class="col-xl-12">
            @endif
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white border-0 pt-4 px-4">
                            <h5 class="mb-1 fw-bold">Operational Highlights</h5>
                            <div class="text-muted small">Quick view of strongest and weakest attendance areas</div>
                        </div>
                        <div class="card-body">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="border rounded-4 p-3 h-100" style="background: #f8fafc;">
                                        <div class="text-uppercase text-muted small fw-bold mb-3">Top Programs</div>
                                        @forelse ($topPrograms as $program)
                                            <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                                                <div>
                                                    <div class="fw-semibold">{{ $program->program_name }}</div>
                                                    <div class="text-muted small">{{ $program->present_records }}/{{ $program->total_records }} present</div>
                                                </div>
                                                <span class="badge bg-success">{{ $program->attendance_percentage }}%</span>
                                            </div>
                                        @empty
                                            <div class="text-muted">No program data available.</div>
                                        @endforelse
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="border rounded-4 p-3 h-100" style="background: #fff7ed;">
                                        <div class="text-uppercase text-muted small fw-bold mb-3">Modules Needing Attention</div>
                                        @forelse ($lowModules as $module)
                                            <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                                                <div>
                                                    <div class="fw-semibold">{{ $module->module_name }}</div>
                                                    <div class="text-muted small">{{ $module->present_records }}/{{ $module->total_records }} present</div>
                                                </div>
                                                <span class="badge bg-{{ $module->attendance_percentage >= 75 ? 'success' : 'warning' }}">{{ $module->attendance_percentage }}%</span>
                                            </div>
                                        @empty
                                            <div class="text-muted">No module data available.</div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const weeklyLabels = {!! json_encode($weeklyStats->pluck('week_label')->values()) !!};
        const weeklyData = {!! json_encode($weeklyStats->pluck('attendance_percentage')->values()) !!};
        const programLabels = {!! json_encode($programStats->pluck('program_name')->values()) !!};
        const programData = {!! json_encode($programStats->pluck('attendance_percentage')->values()) !!};
        const moduleLabels = {!! json_encode($moduleStats->pluck('module_name')->values()) !!};
        const moduleData = {!! json_encode($moduleStats->pluck('attendance_percentage')->values()) !!};

        new Chart(document.getElementById('weeklyChart'), {
            type: 'line',
            data: {
                labels: weeklyLabels,
                datasets: [{
                    label: 'Attendance %',
                    data: weeklyData,
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.15)',
                    fill: true,
                    tension: 0.35,
                    pointBackgroundColor: '#1d4ed8',
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, max: 100 } }
            }
        });

        new Chart(document.getElementById('summaryChart'), {
            type: 'doughnut',
            data: {
                labels: ['Present', 'Absent'],
                datasets: [{
                    data: [{{ $present }}, {{ $absent }}],
                    backgroundColor: ['#16a34a', '#dc2626'],
                    borderWidth: 0
                }]
            },
            options: {
                cutout: '68%',
                plugins: { legend: { position: 'bottom' } }
            }
        });

        new Chart(document.getElementById('programChart'), {
            type: 'bar',
            data: {
                labels: programLabels,
                datasets: [{
                    data: programData,
                    backgroundColor: '#0ea5e9',
                    borderRadius: 8
                }]
            },
            options: {
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, max: 100 } }
            }
        });

        new Chart(document.getElementById('moduleChart'), {
            type: 'bar',
            data: {
                labels: moduleLabels,
                datasets: [{
                    data: moduleData,
                    backgroundColor: '#f97316',
                    borderRadius: 8
                }]
            },
            options: {
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, max: 100 } }
            }
        });

        const departmentSelect = document.getElementById('department_id');
        const programSelect = document.getElementById('program_id');
        const moduleSelect = document.getElementById('module_id');
        const lockedDepartmentId = @json((string) ($selectedDepartmentId ?? ''));

        if (programSelect && moduleSelect) {
            const programOptions = Array.from(programSelect.querySelectorAll('option[data-department-id]'));
            const moduleOptions = Array.from(moduleSelect.querySelectorAll('option[data-department-id]'));
            const getSelectedDepartmentId = () => departmentSelect ? departmentSelect.value : lockedDepartmentId;

            const syncPrograms = () => {
                const selectedDepartmentId = getSelectedDepartmentId();

                programOptions.forEach((option) => {
                    const matchesDepartment = !selectedDepartmentId || option.dataset.departmentId === selectedDepartmentId;
                    option.hidden = !matchesDepartment;
                    option.disabled = !matchesDepartment;
                });

                if (programSelect.selectedOptions[0]?.disabled) {
                    programSelect.value = '';
                }
            };

            const syncModules = () => {
                const selectedDepartmentId = getSelectedDepartmentId();
                const selectedProgramId = programSelect.value;

                moduleOptions.forEach((option) => {
                    const matchesDepartment = !selectedDepartmentId || option.dataset.departmentId === selectedDepartmentId;
                    const matchesProgram = !selectedProgramId || option.dataset.programId === selectedProgramId;
                    const isVisible = matchesDepartment && matchesProgram;

                    option.hidden = !isVisible;
                    option.disabled = !isVisible;
                });

                if (moduleSelect.selectedOptions[0]?.disabled) {
                    moduleSelect.value = '';
                }
            };

            const syncFilters = () => {
                syncPrograms();
                syncModules();
            };

            departmentSelect?.addEventListener('change', syncFilters);
            programSelect.addEventListener('change', syncModules);
            syncFilters();
        }
    </script>
@endsection
