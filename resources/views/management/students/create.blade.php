@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <h3 class="mb-0">Add Student</h3>
            <x-import-actions
                :import-url="route('spreadsheets.import', 'students')"
                import-entity="students"
                :template-fields="['student_name', 'admin_number', 'email', 'password', 'intake', 'program_name', 'program_id', 'fingerprint_id']"
                template-filename="students-template.xlsx"
                hint="student_name, admin_number, email, password, intake, program_name/program_id, fingerprint_id"
            />
        </div>
        @include('management.partials.messages')
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('students.store') }}" method="POST">
                    @csrf
                    @include('management.students._form')
                    <button type="submit" class="btn btn-primary">Save Student</button>
                    <a href="{{ route('students.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
@endsection
