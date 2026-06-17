@extends('layouts.app')
@section('page-title', 'Dashboard — HOD')

@section('content')

<div class="ent-page-header">
    <div>
        <h1 class="ent-page-title">Head of Department</h1>
        <p class="ent-page-sub">
            <i class='bx bx-buildings' style="color:var(--ent-primary)"></i>
            {{ $department->department_name ?? 'Idara Yako' }}
        </p>
    </div>
    <div class="ent-page-actions">
        <a href="{{ route('hod.analysis') }}"   class="ent-btn ent-btn-outline ent-btn-sm">
            <i class='bx bx-line-chart'></i> Uchambuzi
        </a>
        <a href="{{ route('lecturers.index') }}" class="ent-btn ent-btn-outline ent-btn-sm">
            <i class='bx bx-chalkboard'></i> Wahadhiri
        </a>
        <a href="{{ route('hodreport') }}"       class="ent-btn ent-btn-primary ent-btn-sm">
            <i class='bx bx-file-blank'></i> Ripoti ya Moduli
        </a>
    </div>
</div>

{{-- Stat cards --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="ent-stat">
            <div class="ent-stat-icon"><i class='bx bx-chalkboard'></i></div>
            <div class="ent-stat-value">{{ $lecturersCount }}</div>
            <div class="ent-stat-label">Wahadhiri wa Idara</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="ent-stat ent-stat-info">
            <div class="ent-stat-icon"><i class='bx bx-book'></i></div>
            <div class="ent-stat-value">{{ $modulesCount }}</div>
            <div class="ent-stat-label">Moduli Zote</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="ent-stat ent-stat-success">
            <div class="ent-stat-icon"><i class='bx bx-bookmark'></i></div>
            <div class="ent-stat-value">{{ $programsCount }}</div>
            <div class="ent-stat-label">Programu za Idara</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="ent-stat ent-stat-warning">
            <div class="ent-stat-icon"><i class='bx bx-graduation'></i></div>
            <div class="ent-stat-value">{{ $studentsCount }}</div>
            <div class="ent-stat-label">Wanafunzi wa Idara</div>
        </div>
    </div>
</div>

<div class="row g-3">
    {{-- Module assignments --}}
    <div class="col-lg-8">
        <div class="ent-card h-100">
            <div class="ent-card-header">
                <h2 class="ent-card-title"><i class='bx bx-git-branch'></i> Mgawanyo wa Moduli</h2>
                <span class="ent-badge ent-badge-primary">{{ $moduleDistributions->count() }} zilizoonyeshwa</span>
            </div>
            <div class="ent-card-body" style="padding:0">
                @if($moduleDistributions->isEmpty())
                    <div class="ent-empty">
                        <i class='bx bx-book-open'></i>
                        <p>Hakuna ugawaji wa moduli bado.</p>
                    </div>
                @else
                    <table class="ent-table">
                        <thead>
                            <tr>
                                <th>Moduli</th>
                                <th>Programu</th>
                                <th>Mhadhiri</th>
                                <th>NTA</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($moduleDistributions as $dist)
                                <tr>
                                    <td style="font-weight:600">{{ $dist->module_name }}</td>
                                    <td style="color:var(--ent-text-muted)">{{ $dist->program_name }}</td>
                                    <td style="color:var(--ent-text-muted)">{{ $dist->lecturer_name }}</td>
                                    <td><span class="ent-badge ent-badge-info">NTA {{ $dist->nta_level }}</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>

    {{-- Lecturers & quick links --}}
    <div class="col-lg-4">
        {{-- Quick links --}}
        <div class="ent-card mb-3">
            <div class="ent-card-header">
                <h2 class="ent-card-title"><i class='bx bx-link-alt'></i> Viungo vya Haraka</h2>
            </div>
            <div class="ent-card-body" style="display:flex;flex-direction:column;gap:.4rem">
                <a href="{{ route('hodreport') }}"               class="ent-btn ent-btn-outline ent-btn-sm"><i class='bx bx-file-blank'></i> Ripoti ya Moduli</a>
                <a href="{{ route('hod.analysis') }}"            class="ent-btn ent-btn-outline ent-btn-sm"><i class='bx bx-bar-chart-alt-2'></i> Uchambuzi wa Mahudhurio</a>
                <a href="{{ route('moduledistribute.create') }}" class="ent-btn ent-btn-outline ent-btn-sm"><i class='bx bx-plus-circle'></i> Gawa Moduli Mpya</a>
                <a href="{{ route('students.index') }}"          class="ent-btn ent-btn-outline ent-btn-sm"><i class='bx bx-graduation'></i> Simamia Wanafunzi</a>
            </div>
        </div>

        {{-- Lecturers list --}}
        <div class="ent-card">
            <div class="ent-card-header">
                <h2 class="ent-card-title"><i class='bx bx-group'></i> Wahadhiri wa Hivi Karibuni</h2>
            </div>
            <div class="ent-card-body" style="padding:0">
                @if($lecturers->isEmpty())
                    <div class="ent-empty" style="padding:1.5rem">
                        <i class='bx bx-user-x'></i>
                        <p>Hakuna wahadhiri bado.</p>
                    </div>
                @else
                    @foreach($lecturers as $lecturer)
                        <div style="display:flex;align-items:center;gap:.65rem;padding:.65rem 1.25rem;border-bottom:1px solid var(--ent-border-light)">
                            <div style="width:2rem;height:2rem;border-radius:50%;background:rgba(15,76,129,.1);color:var(--ent-primary);display:flex;align-items:center;justify-content:center;font-size:.78rem;font-weight:700;flex-shrink:0">
                                {{ strtoupper(substr($lecturer->lecturer_name ?? $lecturer->user->name ?? '?', 0, 2)) }}
                            </div>
                            <div style="min-width:0">
                                <div style="font-size:.84rem;font-weight:600;color:var(--ent-text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                                    {{ $lecturer->lecturer_name ?? $lecturer->user->name ?? 'N/A' }}
                                </div>
                                <div style="font-size:.72rem;color:var(--ent-text-muted)">Mhadhiri</div>
                            </div>
                            <span class="ent-status-dot online ms-auto"></span>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
