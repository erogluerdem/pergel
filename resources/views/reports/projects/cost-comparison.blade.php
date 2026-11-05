@extends('layouts.master')

@section('title', 'Maliyet Karşılaştırması')

@section('topbar-title', 'Proje Raporları - Maliyet Kıyaslama')

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
            <h1 class="fw-extrabold text-white mb-1">Periyodik Maliyet & Satış Karşılaştırması</h1>
            <p class="text-white-50 mb-0 fs-6">Günlük, haftalık ve aylık periyotlarda maliyet-satış marjlarının kıyası.</p>
        </div>
    </div>
</div>

<div class="prj-fin-glass-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0 text-dark">
            <i class="ri-git-commit-line text-primary me-2"></i>Periyodik Kıyaslama Tablosu
        </h5>
        <span class="badge bg-primary bg-opacity-10 border border-primary border-opacity-30 rounded-pill px-3 py-1 fw-bold text-primary">
            {{ count($comparison) }} Periyot
        </span>
    </div>

    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">Zaman Periyodu</th>
                    <th>Satış Cirosu (₺)</th>
                    <th>Maliyet / Gider (₺)</th>
                    <th>Net Kar Marjı (₺)</th>
                    <th>Marj Oranı (%)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($comparison as $c)
                    <tr>
                        <td class="ps-4 font-monospace fw-bold text-dark">{{ $c['period_label'] }}</td>
                        <td><strong class="text-success">₺{{ number_format($c['sales'], 2, ',', '.') }}</strong></td>
                        <td>₺{{ number_format($c['costs'], 2, ',', '.') }}</td>
                        <td><strong class="text-primary">₺{{ number_format($c['margin'], 2, ',', '.') }}</strong></td>
                        <td><span class="badge bg-success bg-opacity-10 text-success border rounded-pill px-3 py-1">%{{ number_format($c['margin_percentage'], 1) }}</span></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">Kayıt bulunamadı.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
