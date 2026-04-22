<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
</head>

<body class="bg-light">
    @php
        $user = auth()->user();
        $roleName = strtolower($user->role->name ?? '');
        $isStudent = $roleName === 'student';
        $isLecturer = $roleName === 'lecturer';
        $isHod = $roleName === 'hod';
        $isRegistrar = $roleName === 'registrar';
        $isExamOfficer = $roleName === 'examination_officer';
        $isQa = $roleName === 'quality_assurance';
        $isDirector = $roleName === 'director_academic';
        $isRector = $roleName === 'rector';
        $hodReportOpen = request()->routeIs('hodreport') || request()->routeIs('hod.analysis');
        $hodModuleOpen = request()->routeIs('moduledistribute.create') || request()->routeIs('moduledistribute.index');
        $hodDataEntryOpen = request()->routeIs('students.*')
            || request()->routeIs('hod.users.*')
            || request()->routeIs('programs.*')
            || request()->routeIs('modules.*')
            || request()->routeIs('lecturers.*')
            || request()->routeIs('roles.*')
            || request()->routeIs('weeks.*')
            || request()->routeIs('class-timings.*');
        $lecturerTeachingOpen = request()->routeIs('lecturerreport')
            || request()->routeIs('lecturerclasstiming')
            || request()->routeIs('lecturerclasses')
            || request()->routeIs('students.*')
            || request()->routeIs('attendanceindex')
            || request()->routeIs('attendance.records.*');
        $lecturerAnalysisOpen = request()->routeIs('lecturerireport');
        $lecturerDevicesOpen = request()->routeIs('devices.*');
        $studentAcademicsOpen = request()->routeIs('studentdashboard')
            || request()->routeIs('studentmodules')
            || request()->routeIs('studenttimetable');
        $studentSupportOpen = request()->routeIs('profile.edit');
        $registrarOpen = request()->routeIs('registrardashboard')
            || request()->routeIs('management.attendance-report')
            || request()->routeIs('analytics.dashboard')
            || request()->routeIs('profile.edit');
        $examOfficerOpen = request()->routeIs('examdashboard')
            || request()->routeIs('exam.eligibility')
            || request()->routeIs('exam.reports')
            || request()->routeIs('exam.timetable')
            || request()->routeIs('users.*')
            || request()->routeIs('management.attendance-report')
            || request()->routeIs('analytics.dashboard')
            || request()->routeIs('profile.edit');
        $qaOpen = request()->routeIs('qadashboard')
            || request()->routeIs('management.attendance-report')
            || request()->routeIs('analytics.dashboard')
            || request()->routeIs('profile.edit');
        $directorOpen = request()->routeIs('directordashboard')
            || request()->routeIs('director.faculties')
            || request()->routeIs('departments.*')
            || request()->routeIs('hods.*')
            || request()->routeIs('analytics.dashboard')
            || request()->routeIs('profile.edit');
        $rectorOpen = request()->routeIs('rectordashboard')
            || request()->routeIs('management.attendance-report')
            || request()->routeIs('analytics.dashboard')
            || request()->routeIs('profile.edit');

        $isActiveRoute = function (...$patterns) {
            foreach ($patterns as $pattern) {
                if (request()->routeIs($pattern)) {
                    return true;
                }
            }

            return false;
        };

        $activeClass = function (...$patterns) use ($isActiveRoute) {
            return $isActiveRoute(...$patterns) ? 'active' : '';
        };
    @endphp
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 bg-slate text-white min-vh-100 p-3 shadow-lg">
                <h4 class="text-center mb-4">Dashboard</h4>

                <ul class="nav flex-column sidebar-menu">
                    @if ($isHod)
                        <li class="nav-item mb-2">
                            <a href="{{ route('hoddashboard') }}"
                                class="nav-link {{ request()->routeIs('hoddashboard') ? 'active' : '' }}">
                                <span>Dashboard</span>
                            </a>
                        </li>

                        <li class="nav-item mb-2">
                            <details class="sidebar-group {{ $hodReportOpen ? 'is-open' : '' }}" @if($hodReportOpen) open @endif>
                                <summary class="sidebar-toggle">
                                    <span>Reports</span>
                                    <i class='bx bx-chevron-down sidebar-caret'></i>
                                </summary>
                                <div class="sidebar-group-menu">
                                    <a href="{{ route('hodreport') }}"
                                        class="nav-link sidebar-sublink {{ request()->routeIs('hodreport') ? 'active' : '' }}">
                                        <span>Lecturer Modules</span>
                                    </a>
                                    <a href="{{ route('hod.analysis') }}"
                                        class="nav-link sidebar-sublink {{ request()->routeIs('hod.analysis') ? 'active' : '' }}">
                                        <span>Analysis</span>
                                    </a>
                                </div>
                            </details>
                        </li>

                        <li class="nav-item mb-2">
                            <details class="sidebar-group {{ $hodModuleOpen ? 'is-open' : '' }}" @if($hodModuleOpen) open @endif>
                                <summary class="sidebar-toggle">
                                    <span>Module Assign</span>
                                    <i class='bx bx-chevron-down sidebar-caret'></i>
                                </summary>
                                <div class="sidebar-group-menu">
                                    <a href="{{ route('moduledistribute.create') }}"
                                        class="nav-link sidebar-sublink {{ request()->routeIs('moduledistribute.create') ? 'active' : '' }}">
                                        <span>Assign Modules</span>
                                    </a>
                                    <a href="{{ route('moduledistribute.index') }}"
                                        class="nav-link sidebar-sublink {{ request()->routeIs('moduledistribute.index') ? 'active' : '' }}">
                                        <span>Module Distribution</span>
                                    </a>
                                </div>
                            </details>
                        </li>

                        <li class="nav-item mb-2">
                            <details class="sidebar-group {{ $hodDataEntryOpen ? 'is-open' : '' }}" @if($hodDataEntryOpen) open @endif>
                                <summary class="sidebar-toggle">
                                    <span>Data Entry</span>
                                    <i class='bx bx-chevron-down sidebar-caret'></i>
                                </summary>
                                <div class="sidebar-group-menu">
                                    <a href="{{ route('students.index') }}"
                                        class="nav-link sidebar-sublink {{ request()->routeIs('students.*') ? 'active' : '' }}">
                                        <span>Students</span>
                                    </a>
                                    <a href="{{ route('hod.users.index') }}"
                                        class="nav-link sidebar-sublink {{ request()->routeIs('hod.users.*') ? 'active' : '' }}">
                                        <span>Users</span>
                                    </a>
                                    <a href="{{ route('programs.index') }}"
                                        class="nav-link sidebar-sublink {{ request()->routeIs('programs.*') ? 'active' : '' }}">
                                        <span>Programs</span>
                                    </a>
                                    <a href="{{ route('modules.index') }}"
                                        class="nav-link sidebar-sublink {{ request()->routeIs('modules.*') ? 'active' : '' }}">
                                        <span>Modules</span>
                                    </a>
                                    <a href="{{ route('lecturers.index') }}"
                                        class="nav-link sidebar-sublink {{ request()->routeIs('lecturers.*') ? 'active' : '' }}">
                                        <span>Lecturers</span>
                                    </a>
                                    <a href="{{ route('roles.index') }}"
                                        class="nav-link sidebar-sublink {{ request()->routeIs('roles.*') ? 'active' : '' }}">
                                        <span>Roles</span>
                                    </a>
                                    <a href="{{ route('weeks.index') }}"
                                        class="nav-link sidebar-sublink {{ request()->routeIs('weeks.*') ? 'active' : '' }}">
                                        <span>Weeks</span>
                                    </a>
                                    <a href="{{ route('class-timings.index') }}"
                                        class="nav-link sidebar-sublink {{ request()->routeIs('class-timings.*') ? 'active' : '' }}">
                                        <span>Class Timings</span>
                                    </a>
                                </div>
                            </details>
                        </li>

                        <li class="nav-item mb-2">
                            <a href="{{ route('analytics.dashboard') }}"
                                class="nav-link {{ request()->routeIs('analytics.dashboard') ? 'active' : '' }}">
                                <span>Analytics Dashboard</span>
                            </a>
                        </li>
                    @endif

                    @if ($isLecturer)
                        <li class="nav-item mb-2">
                            <a href="{{ route('lecturerdashboard') }}" class="nav-link {{ $activeClass('lecturerdashboard') }}">Dashboard</a>
                        </li>

                        <li class="nav-item mb-2">
                            <details class="sidebar-group {{ $lecturerTeachingOpen ? 'is-open' : '' }}" @if($lecturerTeachingOpen) open @endif>
                                <summary class="sidebar-toggle">
                                    <span>Teaching & Attendance</span>
                                    <i class='bx bx-chevron-down sidebar-caret'></i>
                                </summary>
                                <div class="sidebar-group-menu">
                                    <a href="{{ route('lecturerreport') }}" class="nav-link sidebar-sublink {{ $activeClass('lecturerreport') }}">
                                        <span>My Modules</span>
                                    </a>
                                    <a href="{{ route('lecturerclasstiming') }}" class="nav-link sidebar-sublink {{ $activeClass('lecturerclasstiming') }}">
                                        <span>Class Timetable</span>
                                    </a>
                                    <a href="{{ route('lecturerclasses') }}" class="nav-link sidebar-sublink {{ $activeClass('lecturerclasses') }}">
                                        <span>Classes</span>
                                    </a>
                                    <a href="{{ route('students.index') }}" class="nav-link sidebar-sublink {{ $activeClass('students.*') }}">
                                        <span>Students</span>
                                    </a>
                                    <a href="{{ route('attendanceindex') }}" class="nav-link sidebar-sublink {{ $activeClass('attendanceindex') }}">
                                        <span>Manual Attendance</span>
                                    </a>
                                    <a href="{{ route('attendance.records.index') }}" class="nav-link sidebar-sublink {{ $activeClass('attendance.records.*') }}">
                                        <span>Attendance CRUD</span>
                                    </a>
                                </div>
                            </details>
                        </li>

                        <li class="nav-item mb-2">
                            <details class="sidebar-group {{ $lecturerAnalysisOpen ? 'is-open' : '' }}" @if($lecturerAnalysisOpen) open @endif>
                                <summary class="sidebar-toggle">
                                    <span>Analysis</span>
                                    <i class='bx bx-chevron-down sidebar-caret'></i>
                                </summary>
                                <div class="sidebar-group-menu">
                                    <a href="{{ route('lecturerireport') }}" class="nav-link sidebar-sublink {{ $activeClass('lecturerireport') }}">
                                        <span>Attendance Analysis</span>
                                    </a>
                                </div>
                            </details>
                        </li>

                        <li class="nav-item mb-2">
                            <details class="sidebar-group {{ $lecturerDevicesOpen ? 'is-open' : '' }}" @if($lecturerDevicesOpen) open @endif>
                                <summary class="sidebar-toggle">
                                    <span>Device Management</span>
                                    <i class='bx bx-chevron-down sidebar-caret'></i>
                                </summary>
                                <div class="sidebar-group-menu">
                                    <a href="{{ route('devices.index') }}" class="nav-link sidebar-sublink {{ $activeClass('devices.*') }}">
                                        <span>Manage Devices</span>
                                    </a>
                                </div>
                            </details>
                        </li>
                    @endif

                    @if ($isStudent)
                        <li class="nav-item mb-2">
                            <a href="{{ route('studentdashboard') }}" class="nav-link {{ $activeClass('studentdashboard') }}">Dashboard</a>
                        </li>

                        <li class="nav-item mb-2">
                            <details class="sidebar-group {{ $studentAcademicsOpen ? 'is-open' : '' }}" @if($studentAcademicsOpen) open @endif>
                                <summary class="sidebar-toggle">
                                    <span>Academic Life</span>
                                    <i class='bx bx-chevron-down sidebar-caret'></i>
                                </summary>
                                <div class="sidebar-group-menu">
                                    <a href="{{ route('studentmodules') }}" class="nav-link sidebar-sublink {{ $activeClass('studentmodules') }}">
                                        <span>My Modules</span>
                                    </a>
                                    <a href="{{ route('studenttimetable') }}" class="nav-link sidebar-sublink {{ $activeClass('studenttimetable') }}">
                                        <span>My Timetable</span>
                                    </a>
                                </div>
                            </details>
                        </li>

                        <li class="nav-item mb-2">
                            <details class="sidebar-group {{ $studentSupportOpen ? 'is-open' : '' }}" @if($studentSupportOpen) open @endif>
                                <summary class="sidebar-toggle">
                                    <span>Profile & Support</span>
                                    <i class='bx bx-chevron-down sidebar-caret'></i>
                                </summary>
                                <div class="sidebar-group-menu">
                                    <a href="{{ route('profile.edit') }}" class="nav-link sidebar-sublink {{ $activeClass('profile.edit') }}">
                                        <span>My Profile</span>
                                    </a>
                                </div>
                            </details>
                        </li>
                    @endif

                    @if ($isRegistrar)
                        <li class="nav-item mb-2">
                            <details class="sidebar-group {{ $registrarOpen ? 'is-open' : '' }}" @if($registrarOpen) open @endif>
                                <summary class="sidebar-toggle">
                                    <span>Registrar Hub</span>
                                    <i class='bx bx-chevron-down sidebar-caret'></i>
                                </summary>
                                <div class="sidebar-group-menu">
                                    <a href="{{ route('registrardashboard') }}" class="nav-link sidebar-sublink {{ $activeClass('registrardashboard') }}">
                                        <span>Dashboard</span>
                                    </a>
                                    <a href="{{ route('management.attendance-report') }}" class="nav-link sidebar-sublink {{ $activeClass('management.attendance-report') }}">
                                        <span>Attendance Report</span>
                                    </a>
                    
                                    <a href="{{ route('profile.edit') }}" class="nav-link sidebar-sublink {{ $activeClass('profile.edit') }}">
                                        <span>My Profile</span>
                                    </a>
                                </div>
                            </details>
                        </li>
                    @endif

                    @if ($isExamOfficer)
                        <li class="nav-item mb-2">
                            <details class="sidebar-group {{ $examOfficerOpen ? 'is-open' : '' }}" @if($examOfficerOpen) open @endif>
                                <summary class="sidebar-toggle">
                                    <span>Exams & Compliance</span>
                                    <i class='bx bx-chevron-down sidebar-caret'></i>
                                </summary>
                                <div class="sidebar-group-menu">
                                    <a href="{{ route('examdashboard') }}" class="nav-link sidebar-sublink {{ $activeClass('examdashboard') }}">
                                        <span>Dashboard</span>
                                    </a>
                                    <a href="{{ route('exam.eligibility') }}" class="nav-link sidebar-sublink {{ $activeClass('exam.eligibility') }}">
                                        <span>Eligibility List</span>
                                    </a>
                                    <a href="{{ route('exam.reports') }}" class="nav-link sidebar-sublink {{ $activeClass('exam.reports') }}">
                                        <span>Attendance Reports</span>
                                    </a>
                                    <a href="{{ route('exam.timetable') }}" class="nav-link sidebar-sublink {{ $activeClass('exam.timetable') }}">
                                        <span>Exam Timetable</span>
                                    </a>
                                    <a href="{{ route('users.index') }}" class="nav-link sidebar-sublink {{ $activeClass('users.*') }}">
                                        <span>Manage Users</span>
                                    </a>
                                    <a href="{{ route('management.attendance-report') }}" class="nav-link sidebar-sublink {{ $activeClass('management.attendance-report') }}">
                                        <span>Attendance Report</span>
                                    </a>
                                    <a href="{{ route('profile.edit') }}" class="nav-link sidebar-sublink {{ $activeClass('profile.edit') }}">
                                        <span>My Profile</span>
                                    </a>
                                </div>
                            </details>
                        </li>
                    @endif

                    @if ($isQa)
                        <li class="nav-item mb-2">
                            <details class="sidebar-group {{ $qaOpen ? 'is-open' : '' }}" @if($qaOpen) open @endif>
                                <summary class="sidebar-toggle">
                                    <span>Quality Control</span>
                                    <i class='bx bx-chevron-down sidebar-caret'></i>
                                </summary>
                                <div class="sidebar-group-menu">
                                    <a href="{{ route('qadashboard') }}" class="nav-link sidebar-sublink {{ $activeClass('qadashboard') }}">
                                        <span>Dashboard</span>
                                    </a>
                                    <a href="{{ route('management.attendance-report') }}" class="nav-link sidebar-sublink {{ $activeClass('management.attendance-report') }}">
                                        <span>Attendance Report</span>
                                    </a>
                                    <a href="{{ route('profile.edit') }}" class="nav-link sidebar-sublink {{ $activeClass('profile.edit') }}">
                                        <span>My Profile</span>
                                    </a>
                                </div>
                            </details>
                        </li>
                    @endif

                    @if ($isDirector)
                        <li class="nav-item mb-2">
                            <details class="sidebar-group {{ $directorOpen ? 'is-open' : '' }}" @if($directorOpen) open @endif>
                                <summary class="sidebar-toggle">
                                    <span>Academic Leadership</span>
                                    <i class='bx bx-chevron-down sidebar-caret'></i>
                                </summary>
                                <div class="sidebar-group-menu">
                                    <a href="{{ route('directordashboard') }}" class="nav-link sidebar-sublink {{ $activeClass('directordashboard') }}">
                                        <span>Dashboard</span>
                                    </a>
                                    <a href="{{ route('director.faculties') }}" class="nav-link sidebar-sublink {{ $activeClass('director.faculties') }}">
                                        <span>Faculties & Depts</span>
                                    </a>
                                    <a href="{{ route('departments.index') }}" class="nav-link sidebar-sublink {{ $activeClass('departments.*') }}">
                                        <span>Departments</span>
                                    </a>
                                    <a href="{{ route('hods.index') }}" class="nav-link sidebar-sublink {{ $activeClass('hods.*') }}">
                                        <span>HODs</span>
                                    </a>
                                    <a href="{{ route('profile.edit') }}" class="nav-link sidebar-sublink {{ $activeClass('profile.edit') }}">
                                        <span>My Profile</span>
                                    </a>
                                </div>
                            </details>
                        </li>
                    @endif

                    @if ($isRector)
                        <li class="nav-item mb-2">
                            <details class="sidebar-group {{ $rectorOpen ? 'is-open' : '' }}" @if($rectorOpen) open @endif>
                                <summary class="sidebar-toggle">
                                    <span>Executive Oversight</span>
                                    <i class='bx bx-chevron-down sidebar-caret'></i>
                                </summary>
                                <div class="sidebar-group-menu">
                                    <a href="{{ route('rectordashboard') }}" class="nav-link sidebar-sublink {{ $activeClass('rectordashboard') }}">
                                        <span>Dashboard</span>
                                    </a>
                                    <a href="{{ route('management.attendance-report') }}" class="nav-link sidebar-sublink {{ $activeClass('management.attendance-report') }}">
                                        <span>Attendance Report</span>
                                    </a>
                                    <a href="{{ route('profile.edit') }}" class="nav-link sidebar-sublink {{ $activeClass('profile.edit') }}">
                                        <span>My Profile</span>
                                    </a>
                                </div>
                            </details>
                        </li>
                    @endif

                    @if (!$isHod && in_array($roleName, ['hod', 'registrar', 'examination_officer', 'quality_assurance', 'director_academic', 'rector'], true))
                        <li class="nav-item mb-2"><a href="{{ route('analytics.dashboard') }}" class="nav-link {{ $activeClass('analytics.dashboard') }}">Analytics Dashboard</a></li>
                    @endif
                </ul>
            </div>

            <div class="col-md-10">
                @include('layouts.navigation')

                <div class="px-3">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    @auth
        @if (request()->routeIs(
            'studentdashboard',
            'lecturerdashboard',
            'hoddashboard',
            'registrardashboard',
            'examdashboard',
            'qadashboard',
            'directordashboard',
            'rectordashboard',
            'analytics.dashboard',
            'management.attendance-report',
            'attendanceindex',
            'attendance.records.*'
        ))
            <script>
                (() => {
                    const key = `zkbio:last-sync:${location.pathname}`;
                    const url = @json(route('zkbio.realtime-sync'));

                    async function syncZkbio() {
                        try {
                            const response = await fetch(url, {
                                headers: {
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest',
                                },
                                cache: 'no-store',
                            });

                            if (!response.ok) {
                                return;
                            }

                            const payload = await response.json();
                            const latest = Number(payload.latest_transaction_id || 0);
                            const previous = Number(sessionStorage.getItem(key) || 0);

                            if (!previous) {
                                sessionStorage.setItem(key, latest);
                                return;
                            }

                            if (latest > previous) {
                                sessionStorage.setItem(key, latest);
                                location.reload();
                            }
                        } catch (error) {
                            // Keep the page usable if the sync endpoint is temporarily unavailable.
                        }
                    }

                    syncZkbio();
                    setInterval(syncZkbio, 5000);
                })();
            </script>
        @endif
    @endauth
</body>

</html>
