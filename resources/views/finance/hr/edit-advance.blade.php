@extends('layouts.master')

@section('title', 'Avans Talebini Düzenle')

@section('topbar-title', 'Personel Avansları - Düzenle')

@push('styles')
<style>
    .hr-advance-hero-banner {
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        background: linear-gradient(135deg, #0f172a 0%, #d97706 50%, #f59e0b 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    .hr-advance-form-card {
        border-radius: 20px;
        padding: 2rem;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    html[data-bs-theme="dark"] .hr-advance-form-card {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
</style>
@endpush

@section('content')

<div class="hr-advance-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <a href="{{ route('finance.hr.advances.show', $id) }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                    <i class="ri-arrow-left-line me-1"></i>Talep Detayı
                </a>
            </div>
            <h1 class="fw-extrabold text-white mb-1">Avans Talebini Düzenle</h1>
            <p class="text-white-50 mb-0 fs-6">Avans tutarı, talep gerekçesi ve onay durumu güncellemeleri.</p>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="hr-advance-form-card">
            <form action="{{ route('finance.hr.advances') }}" method="GET">

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="amount" class="form-label fw-bold small text-dark">Avans Tutarı (₺) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" id="amount" class="form-control text-teal fw-bold" value="{{ old('amount', $advance->amount ?? 15000.00) }}" required style="color:#0d9488;">
                    </div>

                    <div class="col-md-6">
                        <label for="status" class="form-label fw-bold small text-dark">Onay Durumu</label>
                        <select id="status" class="form-select">
                            <option value="pending" {{ ($advance->status ?? '') === 'pending' ? 'selected' : '' }}>Onay Bekliyor</option>
                            <option value="approved" {{ ($advance->status ?? '') === 'approved' ? 'selected' : '' }}>Onaylandı</option>
                            <option value="paid" {{ ($advance->status ?? '') === 'paid' ? 'selected' : '' }}>Ödendi (Mahsup Oldu)</option>
                            <option value="rejected" {{ ($advance->status ?? '') === 'rejected' ? 'selected' : '' }}>Reddedildi</option>
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="description" class="form-label fw-bold small text-dark">Avans Gerekçesi & Detaylar</label>
                    <textarea id="description" class="form-control" rows="4">{{ old('description', $advance->description ?? 'Şantiye intikal ve acil saha ekipman tedariki için maaş avansı talebi.') }}</textarea>
                </div>

                <div class="d-flex justify-content-between align-items-center pt-3 border-top border-opacity-10">
                    <a href="{{ route('finance.hr.advances.show', $id) }}" class="btn btn-outline-secondary rounded-pill px-4">İptal</a>
                    <button type="submit" class="btn btn-warning text-dark rounded-pill px-5 fw-bold shadow-sm">
                        <i class="ri-save-line me-1"></i>Değişiklikleri Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
