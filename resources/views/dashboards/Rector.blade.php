@extends('layouts.app')
@section('page-title', 'Dashboard — Rector')

@section('content')

<div class="ent-page-header">
    <div>
        <h1 class="ent-page-title">Rector Dashboard</h1>
        <p class="ent-page-sub">Muhtasari wa taasisi — wanafunzi, wahadhiri na mahudhurio</p>
    </div>
    <div class="ent-page-actions">
        <a href="{{ route('analytics.dashboard') }}" class="ent-btn ent-btn-outline ent-btn-sm">
            <i class='bx bx-bar-chart-alt-2'></i> Takwimu
        </a>
        <a href="{{ route('management.attendance-report') }}" class="ent-btn ent-btn-primary ent-btn-sm">
            <i class='bx bx-file-blank'></i> Ripoti ya Mahudhurio
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
            <div class="ent-stat-icon"><i class='bx bx-chalkboard'></i></div>
            <div class="ent-stat-value">{{ $totalLecturers }}</div>
            <div class="ent-stat-label">Jumla ya Wahadhiri</div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-4">
        <div class="ent-stat {{ $attendanceRate >= 75 ? 'ent-stat-success' : 'ent-stat-danger' }}">
            <div class="ent-stat-icon"><i class='bx bx-user-check'></i></div>
            <div class="ent-stat-value">{{ $attendanceRate }}%</div>
            <div class="ent-stat-label">Wastani wa Mahudhurio</div>
            <div class="ent-stat-trend {{ $attendanceRate >= 75 ? 'up' : 'down' }}">
                <i class='bx {{ $attendanceRate >= 75 ? "bx-trending-up" : "bx-trending-down" }}'></i>
                {{ $attendanceRate >= 75 ? 'Inafikia kiwango' : 'Chini ya kiwango' }}
            </div>
        </div>
    </div>
</div>

{{-- Department performance --}}
<div class="ent-card">
    <div class="ent-card-header">
        <h2 class="ent-card-title"><i class='bx bx-buildings'></i> Utendaji wa Idara</h2>
        <span class="ent-badge ent-badge-primary">{{ $departmentPerformance->count() }} idara</span>
    </div>
    <div class="ent-card-body" style="padding:0">
        @if($departmentPerformance->isEmpty())
            <div class="ent-empty">
                <i class='bx bx-building'></i>
                <p>Hakuna data ya idara bado.</p>
            </div>
        @else
            <table class="ent-table">
                <thead>
                    <tr>
                        <th>Jina la Idara</th>
                        <th>Programu</th>
                        <th>Wanafunzi</th>
                        <th>Mahudhurio</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($departmentPerformance as $dept)
                        <tr>
                            <td style="font-weight:600">{{ $dept->department_name }}</td>
                            <td style="color:var(--ent-text-muted)">{{ $dept->total_programs }}</td>
                            <td style="color:var(--ent-text-muted)">{{ $dept->total_students }}</td>
                            <td>
                                @if($dept->attendance_rate >= 75)
                                    <span class="ent-badge ent-badge-success"><i class='bx bx-trending-up'></i> {{ $dept->attendance_rate }}%</span>
                                @else
                                    <span class="ent-badge ent-badge-danger"><i class='bx bx-trending-down'></i> {{ $dept->attendance_rate }}%</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>

@endsection
