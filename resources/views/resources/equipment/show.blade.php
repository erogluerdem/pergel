@extends('layouts.master')

@section('title', 'İş Makinesi Detayı & Künye')

@section('topbar-title', 'Ekipman - Detay')

@push('styles')
<style>
    .equipment-hero-banner {
        border-radius: 20px;
        padding: 2.2rem;
        margin-bottom: 2rem;
        background: linear-gradient(135deg, #0f172a 0%, #ea580c 50%, #c2410c 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    .equipment-glass-card {
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    html[data-bs-theme="dark"] .equipment-glass-card {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
</style>
@endpush

@section('content')

@php
    $plk = $equipment->plate_number ?: ($equipment->serial_number ?: 'PLK-NO');
    $name = $equipment->name ?? 'CAT 336 Paletli Ağır Ekskavatör';
    $cost = $equipment->purchase_cost ?? 14500000.00;
@endphp

<!-- Hero Banner -->
<div class="equipment-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <a href="{{ route('resources.equipment.index') }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                    <i class="ri-arrow-left-line me-1"></i>Ekipman Parkı
                </a>
                <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-3 py-1 font-monospace fw-bold">
                    {{ $plk }}
                </span>
            </div>
            <h1 class="fw-extrabold text-white mb-1">{{ $name }}</h1>
            <p class="text-white-50 mb-0 fs-6">Operatör: {{ $equipment->operator ?? 'Atanmadı' }} — Proje: {{ $equipment->currentProject->name ?? 'Genel Park' }}</p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('resources.equipment.edit', $equipment->id) }}" class="btn btn-warning text-dark rounded-pill px-4 fw-bold shadow-sm">
                <i class="ri-edit-line me-1"></i>Künyeyi Düzenle
            </a>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="equipment-glass-card">
            <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
                <div>
                    <h3 class="fw-extrabold text-dark mb-0">{{ $name }}</h3>
                    <small class="text-muted">Marka/Model: {{ $equipment->brand ?? '-' }} {{ $equipment->model ?? '' }} (Model Yılı: {{ $equipment->year ?? '-' }})</small>
                </div>
                <div>
                    @if($equipment->status === 'Çalışıyor')
                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-30 rounded-pill px-4 py-2 fw-bold fs-6">Çalışıyor</span>
                    @elseif($equipment->status === 'Bakımda')
                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-30 rounded-pill px-4 py-2 fw-bold fs-6">Bakımda</span>
                    @else
                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-30 rounded-pill px-4 py-2 fw-bold fs-6">Boşta</span>
                    @endif
                </div>
            </div>

            <!-- Summary Specs -->
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <span class="text-muted small fw-bold text-uppercase d-block mb-1">Toplam Çalışma Saati</span>
                    <h2 class="fw-extrabold mb-0 text-orange" style="color:#ea580c;">{{ number_format($equipment->working_hours, 0) }} Sa</h2>
                </div>
                <div class="col-md-3">
                    <span class="text-muted small fw-bold text-uppercase d-block mb-1">Depo Yakıt Seviyesi</span>
                    <h3 class="fw-extrabold mb-0 text-dark">%{{ $equipment->fuel_level ?? 100 }}</h3>
                </div>
                <div class="col-md-3">
                    <span class="text-muted small fw-bold text-uppercase d-block mb-1">Satın Alma Maliyeti</span>
                    <h4 class="fw-bold mb-0 text-dark">₺{{ number_format($cost, 2, ',', '.') }}</h4>
                </div>
                <div class="col-md-3">
                    <span class="text-muted small fw-bold text-uppercase d-block mb-1">Sonraki Bakım Tarihi</span>
                    <h4 class="fw-bold mb-0 text-danger">{{ $equipment->next_maintenance_date ? \Carbon\Carbon::parse($equipment->next_maintenance_date)->format('d.m.Y') : '-' }}</h4>
                </div>
            </div>

            <h6 class="fw-bold text-dark mb-2">Makine Künye Açıklaması & Şantiye Görev Notları</h6>
            <div class="p-3 border rounded-3 bg-light bg-opacity-50 text-dark mb-4" style="white-space: pre-line;">
                {{ $equipment->notes ?: 'Makineye özel görev notu eklenmemiş.' }}
            </div>
        </div>
    </div>
</div>

@endsection
