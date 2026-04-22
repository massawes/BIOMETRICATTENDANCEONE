@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <h3 class="mb-3">Edit Department</h3>
        @include('management.partials.messages')
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('departments.update', $department->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    @include('management.departments._form')
                    <button type="submit" class="btn btn-primary">Update Department</button>
                    <a href="{{ route('departments.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
@endsection
