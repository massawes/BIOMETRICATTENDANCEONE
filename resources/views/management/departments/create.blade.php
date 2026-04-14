@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <h3 class="mb-0">Add Department</h3>
            <x-import-actions
                :import-url="route('spreadsheets.import', 'departments')"
                import-entity="departments"
                :template-fields="['department_name']"
                template-filename="departments-template.xlsx"
                hint="department_name"
            />
        </div>
        @include('management.partials.messages')
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('departments.store') }}" method="POST">
                    @csrf
                    @include('management.departments._form')
                    <button type="submit" class="btn btn-primary">Save Department</button>
                    <a href="{{ route('departments.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
@endsection
