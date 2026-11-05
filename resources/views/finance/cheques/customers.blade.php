@extends('layouts.master')
@section('title', 'Müşteri Çek/Senetleri')
@section('topbar-title', 'Finans - Çek & Senet Yönetimi')

@push('meta')
<meta name="description" content="Müşteri çek ve senet takibi, vade analizi ve ödeme yönetimi sistemi">
<meta name="keywords" content="çek, senet, müşteri, vade, ödeme, finans, takip">
<meta property="og:title" content="Müşteri Çek/Senetleri - Pergel">
<meta property="og:description" content="Profesyonel çek ve senet yönetim sistemi">
@endpush

@push('styles')
<!-- Select2 styles -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css" rel="stylesheet" />
<link rel="preload" href="https://cdn.jsdelivr.net/npm/chart.js" as="script">
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        --warning-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        --danger-gradient: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
        --card-shadow: 0 2px 10px rgba(0,0,0,0.05);
        --card-hover-shadow: 0 8px 25px rgba(0,0,0,0.12);
        --border-radius: 12px;
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .cheque-card {
        border: none;
        border-radius: var(--border-radius);
        box-shadow: var(--card-shadow);
        transition: var(--transition);
        overflow: hidden;
        background: white;
        position: relative;
        border: 1px solid rgba(0,0,0,0.06);
    }

    .cheque-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--card-hover-shadow);
        border-color: rgba(102, 126, 234, 0.2);
    }

    .cheque-header {
        background: var(--primary-gradient);
        color: white;
        padding: 1.25rem;
        position: relative;
        overflow: hidden;
    }

    .cheque-header::before {
        content: '';
        position: absolute;
        top: -30%;
        right: -30%;
        width: 80%;
        height: 80%;
        background: rgba(255,255,255,0.08);
        border-radius: 50%;
        transform: rotate(45deg);
    }

    .stats-overview {
        background: var(--primary-gradient);
        border-radius: var(--border-radius);
        color: white;
        padding: 1.75rem;
        margin-bottom: 1.5rem;
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(255,255,255,0.1);
    }

    .stats-overview::before {
        content: '';
        position: absolute;
        top: -60px;
        right: -60px;
        width: 120px;
        height: 120px;
        background: rgba(255,255,255,0.08);
        border-radius: 50%;
    }

    .status-badge {
        padding: 0.4rem 0.85rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
        text-transform: capitalize;
        letter-spacing: 0.3px;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }

    .status-pending {
        background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
        color: white;
        box-shadow: 0 2px 8px rgba(251, 191, 36, 0.3);
    }

    .status-paid {
        background: linear-gradient(135deg, #34d399 0%, #10b981 100%);
        color: white;
        box-shadow: 0 2px 8px rgba(52, 211, 153, 0.3);
    }

    .status-bounced {
        background: linear-gradient(135deg, #f87171 0%, #ef4444 100%);
        color: white;
        box-shadow: 0 2px 8px rgba(248, 113, 113, 0.3);
    }

    .priority-badge {
        position: absolute;
        top: 0.75rem;
        right: 0.75rem;
        padding: 0.2rem 0.6rem;
        border-radius: 12px;
        font-size: 0.7rem;
        font-weight: 500;
        backdrop-filter: blur(10px);
    }

    .priority-urgent {
        background: rgba(254, 226, 226, 0.9);
        color: #dc2626;
        border: 1px solid rgba(252, 202, 202, 0.5);
        box-shadow: 0 2px 8px rgba(220, 38, 38, 0.15);
    }

    .priority-high {
        background: rgba(254, 243, 199, 0.9);
        color: #d97706;
        border: 1px solid rgba(254, 215, 170, 0.5);
        box-shadow: 0 2px 8px rgba(217, 119, 6, 0.15);
    }

    .priority-medium {
        background: rgba(219, 234, 254, 0.9);
        color: #2563eb;
        border: 1px solid rgba(191, 219, 254, 0.5);
        box-shadow: 0 2px 8px rgba(37, 99, 235, 0.15);
    }

    .cheque-item {
        background: white;
        border-radius: var(--border-radius);
        padding: 1.25rem;
        margin-bottom: 0.75rem;
        box-shadow: var(--card-shadow);
        transition: var(--transition);
        position: relative;
        border: 1px solid rgba(0,0,0,0.06);
        border-left: 3px solid #667eea;
    }

    .cheque-item:hover {
        transform: translateX(3px);
        box-shadow: var(--card-hover-shadow);
        border-color: rgba(102, 126, 234, 0.2);
    }

    .cheque-item.pending {
        border-left-color: #fbbf24;
        background: linear-gradient(90deg, rgba(251, 191, 36, 0.02) 0%, white 5%);
    }

    .cheque-item.paid {
        border-left-color: #34d399;
        background: linear-gradient(90deg, rgba(52, 211, 153, 0.02) 0%, white 5%);
    }

    .cheque-item.bounced {
        border-left-color: #f87171;
        background: linear-gradient(90deg, rgba(248, 113, 113, 0.02) 0%, white 5%);
    }

    .btn-cheque {
        background: var(--primary-gradient);
        border: none;
        border-radius: 20px;
        padding: 0.65rem 1.5rem;
        color: white;
        font-weight: 500;
        font-size: 0.9rem;
        transition: var(--transition);
        box-shadow: 0 2px 8px rgba(102, 126, 234, 0.25);
    }

    .btn-cheque:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.35);
        color: white;
    }

    .metric-card {
        text-align: center;
        padding: 1.25rem;
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--card-shadow);
        transition: var(--transition);
        border: 1px solid rgba(0,0,0,0.06);
    }

    .metric-card:hover {
        transform: translateY(-3px);
        box-shadow: var(--card-hover-shadow);
        border-color: rgba(102, 126, 234, 0.15);
    }

    .metric-value {
        font-size: 1.75rem;
        font-weight: 600;
        color: #667eea;
        margin-bottom: 0.4rem;
        line-height: 1.2;
    }

    .chart-container {
        background: white;
        border-radius: var(--border-radius);
        padding: 1.25rem;
        box-shadow: var(--card-shadow);
        height: 400px;
        border: 1px solid rgba(0,0,0,0.06);
    }

    .filter-tabs {
        background: white;
        border-radius: var(--border-radius);
        padding: 0.75rem;
        box-shadow: var(--card-shadow);
        margin-bottom: 1.25rem;
        border: 1px solid rgba(0,0,0,0.06);
    }

    .nav-pills .nav-link {
        border-radius: 16px;
        padding: 0.6rem 1.2rem;
        margin-right: 0.4rem;
        margin-bottom: 0.3rem;
        transition: var(--transition);
        font-size: 0.9rem;
        font-weight: 500;
        color: #6b7280;
        border: 1px solid transparent;
    }

    .nav-pills .nav-link.active {
        background: var(--primary-gradient);
        box-shadow: 0 2px 8px rgba(102, 126, 234, 0.25);
        color: white;
    }

    .nav-pills .nav-link:hover:not(.active) {
        background: rgba(102, 126, 234, 0.1);
        border-color: rgba(102, 126, 234, 0.2);
        color: #667eea;
    }

    .action-btn {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin: 0 0.15rem;
        transition: var(--transition);
        font-size: 0.85rem;
    }

    .action-btn:hover {
        transform: scale(1.05) translateY(-1px);
    }

    .btn-view {
        background: linear-gradient(135deg, #22d3ee 0%, #06b6d4 100%);
        color: white;
        box-shadow: 0 2px 8px rgba(34, 211, 238, 0.25);
    }

    .btn-edit {
        background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
        color: white;
        box-shadow: 0 2px 8px rgba(251, 191, 36, 0.25);
    }

    .btn-delete {
        background: linear-gradient(135deg, #f87171 0%, #ef4444 100%);
        color: white;
        box-shadow: 0 2px 8px rgba(248, 113, 113, 0.25);
    }

    .search-box {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 20px;
        padding: 0.65rem 1.25rem;
        transition: var(--transition);
        font-size: 0.9rem;
    }

    .search-box:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        outline: none;
    }

    .live-indicator {
        display: inline-block;
        width: 8px;
        height: 8px;
        background: #10b981;
        border-radius: 50%;
        animation: pulse 2s infinite;
        margin-right: 0.5rem;
    }

    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
        70% { box-shadow: 0 0 0 10px rgba(16, 185, 129, 0); }
        100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
    }

    .due-warning {
        background: linear-gradient(135deg, rgba(254, 243, 199, 0.8) 0%, rgba(253, 230, 138, 0.6) 100%);
        border: 1px solid rgba(251, 191, 36, 0.3);
        border-radius: var(--border-radius);
        padding: 0.75rem 1rem;
        margin-bottom: 1rem;
        backdrop-filter: blur(10px);
    }

    .bank-logo {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-right: 0.75rem;
        border: 1px solid rgba(0,0,0,0.06);
        color: #667eea;
    }

    /* Responsive tasarım */
    @media (max-width: 768px) {
        .cheque-card {
            margin-bottom: 0.75rem;
        }

        .stats-overview {
            padding: 1.25rem;
        }

        .metric-value {
            font-size: 1.4rem;
        }

        .chart-container {
            height: 280px;
            padding: 1rem;
        }

        .nav-pills .nav-link {
            padding: 0.5rem 0.9rem;
            margin-bottom: 0.4rem;
            font-size: 0.85rem;
        }

        .action-btn {
            width: 32px;
            height: 32px;
            font-size: 0.8rem;
        }

        .cheque-item {
            padding: 1rem;
        }
    }

    @media (max-width: 576px) {
        .btn-cheque {
            padding: 0.55rem 1.1rem;
            font-size: 0.85rem;
        }

        .stats-overview .row [class*="col-"] {
            margin-bottom: 0.75rem;
        }

        .modal-dialog {
            margin: 0.5rem;
        }

        .filter-tabs {
            padding: 0.5rem;
        }

        .cheque-item .row .col-md-1 {
            margin-top: 0.5rem;
        }
    }

    /* Dark mode desteği */
    @media (prefers-color-scheme: dark) {
        :root {
            --card-shadow: 0 2px 10px rgba(255,255,255,0.05);
            --card-hover-shadow: 0 8px 25px rgba(255,255,255,0.1);
        }
    }

    /* Smooth scrolling */
    html {
        scroll-behavior: smooth;
    }

    /* Özel scrollbar */
    ::-webkit-scrollbar {
        width: 6px;
    }

    ::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }

    ::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 3px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }
