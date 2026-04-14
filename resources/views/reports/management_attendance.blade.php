@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <div>
                <h3 class="mb-0">Attendance Report</h3>
                <p class="text-muted mb-0">Filter attendance by week, course, and subject</p>
            </div>
            <x-report-actions
                :export-url="request()->fullUrlWithQuery(['export' => 1])"
                export-filename="attendance-report.xlsx"
                export-sheet="Attendance Report"
            />
        </div>

        <form method="GET" action="{{ route('management.attendance-report') }}" class="card shadow-sm mb-4" id="attendance-report-form">
            <div class="card-body" id="attendance-filters">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="week_id" class="form-label">Week</label>
                        <select name="week_id" id="week_id" class="form-select">
                            <option value="">All Weeks</option>
                            @foreach ($weeks as $week)
                                <option value="{{ $week->id }}" @selected(request('week_id') == $week->id)>{{ $week->week_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="program_id" class="form-label">Course / Program</label>
                        <select name="program_id" id="program_id" class="form-select">
                            <option value="">All Courses</option>
                            @foreach ($programs as $program)
                                <option value="{{ $program->id }}" @selected(request('program_id') == $program->id)>{{ $program->program_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="module_id" class="form-label">Subject / Module</label>
                        <select name="module_id" id="module_id" class="form-select">
                            <option value="">All Subjects</option>
                            @foreach ($modules as $module)
                                <option value="{{ $module->id }}"
                                    data-program-id="{{ $module->program_id }}"
                                    @selected(request('module_id') == $module->id)>
                                    {{ $module->module_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </form>

        <div class="printable-area">
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <div class="text-muted small">Total Records</div>
                            <h4 class="mb-0">{{ $totalRecords }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <div class="text-muted small">Present</div>
                            <h4 class="mb-0 text-success">{{ $presentRecords }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <div class="text-muted small">Absent</div>
                            <h4 class="mb-0 text-danger">{{ $absentRecords }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <div class="text-muted small">Attendance Rate</div>
                            <h4 class="mb-0">{{ $attendanceRate }}%</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body table-responsive">
                    <table class="table table-striped align-middle">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Course</th>
                                <th>Subject</th>
                                <th>Week</th>
                                <th>Total Sessions</th>
                                <th>Attended</th>
                                <th>Attendance %</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($records as $record)
                                <tr>
                                    <td>{{ $record->student_name }}</td>
                                    <td>{{ $record->program_name }}</td>
                                    <td>{{ $record->module_name }}</td>
                                    <td>{{ $record->week_name ?? 'N/A' }}</td>
                                    <td>{{ $record->total_sessions }}</td>
                                    <td>{{ $record->attended_sessions }}</td>
                                    <td>
                                        <span class="badge bg-{{ $record->attendance_percentage >= 75 ? 'success' : 'danger' }}">
                                            {{ $record->attendance_percentage }}%
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">No attendance report data found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-3 no-print">
                {{ $records->withQueryString()->links() }}
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const filterForm = document.getElementById('attendance-report-form');
            const weekSelect = document.getElementById('week_id');
            const programSelect = document.getElementById('program_id');
            const moduleSelect = document.getElementById('module_id');

            if (!filterForm || !weekSelect || !programSelect || !moduleSelect) {
                return;
            }

            const moduleOptions = Array.from(moduleSelect.querySelectorAll('option[data-program-id]'));
            let submitTimer = null;

            const submitFilters = () => {
                window.clearTimeout(submitTimer);
                submitTimer = window.setTimeout(() => {
                    filterForm.submit();
                }, 150);
            };

            const syncModules = () => {
                const selectedProgramId = programSelect.value;
                const selectedModuleOption = moduleSelect.selectedOptions[0];

                moduleOptions.forEach((option) => {
                    const matchesProgram = !selectedProgramId || option.dataset.programId === selectedProgramId;
                    option.hidden = !matchesProgram;
                    option.disabled = !matchesProgram;
                });

                if (selectedModuleOption && selectedModuleOption.dataset.programId !== selectedProgramId) {
                    moduleSelect.value = '';
                }
            };

            weekSelect.addEventListener('change', submitFilters);
            programSelect.addEventListener('change', () => {
                syncModules();
                submitFilters();
            });
            moduleSelect.addEventListener('change', submitFilters);

            syncModules();
        });
    </script>
@endsection
