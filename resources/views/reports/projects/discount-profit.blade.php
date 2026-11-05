@extends('layouts.master')

@section('title', 'İskonto & Kar Marjı Analizi')

@section('topbar-title', 'Proje Raporları - Kar Marjı')

@push('styles')
<style>
    .prj-fin-hero-banner {
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        background: linear-gradient(135deg, #0f172a 0%, #1e40af 50%, #1d4ed8 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    .prj-fin-glass-card {
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    html[data-bs-theme="dark"] .prj-fin-glass-card {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
</style>
@endpush

@section('content')

<div class="prj-fin-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <a href="{{ route('reports.projects.financial_analysis') }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                    <i class="ri-arrow-left-line me-1"></i>Finansal Analiz
                </a>
            </div>
            <h1 class="fw-extrabold text-white mb-1">İskonto & Kar Marjı Analizi</h1>
            <p class="text-white-50 mb-0 fs-6">Brüt satışlar, uygulanan iskontolar, net ciro ve kar marjı oranları.</p>
        </div>
    </div>
</div>

<div class="prj-fin-glass-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0 text-dark">
            <i class="ri-percent-line text-primary me-2"></i>İskonto & Kar Cetveli
        </h5>
        <span class="badge bg-primary bg-opacity-10 border border-primary border-opacity-30 rounded-pill px-3 py-1 fw-bold text-primary">
            {{ count($profitData) }} Şantiye
        </span>
    </div>

    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">Proje Adı</th>
                    <th>Brüt Satış (₺)</th>
                    <th>İskonto Tutarı (₺)</th>
                    <th>Net Satış (₺)</th>
                    <th>Net Kar (₺)</th>
                    <th>Kar Marjı %</th>
                </tr>
            </thead>
            <tbody>
                @forelse($profitData as $p)
                    <tr>
                        <td class="ps-4"><strong class="text-dark fs-6">{{ $p['project_name'] }}</strong></td>
                        <td>₺{{ number_format($p['gross_sales'], 2, ',', '.') }}</td>
                        <td>₺{{ number_format($p['discount_amount'], 2, ',', '.') }}</td>
                        <td><strong class="text-dark">₺{{ number_format($p['net_sales'], 2, ',', '.') }}</strong></td>
                        <td><strong class="text-success">₺{{ number_format($p['profit'], 2, ',', '.') }}</strong></td>
                        <td><span class="badge bg-success bg-opacity-10 text-success border rounded-pill px-3 py-1">%{{ number_format($p['profit_margin'], 1) }}</span></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">Kayıt bulunamadı.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
