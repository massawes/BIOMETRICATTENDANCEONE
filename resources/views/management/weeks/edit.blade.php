@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <h3 class="mb-3">Edit Week</h3>
        @include('management.partials.messages')
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('weeks.update', $week->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    @include('management.weeks._form')
                    <button type="submit" class="btn btn-primary">Update Week</button>
                    <a href="{{ route('weeks.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
@endsection
