@extends('layouts.master')

@section('title', 'Yeni Stok Fişi Düzenle')

@section('topbar-title', 'Finans - Yeni Stok Fişi')

@push('styles')
<style>
    .movement-hero-banner {
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        background: linear-gradient(135deg, #0f172a 0%, #2563eb 50%, #4f46e5 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    .movement-form-card {
        border-radius: 20px;
        padding: 2rem;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    html[data-bs-theme="dark"] .movement-form-card {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
</style>
@endpush

@section('content')

<div class="movement-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <a href="{{ route('finance.stock.movements') }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                    <i class="ri-arrow-left-line me-1"></i>Stok Hareketleri Panosu
                </a>
            </div>
            <h1 class="fw-extrabold text-white mb-1">Yeni Ambar Stok Fişi Düzenle</h1>
            <p class="text-white-50 mb-0 fs-6">Ambar malzeme girişi, şantiye çıkışı veya depolar arası transfer fişi kaydı.</p>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="movement-form-card">
            <form action="{{ route('finance.stock.movements') }}" method="GET">

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="reference_no" class="form-label fw-bold small text-dark">Fiş / Referans No <span class="text-danger">*</span></label>
                        <input type="text" id="reference_no" class="form-control font-monospace fw-bold" value="STK-IN-2026-{{ rand(100, 999) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="type" class="form-label fw-bold small text-dark">Hareket Tipi <span class="text-danger">*</span></label>
                        <select id="type" class="form-select" required>
                            <option value="in">Ambar Giriş Fişi (+)</option>
                            <option value="out">Saha Çıkış Fişi (-)</option>
                            <option value="transfer">Depo Transfer Fişi (⇆)</option>
                        </select>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="stock_card_id" class="form-label fw-bold small text-dark">Stok Kartı <span class="text-danger">*</span></label>
                        <select name="stock_card_id" id="stock_card_id" class="form-select" required>
                            <option value="">Stok kartı seçin...</option>
                            @foreach($stockCards as $sc)
                                <option value="{{ $sc->id }}">{{ $sc->code }} - {{ $sc->name }} (Mevcut: {{ number_format($sc->current_stock, 0) }} {{ $sc->unit }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="quantity" class="form-label fw-bold small text-dark">İşlem Miktarı <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" id="quantity" class="form-control fw-bold" placeholder="0.00" required>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="unit_price" class="form-label fw-bold small text-dark">Birim Fiyat (₺) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" id="unit_price" class="form-control text-primary fw-bold" placeholder="0.00" required>
                    </div>

                    <div class="col-md-6">
                        <label for="total_amount" class="form-label fw-bold small text-dark">Toplam İşlem Tutarı (₺) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" id="total_amount" class="form-control text-primary fw-bold" placeholder="0.00" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="description" class="form-label fw-bold small text-dark">İrsaliye Notu & Teslimat Açıklaması</label>
                    <textarea id="description" class="form-control" rows="3" placeholder="Sevk irsaliyesi no, teslim eden usta ve teslim ambarı..."></textarea>
                </div>

                <div class="d-flex justify-content-between align-items-center pt-3 border-top border-opacity-10">
                    <a href="{{ route('finance.stock.movements') }}" class="btn btn-outline-secondary rounded-pill px-4">İptal</a>
                    <button type="submit" class="btn btn-primary text-white rounded-pill px-5 fw-bold shadow-sm" style="background:#2563eb;">
                        <i class="ri-save-line me-1"></i>Stok Fişini Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
