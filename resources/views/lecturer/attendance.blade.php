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
                                Type or scan the full admin number. The system will search after you finish typing.
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

    @if($selectedWeek && $selectedCourse && $selectedDay && $selectedSubject)
        <div class="lecturer-card card mb-3">
            <div class="card-body p-3">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                    <div>
                        <h6 class="fw-bold mb-1 text-dark">Biometric Attendance</h6>
                        @if($activeBiometricSession)
                            <p class="text-success small mb-0">
                                Running for {{ $selectedSubject }}. <span id="biometricPresentCount">{{ $biometricPresentCount }}</span> student(s) marked present.
                            </p>
                        @else
                            <p class="text-muted small mb-0">
                                Start this session before students scan face or fingerprint.
                            </p>
                        @endif
                    </div>

                    <div class="d-flex flex-wrap gap-2">
                        @if($activeBiometricSession)
                            <form method="POST" action="{{ route('attendance.biometric.stop') }}">
                                @csrf
                                <input type="hidden" name="session_id" value="{{ $activeBiometricSession->id }}">
                                <button type="submit" class="btn btn-danger btn-sm rounded-pill px-4">
                                    Stop Biometric
                                </button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('attendance.biometric.start') }}">
                                @csrf
                                <input type="hidden" name="week_id" value="{{ $selectedWeek }}">
                                <input type="hidden" name="course_id" value="{{ $selectedCourse }}">
                                <input type="hidden" name="day" value="{{ $selectedDay }}">
                                <input type="hidden" name="subject" value="{{ $selectedSubject }}">
                                <button type="submit" class="btn btn-success btn-sm rounded-pill px-4">
                                    Start Biometric
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

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
                            <span id="attendanceStudentCount" data-count="{{ method_exists($students, 'total') ? $students->total() : count($students) }}">
                                {{ method_exists($students, 'total') ? $students->total() : count($students) }}
                            </span> student(s) found
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
                                    <tr data-student-id="{{ $s->student_id }}" data-admin-number="{{ $s->admin_number ?? '' }}">
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
                                                <h6 class="fw-bold mb-1">All visible students already have attendance</h6>
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
            const compressor = audioContext.createDynamicsCompressor();
            const masterGain = audioContext.createGain();
            compressor.threshold.value = -24;
            compressor.knee.value = 30;
            compressor.ratio.value = 8;
            compressor.attack.value = 0.003;
            compressor.release.value = 0.18;
            masterGain.gain.value = 1.8;
            masterGain.connect(compressor);
            compressor.connect(audioContext.destination);

            const playTone = (frequency, startOffset, duration) => {
                const oscillator = audioContext.createOscillator();
                const toneGain = audioContext.createGain();
                const startTime = audioContext.currentTime + startOffset;
                const endTime = startTime + duration;

                oscillator.type = 'square';
                oscillator.frequency.value = frequency;
                toneGain.gain.value = 0.0001;

                oscillator.connect(toneGain);
                toneGain.connect(masterGain);

                toneGain.gain.exponentialRampToValueAtTime(1.2, startTime + 0.02);
                toneGain.gain.exponentialRampToValueAtTime(0.0001, endTime);

                oscillator.start(startTime);
                oscillator.stop(endTime);
            };

            playTone(880, 0, 0.22);
            playTone(1320, 0.23, 0.24);
            playTone(1760, 0.48, 0.34);
            playTone(1760, 0.9, 0.18);

            window.setTimeout(() => {
                audioContext.close().catch(() => {});
            }, 1300);
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

        const decrementAttendanceCount = (removedCount) => {
            const countElement = document.getElementById('attendanceStudentCount');
            if (!countElement || removedCount < 1) return;

            const currentCount = Number(countElement.dataset.count || countElement.textContent || 0);
            const nextCount = Math.max(0, currentCount - removedCount);
            countElement.dataset.count = String(nextCount);
            countElement.textContent = String(nextCount);
        };

        const showEmptyAttendanceRowIfNeeded = () => {
            const tbody = document.querySelector('#manualAttendanceForm tbody');
            if (!tbody || tbody.querySelector('tr[data-student-id]')) return;

            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="text-center py-4">
                        <div class="lecturer-empty py-4 mx-3">
                            <h6 class="fw-bold mb-1">All visible students already have attendance</h6>
                            <p class="lecturer-muted mb-0">Anyone marked present or absent is hidden automatically from this list.</p>
                        </div>
                    </td>
                </tr>
            `;
        };

        const removeMarkedAttendanceRows = (markedStudents) => {
            if (!Array.isArray(markedStudents) || markedStudents.length === 0) return 0;

            let removedCount = 0;

            markedStudents.forEach((student) => {
                const selectors = [];

                if (student.student_id) {
                    selectors.push(`tr[data-student-id="${CSS.escape(String(student.student_id))}"]`);
                }

                if (student.admin_number) {
                    selectors.push(`tr[data-admin-number="${CSS.escape(String(student.admin_number))}"]`);
                }

                selectors.forEach((selector) => {
                    const markedRow = document.querySelector(selector);

                    if (markedRow) {
                        markedRow.remove();
                        removedCount++;
                    }
                });
            });

            decrementAttendanceCount(removedCount);
            showEmptyAttendanceRowIfNeeded();

            return removedCount;
        };

        const findExactMatch = (rawValue) => {
            const value = rawValue.trim();
            if (!value) return null;

            if (adminNumberSet.has(value)) {
                return value;
            }

            return null;
        };

        const markQuickAttendance = async (message = 'Marking present now...') => {
            if (!quickForm || !quickInput || submitting) return;

            submitting = true;
            setFeedback(message, 'success');

            try {
                const response = await fetch(quickForm.action, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: new FormData(quickForm),
                    cache: 'no-store',
                });
                const payload = await response.json().catch(() => ({}));

                if (!response.ok || !payload.ok) {
                    setFeedback(payload.message || 'Student not found for that admin number.', 'warning');
                    return;
                }

                playAttendanceSuccessSound();
                setFeedback(payload.message || 'Student marked present successfully.', 'success');

                removeMarkedAttendanceRows([payload]);

                quickInput.value = '';
                quickInput.focus();
            } catch (error) {
                setFeedback('Network is busy. Trying again may help.', 'warning');
            } finally {
                submitting = false;
            }
        };

        const tryAutoMark = (forceSubmit = false) => {
            if (!ready || !quickInput || quickInput.disabled || submitting) return;

            const value = quickInput.value.trim();

            if (!value) {
                setFeedback('Type or scan a student admin number to auto-mark present.');
                return;
            }

            const match = findExactMatch(value);

            if (match) {
                if (quickInput.value !== match) {
                    quickInput.value = match;
                }

                submitting = true;
                setFeedback(`Matched ${match}. Marking present now...`, 'success');
                submitting = false;
                markQuickAttendance(`Matched ${match}. Marking present now...`);
                return;
            }

            if (forceSubmit) {
                markQuickAttendance('Searching on the server...');
                return;
            }

            setFeedback('Student not found for that admin number.', 'warning');
        };

        if (quickForm) {
            quickForm.addEventListener('submit', (event) => {
                event.preventDefault();

                if (quickForm.dataset.ready !== '1') {
                    setFeedback('Select week, course, day, and subject first.', 'warning');
                    return;
                }

                markQuickAttendance('Searching on the server...');
            });
        }

        if (!quickInput || quickInput.disabled) return;

        quickInput.addEventListener('input', () => {
            submitting = false;
            if (debounceTimer) {
                clearTimeout(debounceTimer);
            }

            debounceTimer = setTimeout(() => tryAutoMark(false), 900);
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

        const hasActiveBiometricSession = @json((bool) $activeBiometricSession);
        const zkbioSyncUrl = @json(route('zkbio.realtime-sync'));
        const activeBiometricSessionId = @json($activeBiometricSession?->id);
        const biometricPresentCount = @json((int) $biometricPresentCount);

        if (hasActiveBiometricSession) {
            const biometricSoundKey = `attendance:biometric-present-count:${activeBiometricSessionId}`;
            const zkbioSessionSyncUrl = `${zkbioSyncUrl}?session_id=${encodeURIComponent(activeBiometricSessionId)}`;
            const previousBiometricCount = Number(window.sessionStorage.getItem(biometricSoundKey));
            const hasPreviousBiometricCount = window.sessionStorage.getItem(biometricSoundKey) !== null;

            if (!shouldPlayAttendanceSound && hasPreviousBiometricCount && biometricPresentCount > previousBiometricCount) {
                window.setTimeout(() => {
                    playAttendanceSuccessSound();
                }, 180);
            }

            window.sessionStorage.setItem(biometricSoundKey, String(biometricPresentCount));

            let syncingZkbio = false;

            const syncCurrentBiometricSession = async () => {
                if (syncingZkbio || document.hidden) return;

                syncingZkbio = true;

                try {
                    const response = await fetch(zkbioSessionSyncUrl, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        cache: 'no-store',
                    });

                    if (!response.ok) return;

                    const payload = await response.json();

                    const latestPresentCount = Number(payload.present_count ?? window.sessionStorage.getItem(biometricSoundKey) ?? biometricPresentCount);
                    const storedPresentCount = Number(window.sessionStorage.getItem(biometricSoundKey) ?? biometricPresentCount);
                    const biometricCountElement = document.getElementById('biometricPresentCount');

                    if (biometricCountElement && Number.isFinite(latestPresentCount)) {
                        biometricCountElement.textContent = String(latestPresentCount);
                    }

                    if (payload.changed && latestPresentCount > storedPresentCount) {
                        playAttendanceSuccessSound();
                    }

                    removeMarkedAttendanceRows(payload.marked_students || []);

                    window.sessionStorage.setItem(biometricSoundKey, String(latestPresentCount));
                } catch (error) {
                    // Keep manual attendance usable if ZKBio is temporarily busy.
                } finally {
                    syncingZkbio = false;
                }
            };

            window.setTimeout(syncCurrentBiometricSession, 800);
            window.setInterval(syncCurrentBiometricSession, 3000);
        }
    });
</script>
@endsection
