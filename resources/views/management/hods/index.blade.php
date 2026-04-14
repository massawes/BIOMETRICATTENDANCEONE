@extends('layouts.app')

@section('content')
    <div class="container-fluid py-3">
        @include('management.partials.messages')

        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <div>
                <div class="text-uppercase text-muted small fw-semibold mb-1">Academic Leadership</div>
                <h4 class="fw-bold mb-0 text-dark">HODs</h4>
            </div>
            <div class="d-flex flex-wrap align-items-center gap-2">
                <button
                    type="button"
                    class="btn btn-outline-success btn-sm rounded-pill px-3"
                    data-excel-export
                    data-export-url="{{ route('hods.export', request()->only('search')) }}"
                    data-export-filename="hods-export.xlsx"
                    data-export-sheet="HODs"
                >
                    <i class='bx bx-download me-1'></i> Export Excel
                </button>

                <x-import-actions
                    :import-url="route('spreadsheets.import', 'hods')"
                    import-entity="hods"
                    :template-fields="['hod_name', 'email', 'password', 'department_name', 'department_id']"
                    template-filename="hods-template.xlsx"
                    template-label="Download Format File"
                    hint="hod_name, email, password, department_name/department_id"
                />

                <a href="{{ route('hods.create') }}" class="btn btn-dark btn-sm rounded-pill px-3">
                    Add HOD
                </a>
            </div>
        </div>

    <div class="card border-0 shadow-sm rounded-4 mb-3">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('hods.index') }}" id="hod-search-form">
                <div class="row g-2 align-items-end">
                    <div class="col-md-9">
                        <label class="form-label small text-uppercase text-muted fw-semibold mb-1">Search</label>
                        <input
                            type="text"
                            name="search"
                            id="hod-search-input"
                            class="form-control"
                            placeholder="Name, email, or department"
                            value="{{ request('search') }}"
                        >
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('hods.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Name</th>
                        <th>Email</th>
                        <th>Department</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($hods as $hod)
                        <tr>
                            <td class="ps-4 fw-semibold text-dark">{{ $hod->hod_name }}</td>
                            <td class="text-muted">{{ $hod->user?->email }}</td>
                            <td class="text-muted">{{ $hod->department?->department_name }}</td>
                            <td class="text-end pe-4">
                                <div class="d-inline-flex gap-2">
                                    <a href="{{ route('hods.edit', $hod->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">Edit</a>
                                    <form action="{{ route('hods.destroy', $hod->id) }}" method="POST" onsubmit="return confirm('Delete this HOD?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">No HODs found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer bg-white border-0 px-4 py-3 d-flex justify-content-center">
            {{ $hods->withQueryString()->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('hod-search-form');
        const input = document.getElementById('hod-search-input');

        if (!form || !input) {
            return;
        }

        let timer = null;

        input.addEventListener('input', () => {
            window.clearTimeout(timer);
            timer = window.setTimeout(() => {
                form.submit();
            }, 350);
        });
    });
</script>
@endsection
