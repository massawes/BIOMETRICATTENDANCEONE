@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <h3 class="mb-3">Edit Program</h3>
        @include('management.partials.messages')
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('programs.update', $program->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    @include('management.programs._form')
                    <button type="submit" class="btn btn-primary">Update Program</button>
                    <a href="{{ route('programs.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
@endsection
