@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <h3 class="mb-0">Add Lecturer</h3>
            <x-import-actions
                :import-url="route('spreadsheets.import', 'lecturers')"
                import-entity="lecturers"
                :template-fields="['lecturer_name', 'email', 'password']"
                template-filename="lecturers-template.xlsx"
                hint="lecturer_name, email, password"
            />
        </div>
        @include('management.partials.messages')
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('lecturers.store') }}" method="POST">
                    @csrf
                    @include('management.lecturers._form')
                    <button type="submit" class="btn btn-primary">Save Lecturer</button>
                    <a href="{{ route('lecturers.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
@endsection
