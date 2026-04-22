@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <h3 class="mb-0">Add Program</h3>
            <x-import-actions
                :import-url="route('spreadsheets.import', 'programs')"
                import-entity="programs"
                :template-fields="['program_name']"
                template-filename="programs-template.xlsx"
                hint="program_name"
            />
        </div>
        @include('management.partials.messages')
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('programs.store') }}" method="POST">
                    @csrf
                    @include('management.programs._form')
                    <button type="submit" class="btn btn-primary">Save Program</button>
                    <a href="{{ route('programs.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
@endsection
