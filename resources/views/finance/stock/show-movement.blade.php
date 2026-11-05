@extends('layouts.master')

@section('title', 'Stok Hareket Fişi Detayı')

@section('topbar-title', 'Finans - Stok Fişi Detayı')

@push('styles')
<style>
    .movement-hero-banner {
        border-radius: 20px;
        padding: 2.2rem;
        margin-bottom: 2rem;
        background: linear-gradient(135deg, #0f172a 0%, #2563eb 50%, #4f46e5 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    .movement-glass-card {
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    html[data-bs-theme="dark"] .movement-glass-card {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
</style>
@endpush

@section('content')

@php
    $refNo = $movement->reference_no ?? 'STK-IN-2026-001';
    $matName = $movement->stockCard->name ?? 'CEM I 42.5R Portland Çimento';
    $qty = $movement->quantity ?? 500.00;
    $unitPrice = $movement->unit_price ?? 185.00;
    $totalVal = $movement->total_amount ?? 92500.00;
@endphp

<!-- Hero Banner -->
<div class="movement-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <a href="{{ route('finance.stock.movements') }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                    <i class="ri-arrow-left-line me-1"></i>Stok Hareketleri Panosu
                </a>
                <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-3 py-1 font-monospace fw-bold">
                    {{ $refNo }}
                </span>
            </div>
            <h1 class="fw-extrabold text-white mb-1">Ambar Stok Hareket Fişi Detayı</h1>
            <p class="text-white-50 mb-0 fs-6">Malzeme: {{ $matName }} — Düzenleyen: {{ $movement->user->name ?? 'Ambar Sorumlusu' }}</p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('finance.stock.movements.edit', $id) }}" class="btn btn-warning text-dark rounded-pill px-4 fw-bold shadow-sm">
                <i class="ri-edit-line me-1"></i>Stok Fişini Düzenle
            </a>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="movement-glass-card">
            <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
                <div>
                    <h3 class="fw-extrabold text-dark mb-0">{{ $matName }}</h3>
                    <small class="text-muted">İşlem Tarihi: {{ $movement->created_at ? $movement->created_at->format('d.m.Y H:i') : date('d.m.Y H:i') }}</small>
                </div>
                <div>
                    @if(($movement->type ?? 'in') === 'in')
                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-30 rounded-pill px-4 py-2 fw-bold fs-6">Ambar Giriş Fişi</span>
                    @elseif(($movement->type ?? '') === 'out')
                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-30 rounded-pill px-4 py-2 fw-bold fs-6">Saha Çıkış Fişi</span>
                    @else
                        <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-30 rounded-pill px-4 py-2 fw-bold fs-6">Transfer Fişi</span>
                    @endif
                </div>
            </div>

            <!-- Summary Specs -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <span class="text-muted small fw-bold text-uppercase d-block mb-1">İşlem Miktarı</span>
                    <h3 class="fw-extrabold mb-0 text-primary">
                        {{ number_format($qty, 2, ',', '.') }} {{ $movement->stockCard->unit ?? 'Adet' }}
                    </h3>
                </div>
                <div class="col-md-4">
                    <span class="text-muted small fw-bold text-uppercase d-block mb-1">Birim Fiyat (₺)</span>
                    <h4 class="fw-bold mb-0 text-dark">₺{{ number_format($unitPrice, 2, ',', '.') }}</h4>
                </div>
                <div class="col-md-4">
                    <span class="text-muted small fw-bold text-uppercase d-block mb-1">Toplam İşlem Tutarı</span>
                    <h3 class="fw-extrabold mb-0 text-success">₺{{ number_format($totalVal, 2, ',', '.') }}</h3>
                </div>
            </div>

            <h6 class="fw-bold text-dark mb-2">Sevk İrsaliyesi & İşlem Notları</h6>
            <div class="p-3 border rounded-3 bg-light bg-opacity-50 text-dark mb-4" style="white-space: pre-line;">
                {{ $movement->description ?: 'İrsaliye ve şantiye teslim notu bulunmuyor.' }}
            </div>
        </div>
    </div>
</div>

@endsection
