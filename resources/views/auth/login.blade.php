<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login | ATC Attendance Portal</title>

    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div class="auth-shell">
        <div class="container-fluid px-3 px-md-4 py-4 py-md-5">
            <div class="row align-items-center g-4 min-vh-100">
                <div class="col-lg-6 auth-hero">
                    <div class="auth-kicker mb-4">
                        <i class='bx bx-shield-quarter'></i>
                        <span>Secure access to the ATC portal</span>
                    </div>

                    <h1 class="auth-title">
                        Login to your dashboard and keep attendance under control.
                    </h1>

                    <p class="auth-copy">
                        Access lecturer tools, student services, reports, timetable views, and biometric device
                        management from one secure portal built for speed and professionalism.
                    </p>

                    <div class="auth-points">
                        <div class="auth-point">
                            <strong><i class='bx bx-fingerprint me-2'></i>Fast Attendance</strong>
                            <span>Capture attendance without extra friction.</span>
                        </div>
                        <div class="auth-point">
                            <strong><i class='bx bx-line-chart me-2'></i>Live Reports</strong>
                            <span>Track progress with clean analytics.</span>
                        </div>
                        <div class="auth-point">
                            <strong><i class='bx bx-lock-alt me-2'></i>Role Based</strong>
                            <span>Different dashboards for each user type.</span>
                        </div>
                        <div class="auth-point">
                            <strong><i class='bx bx-chip me-2'></i>Device Ready</strong>
                            <span>Support for biometric scanners and sensors.</span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5 offset-lg-1">
                    <div class="auth-card p-4 p-md-5">
                        <div class="auth-mini mb-4">
                            <div class="auth-brand">
                                <img src="{{ asset('images/logo.png') }}" alt="ATC Logo">
                                <div>
                                    <div class="auth-brand-title h4 mb-0">ATC Attendance Portal</div>
                                    <small class="text-muted">Sign in to continue</small>
                                </div>
                            </div>
                            <a href="{{ route('home') }}" class="btn auth-home-btn btn-sm px-3 py-2">
                                <i class='bx bx-arrow-back me-1'></i> Home
                            </a>
                        </div>

                        <h2 class="h3 fw-bold text-dark mb-2">Welcome back</h2>
                        <p class="text-muted mb-4">Use your email and password to access the portal.</p>

                        @if (session('success'))
                            <div class="alert alert-success border-0 shadow-sm rounded-4">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if (session('status'))
                            <div class="alert alert-info border-0 shadow-sm rounded-4">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}" class="mt-3">
                            @csrf

                            <div class="mb-3">
                                <label for="email" class="form-label">Email address</label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-white border-end-0 rounded-start-4">
                                        <i class='bx bx-envelope text-primary'></i>
                                    </span>
                                    <input
                                        type="email"
                                        name="email"
                                        id="email"
                                        value="{{ old('email') }}"
                                        class="form-control border-start-0 rounded-end-4"
                                        placeholder="name@college.ac.tz"
                                        required
                                        autofocus
                                    >
                                </div>
                                @error('email')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-white border-end-0 rounded-start-4">
                                        <i class='bx bx-lock-alt text-primary'></i>
                                    </span>
                                    <input
                                        type="password"
                                        name="password"
                                        id="password"
                                        class="form-control border-start-0 rounded-end-4"
                                        placeholder="Enter your password"
                                        required
                                    >
                                </div>
                                @error('password')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
                                <div class="form-check">
                                    <input type="checkbox" name="remember" id="remember" class="form-check-input">
                                    <label for="remember" class="form-check-label">Remember me</label>
                                </div>

                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="auth-soft-link">
                                        Forgot password?
                                    </a>
                                @endif
                            </div>

                            <button type="submit" class="btn btn-login btn-lg w-100 text-white">
                                <i class='bx bx-log-in-circle me-1'></i> Login
                            </button>
                        </form>

                        <div class="d-flex align-items-center gap-3 my-4">
                            <div class="flex-grow-1 border-top"></div>
                            <span class="text-muted small">or</span>
                            <div class="flex-grow-1 border-top"></div>
                        </div>

                        <div class="text-center">
                            <p class="text-muted mb-2">Need an account?</p>
                            <a href="{{ route('register') }}" class="btn btn-outline-primary rounded-pill px-4">
                                Create account
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
