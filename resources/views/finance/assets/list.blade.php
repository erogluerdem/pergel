@extends('layouts.master')

@section('title', 'Demirbaş & Sabit Kıymet Envanteri')

@section('topbar-title', 'Finans - Sabit Kıymetler')

@push('styles')
<style>
    /* Hero Header Banner */
    .asset-hero-banner {
        border-radius: 20px;
        padding: 2.2rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    html[data-bs-theme="light"] .asset-hero-banner,
    :root:not([data-bs-theme="dark"]) .asset-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #0d9488 50%, #0f766e 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    html[data-bs-theme="dark"] .asset-hero-banner {
        background: linear-gradient(135deg, rgba(13, 148, 136, 0.95) 0%, rgba(15, 23, 42, 0.95) 100%);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #f8fafc;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
    }

    /* KPI Spec Cards */
    .asset-kpi-card {
        border-radius: 16px;
        padding: 1.5rem;
        height: 100%;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .asset-kpi-card:hover {
        transform: translateY(-4px);
    }

    html[data-bs-theme="light"] .asset-kpi-card,
    :root:not([data-bs-theme="dark"]) .asset-kpi-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }

    html[data-bs-theme="dark"] .asset-kpi-card {
        background: rgba(30, 41, 59, 0.75);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
    }

    .asset-stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    /* Glassmorphic Container */
    .asset-glass-card {
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        transition: all 0.3s ease;
    }

    html[data-bs-theme="light"] .asset-glass-card,
    :root:not([data-bs-theme="dark"]) .asset-glass-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    html[data-bs-theme="dark"] .asset-glass-card {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
</style>
@endpush

@section('content')

@php
    $totalCount = $assets->total() > 0 ? $assets->total() : count($assets);
    $activeCount = $activeAssetsCount ?? count($assets);
@endphp

<!-- Hero Header Banner -->
<div class="asset-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-3 py-1 fw-bold">
                    <i class="ri-bank-line me-1"></i>Fixed Assets & Amortization Management
                </span>
            </div>
            <h1 class="fw-extrabold text-white mb-1">Demirbaş & Sabit Kıymet Envanteri</h1>
            <p class="text-white-50 mb-0 fs-6">HVAC, jeneratör, asansör, bilgisayar ve demirbaş amortisman takipleri.</p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('finance.assets.create') }}" class="btn btn-teal text-white rounded-pill px-4 fw-bold shadow-sm" style="background:#0d9488;">
                <i class="ri-add-circle-line me-1"></i>Yeni Demirbaş Ekle
            </a>
        </div>
    </div>
</div>

<!-- 4 Top KPI Spec Cards -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="asset-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Toplam Net Defter Değeri</span>
                    <h2 class="fw-extrabold mb-0 text-teal" style="color:#0d9488;">₺{{ number_format($totalValue, 2, ',', '.') }}</h2>
                </div>
                <div class="asset-stat-icon bg-teal bg-opacity-10 text-teal" style="color:#0d9488;">
                    <i class="ri-safe-2-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="asset-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Satın Alma Brüt Maliyeti</span>
                    <h2 class="fw-extrabold mb-0 text-primary">₺{{ number_format($totalPurchaseCost, 2, ',', '.') }}</h2>
                </div>
                <div class="asset-stat-icon bg-primary bg-opacity-10 text-primary">
                    <i class="ri-shopping-bag-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="asset-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Birikmiş Amortisman</span>
                    <h2 class="fw-extrabold mb-0 text-danger">₺{{ number_format($totalDepreciation, 2, ',', '.') }}</h2>
                </div>
                <div class="asset-stat-icon bg-danger bg-opacity-10 text-danger">
                    <i class="ri-line-chart-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="asset-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Kayıtlı Demirbaş Sayısı</span>
                    <h2 class="fw-extrabold mb-0 text-dark">{{ $totalCount }} Demirbaş</h2>
                </div>
                <div class="asset-stat-icon bg-secondary bg-opacity-10 text-secondary">
                    <i class="ri-archive-line"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Fixed Assets Table Container -->
<div class="asset-glass-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0 text-dark">
            <i class="ri-list-settings-line text-teal me-2" style="color:#0d9488;"></i>Demirbaş Envanteri & Amortisman Cetveli
        </h5>
        <span class="badge bg-teal bg-opacity-10 border border-teal border-opacity-30 rounded-pill px-3 py-1 fw-bold" style="color:#0d9488;">
            {{ count($assets) }} Kayıt
        </span>
    </div>

    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">Demirbaş Kodu</th>
                    <th>Demirbaş Adı & Marka</th>
                    <th>Kategori</th>
                    <th>Satın Alış Değeri (₺)</th>
                    <th>Net Defter Değeri (₺)</th>
                    <th>Lokasyon</th>
                    <th>Durum</th>
                    <th class="text-end pe-4">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                @forelse($assets as $ast)
                    <tr>
                        <td class="ps-4">
                            <span class="badge bg-dark bg-opacity-10 text-dark border font-monospace px-3 py-1 fw-bold fs-6">
                                {{ $ast->asset_code }}
                            </span>
                        </td>
                        <td>
                            <strong class="text-dark d-block">{{ $ast->name }}</strong>
                            <small class="text-muted">{{ $ast->brand ?? '-' }} {{ $ast->model ? '('.$ast->model.')' : '' }}</small>
                        </td>
                        <td>
                            <span class="badge bg-info bg-opacity-10 text-info border px-2 py-1 small">{{ $ast->category }}</span>
                        </td>
                        <td>
                            <strong class="text-dark">₺{{ number_format($ast->purchase_price, 2, ',', '.') }}</strong>
                        </td>
                        <td>
                            <strong class="text-teal fs-6" style="color:#0d9488;">₺{{ number_format($ast->current_value, 2, ',', '.') }}</strong>
                        </td>
                        <td>
                            <span class="text-dark small">{{ $ast->location ?? 'Genel Ambar' }}</span>
                        </td>
                        <td>
                            @if($ast->status === 'active')
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-30 rounded-pill px-3 py-1 fw-bold">Kullanımda</span>
                            @elseif($ast->status === 'maintenance')
                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-30 rounded-pill px-3 py-1 fw-bold">Bakımda</span>
                            @else
                                <span class="badge bg-secondary bg-opacity-10 text-secondary border rounded-pill px-3 py-1 fw-bold">Hurda/Pasif</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <div class="btn-group gap-1">
                                <a href="{{ route('finance.assets.show', $ast->id) }}" class="btn btn-sm btn-outline-teal rounded-pill px-3" style="color:#0d9488; border-color:#0d9488;">
                                    <i class="ri-eye-line me-1"></i>İncele
                                </a>
                                <a href="{{ route('finance.assets.edit', $ast->id) }}" class="btn btn-sm btn-outline-secondary rounded-circle" title="Düzenle">
                                    <i class="ri-pencil-line"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="ri-bank-line fs-1 d-block mb-2 text-teal" style="color:#0d9488;"></i>
                            Henüz kayıtlı demirbaş bulunamadı.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
