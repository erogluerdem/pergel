@extends('layouts.master')

@section('title', 'Demirbaş Detayı & Amortisman Cetveli')

@section('topbar-title', 'Finans - Demirbaş Detayı')

@push('styles')
<style>
    .asset-hero-banner {
        border-radius: 20px;
        padding: 2.2rem;
        margin-bottom: 2rem;
        background: linear-gradient(135deg, #0f172a 0%, #0d9488 50%, #0f766e 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    .asset-glass-card {
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    html[data-bs-theme="dark"] .asset-glass-card {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
</style>
@endpush

@section('content')

@php
    $code = $asset->asset_code ?? 'AST-001';
    $name = $asset->name ?? 'VRF Merkezi İklimlendirme Santrali';
    $price = $asset->purchase_price ?? 850000.00;
    $currVal = $asset->current_value ?? 760000.00;
    $depr = $price - $currVal;
@endphp

<!-- Hero Banner -->
<div class="asset-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <a href="{{ route('finance.assets.list') }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                    <i class="ri-arrow-left-line me-1"></i>Demirbaş Listesi
                </a>
                <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-3 py-1 font-monospace fw-bold">
                    {{ $code }}
                </span>
            </div>
            <h1 class="fw-extrabold text-white mb-1">{{ $name }}</h1>
            <p class="text-white-50 mb-0 fs-6">Kategori: {{ $asset->category ?? 'HVAC' }} — Lokasyon: {{ $asset->location ?? 'Genel Ambar' }}</p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('finance.assets.edit', $asset->id) }}" class="btn btn-teal text-white rounded-pill px-4 fw-bold shadow-sm" style="background:#0d9488;">
                <i class="ri-edit-line me-1"></i>Düzenle
            </a>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="asset-glass-card">
            <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
                <div>
                    <h3 class="fw-extrabold text-dark mb-0">{{ $name }}</h3>
                    <small class="text-muted">Marka/Model: {{ $asset->brand ?? '-' }} {{ $asset->model ?? '' }} | Seri No: {{ $asset->serial_number ?? '-' }}</small>
                </div>
                <div>
                    @if($asset->status === 'active')
                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-30 rounded-pill px-4 py-2 fw-bold fs-6">Kullanımda</span>
                    @else
                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-30 rounded-pill px-4 py-2 fw-bold fs-6">Bakımda</span>
                    @endif
                </div>
            </div>

            <!-- Financial Metrics -->
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <span class="text-muted small fw-bold text-uppercase d-block mb-1">Satın Alış Maliyeti</span>
                    <h3 class="fw-extrabold mb-0 text-primary">₺{{ number_format($price, 2, ',', '.') }}</h3>
                </div>
                <div class="col-md-3">
                    <span class="text-muted small fw-bold text-uppercase d-block mb-1">Net Defter Değeri</span>
                    <h3 class="fw-extrabold mb-0 text-teal" style="color:#0d9488;">₺{{ number_format($currVal, 2, ',', '.') }}</h3>
                </div>
                <div class="col-md-3">
                    <span class="text-muted small fw-bold text-uppercase d-block mb-1">Birikmiş Amortisman</span>
                    <h4 class="fw-bold mb-0 text-danger">₺{{ number_format($depr, 2, ',', '.') }}</h4>
                </div>
                <div class="col-md-3">
                    <span class="text-muted small fw-bold text-uppercase d-block mb-1">Faydalı Ömür</span>
                    <h4 class="fw-bold mb-0 text-dark">{{ $asset->useful_life_years ?? 5 }} Yıl</h4>
                </div>
            </div>

            <h6 class="fw-bold text-dark mb-2">Demirbaş Açıklaması & Zimmet Bilgileri</h6>
            <div class="p-3 border rounded-3 bg-light bg-opacity-50 text-dark mb-4">
                <p class="mb-1">{{ $asset->description ?: 'Demirbaş açıklaması girilmemiş.' }}</p>
                <small class="text-muted">Fatura No: {{ $asset->invoice_number ?? '-' }} | Satıcı: {{ $asset->supplier_name ?? '-' }}</small>
            </div>
        </div>
    </div>
</div>

@endsection
