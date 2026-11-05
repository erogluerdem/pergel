@extends('layouts.master')

@section('title', 'Stok Hareketleri & Ambar Fişleri')

@section('topbar-title', 'Finans - Stok Hareketleri')

@push('styles')
<style>
    /* Hero Header Banner */
    .movement-hero-banner {
        border-radius: 20px;
        padding: 2.2rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    html[data-bs-theme="light"] .movement-hero-banner,
    :root:not([data-bs-theme="dark"]) .movement-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #2563eb 50%, #4f46e5 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    html[data-bs-theme="dark"] .movement-hero-banner {
        background: linear-gradient(135deg, rgba(37, 99, 235, 0.95) 0%, rgba(15, 23, 42, 0.95) 100%);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #f8fafc;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
    }

    /* KPI Spec Cards */
    .movement-kpi-card {
        border-radius: 16px;
        padding: 1.5rem;
        height: 100%;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .movement-kpi-card:hover {
        transform: translateY(-4px);
    }

    html[data-bs-theme="light"] .movement-kpi-card,
    :root:not([data-bs-theme="dark"]) .movement-kpi-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }

    html[data-bs-theme="dark"] .movement-kpi-card {
        background: rgba(30, 41, 59, 0.75);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
    }

    .movement-stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    /* Glassmorphic Container */
    .movement-glass-card {
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        transition: all 0.3s ease;
    }

    html[data-bs-theme="light"] .movement-glass-card,
    :root:not([data-bs-theme="dark"]) .movement-glass-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    html[data-bs-theme="dark"] .movement-glass-card {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
</style>
@endpush

@section('content')

<!-- Hero Header Banner -->
<div class="movement-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-3 py-1 fw-bold">
                    <i class="ri-arrow-left-right-line me-1"></i>Warehouse Stock Transactions
                </span>
            </div>
            <h1 class="fw-extrabold text-white mb-1">Stok Hareketleri & Ambar Fişleri Portalı</h1>
            <p class="text-white-50 mb-0 fs-6">Ambar malzeme girişleri, zimmetli şantiye çıkışları ve ambarlar arası transfer fişleri.</p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('finance.stock.movements.create') }}" class="btn btn-primary text-white rounded-pill px-4 fw-bold shadow-sm" style="background:#2563eb;">
                <i class="ri-add-circle-line me-1"></i>Yeni Stok Fişi Düzenle
            </a>
        </div>
    </div>
</div>

<!-- 4 Top KPI Spec Cards -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="movement-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Toplam Stok Hareketi</span>
                    <h2 class="fw-extrabold mb-0 text-primary">{{ $totalMovements }} Fiş</h2>
                </div>
                <div class="movement-stat-icon bg-primary bg-opacity-10 text-primary">
                    <i class="ri-file-transfer-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="movement-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Toplam Hareket Hacmi (₺)</span>
                    <h2 class="fw-extrabold mb-0 text-primary">₺{{ number_format($totalMovementVolume, 2, ',', '.') }}</h2>
                </div>
                <div class="movement-stat-icon bg-primary bg-opacity-10 text-primary">
                    <i class="ri-funds-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="movement-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Ambar Giriş Fişleri</span>
                    <h2 class="fw-extrabold mb-0 text-success">{{ $inCount }}</h2>
                </div>
                <div class="movement-stat-icon bg-success bg-opacity-10 text-success">
                    <i class="ri-arrow-down-circle-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="movement-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Saha Çıkış & Transfer</span>
                    <h2 class="fw-extrabold mb-0 text-danger">{{ $outCount + $transferCount }}</h2>
                </div>
                <div class="movement-stat-icon bg-danger bg-opacity-10 text-danger">
                    <i class="ri-arrow-up-circle-line"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stock Movements Main Table Card -->
<div class="movement-glass-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0 text-dark">
            <i class="ri-list-check-2 text-primary me-2"></i>Stok Hareket Fişleri Kayıt Listesi
        </h5>
        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-30 rounded-pill px-3 py-1 fw-bold">
            {{ count($movements) }} Stok Hareketi
        </span>
    </div>

    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">Fiş / Ref No</th>
                    <th>Hareket Tipi</th>
                    <th>Malzeme Kartı</th>
                    <th>Miktar</th>
                    <th>Birim Fiyat</th>
                    <th>Toplam Tutar (₺)</th>
                    <th>Tarih</th>
                    <th class="text-end pe-4">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                @forelse($movements as $m)
                    <tr>
                        <td class="ps-4">
                            <span class="badge bg-dark bg-opacity-10 text-dark border font-monospace px-3 py-1 fw-bold fs-6">
                                {{ $m->reference_no }}
                            </span>
                        </td>
                        <td>
                            @if($m->type === 'in')
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-30 rounded-pill px-3 py-1 fw-bold">
                                    <i class="ri-arrow-down-line me-1"></i>Ambar Giriş
                                </span>
                            @elseif($m->type === 'out')
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-30 rounded-pill px-3 py-1 fw-bold">
                                    <i class="ri-arrow-up-line me-1"></i>Saha Çıkış
                                </span>
                            @else
                                <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-30 rounded-pill px-3 py-1 fw-bold">
                                    <i class="ri-arrow-left-right-line me-1"></i>Transfer
                                </span>
                            @endif
                        </td>
                        <td>
                            <strong class="text-dark d-block">{{ $m->stockCard->name ?? 'Malzeme Kartı' }}</strong>
                            <small class="text-muted">Kod: {{ $m->stockCard->code ?? '-' }}</small>
                        </td>
                        <td>
                            <strong class="text-dark fs-6">{{ number_format($m->quantity, 2, ',', '.') }} {{ $m->stockCard->unit ?? 'Adet' }}</strong>
                        </td>
                        <td>
                            <span class="text-dark">₺{{ number_format($m->unit_price, 2, ',', '.') }}</span>
                        </td>
                        <td>
                            <strong class="text-dark fs-6">₺{{ number_format($m->total_amount, 2, ',', '.') }}</strong>
                        </td>
                        <td>
                            <span class="text-dark small">{{ $m->created_at ? $m->created_at->format('d.m.Y H:i') : '-' }}</span>
                        </td>
                        <td class="text-end pe-4">
                            <div class="btn-group gap-1">
                                <a href="{{ route('finance.stock.movements.show', $m->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                    <i class="ri-eye-line me-1"></i>İncele
                                </a>
                                <a href="{{ route('finance.stock.movements.edit', $m->id) }}" class="btn btn-sm btn-outline-warning rounded-circle" title="Düzenle">
                                    <i class="ri-pencil-line"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="ri-inbox-archive-line fs-1 d-block mb-2 text-primary"></i>
                            Henüz stok hareketi bulunamadı.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
