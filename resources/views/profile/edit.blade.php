@extends('layouts.app')

@section('content')

<div class="container mt-4">

    <!-- TITLE -->
    <div class="mb-4">
        <h4 class="fw-bold">Profile</h4>
    </div>

    <!-- UPDATE PROFILE -->
    <div class="card shadow mb-4">
        <div class="card-body">
            @include('profile.partials.update-profile-information-form')
        </div>
    </div>

    <!-- UPDATE PASSWORD -->
    <div class="card shadow mb-4">
        <div class="card-body">
            @include('profile.partials.update-password-form')
        </div>
    </div>

    <!-- DELETE USER -->
    <div class="card shadow">
        <div class="card-body">
            @include('profile.partials.delete-user-form')
        </div>
    </div>

</div>

@endsection