@extends('layouts.master')

@section('title', 'Stok Kartını Düzenle')

@section('topbar-title', 'Finans - Stok Düzenle')

@push('styles')
<style>
    .stock-hero-banner {
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        background: linear-gradient(135deg, #0f172a 0%, #d97706 50%, #f59e0b 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    .stock-form-card {
        border-radius: 20px;
        padding: 2rem;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    html[data-bs-theme="dark"] .stock-form-card {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
</style>
@endpush

@section('content')

<div class="stock-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <a href="{{ route('finance.stock.cards.show', $id) }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                    <i class="ri-arrow-left-line me-1"></i>Stok Detayı
                </a>
            </div>
            <h1 class="fw-extrabold text-white mb-1">Stok Kartını Düzenle</h1>
            <p class="text-white-50 mb-0 fs-6">Stok miktarı, birim fiyat, min/max limitleri ve ambar lokasyonunu güncelleyin.</p>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="stock-form-card">
            <form action="{{ route('finance.stock.cards') }}" method="GET">

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="code" class="form-label fw-bold small text-dark">Stok Kodu <span class="text-danger">*</span></label>
                        <input type="text" id="code" class="form-control font-monospace fw-bold" value="{{ old('code', $stockCard->code ?? 'STK-00101') }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="name" class="form-label fw-bold small text-dark">Malzeme Adı <span class="text-danger">*</span></label>
                        <input type="text" id="name" class="form-control" value="{{ old('name', $stockCard->name ?? 'CEM I 42.5R Portland Çimento') }}" required>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label for="current_stock" class="form-label fw-bold small text-dark">Mevcut Stok Miktarı <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" id="current_stock" class="form-control fw-bold" value="{{ old('current_stock', $stockCard->current_stock ?? 650.00) }}" required>
                    </div>

                    <div class="col-md-4">
                        <label for="unit_price" class="form-label fw-bold small text-dark">Birim Fiyat (₺) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" id="unit_price" class="form-control text-primary fw-bold" value="{{ old('unit_price', $stockCard->unit_price ?? 185.00) }}" required>
                    </div>

                    <div class="col-md-4">
                        <label for="location" class="form-label fw-bold small text-dark">Ambar Lokasyonu</label>
                        <input type="text" id="location" class="form-control" value="{{ old('location', $stockCard->location ?? 'Şantiye A Deposu - Raf 04') }}">
                    </div>
                </div>

                <div class="mb-4">
                    <label for="description" class="form-label fw-bold small text-dark">Malzeme Detay Açıklaması</label>
                    <textarea id="description" class="form-control" rows="3">{{ old('description', $stockCard->description ?? 'A-Blok betonarme ve sıva imalatları için yüksek dayanımlı torbalı çimento.') }}</textarea>
                </div>

                <div class="d-flex justify-content-between align-items-center pt-3 border-top border-opacity-10">
                    <a href="{{ route('finance.stock.cards.show', $id) }}" class="btn btn-outline-secondary rounded-pill px-4">İptal</a>
                    <button type="submit" class="btn btn-warning text-dark rounded-pill px-5 fw-bold shadow-sm">
                        <i class="ri-save-line me-1"></i>Değişiklikleri Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
