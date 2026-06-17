<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@hasSection('page-title')@yield('page-title') — @endif ATC Attendance</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet"></noscript>
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="ega-app-body">
@php
    $user          = auth()->user();
    $roleName      = strtolower($user->role->name ?? '');
    $isStudent     = $roleName === 'student';
    $isLecturer    = $roleName === 'lecturer';
    $isHod         = $roleName === 'hod';
    $isRegistrar   = $roleName === 'registrar';
    $isExamOfficer = $roleName === 'examination_officer';
    $isQa          = $roleName === 'quality_assurance';
    $isDirector    = $roleName === 'director_academic';
    $isRector      = $roleName === 'rector';

    // Group open states
    $hodReportOpen    = request()->routeIs('hodreport','hod.analysis');
    $hodModuleOpen    = request()->routeIs('moduledistribute.create','moduledistribute.index');
    $hodDataOpen      = request()->routeIs('students.*','hod.users.*','programs.*','modules.*',
                            'lecturers.*','roles.*','weeks.*','class-timings.*','role-permissions.*');
    $lecTeachOpen     = request()->routeIs('lecturerreport','lecturerclasstiming','lecturerclasses',
                            'students.*','attendanceindex','attendance.records.*');
    $lecAnalysisOpen  = request()->routeIs('lecturerireport');
    $lecDevicesOpen   = request()->routeIs('devices.*');
    $stuAcademicsOpen = request()->routeIs('studentdashboard','studentmodules','studenttimetable');

    $isActive = fn(...$p) => collect($p)->contains(fn($x) => request()->routeIs($x));
    $ac       = fn(...$p) => $isActive(...$p) ? 'active' : '';

    $roleLabels = [
        'student'             => ['label' => 'Mwanafunzi',     'icon' => 'bx-graduation'],
        'lecturer'            => ['label' => 'Mhadhiri',        'icon' => 'bx-chalkboard'],
        'hod'                 => ['label' => 'Mkuu wa Idara',   'icon' => 'bx-briefcase-alt-2'],
        'registrar'           => ['label' => 'Msajili',         'icon' => 'bx-id-card'],
        'examination_officer' => ['label' => 'Afisa Mitihani',  'icon' => 'bx-check-shield'],
        'quality_assurance'   => ['label' => 'Ubora (QA)',      'icon' => 'bx-badge-check'],
        'director_academic'   => ['label' => 'Mkurugenzi',      'icon' => 'bx-building'],
        'rector'              => ['label' => 'Rector',          'icon' => 'bx-crown'],
    ];
    $ri = $roleLabels[$roleName] ?? ['label' => ucfirst($roleName), 'icon' => 'bx-user'];
@endphp

<div id="sidebarOverlay" class="sidebar-overlay" onclick="closeSidebar()"></div>

<div class="ega-shell">

