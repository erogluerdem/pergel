@extends('layouts.master')
@section('title', 'Nakit ve Banka Hesapları')
@section('topbar-title', 'Finans - Hesap Yönetimi')
@push('styles')
<style>
    .stats-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        overflow: hidden;
        background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);
        color: white;
        margin-bottom: 1.5rem;
        position: relative;
        z-index: 1;
    }
    .stats-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 30px rgba(245, 158, 11, 0.25);
        z-index: 2;
    }
    .stats-card .card-body {
        padding: 2rem 1.5rem;
        position: relative;
        z-index: 3;
    }
    .stats-card.bank {
        background: linear-gradient(135deg, #3B82F6 0%, #1D4ED8 100%);
        color: white;
        text-shadow: 0 1px 2px rgba(0,0,0,0.3);
    }
    .stats-card.cash {
        background: linear-gradient(135deg, #10B981 0%, #059669 100%);
        color: white;
        text-shadow: 0 1px 2px rgba(0,0,0,0.3);
    }
    .stats-card.total {
        background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);
        color: white;
        text-shadow: 0 1px 2px rgba(0,0,0,0.3);
    }
    .stats-card.count {
        background: linear-gradient(135deg, #334155 0%, #1E293B 100%);
        color: white;
        text-shadow: 0 1px 2px rgba(0,0,0,0.3);
    }

    .stats-icon {
        position: absolute;
        right: 20px;
        top: 20px;
        font-size: 2.5rem;
        opacity: 0.2;
        z-index: 1;
        color: rgba(255,255,255,0.4);
    }

    .account-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        margin-bottom: 1rem;
        transition: all 0.3s ease;
        background: white;
    }
    .account-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    .account-type-badge {
        border-radius: 20px;
        padding: 0.3rem 0.8rem;
        font-size: 0.8rem;
        font-weight: 600;
    }
    .badge-bank { background: #EBF4FF; color: #3B82F6; }
    .badge-cash { background: #ECFDF5; color: #10B981; }

    .balance-positive { color: #10B981; font-weight: 700; }
    .balance-negative { color: #DC2626; font-weight: 700; }

    .quick-action-btn {
        border-radius: 10px;
        padding: 0.75rem 1.25rem;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
        margin: 0.25rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .quick-action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.15);
    }
    .quick-action-btn.btn-primary { background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%); border: none; }
    .quick-action-btn.btn-success { background: linear-gradient(135deg, #10B981 0%, #059669 100%); border: none; }
    .quick-action-btn.btn-warning { background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%); border: none; }
    .quick-action-btn.btn-info { background: linear-gradient(135deg, #3B82F6 0%, #1D4ED8 100%); border: none; }
    .quick-action-btn.btn-secondary { background: linear-gradient(135deg, #334155 0%, #1E293B 100%); border: none; }

    .chart-container {
        position: relative;
        height: 300px;
        background: linear-gradient(135deg, #FFFFFF 0%, #F8FAFC 100%);
        border-radius: 15px;
        padding: 1.5rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        border: 1px solid #E2E8F0;
        transition: all 0.3s ease;
    }
    .chart-container:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.12);
    }

    .filter-section {
        background: linear-gradient(135deg, #FFFFFF 0%, #F8FAFC 100%);
        border-radius: 15px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        border: 1px solid #E2E8F0;
    }

    .custom-modal .modal-content {
        border: none;
        border-radius: 15px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.2);
    }

    .form-control {
        border-radius: 8px;
        border: 1px solid #e0e0e0;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
    }
    .form-control:focus {
        border-color: #F59E0B;
        box-shadow: 0 0 0 0.2rem rgba(245, 158, 11, 0.25);
    }

    .page-header {
        background: linear-gradient(135deg, #64748B 0%, #475569 100%);
        color: white;
        padding: 2rem 0;
        margin: 0.5rem 0 2rem 0;
        border-radius: 0 0 25px 25px;
        position: relative;
        overflow: hidden;
        z-index: 1;
    }
    .page-header .container-fluid {
        position: relative;
        z-index: 3;
    }
    .page-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(255,255,255,0.05)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
        opacity: 0.1;
        z-index: 2;
    }

    .activity-timeline {
        max-height: 400px;
        overflow-y: auto;
    }

    .timeline-item {
        padding: 1rem;
        border-left: 3px solid #F59E0B;
        margin-left: 1rem;
        position: relative;
        background: #FFF7ED;
        border-radius: 0 10px 10px 0;
        margin-bottom: 0.5rem;
        transition: all 0.3s ease;
    }
    .timeline-item:hover {
        background: #FED7AA;
        transform: translateX(5px);
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: -8px;
        top: 1.5rem;
        width: 12px;
        height: 12px;
        background: #F59E0B;
        border-radius: 50%;
        border: 2px solid white;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    /* Responsive Table Styles */
    .table-responsive {
        border-radius: 8px;
        box-shadow: none;
        overflow-x: auto; /* ensure horizontal scroll when table is wider than container */
        -webkit-overflow-scrolling: touch;
    }

    #accounts-table {
        min-width: 100%;
        table-layout: fixed;
    }

    #accounts-table th:first-child {
        width: 50px;
        min-width: 50px;
    }

    #accounts-table th:nth-child(2) {
        width: 25%;
        min-width: 200px;
    }

    #accounts-table th:nth-child(3) {
        width: 10%;
        min-width: 100px;
    }

    #accounts-table th:nth-child(4) {
        width: 15%;
        min-width: 120px;
    }

    #accounts-table th:nth-child(5) {
        width: 15%;
        min-width: 120px;
        text-align: right;
    }

    #accounts-table th:nth-child(6) {
        width: 12%;
        min-width: 100px;
    }

    #accounts-table th:nth-child(7) {
        width: 8%;
        min-width: 80px;
    }

    #accounts-table th:nth-child(8) {
        width: 15%;
        min-width: 120px;
    }

    #accounts-table td {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        vertical-align: middle;
    }

    #accounts-table td:nth-child(5) {
        text-align: right;
        font-weight: 600;
    }

    /* Dark mode support */
    @media (prefers-color-scheme: dark) {
        .stats-card:hover {
            box-shadow: 0 8px 30px rgba(245, 158, 11, 0.4);
        }
        .timeline-item {
            background: #1E293B;
            color: white;
        }
        .timeline-item:hover {
            background: #334155;
        }
        .chart-container {
            background: #1E293B;
            border-color: #334155;
        }
        .filter-section {
            background: #1E293B;
            border-color: #334155;
        }
    }

    /* Mobile responsive adjustments */
    @media (max-width: 768px) {
        .page-header {
            padding: 1.5rem 0;
        }
        .stats-card {
            margin-bottom: 1rem;
        }
        .stats-card .card-body {
            padding: 1.5rem 1rem;
        }
        .stats-card h2 {
            font-size: 1.5rem !important;
        }
        .stats-card h6 {
            font-size: 0.8rem !important;
        }
        .stats-icon {
            font-size: 2rem !important;
            right: 15px;
            top: 15px;
        }
        .quick-action-btn {
            margin: 0.15rem;
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }
        #accounts-table th:nth-child(4),
        #accounts-table td:nth-child(4),
        #accounts-table th:nth-child(6),
        #accounts-table td:nth-child(6) {
            display: none;
        }

        #accounts-table th:nth-child(2) {
            width: 40%;
        }

        #accounts-table th:nth-child(3) {
            width: 20%;
        }

        #accounts-table th:nth-child(5) {
            width: 25%;
        }

        #accounts-table th:nth-child(8) {
            width: 15%;
        }
    }

    @media (max-width: 576px) {
        #accounts-table th:nth-child(3),
        #accounts-table td:nth-child(3) {
            display: none;
        }

        #accounts-table th:nth-child(2) {
            width: 50%;
        }

        #accounts-table th:nth-child(5) {
            width: 30%;
        }

        #accounts-table th:nth-child(8) {
            width: 20%;
        }
    }

    /* Account info responsive styles */
    .account-info {
        max-width: 100%;
    }

    .account-name {
        font-weight: 600;
        color: #334155;
        display: block;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .account-details {
        font-size: 0.85rem;
        color: #6c757d;
        display: block;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Badge responsive styles */
    .account-type-badge {
        display: inline-block;
        white-space: nowrap;
    }

    @media (max-width: 576px) {
        .account-type-badge {
            font-size: 0.7rem;
            padding: 0.2rem 0.5rem;
        }
    }

    /* Button group responsive */
    .btn-group-sm .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }

    @media (max-width: 576px) {
        .btn-group-sm .btn {
            padding: 0.2rem 0.4rem;
            font-size: 0.7rem;
        }

        .btn-group-sm .btn i {
            font-size: 0.7rem;
        }
    }
</style>
@endpush
@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h2 class="mb-0"><i class="fas fa-university me-3"></i>Nakit ve Banka Hesapları</h2>
                    <p class="mb-0 opacity-75">Tüm finansal hesaplarınızı tek yerden yönetin</p>
                </div>
                <div class="col-md-6 text-end">
                    <button class="btn btn-warning btn-lg shadow-sm" data-bs-toggle="modal" data-bs-target="#modal-new-account" style="background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%); border: none; color: white;">
                        <i class="fas fa-plus me-2"></i>Yeni Hesap Ekle
                    </button>
                    <button class="btn btn-outline-light ms-2 shadow-sm" onclick="exportData()" style="border-color: rgba(255,255,255,0.3);">
                        <i class="fas fa-download me-2"></i>Dışa Aktar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card stats-card total">
                <div class="card-body">
                    <div class="stats-icon">
                        <i class="fas fa-coins"></i>
                    </div>
                    <h6 class="mb-2" style="color: rgba(255,255,255,0.9); font-weight: 500; font-size: 0.9rem;">Toplam Nakit Varlığı</h6>
                    <h2 id="total-cash" class="mb-2" style="color: white; font-weight: 700; font-size: 1.8rem; text-shadow: 0 1px 2px rgba(0,0,0,0.3);">₺0,00</h2>
                    <small style="color: rgba(255,255,255,0.8);">
                        <span id="cash-change" class="badge" style="background: rgba(255,255,255,0.2); color: white; font-weight: 600;">+0%</span>
                        <span class="ms-1">bu ay</span>
                    </small>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card stats-card bank">
                <div class="card-body">
                    <div class="stats-icon">
                        <i class="fas fa-university"></i>
                    </div>
                    <h6 class="mb-2" style="color: rgba(255,255,255,0.9); font-weight: 500; font-size: 0.9rem;">Banka Hesapları</h6>
                    <h2 id="total-bank" class="mb-2" style="color: white; font-weight: 700; font-size: 1.8rem; text-shadow: 0 1px 2px rgba(0,0,0,0.3);">₺0,00</h2>
                    <small style="color: rgba(255,255,255,0.8);">
                        <span id="bank-change" class="badge" style="background: rgba(255,255,255,0.2); color: white; font-weight: 600;">+0%</span>
                        <span class="ms-1">bu ay</span>
                    </small>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card stats-card cash">
                <div class="card-body">
                    <div class="stats-icon">
                        <i class="fas fa-cash-register"></i>
                    </div>
                    <h6 class="mb-2" style="color: rgba(255,255,255,0.9); font-weight: 500; font-size: 0.9rem;">Kasa Hesapları</h6>
                    <h2 id="total-cashbox" class="mb-2" style="color: white; font-weight: 700; font-size: 1.8rem; text-shadow: 0 1px 2px rgba(0,0,0,0.3);">₺0,00</h2>
                    <small style="color: rgba(255,255,255,0.8);">
                        <span id="cashbox-change" class="badge" style="background: rgba(255,255,255,0.2); color: white; font-weight: 600;">+0%</span>
                        <span class="ms-1">bu ay</span>
                    </small>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card stats-card count">
                <div class="card-body">
                    <div class="stats-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h6 class="mb-2" style="color: rgba(255,255,255,0.9); font-weight: 500; font-size: 0.9rem;">Aktif Hesap Sayısı</h6>
                    <h2 id="total-accounts" class="mb-2" style="color: white; font-weight: 700; font-size: 1.8rem; text-shadow: 0 1px 2px rgba(0,0,0,0.3);">0</h2>
                    <small style="color: rgba(255,255,255,0.8);">
                        <span id="inactive-accounts">0</span> pasif hesap
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts and Analytics -->
    <div class="row mb-4">
        <div class="col-lg-4 mb-3">
            <div class="chart-container">
                <h6 class="mb-3"><i class="fas fa-chart-pie me-2 text-primary"></i>Nakit Dağılımı</h6>
                <canvas id="cash-distribution-chart"></canvas>
            </div>
        </div>
        <div class="col-lg-5 mb-3">
            <div class="chart-container">
                <h6 class="mb-3"><i class="fas fa-chart-line me-2 text-success"></i>Nakit Akış Trendi (Son 30 Gün)</h6>
                <canvas id="cash-trend-chart"></canvas>
            </div>
        </div>
        <div class="col-lg-3 mb-3">
            <div class="chart-container">
                <h6 class="mb-3"><i class="fas fa-clock me-2 text-info"></i>Son Aktiviteler</h6>
                <div class="activity-timeline" id="recent-activities">
                    <!-- Dinamik olarak doldurulacak -->
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card" style="border-radius: 15px; border: 1px solid #E2E8F0; box-shadow: 0 4px 15px rgba(0,0,0,0.08); background: linear-gradient(135deg, #FFFFFF 0%, #F8FAFC 100%);">
                <div class="card-body" style="padding: 2rem;">
                    <h6 class="mb-4" style="color: #334155; font-weight: 600;"><i class="fas fa-bolt me-2" style="color: #F59E0B;"></i>Hızlı İşlemler</h6>
                    <div class="d-flex flex-wrap">
                        <button class="btn btn-primary quick-action-btn" onclick="quickTransfer()">
                            <i class="fas fa-exchange-alt me-2"></i>Hesap Arası Transfer
                        </button>
                        <button class="btn btn-success quick-action-btn" onclick="quickDeposit()">
                            <i class="fas fa-plus-circle me-2"></i>Para Yatır
                        </button>
                        <button class="btn btn-warning quick-action-btn" onclick="quickWithdraw()">
                            <i class="fas fa-minus-circle me-2"></i>Para Çek
                        </button>
                        <button class="btn btn-info quick-action-btn" onclick="reconcileAccounts()">
                            <i class="fas fa-balance-scale me-2"></i>Hesap Mutabakatı
                        </button>
                        <button class="btn btn-secondary quick-action-btn" onclick="generateReports()">
                            <i class="fas fa-file-alt me-2"></i>Rapor Oluştur
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Advanced Filters -->
    <div class="filter-section">
        <div class="row align-items-center">
            <div class="col-md-3">
                <label class="form-label fw-bold">Hesap Ara</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input id="search" class="form-control" placeholder="Hesap adı, IBAN, banka ara...">
                </div>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-bold">Hesap Tipi</label>
                <select id="filter-type" class="form-select">
                    <option value="">Tüm Hesaplar</option>
                    <option value="bank">Banka Hesapları</option>
                    <option value="cashbox">Kasa Hesapları</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-bold">Para Birimi</label>
                <select id="filter-currency" class="form-select">
                    <option value="">Tüm Para Birimleri</option>
                    <option value="TRY">TRY</option>
                    <option value="USD">USD</option>
                    <option value="EUR">EUR</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-bold">Durum</label>
                <select id="filter-status" class="form-select">
                    <option value="">Tümü</option>
                    <option value="active">Aktif</option>
                    <option value="inactive">Pasif</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Bakiye Aralığı</label>
                <div class="row">
                    <div class="col-6">
                        <input type="number" id="min-balance" class="form-control" placeholder="Min">
                    </div>
                    <div class="col-6">
                        <input type="number" id="max-balance" class="form-control" placeholder="Max">
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <button class="btn shadow-sm" onclick="applyFilters()" style="background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%); border: none; color: white; border-radius: 10px; padding: 0.75rem 1.5rem;">
                    <i class="fas fa-filter me-2"></i>Filtreleri Uygula
                </button>
                <button class="btn btn-outline-secondary ms-2 shadow-sm" onclick="clearFilters()" style="border-radius: 10px; padding: 0.75rem 1.5rem;">
                    <i class="fas fa-times me-2"></i>Temizle
                </button>
                <div class="float-end">
                    <button class="btn btn-outline-primary me-2 shadow-sm" onclick="toggleView('grid')" style="border-color: #3B82F6; color: #3B82F6; border-radius: 10px;">
                        <i class="fas fa-th me-2"></i>Kart Görünümü
                    </button>
                    <button class="btn btn-outline-primary shadow-sm" onclick="toggleView('table')" style="border-color: #3B82F6; color: #3B82F6; border-radius: 10px;">
                        <i class="fas fa-list me-2"></i>Liste Görünümü
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Accounts Grid View -->
    <div id="accounts-grid" class="row" style="display: none;">
        <!-- Dinamik olarak doldurulacak -->
    </div>

    <!-- Accounts Table View -->
    <div id="accounts-table-container" class="card" style="border-radius: 15px; border: 1px solid #E2E8F0; box-shadow: 0 4px 15px rgba(0,0,0,0.08);">
        <div class="card-header" style="background: linear-gradient(135deg, #FFFFFF 0%, #F8FAFC 100%); border-radius: 15px 15px 0 0; border-bottom: 1px solid #E2E8F0;">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0" style="color: #334155;"><i class="fas fa-list me-2" style="color: #F59E0B;"></i>Hesap Listesi</h5>
                <div>
                    <span class="badge me-2" id="accounts-count" style="background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%); color: white; border-radius: 20px; padding: 0.5rem 1rem;">0 hesap</span>
                    <div class="btn-group">
                        <button class="btn btn-outline-primary btn-sm dropdown-toggle shadow-sm" data-bs-toggle="dropdown" style="border-color: #3B82F6; color: #3B82F6; border-radius: 8px;">
                            <i class="fas fa-cog me-2"></i>İşlemler
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#" onclick="bulkActivate()"><i class="fas fa-check me-2"></i>Toplu Aktifleştir</a></li>
                            <li><a class="dropdown-item" href="#" onclick="bulkDeactivate()"><i class="fas fa-times me-2"></i>Toplu Pasifleştir</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#" onclick="exportSelected()"><i class="fas fa-download me-2"></i>Seçilenleri Dışa Aktar</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="accounts-table">
                    <thead class="table-light">
                        <tr>
                            <th width="50">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="select-all">
                                </div>
                            </th>
                            <th>Hesap Bilgileri</th>
                            <th>Hesap Tipi</th>
                            <th>Banka / Şube</th>
                            <th>Bakiye</th>
                            <th>Son Hareket</th>
                            <th>Durum</th>
                            <th width="120">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody id="accounts-tbody">
                        <!-- Dinamik olarak doldurulacak -->
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer" style="background: linear-gradient(135deg, #F8FAFC 0%, #F1F5F9 100%); border-top: 1px solid #E2E8F0; border-radius: 0 0 15px 15px;">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted">
                    <span id="showing-info">Gösterilen: 0-0 / 0</span>
                </div>
                <nav>
                    <ul class="pagination pagination-sm mb-0" id="pagination">
                        <!-- Dinamik sayfalama -->
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    <!-- Account Detail Modal -->
    <div class="modal fade custom-modal" id="modal-account-detail" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header text-white" style="background: linear-gradient(135deg, #334155 0%, #1E293B 100%);">
                    <h5 class="modal-title"><i class="fas fa-info-circle me-2" style="color: #F59E0B;"></i>Hesap Detayı</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="row g-0">
                        <div class="col-md-4 bg-light p-4">
                            <div id="account-basic-info">
                                <!-- Temel hesap bilgileri -->
                            </div>
                        </div>
                        <div class="col-md-8">
                            <ul class="nav nav-tabs" id="accountDetailTabs">
                                <li class="nav-item">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#transactions-tab">
                                        <i class="fas fa-list me-2"></i>Hareketler
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#balance-history-tab">
                                        <i class="fas fa-chart-line me-2"></i>Bakiye Geçmişi
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#reconciliation-tab">
                                        <i class="fas fa-balance-scale me-2"></i>Mutabakat
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#settings-tab">
                                        <i class="fas fa-cog me-2"></i>Ayarlar
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content p-4">
                                <div class="tab-pane fade show active" id="transactions-tab">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="mb-0">Son Hareketler</h6>
                                        <button class="btn btn-primary btn-sm" onclick="showAllTransactions()">
                                            <i class="fas fa-eye me-2"></i>Tümünü Görüntüle
                                        </button>
                                    </div>
                                    <div id="account-transactions" class="table-responsive">
                                        <!-- Hareketler tablosu -->
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="balance-history-tab">
                                    <h6 class="mb-3">Bakiye Değişim Grafiği</h6>
                                    <canvas id="balance-history-chart" height="300"></canvas>
                                </div>
                                <div class="tab-pane fade" id="reconciliation-tab">
                                    <div id="reconciliation-content">
                                        <!-- Mutabakat içeriği -->
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="settings-tab">
                                    <div id="account-settings">
                                        <!-- Hesap ayarları -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" onclick="editAccount()">
                        <i class="fas fa-edit me-2"></i>Düzenle
                    </button>
                    <button type="button" class="btn btn-outline-success" onclick="quickTransaction()">
                        <i class="fas fa-plus me-2"></i>Hızlı İşlem
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
                </div>
            </div>
        </div>
    </div>

    <!-- New Account Modal -->
    <div class="modal fade custom-modal" id="modal-new-account" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <form id="form-new-account" class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title"><i class="fas fa-plus-circle me-2"></i>Yeni Hesap Ekle</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Hesap Tipi <span class="text-danger">*</span></label>
                                <select id="new-account-type" name="type" class="form-select" required>
                                    <option value="">Hesap tipi seçin</option>
                                    <option value="bank">🏦 Banka Hesabı</option>
                                    <option value="cashbox">💰 Kasa Hesabı</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Para Birimi <span class="text-danger">*</span></label>
                                <select name="currency" class="form-select" required>
                                    <option value="TRY">🇹🇷 TRY - Türk Lirası</option>
                                    <option value="USD">🇺🇸 USD - Amerikan Doları</option>
                                    <option value="EUR">🇪🇺 EUR - Euro</option>
                                    <option value="GBP">🇬🇧 GBP - İngiliz Sterlini</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Hesap Adı <span class="text-danger">*</span></label>
                        <input name="name" class="form-control" placeholder="Örn: Garanti Bankası - Ticari Hesap" required>
                        <div class="form-text">Hesabınızı kolayca tanımlayabileceğiniz açıklayıcı bir isim verin</div>
                    </div>

                    <!-- Banka Hesabı Alanları -->
                    <div id="bank-fields" style="display:none;">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Banka Hesabı Bilgileri</strong> - Lütfen tüm alanları eksiksiz doldurun
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Banka Adı</label>
                                    <select name="bank_name" class="form-select">
                                        <option value="">Banka seçin</option>
                                        <option value="Ziraat Bankası">Ziraat Bankası</option>
                                        <option value="Garanti BBVA">Garanti BBVA</option>
                                        <option value="İş Bankası">İş Bankası</option>
                                        <option value="Akbank">Akbank</option>
                                        <option value="Yapı Kredi">Yapı Kredi</option>
                                        <option value="Halkbank">Halkbank</option>
                                        <option value="VakıfBank">VakıfBank</option>
                                        <option value="Diğer">Diğer</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Şube</label>
                                    <input name="branch" class="form-control" placeholder="Şube adı">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Hesap Numarası</label>
                                    <input name="account_no" class="form-control" placeholder="Hesap numarası">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">IBAN</label>
                                    <input name="iban" class="form-control" placeholder="TR00 0000 0000 0000 0000 0000 00" maxlength="32">
                                    <div class="form-text">IBAN formatında giriniz (boşluklu veya boşluksuz)</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kasa Hesabı Alanları -->
                    <div id="cashbox-fields" style="display:none;">
                        <div class="alert alert-warning">
                            <i class="fas fa-cash-register me-2"></i>
                            <strong>Kasa Hesabı Bilgileri</strong> - Nakit takibi için gerekli bilgiler
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Kasa Sorumlusu</label>
                                    <input name="responsible" class="form-control" placeholder="Sorumlu kişi adı">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Kasa Yeri</label>
                                    <input name="location" class="form-control" placeholder="Kasa konumu">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Açılış Bakiyesi</label>
                                <div class="input-group">
                                    <span class="input-group-text">₺</span>
                                    <input name="opening_balance" class="form-control" type="number" step="0.01" value="0" placeholder="0,00">
                                </div>
                                <div class="form-text">Hesabın ilk açılış bakiyesi</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Açılış Tarihi</label>
                                <input name="opening_date" class="form-control" type="date" value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" checked>
                            <label class="form-check-label" for="is_active">
                                Hesabı aktif olarak oluştur
                            </label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Notlar</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="Bu hesap hakkında notlarınız..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>İptal
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-2"></i>Hesabı Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Quick Transfer Modal -->
    <div class="modal fade custom-modal" id="modal-quick-transfer" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title"><i class="fas fa-exchange-alt me-2"></i>Hızlı Transfer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="form-quick-transfer">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Kaynak Hesap</label>
                            <select name="from_account" class="form-select" required>
                                <option value="">Hesap seçin</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Hedef Hesap</label>
                            <select name="to_account" class="form-select" required>
                                <option value="">Hesap seçin</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tutar</label>
                            <div class="input-group">
                                <span class="input-group-text">₺</span>
                                <input name="amount" class="form-control" type="number" step="0.01" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Açıklama</label>
                            <input name="description" class="form-control" placeholder="Transfer açıklaması">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                    <button type="button" class="btn btn-warning" onclick="processTransfer()">
                        <i class="fas fa-arrow-right me-2"></i>Transfer Yap
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
<!-- Enhanced Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<!-- Professional Account Management System -->
<script>
// Global Variables
let currentAccounts = [];
let currentView = 'table';
let accountsTable = null;

// Enhanced Account Management System
document.addEventListener('DOMContentLoaded', function(){
    initializeSystem();
    loadAccountData();
    setupEventListeners();
});

function initializeSystem() {
    // Tooltip initialization
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

function loadAccountData() {
    const accountsUrl = '{{ route("finance.cash.accounts.data") }}';

    // Show loading animation
    showLoadingSpinner();

    fetch(accountsUrl+'?kind=accounts').then(r=>{
        if(!r.ok) throw new Error('no data');
        return r.json();
    }).then(json=>{
        currentAccounts = json.accounts || [];
        const totals = json.totals || {};
        renderAccounts(currentAccounts);
        renderTotals(totals);
        renderCharts(json.distribution || [], json.trend || []);
        renderRecentActivities(json.activities || []);
        hideLoadingSpinner();
    }).catch(()=>{
        // Enhanced demo data with more realistic information
        const demoAccounts = [
            {
                id: 1,
                name: 'Garanti BBVA - Ticari Hesap',
                type: 'bank',
                bank: 'Garanti BBVA',
                branch: 'Niğde Merkez Şube',
                account_no: '1234567890',
                iban: 'TR33 0006 2000 1234 5678 9012 34',
                balance: 2875420.50,
                currency: 'TRY',
                active: true,
                last_transaction: '2024-10-29',
                opening_date: '2023-01-15',
                responsible: null
            },
            {
                id: 2,
                name: 'Ana Kasa - TL',
                type: 'cashbox',
                bank: '-',
                branch: '-',
                balance: 125750.00,
                currency: 'TRY',
                active: true,
                last_transaction: '2024-10-30',
                opening_date: '2023-01-01',
                responsible: 'Ahmet Yılmaz'
            },
            {
                id: 3,
                name: 'Akbank - USD Hesabı',
                type: 'bank',
                bank: 'Akbank',
                branch: 'Niğde Şube',
                account_no: '9876543210',
                iban: 'TR44 0004 6000 9876 5432 1098 76',
                balance: 85420.75,
                currency: 'USD',
                active: true,
                last_transaction: '2024-10-28',
                opening_date: '2023-03-10'
            },
            {
                id: 4,
                name: 'İş Bankası - EUR Hesabı',
                type: 'bank',
                bank: 'İş Bankası',
                branch: 'Ankara Çankaya Şube',
                balance: 42300.25,
                currency: 'EUR',
                active: true,
                last_transaction: '2024-10-27',
                opening_date: '2023-06-20'
            },
            {
                id: 5,
                name: 'Yedek Kasa - USD',
                type: 'cashbox',
                bank: '-',
                branch: '-',
                balance: 15000.00,
                currency: 'USD',
                active: false,
                last_transaction: '2024-10-25',
                opening_date: '2023-02-01',
                responsible: 'Fatma Özkan'
            }
        ];

        const totals = {
            total: 3143891.50,
            total_bank: 3003141.50,
            total_cash: 140750.00,
            count: 5,
            active_count: 4,
            inactive_count: 1
        };

        const distribution = [
            {label:'Garanti BBVA', value:2875420.50, color:'#38c66c'},
            {label:'Ana Kasa', value:125750.00, color:'#4caf50'},
            {label:'Akbank USD', value:85420.75, color:'#66bb6a'},
            {label:'İş Bankası EUR', value:42300.25, color:'#81c784'},
            {label:'Yedek Kasa', value:15000.00, color:'#2e7d32'}
        ];

        const trend = Array.from({length:30}).map((_,i)=>{
            const date = new Date(Date.now()-(29-i)*24*3600*1000);
            const baseValue = 3000000;
            const variation = Math.sin(i/5) * 100000 + Math.random() * 50000;
            return {
                date: date.toISOString().slice(0,10),
                value: baseValue + variation + (i * 5000)
            };
        });

        const activities = [
            {type: 'deposit', account: 'Garanti BBVA', amount: 50000, date: '2024-10-30 14:30'},
            {type: 'withdrawal', account: 'Ana Kasa', amount: 15000, date: '2024-10-30 11:15'},
            {type: 'transfer', from: 'Akbank USD', to: 'Ana Kasa', amount: 5000, date: '2024-10-29 16:45'},
            {type: 'deposit', account: 'İş Bankası EUR', amount: 2500, date: '2024-10-29 09:20'},
            {type: 'withdrawal', account: 'Garanti BBVA', amount: 25000, date: '2024-10-28 13:10'}
        ];

        currentAccounts = demoAccounts;
        renderAccounts(demoAccounts);
        renderTotals(totals);
        renderCharts(distribution, trend);
        renderRecentActivities(activities);
        hideLoadingSpinner();
    });

function renderAccounts(accounts) {
    if (currentView === 'table') {
        renderTableView(accounts);
    } else {
        renderGridView(accounts);
    }

    // Update accounts count
    document.getElementById('accounts-count').textContent = `${accounts.length} hesap`;
    updateShowingInfo(accounts.length);
}

function renderTableView(accounts) {
    const tbody = document.getElementById('accounts-tbody');
    tbody.innerHTML = '';

    accounts.forEach((account, index) => {
        const row = createTableRow(account, index);
        tbody.appendChild(row);
    });

    // Initialize or refresh DataTable
    setTimeout(() => {
        if (accountsTable) {
            accountsTable.destroy();
        }

        accountsTable = $('#accounts-table').DataTable({
            pageLength: 10,
            ordering: true,
            responsive: true,
            scrollX: true,
            autoWidth: false,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/Turkish.json'
            },
            columnDefs: [
                { orderable: false, targets: [0, 7] },
                { className: 'text-center', targets: [0, 2, 6, 7] },
                { width: '50px', targets: 0 },
                { width: '25%', targets: 1 },
                { width: '10%', targets: 2 },
                { width: '15%', targets: 3 },
                { width: '15%', targets: 4 },
                { width: '12%', targets: 5 },
                { width: '8%', targets: 6 },
                { width: '15%', targets: 7 }
            ],
            order: [[1, 'asc']],
            drawCallback: function() {
                // Re-attach event listeners after DataTable redraw
                attachTableEventListeners();
            }
        });
    }, 100);
}

function createTableRow(account, index) {
    const tr = document.createElement('tr');
    tr.className = 'animate__animated animate__fadeInUp';
    tr.style.animationDelay = `${index * 0.1}s`;

    const balanceClass = account.balance >= 0 ? 'balance-positive' : 'balance-negative';
    const statusBadge = account.active ?
        '<span class="badge bg-success"><i class="fas fa-check me-1"></i>Aktif</span>' :
        '<span class="badge bg-danger"><i class="fas fa-times me-1"></i>Pasif</span>';

    const typeBadge = account.type === 'bank' ?
        '<span class="account-type-badge badge-bank"><i class="fas fa-university me-1"></i>Banka</span>' :
        '<span class="account-type-badge badge-cash"><i class="fas fa-cash-register me-1"></i>Kasa</span>';

    tr.innerHTML = `
        <td>
            <div class="form-check">
                <input type="checkbox" class="form-check-input account-select" value="${account.id}">
            </div>
        </td>
        <td>
            <div class="d-flex align-items-center account-info">
                <div class="account-avatar me-2 d-none d-md-block">
                    <i class="fas ${account.type === 'bank' ? 'fa-university' : 'fa-cash-register'} text-primary"></i>
                </div>
                <div class="flex-grow-1" style="min-width: 0;">
                    <div class="account-name account-link" data-account-id="${account.id}" style="cursor: pointer;" title="${escapeHtml(account.name)}">
                        ${escapeHtml(account.name)}
                    </div>
                    <div class="account-details">
                        ${account.account_no ? `Hesap: ${account.account_no}` : ''}
                        ${account.iban ? ` • IBAN: ${account.iban.substring(0, 10)}...` : ''}
                    </div>
                </div>
            </div>
        </td>
        <td class="text-center">${typeBadge}</td>
        <td class="d-none d-md-table-cell">
            <div class="fw-bold text-truncate" title="${escapeHtml(account.bank || '-')}">${escapeHtml(account.bank || '-')}</div>
            <small class="text-muted text-truncate d-block" title="${escapeHtml(account.branch || '')}">${escapeHtml(account.branch || '')}</small>
        </td>
        <td>
            <div class="${balanceClass} fw-bold" style="font-size: 0.9rem;">
                ${formatMoney(account.balance)} ${account.currency}
            </div>
            <small class="text-muted d-none d-sm-block">Son: ${formatDate(account.last_transaction)}</small>
        </td>
        <td class="d-none d-lg-table-cell">
            <small class="text-muted">${formatDate(account.last_transaction)}</small>
        </td>
        <td class="text-center">${statusBadge}</td>
        <td class="text-center">
            <div class="btn-group btn-group-sm d-none d-sm-inline-flex" role="group">
                <button class="btn btn-outline-primary" onclick="showAccountDetail(${account.id})"
                        data-bs-toggle="tooltip" title="Detayları Görüntüle">
                    <i class="fas fa-eye"></i>
                </button>
                <button class="btn btn-outline-success" onclick="quickTransaction(${account.id})"
                        data-bs-toggle="tooltip" title="Hızlı İşlem">
                    <i class="fas fa-plus"></i>
                </button>
                <button class="btn btn-outline-warning" onclick="editAccount(${account.id})"
                        data-bs-toggle="tooltip" title="Düzenle">
                    <i class="fas fa-edit"></i>
                </button>
            </div>
            <div class="dropdown d-sm-none">
                <button class="btn btn-outline-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="fas fa-ellipsis-v"></i>
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#" onclick="showAccountDetail(${account.id})"><i class="fas fa-eye me-2"></i>Detaylar</a></li>
                    <li><a class="dropdown-item" href="#" onclick="quickTransaction(${account.id})"><i class="fas fa-plus me-2"></i>İşlem</a></li>
                    <li><a class="dropdown-item" href="#" onclick="editAccount(${account.id})"><i class="fas fa-edit me-2"></i>Düzenle</a></li>
                </ul>
            </div>
        </td>
    `;

    return tr;
}

function renderGridView(accounts) {
    const grid = document.getElementById('accounts-grid');
    grid.innerHTML = '';

    accounts.forEach((account, index) => {
        const card = createAccountCard(account, index);
        grid.appendChild(card);
    });
}

function createAccountCard(account, index) {
    const col = document.createElement('div');
    col.className = 'col-lg-4 col-md-6 mb-4';

    const balanceClass = account.balance >= 0 ? 'text-success' : 'text-danger';
    const statusIcon = account.active ? 'fas fa-check-circle text-success' : 'fas fa-times-circle text-danger';
    const typeIcon = account.type === 'bank' ? 'fas fa-university' : 'fas fa-cash-register';

    col.innerHTML = `
        <div class="account-card animate__animated animate__fadeInUp" style="animation-delay: ${index * 0.1}s;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-gradient rounded-circle p-2 me-3">
                            <i class="${typeIcon} text-white"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold">${escapeHtml(account.name)}</h6>
                            <small class="text-muted">${account.type === 'bank' ? 'Banka Hesabı' : 'Kasa Hesabı'}</small>
                        </div>
                    </div>
                    <i class="${statusIcon}"></i>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Bakiye:</span>
                        <span class="${balanceClass} fw-bold">${formatMoney(account.balance)} ${account.currency}</span>
                    </div>
                    ${account.bank ? `
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Banka:</span>
                            <span>${escapeHtml(account.bank)}</span>
                        </div>
                    ` : ''}
                    ${account.responsible ? `
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Sorumlu:</span>
                            <span>${escapeHtml(account.responsible)}</span>
                        </div>
                    ` : ''}
                </div>

                <div class="d-flex justify-content-between">
                    <button class="btn btn-outline-primary btn-sm" onclick="showAccountDetail(${account.id})">
                        <i class="fas fa-eye me-1"></i>Detay
                    </button>
                    <button class="btn btn-outline-success btn-sm" onclick="quickTransaction(${account.id})">
                        <i class="fas fa-plus me-1"></i>İşlem
                    </button>
                    <button class="btn btn-outline-warning btn-sm" onclick="editAccount(${account.id})">
                        <i class="fas fa-edit me-1"></i>Düzenle
                    </button>
                </div>
            </div>
        </div>
    `;

    return col;
}
function renderTotals(totals) {
    // Animate counter updates
    animateCounter('total-cash', totals.total || 0, '₺');
    animateCounter('total-bank', totals.total_bank || 0, '₺');
    animateCounter('total-cashbox', totals.total_cash || 0, '₺');
    animateCounter('total-accounts', totals.active_count || totals.count || 0);

    // Update change indicators
    document.getElementById('cash-change').textContent = '+2.5%';
    document.getElementById('bank-change').textContent = '+1.8%';
    document.getElementById('cashbox-change').textContent = '-0.5%';
    document.getElementById('inactive-accounts').textContent = `${totals.inactive_count || 0} pasif hesap`;
}

function animateCounter(elementId, targetValue, suffix = '') {
    const element = document.getElementById(elementId);
    const startValue = 0;
    const duration = 2000;
    const startTime = Date.now();

    function updateCounter() {
        const elapsed = Date.now() - startTime;
        const progress = Math.min(elapsed / duration, 1);
        const currentValue = startValue + (targetValue - startValue) * easeOutCubic(progress);

        element.textContent = formatMoney(currentValue) + suffix;

        if (progress < 1) {
            requestAnimationFrame(updateCounter);
        }
    }

    updateCounter();
}

function easeOutCubic(t) {
    return 1 - Math.pow(1 - t, 3);
}
function renderCharts(distribution, trend) {
    if (typeof Chart === 'undefined') return;

    // Cash Distribution Chart
    const ctx1 = document.getElementById('cash-distribution-chart').getContext('2d');
    new Chart(ctx1, {
        type: 'doughnut',
        data: {
            labels: distribution.map(d => d.label),
            datasets: [{
                data: distribution.map(d => d.value),
                backgroundColor: distribution.map(d => d.color || '#38c66c'),
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        padding: 15
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const value = formatMoney(context.raw);
                            const percentage = ((context.raw / distribution.reduce((a,b) => a + b.value, 0)) * 100).toFixed(1);
                            return `${context.label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });

    // Cash Trend Chart
    const ctx2 = document.getElementById('cash-trend-chart').getContext('2d');
    new Chart(ctx2, {
        type: 'line',
        data: {
            labels: trend.map(t => formatDate(t.date)),
            datasets: [{
                label: 'Toplam Nakit Varlığı',
                data: trend.map(t => t.value),
                borderColor: '#38c66c',
                backgroundColor: 'rgba(56, 198, 108, 0.1)',
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#38c66c',
                pointBorderColor: '#fff',
                pointBorderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                        label: function(context) {
                            return `${context.dataset.label}: ${formatMoney(context.raw)} TL`;
                        }
                    }
                }
            },
            scales: {
                x: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Tarih'
                    }
                },
                y: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Tutar (TL)'
                    },
                    ticks: {
                        callback: function(value) {
                            return formatMoney(value);
                        }
                    }
                }
            },
            interaction: {
                mode: 'nearest',
                intersect: false
            }
        }
    });
}

function renderRecentActivities(activities) {
    const container = document.getElementById('recent-activities');
    container.innerHTML = '';

    if (!activities || activities.length === 0) {
        container.innerHTML = '<div class="text-center text-muted"><i class="fas fa-info-circle me-2"></i>Henüz aktivite bulunmuyor</div>';
        return;
    }

    activities.slice(0, 5).forEach((activity, index) => {
        const item = document.createElement('div');
        item.className = 'timeline-item animate__animated animate__fadeInLeft';
        item.style.animationDelay = `${index * 0.2}s`;

        let activityIcon, activityText, activityColor;

        switch(activity.type) {
            case 'deposit':
                activityIcon = 'fas fa-arrow-down';
                activityText = `<strong>${formatMoney(activity.amount)} TL</strong> yatırıldı`;
                activityColor = 'success';
                break;
            case 'withdrawal':
                activityIcon = 'fas fa-arrow-up';
                activityText = `<strong>${formatMoney(activity.amount)} TL</strong> çekildi`;
                activityColor = 'danger';
                break;
            case 'transfer':
                activityIcon = 'fas fa-exchange-alt';
                activityText = `<strong>${formatMoney(activity.amount)} TL</strong> transfer`;
                activityColor = 'warning';
                break;
            default:
                activityIcon = 'fas fa-info';
                activityText = 'Bilinmeyen işlem';
                activityColor = 'secondary';
        }

        item.innerHTML = `
            <div class="d-flex align-items-center">
                <div class="bg-${activityColor} rounded-circle p-2 me-3">
                    <i class="${activityIcon} text-white" style="font-size: 0.8rem;"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="fw-bold">${activity.account}</div>
                    <div class="small text-muted">${activityText}</div>
                    <div class="small text-muted">${formatDateTime(activity.date)}</div>
                </div>
            </div>
        `;

        container.appendChild(item);
    });
}
function showAccountDetail(accountId) {
    const account = currentAccounts.find(a => a.id == accountId);
    if (!account) return;

    // Basic Info
    const basicInfo = document.getElementById('account-basic-info');
    const balanceClass = account.balance >= 0 ? 'text-success' : 'text-danger';
    const statusBadge = account.active ?
        '<span class="badge bg-success"><i class="fas fa-check me-1"></i>Aktif</span>' :
        '<span class="badge bg-danger"><i class="fas fa-times me-1"></i>Pasif</span>';

    basicInfo.innerHTML = `
        <div class="text-center mb-4">
            <div class="bg-primary bg-gradient rounded-circle p-4 d-inline-block mb-3">
                <i class="fas ${account.type === 'bank' ? 'fa-university' : 'fa-cash-register'} text-white fa-2x"></i>
            </div>
            <h5 class="fw-bold">${escapeHtml(account.name)}</h5>
            <div class="mb-2">${statusBadge}</div>
            <div class="${balanceClass} fw-bold fs-4">${formatMoney(account.balance)} ${account.currency}</div>
        </div>

        <div class="border-top pt-3">
            <dl class="row mb-0">
                <dt class="col-6 small">Hesap Tipi:</dt>
                <dd class="col-6 small">${account.type === 'bank' ? 'Banka Hesabı' : 'Kasa Hesabı'}</dd>

                ${account.bank ? `
                    <dt class="col-6 small">Banka:</dt>
                    <dd class="col-6 small">${escapeHtml(account.bank)}</dd>
                ` : ''}

                ${account.branch ? `
                    <dt class="col-6 small">Şube:</dt>
                    <dd class="col-6 small">${escapeHtml(account.branch)}</dd>
                ` : ''}

                ${account.account_no ? `
                    <dt class="col-6 small">Hesap No:</dt>
                    <dd class="col-6 small">${account.account_no}</dd>
                ` : ''}

                ${account.iban ? `
                    <dt class="col-6 small">IBAN:</dt>
                    <dd class="col-6 small text-break">${account.iban}</dd>
                ` : ''}

                ${account.responsible ? `
                    <dt class="col-6 small">Sorumlu:</dt>
                    <dd class="col-6 small">${escapeHtml(account.responsible)}</dd>
                ` : ''}

                <dt class="col-6 small">Açılış Tarihi:</dt>
                <dd class="col-6 small">${formatDate(account.opening_date)}</dd>

                <dt class="col-6 small">Son İşlem:</dt>
                <dd class="col-6 small">${formatDate(account.last_transaction)}</dd>
            </dl>
        </div>
    `;

    // Load transactions
    loadAccountTransactions(accountId);

    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('modal-account-detail'));
    modal.show();
}

function loadAccountTransactions(accountId) {
    // Demo transaction data
    const demoTransactions = [
        {date: '2024-10-30', description: 'Müşteri Ödemesi', amount: 50000, type: 'credit', balance: 2875420.50},
        {date: '2024-10-29', description: 'Kira Ödemesi', amount: -25000, type: 'debit', balance: 2825420.50},
        {date: '2024-10-28', description: 'Satış Geliri', amount: 75000, type: 'credit', balance: 2850420.50},
        {date: '2024-10-27', description: 'Tedarikçi Ödemesi', amount: -30000, type: 'debit', balance: 2775420.50},
        {date: '2024-10-26', description: 'Banka Faizi', amount: 1250, type: 'credit', balance: 2805420.50}
    ];

    const container = document.getElementById('account-transactions');
    let tableHTML = `
        <table class="table table-sm">
            <thead>
                <tr>
                    <th>Tarih</th>
                    <th>Açıklama</th>
                    <th class="text-end">Tutar</th>
                    <th class="text-end">Bakiye</th>
                </tr>
            </thead>
            <tbody>
    `;

    demoTransactions.forEach(tx => {
        const amountClass = tx.amount >= 0 ? 'text-success' : 'text-danger';
        const amountIcon = tx.amount >= 0 ? '+' : '';

        tableHTML += `
            <tr>
                <td>${formatDate(tx.date)}</td>
                <td>${escapeHtml(tx.description)}</td>
                <td class="text-end ${amountClass} fw-bold">
                    ${amountIcon}${formatMoney(Math.abs(tx.amount))} TL
                </td>
                <td class="text-end">${formatMoney(tx.balance)} TL</td>
            </tr>
        `;
    });

    tableHTML += '</tbody></table>';
    container.innerHTML = tableHTML;
}

}

// Event Listeners Setup
function setupEventListeners() {
    // Account type change
    document.getElementById('new-account-type').addEventListener('change', function() {
        const bankFields = document.getElementById('bank-fields');
        const cashboxFields = document.getElementById('cashbox-fields');

        if (this.value === 'bank') {
            bankFields.style.display = 'block';
            cashboxFields.style.display = 'none';
        } else if (this.value === 'cashbox') {
            bankFields.style.display = 'none';
            cashboxFields.style.display = 'block';
        } else {
            bankFields.style.display = 'none';
            cashboxFields.style.display = 'none';
        }
    });

    // New account form submission
    document.getElementById('form-new-account').addEventListener('submit', function(e) {
        e.preventDefault();

        const form = e.target;
        const formData = new FormData(form);

        // Validation
        if (!validateAccountForm(formData)) {
            return;
        }

        // Show loading
        Swal.fire({
            title: 'Hesap Oluşturuluyor...',
            text: 'Lütfen bekleyin',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Submit
        fetch('{{ route("finance.cash.accounts.store") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        }).then(response => {
            if (response.ok) {
                Swal.fire({
                    icon: 'success',
                    title: 'Başarılı!',
                    text: 'Hesap başarıyla oluşturuldu',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            } else {
                throw new Error('Kaydetme hatası');
            }
        }).catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Hata!',
                text: 'Hesap oluşturulurken bir hata oluştu',
                confirmButtonText: 'Tamam'
            });
        });
    });

    // Select all checkbox
    document.getElementById('select-all').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.account-select');
        checkboxes.forEach(cb => cb.checked = this.checked);
    });

    // Filter events
    document.getElementById('search').addEventListener('input', debounce(applyFilters, 300));
}

function attachTableEventListeners() {
    // Account detail links
    document.querySelectorAll('.account-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const accountId = this.getAttribute('data-account-id');
            showAccountDetail(accountId);
        });
    });
}

// Quick Actions
function quickTransfer() {
    populateAccountSelects();
    const modal = new bootstrap.Modal(document.getElementById('modal-quick-transfer'));
    modal.show();
}

function quickDeposit() {
    Swal.fire({
        title: 'Para Yatır',
        html: `
            <select id="deposit-account" class="form-select mb-3">
                <option value="">Hesap seçin</option>
                ${currentAccounts.map(a => `<option value="${a.id}">${a.name}</option>`).join('')}
            </select>
            <input id="deposit-amount" type="number" class="form-control mb-3" placeholder="Tutar" step="0.01">
            <input id="deposit-description" class="form-control" placeholder="Açıklama">
        `,
        showCancelButton: true,
        confirmButtonText: 'Para Yatır',
        cancelButtonText: 'İptal',
        preConfirm: () => {
            const account = document.getElementById('deposit-account').value;
            const amount = document.getElementById('deposit-amount').value;
            const description = document.getElementById('deposit-description').value;

            if (!account || !amount) {
                Swal.showValidationMessage('Lütfen tüm alanları doldurun');
                return false;
            }
            return { account, amount, description };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            processDeposit(result.value);
        }
    });
}

function quickWithdraw() {
    Swal.fire({
        title: 'Para Çek',
        html: `
            <select id="withdraw-account" class="form-select mb-3">
                <option value="">Hesap seçin</option>
                ${currentAccounts.map(a => `<option value="${a.id}">${a.name} (${formatMoney(a.balance)} ${a.currency})</option>`).join('')}
            </select>
            <input id="withdraw-amount" type="number" class="form-control mb-3" placeholder="Tutar" step="0.01">
            <input id="withdraw-description" class="form-control" placeholder="Açıklama">
        `,
        showCancelButton: true,
        confirmButtonText: 'Para Çek',
        cancelButtonText: 'İptal',
        preConfirm: () => {
            const account = document.getElementById('withdraw-account').value;
            const amount = document.getElementById('withdraw-amount').value;
            const description = document.getElementById('withdraw-description').value;

            if (!account || !amount) {
                Swal.showValidationMessage('Lütfen tüm alanları doldurun');
                return false;
            }
            return { account, amount, description };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            processWithdraw(result.value);
        }
    });
}

// View Toggle
function toggleView(viewType) {
    currentView = viewType;

    const gridContainer = document.getElementById('accounts-grid');
    const tableContainer = document.getElementById('accounts-table-container');

    if (viewType === 'grid') {
        gridContainer.style.display = 'block';
        tableContainer.style.display = 'none';
        renderGridView(currentAccounts);
    } else {
        gridContainer.style.display = 'none';
        tableContainer.style.display = 'block';
        renderTableView(currentAccounts);
    }
}

// Filter Functions
function applyFilters() {
    const search = document.getElementById('search').value.toLowerCase();
    const type = document.getElementById('filter-type').value;
    const currency = document.getElementById('filter-currency').value;
    const status = document.getElementById('filter-status').value;
    const minBalance = parseFloat(document.getElementById('min-balance').value) || 0;
    const maxBalance = parseFloat(document.getElementById('max-balance').value) || Infinity;

    const filteredAccounts = currentAccounts.filter(account => {
        const matchesSearch = !search ||
            account.name.toLowerCase().includes(search) ||
            (account.bank && account.bank.toLowerCase().includes(search)) ||
            (account.iban && account.iban.toLowerCase().includes(search));

        const matchesType = !type || (
            (type === 'cashbox' && (account.type === 'cashbox' || account.type === 'cash')) ||
            (type !== 'cashbox' && account.type === type)
        );
        const matchesCurrency = !currency || account.currency === currency;
        const matchesStatus = !status ||
            (status === 'active' && account.active) ||
            (status === 'inactive' && !account.active);
        const matchesBalance = account.balance >= minBalance && account.balance <= maxBalance;

        return matchesSearch && matchesType && matchesCurrency && matchesStatus && matchesBalance;
    });

    renderAccounts(filteredAccounts);
}

function clearFilters() {
    document.getElementById('search').value = '';
    document.getElementById('filter-type').value = '';
    document.getElementById('filter-currency').value = '';
    document.getElementById('filter-status').value = '';
    document.getElementById('min-balance').value = '';
    document.getElementById('max-balance').value = '';
    renderAccounts(currentAccounts);
}

// Utility Functions
function validateAccountForm(formData) {
    const name = formData.get('name')?.trim();
    const type = formData.get('type');
    const iban = formData.get('iban')?.trim();

    if (!name) {
        Swal.fire('Hata', 'Hesap adı zorunludur', 'error');
        return false;
    }

    if (!type) {
        Swal.fire('Hata', 'Hesap tipi seçmelisiniz', 'error');
        return false;
    }

    if (iban && !validateIBAN(iban)) {
        Swal.fire({
            title: 'IBAN Formatı',
            text: 'Girilen IBAN formatı geçersiz görünüyor. Devam etmek istediğinizden emin misiniz?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Devam Et',
            cancelButtonText: 'İptal'
        }).then((result) => {
            return result.isConfirmed;
        });
    }

    return true;
}

function validateIBAN(iban) {
    const cleanIban = iban.replace(/\s/g, '');
    return /^TR\d{2}\d{4}\d{4}\d{4}\d{4}\d{2}$/.test(cleanIban);
}

function formatMoney(value) {
    try {
        return Number(value).toLocaleString('tr-TR', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    } catch (e) {
        return value;
    }
}

function formatDate(dateString) {
    if (!dateString) return '-';
    try {
        return new Date(dateString).toLocaleDateString('tr-TR');
    } catch (e) {
        return dateString;
    }
}

function formatDateTime(dateString) {
    if (!dateString) return '-';
    try {
        return new Date(dateString).toLocaleString('tr-TR');
    } catch (e) {
        return dateString;
    }
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text || '';
    return div.innerHTML;
}

function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

function showLoadingSpinner() {
    // Implementation for loading spinner
}

function hideLoadingSpinner() {
    // Implementation for hiding loading spinner
}

function updateShowingInfo(count) {
    document.getElementById('showing-info').textContent = `Gösterilen: 1-${count} / ${count}`;
}

// Placeholder functions for missing implementations
function populateAccountSelects() {}
function processDeposit(data) {}
function processWithdraw(data) {}
function processTransfer() {}
function reconcileAccounts() {}
function generateReports() {}
function exportData() {}
function editAccount(id) {}
function quickTransaction(id) {}
function bulkActivate() {}
function bulkDeactivate() {}
function exportSelected() {}
function showAllTransactions() {}

</script>
@endsection
