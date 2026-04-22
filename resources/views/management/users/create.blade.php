@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <h3 class="mb-3">Add User</h3>
        @include('management.partials.messages')
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('users.store') }}" method="POST">
                    @csrf
                    @include('management.users._form')
                    <button type="submit" class="btn btn-primary">Save User</button>
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
@endsection
