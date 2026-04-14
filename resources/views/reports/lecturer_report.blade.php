@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <div class="card shadow-sm border-0">

        <!-- Header -->
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center gap-2">
            <h5 class="mb-0">Lecturer Modules</h5>
            <x-report-actions
                :export-url="request()->fullUrlWithQuery(['export' => 1])"
                export-filename="lecturer-report.xlsx"
                export-sheet="Lecturer Report"
            />
        </div>

        <!-- Body -->
        <div class="card-body printable-area">

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle text-nowrap">

                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Module</th>
                            <th>NTA Level</th>
                            <th>Program</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($reports as $r)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $r->module_name }}</td>
                            <td>{{ $r->nta_level }}</td>
                            <td>{{ $r->program_name }}</td>
                        </tr>

                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-danger">
                                No modules assigned
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

        </div>
    </div>

</div>
@endsection