{{-- ═══════════════════════════════════════════════════════ SIDEBAR ════════ --}}
<aside id="appSidebar" class="ega-sidebar" aria-label="Navigation">

    {{-- Brand --}}
    <div class="ega-sidebar-brand">
        <a href="{{ route($isStudent ? 'studentdashboard' : ($isLecturer ? 'lecturerdashboard' : ($isHod ? 'hoddashboard' : 'home'))) }}" class="ega-sidebar-brand-inner" style="text-decoration:none">
            <div class="ega-sidebar-logo">
                <img src="{{ asset('images/logo.png') }}" alt="ATC">
            </div>
            <div>
                <div class="ega-sidebar-brand-name">ATC System</div>
                <div class="ega-sidebar-brand-sub">Attendance Portal</div>
            </div>
        </a>
        <button class="ega-sidebar-close d-md-none" onclick="closeSidebar()" aria-label="Close">
            <i class="bx bx-x"></i>
        </button>
    </div>

    {{-- Role pill --}}
    <div class="ega-sidebar-role">
        <i class='bx {{ $ri["icon"] }}'></i>
        {{ $ri['label'] }}
    </div>

    {{-- Navigation --}}
    <nav class="ega-sidebar-nav">
        <ul class="ega-nav-list">

        {{-- ████████ HOD ████████████████████████████████████████████████████ --}}
        @if ($isHod)
            <li><div class="ega-nav-section">Muhtasari</div></li>
            <li>
                <a href="{{ route('hoddashboard') }}" class="ega-nav-link {{ $ac('hoddashboard') }}">
                    <i class='bx bx-tachometer'></i><span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('analytics.dashboard') }}" class="ega-nav-link {{ $ac('analytics.dashboard') }}">
                    <i class='bx bx-bar-chart-alt-2'></i><span>Takwimu</span>
                </a>
            </li>

            <li><div class="ega-nav-section">Ripoti</div></li>
            <li>
                <details class="ega-nav-group {{ $hodReportOpen ? 'is-open' : '' }}" @if($hodReportOpen) open @endif>
                    <summary>
                        <div class="ega-nav-group-toggle">
                            <i class='bx bx-file-blank'></i><span>Ripoti</span>
                            <i class='bx bx-chevron-down ega-caret ms-auto'></i>
                        </div>
                    </summary>
                    <div class="ega-nav-sub">
                        <a href="{{ route('hodreport') }}" class="ega-nav-sub-link {{ $ac('hodreport') }}">
                            <i class='bx bx-list-ul'></i> Moduli za Wahadhiri
                        </a>
                        <a href="{{ route('hod.analysis') }}" class="ega-nav-sub-link {{ $ac('hod.analysis') }}">
                            <i class='bx bx-line-chart'></i> Uchambuzi
                        </a>
                    </div>
                </details>
            </li>

            <li><div class="ega-nav-section">Moduli</div></li>
            <li>
                <details class="ega-nav-group {{ $hodModuleOpen ? 'is-open' : '' }}" @if($hodModuleOpen) open @endif>
                    <summary>
                        <div class="ega-nav-group-toggle">
                            <i class='bx bx-book-open'></i><span>Ugawaji wa Moduli</span>
                            <i class='bx bx-chevron-down ega-caret ms-auto'></i>
                        </div>
                    </summary>
                    <div class="ega-nav-sub">
                        <a href="{{ route('moduledistribute.create') }}" class="ega-nav-sub-link {{ $ac('moduledistribute.create') }}">
                            <i class='bx bx-plus-circle'></i> Gawa Moduli
                        </a>
                        <a href="{{ route('moduledistribute.index') }}" class="ega-nav-sub-link {{ $ac('moduledistribute.index') }}">
                            <i class='bx bx-git-branch'></i> Mgawanyo
                        </a>
                    </div>
                </details>
            </li>

            <li><div class="ega-nav-section">Usimamizi wa Data</div></li>
            <li>
                <details class="ega-nav-group {{ $hodDataOpen ? 'is-open' : '' }}" @if($hodDataOpen) open @endif>
                    <summary>
                        <div class="ega-nav-group-toggle">
                            <i class='bx bx-data'></i><span>Uingizaji Data</span>
                            <i class='bx bx-chevron-down ega-caret ms-auto'></i>
                        </div>
                    </summary>
                    <div class="ega-nav-sub">
                        <a href="{{ route('students.index') }}"      class="ega-nav-sub-link {{ $ac('students.*') }}">
                            <i class='bx bx-graduation'></i> Wanafunzi
                        </a>
                        <a href="{{ route('hod.users.index') }}"     class="ega-nav-sub-link {{ $ac('hod.users.*') }}">
                            <i class='bx bx-group'></i> Watumiaji
                        </a>
                        <a href="{{ route('programs.index') }}"      class="ega-nav-sub-link {{ $ac('programs.*') }}">
                            <i class='bx bx-bookmark'></i> Programu
                        </a>
                        <a href="{{ route('modules.index') }}"       class="ega-nav-sub-link {{ $ac('modules.*') }}">
                            <i class='bx bx-book'></i> Moduli
                        </a>
                        <a href="{{ route('lecturers.index') }}"     class="ega-nav-sub-link {{ $ac('lecturers.*') }}">
                            <i class='bx bx-chalkboard'></i> Wahadhiri
                        </a>
                        <a href="{{ route('roles.index') }}"         class="ega-nav-sub-link {{ $ac('roles.*') }}">
                            <i class='bx bx-shield'></i> Majukumu
                        </a>
                        <a href="{{ route('weeks.index') }}"         class="ega-nav-sub-link {{ $ac('weeks.*') }}">
                            <i class='bx bx-calendar-week'></i> Wiki
                        </a>
                        <a href="{{ route('class-timings.index') }}" class="ega-nav-sub-link {{ $ac('class-timings.*') }}">
                            <i class='bx bx-time'></i> Muda wa Darasa
                        </a>
                        <a href="{{ route('role-permissions.index') }}" class="ega-nav-sub-link {{ $ac('role-permissions.*') }}">
                            <i class='bx bx-lock-open-alt'></i> Ruhusa
                        </a>
                    </div>
                </details>
            </li>
        @endif

        {{-- ████████ LECTURER ███████████████████████████████████████████████ --}}
        @if ($isLecturer)
            <li><div class="ega-nav-section">Muhtasari</div></li>
            <li>
                <a href="{{ route('lecturerdashboard') }}" class="ega-nav-link {{ $ac('lecturerdashboard') }}">
                    <i class='bx bx-tachometer'></i><span>Dashboard</span>
                </a>
            </li>

            <li><div class="ega-nav-section">Ufundishaji</div></li>
            <li>
                <details class="ega-nav-group {{ $lecTeachOpen ? 'is-open' : '' }}" @if($lecTeachOpen) open @endif>
                    <summary>
                        <div class="ega-nav-group-toggle">
                            <i class='bx bx-chalkboard'></i><span>Ufundishaji & Mahudhurio</span>
                            <i class='bx bx-chevron-down ega-caret ms-auto'></i>
                        </div>
                    </summary>
                    <div class="ega-nav-sub">
                        <a href="{{ route('lecturerreport') }}"         class="ega-nav-sub-link {{ $ac('lecturerreport') }}">
                            <i class='bx bx-book-open'></i> Moduli Zangu
                        </a>
                        <a href="{{ route('lecturerclasstiming') }}"    class="ega-nav-sub-link {{ $ac('lecturerclasstiming') }}">
                            <i class='bx bx-calendar-check'></i> Ratiba ya Madarasa
                        </a>
                        <a href="{{ route('lecturerclasses') }}"        class="ega-nav-sub-link {{ $ac('lecturerclasses') }}">
                            <i class='bx bx-door-open'></i> Madarasa
                        </a>
                        <a href="{{ route('students.index') }}"         class="ega-nav-sub-link {{ $ac('students.*') }}">
                            <i class='bx bx-graduation'></i> Wanafunzi
                        </a>
                        <a href="{{ route('attendanceindex') }}"        class="ega-nav-sub-link {{ $ac('attendanceindex') }}">
                            <i class='bx bx-user-check'></i> Mahudhurio ya Mkono
                        </a>
                        <a href="{{ route('attendance.records.index') }}" class="ega-nav-sub-link {{ $ac('attendance.records.*') }}">
                            <i class='bx bx-spreadsheet'></i> Rekodi za Mahudhurio
                        </a>
                    </div>
                </details>
            </li>

            <li><div class="ega-nav-section">Uchambuzi & Vifaa</div></li>
            <li>
                <details class="ega-nav-group {{ $lecAnalysisOpen ? 'is-open' : '' }}" @if($lecAnalysisOpen) open @endif>
                    <summary>
                        <div class="ega-nav-group-toggle">
                            <i class='bx bx-line-chart'></i><span>Uchambuzi</span>
                            <i class='bx bx-chevron-down ega-caret ms-auto'></i>
                        </div>
                    </summary>
                    <div class="ega-nav-sub">
                        <a href="{{ route('lecturerireport') }}" class="ega-nav-sub-link {{ $ac('lecturerireport') }}">
                            <i class='bx bx-pie-chart-alt-2'></i> Uchambuzi wa Mahudhurio
                        </a>
                    </div>
                </details>
            </li>
            <li>
                <details class="ega-nav-group {{ $lecDevicesOpen ? 'is-open' : '' }}" @if($lecDevicesOpen) open @endif>
                    <summary>
                        <div class="ega-nav-group-toggle">
                            <i class='bx bx-devices'></i><span>Usimamizi wa Vifaa</span>
                            <i class='bx bx-chevron-down ega-caret ms-auto'></i>
                        </div>
                    </summary>
                    <div class="ega-nav-sub">
                        <a href="{{ route('devices.index') }}" class="ega-nav-sub-link {{ $ac('devices.*') }}">
                            <i class='bx bx-fingerprint'></i> Vifaa vya Biometric
                        </a>
                    </div>
                </details>
            </li>
        @endif

        {{-- ████████ STUDENT ████████████████████████████████████████████████ --}}
        @if ($isStudent)
            <li><div class="ega-nav-section">Muhtasari</div></li>
            <li>
                <a href="{{ route('studentdashboard') }}" class="ega-nav-link {{ $ac('studentdashboard') }}">
                    <i class='bx bx-tachometer'></i><span>Dashboard</span>
                </a>
            </li>

            <li><div class="ega-nav-section">Masomo Yangu</div></li>
            <li>
                <a href="{{ route('studentmodules') }}" class="ega-nav-link {{ $ac('studentmodules') }}">
                    <i class='bx bx-book'></i><span>Moduli Zangu</span>
                </a>
            </li>
            <li>
                <a href="{{ route('studenttimetable') }}" class="ega-nav-link {{ $ac('studenttimetable') }}">
                    <i class='bx bx-calendar'></i><span>Ratiba Yangu</span>
                </a>
            </li>

            <li><div class="ega-nav-section">Akaunti</div></li>
            <li>
                <a href="{{ route('profile.edit') }}" class="ega-nav-link {{ $ac('profile.edit') }}">
                    <i class='bx bx-user-circle'></i><span>Wasifu Wangu</span>
                </a>
            </li>
        @endif

        {{-- ████████ REGISTRAR ██████████████████████████████████████████████ --}}
        @if ($isRegistrar)
            <li><div class="ega-nav-section">Muhtasari</div></li>
            <li>
                <a href="{{ route('registrardashboard') }}" class="ega-nav-link {{ $ac('registrardashboard') }}">
                    <i class='bx bx-tachometer'></i><span>Dashboard</span>
                </a>
            </li>
            <li><div class="ega-nav-section">Ripoti & Takwimu</div></li>
            <li>
                <a href="{{ route('management.attendance-report') }}" class="ega-nav-link {{ $ac('management.attendance-report') }}">
                    <i class='bx bx-file-blank'></i><span>Ripoti ya Mahudhurio</span>
                </a>
            </li>
            <li>
                <a href="{{ route('analytics.dashboard') }}" class="ega-nav-link {{ $ac('analytics.dashboard') }}">
                    <i class='bx bx-bar-chart-alt-2'></i><span>Takwimu</span>
                </a>
            </li>
            <li><div class="ega-nav-section">Akaunti</div></li>
            <li>
                <a href="{{ route('profile.edit') }}" class="ega-nav-link {{ $ac('profile.edit') }}">
                    <i class='bx bx-user-circle'></i><span>Wasifu Wangu</span>
                </a>
            </li>
        @endif

        {{-- ████████ EXAMINATION OFFICER ████████████████████████████████████ --}}
        @if ($isExamOfficer)
            <li><div class="ega-nav-section">Muhtasari</div></li>
            <li>
                <a href="{{ route('examdashboard') }}" class="ega-nav-link {{ $ac('examdashboard') }}">
                    <i class='bx bx-tachometer'></i><span>Dashboard</span>
                </a>
            </li>
            <li><div class="ega-nav-section">Mitihani</div></li>
            <li>
                <a href="{{ route('exam.eligibility') }}" class="ega-nav-link {{ $ac('exam.eligibility') }}">
                    <i class='bx bx-check-shield'></i><span>Orodha ya Kufuzu</span>
                </a>
            </li>
            <li>
                <a href="{{ route('exam.timetable') }}" class="ega-nav-link {{ $ac('exam.timetable') }}">
                    <i class='bx bx-calendar-alt'></i><span>Ratiba ya Mitihani</span>
                </a>
            </li>
            <li><div class="ega-nav-section">Ripoti & Watumiaji</div></li>
            <li>
                <a href="{{ route('exam.reports') }}" class="ega-nav-link {{ $ac('exam.reports') }}">
                    <i class='bx bx-file-blank'></i><span>Ripoti za Mahudhurio</span>
                </a>
            </li>
            <li>
                <a href="{{ route('management.attendance-report') }}" class="ega-nav-link {{ $ac('management.attendance-report') }}">
                    <i class='bx bx-clipboard'></i><span>Ripoti ya Mahudhurio</span>
                </a>
            </li>
            <li>
                <a href="{{ route('analytics.dashboard') }}" class="ega-nav-link {{ $ac('analytics.dashboard') }}">
                    <i class='bx bx-bar-chart-alt-2'></i><span>Takwimu</span>
                </a>
            </li>
            <li>
                <a href="{{ route('users.index') }}" class="ega-nav-link {{ $ac('users.*') }}">
                    <i class='bx bx-group'></i><span>Simamia Watumiaji</span>
                </a>
            </li>
            <li><div class="ega-nav-section">Akaunti</div></li>
            <li>
                <a href="{{ route('profile.edit') }}" class="ega-nav-link {{ $ac('profile.edit') }}">
                    <i class='bx bx-user-circle'></i><span>Wasifu Wangu</span>
                </a>
            </li>
        @endif

        {{-- ████████ QUALITY ASSURANCE ██████████████████████████████████████ --}}
        @if ($isQa)
            <li><div class="ega-nav-section">Muhtasari</div></li>
            <li>
                <a href="{{ route('qadashboard') }}" class="ega-nav-link {{ $ac('qadashboard') }}">
                    <i class='bx bx-tachometer'></i><span>Dashboard</span>
                </a>
            </li>
            <li><div class="ega-nav-section">Udhibiti wa Ubora</div></li>
            <li>
                <a href="{{ route('management.attendance-report') }}" class="ega-nav-link {{ $ac('management.attendance-report') }}">
                    <i class='bx bx-file-blank'></i><span>Ripoti ya Mahudhurio</span>
                </a>
            </li>
            <li>
                <a href="{{ route('analytics.dashboard') }}" class="ega-nav-link {{ $ac('analytics.dashboard') }}">
                    <i class='bx bx-bar-chart-alt-2'></i><span>Takwimu</span>
                </a>
            </li>
            <li><div class="ega-nav-section">Akaunti</div></li>
            <li>
                <a href="{{ route('profile.edit') }}" class="ega-nav-link {{ $ac('profile.edit') }}">
                    <i class='bx bx-user-circle'></i><span>Wasifu Wangu</span>
                </a>
            </li>
        @endif

        {{-- ████████ DIRECTOR ACADEMIC ██████████████████████████████████████ --}}
        @if ($isDirector)
            <li><div class="ega-nav-section">Muhtasari</div></li>
            <li>
                <a href="{{ route('directordashboard') }}" class="ega-nav-link {{ $ac('directordashboard') }}">
                    <i class='bx bx-tachometer'></i><span>Dashboard</span>
                </a>
            </li>
            <li><div class="ega-nav-section">Uongozi wa Kitaaluma</div></li>
            <li>
                <a href="{{ route('director.faculties') }}" class="ega-nav-link {{ $ac('director.faculties') }}">
                    <i class='bx bx-building'></i><span>Vitivo na Idara</span>
                </a>
            </li>
            <li>
                <a href="{{ route('departments.index') }}" class="ega-nav-link {{ $ac('departments.*') }}">
                    <i class='bx bx-category'></i><span>Idara</span>
                </a>
            </li>
            <li>
                <a href="{{ route('hods.index') }}" class="ega-nav-link {{ $ac('hods.*') }}">
                    <i class='bx bx-briefcase-alt-2'></i><span>Wakuu wa Idara</span>
                </a>
            </li>
            <li>
                <a href="{{ route('analytics.dashboard') }}" class="ega-nav-link {{ $ac('analytics.dashboard') }}">
                    <i class='bx bx-bar-chart-alt-2'></i><span>Takwimu</span>
                </a>
            </li>
            <li><div class="ega-nav-section">Akaunti</div></li>
            <li>
                <a href="{{ route('profile.edit') }}" class="ega-nav-link {{ $ac('profile.edit') }}">
                    <i class='bx bx-user-circle'></i><span>Wasifu Wangu</span>
                </a>
            </li>
        @endif

        {{-- ████████ RECTOR █████████████████████████████████████████████████ --}}
        @if ($isRector)
            <li><div class="ega-nav-section">Muhtasari</div></li>
            <li>
                <a href="{{ route('rectordashboard') }}" class="ega-nav-link {{ $ac('rectordashboard') }}">
                    <i class='bx bx-tachometer'></i><span>Dashboard</span>
                </a>
            </li>
            <li><div class="ega-nav-section">Uongozi Mkuu</div></li>
            <li>
                <a href="{{ route('management.attendance-report') }}" class="ega-nav-link {{ $ac('management.attendance-report') }}">
                    <i class='bx bx-file-blank'></i><span>Ripoti ya Mahudhurio</span>
                </a>
            </li>
            <li>
                <a href="{{ route('analytics.dashboard') }}" class="ega-nav-link {{ $ac('analytics.dashboard') }}">
                    <i class='bx bx-bar-chart-alt-2'></i><span>Takwimu</span>
                </a>
            </li>
            <li><div class="ega-nav-section">Akaunti</div></li>
            <li>
                <a href="{{ route('profile.edit') }}" class="ega-nav-link {{ $ac('profile.edit') }}">
                    <i class='bx bx-user-circle'></i><span>Wasifu Wangu</span>
                </a>
            </li>
        @endif

        </ul>
    </nav>

    {{-- Sidebar footer / user --}}
    <div class="ega-sidebar-footer">
        <div class="ega-sidebar-user">
            <div class="ega-sidebar-user-avatar">{{ strtoupper(substr($user->name, 0, 2)) }}</div>
            <div class="ega-sidebar-user-info">
                <div class="ega-sidebar-user-name">{{ $user->name }}</div>
                <div class="ega-sidebar-user-role">{{ $ri['label'] }}</div>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="ms-auto">
                @csrf
                <button type="submit" class="ega-sidebar-logout" title="Toka Mfumoni">
                    <i class='bx bx-log-out'></i>
                </button>
            </form>
        </div>
        <div class="ega-sidebar-footer-note">
            <i class='bx bx-shield-check'></i> eGA Tanzania · ATC &copy; {{ date('Y') }}
        </div>
    </div>

