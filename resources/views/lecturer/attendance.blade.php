@extends('layouts.app')

@section('content')
<div class="container-fluid py-3 lecturer-shell attendance-page">
    <div class="attendance-header mb-3">
        <div>
            <div class="text-uppercase text-muted small fw-semibold mb-1">Attendance</div>
            <h4 class="fw-bold mb-1 text-dark">Lecturer Attendance</h4>
            <p class="text-muted small mb-0">Use filters for the class, then type or scan the student admin number to mark present fast.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-4">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm rounded-4">{{ session('error') }}</div>
    @endif

    <div class="lecturer-card card mb-3 attendance-filter-card">
        <div class="card-body p-3 p-md-4">
            <div class="lecturer-filter-bar p-3">
                <div class="row g-2 g-md-3 align-items-end mb-3">
                    <div class="col-12">
                        <label class="form-label filter-label mb-1">Admin Number / QR Scan</label>
                        <form method="POST" action="{{ route('attendance.quick-mark') }}" id="quickAdminMarkForm" class="d-flex flex-column flex-lg-row gap-2" data-ready="{{ ($selectedWeek && $selectedCourse && $selectedDay && $selectedSubject) ? '1' : '0' }}" data-admin-numbers='@json($classAdminNumbers ?? [])'>
                            @csrf
                            <input type="hidden" name="week_id" value="{{ $selectedWeek }}">
                            <input type="hidden" name="course_id" value="{{ $selectedCourse }}">
                            <input type="hidden" name="day" value="{{ $selectedDay }}">
                            <input type="hidden" name="subject" value="{{ $selectedSubject }}">

                            <input
                                type="text"
                                name="admin_number"
                                id="adminNumberSearch"
                                class="form-control form-control-lg flex-grow-1"
                                placeholder="Type or scan student admin number"
                                autocomplete="off"
                                inputmode="numeric"
                                autofocus
                                {{ (!$selectedWeek || !$selectedCourse || !$selectedDay || !$selectedSubject) ? 'disabled' : '' }}
                            >
                        </form>
                        <div id="adminSearchFeedback" class="lecturer-muted small mt-2" aria-live="polite">
                            @if(!$selectedWeek || !$selectedCourse || !$selectedDay || !$selectedSubject)
                                Select week, course, day, and subject first, then type or scan the student admin number here.
                            @else
                                Auto search is active here. As soon as the admin number matches a student in this class, the system marks them present automatically.
                            @endif
                        </div>
                    </div>
                </div>

                <form method="GET" action="{{ route('attendanceindex') }}">
                    <div class="row g-2 g-md-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label filter-label mb-1">Week</label>
                        <select name="week_id" class="form-select form-select-lg" onchange="this.form.submit()">
                            <option value="">-- Select --</option>
                            @foreach($weeks as $week)
                                <option value="{{ $week->id }}" {{ $selectedWeek == $week->id ? 'selected' : '' }}>
                                    {{ $week->week_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label filter-label mb-1">Course</label>
                        <select name="course_id" class="form-select form-select-lg" onchange="this.form.submit()">
                            <option value="">-- Select --</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}" {{ (string) $selectedCourse === (string) $course->id ? 'selected' : '' }}>
                                    {{ $course->program_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label filter-label mb-1">Day</label>
                        <select name="day" class="form-select form-select-lg" onchange="this.form.submit()">
                            <option value="">-- Select --</option>
                            @foreach($days as $d)
                                <option value="{{ $d->day }}" {{ $selectedDay == $d->day ? 'selected' : '' }}>
                                    {{ ucfirst($d->day) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label filter-label mb-1">Subject</label>
                        <select name="subject" class="form-select form-select-lg" onchange="this.form.submit()">
                            <option value="">-- Select --</option>
                            @foreach($subjects as $s)
                                <option value="{{ $s->subject }}" {{ $selectedSubject == $s->subject ? 'selected' : '' }}>
                                    {{ $s->subject }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>

    <div class="lecturer-card card">
        <div class="card-header bg-white border-0 pt-3 px-3 pb-0">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h5 class="lecturer-section-title mb-0">Attendance Register</h5>
                @if($selectedWeek)
                    <span class="badge bg-primary lecturer-badge">Week {{ $selectedWeek }}</span>
                @endif
            </div>
        </div>

        <div class="card-body p-3">
            @if(!$selectedWeek || !$selectedCourse || !$selectedDay || !$selectedSubject)
                <div class="lecturer-empty text-center py-5">
                    <h6 class="fw-bold mb-1">Select week, course, day, and subject</h6>
                    <p class="lecturer-muted mb-0">Then the class list will appear here.</p>
                </div>
            @else
                <form method="POST" action="{{ route('attendancestore') }}" id="manualAttendanceForm">
                    @csrf

                    <div class="attendance-toolbar d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                        <div class="text-muted small">
                            {{ method_exists($students, 'total') ? $students->total() : count($students) }} student(s) found
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-success btn-sm rounded-pill px-4" onclick="setAllAttendance('present')">
                                All Present
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-4" onclick="setAllAttendance('absent')">
                                All Absent
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table lecturer-table table-sm table-hover align-middle mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th class="ps-3">Student</th>
                                    <th>Admin Number</th>
                                    <th>Day</th>
                                    <th>Subject</th>
                                    <th>Time</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($students as $s)
                                    <tr>
                                        <td class="ps-3 fw-semibold">
                                            {{ $s->student_name }}
                                            <div class="lecturer-muted small">ID {{ $s->student_id }}</div>
                                        </td>
                                        <td>{{ $s->admin_number ?? '-' }}</td>
                                        <td>{{ ucfirst($s->day) }}</td>
                                        <td>{{ $s->subject }}</td>
                                        <td>{{ $s->time }}</td>
                                        <td style="min-width: 210px;">
                                            <input type="hidden" name="attendance[{{ $s->student_id }}][class_timing_id]" value="{{ $s->class_timing_id }}">
                                            <input type="hidden" name="attendance[{{ $s->student_id }}][module_distribution_id]" value="{{ $s->module_distribution_id }}">
                                            <input type="hidden" name="attendance[{{ $s->student_id }}][week_id]" value="{{ $selectedWeek }}">
                                            <select name="attendance[{{ $s->student_id }}][status]" class="form-select">
                                                <option value="">-- Select --</option>
                                                <option value="present" {{ (string) ($s->is_present ?? '') === '1' ? 'selected' : '' }}>Present</option>
                                                <option value="absent" {{ (string) ($s->is_present ?? '') === '0' ? 'selected' : '' }}>Absent</option>
                                            </select>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <div class="lecturer-empty py-4 mx-3">
                                                <h6 class="fw-bold mb-1">All students already have attendance</h6>
                                                <p class="lecturer-muted mb-0">Anyone marked present or absent is hidden automatically from this list.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mt-3">
                        <div class="attendance-pagination">
                            @if(method_exists($students, 'links'))
                                {{ $students->onEachSide(1)->links() }}
                            @endif
                        </div>

                        @if(count($students) > 0)
                            <button type="submit" class="btn btn-primary btn-sm rounded-pill px-4 shadow-sm">
                                Save Attendance
                            </button>
                        @endif
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>

<script>
    function setAllAttendance(value) {
        const form = document.getElementById('manualAttendanceForm');
        if (!form) return;

        form.querySelectorAll('select[name*="[status]"]').forEach((select) => {
            select.value = value;
        });
    }

    function playAttendanceSuccessSound() {
        const AudioContextClass = window.AudioContext || window.webkitAudioContext;
        if (!AudioContextClass) return;

        try {
            const audioContext = new AudioContextClass();
            const oscillator = audioContext.createOscillator();
            const gainNode = audioContext.createGain();

            oscillator.type = 'sine';
            oscillator.frequency.value = 880;
            gainNode.gain.value = 0.0001;

            oscillator.connect(gainNode);
            gainNode.connect(audioContext.destination);

            const startTime = audioContext.currentTime;
            gainNode.gain.exponentialRampToValueAtTime(0.12, startTime + 0.02);
            gainNode.gain.exponentialRampToValueAtTime(0.0001, startTime + 0.32);

            oscillator.start(startTime);
            oscillator.stop(startTime + 0.34);

            oscillator.onended = () => {
                audioContext.close().catch(() => {});
            };
        } catch (error) {
            // Ignore audio failures so attendance still works normally.
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        const quickInput = document.getElementById('adminNumberSearch');
        const quickForm = document.getElementById('quickAdminMarkForm');
        const feedback = document.getElementById('adminSearchFeedback');
        const shouldPlayAttendanceSound = @json(session('attendance_sound') === 'present');
        const ready = quickForm && quickForm.dataset.ready === '1';
        const adminNumbers = quickForm ? JSON.parse(quickForm.dataset.adminNumbers || '[]').map((value) => String(value).trim()).filter(Boolean) : [];
        const adminNumberSet = new Set(adminNumbers);
        let debounceTimer = null;
        let submitting = false;

        const setFeedback = (message, tone = 'muted') => {
            if (!feedback) return;
            feedback.className = `lecturer-muted small mt-2 text-${tone === 'warning' ? 'warning' : tone === 'success' ? 'success' : tone === 'info' ? 'primary' : 'muted'}`;
            feedback.textContent = message;
        };

        const findMatch = (rawValue) => {
            const value = rawValue.trim();
            if (!value) return null;

            if (adminNumberSet.has(value)) {
                return value;
            }

            if (value.length < 3) {
                return null;
            }

            const prefixMatches = adminNumbers.filter((number) => number.startsWith(value));
            if (prefixMatches.length === 1) {
                return prefixMatches[0];
            }

            if (prefixMatches.length > 1) {
                return '__ambiguous__';
            }

            const containsMatches = adminNumbers.filter((number) => number.includes(value));
            if (containsMatches.length === 1) {
                return containsMatches[0];
            }

            if (containsMatches.length > 1) {
                return '__ambiguous__';
            }

            return null;
        };

        const tryAutoMark = (forceSubmit = false) => {
            if (!ready || !quickInput || quickInput.disabled || submitting) return;

            const value = quickInput.value.trim();

            if (!value) {
                setFeedback('Type or scan a student admin number to auto-mark present.');
                return;
            }

            const match = findMatch(value);

            if (match && match !== '__ambiguous__') {
                if (quickInput.value !== match) {
                    quickInput.value = match;
                }

                submitting = true;
                setFeedback(`Matched ${match}. Marking present now...`, 'success');
                quickForm.submit();
                return;
            }

            if (match === '__ambiguous__') {
                setFeedback('More than one student matches this number. Keep typing the full admin number.', 'warning');
                return;
            }

            if (forceSubmit) {
                submitting = true;
                setFeedback('Searching on the server...', 'info');
                quickForm.submit();
                return;
            }

            setFeedback('Keep typing or scan the full number. Search will submit once there is a clear match.', 'info');
        };

        if (quickForm) {
            quickForm.addEventListener('submit', (event) => {
                if (quickForm.dataset.ready !== '1') {
                    event.preventDefault();
                    setFeedback('Select week, course, day, and subject first.', 'warning');
                    return;
                }
            });
        }

        if (!quickInput || quickInput.disabled) return;

        quickInput.addEventListener('input', () => {
            submitting = false;
            if (debounceTimer) {
                clearTimeout(debounceTimer);
            }

            debounceTimer = setTimeout(() => tryAutoMark(false), 250);
        });

        quickInput.addEventListener('change', () => {
            tryAutoMark(true);
        });

        quickInput.addEventListener('keydown', (event) => {
            if (event.key === 'Enter') {
                event.preventDefault();
                tryAutoMark(true);
            }
        });

        quickInput.addEventListener('paste', () => {
            submitting = false;
            if (debounceTimer) {
                clearTimeout(debounceTimer);
            }

            debounceTimer = setTimeout(() => tryAutoMark(true), 100);
        });

        quickInput.focus();
        quickInput.select?.();

        if (shouldPlayAttendanceSound) {
            window.setTimeout(() => {
                playAttendanceSuccessSound();
            }, 120);
        }
    });
</script>
@endsection
