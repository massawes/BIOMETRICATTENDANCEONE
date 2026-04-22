@extends('layouts.app')

@section('content')
<div class="container-fluid py-3">
    @include('management.partials.messages')

    <div class="d-flex justify-content-between align-items-center gap-2 mb-3">
        <div>
            <div class="text-uppercase text-muted small fw-semibold mb-1">Academic Leadership</div>
            <h4 class="fw-bold mb-0 text-dark">Add HOD</h4>
        </div>
        <a href="{{ route('hods.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">Back</a>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-3 p-md-4">
            <form action="{{ route('hods.store') }}" method="POST">
                @csrf
                @include('management.hods._form')
                <div class="d-flex gap-2 flex-wrap mt-3">
                    <button type="submit" class="btn btn-success rounded-pill px-4">Save HOD</button>
                    <a href="{{ route('hods.index') }}" class="btn btn-outline-secondary rounded-pill px-4">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
