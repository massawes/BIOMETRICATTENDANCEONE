@extends('layouts.app')
@section('page-title', 'Dashboard — Msajili')

@section('content')

<div class="ent-page-header">
    <div>
        <h1 class="ent-page-title">Registrar Dashboard</h1>
        <p class="ent-page-sub">Muhtasari wa usajili — wanafunzi, programu na idara</p>
    </div>
    <div class="ent-page-actions">
        <a href="{{ route('management.attendance-report') }}" class="ent-btn ent-btn-outline ent-btn-sm">
            <i class='bx bx-file-blank'></i> Ripoti ya Mahudhurio
        </a>
        <a href="{{ route('analytics.dashboard') }}" class="ent-btn ent-btn-primary ent-btn-sm">
            <i class='bx bx-bar-chart-alt-2'></i> Takwimu
        </a>
    </div>
</div>

{{-- Stat cards --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-4">
        <div class="ent-stat">
            <div class="ent-stat-icon"><i class='bx bx-graduation'></i></div>
            <div class="ent-stat-value">{{ $totalStudents }}</div>
            <div class="ent-stat-label">Jumla ya Wanafunzi</div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-4">
        <div class="ent-stat ent-stat-success">
            <div class="ent-stat-icon"><i class='bx bx-bookmark-heart'></i></div>
            <div class="ent-stat-value">{{ $totalPrograms }}</div>
            <div class="ent-stat-label">Programu Zilizosajiliwa</div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-4">
        <div class="ent-stat ent-stat-info">
            <div class="ent-stat-icon"><i class='bx bx-buildings'></i></div>
            <div class="ent-stat-value">{{ $totalDepartments }}</div>
            <div class="ent-stat-label">Idara Zilizopo</div>
        </div>
    </div>
</div>

{{-- Quick links card --}}
<div class="ent-card">
    <div class="ent-card-header">
        <h2 class="ent-card-title"><i class='bx bx-link-alt'></i> Viungo vya Haraka</h2>
    </div>
    <div class="ent-card-body" style="display:flex;flex-wrap:wrap;gap:.5rem">
        <a href="{{ route('management.attendance-report') }}" class="ent-btn ent-btn-outline ent-btn-sm">
            <i class='bx bx-clipboard'></i> Ripoti ya Mahudhurio
        </a>
        <a href="{{ route('analytics.dashboard') }}" class="ent-btn ent-btn-outline ent-btn-sm">
            <i class='bx bx-bar-chart-alt-2'></i> Takwimu
        </a>
        <a href="{{ route('profile.edit') }}" class="ent-btn ent-btn-outline ent-btn-sm">
            <i class='bx bx-user-circle'></i> Wasifu Wangu
        </a>
    </div>
</div>

@endsection
