@extends('layouts.app')

@section('content')
    @php
        $sourceLabels = [
            'manual' => 'Manual',
            'zkbio' => 'Face 990 / ZKBio',
            'fingerprint' => 'Fingerprint',
        ];

        $sourceBadgeClasses = [
            'manual' => 'secondary',
            'zkbio' => 'primary',
            'fingerprint' => 'info',
        ];
    @endphp

    <div class="container py-4">
        @include('management.partials.messages')
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h3 class="mb-0">Attendance Records</h3>
                <small class="text-muted">Manage manual records and view biometric records from Face 990 / ZKBio Time.</small>
            </div>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-outline-primary" id="syncZkbioNow" data-sync-url="{{ route('zkbio.realtime-sync') }}">Sync ZKBio Now</button>
                <a href="{{ route('attendance.records.create') }}" class="btn btn-primary">Add Attendance</a>
            </div>
        </div>
        <form method="GET" action="{{ route('attendance.records.index') }}" class="row g-2 mb-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search student, module, year, date, source" value="{{ request('search') }}">
            </div>
            @if ($hasAttendanceSource)
                <div class="col-md-3">
                    <select name="source" class="form-select">
                        <option value="all" @selected($sourceFilter === 'all')>All sources</option>
                        <option value="manual" @selected($sourceFilter === 'manual')>Manual</option>
                        <option value="zkbio" @selected($sourceFilter === 'zkbio')>Face 990 / ZKBio</option>
                        <option value="fingerprint" @selected($sourceFilter === 'fingerprint')>Fingerprint</option>
                    </select>
                </div>
            @endif
            <div class="col-auto">
                <button type="submit" class="btn btn-outline-primary">Search</button>
            </div>
        </form>
        <div class="card shadow-sm">
            <div class="card-body table-responsive">
                <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Admin Number</th>
                            <th>Module</th>
                            <th>Week</th>
                            <th>Academic Year</th>
                            <th>Date</th>
                            <th>Source</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($attendances as $attendance)
                            @php
                                $source = $attendance->attendance_source ?: 'manual';
                                $sourceLabel = $sourceLabels[$source] ?? ucfirst($source);
                                $sourceBadgeClass = $sourceBadgeClasses[$source] ?? 'dark';
                            @endphp
                            <tr>
                                <td>{{ $attendance->student?->student_name }}</td>
                                <td>{{ $attendance->student?->admin_number ?? '-' }}</td>
                                <td>{{ $attendance->moduleDistribution?->module?->module_name }}</td>
                                <td>{{ $attendance->week_id ? 'Week ' . $attendance->week_id : '-' }}</td>
                                <td>{{ $attendance->academic_year }}</td>
                                <td>{{ $attendance->date }}</td>
                                <td>
                                    <span class="badge bg-{{ $sourceBadgeClass }}">{{ $sourceLabel }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $attendance->is_present ? 'success' : 'danger' }}">
                                        {{ $attendance->is_present ? 'Present' : 'Absent' }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('attendance.records.edit', $attendance->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('attendance.records.destroy', $attendance->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this attendance record?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">No attendance records found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-3">{{ $attendances->withQueryString()->links() }}</div>

        <div class="card shadow-sm mt-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">Latest Face 990 / ZKBio Scans Today</h5>
                    <small class="text-muted">Raw device scans from ZKBio Time before they are converted into class attendance.</small>
                </div>
                @if ($showRawZkbioLogs)
                    <span class="badge bg-primary">{{ $latestZkbioLogs->count() }}</span>
                @else
                    <a href="{{ route('attendance.records.index', array_merge(request()->query(), ['show_raw_logs' => 1])) }}" class="btn btn-sm btn-outline-primary">
                        View Raw Scans
                    </a>
                @endif
            </div>
            <div class="card-body table-responsive">
                @if ($showRawZkbioLogs)
                    <table class="table table-sm align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Transaction</th>
                                <th>Emp Code</th>
                                <th>Student</th>
                                <th>Scan Time</th>
                                <th>Terminal</th>
                                <th>Sync</th>
                                <th>Attendance</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($latestZkbioLogs as $log)
                                @php
                                    $matchedStudent = $zkbioStudentMap->get((string) $log->emp_code);
                                    $sync = $zkbioSyncMap->get($log->id);
                                    $syncStatus = $sync->status ?? 'not synced';
                                    $syncClass = [
                                        'synced' => 'success',
                                        'skipped' => 'warning',
                                        'error' => 'danger',
                                        'not synced' => 'secondary',
                                    ][$syncStatus] ?? 'secondary';
                                @endphp
                                <tr>
                                    <td>{{ $log->id }}</td>
                                    <td>{{ $log->emp_code }}</td>
                                    <td>
                                        @if ($matchedStudent)
                                            {{ $matchedStudent->student_name }}
                                            <div class="small text-muted">{{ $matchedStudent->admin_number ?? '-' }}</div>
                                        @else
                                            <span class="text-muted">Not matched</span>
                                        @endif
                                    </td>
                                    <td>{{ $log->punch_time }}</td>
                                    <td>{{ $log->terminal_sn ?? '-' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $syncClass }}">{{ ucfirst($syncStatus) }}</span>
                                        @if (! empty($sync?->message))
                                            <div class="small text-muted">{{ $sync->message }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        @if (! empty($sync?->attendance_id))
                                            <span class="badge bg-light text-dark">#{{ $sync->attendance_id }}</span>
                                        @elseif ($syncStatus === 'synced')
                                            <span class="text-muted small">Duplicate scan</span>
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">No Face 990 / ZKBio scans found today.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                @else
                    <div class="text-muted small">Raw scans are hidden so this page loads faster.</div>
                @endif
            </div>
        </div>
    </div>

    <script>
        document.getElementById('syncZkbioNow')?.addEventListener('click', async (event) => {
            const button = event.currentTarget;
            const originalText = button.textContent;

            button.disabled = true;
            button.textContent = 'Syncing...';

            try {
                const response = await fetch(button.dataset.syncUrl, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    cache: 'no-store',
                });

                if (response.ok) {
                    location.reload();
                    return;
                }
            } catch (error) {
                // The automatic sync in the layout will keep retrying.
            }

            button.disabled = false;
            button.textContent = originalText;
        });
    </script>
@endsection
