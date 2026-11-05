@extends('layouts.master')

@section('title', 'Yeni Nakit Akış Tahmini Ekle')

@section('topbar-title', 'Nakit Akış - Yeni Tahmin')

@push('styles')
<style>
    .forecast-hero-banner {
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        background: linear-gradient(135deg, #0f172a 0%, #047857 50%, #059669 100%);
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
                <a href="{{ route('finance.forecast.dashboard') }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                    <i class="ri-arrow-left-line me-1"></i>Tahmin Panosu
                </a>
            </div>
            <h1 class="fw-extrabold text-white mb-1">Yeni Nakit Akış Tahmini Ekle</h1>
            <p class="text-white-50 mb-0 fs-6">Proje bazlı planlanan tahsilat (giriş) ve ödeme (çıkış) hedeflerini tanımlayın.</p>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="forecast-form-card">
            <form action="{{ route('finance.forecast.store') }}" method="POST">
                @csrf

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="project_id" class="form-label fw-bold small text-dark">İlgili Proje <span class="text-danger">*</span></label>
                        <select name="project_id" id="project_id" class="form-select" required>
                            <option value="">Proje seçin...</option>
                            @foreach($projects as $p)
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="forecast_date" class="form-label fw-bold small text-dark">Tahmin Dönemi / Tarihi <span class="text-danger">*</span></label>
                        <input type="date" name="forecast_date" id="forecast_date" class="form-control" value="{{ date('Y-m-01') }}" required>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="inflow_planned" class="form-label fw-bold small text-dark">Planlanan Tahsilat / Nakit Girişi (₺) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="inflow_planned" id="inflow_planned" class="form-control text-success fw-bold" placeholder="0.00" required>
                    </div>

                    <div class="col-md-6">
                        <label for="outflow_planned" class="form-label fw-bold small text-dark">Planlanan Ödeme / Nakit Çıkışı (₺) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="outflow_planned" id="outflow_planned" class="form-control text-danger fw-bold" placeholder="0.00" required>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="confidence_level" class="form-label fw-bold small text-dark">Tahmin Güven Seviyesi (%) <span class="text-danger">*</span></label>
                        <input type="number" min="0" max="100" name="confidence_level" id="confidence_level" class="form-control" value="85" required>
                    </div>

                    <div class="col-md-6">
                        <label for="status" class="form-label fw-bold small text-dark">Tahmin Durumu <span class="text-danger">*</span></label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="draft">Taslak</option>
                            <option value="confirmed">Onaylı</option>
                            <option value="revised">Revize Edildi</option>
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="notes" class="form-label fw-bold small text-dark">Finansal Notlar & Gerekçeler</label>
                    <textarea name="notes" id="notes" class="form-control" rows="4" placeholder="Tahmin varsayımları, taşeron ödeme takvimi ve beklenen müşteri tahsilatları..."></textarea>
                </div>

                <div class="d-flex justify-content-between align-items-center pt-3 border-top border-opacity-10">
                    <a href="{{ route('finance.forecast.dashboard') }}" class="btn btn-outline-secondary rounded-pill px-4">İptal</a>
                    <button type="submit" class="btn btn-emerald text-white rounded-pill px-5 fw-bold shadow-sm" style="background:#059669;">
                        <i class="ri-save-line me-1"></i>Tahmini Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