</style>
@endpush

@section('content')
<!-- Özet İstatistikler -->
<div class="stats-overview">
    <div class="row">
        <div class="col-lg-3 col-md-6 mb-lg-0 mb-3">
            <div class="metric-card" style="background: rgba(255,255,255,0.1); backdrop-filter: blur(10px);">
                <div class="metric-value" style="color: white;">{{ $chequeStats['total_cheques'] }}</div>
                <div style="color: rgba(255,255,255,0.8);">Toplam Çek</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-lg-0 mb-3">
            <div class="metric-card" style="background: rgba(255,255,255,0.1); backdrop-filter: blur(10px);">
                <div class="metric-value" style="color: white;">₺{{ number_format($chequeStats['total_amount'], 0, ',', '.') }}</div>
                <div style="color: rgba(255,255,255,0.8);">Toplam Tutar</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-lg-0 mb-3">
            <div class="metric-card" style="background: rgba(255,255,255,0.1); backdrop-filter: blur(10px);">
                <div class="metric-value" style="color: white;">{{ $chequeStats['pending_cheques'] }}</div>
                <div style="color: rgba(255,255,255,0.8);">Bekleyen Çek</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="metric-card" style="background: rgba(255,255,255,0.1); backdrop-filter: blur(10px);">
                <div class="metric-value" style="color: white;">
                    <span class="live-indicator"></span>CANLI
                </div>
                <div style="color: rgba(255,255,255,0.8);">Takip Durumu</div>
            </div>
        </div>
    </div>
