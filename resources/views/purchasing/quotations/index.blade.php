@extends('layouts.master')

@section('title', 'Satın Alma Teklif Toplama & Karşılaştırma Portalı')

@section('topbar-title', 'Satın Alma - Tedarikçi Teklifleri')

@push('styles')
<style>
    /* Hero Header Banner */
    .purchasing-hero-banner {
        border-radius: 20px;
        padding: 2.2rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    html[data-bs-theme="light"] .purchasing-hero-banner,
    :root:not([data-bs-theme="dark"]) .purchasing-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #2563eb 50%, #1d4ed8 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    html[data-bs-theme="dark"] .purchasing-hero-banner {
        background: linear-gradient(135deg, rgba(29, 78, 216, 0.95) 0%, rgba(15, 23, 42, 0.95) 100%);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #f8fafc;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
    }

    /* KPI Spec Cards */
    .purchasing-kpi-card {
        border-radius: 16px;
        padding: 1.5rem;
        height: 100%;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .purchasing-kpi-card:hover {
        transform: translateY(-4px);
    }

    html[data-bs-theme="light"] .purchasing-kpi-card,
    :root:not([data-bs-theme="dark"]) .purchasing-kpi-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }

    html[data-bs-theme="dark"] .purchasing-kpi-card {
        background: rgba(30, 41, 59, 0.75);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
    }

    .purchasing-stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    /* Glassmorphic Container */
    .purchasing-glass-card {
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        transition: all 0.3s ease;
    }

    html[data-bs-theme="light"] .purchasing-glass-card,
    :root:not([data-bs-theme="dark"]) .purchasing-glass-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    html[data-bs-theme="dark"] .purchasing-glass-card {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
</style>
@endpush

@section('content')

<!-- Hero Header Banner -->
<div class="purchasing-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-3 py-1 fw-bold">
                    <i class="ri-file-search-line me-1"></i>Supplier Procurement & Quotations
                </span>
            </div>
            <h1 class="fw-extrabold text-white mb-1">Satın Alma Teklif Toplama & Karşılaştırma Portalı</h1>
            <p class="text-white-50 mb-0 fs-6">Tedarikçi birim fiyat teklifleri, ihale teklif toplama süreçleri ve satınalma onay matrisi.</p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('purchasing.quotations.create') }}" class="btn btn-primary text-white rounded-pill px-4 fw-bold shadow-sm" style="background:#2563eb;">
                <i class="ri-add-circle-line me-1"></i>Yeni Teklif Kaydı
            </a>
        </div>
    </div>
</div>

<!-- 4 Top KPI Spec Cards -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="purchasing-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Toplam Alınan Teklif</span>
                    <h2 class="fw-extrabold mb-0 text-primary">{{ $totalQuotations }} Kayıt</h2>
                </div>
                <div class="purchasing-stat-icon bg-primary bg-opacity-10 text-primary">
                    <i class="ri-price-tag-3-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="purchasing-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Toplam Teklif Hacmi (₺)</span>
                    <h2 class="fw-extrabold mb-0 text-primary">₺{{ number_format($totalQuotationValue, 2, ',', '.') }}</h2>
                </div>
                <div class="purchasing-stat-icon bg-primary bg-opacity-10 text-primary">
                    <i class="ri-currency-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="purchasing-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Onaylı Satınalma Teklifleri</span>
                    <h2 class="fw-extrabold mb-0 text-success">{{ $approvedCount }}</h2>
                </div>
                <div class="purchasing-stat-icon bg-success bg-opacity-10 text-success">
                    <i class="ri-checkbox-circle-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="purchasing-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Değerlendirmede / Bekleyen</span>
                    <h2 class="fw-extrabold mb-0 text-warning">{{ $pendingCount }}</h2>
                </div>
                <div class="purchasing-stat-icon bg-warning bg-opacity-10 text-warning">
                    <i class="ri-time-line"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quotations Main Table Card -->
<div class="purchasing-glass-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0 text-dark">
            <i class="ri-file-list-3-line text-primary me-2"></i>Tedarikçi Fiyat Teklifleri Listesi
        </h5>
        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-30 rounded-pill px-3 py-1 fw-bold">
            {{ count($quotations) }} Teklif Kaydı
        </span>
    </div>

    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">Teklif No</th>
                    <th>Tedarikçi Firma</th>
                    <th>Teklif Tarihi</th>
                    <th>Geçerlilik Tarihi</th>
                    <th>Toplam Tutar (₺)</th>
                    <th>Durum</th>
                    <th class="text-end pe-4">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                @forelse($quotations as $q)
                    <tr>
                        <td class="ps-4">
                            <span class="badge bg-dark bg-opacity-10 text-dark border font-monospace px-3 py-1 fw-bold fs-6">
                                {{ $q->quote_number }}
                            </span>
                        </td>
                        <td>
                            <strong class="text-dark d-block">{{ $q->vendor->name ?? 'Tedarikçi Firma' }}</strong>
                            <small class="text-muted">{{ $q->vendor->specialty ?? 'Genel Malzeme' }}</small>
                        </td>
                        <td>
                            <span class="text-dark small">{{ $q->quote_date ? $q->quote_date->format('d.m.Y') : '-' }}</span>
                        </td>
                        <td>
                            <span class="text-dark small">{{ $q->valid_until ? $q->valid_until->format('d.m.Y') : '-' }}</span>
                        </td>
                        <td>
                            <strong class="text-dark fs-6">₺{{ number_format($q->total_amount, 2, ',', '.') }}</strong>
                        </td>
                        <td>
                            @if($q->status === 'approved')
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-30 rounded-pill px-3 py-1 fw-bold">Onaylandı</span>
                            @elseif($q->status === 'rejected')
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-30 rounded-pill px-3 py-1 fw-bold">Reddedildi</span>
                            @else
                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-30 rounded-pill px-3 py-1 fw-bold">Değerlendirmede</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <div class="btn-group gap-1">
                                <a href="{{ route('purchasing.quotations.show', $q->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                    <i class="ri-eye-line me-1"></i>İncele
                                </a>
                                <a href="{{ route('purchasing.quotations.edit', $q->id) }}" class="btn btn-sm btn-outline-warning rounded-circle" title="Düzenle">
                                    <i class="ri-pencil-line"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="ri-price-tag-3-line fs-1 d-block mb-2 text-primary"></i>
                            Henüz satın alma teklifi bulunamadı.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
