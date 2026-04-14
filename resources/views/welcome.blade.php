<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ATC Attendance Portal</title>

    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div class="welcome-shell">
        <header class="welcome-nav py-3">
            <div class="container-fluid px-3 px-md-5">
                <div class="d-flex align-items-center justify-content-between gap-3">
                    <a href="{{ route('home') }}" class="welcome-brand">
                        <img src="{{ asset('images/logo.png') }}" alt="ATC Logo">
                        <div>
                            <div class="welcome-brand-title h5 mb-0">ATC Attendance Portal</div>
                            <small class="text-muted">Smart attendance and academic control</small>
                        </div>
                    </a>

                    <div class="d-none d-md-flex align-items-center gap-2">
                        <span class="badge rounded-pill text-bg-light border px-3 py-2">Role-based access</span>
                        <span class="badge rounded-pill text-bg-light border px-3 py-2">Fingerprint ready</span>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <a href="{{ route('login') }}" class="btn btn-outline-primary rounded-pill px-4">Login</a>
                        <a href="{{ route('register') }}" class="btn btn-primary rounded-pill px-4">Register</a>
                    </div>
                </div>
            </div>
        </header>

        <main class="welcome-hero">
            <div class="container-fluid px-3 px-md-5">
                <div class="row g-4 align-items-stretch">
                    <div class="col-lg-7">
                        <div class="welcome-hero-card h-100 p-4 p-md-5">
                            <div class="welcome-kicker mb-4">
                                <i class='bx bx-shield-quarter'></i>
                                <span>ATC Digital Attendance System</span>
                            </div>

                            <h1 class="welcome-title mb-4">
                                A modern attendance portal for students, lecturers, and administrators.
                            </h1>

                            <p class="welcome-copy mb-4">
                                Track classes, manage biometric devices, record attendance, and view reports in one
                                professional dashboard designed for speed and clarity.
                            </p>

                            <div class="d-flex flex-wrap gap-3 welcome-cta mb-4">
                                <a href="{{ route('login') }}" class="btn btn-primary btn-lg">
                                    <i class='bx bx-log-in me-1'></i> Login
                                </a>
                                <a href="{{ route('register') }}" class="btn btn-outline-dark btn-lg">
                                    <i class='bx bx-user-plus me-1'></i> Register
                                </a>
                            </div>

                            <div class="row g-3 welcome-features">
                                <div class="col-md-4">
                                    <div class="welcome-feature p-3 h-100">
                                        <div class="d-flex align-items-start gap-3">
                                            <div class="icon"><i class='bx bx-fingerprint'></i></div>
                                            <div>
                                                <div class="fw-bold text-dark">Biometric Attendance</div>
                                                <small class="text-muted">Fast check-in with device support.</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="welcome-feature p-3 h-100">
                                        <div class="d-flex align-items-start gap-3">
                                            <div class="icon"><i class='bx bx-data'></i></div>
                                            <div>
                                                <div class="fw-bold text-dark">Live Reports</div>
                                                <small class="text-muted">Monitor attendance and analysis instantly.</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="welcome-feature p-3 h-100">
                                        <div class="d-flex align-items-start gap-3">
                                            <div class="icon"><i class='bx bx-lock-alt'></i></div>
                                            <div>
                                                <div class="fw-bold text-dark">Secure Roles</div>
                                                <small class="text-muted">Different dashboards for each user role.</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-5">
                        <div class="welcome-panel h-100 p-4 p-md-5">
                            <div class="position-relative">
                                <div class="d-flex align-items-center justify-content-between mb-4">
                                    <div>
                                        <div class="text-uppercase small fw-bold" style="letter-spacing: .14em; color: rgba(255,255,255,.75);">Portal Preview</div>
                                        <h3 class="fw-bold mb-0">Everything in one place</h3>
                                    </div>
                                    <div class="p-3 rounded-4 bg-white bg-opacity-10">
                                        <i class='bx bx-grid-alt fs-3'></i>
                                    </div>
                                </div>

                                <div class="welcome-panel-card p-4 mb-4">
                                    <div class="row g-3">
                                        <div class="col-6">
                                            <div class="welcome-stat p-3 h-100">
                                                <div class="text-white-50 small">Modules</div>
                                                <div class="welcome-stat-value">24+</div>
                                                <div class="welcome-footer-note">Manage teaching workloads</div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="welcome-stat p-3 h-100">
                                                <div class="text-white-50 small">Reports</div>
                                                <div class="welcome-stat-value">Live</div>
                                                <div class="welcome-footer-note">Filter by week and subject</div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="welcome-stat p-3">
                                                <div class="text-white-50 small mb-2">What users can do</div>
                                                <div class="d-flex flex-wrap gap-2">
                                                    <span class="badge rounded-pill text-bg-light px-3 py-2">Take Attendance</span>
                                                    <span class="badge rounded-pill text-bg-light px-3 py-2">View Timetables</span>
                                                    <span class="badge rounded-pill text-bg-light px-3 py-2">Manage Devices</span>
                                                    <span class="badge rounded-pill text-bg-light px-3 py-2">Download Analysis</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex flex-wrap gap-2">
                                    <span class="badge rounded-pill text-bg-light px-3 py-2">Fast workflow</span>
                                    <span class="badge rounded-pill text-bg-light px-3 py-2">Professional UI</span>
                                    <span class="badge rounded-pill text-bg-light px-3 py-2">Attendance system</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
