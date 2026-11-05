@extends('layouts.master')

@section('title', 'Kuyruk Takip & Canlı İlerleme')

@section('topbar-title', 'Veri Transferi - Canlı Takip')

@push('styles')
<style>
    .die-hero-banner {
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        background: linear-gradient(135deg, #0f172a 0%, #2563eb 50%, #1d4ed8 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    .die-glass-card {
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 1.5rem;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    html[data-bs-theme="dark"] .die-glass-card {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
</style>
@endpush

@section('content')

<div class="die-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <a href="{{ route('data-import-export.index') }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                    <i class="ri-arrow-left-line me-1"></i>Veri Transfer Merkezi
                </a>
            </div>
            <h1 class="fw-extrabold text-white mb-1">Canlı Aktarım İlerlemesi</h1>
            <p class="text-white-50 mb-0 fs-6">Arka plan veri kuyruğu işleniyor...</p>
        </div>
    </div>
</div>

<div class="die-glass-card text-center py-5">
    <div class="spinner-border text-primary mb-3" style="width: 3rem; height: 3rem;" role="status"></div>
    <h4 class="fw-bold text-dark mb-2">İşlem Devam Ediyor</h4>
    <p class="text-muted mb-0">Lütfen sayfayı kapatmayınız, işlem tamamlandığında otomatik olarak detay sayfasına yönlendirileceksiniz.</p>
</div>

@endsection
