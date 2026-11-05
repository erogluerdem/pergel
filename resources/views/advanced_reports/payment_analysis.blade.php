@extends('layouts.master')

@section('title', 'Taksit & Ödeme Analizi')

@section('topbar-title', 'Gelişmiş Raporlar - Ödeme Analizi')

@push('styles')
<style>
    .adv-rep-hero-banner {
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        background: linear-gradient(135deg, #0f172a 0%, #0284c7 50%, #0369a1 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    .adv-rep-glass-card {
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    html[data-bs-theme="dark"] .adv-rep-glass-card {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
</style>
@endpush

@section('content')

<div class="adv-rep-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <a href="{{ route('advanced_reports.index') }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                    <i class="ri-arrow-left-line me-1"></i>Raporlar Hub
                </a>
            </div>
            <h1 class="fw-extrabold text-white mb-1">Taksit & Ödeme Analizi</h1>
            <p class="text-white-50 mb-0 fs-6">Sözleşme taksit planları ve hakediş tahsilat performansları.</p>
        </div>
    </div>
</div>

<div class="adv-rep-glass-card text-center py-5">
    <i class="ri-coin-line fs-1 text-warning mb-2 d-block"></i>
    <h5 class="fw-bold text-dark">Taksit Ödeme Analiz Panosu</h5>
    <p class="text-muted mb-0">Tüm taksit tahsilat bakiyeleri ve vadesi geçen borçlar izlenmektedir.</p>
</div>

@endsection