</aside>
{{-- ══════════════════════════════════════════════════ END SIDEBAR ══════════ --}}

{{-- Main --}}
<div class="ega-main">
    @include('layouts.navigation')

    <div class="ega-content">
        {{-- Flash messages --}}
        @if (session('success'))
            <div class="ega-alert ega-alert-success alert alert-dismissible" role="alert">
                <i class='bx bx-check-circle'></i>
                <span>{{ session('success') }}</span>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="ega-alert ega-alert-danger alert alert-dismissible" role="alert">
                <i class='bx bx-error-circle'></i>
                <span>{{ session('error') }}</span>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @yield('content')
    </div>

    {{-- Footer --}}
    <footer class="ega-app-footer">
        <span>ATC Student Attendance Management System</span>
        <span class="ega-footer-divider">·</span>
        <span><i class='bx bx-shield-check'></i> Inafuata viwango vya eGA Tanzania</span>
        <span class="ega-footer-divider">·</span>
        <span>&copy; {{ date('Y') }} ATC. Haki zote zimehifadhiwa.</span>
    </footer>
</div>

</div>{{-- .ega-shell --}}

<script>
    function openSidebar()  {
        document.getElementById('appSidebar').classList.add('open');
        document.getElementById('sidebarOverlay').classList.add('show');
        document.body.style.overflow = 'hidden';
    }
    function closeSidebar() {
        document.getElementById('appSidebar').classList.remove('open');
        document.getElementById('sidebarOverlay').classList.remove('show');
        document.body.style.overflow = '';
    }
</script>
@stack('scripts')
</body>
</html>
