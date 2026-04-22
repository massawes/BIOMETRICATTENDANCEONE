@extends('layouts.app')

@section('content')
    <div class="container py-4">
        @include('management.partials.messages')
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <div>
                <h3 class="mb-0">Modules Management</h3>
                <p class="text-muted mb-0">Import, export, and manage module records for your department.</p>
            </div>
            <div class="d-flex flex-wrap align-items-center gap-2">
                <button
                    type="button"
                    class="btn btn-outline-success rounded-pill px-3"
                    data-excel-export
                    data-export-url="{{ route('modules.export', request()->only('search')) }}"
                    data-export-filename="modules-export.xlsx"
                    data-export-sheet="Modules"
                >
                    <i class='bx bx-download me-1'></i> Export Excel
                </button>

                <x-import-actions
                    :import-url="route('spreadsheets.import', 'modules')"
                    import-entity="modules"
                    :template-fields="['module_name', 'module_code', 'module_credit', 'semester', 'nta_level', 'program_name', 'program_id']"
                    template-filename="modules-template.xlsx"
                    hint="module_name, module_code, module_credit, semester, nta_level, program_name/program_id"
                />

                <a href="{{ route('modules.create') }}" class="btn btn-primary rounded-pill px-3">Add Module</a>
            </div>
        </div>
        <form method="GET" action="{{ route('modules.index') }}" class="row g-2 mb-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search module, code, semester, program" value="{{ request('search') }}">
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
                            <th>Code</th>
                            <th>Credit</th>
                            <th>Semester</th>
                            <th>NTA Level</th>
                            <th>Program</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($modules as $module)
                            <tr>
                                <td>{{ $module->module_name }}</td>
                                <td>{{ $module->module_code }}</td>
                                <td>{{ $module->module_credit }}</td>
                                <td>{{ $module->semester }}</td>
                                <td>{{ $module->nta_level }}</td>
                                <td>{{ $module->program?->program_name }}</td>
                                <td class="text-end">
                                    <a href="{{ route('modules.edit', $module->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('modules.destroy', $module->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this module?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No modules found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-3">{{ $modules->withQueryString()->links() }}</div>
    </div>
@endsection
