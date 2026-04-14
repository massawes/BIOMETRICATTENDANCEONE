@extends('layouts.app')

@section('content')
    <div class="container py-4">
        @include('management.partials.messages')
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <div>
                <h3 class="mb-0">Lecturers Management</h3>
                <p class="text-muted mb-0">Import, export, and manage lecturer records for your department.</p>
            </div>
            <div class="d-flex flex-wrap align-items-center gap-2">
                <button
                    type="button"
                    class="btn btn-outline-success rounded-pill px-3"
                    data-excel-export
                    data-export-url="{{ route('lecturers.export', request()->only('search')) }}"
                    data-export-filename="lecturers-export.xlsx"
                    data-export-sheet="Lecturers"
                >
                    <i class='bx bx-download me-1'></i> Export Excel
                </button>

                <x-import-actions
                    :import-url="route('spreadsheets.import', 'lecturers')"
                    import-entity="lecturers"
                    :template-fields="['lecturer_name', 'email', 'password']"
                    template-filename="lecturers-template.xlsx"
                    hint="lecturer_name, email, password"
                />

                <a href="{{ route('lecturers.create') }}" class="btn btn-primary rounded-pill px-3">Add Lecturer</a>
            </div>
        </div>
        <form method="GET" action="{{ route('lecturers.index') }}" class="row g-2 mb-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search lecturer or email" value="{{ request('search') }}">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-outline-primary">Search</button>
            </div>
        </form>
        <div class="card shadow-sm">
            <div class="card-body table-responsive">
                <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Department</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($lecturers as $lecturer)
                            <tr>
                                <td>{{ $lecturer->lecturer_name }}</td>
                                <td>{{ $lecturer->user?->email }}</td>
                                <td>{{ $lecturer->department?->department_name }}</td>
                                <td class="text-end">
                                    <a href="{{ route('lecturers.edit', $lecturer->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('lecturers.destroy', $lecturer->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this lecturer?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No lecturers found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-3">{{ $lecturers->withQueryString()->links() }}</div>
    </div>
@endsection
