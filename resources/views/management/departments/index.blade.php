@extends('layouts.app')

@section('content')
    <div class="container py-4">
        @include('management.partials.messages')
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <div>
                <h3 class="mb-0">Departments Management</h3>
                <p class="text-muted mb-0">Import, export, and manage department records.</p>
            </div>
            <div class="d-flex flex-wrap align-items-center gap-2">
                <button
                    type="button"
                    class="btn btn-outline-success rounded-pill px-3"
                    data-excel-export
                    data-export-url="{{ route('departments.export', request()->only('search')) }}"
                    data-export-filename="departments-export.xlsx"
                    data-export-sheet="Departments"
                >
                    <i class='bx bx-download me-1'></i> Export Excel
                </button>

                <x-import-actions
                    :import-url="route('spreadsheets.import', 'departments')"
                    import-entity="departments"
                    :template-fields="['department_name']"
                    template-filename="departments-template.xlsx"
                    template-label="Download Format File"
                    hint="department_name"
                />

                <a href="{{ route('departments.create') }}" class="btn btn-primary rounded-pill px-3">Add Department</a>
            </div>
        </div>
        <form method="GET" action="{{ route('departments.index') }}" class="row g-2 mb-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search department name" value="{{ request('search') }}">
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
                            <th>Department Name</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($departments as $department)
                            <tr>
                                <td>{{ $department->department_name }}</td>
                                <td class="text-end">
                                    <a href="{{ route('departments.edit', $department->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('departments.destroy', $department->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this department?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center">No departments found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-3">{{ $departments->withQueryString()->links() }}</div>
    </div>
@endsection
