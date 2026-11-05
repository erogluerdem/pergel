@extends('layouts.master')

@section('title', 'E-Fatura Yönetim Sistemi & GİB Entegrasyonu')

@section('topbar-title', 'Finans - E-Dönüşüm (E-Fatura)')

@push('styles')
<style>
    /* Hero Header Banner */
    .edoc-hero-banner {
        border-radius: 20px;
        padding: 2.2rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    html[data-bs-theme="light"] .edoc-hero-banner,
    :root:not([data-bs-theme="dark"]) .edoc-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #3b82f6 50%, #1d4ed8 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    html[data-bs-theme="dark"] .edoc-hero-banner {
        background: linear-gradient(135deg, rgba(29, 78, 216, 0.95) 0%, rgba(15, 23, 42, 0.95) 100%);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #f8fafc;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
    }

    /* KPI Spec Cards */
    .edoc-kpi-card {
        border-radius: 16px;
        padding: 1.5rem;
        height: 100%;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .edoc-kpi-card:hover {
        transform: translateY(-4px);
    }

    html[data-bs-theme="light"] .edoc-kpi-card,
    :root:not([data-bs-theme="dark"]) .edoc-kpi-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }

    html[data-bs-theme="dark"] .edoc-kpi-card {
        background: rgba(30, 41, 59, 0.75);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
    }

    .edoc-stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    /* Glassmorphic Container */
    .edoc-glass-card {
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        transition: all 0.3s ease;
    }

    html[data-bs-theme="light"] .edoc-glass-card,
    :root:not([data-bs-theme="dark"]) .edoc-glass-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    html[data-bs-theme="dark"] .edoc-glass-card {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }

    .gib-pulse-indicator {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #10b981;
        box-shadow: 0 0 10px #10b981;
        display: inline-block;
        animation: pulse-ring 2s infinite;
    }

    @keyframes pulse-ring {
        0% { transform: scale(0.95); opacity: 1; }
        50% { transform: scale(1.2); opacity: 0.7; }
        100% { transform: scale(0.95); opacity: 1; }
    }
</style>
@endpush

@section('content')

<!-- Hero Header Banner -->
<div class="edoc-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-3 py-1 fw-bold">
                    <i class="ri-file-text-line me-1"></i>E-Dönüşüm Portal & GİB Entegrasyonu
                </span>
                <span class="badge bg-success bg-opacity-20 text-white rounded-pill px-3 py-1 fw-bold d-inline-flex align-items-center gap-2">
                    <span class="gib-pulse-indicator"></span>GİB Bağlantısı Aktif
                </span>
            </div>
            <h1 class="fw-extrabold text-white mb-1">E-Fatura & E-Arşiv Yönetim Portalı</h1>
            <p class="text-white-50 mb-0 fs-6">Resmi Gelir İdaresi Başkanlığı (GİB) entegrasyonu ile fatura taslaklama, onay ve otomatik gönderim.</p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('finance.edoc.create') }}" class="btn btn-light text-primary rounded-pill px-4 fw-bold shadow-sm">
                <i class="ri-add-circle-line me-1"></i>Yeni E-Fatura Kes
            </a>
        </div>
    </div>
</div>

<!-- 6 KPI Spec Cards -->
<div class="row g-3 mb-4">
    <div class="col-xl-2 col-md-4 col-6">
        <div class="edoc-kpi-card">
            <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Toplam Fatura</span>
            <h3 class="fw-extrabold mb-0 text-dark">{{ number_format($invoiceStats['total_invoices']) }}</h3>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="edoc-kpi-card">
            <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Onay Bekleyen</span>
            <h3 class="fw-extrabold mb-0 text-warning">{{ $invoiceStats['pending_approval'] }}</h3>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="edoc-kpi-card">
            <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Bugün Gönderilen</span>
            <h3 class="fw-extrabold mb-0 text-info">{{ $invoiceStats['sent_today'] }}</h3>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="edoc-kpi-card">
            <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Reddedilen</span>
            <h3 class="fw-extrabold mb-0 text-danger">{{ $invoiceStats['rejected'] }}</h3>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="edoc-kpi-card">
            <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Aylık Hacim</span>
            <h3 class="fw-extrabold mb-0 text-primary">₺{{ number_format($invoiceStats['monthly_volume'] / 1000000, 1) }}M</h3>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="edoc-kpi-card">
            <span class="text-muted fw-bold text-uppercase d-block mb-1 small">GİB Onay %</span>
            <h3 class="fw-extrabold mb-0 text-success">%{{ $invoiceStats['success_rate'] }}</h3>
        </div>
    </div>
