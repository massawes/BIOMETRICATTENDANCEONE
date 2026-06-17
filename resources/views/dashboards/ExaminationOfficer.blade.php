@extends('layouts.app')
@section('page-title', 'Dashboard — Afisa Mitihani')

@section('content')

<div class="ent-page-header">
    <div>
        <h1 class="ent-page-title">Examination Officer Dashboard</h1>
        <p class="ent-page-sub">Muhtasari wa ufuzu wa mitihani, mahudhurio na wanafunzi walio hatarini</p>
    </div>
    <div class="ent-page-actions">
        <a href="{{ route('exam.reports') }}"     class="ent-btn ent-btn-outline ent-btn-sm">
            <i class='bx bx-file-blank'></i> Ripoti
        </a>
        <a href="{{ route('exam.eligibility') }}" class="ent-btn ent-btn-primary ent-btn-sm">
            <i class='bx bx-list-check'></i> Orodha ya Kufuzu
        </a>
    </div>
</div>

{{-- Stat cards --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="ent-stat">
            <div class="ent-stat-icon"><i class='bx bx-graduation'></i></div>
            <div class="ent-stat-value">{{ $totalStudents }}</div>
            <div class="ent-stat-label">Jumla ya Wanafunzi</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="ent-stat ent-stat-info">
            <div class="ent-stat-icon"><i class='bx bx-book-content'></i></div>
            <div class="ent-stat-value">{{ $totalModules }}</div>
            <div class="ent-stat-label">Moduli Zote</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="ent-stat {{ $attendanceRate >= 75 ? 'ent-stat-success' : 'ent-stat-warning' }}">
            <div class="ent-stat-icon"><i class='bx bx-line-chart'></i></div>
            <div class="ent-stat-value">{{ $attendanceRate }}%</div>
            <div class="ent-stat-label">Wastani wa Mahudhurio</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="ent-stat ent-stat-danger">
            <div class="ent-stat-icon"><i class='bx bx-error-circle'></i></div>
            <div class="ent-stat-value">{{ $studentRiskCount }}</div>
            <div class="ent-stat-label">Wanafunzi Walio Hatarini</div>
        </div>
    </div>
</div>

<div class="row g-3">
    {{-- Eligibility snapshot --}}
    <div class="col-lg-8">
        <div class="ent-card h-100">
            <div class="ent-card-header">
                <h2 class="ent-card-title"><i class='bx bx-check-shield'></i> Hali ya Ufuzu wa Mitihani</h2>
                <span class="ent-badge ent-badge-warning">Sheria ya 75%</span>
            </div>
            <div class="ent-card-body">
                <div class="row g-3">
                    <div class="col-sm-4">
                        <div style="background:var(--ent-bg);border:1px solid var(--ent-border);border-radius:var(--ent-radius);padding:1rem;text-align:center">
                            <div style="font-size:1.6rem;font-weight:800;color:var(--ent-success)">{{ $clearedCount }}</div>
                            <div style="font-size:.75rem;font-weight:600;text-transform:uppercase;letter-spacing:.05em;color:var(--ent-text-muted);margin-top:.3rem">
                                <i class='bx bx-check-circle' style="color:var(--ent-success)"></i> Wamefuzu
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div style="background:var(--ent-bg);border:1px solid var(--ent-border);border-radius:var(--ent-radius);padding:1rem;text-align:center">
                            <div style="font-size:1.6rem;font-weight:800;color:var(--ent-danger)">{{ $notClearedCount }}</div>
                            <div style="font-size:.75rem;font-weight:600;text-transform:uppercase;letter-spacing:.05em;color:var(--ent-text-muted);margin-top:.3rem">
                                <i class='bx bx-x-circle' style="color:var(--ent-danger)"></i> Hawajafuzu
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div style="background:var(--ent-bg);border:1px solid var(--ent-border);border-radius:var(--ent-radius);padding:1rem;text-align:center">
                            <div style="font-size:1.6rem;font-weight:800;color:var(--ent-primary)">{{ $attendanceRate }}%</div>
                            <div style="font-size:.75rem;font-weight:600;text-transform:uppercase;letter-spacing:.05em;color:var(--ent-text-muted);margin-top:.3rem">
                                <i class='bx bx-bar-chart-alt-2' style="color:var(--ent-primary)"></i> Kiwango cha Mfumo
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Low attendance modules --}}
    <div class="col-lg-4">
        <div class="ent-card h-100">
            <div class="ent-card-header">
                <h2 class="ent-card-title"><i class='bx bx-error'></i> Angalizo la Haraka</h2>
                <span class="ent-badge ent-badge-danger">{{ $lowAttendanceModules->count() }}</span>
            </div>
            <div class="ent-card-body" style="padding:0">
                @if($lowAttendanceModules->isEmpty())
                    <div class="ent-empty">
                        <i class='bx bx-check-circle'></i>
                        <p>Hakuna moduli za hatari.</p>
                    </div>
                @else
                    @foreach($lowAttendanceModules->take(4) as $module)
                        <div style="display:flex;align-items:center;justify-content:space-between;padding:.7rem 1.25rem;border-bottom:1px solid var(--ent-border-light)">
                            <div>
                                <div style="font-size:.85rem;font-weight:600;color:var(--ent-text)">{{ $module->module_name }}</div>
                                <div style="font-size:.75rem;color:var(--ent-text-muted)">{{ $module->program_name ?? 'Jumla' }}</div>
                            </div>
                            <span class="ent-badge {{ $module->attendance_rate >= 75 ? 'ent-badge-success' : 'ent-badge-danger' }}">
                                {{ $module->attendance_rate }}%
                            </span>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
