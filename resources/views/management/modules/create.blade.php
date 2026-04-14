@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <h3 class="mb-0">Add Module</h3>
            <x-import-actions
                :import-url="route('spreadsheets.import', 'modules')"
                import-entity="modules"
                :template-fields="['module_name', 'module_code', 'module_credit', 'semester', 'nta_level', 'program_name', 'program_id']"
                template-filename="modules-template.xlsx"
                hint="module_name, module_code, module_credit, semester, nta_level, program_name/program_id"
            />
        </div>
        @include('management.partials.messages')
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('modules.store') }}" method="POST">
                    @csrf
                    @include('management.modules._form')
                    <button type="submit" class="btn btn-primary">Save Module</button>
                    <a href="{{ route('modules.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
@endsection
