@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <h3 class="mb-3">Edit User</h3>
        @include('management.partials.messages')
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    @include('management.users._form')
                    <button type="submit" class="btn btn-primary">Update User</button>
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
@endsection
