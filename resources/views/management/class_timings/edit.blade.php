@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <h3 class="mb-3">Edit Timetable Entry</h3>
        @include('management.partials.messages')
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('class-timings.update', $classTiming->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    @include('management.class_timings._form')
                    <button type="submit" class="btn btn-primary">Update Timetable</button>
                    <a href="{{ route('class-timings.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
@endsection