</div>

<!-- Hızlı Eylemler ve Filtreler -->
<div class="row mb-4">
    <div class="col-lg-8">
        <div class="filter-tabs">
            <ul class="nav nav-pills" id="chequeFilter" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="all-tab" data-bs-toggle="pill" data-bs-target="#all" type="button">
                        <i class="fas fa-list me-2"></i>Tümü ({{ count($customerCheques) }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pending-tab" data-bs-toggle="pill" data-bs-target="#pending" type="button">
                        <i class="fas fa-clock me-2"></i>Bekleyen ({{ $chequeStats['pending_cheques'] }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="paid-tab" data-bs-toggle="pill" data-bs-target="#paid" type="button">
                        <i class="fas fa-check me-2"></i>Ödenen ({{ $chequeStats['paid_cheques'] }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="bounced-tab" data-bs-toggle="pill" data-bs-target="#bounced" type="button">
                        <i class="fas fa-exclamation-triangle me-2"></i>Karşılıksız ({{ $chequeStats['bounced_cheques'] }})
                    </button>
                </li>
            </ul>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="d-flex justify-content-end">
            <input type="text" class="form-control search-box me-2" placeholder="Çek ara..." id="chequeSearch">
            <button class="btn btn-cheque" data-bs-toggle="modal" data-bs-target="#addChequeModal">
                <i class="fas fa-plus me-1"></i>Yeni Çek
            </button>
        </div>
    </div>
</div>

<!-- Yaklaşan Vadeler Uyarısı -->
@if(count($upcomingDues) > 0)
<div class="due-warning">
    <div class="d-flex align-items-center">
        <i class="fas fa-exclamation-triangle text-warning me-2"></i>
        <strong>Dikkat!</strong> Önümüzdeki 30 gün içinde {{ count($upcomingDues) }} çekin vadesi dolacak.
        <button class="btn btn-sm btn-outline-warning ms-auto" data-bs-toggle="modal" data-bs-target="#upcomingDuesModal">
            Detayları Görüntüle
        </button>
    </div>
</div>
@endif

<!-- İstatistik Kartları -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="metric-card" style="border-left: 3px solid #fbbf24;">
            <div class="metric-value" style="color: #fbbf24;">{{ $chequeStats['pending_cheques'] }}</div>
            <div class="text-muted mb-1">Bekleyen Çekler</div>
            <small style="color: #fbbf24; font-weight: 500;">₺{{ number_format($chequeStats['pending_amount'], 0, ',', '.') }}</small>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="metric-card" style="border-left: 3px solid #34d399;">
            <div class="metric-value" style="color: #34d399;">{{ $chequeStats['paid_cheques'] }}</div>
            <div class="text-muted mb-1">Ödenen Çekler</div>
            <small style="color: #34d399; font-weight: 500;">₺{{ number_format($chequeStats['paid_amount'], 0, ',', '.') }}</small>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="metric-card" style="border-left: 3px solid #f87171;">
            <div class="metric-value" style="color: #f87171;">{{ $chequeStats['bounced_cheques'] }}</div>
            <div class="text-muted mb-1">Karşılıksız Çekler</div>
            <small style="color: #f87171; font-weight: 500;">₺{{ number_format($chequeStats['bounced_amount'], 0, ',', '.') }}</small>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="metric-card" style="border-left: 3px solid #667eea;">
            <div class="metric-value" style="color: #667eea;">{{ number_format(($chequeStats['paid_cheques'] / $chequeStats['total_cheques']) * 100, 1) }}%</div>
            <div class="text-muted mb-1">Başarı Oranı</div>
            <small style="color: #667eea; font-weight: 500;">Son 12 ay</small>
        </div>
    </div>
</div>

<!-- Çek Listesi ve Grafik -->
<div class="row">
    <div class="col-lg-8 mb-4">
        <div class="card" style="border: none; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); border: 1px solid rgba(0,0,0,0.06);">
            <div class="card-header d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%); border-bottom: 1px solid rgba(102, 126, 234, 0.1); border-radius: 12px 12px 0 0; padding: 1rem 1.25rem;">
                <h5 class="mb-0" style="color: #667eea; font-weight: 600; font-size: 1rem;">
                    <i class="fas fa-money-check me-2"></i>
                    Müşteri Çekleri
                </h5>
                <div class="dropdown">
                    <button class="btn btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" style="background: var(--primary-gradient); color: white; border: none; border-radius: 16px; padding: 0.4rem 0.8rem; font-size: 0.85rem; box-shadow: 0 2px 6px rgba(102, 126, 234, 0.25);">
                        <i class="fas fa-download me-1"></i>Rapor Al
                    </button>
                    <ul class="dropdown-menu" style="border-radius: 8px; border: 1px solid rgba(0,0,0,0.1); box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                        <li><a class="dropdown-item" href="#" style="padding: 0.5rem 1rem; font-size: 0.9rem;"><i class="fas fa-file-pdf me-2 text-danger"></i>PDF Rapor</a></li>
                        <li><a class="dropdown-item" href="#" style="padding: 0.5rem 1rem; font-size: 0.9rem;"><i class="fas fa-file-excel me-2 text-success"></i>Excel Rapor</a></li>
                        <li><a class="dropdown-item" href="#" style="padding: 0.5rem 1rem; font-size: 0.9rem;"><i class="fas fa-print me-2 text-info"></i>Yazdır</a></li>
                    </ul>
                </div>
            </div>
            <div class="card-body">
                <div class="tab-content" id="chequeFilterContent">
                    <div class="tab-pane fade show active" id="all" role="tabpanel">
                        @foreach($customerCheques as $cheque)
                        <div class="cheque-item {{ $cheque['status'] }}" data-status="{{ $cheque['status'] }}" data-customer="{{ strtolower($cheque['customer_name']) }}">
                            <div class="priority-badge priority-{{ $cheque['priority'] }}">
                                {{ ucfirst($cheque['priority']) }}
                            </div>

                            <div class="row align-items-center">
                                <div class="col-md-3">
                                    <div class="d-flex align-items-center">
                                        <div class="bank-logo">
                                            <i class="fas fa-university text-primary"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $cheque['customer_name'] }}</h6>
                                            <small class="text-muted">{{ $cheque['customer_code'] }}</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="text-center">
                                        <div class="fw-bold">{{ $cheque['cheque_no'] }}</div>
                                        <small class="text-muted">Çek No</small>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="text-center">
                                        <div class="fw-bold text-primary">₺{{ number_format($cheque['amount'], 0, ',', '.') }}</div>
                                        <small class="text-muted">{{ $cheque['currency'] }}</small>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="text-center">
                                        <div class="fw-bold">{{ \Carbon\Carbon::parse($cheque['due_date'])->format('d.m.Y') }}</div>
                                        <small class="text-muted">Vade Tarihi</small>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="text-center">
                                        <span class="status-badge status-{{ $cheque['status'] }}">
                                            @if($cheque['status'] === 'pending')
                                                <i class="fas fa-clock me-1"></i>Bekliyor
                                            @elseif($cheque['status'] === 'paid')
                                                <i class="fas fa-check me-1"></i>Ödendi
                                            @else
                                                <i class="fas fa-times me-1"></i>Karşılıksız
                                            @endif
                                        </span>
                                    </div>
                                </div>

                                <div class="col-md-1">
                                    <div class="text-end">
                                        <button class="action-btn btn-view" title="Detayları Görüntüle" onclick="viewCheque({{ $cheque['id'] }})">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="action-btn btn-edit" title="Düzenle" onclick="editCheque({{ $cheque['id'] }})">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="action-btn btn-delete" title="Sil" onclick="deleteCheque({{ $cheque['id'] }})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            @if($cheque['notes'])
                            <div class="row mt-2">
                                <div class="col-12">
                                    <small class="text-muted">
                                        <i class="fas fa-sticky-note me-1"></i>
                                        {{ $cheque['notes'] }}
                                    </small>
                                </div>
                            </div>
                            @endif

                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <small class="text-muted">
                                        <i class="fas fa-building me-1"></i>
                                        {{ $cheque['bank_name'] }} - {{ $cheque['branch'] }}
                                    </small>
                                </div>
                                <div class="col-md-6 text-end">
                                    <small class="text-muted">
                                        <i class="fas fa-user me-1"></i>
                                        {{ $cheque['endorser'] }}
                                    </small>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 mb-4">
        <div class="card mb-4" style="border: none; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); border: 1px solid rgba(0,0,0,0.06);">
            <div class="card-header" style="background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%); border-bottom: 1px solid rgba(102, 126, 234, 0.1); border-radius: 12px 12px 0 0;">
                <h5 class="mb-0" style="color: #667eea; font-weight: 600; font-size: 1rem;">
                    <i class="fas fa-chart-pie me-2"></i>
                    Çek Durumu Dağılımı
                </h5>
            </div>
            <div class="card-body" style="padding: 1rem;">
                <div class="chart-container" style="height: 250px; box-shadow: none; border: none; padding: 0.5rem;">
                    <canvas id="chequeStatusChart"></canvas>
                </div>
            </div>
        </div>

        <div class="card" style="border: none; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); border: 1px solid rgba(0,0,0,0.06);">
            <div class="card-header" style="background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%); border-bottom: 1px solid rgba(102, 126, 234, 0.1); border-radius: 12px 12px 0 0;">
                <h5 class="mb-0" style="color: #667eea; font-weight: 600; font-size: 1rem;">
                    <i class="fas fa-calendar-alt me-2"></i>
                    Aylık Çek Analizi
                </h5>
            </div>
            <div class="card-body" style="padding: 1rem;">
                <div class="chart-container" style="height: 200px; box-shadow: none; border: none; padding: 0.5rem;">
                    <canvas id="monthlyChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Yaklaşan Vadeler Modalı -->
<div class="modal fade" id="upcomingDuesModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white;">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Yaklaşan Vadeler
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                @foreach($upcomingDues as $due)
                <div class="alert alert-warning d-flex justify-content-between align-items-center">
                    <div>
                        <strong>{{ $due['customer_name'] }}</strong><br>
                        <small>Çek No: {{ $due['cheque_no'] }} | Tutar: ₺{{ number_format($due['amount'], 0, ',', '.') }}</small>
                    </div>
                    <div class="text-end">
                        <div class="fw-bold">{{ \Carbon\Carbon::parse($due['due_date'])->format('d.m.Y') }}</div>
                        <small>{{ \Carbon\Carbon::parse($due['due_date'])->diffForHumans() }}</small>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
                <button type="button" class="btn btn-warning">Hatırlatma Gönder</button>
            </div>
        </div>
    </div>
</div>

<!-- Yeni Çek Modalı (ve Düzenleme için kullanılır) -->
<div class="modal fade" id="addChequeModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); color: white;">
                <h5 class="modal-title">
                    <i class="fas fa-plus me-2"></i>
                    Yeni Müşteri Çeki
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editChequeForm">
                    <input type="hidden" id="editChequeId" value="">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Müşteri</label>
                            <select id="editCustomer" name="customer_id" class="form-control">
                                <option value="">-- Müşteri Seçiniz --</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Çek Görseli</label>
                            <input id="editChequeImage" name="image" type="file" accept="image/*" class="form-control">
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-4">
                            <label class="form-label">Çek Numarası</label>
                            <input id="editChequeNo" type="text" class="form-control" placeholder="123456789">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tutar</label>
                            <input id="editAmount" type="number" class="form-control" placeholder="0.00">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Para Birimi</label>
                            <select id="editCurrency" class="form-select">
                                <option value="TRY">TRY - Türk Lirası</option>
                                <option value="USD">USD - Amerikan Doları</option>
                                <option value="EUR">EUR - Euro</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label class="form-label">Düzenleme Tarihi</label>
                            <input id="editIssueDate" type="date" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Vade Tarihi</label>
                            <input id="editDueDate" type="date" class="form-control">
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label class="form-label">Banka Adı</label>
                            <input id="editBankName" type="text" class="form-control" placeholder="Banka adı">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Şube</label>
                            <input id="editBranch" type="text" class="form-control" placeholder="Şube adı">
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label class="form-label">Hesap Numarası</label>
                            <input id="editAccountNumber" type="text" class="form-control" placeholder="1234567890">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Ciranta</label>
                            <input id="editEndorser" type="text" class="form-control" placeholder="Ciranta adı">
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label class="form-label">Öncelik</label>
                            <select id="editPriority" class="form-select">
                                <option value="medium">Orta</option>
                                <option value="high">Yüksek</option>
                                <option value="urgent">Acil</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Durum</label>
                            <select id="editStatus" class="form-select">
                                <option value="pending">Bekliyor</option>
                                <option value="paid">Ödendi</option>
                                <option value="bounced">Karşılıksız</option>
                                <option value="cancelled">İptal Edildi</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-3">
                        <label class="form-label">Notlar</label>
                        <textarea id="editNotes" class="form-control" rows="3" placeholder="Çek hakkında ek notlar..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                <button type="button" id="saveEditedChequeBtn" class="btn btn-cheque">Kaydet</button>
            </div>
        </div>
    </div>
</div>

<!-- Çek Detay Modalı -->
<div class="modal fade" id="chequeDetailModal" tabindex="-1">
  <div class="modal-dialog modal-md modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Çek Detayları</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div id="chequeDetailContent">
          <p><strong>Müşteri:</strong> <span id="detailCustomer"></span></p>
          <p><strong>Çek No:</strong> <span id="detailChequeNo"></span></p>
          <p><strong>Tutar:</strong> <span id="detailAmount"></span></p>
          <p><strong>Vade:</strong> <span id="detailDueDate"></span></p>
          <p><strong>Banka / Şube:</strong> <span id="detailBank"></span></p>
          <p><strong>Ciranta:</strong> <span id="detailEndorser"></span></p>
          <p><strong>Notlar:</strong> <span id="detailNotes"></span></p>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Çek durumu grafik verileri
const statusData = {
    labels: ['Bekleyen', 'Ödenen', 'Karşılıksız'],
    datasets: [{
        data: [{{ $chequeStats['pending_cheques'] }}, {{ $chequeStats['paid_cheques'] }}, {{ $chequeStats['bounced_cheques'] }}],
        backgroundColor: [
            '#fbbf24',
            '#34d399',
            '#f87171'
        ],
        borderWidth: 2,
        borderColor: ['#fff', '#fff', '#fff'],
        hoverBorderWidth: 3,
        hoverBorderColor: ['#fff', '#fff', '#fff']
    }]
};

// Çek durumu pasta grafiği
const statusCtx = document.getElementById('chequeStatusChart').getContext('2d');
const statusChart = new Chart(statusCtx, {
    type: 'doughnut',
    data: statusData,
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 20,
                    usePointStyle: true
                }
            }
        }
    }
});

