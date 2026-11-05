@extends('layouts.master')

@section('title', 'Tüm Nakit Akış Tahminleri')

@section('topbar-title', 'Nakit Akış Tahmin Listesi')

@push('styles')
<style>
    .forecast-hero-banner {
        border-radius: 20px;
        padding: 2.2rem;
        margin-bottom: 2rem;
        background: linear-gradient(135deg, #0f172a 0%, #047857 50%, #059669 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    .forecast-glass-card {
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    html[data-bs-theme="dark"] .forecast-glass-card {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
</style>
@endpush

@section('content')

<!-- Hero Header Banner -->
<div class="forecast-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <a href="{{ route('finance.forecast.dashboard') }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                    <i class="ri-dashboard-line me-1"></i>Tahmin Panosu
                </a>
            </div>
            <h1 class="fw-extrabold text-white mb-1">Tüm Nakit Akış Tahminleri</h1>
            <p class="text-white-50 mb-0 fs-6">Projelere ait geçmiş ve gelecek tüm nakit giriş/çıkış projeksiyon kayıtları.</p>
        </div>

        <a href="{{ route('finance.forecast.create') }}" class="btn btn-emerald text-white rounded-pill px-4 fw-bold shadow-sm" style="background:#059669;">
            <i class="ri-add-circle-line me-1"></i>Yeni Tahmin Ekle
        </a>
    </div>
</div>

<!-- Table Card -->
<div class="forecast-glass-card">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">Proje</th>
                    <th>Tahmin Tarihi</th>
                    <th>Planlanan Giriş</th>
                    <th>Planlanan Çıkış</th>
                    <th>Net Nakit Akışı</th>
                    <th>Kapanış Bakiyesi</th>
                    <th>Güven %</th>
                    <th>Durum</th>
                    <th class="text-end pe-4">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                @forelse($forecasts as $forecast)
                    @php
                        $net = $forecast->inflow_planned - $forecast->outflow_planned;
                    @endphp
                    <tr>
                        <td class="ps-4"><strong class="text-dark">{{ $forecast->project?->name ?? 'Genel Proje' }}</strong></td>
                        <td><span class="badge bg-dark bg-opacity-10 text-dark border font-monospace">{{ $forecast->forecast_date ? $forecast->forecast_date->format('M Y') : '-' }}</span></td>
                        <td><span class="text-success fw-bold">+₺{{ number_format($forecast->inflow_planned, 2, ',', '.') }}</span></td>
                        <td><span class="text-danger fw-bold">-₺{{ number_format($forecast->outflow_planned, 2, ',', '.') }}</span></td>
                        <td><strong class="{{ $net >= 0 ? 'text-success' : 'text-danger' }}">{{ $net >= 0 ? '+' : '' }}₺{{ number_format($net, 2, ',', '.') }}</strong></td>
                        <td><strong class="text-dark">₺{{ number_format($forecast->closing_balance, 2, ',', '.') }}</strong></td>
                        <td><span class="badge bg-emerald bg-opacity-10 text-emerald border" style="color:#059669;">%{{ $forecast->confidence_level }}</span></td>
                        <td>
                            @if($forecast->status === 'confirmed')
                                <span class="badge bg-success bg-opacity-10 text-success border rounded-pill px-3 py-1 fw-bold">Onaylı</span>
                            @elseif($forecast->status === 'revised')
                                <span class="badge bg-info bg-opacity-10 text-info border rounded-pill px-3 py-1 fw-bold">Revize</span>
                            @else
                                <span class="badge bg-warning bg-opacity-10 text-warning border rounded-pill px-3 py-1 fw-bold">Taslak</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <div class="btn-group gap-1">
                                <a href="{{ route('finance.forecast.show', $forecast->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                    <i class="ri-eye-line me-1"></i>İncele
                                </a>
                                <a href="{{ route('finance.forecast.edit', $forecast->id) }}" class="btn btn-sm btn-outline-warning rounded-circle" title="Düzenle">
                                    <i class="ri-pencil-line"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center py-4 text-muted">Tahmin kaydı bulunamadı.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top border-opacity-10">
        <small class="text-muted">Toplam {{ $forecasts->total() }} kayıt</small>
        <div>{{ $forecasts->links() }}</div>
    </div>
</div>

@endsection
