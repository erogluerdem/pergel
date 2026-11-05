@extends('layouts.master')

@section('title', 'Tahsilat Zaman Çizelgesi')

@section('topbar-title', 'Satış Raporları - Tahsilat Zamanı')

@push('styles')
<style>
    .sales-acc-hero-banner {
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        background: linear-gradient(135deg, #0f172a 0%, #059669 50%, #047857 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    .sales-acc-glass-card {
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    html[data-bs-theme="dark"] .sales-acc-glass-card {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
</style>
@endpush

@section('content')

<div class="sales-acc-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <a href="{{ route('reports.sales.accounting_summary') }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                    <i class="ri-arrow-left-line me-1"></i>Muhasebe Özeti
                </a>
            </div>
            <h1 class="fw-extrabold text-white mb-1">Tahsilat Zaman Çizelgesi (Timeline)</h1>
            <p class="text-white-50 mb-0 fs-6">Erken ödemeler, tam vadesinde ödemeler ve geciken tahsilat analizleri.</p>
        </div>
    </div>
</div>

<div class="sales-acc-glass-card text-center py-5">
    <i class="ri-history-line fs-1 text-emerald mb-2 d-block" style="color:#059669;"></i>
    <h5 class="fw-bold text-dark">Tahsilat Zaman Çizelgesi Analizi</h5>
    <p class="text-muted mb-0">Tüm taksit tahsilat aksiyonları zaman ekseninde izlenmektedir.</p>
</div>

@endsection