</div>

<!-- Search & Filter Bar -->
<div class="edoc-glass-card mb-4 p-3">
    <form method="GET" action="{{ route('finance.edoc.efatura') }}">
        <div class="row g-3 align-items-center">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text"><i class="ri-search-line"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Fatura numarası veya alıcı şirket..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">Tüm Durumlar</option>
                    <option value="sent">Gönderildi (GİB)</option>
                    <option value="pending">Onay Bekliyor</option>
                    <option value="approved">Alıcı Tarafından Onaylandı</option>
                    <option value="rejected">Reddedildi</option>
                </select>
            </div>
            <div class="col-md-3">
                <input type="date" name="date" class="form-control" value="{{ request('date') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold">
                    <i class="ri-filter-3-line me-1"></i>Filtrele
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Main Table and System Status Cards -->
<div class="row">
    <!-- Invoices Table -->
    <div class="col-lg-8">
        <div class="edoc-glass-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0 text-dark">
                    <i class="ri-receipt-line text-primary me-2"></i>Son Kesilen E-Faturalar
                </h5>
                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-30 rounded-pill px-3 py-1 fw-bold">
                    {{ count($recentInvoices) }} Fatura
                </span>
            </div>

            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Fatura No</th>
                            <th>Müşteri / Alıcı Unvan</th>
                            <th>Tutar (KDV Dahil)</th>
                            <th>Fatura Tarihi</th>
                            <th>GİB Durumu</th>
                            <th class="text-end pe-4">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentInvoices as $inv)
                            <tr>
                                <td class="ps-4">
                                    <a href="{{ route('finance.edoc.show', $inv['db_id'] ?? 1) }}" class="fw-extrabold text-primary font-monospace fs-6">
                                        {{ $inv['id'] }}
                                    </a>
                                </td>
                                <td>
                                    <strong class="text-dark d-block">{{ $inv['customer'] }}</strong>
                                </td>
                                <td>
                                    <strong class="text-dark fs-6">₺{{ number_format($inv['amount'], 2, ',', '.') }}</strong>
                                </td>
                                <td>
                                    <span class="text-dark small">{{ $inv['date'] }}</span>
                                </td>
                                <td>
                                    @if($inv['status'] === 'approved')
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-30 rounded-pill px-3 py-1 fw-bold">Onaylandı</span>
                                    @elseif($inv['status'] === 'sent')
                                        <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-30 rounded-pill px-3 py-1 fw-bold">GİB'e Gönderildi</span>
                                    @elseif($inv['status'] === 'rejected')
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-30 rounded-pill px-3 py-1 fw-bold">Reddedildi</span>
                                    @else
                                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-30 rounded-pill px-3 py-1 fw-bold">Onay Bekliyor</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group gap-1">
                                        <a href="{{ route('finance.edoc.show', $inv['db_id'] ?? 1) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                            <i class="ri-eye-line me-1"></i>İncele
                                        </a>
                                        <a href="{{ route('finance.edoc.edit', $inv['db_id'] ?? 1) }}" class="btn btn-sm btn-outline-warning rounded-circle" title="Düzenle">
                                            <i class="ri-pencil-line"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="ri-file-search-line fs-1 d-block mb-2 text-primary"></i>
                                    E-Fatura kaydı bulunamadı.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Right Sidebar: Integration Status -->
    <div class="col-lg-4">
        <div class="edoc-glass-card">
            <h6 class="fw-bold text-dark mb-3"><i class="ri-server-line text-primary me-2"></i>GİB Entegrasyon Servis Durumu</h6>
            <div class="list-group list-group-flush border-0">
                <div class="list-group-item d-flex justify-content-between align-items-center px-0 bg-transparent">
                    <span class="text-dark fw-bold">GİB Portal Servisi</span>
                    <span class="badge bg-success bg-opacity-10 text-success border px-3 py-1 rounded-pill">Aktif & Çalışıyor</span>
                </div>
                <div class="list-group-item d-flex justify-content-between align-items-center px-0 bg-transparent">
                    <span class="text-dark fw-bold">E-Arşiv İmzala Servisi</span>
                    <span class="badge bg-success bg-opacity-10 text-success border px-3 py-1 rounded-pill">Aktif</span>
                </div>
                <div class="list-group-item d-flex justify-content-between align-items-center px-0 bg-transparent">
                    <span class="text-dark fw-bold">Mali Mühür & Sertifika</span>
                    <span class="badge bg-warning bg-opacity-10 text-warning border px-3 py-1 rounded-pill">45 Gün Geçerli</span>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