// Aylık grafik verileri
const monthlyData = @json($monthlyData);
const monthlyLabels = Object.keys(monthlyData).slice(-6);
const monthlyAmounts = monthlyLabels.map(month => monthlyData[month].amount / 1000);

const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
const monthlyChartObj = new Chart(monthlyCtx, {
    type: 'line',
    data: {
        labels: monthlyLabels,
        datasets: [{
            label: 'Tutar (₺000)',
            data: monthlyAmounts,
            borderColor: '#667eea',
            backgroundColor: 'rgba(102, 126, 234, 0.1)',
            borderWidth: 2,
            pointBackgroundColor: '#667eea',
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
            pointRadius: 4,
            pointHoverRadius: 6,
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    color: 'rgba(0,0,0,0.05)'
                }
            },
            x: {
                grid: {
                    color: 'rgba(0,0,0,0.05)'
                }
            }
        }
    }
});

// Arama fonksiyonu
document.getElementById('chequeSearch').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const chequeItems = document.querySelectorAll('.cheque-item');

    chequeItems.forEach(item => {
        const customerName = item.dataset.customer;
        if (customerName.includes(searchTerm)) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
});

// Filtre fonksiyonları
document.querySelectorAll('#chequeFilter button').forEach(button => {
    button.addEventListener('click', function() {
        const filter = this.id.replace('-tab', '');
        const chequeItems = document.querySelectorAll('.cheque-item');

        chequeItems.forEach(item => {
            if (filter === 'all' || item.dataset.status === filter) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    });
});

// Çek işlem fonksiyonları
function viewCheque(id) {
    // fetch details via AJAX and populate detail modal
    $.ajax({
        url: '/finance/cheques/' + encodeURIComponent(id),
        method: 'GET',
        success: function(res) {
            if (res && res.ok && res.cheque) {
                var c = res.cheque;
                $('#detailCustomer').text(c.customer_name || '—');
                $('#detailChequeNo').text(c.cheque_no || c.cheque_number || '—');
                $('#detailAmount').text(c.amount ? ('₺' + Number(c.amount).toLocaleString('tr-TR')) : '—');
                $('#detailDueDate').text(c.due_date ? new Date(c.due_date).toLocaleDateString('tr-TR') : '—');
                $('#detailBank').text([c.bank_name, c.branch].filter(Boolean).join(' / '));
                $('#detailEndorser').text(c.endorser || c.recipient || '—');
                $('#detailNotes').text(c.notes || '');
                $('#chequeDetailModal').modal('show');
            } else {
                alert('Detay alınamadı');
            }
        },
        error: function(err) {
            if (err.status === 403) { window.location = '/login'; return; }
            alert('Sunucu hatası');
        }
    });
}

function editCheque(id) {
    // load record and open edit modal
    $.ajax({
        url: '/finance/cheques/' + encodeURIComponent(id),
        method: 'GET',
        success: function(res) {
            if (res && res.ok && res.cheque) {
                var c = res.cheque;
                $('#editChequeId').val(c.id);
                // populate customer select (if customer_id present, ensure option exists and select it)
                var custId = c.customer_id || c.customerId || c.customer_id || null;
                var custName = c.customer_name || c.customer || '';
                if (custId) {
                    // if option doesn't exist, add it (this works whether Select2 is initialized or not)
                    if ($('#editCustomer option[value="' + custId + '"]').length === 0) {
                        try {
                            $('#editCustomer').append(new Option(custName || custId, custId, true, true));
                        } catch(e) {
                            // fallback: set html option
                            $('#editCustomer').html('<option value="'+custId+'">'+(custName||custId)+'</option>');
                        }
                    } else {
                        $('#editCustomer').val(custId).trigger('change');
                    }
                    // ensure select value is set
                    $('#editCustomer').val(custId).trigger('change');
                } else {
                    // no customer info returned: clear select
                    $('#editCustomer').val('').trigger('change');
                }
                $('#editChequeNo').val(c.cheque_no || c.cheque_number || '');
                $('#editAmount').val(c.amount || '');
                $('#editCurrency').val(c.currency || 'TRY');
                $('#editIssueDate').val(c.issue_date ? c.issue_date.split(' ')[0] : '');
                $('#editDueDate').val(c.due_date ? c.due_date.split(' ')[0] : '');
                $('#editBankName').val(c.bank_name || '');
                $('#editBranch').val(c.branch || '');
                $('#editAccountNumber').val(c.account_number || '');
                $('#editEndorser').val(c.endorser || c.recipient || '');
                $('#editPriority').val(c.priority || 'medium');
                $('#editStatus').val(c.status || 'pending');
                $('#editNotes').val(c.notes || '');
                $('#addChequeModal').modal('show');
            } else {
                alert('Kayıt yüklenemedi');
            }
        },
        error: function(err) { if (err.status === 403) { window.location = '/login'; return; } alert('Sunucu hatası'); }
    });
}

function deleteCheque(id) {
    if (!confirm('Bu çeki silmek istediğinizden emin misiniz?')) return;
    $.ajax({
        url: '/finance/cheques/' + encodeURIComponent(id),
        method: 'POST',
        data: { _method: 'DELETE' },
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function(res) {
            if (res && res.ok) {
                // remove element from DOM
                var el = $('[onclick*="deleteCheque(' + id + ')"]').closest('.cheque-item');
                if (el && el.length) el.fadeOut(200, function() { $(this).remove(); });
            } else {
                alert(res && res.message ? res.message : 'Silme başarısız');
            }
        },
        error: function(err) { if (err.status === 403) { window.location = '/login'; return; } alert('Sunucu hatası'); }
    });
}

// Sayfa yüklenme animasyonları
document.addEventListener('DOMContentLoaded', function() {
    const chequeItems = document.querySelectorAll('.cheque-item');
    chequeItems.forEach((item, index) => {
        item.style.opacity = '0';
        item.style.transform = 'translateY(20px)';

        setTimeout(() => {
            item.style.transition = 'all 0.5s ease';
            item.style.opacity = '1';
            item.style.transform = 'translateY(0)';
        }, index * 100);
    });
});

// Save edited cheque
$('#saveEditedChequeBtn').on('click', function() {
    var id = $('#editChequeId').val();
    if (!id) return alert('Kayıt seçilmedi');
    var form = document.getElementById('editChequeForm');
    var fd = new FormData(form);
    fd.append('_method', 'PUT');
    fd.append('cheque_no', $('#editChequeNo').val());
    fd.append('amount', $('#editAmount').val());
    fd.append('currency', $('#editCurrency').val());
    fd.append('issue_date', $('#editIssueDate').val());
    fd.append('due_date', $('#editDueDate').val());
    fd.append('bank_name', $('#editBankName').val());
    fd.append('branch', $('#editBranch').val());
    fd.append('account_number', $('#editAccountNumber').val());
    fd.append('endorser', $('#editEndorser').val());
    fd.append('priority', $('#editPriority').val());
    fd.append('status', $('#editStatus').val());
    fd.append('notes', $('#editNotes').val());
    // include selected customer id if select exists
    if ($('#editCustomer').length) fd.append('customer_id', $('#editCustomer').val());

    $.ajax({
        url: '/finance/cheques/' + encodeURIComponent(id),
        method: 'POST',
        data: fd,
        processData: false,
        contentType: false,
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function(res) {
            if (res && res.ok && res.cheque) {
                // refresh to reflect image and normalized data
                $('#addChequeModal').modal('hide');
                setTimeout(function(){ location.reload(); }, 300);
            } else {
                alert(res && res.message ? res.message : 'Güncelleme başarısız');
            }
        },
        error: function(err) { if (err.status === 403) { window.location = '/login'; return; } alert('Sunucu hatası'); }
    });
});

// initialize Select2 for edit modal customer select when modal shown
(function(){
    var s = document.createElement('script');
    s.src = 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js';
    s.onload = function(){
        $(document).on('shown.bs.modal', '#addChequeModal', function(){
                if (!$('#editCustomer').data('select2')) {
                $('#editCustomer').select2({
                    theme: 'bootstrap-5',
                    placeholder: '-- Müşteri Ara veya Seç --',
                    dropdownParent: $('#addChequeModal'),
                    ajax: {
                        url: '/finance/accounts/vouchers/search-parties',
                        dataType: 'json',
                        delay: 250,
                        data: function(params){ return { q: params.term }; },
                        processResults: function(data){
                            var out = [];
                            if (data && data.results) {
                                out = data.results.filter(r => r.type === 'customer').map(function(r){ return { id: r.id, text: r.text }; });
                            }
                            return { results: out };
                        }
                    },
                    minimumInputLength: 1
                });
            }
        });
    };
    document.head.appendChild(s);
})();
</script>
@endpush
