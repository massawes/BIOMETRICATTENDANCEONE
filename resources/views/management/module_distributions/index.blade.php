@extends('layouts.app')

@section('content')
    <div class="container py-4">
        @include('management.partials.messages')
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <div>
                <h3 class="mb-0">Module Distributions</h3>
                <p class="text-muted mb-0">Latest saved allocations appear first, including updated records.</p>
            </div>
            <a href="{{ route('moduledistribute.create', request()->only('academic_year')) }}" class="btn btn-primary">Assign Modules</a>
        </div>
        <form method="GET" action="{{ route('moduledistribute.index') }}" class="row g-2 mb-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search module, lecturer, year" value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <input type="text" name="academic_year" class="form-control" placeholder="Academic year e.g. 2025/2026" value="{{ request('academic_year') }}">
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
                            <th>Program</th>
                            <th>Lecturer</th>
                            <th>Academic Year</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($distributions as $distribution)
                            <tr>
                                <td>{{ $distribution->module?->module_name }}</td>
                                <td>{{ $distribution->module?->program?->program_name }}</td>
                                <td>{{ $distribution->lecturer?->name }}</td>
                                <td>{{ $distribution->academic_year }}</td>
                                <td class="text-end">
                                    <a href="{{ route('moduledistribute.show', $distribution->id) }}" class="btn btn-sm btn-info text-white">View</a>
                                    <a href="{{ route('moduledistribute.edit', $distribution->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('moduledistribute.destroy', $distribution->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this distribution?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No module distributions found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-3">{{ $distributions->withQueryString()->links() }}</div>
    </div>
@endsection
