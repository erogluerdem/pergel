@extends('layouts.master')

@section('title', 'Nakit Akış Tahminini Düzenle')

@section('topbar-title', 'Nakit Akış - Düzenle')

@push('styles')
<style>
    .forecast-hero-banner {
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        background: linear-gradient(135deg, #0f172a 0%, #d97706 50%, #f59e0b 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    .forecast-form-card {
        border-radius: 20px;
        padding: 2rem;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    html[data-bs-theme="dark"] .forecast-form-card {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
</style>
@endpush

@section('content')

<div class="forecast-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <a href="{{ route('finance.forecast.show', $forecast->id) }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                    <i class="ri-arrow-left-line me-1"></i>Tahmin Detayı
                </a>
            </div>
            <h1 class="fw-extrabold text-white mb-1">Nakit Akış Tahminini Düzenle</h1>
            <p class="text-white-50 mb-0 fs-6">{{ $forecast->forecast_date ? $forecast->forecast_date->format('M Y') : '' }} Dönemi</p>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="forecast-form-card">
            <form action="{{ route('finance.forecast.update', $forecast->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="inflow_planned" class="form-label fw-bold small text-dark">Planlanan Tahsilat / Nakit Girişi (₺) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="inflow_planned" id="inflow_planned" class="form-control text-success fw-bold" value="{{ old('inflow_planned', $forecast->inflow_planned) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="outflow_planned" class="form-label fw-bold small text-dark">Planlanan Ödeme / Nakit Çıkışı (₺) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="outflow_planned" id="outflow_planned" class="form-control text-danger fw-bold" value="{{ old('outflow_planned', $forecast->outflow_planned) }}" required>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="confidence_level" class="form-label fw-bold small text-dark">Tahmin Güven Seviyesi (%) <span class="text-danger">*</span></label>
                        <input type="number" min="0" max="100" name="confidence_level" id="confidence_level" class="form-control" value="{{ old('confidence_level', $forecast->confidence_level) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="status" class="form-label fw-bold small text-dark">Tahmin Durumu <span class="text-danger">*</span></label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="draft" {{ old('status', $forecast->status) == 'draft' ? 'selected' : '' }}>Taslak</option>
                            <option value="confirmed" {{ old('status', $forecast->status) == 'confirmed' ? 'selected' : '' }}>Onaylı</option>
                            <option value="revised" {{ old('status', $forecast->status) == 'revised' ? 'selected' : '' }}>Revize Edildi</option>
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="notes" class="form-label fw-bold small text-dark">Finansal Notlar & Gerekçeler</label>
                    <textarea name="notes" id="notes" class="form-control" rows="4">{{ old('notes', $forecast->notes) }}</textarea>
                </div>

                <div class="d-flex justify-content-between align-items-center pt-3 border-top border-opacity-10">
                    <a href="{{ route('finance.forecast.show', $forecast->id) }}" class="btn btn-outline-secondary rounded-pill px-4">İptal</a>
                    <button type="submit" class="btn btn-warning text-dark rounded-pill px-5 fw-bold shadow-sm">
                        <i class="ri-save-line me-1"></i>Değişiklikleri Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
