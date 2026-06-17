@extends('layouts.app')
@section('page-title', 'Dashboard — Mhadhiri')

@section('content')

<div class="ent-page-header">
    <div>
        <h1 class="ent-page-title">Habari, {{ auth()->user()->name }}</h1>
        <p class="ent-page-sub">Simamia madarasa, mahudhurio na takwimu zako</p>
    </div>
    <div class="ent-page-actions">
        <a href="{{ route('lecturerclasstiming') }}" class="ent-btn ent-btn-outline ent-btn-sm">
            <i class='bx bx-time'></i> Ratiba
        </a>
        <a href="{{ route('lecturerireport') }}" class="ent-btn ent-btn-outline ent-btn-sm">
            <i class='bx bx-bar-chart-alt-2'></i> Uchambuzi
        </a>
        <a href="{{ route('attendanceindex') }}" class="ent-btn ent-btn-primary ent-btn-sm">
            <i class='bx bx-user-check'></i> Rekodi Mahudhurio
        </a>
    </div>
</div>

{{-- Stat cards --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-4">
        <div class="ent-stat">
            <div class="ent-stat-icon"><i class='bx bx-book'></i></div>
            <div class="ent-stat-value">{{ $totalModules }}</div>
            <div class="ent-stat-label">Moduli Zilizopangwa</div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-4">
        <div class="ent-stat ent-stat-success">
            <div class="ent-stat-icon"><i class='bx bx-door-open'></i></div>
            <div class="ent-stat-value">{{ $totalClasses }}</div>
            <div class="ent-stat-label">Jumla ya Madarasa</div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-4">
        <div class="ent-stat ent-stat-info">
            <div class="ent-stat-icon"><i class='bx bx-graduation'></i></div>
            <div class="ent-stat-value">{{ $totalStudents }}</div>
            <div class="ent-stat-label">Wanafunzi Waliopo</div>
        </div>
    </div>
</div>

{{-- Quick links --}}
<div class="ent-card mb-3">
    <div class="ent-card-header">
        <h2 class="ent-card-title"><i class='bx bx-link-alt'></i> Viungo vya Haraka</h2>
    </div>
    <div class="ent-card-body" style="display:flex;flex-wrap:wrap;gap:.5rem">
        <a href="{{ route('lecturerireport') }}"    class="ent-btn ent-btn-outline ent-btn-sm"><i class='bx bx-pie-chart-alt-2'></i> Uchambuzi wa Mahudhurio</a>
        <a href="{{ route('lecturerclasstiming') }}" class="ent-btn ent-btn-outline ent-btn-sm"><i class='bx bx-calendar-check'></i> Ratiba ya Darasa</a>
        <a href="{{ route('attendanceindex') }}"    class="ent-btn ent-btn-outline ent-btn-sm"><i class='bx bx-pencil'></i> Mahudhurio ya Mkono</a>
        <a href="{{ route('devices.index') }}"      class="ent-btn ent-btn-outline ent-btn-sm"><i class='bx bx-fingerprint'></i> Vifaa vya Biometric</a>
    </div>
</div>

{{-- Modules snapshot --}}
<div class="ent-card">
    <div class="ent-card-header">
        <h2 class="ent-card-title"><i class='bx bx-chalkboard'></i> Moduli Zilizopangwa</h2>
        <span class="ent-badge ent-badge-primary">{{ $recentModules->count() }} zilizo onekana</span>
    </div>
    <div class="ent-card-body" style="padding:0">
        @if($recentModules->isEmpty())
            <div class="ent-empty">
                <i class='bx bx-book-open'></i>
                <p>Hakuna moduli zilizopangwa bado.</p>
            </div>
        @else
            <table class="ent-table">
                <thead>
                    <tr>
                        <th>Jina la Moduli</th>
                        <th>Programu</th>
                        <th>Kiwango NTA</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentModules as $dist)
                        <tr>
                            <td style="font-weight:600">{{ $dist->module->module_name ?? 'N/A' }}</td>
                            <td style="color:var(--ent-text-muted)">{{ $dist->module->program->program_name ?? 'N/A' }}</td>
                            <td>
                                <span class="ent-badge ent-badge-info">NTA {{ $dist->module->nta_level ?? '—' }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>

@endsection
