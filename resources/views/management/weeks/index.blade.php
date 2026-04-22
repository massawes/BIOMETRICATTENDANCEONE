@extends('layouts.app')

@section('content')
    <div class="container py-4">
        @include('management.partials.messages')
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <div>
                <h3 class="mb-0">Weeks Management</h3>
                <p class="text-muted mb-0">Import, export, and manage week records.</p>
            </div>
            <div class="d-flex flex-wrap align-items-center gap-2">
                <button
                    type="button"
                    class="btn btn-outline-success rounded-pill px-3"
                    data-excel-export
                    data-export-url="{{ route('weeks.export', request()->only('search')) }}"
                    data-export-filename="weeks-export.xlsx"
                    data-export-sheet="Weeks"
                >
                    <i class='bx bx-download me-1'></i> Export Excel
                </button>

                <x-import-actions
                    :import-url="route('spreadsheets.import', 'weeks')"
                    import-entity="weeks"
                    :template-fields="['week_name']"
                    template-filename="weeks-template.xlsx"
                    hint="week_name"
                />

                <a href="{{ route('weeks.create') }}" class="btn btn-primary rounded-pill px-3">Add Week</a>
            </div>
        </div>
        <form method="GET" action="{{ route('weeks.index') }}" class="row g-2 mb-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search week name" value="{{ request('search') }}">
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
                            <th>Week Name</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($weeks as $week)
                            <tr>
                                <td>{{ $week->week_name }}</td>
                                <td class="text-end">
                                    <a href="{{ route('weeks.edit', $week->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('weeks.destroy', $week->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this week?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center">No weeks found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-3">{{ $weeks->withQueryString()->links() }}</div>
    </div>
@endsection
