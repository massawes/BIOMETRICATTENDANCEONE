@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <h3 class="mb-0">Add Timetable Entry</h3>
            <x-import-actions
                :import-url="route('spreadsheets.import', 'class_timings')"
                import-entity="class_timings"
                :template-fields="['module_distribution_id', 'module_code', 'academic_year', 'day', 'time', 'room', 'week_name', 'week_id']"
                template-filename="class-timings-template.xlsx"
                hint="module_distribution_id/module_code, academic_year, day, time, room, week_name/week_id"
            />
        </div>
        @include('management.partials.messages')
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('class-timings.store') }}" method="POST">
                    @csrf
                    @include('management.class_timings._form')
                    <button type="submit" class="btn btn-primary">Save Timetable</button>
                    <a href="{{ route('class-timings.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
@endsection
