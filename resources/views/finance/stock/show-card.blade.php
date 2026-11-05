@extends('layouts.master')

@section('title', 'Stok Kartı Detayı')

@section('topbar-title', 'Finans - Stok Detayı')

@push('styles')
<style>
    .stock-hero-banner {
        border-radius: 20px;
        padding: 2.2rem;
        margin-bottom: 2rem;
        background: linear-gradient(135deg, #0f172a 0%, #0d9488 50%, #0f766e 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    .stock-glass-card {
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    html[data-bs-theme="dark"] .stock-glass-card {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
</style>
@endpush

@section('content')

@php
    $code = $stockCard->code ?? 'STK-00101';
    $name = $stockCard->name ?? 'CEM I 42.5R Portland Çimento';
    $current = $stockCard->current_stock ?? 650.00;
    $min = $stockCard->min_stock ?? 100.00;
    $max = $stockCard->max_stock ?? 1000.00;
    $price = $stockCard->unit_price ?? 185.00;
    $totalVal = $stockCard->total_value ?? 120250.00;
    $isCritical = $current <= $min;
@endphp

<!-- Hero Banner -->
<div class="stock-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <a href="{{ route('finance.stock.cards') }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                    <i class="ri-arrow-left-line me-1"></i>Stok Kartları Panosu
                </a>
                <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-3 py-1 font-monospace fw-bold">
                    {{ $code }}
                </span>
            </div>
            <h1 class="fw-extrabold text-white mb-1">{{ $name }}</h1>
            <p class="text-white-50 mb-0 fs-6">Kategori: {{ $stockCard->category ?? 'İnşaat Malzemesi' }} — Depo: {{ $stockCard->location ?? 'Genel Ambar' }}</p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('finance.stock.cards.edit', $id) }}" class="btn btn-warning text-dark rounded-pill px-4 fw-bold shadow-sm">
                <i class="ri-edit-line me-1"></i>Stok Kartını Düzenle
            </a>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="stock-glass-card">
            <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
                <div>
                    <h3 class="fw-extrabold text-dark mb-0">{{ $name }}</h3>
                    <small class="text-muted">Barkod: {{ $stockCard->barcode ?? '8690001001012' }} | Tedarikçi: {{ $stockCard->supplier ?? 'Tedarikçi Firma' }}</small>
                </div>
                <div>
                    @if($isCritical)
                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-30 rounded-pill px-4 py-2 fw-bold fs-6">KRİTİK STOK UYARISI</span>
                    @else
                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-30 rounded-pill px-4 py-2 fw-bold fs-6">Normal Stok Seviyesi</span>
                    @endif
                </div>
            </div>

            <!-- Summary Specs -->
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <span class="text-muted small fw-bold text-uppercase d-block mb-1">Mevcut Stok Miktarı</span>
                    <h2 class="fw-extrabold mb-0 {{ $isCritical ? 'text-danger' : 'text-teal' }}" style="color:#0d9488;">
                        {{ number_format($current, 2, ',', '.') }} {{ $stockCard->unit ?? 'Adet' }}
                    </h2>
                </div>
                <div class="col-md-3">
                    <span class="text-muted small fw-bold text-uppercase d-block mb-1">Min / Max Limit</span>
                    <h4 class="fw-bold mb-0 text-dark">{{ number_format($min, 0) }} / {{ number_format($max, 0) }} {{ $stockCard->unit ?? 'Adet' }}</h4>
                </div>
                <div class="col-md-3">
                    <span class="text-muted small fw-bold text-uppercase d-block mb-1">Birim Fiyat</span>
                    <h4 class="fw-bold mb-0 text-dark">₺{{ number_format($price, 2, ',', '.') }}</h4>
                </div>
                <div class="col-md-3">
                    <span class="text-muted small fw-bold text-uppercase d-block mb-1">Toplam Stok Değeri</span>
                    <h3 class="fw-extrabold mb-0 text-success">₺{{ number_format($totalVal, 2, ',', '.') }}</h3>
                </div>
            </div>

            <h6 class="fw-bold text-dark mb-2">Malzeme Açıklaması & Saklama Koşulları</h6>
            <div class="p-3 border rounded-3 bg-light bg-opacity-50 text-dark mb-4" style="white-space: pre-line;">
                {{ $stockCard->description ?: 'Malzeme detay açıklaması girilmemiş.' }}

                {{ $stockCard->notes ? "\nNotlar: " . $stockCard->notes : '' }}
            </div>
        </div>
    </div>
</div>

@endsection
