@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <h3 class="mb-3">Edit Module</h3>
        @include('management.partials.messages')
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('modules.update', $module->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    @include('management.modules._form')
                    <button type="submit" class="btn btn-primary">Update Module</button>
                    <a href="{{ route('modules.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
@endsection
