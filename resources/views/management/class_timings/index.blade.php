@extends('layouts.app')

@section('content')
    <div class="container py-4">
        @include('management.partials.messages')
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <div>
                <h3 class="mb-0">Class Timings Management</h3>
                <p class="text-muted mb-0">Import, export, and manage timetable records.</p>
            </div>
            <div class="d-flex flex-wrap align-items-center gap-2">
                <button
                    type="button"
                    class="btn btn-outline-success rounded-pill px-3"
                    data-excel-export
                    data-export-url="{{ route('class-timings.export', request()->only('search')) }}"
                    data-export-filename="class-timings-export.xlsx"
                    data-export-sheet="Class Timings"
                >
                    <i class='bx bx-download me-1'></i> Export Excel
                </button>

                <x-import-actions
                    :import-url="route('spreadsheets.import', 'class_timings')"
                    import-entity="class_timings"
                    :template-fields="['module_distribution_id', 'module_code', 'academic_year', 'day', 'time', 'room', 'week_name', 'week_id']"
                    template-filename="class-timings-template.xlsx"
                    hint="module_distribution_id/module_code, academic_year, day, time, room, week_name/week_id"
                />

                <a href="{{ route('class-timings.create') }}" class="btn btn-primary rounded-pill px-3">Add Timetable</a>
            </div>
        </div>
        <form method="GET" action="{{ route('class-timings.index') }}" class="row g-2 mb-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search day, time, room, module" value="{{ request('search') }}">
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
                            <th>Module</th>
                            <th>Day</th>
                            <th>Time</th>
                            <th>Room</th>
                            <th>Week</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($classTimings as $classTiming)
                            <tr>
                                <td>{{ $classTiming->moduleDistribution?->module?->module_name }}</td>
                                <td>{{ $classTiming->day }}</td>
                                <td>{{ $classTiming->time }}</td>
                                <td>{{ $classTiming->room }}</td>
                                <td>{{ $classTiming->week?->week_name ?? '-' }}</td>
                                <td class="text-end">
                                    <a href="{{ route('class-timings.edit', $classTiming->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('class-timings.destroy', $classTiming->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this timetable entry?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No class timings found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-3">{{ $classTimings->withQueryString()->links() }}</div>
    </div>
@endsection
