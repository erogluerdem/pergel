@extends('layouts.master')

@section('title', 'Genel Yönetim KPI Dashboard & Üst Yönetim Raporu')

@section('topbar-title', 'Genel Raporlar - KPI Dashboard')

@push('styles')
<style>
    /* Hero Header Banner */
    .gen-kpi-hero-banner {
        border-radius: 20px;
        padding: 2.2rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    html[data-bs-theme="light"] .gen-kpi-hero-banner,
    :root:not([data-bs-theme="dark"]) .gen-kpi-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #7c3aed 50%, #6d28d9 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    html[data-bs-theme="dark"] .gen-kpi-hero-banner {
        background: linear-gradient(135deg, rgba(124, 58, 237, 0.95) 0%, rgba(15, 23, 42, 0.95) 100%);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #f8fafc;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
    }

    /* KPI Spec Cards */
    .gen-kpi-card {
        border-radius: 16px;
        padding: 1.5rem;
        height: 100%;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .gen-kpi-card:hover {
        transform: translateY(-4px);
    }

    html[data-bs-theme="light"] .gen-kpi-card,
    :root:not([data-bs-theme="dark"]) .gen-kpi-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }

    html[data-bs-theme="dark"] .gen-kpi-card {
        background: rgba(30, 41, 59, 0.75);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
    }

    .gen-kpi-stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    /* Glassmorphic Container */
    .gen-kpi-glass-card {
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        transition: all 0.3s ease;
    }

    html[data-bs-theme="light"] .gen-kpi-glass-card,
    :root:not([data-bs-theme="dark"]) .gen-kpi-glass-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    html[data-bs-theme="dark"] .gen-kpi-glass-card {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
</style>
@endpush

@section('content')

<!-- Hero Header Banner -->
<div class="gen-kpi-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-3 py-1 fw-bold">
                    <i class="ri-dashboard-3-line me-1"></i>Executive KPI & Performance Cockpit
                </span>
            </div>
            <h1 class="fw-extrabold text-white mb-1">Genel Yönetim KPI Dashboard</h1>
            <p class="text-white-50 mb-0 fs-6">Üst yönetim satış, tahsilat oranı, aktif müşteri portföyü ve operasyonel performans göstergeleri.</p>
        </div>
    </div>
</div>

<!-- 4 Top KPI Spec Cards -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="gen-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Toplam Satış Tutarı</span>
                    <h2 class="fw-extrabold mb-0 text-violet" style="color:#7c3aed;">₺{{ number_format($kpis['sales_value'] ?? 0, 2, ',', '.') }}</h2>
                </div>
                <div class="gen-kpi-stat-icon bg-purple bg-opacity-10 text-violet" style="color:#7c3aed;">
                    <i class="ri-shopping-cart-2-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="gen-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Tahsil Edilen Toplam Tutar</span>
                    <h2 class="fw-extrabold mb-0 text-success">₺{{ number_format($kpis['payments'] ?? 0, 2, ',', '.') }}</h2>
                </div>
                <div class="gen-kpi-stat-icon bg-success bg-opacity-10 text-success">
                    <i class="ri-money-dollar-box-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="gen-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Tahsilat Performans Oranı</span>
                    <h2 class="fw-extrabold mb-0 text-primary">%{{ number_format($kpis['collection_rate'] ?? 0, 1) }}</h2>
                </div>
                <div class="gen-kpi-stat-icon bg-primary bg-opacity-10 text-primary">
                    <i class="ri-pie-chart-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="gen-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Aktif Şantiye & Projeler</span>
                    <h2 class="fw-extrabold mb-0 text-dark">{{ $kpis['active_projects'] ?? 0 }} Proje</h2>
                </div>
                <div class="gen-kpi-stat-icon bg-secondary bg-opacity-10 text-secondary">
                    <i class="ri-building-4-line"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter Bar -->
<div class="gen-kpi-glass-card p-3 mb-4">
    <form method="GET" action="{{ route('reports.general.kpi_dashboard') }}" class="row g-2 align-items-center">
        <div class="col-md-3">
            <label class="form-label small fw-bold text-dark mb-1">Dönem Seçimi</label>
            <select name="period" class="form-select">
                <option value="weekly" {{ $period === 'weekly' ? 'selected' : '' }}>Haftalık Analiz</option>
                <option value="monthly" {{ $period === 'monthly' ? 'selected' : '' }}>Aylık Analiz</option>
                <option value="yearly" {{ $period === 'yearly' ? 'selected' : '' }}>Yıllık Analiz</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label small fw-bold text-dark mb-1">Başlangıç Tarihi</label>
            <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
        </div>
        <div class="col-md-3">
            <label class="form-label small fw-bold text-dark mb-1">Bitiş Tarihi</label>
            <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
        </div>
        <div class="col-md-3 d-flex align-items-end pt-4">
            <button type="submit" class="btn btn-purple text-white w-100 rounded-pill fw-bold" style="background:#7c3aed;">
                <i class="ri-filter-3-line me-1"></i>KPI Göstergelerini Filtrele
            </button>
        </div>
    </form>
</div>

<!-- Sub Navigation Tabs for Reports -->
<div class="d-flex gap-2 mb-4 overflow-auto pb-2">
    <a href="{{ route('reports.general.kpi_dashboard') }}" class="btn btn-purple rounded-pill px-4 fw-bold text-white shadow-sm" style="background:#7c3aed;">
        <i class="ri-tachometer-line me-1"></i>KPI Dashboard
    </a>
    <a href="{{ route('reports.general.performance_graphs') }}" class="btn btn-outline-secondary rounded-pill px-4">
        <i class="ri-line-chart-line me-1"></i>Performans Grafikleri
    </a>
    <a href="{{ route('reports.general.sales_analysis') }}" class="btn btn-outline-secondary rounded-pill px-4">
        <i class="ri-bar-chart-2-line me-1"></i>Satış Trend Analizi
    </a>
    <a href="{{ route('reports.general.dynamic_query') }}" class="btn btn-outline-secondary rounded-pill px-4">
        <i class="ri-search-eye-line me-1"></i>Dinamik Sorgulama
    </a>
    <a href="{{ route('reports.general.available_portfolio') }}" class="btn btn-outline-secondary rounded-pill px-4">
        <i class="ri-home-gear-line me-1"></i>Müsait Portföy
    </a>
</div>

<!-- Executive Operational Performance Grid -->
<div class="row g-3">
    <div class="col-md-6">
        <div class="gen-kpi-glass-card h-100 mb-0">
            <h5 class="fw-bold mb-3 text-dark">
                <i class="ri-shopping-bag-3-line text-purple me-2" style="color:#7c3aed;"></i>Satış & Müşteri Metrikleri
            </h5>
            <div class="list-group list-group-flush">
                <div class="list-group-item d-flex justify-content-between align-items-center bg-transparent py-3">
                    <span class="text-muted fw-bold">Toplam Kesilen Fatura / Satış Sözleşmesi</span>
                    <strong class="text-dark fs-5">{{ $kpis['sales_count'] ?? 0 }} Adet</strong>
                </div>
                <div class="list-group-item d-flex justify-content-between align-items-center bg-transparent py-3">
                    <span class="text-muted fw-bold">Aktif Müşteri / Kurum Sayısı</span>
                    <strong class="text-dark fs-5">{{ $kpis['new_customers'] ?? 0 }} Müşteri</strong>
                </div>
                <div class="list-group-item d-flex justify-content-between align-items-center bg-transparent py-3">
                    <span class="text-muted fw-bold">Ortalama Fatura Büyüklüğü</span>
                    <strong class="text-purple fs-5" style="color:#7c3aed;">₺{{ number_format(($kpis['sales_count'] ?? 0) > 0 ? ($kpis['sales_value'] / $kpis['sales_count']) : 0, 2, ',', '.') }}</strong>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="gen-kpi-glass-card h-100 mb-0">
            <h5 class="fw-bold mb-3 text-dark">
                <i class="ri-shield-check-line text-success me-2"></i>Operasyonel Performans & Risk Analizi
            </h5>
            <div class="list-group list-group-flush">
                <div class="list-group-item d-flex justify-content-between align-items-center bg-transparent py-3">
                    <span class="text-muted fw-bold">Tahsilat Verimlilik Skor Kartı</span>
                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-30 rounded-pill px-3 py-1 fw-bold fs-6">Yüksek Verimlilik (%{{ number_format($kpis['collection_rate'] ?? 0, 1) }})</span>
                </div>
                <div class="list-group-item d-flex justify-content-between align-items-center bg-transparent py-3">
                    <span class="text-muted fw-bold">Aktif Şantiye Portföy Durumu</span>
                    <span class="badge bg-primary bg-opacity-10 text-primary border rounded-pill px-3 py-1 fw-bold fs-6">{{ $kpis['active_projects'] ?? 0 }} Şantiye Devam Ediyor</span>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
