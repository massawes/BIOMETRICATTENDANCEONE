@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-1">
            <h3 class="mb-0">Add Manual Attendance Record</h3>
            <x-import-actions
                :import-url="route('spreadsheets.import', 'attendance_records')"
                import-entity="attendance_records"
                :template-fields="['student_id', 'student_admin_number', 'module_distribution_id', 'module_code', 'academic_year', 'date', 'is_present', 'status', 'class_timing_id', 'week_name', 'week_id']"
                template-filename="attendance-records-template.xlsx"
                hint="student_admin_number/student_id, module_code/module_distribution_id, academic_year, date, is_present/status"
            />
        </div>
        <p class="text-muted mb-3">This form is for lecturer-entered records only.</p>
        @include('management.partials.messages')
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('attendance.records.store') }}" method="POST">
                    @csrf
                    @include('management.attendance._form')
                    <button type="submit" class="btn btn-primary">Save Attendance</button>
                    <a href="{{ route('attendance.records.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
@endsection
