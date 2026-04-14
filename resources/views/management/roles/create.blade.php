@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <h3 class="mb-0">Add Role</h3>
            <x-import-actions
                :import-url="route('spreadsheets.import', 'roles')"
                import-entity="roles"
                :template-fields="['name']"
                template-filename="roles-template.xlsx"
                hint="name"
            />
        </div>
        @include('management.partials.messages')
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('roles.store') }}" method="POST">
                    @csrf
                    @include('management.roles._form')
                    <button type="submit" class="btn btn-primary">Save Role</button>
                    <a href="{{ route('roles.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
@endsection
