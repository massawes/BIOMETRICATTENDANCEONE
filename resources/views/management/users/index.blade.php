@extends('layouts.app')

@section('content')
    <div class="container py-4">
        @include('management.partials.messages')
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3 no-print">
            <div>
                <h3 class="mb-0">Users Management</h3>
                <p class="text-muted mb-0">Manage users, roles, programs, and departments.</p>
            </div>
            <div class="d-flex flex-wrap align-items-center gap-2">
                <button
                    type="button"
                    class="btn btn-outline-success rounded-pill px-3"
                    data-excel-export
                    data-export-url="{{ route('users.export', request()->only('search')) }}"
                    data-export-filename="users-export.xlsx"
                    data-export-sheet="Users"
                >
                    <i class='bx bx-download me-1'></i> Export Excel
                </button>

                <x-import-actions
                    :import-url="route('spreadsheets.import', 'users')"
                    import-entity="users"
                    :template-fields="['name', 'email', 'password', 'role_name', 'program_name', 'program_id', 'department_name', 'department_id', 'admin_number']"
                    template-filename="users-template.xlsx"
                    template-label="Download Format File"
                    hint="name, email, password, role_name, program_name/program_id, department_name/department_id"
                />

                <button type="button" class="btn btn-outline-dark rounded-pill px-3" data-report-print>
                    <i class='bx bx-printer me-1'></i> Print
                </button>
                <a href="{{ route('users.create') }}" class="btn btn-primary rounded-pill px-3">Add User</a>
            </div>
        </div>
        <form method="GET" action="{{ route('users.index') }}" class="row g-2 mb-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search name, email, role, program" value="{{ request('search') }}">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-outline-primary">Search</button>
            </div>
        </form>
        <div class="card shadow-sm printable-area">
            <div class="card-body table-responsive">
                <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Program</th>
                            <th>Department</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->role?->name }}</td>
                                <td>{{ $user->program?->program_name }}</td>
                                <td>{{ $user->department?->department_name ?? '-' }}</td>
                                <td class="text-end">
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this user?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No users found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-3">{{ $users->withQueryString()->links() }}</div>
    </div>
@endsection
