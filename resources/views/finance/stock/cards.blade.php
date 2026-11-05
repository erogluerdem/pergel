@extends('layouts.master')

@section('title', 'Stok Kartları & Şantiye Envanter Portalı')

@section('topbar-title', 'Finans - Stok Kartları')

@push('styles')
<style>
    /* Hero Header Banner */
    .stock-hero-banner {
        border-radius: 20px;
        padding: 2.2rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    html[data-bs-theme="light"] .stock-hero-banner,
    :root:not([data-bs-theme="dark"]) .stock-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #0d9488 50%, #0f766e 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    html[data-bs-theme="dark"] .stock-hero-banner {
        background: linear-gradient(135deg, rgba(13, 148, 136, 0.95) 0%, rgba(15, 23, 42, 0.95) 100%);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #f8fafc;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
    }

    /* KPI Spec Cards */
    .stock-kpi-card {
        border-radius: 16px;
        padding: 1.5rem;
        height: 100%;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .stock-kpi-card:hover {
        transform: translateY(-4px);
    }

    html[data-bs-theme="light"] .stock-kpi-card,
    :root:not([data-bs-theme="dark"]) .stock-kpi-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }

    html[data-bs-theme="dark"] .stock-kpi-card {
        background: rgba(30, 41, 59, 0.75);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
    }

    .stock-stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    /* Glassmorphic Container */
    .stock-glass-card {
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        transition: all 0.3s ease;
    }

    html[data-bs-theme="light"] .stock-glass-card,
    :root:not([data-bs-theme="dark"]) .stock-glass-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    html[data-bs-theme="dark"] .stock-glass-card {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
</style>
@endpush

@section('content')

<!-- Hero Header Banner -->
<div class="stock-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-3 py-1 fw-bold">
                    <i class="ri-box-3-line me-1"></i>Warehouse & Material Inventory
                </span>
            </div>
            <h1 class="fw-extrabold text-white mb-1">Stok Kartları & Şantiye Envanter Portalı</h1>
            <p class="text-white-50 mb-0 fs-6">İnşaat malzemeleri, çelik donatı, boru tesisat ve şantiye ambar stok seviyeleri.</p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('finance.stock.cards.create') }}" class="btn btn-teal text-white rounded-pill px-4 fw-bold shadow-sm" style="background:#0d9488;">
                <i class="ri-add-circle-line me-1"></i>Yeni Stok Kartı Ekle
            </a>
        </div>
    </div>
</div>

<!-- 4 Top KPI Spec Cards -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="stock-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Toplam Stok Kartı</span>
                    <h2 class="fw-extrabold mb-0 text-teal" style="color:#0d9488;">{{ $totalCards }} Kart</h2>
                </div>
                <div class="stock-stat-icon bg-teal bg-opacity-10 text-teal" style="color:#0d9488;">
                    <i class="ri-scan-2-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stock-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Toplam Stok Değeri (₺)</span>
                    <h2 class="fw-extrabold mb-0 text-success">₺{{ number_format($totalInventoryValue, 2, ',', '.') }}</h2>
                </div>
                <div class="stock-stat-icon bg-success bg-opacity-10 text-success">
                    <i class="ri-money-dollar-circle-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stock-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Kritik Stok Uyarısı</span>
                    <h2 class="fw-extrabold mb-0 text-danger">{{ $criticalStockCount }} Malzeme</h2>
                </div>
                <div class="stock-stat-icon bg-danger bg-opacity-10 text-danger">
                    <i class="ri-alert-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stock-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Aktif Envanter Kalemi</span>
                    <h2 class="fw-extrabold mb-0 text-info">{{ $activeCardsCount }} Kart</h2>
                </div>
                <div class="stock-stat-icon bg-info bg-opacity-10 text-info">
                    <i class="ri-checkbox-circle-line"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stock Cards Main Table Container -->
<div class="stock-glass-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0 text-dark">
            <i class="ri-inbox-archive-line me-2" style="color:#0d9488;"></i>Stok Kartları Envanter Listesi
        </h5>
        <span class="badge bg-teal bg-opacity-10 border border-teal border-opacity-30 rounded-pill px-3 py-1 fw-bold" style="color:#0d9488;">
            {{ count($stockCards) }} Stok Kalemi
        </span>
    </div>

    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">Stok Kodu</th>
                    <th>Malzeme Tanımı & Kategori</th>
                    <th>Mevcut Stok</th>
                    <th>Min / Max Limit</th>
                    <th>Birim Fiyat</th>
                    <th>Toplam Tutar (₺)</th>
                    <th>Stok Seviyesi</th>
                    <th class="text-end pe-4">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                @forelse($stockCards as $sc)
                    @php
                        $isCritical = $sc->current_stock <= $sc->min_stock;
                        $pct = $sc->max_stock > 0 ? min(100, round(($sc->current_stock / $sc->max_stock) * 100)) : 0;
                    @endphp
                    <tr>
                        <td class="ps-4">
                            <span class="badge bg-dark bg-opacity-10 text-dark border font-monospace px-3 py-1 fw-bold fs-6">
                                {{ $sc->code }}
                            </span>
                        </td>
                        <td>
                            <strong class="text-dark d-block">{{ $sc->name }}</strong>
                            <small class="text-muted">{{ $sc->category }} — Depo: {{ $sc->location ?? 'Genel Ambar' }}</small>
                        </td>
                        <td>
                            <strong class="fs-6 {{ $isCritical ? 'text-danger fw-extrabold' : 'text-dark' }}">
                                {{ number_format($sc->current_stock, 2, ',', '.') }} {{ $sc->unit }}
                            </strong>
                        </td>
                        <td>
                            <small class="text-muted font-monospace">
                                Min: {{ number_format($sc->min_stock, 0) }} / Max: {{ number_format($sc->max_stock, 0) }}
                            </small>
                        </td>
                        <td>
                            <span class="text-dark">₺{{ number_format($sc->unit_price, 2, ',', '.') }}</span>
                        </td>
                        <td>
                            <strong class="text-dark fs-6">₺{{ number_format($sc->total_value, 2, ',', '.') }}</strong>
                        </td>
                        <td style="min-width: 140px;">
                            <div class="d-flex align-items-center gap-2">
                                <div class="progress flex-grow-1" style="height: 6px;">
                                    <div class="progress-bar {{ $isCritical ? 'bg-danger' : 'bg-teal' }}" style="width: {{ $pct }}%; background:#0d9488;"></div>
                                </div>
                                <span class="small fw-bold {{ $isCritical ? 'text-danger' : 'text-muted' }}">%{{ $pct }}</span>
                            </div>
                        </td>
                        <td class="text-end pe-4">
                            <div class="btn-group gap-1">
                                <a href="{{ route('finance.stock.cards.show', $sc->id) }}" class="btn btn-sm btn-outline-teal rounded-pill px-3" style="color:#0d9488; border-color:#0d9488;">
                                    <i class="ri-eye-line me-1"></i>İncele
                                </a>
                                <a href="{{ route('finance.stock.cards.edit', $sc->id) }}" class="btn btn-sm btn-outline-warning rounded-circle" title="Düzenle">
                                    <i class="ri-pencil-line"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="ri-box-3-line fs-1 d-block mb-2 text-teal" style="color:#0d9488;"></i>
                            Henüz kayıtlı stok kartı bulunamadı.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
