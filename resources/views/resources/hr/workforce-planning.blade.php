@extends('layouts.master')

@section('title', 'Şantiye İş Gücü & Kadro Planlaması')

@section('topbar-title', 'İK - İş Gücü Planlama')

@push('styles')
<style>
    .hr-hero-banner {
        border-radius: 20px;
        padding: 2.2rem;
        margin-bottom: 2rem;
        background: linear-gradient(135deg, #0f172a 0%, #059669 50%, #10b981 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    .hr-glass-card {
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    html[data-bs-theme="dark"] .hr-glass-card {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
</style>
@endpush

@section('content')

<!-- Hero Banner -->
<div class="hr-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <a href="{{ route('resources.hr.dashboard') }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                    <i class="ri-arrow-left-line me-1"></i>İK Panosu
                </a>
            </div>
            <h1 class="fw-extrabold text-white mb-1">Şantiye İş Gücü & Kadro Planlaması</h1>
            <p class="text-white-50 mb-0 fs-6">Aktif projelere kadrolu mühendis ve teknik personel atamaları ve tarihler.</p>
        </div>
    </div>
</div>

<!-- Main Cards -->
<div class="row">
    @foreach($projects as $proj)
        @php
            $projEmployees = $employees->filter(function($e) use ($proj) {
                return $e->current_project_id == $proj->id;
            });
        @endphp
        <div class="col-lg-6 mb-4">
            <div class="hr-glass-card h-100 mb-0">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-dark mb-0">
                        <i class="ri-building-4-line text-emerald me-2" style="color:#10b981;"></i>{{ $proj->name }}
                    </h5>
                    <span class="badge bg-emerald bg-opacity-10 text-emerald border rounded-pill px-3 py-1 fw-bold" style="color:#10b981;">
                        {{ $projEmployees->count() }} Personel
                    </span>
                </div>

                <div class="list-group list-group-flush border-0">
                    @forelse($projEmployees as $emp)
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0 bg-transparent py-2">
                            <div>
                                <a href="{{ route('resources.hr.employee_profile', $emp->id) }}" class="fw-bold text-dark d-block">
                                    {{ $emp->full_name }}
                                </a>
                                <small class="text-muted">{{ $emp->position }}</small>
                            </div>
                            <span class="badge bg-light text-dark border font-monospace">{{ $emp->hire_date ? $emp->hire_date->format('d.m.Y') : 'Aktif' }}</span>
                        </div>
                    @empty
                        <p class="text-muted small py-3 mb-0 text-center">Bu projeye atanmış personel bulunmuyor.</p>
                    @endforelse
                </div>
            </div>
        </div>
    @endforeach
</div>

@endsection
