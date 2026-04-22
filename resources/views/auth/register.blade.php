<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register | ATC Attendance Portal</title>

    <link rel="preload" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet"></noscript>
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div class="auth-shell">
        <div class="container-fluid px-3 px-md-4 py-4 py-md-5">
            <div class="row align-items-center g-4 min-vh-100">
                <div class="col-lg-6 auth-hero">
                    <div class="auth-kicker mb-4">
                        <i class='bx bx-user-plus'></i>
                        <span>Create your ATC portal account</span>
                    </div>

                    <h1 class="auth-title">
                        Join the attendance system with a clean registration flow.
                    </h1>

                    <p class="auth-copy">
                        Create a secure account for students, lecturers, or HODs and immediately connect to the
                        right dashboard, timetable, and reporting tools.
                    </p>

                    <div class="auth-points">
                        <div class="auth-point">
                            <strong><i class='bx bx-id-card me-2'></i>Role Based</strong>
                            <span>Choose the right role for your account.</span>
                        </div>
                        <div class="auth-point">
                            <strong><i class='bx bx-buildings me-2'></i>Scoped Setup</strong>
                            <span>Program or department fields appear automatically.</span>
                        </div>
                        <div class="auth-point">
                            <strong><i class='bx bx-shield-quarter me-2'></i>Secure Start</strong>
                            <span>Passwords are protected from the first step.</span>
                        </div>
                        <div class="auth-point">
                            <strong><i class='bx bx-log-in-circle me-2'></i>Instant Access</strong>
                            <span>Register once, then log in immediately.</span>
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
                                    <small class="text-muted">Create account</small>
                                </div>
                            </div>
                            <a href="{{ route('login') }}" class="btn auth-home-btn btn-sm px-3 py-2">
                                <i class='bx bx-arrow-back me-1'></i> Login
                            </a>
                        </div>

                        <h2 class="h3 fw-bold text-dark mb-2">Create Account</h2>
                        <p class="text-muted mb-4">Fill in the details below to get started.</p>

                        @if ($errors->any())
                            <div class="alert alert-danger border-0 shadow-sm rounded-4">
                                Please review the form and try again.
                            </div>
                        @endif

                        <form method="POST" action="{{ route('register') }}" id="register-form">
                            @csrf

                            <div class="mb-3">
                                <label for="name" class="form-label">Full Name</label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-white border-end-0 rounded-start-4">
                                        <i class='bx bx-user text-primary'></i>
                                    </span>
                                    <input
                                        type="text"
                                        name="name"
                                        id="name"
                                        value="{{ old('name') }}"
                                        class="form-control border-start-0 rounded-end-4 @error('name') is-invalid @enderror"
                                        placeholder="Enter full name"
                                        required
                                    >
                                </div>
                                @error('name')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
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
                                        class="form-control border-start-0 rounded-end-4 @error('password') is-invalid @enderror"
                                        placeholder="Create a password"
                                        required
                                    >
                                </div>
                                @error('password')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-white border-end-0 rounded-start-4">
                                        <i class='bx bx-lock-open text-primary'></i>
                                    </span>
                                    <input
                                        type="password"
                                        name="password_confirmation"
                                        id="password_confirmation"
                                        class="form-control border-start-0 rounded-end-4"
                                        placeholder="Confirm password"
                                        required
                                    >
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="role" class="form-label">Select Role</label>
                                <select name="role_id" id="role" class="form-select form-select-lg @error('role_id') is-invalid @enderror" required>
                                    <option value="">-- Select Role --</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}" data-role-name="{{ $role->name }}" @selected(old('role_id') == $role->id)>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('role_id')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3" id="program-field" style="display:none;">
                                <label for="program_id" class="form-label">Select Program</label>
                                <select name="program_id" id="program_id" class="form-select form-select-lg">
                                    <option value="">-- Select Program --</option>
                                    @foreach ($programs as $program)
                                        <option value="{{ $program->id }}" @selected(old('program_id') == $program->id)>
                                            {{ $program->program_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3" id="admin-number-field" style="display:none;">
                                <label for="admin_number" class="form-label">Admin Number</label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-white border-end-0 rounded-start-4">
                                        <i class='bx bx-id-card text-primary'></i>
                                    </span>
                                    <input
                                        type="text"
                                        name="admin_number"
                                        id="admin_number"
                                        value="{{ old('admin_number') }}"
                                        class="form-control border-start-0 rounded-end-4 @error('admin_number') is-invalid @enderror"
                                        placeholder="e.g. 2024147"
                                    >
                                </div>
                                <small class="text-muted">Required for student accounts.</small>
                                @error('admin_number')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3" id="department-field" style="display:none;">
                                <label for="department_id" class="form-label">Select Department</label>
                                <select name="department_id" id="department_id" class="form-select form-select-lg">
                                    <option value="">-- Select Department --</option>
                                    @foreach ($departments as $department)
                                        <option value="{{ $department->id }}" @selected(old('department_id') == $department->id)>
                                            {{ $department->department_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <button type="submit" class="btn btn-login btn-lg w-100 text-white mt-2">
                                <i class='bx bx-user-check me-1'></i> Register
                            </button>
                        </form>

                        <div class="d-flex align-items-center gap-3 my-4">
                            <div class="flex-grow-1 border-top"></div>
                            <span class="text-muted small">or</span>
                            <div class="flex-grow-1 border-top"></div>
                        </div>

                        <div class="text-center">
                            <p class="text-muted mb-2">Already have an account?</p>
                            <a href="{{ route('login') }}" class="btn btn-outline-primary rounded-pill px-4">
                                Back to Login
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const role = document.getElementById('role');
        const program = document.getElementById('program-field');
        const adminNumber = document.getElementById('admin-number-field');
        const department = document.getElementById('department-field');

        function toggleFields() {
            const selectedRole = role.options[role.selectedIndex];
            const roleName = selectedRole ? selectedRole.dataset.roleName : '';
            const isStudent = roleName === 'student';
            const isStaff = roleName === 'lecturer' || roleName === 'HOD';

            program.required = isStudent;
            adminNumber.required = isStudent;
            department.required = isStaff;

            if (isStudent) {
                program.style.display = 'block';
                adminNumber.style.display = 'block';
                department.style.display = 'none';
            } else if (isStaff) {
                program.style.display = 'none';
                adminNumber.style.display = 'none';
                department.style.display = 'block';
            } else {
                program.style.display = 'none';
                adminNumber.style.display = 'none';
                department.style.display = 'none';
            }
        }

        role.addEventListener('change', toggleFields);
        toggleFields();
    </script>
</body>
</html>
