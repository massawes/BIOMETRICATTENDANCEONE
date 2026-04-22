@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <h3 class="mb-3">Edit Student</h3>
        @include('management.partials.messages')
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('students.update', $student->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    @include('management.students._form')
                    <button type="submit" class="btn btn-primary">Update Student</button>
                    <a href="{{ route('students.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
@endsection
