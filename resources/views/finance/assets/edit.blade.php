@extends('layouts.master')

@section('title', 'Demirbaş Düzenle')

@section('topbar-title', 'Finans - Demirbaş Düzenle')

@push('styles')
<style>
    .asset-hero-banner {
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        background: linear-gradient(135deg, #0f172a 0%, #0d9488 50%, #0f766e 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    .asset-form-card {
        border-radius: 20px;
        padding: 2rem;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    html[data-bs-theme="dark"] .asset-form-card {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
</style>
@endpush

@section('content')

<div class="asset-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <a href="{{ route('finance.assets.show', $asset->id) }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                    <i class="ri-arrow-left-line me-1"></i>Demirbaş Detayı
                </a>
            </div>
            <h1 class="fw-extrabold text-white mb-1">Demirbaş Bilgilerini Düzenle</h1>
            <p class="text-white-50 mb-0 fs-6">Kullanım durumu, net defter değeri ve lokasyon güncellemesi.</p>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="asset-form-card">
            <form action="#" method="POST">
                @csrf

                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label for="asset_code" class="form-label fw-bold small text-dark">Demirbaş Kodu</label>
                        <input type="text" name="asset_code" id="asset_code" class="form-control font-monospace fw-bold" value="{{ $asset->asset_code }}" readonly>
                    </div>

                    <div class="col-md-8">
                        <label for="name" class="form-label fw-bold small text-dark">Demirbaş Tanımı <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $asset->name) }}" required>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="current_value" class="form-label fw-bold small text-dark">Güncel Net Defter Değeri (₺)</label>
                        <input type="number" step="0.01" name="current_value" id="current_value" class="form-control fw-bold" value="{{ old('current_value', $asset->current_value) }}">
                    </div>

                    <div class="col-md-6">
                        <label for="status" class="form-label fw-bold small text-dark">Durum <span class="text-danger">*</span></label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="active" {{ $asset->status === 'active' ? 'selected' : '' }}>Kullanımda</option>
                            <option value="maintenance" {{ $asset->status === 'maintenance' ? 'selected' : '' }}>Bakımda</option>
                            <option value="disposed" {{ $asset->status === 'disposed' ? 'selected' : '' }}>Hurda/Pasif</option>
                        </select>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center pt-3 border-top border-opacity-10">
                    <a href="{{ route('finance.assets.show', $asset->id) }}" class="btn btn-outline-secondary rounded-pill px-4">İptal</a>
                    <button type="submit" class="btn btn-teal text-white rounded-pill px-5 fw-bold shadow-sm" style="background:#0d9488;">
                        <i class="ri-save-line me-1"></i>Değişiklikleri Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
