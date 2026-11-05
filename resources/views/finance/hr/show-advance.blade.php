@extends('layouts.master')

@section('title', 'Avans Talebi Detayı')

@section('topbar-title', 'Personel Avansları - Detay')

@push('styles')
<style>
    .hr-advance-hero-banner {
        border-radius: 20px;
        padding: 2.2rem;
        margin-bottom: 2rem;
        background: linear-gradient(135deg, #0f172a 0%, #0d9488 50%, #0f766e 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    .hr-advance-glass-card {
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    html[data-bs-theme="dark"] .hr-advance-glass-card {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
</style>
@endpush

@section('content')

@php
    $refNo = 'ADV-2026-' . sprintf('%04d', $advance->id);
    $empName = $advance->employee->first_name ? ($advance->employee->first_name . ' ' . $advance->employee->last_name) : ($advance->employee->name ?? 'Ahmet Yılmaz');
    $amount = $advance->amount ?? 15000.00;
@endphp

<!-- Hero Banner -->
<div class="hr-advance-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <a href="{{ route('finance.hr.advances') }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                    <i class="ri-arrow-left-line me-1"></i>Avanslar Panosu
                </a>
                <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-3 py-1 font-monospace fw-bold">
                    {{ $refNo }}
                </span>
            </div>
            <h1 class="fw-extrabold text-white mb-1">Avans Talep Detayı</h1>
            <p class="text-white-50 mb-0 fs-6">Personel: {{ $empName }} — Pozisyon: {{ $advance->employee->position ?? 'Saha Personeli' }}</p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('finance.hr.advances.edit', $id) }}" class="btn btn-warning text-dark rounded-pill px-4 fw-bold shadow-sm">
                <i class="ri-edit-line me-1"></i>Talebi Düzenle
            </a>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="hr-advance-glass-card">
            <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
                <div>
                    <h3 class="fw-extrabold text-dark mb-0">{{ $empName }} — Avans Talebi</h3>
                    <small class="text-muted">Talep Tarihi: {{ $advance->date ? \Carbon\Carbon::parse($advance->date)->format('d.m.Y') : date('d.m.Y') }}</small>
                </div>
                <div>
                    @if($advance->status === 'paid')
                        <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-30 rounded-pill px-4 py-2 fw-bold fs-6">Ödendi (Mahsup Oldu)</span>
                    @elseif($advance->status === 'approved')
                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-30 rounded-pill px-4 py-2 fw-bold fs-6">Onaylandı</span>
                    @elseif($advance->status === 'rejected')
                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-30 rounded-pill px-4 py-2 fw-bold fs-6">Reddedildi</span>
                    @else
                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-30 rounded-pill px-4 py-2 fw-bold fs-6">Onay Bekliyor</span>
                    @endif
                </div>
            </div>

            <!-- Summary Specs -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <span class="text-muted small fw-bold text-uppercase d-block mb-1">Talep Edilen Tutar</span>
                    <h3 class="fw-extrabold mb-0 text-teal" style="color:#0d9488;">₺{{ number_format($amount, 2, ',', '.') }}</h3>
                </div>
                <div class="col-md-4">
                    <span class="text-muted small fw-bold text-uppercase d-block mb-1">Personel Şubesi / Departman</span>
                    <h4 class="fw-bold mb-0 text-dark">{{ $advance->employee->department ?? 'Saha Ekibi' }}</h4>
                </div>
                <div class="col-md-4">
                    <span class="text-muted small fw-bold text-uppercase d-block mb-1">Ödeme / Mahsup Durumu</span>
                    <h4 class="fw-bold mb-0 text-dark">{{ $advance->status === 'paid' ? 'Bordrodan Kesildi' : 'Beklemede' }}</h4>
                </div>
            </div>

            <h6 class="fw-bold text-dark mb-2">Avans Talebi Sebebi & Detaylar</h6>
            <div class="p-3 border rounded-3 bg-light bg-opacity-50 text-dark mb-4" style="white-space: pre-line;">
                {{ $advance->description ?: 'Avans talep açıklaması girilmemiş.' }}
            </div>
        </div>
    </div>
</div>

@endsection
