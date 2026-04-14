@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <h3 class="mb-1">Edit Manual Attendance Record</h3>
        <p class="text-muted mb-3">Fingerprint logs are managed from the biometric attendance page.</p>
        @include('management.partials.messages')
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('attendance.records.update', $attendance->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    @include('management.attendance._form')
                    <button type="submit" class="btn btn-primary">Update Attendance</button>
                    <a href="{{ route('attendance.records.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
@endsection
