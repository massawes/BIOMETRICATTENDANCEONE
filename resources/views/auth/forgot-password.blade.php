<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Forgot Password | ATC Attendance Portal</title>

    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div class="auth-shell">
        <div class="container-fluid px-3 px-md-4 py-4 py-md-5">
            <div class="row align-items-center g-4 min-vh-100">
                <div class="col-lg-6 auth-hero">
                    <div class="auth-kicker mb-4">
                        <i class='bx bx-key'></i>
                        <span>Password Recovery Center</span>
                    </div>

                    <h1 class="auth-title">
                        Reset your password in a clean, secure, and guided flow.
                    </h1>

                    <p class="auth-copy">
                        Enter your email and we will send a reset link. The process is designed to be simple,
                        safe, and consistent with the rest of the portal.
                    </p>

                    <div class="auth-points">
                        <div class="auth-point">
                            <strong><i class='bx bx-envelope me-2'></i>Email Reset</strong>
                            <span>We send a secure link to your inbox.</span>
                        </div>
                        <div class="auth-point">
                            <strong><i class='bx bx-shield-quarter me-2'></i>Secure Flow</strong>
                            <span>Reset requests stay protected and scoped.</span>
                        </div>
                        <div class="auth-point">
                            <strong><i class='bx bx-time-five me-2'></i>Fast Recovery</strong>
                            <span>Get back to your work without hassle.</span>
                        </div>
                        <div class="auth-point">
                            <strong><i class='bx bx-user-circle me-2'></i>Portal Ready</strong>
                            <span>Return quickly to login once you reset.</span>
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
                                    <small class="text-muted">Forgot password</small>
                                </div>
                            </div>
                            <a href="{{ route('login') }}" class="btn auth-home-btn btn-sm px-3 py-2">
                                <i class='bx bx-arrow-back me-1'></i> Login
                            </a>
                        </div>

                        <div class="mb-4">
                            <h2 class="h3 fw-bold text-dark mb-2">Reset your password</h2>
                            <p class="text-muted mb-0">
                                Enter the email linked to your account and we'll send you a reset link.
                            </p>
                        </div>

                        @if (session('status'))
                            <div class="alert alert-success border-0 shadow-sm rounded-4">
                                {{ session('status') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger border-0 shadow-sm rounded-4">
                                Please check the email address and try again.
                            </div>
                        @endif

                        <form method="POST" action="{{ route('password.email') }}" class="mt-3">
                            @csrf

                            <div class="mb-4">
                                <label for="email" class="form-label">Email Address</label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-white border-end-0 rounded-start-4">
                                        <i class='bx bx-envelope text-primary'></i>
                                    </span>
                                    <input
                                        type="email"
                                        name="email"
                                        id="email"
                                        value="{{ old('email') }}"
                                        class="form-control border-start-0 rounded-end-4 @error('email') is-invalid @enderror"
                                        placeholder="name@college.ac.tz"
                                        required
                                        autofocus
                                    >
                                </div>
                                @error('email')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-login btn-lg w-100 text-white mb-3">
                                <i class='bx bx-send me-1'></i> Send Reset Link
                            </button>

                            <div class="d-flex align-items-center gap-3 my-4">
                                <div class="flex-grow-1 border-top"></div>
                                <span class="text-muted small">or</span>
                                <div class="flex-grow-1 border-top"></div>
                            </div>

                            <div class="text-center">
                                <p class="text-muted mb-2">Remembered your password?</p>
                                <a href="{{ route('login') }}" class="btn btn-outline-primary rounded-pill px-4">
                                    Back to Login
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
