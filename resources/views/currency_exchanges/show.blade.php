@extends('layouts.master')

@section('title', 'Döviz İşlem Detayı')

@section('topbar-title', 'Döviz İşlemleri - Detay')

@push('styles')
<style>
    .currency-hero-banner {
        border-radius: 20px;
        padding: 2.2rem;
        margin-bottom: 2rem;
        background: linear-gradient(135deg, #0f172a 0%, #0369a1 50%, #0284c7 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    .currency-glass-card {
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    html[data-bs-theme="dark"] .currency-glass-card {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }

    .currency-spec-box {
        border-radius: 14px;
        padding: 1.25rem;
        background: rgba(15, 23, 42, 0.03);
        border: 1px solid rgba(0, 0, 0, 0.06);
    }

    html[data-bs-theme="dark"] .currency-spec-box {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.06);
    }
</style>
@endpush

@section('content')

@php
    $fromCode = \App\Models\Currency::find($currencyExchange->from_currency)?->code ?? $currencyExchange->from_currency;
    $toCode = \App\Models\Currency::find($currencyExchange->to_currency)?->code ?? $currencyExchange->to_currency;
@endphp

<!-- Hero Banner -->
<div class="currency-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <a href="{{ route('currency_exchanges.index') }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                    <i class="ri-arrow-left-line me-1"></i>Döviz Panosu
                </a>
                <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-3 py-1 font-monospace fw-bold">
                    {{ $fromCode }} <i class="ri-arrow-right-line mx-1"></i> {{ $toCode }}
                </span>
            </div>
            <h1 class="fw-extrabold text-white mb-1">Döviz İşlem Detayı</h1>
            <p class="text-white-50 mb-0 fs-6">İşlem Tarihi: {{ $currencyExchange->exchange_date ? $currencyExchange->exchange_date->format('d.m.Y H:i') : '-' }}</p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('currency_exchanges.edit', $currencyExchange) }}" class="btn btn-warning text-dark rounded-pill px-4 fw-bold shadow-sm">
                <i class="ri-edit-line me-1"></i>İşlemi Düzenle
            </a>
        </div>
    </div>
</div>

<!-- 4 Spec Summary Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="currency-spec-box">
            <span class="text-muted small fw-bold text-uppercase d-block mb-1">Çıkan Tutar (Kaynak)</span>
            <h3 class="fw-extrabold mb-0 text-danger">-{{ number_format($currencyExchange->from_amount, 2, ',', '.') }} {{ $fromCode }}</h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="currency-spec-box">
            <span class="text-muted small fw-bold text-uppercase d-block mb-1">İşlem Kuru</span>
            <h3 class="fw-extrabold mb-0 text-dark">{{ number_format($currencyExchange->exchange_rate, 4, ',', '.') }}</h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="currency-spec-box">
            <span class="text-muted small fw-bold text-uppercase d-block mb-1">Giren Tutar (Hedef)</span>
            <h3 class="fw-extrabold mb-0 text-success">+{{ number_format($currencyExchange->to_amount, 2, ',', '.') }} {{ $toCode }}</h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="currency-spec-box">
            <span class="text-muted small fw-bold text-uppercase d-block mb-1">Banka Komisyonu</span>
            <h3 class="fw-extrabold mb-0 text-warning">₺{{ number_format($currencyExchange->commission_amount, 2, ',', '.') }}</h3>
        </div>
    </div>
</div>

<!-- Main Info Card -->
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="currency-glass-card">
            <h5 class="fw-bold text-dark mb-3"><i class="ri-information-line text-sky me-2" style="color:#0284c7;"></i>İşlem Açıklaması & Künye</h5>
            <div class="list-group list-group-flush border-0 mb-4">
                <div class="list-group-item d-flex justify-content-between px-0 bg-transparent">
                    <span class="text-muted">İlişkili Fatura / Belge:</span>
                    <strong class="text-dark">{{ $currencyExchange->invoice?->invoice_number ?? 'Fatura Bağlanmamış' }}</strong>
                </div>
                <div class="list-group-item d-flex justify-content-between px-0 bg-transparent">
                    <span class="text-muted">Komisyon Oranı (%):</span>
                    <strong class="text-dark">%{{ number_format($currencyExchange->commission_percentage, 2) }}</strong>
                </div>
                <div class="list-group-item d-flex justify-content-between px-0 bg-transparent">
                    <span class="text-muted">Kayıt Tarihi:</span>
                    <strong class="text-dark">{{ $currencyExchange->created_at ? $currencyExchange->created_at->format('d.m.Y H:i') : '-' }}</strong>
                </div>
            </div>

            <h6 class="fw-bold text-dark mb-2">İşlem Notları</h6>
            <div class="p-3 border rounded-3 bg-light bg-opacity-50 text-dark" style="white-space: pre-line;">
                {{ $currencyExchange->notes ?: 'Not girilmedi.' }}
            </div>
        </div>
    </div>
</div>

@endsection
