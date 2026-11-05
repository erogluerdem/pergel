@extends('layouts.master')

@section('title', 'Tedarikçi Teklifini Düzenle')

@section('topbar-title', 'Satın Alma - Teklif Düzenle')

@push('styles')
<style>
    .purchasing-hero-banner {
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        background: linear-gradient(135deg, #0f172a 0%, #d97706 50%, #f59e0b 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    .purchasing-form-card {
        border-radius: 20px;
        padding: 2rem;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    html[data-bs-theme="dark"] .purchasing-form-card {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
</style>
@endpush

@section('content')

<div class="purchasing-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <a href="{{ route('purchasing.quotations.show', $id) }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                    <i class="ri-arrow-left-line me-1"></i>Teklif Detayı
                </a>
            </div>
            <h1 class="fw-extrabold text-white mb-1">Tedarikçi Fiyat Teklifini Düzenle</h1>
            <p class="text-white-50 mb-0 fs-6">Teklif tutarı, geçerlilik süresi, durum ve tedarikçi notlarını güncelleyin.</p>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="purchasing-form-card">
            <form action="{{ route('purchasing.quotations.index') }}" method="GET">

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="quote_number" class="form-label fw-bold small text-dark">Teklif Referans No <span class="text-danger">*</span></label>
                        <input type="text" id="quote_number" class="form-control font-monospace fw-bold" value="{{ old('quote_number', $quotation->quote_number ?? 'QT-2026-000101') }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="total_amount" class="form-label fw-bold small text-dark">Toplam Teklif Tutarı (₺) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" id="total_amount" class="form-control text-primary fw-bold" value="{{ old('total_amount', $quotation->total_amount ?? 1250000.00) }}" required>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="valid_until" class="form-label fw-bold small text-dark">Son Geçerlilik Tarihi <span class="text-danger">*</span></label>
                        <input type="date" id="valid_until" class="form-control" value="{{ date('Y-m-d', strtotime('+20 days')) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="status" class="form-label fw-bold small text-dark">Teklif Durumu</label>
                        <select id="status" class="form-select">
                            <option value="pending" {{ ($quotation->status ?? '') === 'pending' ? 'selected' : '' }}>Değerlendirmede</option>
                            <option value="approved" {{ ($quotation->status ?? '') === 'approved' ? 'selected' : '' }}>Onaylandı (Kazanan Teklif)</option>
                            <option value="rejected" {{ ($quotation->status ?? '') === 'rejected' ? 'selected' : '' }}>Reddedildi</option>
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="notes" class="form-label fw-bold small text-dark">Tedarikçi Özel Koşulları & Vade Notları</label>
                    <textarea id="notes" class="form-control" rows="4">{{ old('notes', $quotation->notes ?? 'C35/40 hazır beton ve pompa teslimi birim fiyat teklifi. Vadesi 45 gün.') }}</textarea>
                </div>

                <div class="d-flex justify-content-between align-items-center pt-3 border-top border-opacity-10">
                    <a href="{{ route('purchasing.quotations.show', $id) }}" class="btn btn-outline-secondary rounded-pill px-4">İptal</a>
                    <button type="submit" class="btn btn-warning text-dark rounded-pill px-5 fw-bold shadow-sm">
                        <i class="ri-save-line me-1"></i>Değişiklikleri Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
