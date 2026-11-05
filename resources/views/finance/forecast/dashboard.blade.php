@extends('layouts.master')

@section('title', 'Nakit Akış Tahmini & Finansal Projeksiyonlar')

@section('topbar-title', 'Nakit Akış Tahmini')

@push('styles')
<style>
    /* Hero Header Banner */
    .forecast-hero-banner {
        border-radius: 20px;
        padding: 2.2rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    html[data-bs-theme="light"] .forecast-hero-banner,
    :root:not([data-bs-theme="dark"]) .forecast-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #047857 50%, #059669 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    html[data-bs-theme="dark"] .forecast-hero-banner {
        background: linear-gradient(135deg, rgba(4, 120, 87, 0.95) 0%, rgba(15, 23, 42, 0.95) 100%);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #f8fafc;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
    }

    /* KPI Spec Cards */
    .forecast-kpi-card {
        border-radius: 16px;
        padding: 1.5rem;
        height: 100%;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .forecast-kpi-card:hover {
        transform: translateY(-4px);
    }

    html[data-bs-theme="light"] .forecast-kpi-card,
    :root:not([data-bs-theme="dark"]) .forecast-kpi-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }

    html[data-bs-theme="dark"] .forecast-kpi-card {
        background: rgba(30, 41, 59, 0.75);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
    }

    .forecast-stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    /* Glassmorphic Container */
    .forecast-glass-card {
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        transition: all 0.3s ease;
    }

    html[data-bs-theme="light"] .forecast-glass-card,
    :root:not([data-bs-theme="dark"]) .forecast-glass-card {
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
                <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-3 py-1 fw-bold">
                    <i class="ri-funds-box-line me-1"></i>Cash Flow Projections & Financial AI
                </span>
                <a href="{{ route('finance.forecast.comparison', ['project_id' => $project->id]) }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                    <i class="ri-scales-line me-1"></i>Karşılaştırma Raporu
                </a>
            </div>
            <h1 class="fw-extrabold text-white mb-1">Nakit Akış Tahmini & Finansal Projeksiyonlar</h1>
            <p class="text-white-50 mb-0 fs-6">Proje: {{ $project->name ?? 'Seçili Proje' }} — Geleceğe Yönelik 12 Aylık Likidite ve Nakit Dengesi</p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('finance.forecast.create') }}" class="btn btn-emerald text-white rounded-pill px-4 fw-bold shadow-sm" style="background:#059669;">
                <i class="ri-add-circle-line me-1"></i>Yeni Tahmin Ekle
            </a>
        </div>
    </div>
</div>

<!-- 4 Top KPI Spec Cards -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="forecast-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Ortalama Güven Skoru</span>
                    <h2 class="fw-extrabold mb-0 text-emerald" style="color:#059669;">%{{ round($avgConfidence, 1) }}</h2>
                </div>
                <div class="forecast-stat-icon bg-emerald bg-opacity-10 text-emerald" style="color:#059669;">
                    <i class="ri-shield-check-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="forecast-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Ortalama Sapma Oranı</span>
                    <h2 class="fw-extrabold mb-0 text-primary">%{{ round($avgVariance, 1) }}</h2>
                </div>
                <div class="forecast-stat-icon bg-primary bg-opacity-10 text-primary">
                    <i class="ri-line-chart-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="forecast-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Pozitif Likidite Dönemi</span>
                    <h2 class="fw-extrabold mb-0 text-success">{{ $positiveFlowCount }} Ay</h2>
                </div>
                <div class="forecast-stat-icon bg-success bg-opacity-10 text-success">
                    <i class="ri-arrow-up-circle-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="forecast-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Nakit Açığı Riski (Dönem)</span>
                    <h2 class="fw-extrabold mb-0 text-danger">{{ $negativeFlowCount }} Ay</h2>
                </div>
                <div class="forecast-stat-icon bg-danger bg-opacity-10 text-danger">
                    <i class="ri-error-warning-line"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Project Filter Bar -->
