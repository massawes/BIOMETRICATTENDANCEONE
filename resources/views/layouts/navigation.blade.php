@php
    $user     = auth()->user();
    $role     = $user->role->name ?? 'User';
    $roleMap  = [
        'student'              => ['label' => 'Mwanafunzi',    'icon' => 'bx-graduation',      'color' => 'role-student'],
        'lecturer'             => ['label' => 'Mhadhiri',      'icon' => 'bx-chalkboard',      'color' => 'role-lecturer'],
        'hod'                  => ['label' => 'Mkuu wa Idara', 'icon' => 'bx-briefcase-alt-2', 'color' => 'role-hod'],
        'registrar'            => ['label' => 'Msajili',       'icon' => 'bx-id-card',         'color' => 'role-registrar'],
        'examination_officer'  => ['label' => 'Afisa Mitihani','icon' => 'bx-check-shield',    'color' => 'role-exam'],
        'quality_assurance'    => ['label' => 'Ubora (QA)',    'icon' => 'bx-badge-check',     'color' => 'role-qa'],
        'director_academic'    => ['label' => 'Mkurugenzi',    'icon' => 'bx-building',        'color' => 'role-director'],
        'rector'               => ['label' => 'Rector',        'icon' => 'bx-crown',           'color' => 'role-rector'],
    ];
    $roleInfo  = $roleMap[strtolower($role)] ?? ['label' => $role, 'icon' => 'bx-user', 'color' => 'role-default'];
    $pageTitle = View::hasSection('page-title') ? View::getSection('page-title') : 'Dashboard';
@endphp

<header class="ega-topnav">
    <div class="ega-topnav-inner">

        {{-- Left: hamburger + page title --}}
        <div class="ega-topnav-left">
            <button class="ega-hamburger d-md-none" onclick="openSidebar()" type="button" aria-label="Open menu">
                <i class="bx bx-menu"></i>
            </button>
            <div class="ega-topnav-title">
                <span class="ega-topnav-page">{{ $pageTitle }}</span>
                <span class="ega-topnav-sub d-none d-xl-inline">
                    Mfumo wa Usimamizi wa Mahudhurio · ATC
                </span>
            </div>
        </div>

        {{-- Centre: search (desktop only) --}}
        <div class="ega-topnav-centre d-none d-lg-flex">
            <div class="ega-search">
                <i class='bx bx-search ega-search-icon'></i>
                <input type="text" class="ega-search-input" placeholder="Tafuta wanafunzi, moduli, ripoti…" aria-label="Tafuta">
            </div>
        </div>

        {{-- Right: date + notifications + role + user --}}
        <div class="ega-topnav-right">

            {{-- Current date (desktop) --}}
            <div class="ega-topnav-date d-none d-xl-flex">
                <i class='bx bx-calendar'></i>
                <span>{{ now()->translatedFormat('d M Y') }}</span>
            </div>

            {{-- Notification bell --}}
            <button class="ega-topnav-icon-btn" type="button" title="Arifa" aria-label="Arifa">
                <i class='bx bx-bell'></i>
                <span class="ega-badge-dot"></span>
            </button>

            {{-- Role badge --}}
            <div class="ega-role-badge {{ $roleInfo['color'] }}">
                <i class='bx {{ $roleInfo['icon'] }}'></i>
                <span class="d-none d-md-inline">{{ $roleInfo['label'] }}</span>
            </div>

            {{-- User dropdown --}}
            <div class="dropdown">
                <button class="ega-user-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="ega-user-avatar">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <span class="ega-user-name d-none d-md-inline">{{ Str::words($user->name, 1, '') }}</span>
                    <i class='bx bx-chevron-down ega-user-caret'></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end ega-dropdown">
                    <li class="ega-dropdown-header">
                        <div class="ega-dropdown-avatar">{{ strtoupper(substr($user->name, 0, 2)) }}</div>
                        <div>
                            <div class="ega-dropdown-name">{{ $user->name }}</div>
                            <div class="ega-dropdown-role">{{ $roleInfo['label'] }}</div>
                        </div>
                    </li>
                    <li><hr class="dropdown-divider my-1"></li>
                    <li>
                        <a class="dropdown-item ega-dropdown-item" href="{{ route('profile.edit') }}">
                            <i class='bx bx-user-circle'></i> Wasifu Wangu
                        </a>
                    </li>
                    <li><hr class="dropdown-divider my-1"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item ega-dropdown-item ega-dropdown-logout">
                                <i class='bx bx-log-out'></i> Toka Mfumoni
                            </button>
                        </form>
                    </li>
                </ul>
            </div>

        </div>
    </div>

    {{-- EGA primary accent strip --}}
    <div class="ega-topnav-strip"></div>
</header>
