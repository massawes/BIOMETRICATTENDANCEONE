@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <h3 class="mb-0">Add Week</h3>
            <x-import-actions
                :import-url="route('spreadsheets.import', 'weeks')"
                import-entity="weeks"
                :template-fields="['week_name']"
                template-filename="weeks-template.xlsx"
                hint="week_name"
            />
        </div>
        @include('management.partials.messages')
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('weeks.store') }}" method="POST">
                    @csrf
                    @include('management.weeks._form')
                    <button type="submit" class="btn btn-primary">Save Week</button>
                    <a href="{{ route('weeks.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
@endsection
