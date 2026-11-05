@extends('layouts.master')

@section('title', 'Döviz İşlemini Düzenle')

@section('topbar-title', 'Döviz İşlemleri - Düzenle')

@push('styles')
<style>
    .currency-hero-banner {
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        background: linear-gradient(135deg, #0f172a 0%, #d97706 50%, #f59e0b 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    .currency-form-card {
        border-radius: 20px;
        padding: 2rem;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    html[data-bs-theme="dark"] .currency-form-card {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
</style>
@endpush

@section('content')

<div class="currency-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <a href="{{ route('currency_exchanges.show', $currencyExchange) }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                    <i class="ri-arrow-left-line me-1"></i>İşlem Detayı
                </a>
            </div>
            <h1 class="fw-extrabold text-white mb-1">Döviz Alım/Satım İşlemini Düzenle</h1>
            <p class="text-white-50 mb-0 fs-6">İşlem kuru, banka komisyonu ve tarih güncellemelerini kaydedin.</p>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="currency-form-card">
            <form action="{{ route('currency_exchanges.update', $currencyExchange) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="exchange_rate" class="form-label fw-bold small text-dark">İşlem Kuru <span class="text-danger">*</span></label>
                        <input type="number" step="0.0001" name="exchange_rate" id="exchange_rate" class="form-control font-monospace fw-bold" value="{{ old('exchange_rate', $currencyExchange->exchange_rate) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="commission_percentage" class="form-label fw-bold small text-dark">Banka Komisyon Oranı (%)</label>
                        <input type="number" step="0.01" name="commission_percentage" id="commission_percentage" class="form-control" value="{{ old('commission_percentage', $currencyExchange->commission_percentage) }}">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="exchange_date" class="form-label fw-bold small text-dark">İşlem Tarihi <span class="text-danger">*</span></label>
                    <input type="date" name="exchange_date" id="exchange_date" class="form-control" value="{{ old('exchange_date', $currencyExchange->exchange_date ? $currencyExchange->exchange_date->format('Y-m-d') : '') }}" required>
                </div>

                <div class="mb-4">
                    <label for="notes" class="form-label fw-bold small text-dark">İşlem Notları & Açıklama</label>
                    <textarea name="notes" id="notes" class="form-control" rows="4">{{ old('notes', $currencyExchange->notes) }}</textarea>
                </div>

                <div class="d-flex justify-content-between align-items-center pt-3 border-top border-opacity-10">
                    <a href="{{ route('currency_exchanges.show', $currencyExchange) }}" class="btn btn-outline-secondary rounded-pill px-4">İptal</a>
                    <button type="submit" class="btn btn-warning text-dark rounded-pill px-5 fw-bold shadow-sm">
                        <i class="ri-save-line me-1"></i>Değişiklikleri Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
