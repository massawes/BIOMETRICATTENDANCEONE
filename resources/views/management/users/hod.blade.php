@extends('layouts.app')

@section('content')
    <div class="container py-4">
        @include('management.partials.messages')

        <div class="card border-0 shadow-sm mb-4 no-print">
            <div class="card-body p-4">
                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                    <div>
                        <span class="badge bg-light text-dark border mb-2">HOD Control</span>
                        <h3 class="mb-1 fw-bold">Department Users</h3>
                        <p class="text-muted mb-0">Assign roles only to users in your department and manage them from one clean screen.</p>
                    </div>
                    <div class="text-lg-end">
                        <div class="small text-muted">Users in this department</div>
                        <div class="fs-3 fw-bold">{{ $assignableUsers->count() }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4 no-print">
            <div class="col-xl-8">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <h5 class="mb-1 fw-semibold">Assign User Role</h5>
                            <p class="text-muted small mb-0">Search for a user, choose a role, then save the assignment.</p>
                        </div>

                        <form method="POST" action="{{ route('hod.users.assign') }}" class="row g-3" id="hod-user-role-form">
                            @csrf

                            <div class="col-md-6">
                                <label for="user_search" class="form-label fw-semibold">Search User</label>
                                <input
                                    type="text"
                                    id="user_search"
                                    class="form-control form-control-lg"
                                    placeholder="Type user name or email"
                                    autocomplete="off"
                                >
                                <small class="text-muted">Typing here filters the users dropdown automatically.</small>
                            </div>

                            <div class="col-md-6">
                                <label for="user_id" class="form-label fw-semibold">Department Users</label>
                                <select name="user_id" id="user_id" class="form-select form-select-lg" required>
                                    <option value="">Select user</option>
                                    @foreach ($assignableUsers as $assignableUser)
                                        <option
                                            value="{{ $assignableUser->id }}"
                                            data-search="{{ strtolower($assignableUser->name . ' ' . $assignableUser->email) }}"
                                            data-role="{{ $assignableUser->role?->name }}"
                                            data-program-id="{{ $assignableUser->program_id }}"
                                            @selected(old('user_id') == $assignableUser->id)
                                        >
                                            {{ $assignableUser->name }} - {{ $assignableUser->email }}@if($assignableUser->role) ({{ $assignableUser->role->name }})@endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="role_id" class="form-label fw-semibold">Assign Role</label>
                                <select name="role_id" id="role_id" class="form-select" required>
                                    <option value="">Select role</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}" data-role-name="{{ $role->name }}" @selected(old('role_id') == $role->id)>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6" id="program-wrapper" style="display: none;">
                                <label for="program_id" class="form-label fw-semibold">Program</label>
                                <select name="program_id" id="program_id" class="form-select">
                                    <option value="">Select program for student</option>
                                    @foreach ($programs as $program)
                                        <option value="{{ $program->id }}" @selected(old('program_id') == $program->id)>
                                            {{ $program->program_name }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Required only when assigning the student role.</small>
                                @error('program_id')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6" id="admin-number-wrapper" style="display: none;">
                                <label for="admin_number" class="form-label fw-semibold">Admin Number</label>
                                <input
                                    type="text"
                                    name="admin_number"
                                    id="admin_number"
                                    class="form-control form-control-lg"
                                    placeholder="e.g. 2024147"
                                    value="{{ old('admin_number') }}"
                                >
                                <small class="text-muted">Required only when assigning the student role.</small>
                                @error('admin_number')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <div class="rounded-3 border bg-light p-3 small" id="selected-user-preview">
                                    Select a user to see the current role before assigning a new one.
                                </div>
                            </div>

                            <div class="col-12 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary px-4">Assign Role</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-xl-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <h5 class="fw-semibold mb-3">Quick Guide</h5>
                        <div class="d-flex flex-column gap-3">
                            <div class="border rounded-3 p-3">
                                <div class="fw-semibold mb-1">1. Search user</div>
                                <div class="small text-muted">Start by typing the user's name or email.</div>
                            </div>
                            <div class="border rounded-3 p-3">
                                <div class="fw-semibold mb-1">2. Choose role</div>
                                <div class="small text-muted">Pick the correct role from the dropdown list.</div>
                            </div>
                            <div class="border rounded-3 p-3">
                                <div class="fw-semibold mb-1">3. Save assignment</div>
                                <div class="small text-muted">Student, lecturer, and HOD profile records update automatically.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3 no-print">
            <div>
                <h5 class="mb-0 fw-semibold">Department Users List</h5>
                <p class="text-muted small mb-0">Only users connected to your department are shown below.</p>
            </div>
            <button type="button" class="btn btn-outline-dark rounded-pill px-3" data-report-print>
                <i class='bx bx-printer me-1'></i> Print
            </button>
        </div>

        <div class="card border-0 shadow-sm printable-area">
            <div class="card-body p-4">
                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-3 no-print">
                    <form method="GET" action="{{ route('hod.users.index') }}" class="row g-2">
                        <div class="col-md-8">
                            <input type="text" name="search" class="form-control" placeholder="Search user, email, role, program" value="{{ request('search') }}">
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-outline-primary">Search</button>
                        </div>
                    </form>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Program</th>
                                <th>Department</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                                <tr>
                                    <td class="fw-semibold">{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @php
                                            $badgeClass = match($user->role?->name) {
                                                'student' => 'bg-primary',
                                                'lecturer' => 'bg-success',
                                                'HOD' => 'bg-dark',
                                                'registrar' => 'bg-info text-dark',
                                                'rector' => 'bg-danger',
                                                'director_academic' => 'bg-warning text-dark',
                                                'quality_assurance' => 'bg-secondary',
                                                'examination_officer' => 'bg-warning text-dark',
                                                default => 'bg-light text-dark border',
                                            };
                                        @endphp
                                        <span class="badge rounded-pill {{ $badgeClass }}">{{ $user->role?->name ?? '-' }}</span>
                                    </td>
                                    <td>{{ $user->program?->program_name ?? '-' }}</td>
                                    <td>{{ $user->department?->department_name ?? $user->program?->department?->department_name ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">No department users found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $users->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>

    <script>
        const userSearchInput = document.getElementById('user_search');
        const userSelect = document.getElementById('user_id');
        const roleSelect = document.getElementById('role_id');
        const programWrapper = document.getElementById('program-wrapper');
        const programSelect = document.getElementById('program_id');
        const adminNumberWrapper = document.getElementById('admin-number-wrapper');
        const adminNumberInput = document.getElementById('admin_number');
        const previewBox = document.getElementById('selected-user-preview');

        function syncProgramVisibility() {
            const selectedRole = roleSelect.options[roleSelect.selectedIndex];
            const roleName = selectedRole ? selectedRole.dataset.roleName : '';
            const isStudent = roleName === 'student';
            const isStaff = roleName === 'lecturer' || roleName === 'HOD';

            programWrapper.style.display = isStudent ? '' : 'none';
            adminNumberWrapper.style.display = isStudent ? '' : 'none';
            programSelect.required = isStudent;
            adminNumberInput.required = isStudent;

            if (!isStudent) {
                programSelect.value = '';
                adminNumberInput.value = '';
            }
        }

        function updateSelectedUserPreview() {
            const selectedOption = userSelect.options[userSelect.selectedIndex];

            if (!selectedOption || !selectedOption.value) {
                previewBox.textContent = 'Select a user to see the current role before assigning a new one.';
                return;
            }

            const currentRole = selectedOption.dataset.role || 'No role assigned';
            previewBox.textContent = `Selected user: ${selectedOption.text}. Current role: ${currentRole}.`;
        }

        function filterUsersDropdown() {
            const term = userSearchInput.value.trim().toLowerCase();
            let firstVisibleValue = '';

            Array.from(userSelect.options).forEach((option, index) => {
                if (index === 0) {
                    option.hidden = false;
                    return;
                }

                const matches = option.dataset.search.includes(term);
                option.hidden = !matches;

                if (matches && !firstVisibleValue) {
                    firstVisibleValue = option.value;
                }
            });

            if (term && firstVisibleValue) {
                userSelect.value = firstVisibleValue;
            }

            updateSelectedUserPreview();
        }

        userSearchInput.addEventListener('input', filterUsersDropdown);
        roleSelect.addEventListener('change', syncProgramVisibility);
        userSelect.addEventListener('change', updateSelectedUserPreview);

        syncProgramVisibility();
        filterUsersDropdown();
        updateSelectedUserPreview();
    </script>
@endsection