<div class="forecast-glass-card mb-4 p-3">
    <form method="GET" action="{{ route('finance.forecast.dashboard') }}">
        <div class="row g-3 align-items-center">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text"><i class="ri-building-line text-emerald" style="color:#059669;"></i></span>
                    <select name="project_id" class="form-select" onchange="this.form.submit()">
                        @foreach($projects as $p)
                            <option value="{{ $p->id }}" {{ $project->id == $p->id ? 'selected' : '' }}>
                                {{ $p->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text"><i class="ri-calendar-event-line"></i></span>
                    <select name="months" class="form-select" onchange="this.form.submit()">
                        <option value="6" {{ request('months') == 6 ? 'selected' : '' }}>Gelecek 6 Ay Projeksiyonu</option>
                        <option value="12" {{ request('months') == 12 || !request('months') ? 'selected' : '' }}>Gelecek 12 Ay Projeksiyonu</option>
                        <option value="24" {{ request('months') == 24 ? 'selected' : '' }}>Gelecek 24 Ay Projeksiyonu</option>
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <a href="{{ route('finance.forecast.dashboard', ['project_id' => $project->id]) }}" class="btn btn-outline-secondary w-100 rounded-pill">
                    <i class="ri-refresh-line me-1"></i>Yenile
                </a>
            </div>
        </div>
    </form>
</div>

<!-- Projections Table Card -->
<div class="row">
    <div class="col-lg-12">
        <div class="forecast-glass-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0 text-dark">
                    <i class="ri-table-line text-emerald me-2" style="color:#059669;"></i>Aylık Nakit Akış Projeksiyon Tablosu
                </h5>
                <span class="badge bg-emerald bg-opacity-10 text-emerald border border-emerald border-opacity-30 rounded-pill px-3 py-1 fw-bold" style="color:#059669;">
                    {{ $forecasts->count() }} Ay Listelendi
                </span>
            </div>

            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Dönem / Tarih</th>
                            <th>Devir Bakiyesi (Açılış)</th>
                            <th>Planlanan Tahsilat (Giriş)</th>
                            <th>Planlanan Ödeme (Çıkış)</th>
                            <th>Net Nakit Akışı</th>
                            <th>Kapanış Bakiyesi</th>
                            <th>Güven Skoru</th>
                            <th>Durum</th>
                            <th class="text-end pe-4">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($forecasts as $forecast)
                            @php
                                $netFlow = $forecast->inflow_planned - $forecast->outflow_planned;
                            @endphp
                            <tr>
                                <td class="ps-4">
                                    <span class="badge bg-dark bg-opacity-10 text-dark border font-monospace px-3 py-1 fw-bold fs-6">
                                        <i class="ri-calendar-line me-1"></i>{{ $forecast->forecast_date ? $forecast->forecast_date->format('M Y') : '-' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="text-muted small">₺{{ number_format($forecast->opening_balance, 2, ',', '.') }}</span>
                                </td>
                                <td>
                                    <strong class="text-success">+₺{{ number_format($forecast->inflow_planned, 2, ',', '.') }}</strong>
                                </td>
                                <td>
                                    <strong class="text-danger">-₺{{ number_format($forecast->outflow_planned, 2, ',', '.') }}</strong>
                                </td>
                                <td>
                                    <strong class="{{ $netFlow >= 0 ? 'text-success' : 'text-danger' }} fs-6">
                                        {{ $netFlow >= 0 ? '+' : '' }}₺{{ number_format($netFlow, 2, ',', '.') }}
                                    </strong>
                                </td>
                                <td>
                                    <strong class="text-dark fs-6">₺{{ number_format($forecast->closing_balance, 2, ',', '.') }}</strong>
                                </td>
                                <td>
                                    <span class="badge bg-emerald bg-opacity-10 text-emerald border border-emerald border-opacity-30 rounded-pill px-3 py-1 fw-bold" style="color:#059669;">
                                        %{{ $forecast->confidence_level }}
                                    </span>
                                </td>
                                <td>
                                    @if($forecast->status === 'confirmed')
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-30 rounded-pill px-3 py-1 fw-bold">Onaylı</span>
                                    @elseif($forecast->status === 'revised')
                                        <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-30 rounded-pill px-3 py-1 fw-bold">Revize Edildi</span>
                                    @else
                                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-30 rounded-pill px-3 py-1 fw-bold">Taslak</span>
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
                                        <form action="{{ route('finance.forecast.destroy', $forecast->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bu tahmin kaydını silmek istediğinize emin misiniz?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle" title="Sil">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5 text-muted">
                                    <i class="ri-funds-line fs-1 d-block mb-2 text-emerald" style="color:#059669;"></i>
                                    Henüz bu proje için nakit akış tahmini bulunamadı.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
