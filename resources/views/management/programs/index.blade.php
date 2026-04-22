@extends('layouts.app')

@section('content')
    <div class="container py-4">
        @include('management.partials.messages')
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <div>
                <h3 class="mb-0">Programs Management</h3>
                <p class="text-muted mb-0">Import, export, and manage program records for your department.</p>
            </div>
            <div class="d-flex flex-wrap align-items-center gap-2">
                <button
                    type="button"
                    class="btn btn-outline-success rounded-pill px-3"
                    data-excel-export
                    data-export-url="{{ route('programs.export', request()->only('search')) }}"
                    data-export-filename="programs-export.xlsx"
                    data-export-sheet="Programs"
                >
                    <i class='bx bx-download me-1'></i> Export Excel
                </button>

                <x-import-actions
                    :import-url="route('spreadsheets.import', 'programs')"
                    import-entity="programs"
                    :template-fields="['program_name']"
                    template-filename="programs-template.xlsx"
                    hint="program_name"
                />

                <a href="{{ route('programs.create') }}" class="btn btn-primary rounded-pill px-3">Add Program</a>
            </div>
        </div>
        <form method="GET" action="{{ route('programs.index') }}" class="row g-2 mb-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search program name" value="{{ request('search') }}">
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
                            <th>Program</th>
                            <th>Department</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($programs as $program)
                            <tr>
                                <td>{{ $program->program_name }}</td>
                                <td>{{ $program->department?->department_name }}</td>
                                <td class="text-end">
                                    <a href="{{ route('programs.edit', $program->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('programs.destroy', $program->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this program?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">No programs found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-3">{{ $programs->withQueryString()->links() }}</div>
    </div>
@endsection
