@extends('layouts.master')

@section('title', 'Proje Finansal Analiz & Bütçe Kar/Zarar Raporu')

@section('topbar-title', 'Proje Raporları - Finansal Analiz')

@push('styles')
<style>
    /* Hero Header Banner */
    .prj-fin-hero-banner {
        border-radius: 20px;
        padding: 2.2rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    html[data-bs-theme="light"] .prj-fin-hero-banner,
    :root:not([data-bs-theme="dark"]) .prj-fin-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e40af 50%, #1d4ed8 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    html[data-bs-theme="dark"] .prj-fin-hero-banner {
        background: linear-gradient(135deg, rgba(30, 64, 175, 0.95) 0%, rgba(15, 23, 42, 0.95) 100%);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #f8fafc;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
    }

    /* KPI Spec Cards */
    .prj-fin-kpi-card {
        border-radius: 16px;
        padding: 1.5rem;
        height: 100%;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .prj-fin-kpi-card:hover {
        transform: translateY(-4px);
    }

    html[data-bs-theme="light"] .prj-fin-kpi-card,
    :root:not([data-bs-theme="dark"]) .prj-fin-kpi-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }

    html[data-bs-theme="dark"] .prj-fin-kpi-card {
        background: rgba(30, 41, 59, 0.75);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
    }

    .prj-fin-stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    /* Glassmorphic Container */
    .prj-fin-glass-card {
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        transition: all 0.3s ease;
    }

    html[data-bs-theme="light"] .prj-fin-glass-card,
    :root:not([data-bs-theme="dark"]) .prj-fin-glass-card {
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

<!-- Hero Header Banner -->
<div class="prj-fin-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-3 py-1 fw-bold">
                    <i class="ri-pie-chart-2-line me-1"></i>Project Financial P&L Analytics
                </span>
            </div>
            <h1 class="fw-extrabold text-white mb-1">Proje Finansal Analiz & Kar/Zarar Raporu</h1>
            <p class="text-white-50 mb-0 fs-6">Proje bütçeleri, gerçekleşen satış gelirleri, sapma oranları ve kar marjları.</p>
        </div>
    </div>
</div>

<!-- 4 Top KPI Spec Cards -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="prj-fin-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Toplam Planlanan Bütçe</span>
                    <h2 class="fw-extrabold mb-0 text-primary">₺{{ number_format($totals['total_budget'] ?? 0, 2, ',', '.') }}</h2>
                </div>
                <div class="prj-fin-stat-icon bg-primary bg-opacity-10 text-primary">
                    <i class="ri-bank-card-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="prj-fin-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Gerçekleşen Satış Geliri</span>
                    <h2 class="fw-extrabold mb-0 text-success">₺{{ number_format($totals['total_revenue'] ?? 0, 2, ',', '.') }}</h2>
                </div>
                <div class="prj-fin-stat-icon bg-success bg-opacity-10 text-success">
                    <i class="ri-money-dollar-circle-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="prj-fin-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Toplu Kar / Zarar Farkı</span>
                    <h2 class="fw-extrabold mb-0 text-indigo" style="color:#1d4ed8;">₺{{ number_format($totals['total_profit'] ?? 0, 2, ',', '.') }}</h2>
                </div>
                <div class="prj-fin-stat-icon bg-indigo bg-opacity-10 text-indigo" style="color:#1d4ed8;">
                    <i class="ri-funds-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="prj-fin-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Aktif Şantiye Sayısı</span>
                    <h2 class="fw-extrabold mb-0 text-dark">{{ count($projects) }} Şantiye</h2>
                </div>
                <div class="prj-fin-stat-icon bg-secondary bg-opacity-10 text-secondary">
                    <i class="ri-building-4-line"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter Bar -->
<div class="prj-fin-glass-card p-3 mb-4">
    <form method="GET" action="{{ route('reports.projects.financial_analysis') }}" class="row g-2 align-items-center">
        <div class="col-md-3">
            <label class="form-label small fw-bold text-dark mb-1">Başlangıç Tarihi</label>
            <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
        </div>
        <div class="col-md-3">
            <label class="form-label small fw-bold text-dark mb-1">Bitiş Tarihi</label>
            <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
        </div>
        <div class="col-md-3">
            <label class="form-label small fw-bold text-dark mb-1">Proje Filtresi</label>
            <select name="project_id" class="form-select">
                <option value="">-- Tüm Şantiyeler --</option>
                @foreach($projects as $proj)
                    <option value="{{ $proj->id }}" {{ $projectId == $proj->id ? 'selected' : '' }}>{{ $proj->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 d-flex align-items-end pt-4">
            <button type="submit" class="btn btn-primary text-white w-100 rounded-pill fw-bold" style="background:#1d4ed8;">
                <i class="ri-filter-3-line me-1"></i>Analizi Güncelle
            </button>
        </div>
    </form>
</div>

<!-- Sub Navigation Tabs for Reports -->
<div class="d-flex gap-2 mb-4 overflow-auto pb-2">
    <a href="{{ route('reports.projects.financial_analysis') }}" class="btn btn-primary rounded-pill px-4 fw-bold text-white shadow-sm" style="background:#1d4ed8;">
        <i class="ri-bar-chart-fill me-1"></i>Finansal Analiz
    </a>
    <a href="{{ route('reports.projects.stock_sales_balance') }}" class="btn btn-outline-secondary rounded-pill px-4">
        <i class="ri-scales-line me-1"></i>Stok & Satış Dengesi
    </a>
    <a href="{{ route('reports.projects.revenue_analysis') }}" class="btn btn-outline-secondary rounded-pill px-4">
        <i class="ri-line-chart-line me-1"></i>Ciro Gerçekleşme
    </a>
    <a href="{{ route('reports.projects.discount_profit') }}" class="btn btn-outline-secondary rounded-pill px-4">
        <i class="ri-percent-line me-1"></i>İskonto & Kar Marjı
    </a>
    <a href="{{ route('reports.projects.cost_comparison') }}" class="btn btn-outline-secondary rounded-pill px-4">
        <i class="ri-git-commit-line me-1"></i>Maliyet Karşılaştırması
    </a>
</div>

<!-- Project Financial P&L Table -->
<div class="prj-fin-glass-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0 text-dark">
            <i class="ri-table-line text-primary me-2"></i>Proje Bazlı Finansal Kar/Zarar Dökümü
        </h5>
    </div>

    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">Proje / Şantiye Adı</th>
                    <th>Planlanan Bütçe (₺)</th>
                    <th>Gerçekleşen Satış (₺)</th>
                    <th>Bütçe Sapması (₺)</th>
                    <th>Sapma Oranı (%)</th>
                    <th>Performans</th>
                </tr>
            </thead>
            <tbody>
                @if($projectId && is_array($analysis) && isset($analysis['project_name']))
                    @php
                        $varPct = min(100, max(-100, $analysis['variance_percentage']));
                    @endphp
                    <tr>
                        <td class="ps-4"><strong class="text-dark fs-6">{{ $analysis['project_name'] }}</strong></td>
                        <td>₺{{ number_format($analysis['budget'], 2, ',', '.') }}</td>
                        <td><strong class="text-success">₺{{ number_format($analysis['actual_sales'], 2, ',', '.') }}</strong></td>
                        <td>
                            @if($analysis['variance'] >= 0)
                                <strong class="text-success">+₺{{ number_format($analysis['variance'], 2, ',', '.') }}</strong>
                            @else
                                <strong class="text-danger">-₺{{ number_format(abs($analysis['variance']), 2, ',', '.') }}</strong>
                            @endif
                        </td>
                        <td>%{{ number_format($analysis['variance_percentage'], 1) }}</td>
                        <td>
                            @if($analysis['variance'] >= 0)
                                <span class="badge bg-success bg-opacity-10 text-success border rounded-pill px-3 py-1 fw-bold">Karda</span>
                            @else
                                <span class="badge bg-danger bg-opacity-10 text-danger border rounded-pill px-3 py-1 fw-bold">Zararda</span>
                            @endif
                        </td>
                    </tr>
                @elseif(is_array($analysis))
                    @forelse($analysis as $row)
                        <tr>
                            <td class="ps-4"><strong class="text-dark fs-6">{{ $row['project_name'] }}</strong></td>
                            <td>₺{{ number_format($row['budget'], 2, ',', '.') }}</td>
                            <td><strong class="text-success">₺{{ number_format($row['actual_sales'], 2, ',', '.') }}</strong></td>
                            <td>
                                @if($row['variance'] >= 0)
                                    <strong class="text-success">+₺{{ number_format($row['variance'], 2, ',', '.') }}</strong>
                                @else
                                    <strong class="text-danger">-₺{{ number_format(abs($row['variance']), 2, ',', '.') }}</strong>
                                @endif
                            </td>
                            <td>%{{ number_format($row['variance_percentage'], 1) }}</td>
                            <td>
                                @if($row['variance'] >= 0)
                                    <span class="badge bg-success bg-opacity-10 text-success border rounded-pill px-3 py-1 fw-bold">Karda</span>
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger border rounded-pill px-3 py-1 fw-bold">Zararda</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">Analiz verisi bulunamadı.</td>
                        </tr>
                    @endforelse
                @endif
            </tbody>
        </table>
    </div>
</div>

@endsection
