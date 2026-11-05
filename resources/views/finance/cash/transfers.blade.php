@extends('layouts.master')
@section('title', 'Para Transferleri')
@section('topbar-title', 'Finans - Kasa & Banka')

@push('styles')
<style>
    .transfer-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
        background: white;
        margin-bottom: 1.5rem;
        position: relative;
    }
    
    .transfer-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, #667eea, #764ba2, #f093fb);
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .transfer-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 40px rgba(0,0,0,0.15);
    }
    
    .transfer-card:hover::before {
        opacity: 1;
    }
    
    .transfer-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2.5rem 0;
        position: relative;
        overflow: hidden;
        margin-bottom: 2rem;
        border-radius: 0 0 20px 20px;
    }
    
    .transfer-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%);
        animation: float 8s ease-in-out infinite;
    }
    
    .transfer-header::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(5deg); }
    }
    
    .quick-action-card {
        border: none;
        border-radius: 14px;
        background: white;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        height: 100%;
        position: relative;
        overflow: hidden;
    }
    
    .quick-action-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(102, 126, 234, 0.1), transparent);
        transition: left 0.5s ease;
    }
    
    .quick-action-card:hover {
        transform: translateY(-6px) scale(1.02);
        box-shadow: 0 12px 30px rgba(0,0,0,0.15);
        border: 2px solid rgba(102, 126, 234, 0.3);
    }
    
    .quick-action-card:hover::before {
        left: 100%;
    }
    
    .quick-action-icon {
        width: 65px;
        height: 65px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
        margin-bottom: 1rem;
        color: white;
        transition: all 0.3s ease;
        position: relative;
        z-index: 2;
    }
    
    .quick-action-card:hover .quick-action-icon {
        transform: rotateY(360deg) scale(1.1);
        box-shadow: 0 8px 20px rgba(0,0,0,0.2);
    }
    
    .action-transfer { background: linear-gradient(135deg, #667eea, #764ba2); }
    .action-eft { background: linear-gradient(135deg, #f093fb, #f5576c); }
    .action-bulk { background: linear-gradient(135deg, #4facfe, #00f2fe); }
    .action-scheduled { background: linear-gradient(135deg, #a8edea, #fed6e3); }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }
    
    .stat-card {
        background: white;
        border-radius: 14px;
        padding: 1.8rem;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        border-left: 4px solid transparent;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 60px;
        height: 60px;
        background: radial-gradient(circle, rgba(102, 126, 234, 0.1), transparent);
        border-radius: 50%;
        transform: translate(20px, -20px);
        transition: all 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.12);
    }
    
    .stat-card:hover::before {
        transform: translate(15px, -15px) scale(1.2);
        opacity: 0.7;
    }
    
    .stat-card.today { border-left-color: #667eea; }
    .stat-card.week { border-left-color: #f093fb; }
    .stat-card.month { border-left-color: #4facfe; }
    .stat-card.pending { border-left-color: #f5576c; }
    
    .stat-value {
        font-size: 1.8rem;
        font-weight: 700;
        color: #667eea;
        margin-bottom: 0.5rem;
    }
    
    .stat-label {
        color: #6c757d;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .transfer-table {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
    }
    
    .table thead th {
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        border: none;
        font-weight: 700;
        color: #2d3436;
        padding: 1.2rem 1rem;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
        position: relative;
    }
    
    .table thead th::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: linear-gradient(90deg, transparent, #667eea, transparent);
        opacity: 0.6;
    }
    
    .table tbody td {
        border: none;
        padding: 1rem;
        vertical-align: middle;
    }
    
    .table tbody tr {
        border-bottom: 1px solid #f1f3f4;
        transition: all 0.3s ease;
    }
    
    .table tbody tr:hover {
        background: linear-gradient(90deg, #f8f9fa, #f1f8ff, #f8f9fa);
        transform: scale(1.005);
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    
    .status-badge {
        border-radius: 25px;
        padding: 0.5rem 1rem;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .status-badge::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
        transition: left 0.5s ease;
    }
    
    .status-badge:hover::before {
        left: 100%;
    }
    
    .status-completed { 
        background: linear-gradient(135deg, #e8f5e8, #d4f4dd); 
        color: #2d5a2d;
        border: 1px solid #a8e6a3;
    }
    .status-pending { 
        background: linear-gradient(135deg, #fff4e6, #ffe8cc); 
        color: #8b5a00;
        border: 1px solid #ffb84d;
    }
    .status-failed { 
        background: linear-gradient(135deg, #ffe6e6, #ffcccc); 
        color: #8b1538;
        border: 1px solid #ff6b9d;
    }
    
    .transfer-type-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.4rem 1rem;
        border-radius: 18px;
        font-size: 0.8rem;
        font-weight: 600;
        position: relative;
        overflow: hidden;
        box-shadow: 0 2px 6px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
    }
    
    .transfer-type-badge::before {
        content: '';
        position: absolute;
        top: 0;
        left: -2px;
        width: 4px;
        height: 100%;
        transition: all 0.3s ease;
    }
    
    .type-transfer { 
        background: linear-gradient(135deg, #f0f4ff, #e6efff); 
        color: #667eea;
        border: 1px solid #b8c5f2;
    }
    .type-transfer::before { background: #667eea; }
    
    .type-eft { 
        background: linear-gradient(135deg, #fef0ff, #fce4ff); 
        color: #f093fb;
        border: 1px solid #f8c8ff;
    }
    .type-eft::before { background: #f093fb; }
    
    .type-havale { 
        background: linear-gradient(135deg, #e6faff, #ccf7ff); 
        color: #4facfe;
        border: 1px solid #99e9ff;
    }
    .type-havale::before { background: #4facfe; }
    
    .transfer-type-badge:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    
    .transfer-type-badge:hover::before {
        width: 6px;
        left: -3px;
    }
    
    .pending-item {
        background: white;
        border-radius: 14px;
        border: 2px solid #f1f3f4;
        margin-bottom: 1rem;
        padding: 1.2rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    
    .pending-item::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 3px;
        background: linear-gradient(90deg, #667eea, #f093fb);
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .pending-item:hover {
        border-color: rgba(102, 126, 234, 0.3);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.15);
        transform: translateY(-2px);
    }
    
    .pending-item:hover::before {
        opacity: 1;
    }
    
    .amount-display {
        font-size: 1.15rem;
        font-weight: 700;
        color: #667eea;
        background: linear-gradient(135deg, #667eea, #764ba2);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        position: relative;
    }
    
    .modal-content {
        border: none;
        border-radius: 15px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.2);
    }
    
    .modal-header {
        background: linear-gradient(135deg, #38c66c 0%, #2e7d32 100%);
        color: white;
        border-radius: 15px 15px 0 0;
    }
    
    .form-control, .form-select {
        border-radius: 10px;
        border: 2px solid #e8ecef;
        padding: 0.8rem 1.2rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        background: #fafbfc;
        font-size: 0.95rem;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.15);
        background: white;
        transform: translateY(-1px);
    }
    
    .form-control:hover:not(:focus), .form-select:hover:not(:focus) {
        border-color: #b8c6db;
        background: white;
    }
    
    .btn-transfer {
        background: linear-gradient(135deg, #667eea, #764ba2);
        border: none;
        color: white;
        padding: 0.75rem 2rem;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    
    .btn-transfer::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s ease;
    }
    
    .btn-transfer:hover {
        transform: translateY(-3px) scale(1.05);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        color: white;
        background: linear-gradient(135deg, #764ba2, #667eea);
    }
    
    .btn-transfer:hover::before {
        left: 100%;
    }
    
    .btn-transfer:active {
        transform: translateY(-1px) scale(1.02);
    }
    
    .filter-tabs {
        background: white;
        border-radius: 14px;
        padding: 0.6rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        margin-bottom: 1.5rem;
        display: flex;
        gap: 0.3rem;
    }
    
    .filter-tab {
        border: none;
        background: transparent;
        padding: 0.9rem 1.8rem;
        border-radius: 10px;
        font-weight: 500;
        color: #6c757d;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        flex: 1;
        text-align: center;
    }
    
    .filter-tab::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        transition: left 0.5s ease;
    }
    
    .filter-tab:hover:not(.active) {
        background: rgba(102, 126, 234, 0.1);
        color: #667eea;
        transform: translateY(-1px);
    }
    
    .filter-tab.active {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        transform: translateY(-2px);
    }
    
    .filter-tab.active::before {
        left: 100%;
    }
    
    /* Header Button Styles */
    .btn-light:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.15) !important;
        background: #ffffff !important;
    }
    
    .btn-outline-light:hover {
        transform: translateY(-2px);
        background: rgba(255,255,255,0.2) !important;
        border-color: rgba(255,255,255,0.6) !important;
        box-shadow: 0 4px 15px rgba(255,255,255,0.2);
    }
    
    /* Modal Improvements */
    .modal-content {
        border: none;
        border-radius: 18px;
        box-shadow: 0 15px 50px rgba(0,0,0,0.2);
        backdrop-filter: blur(10px);
    }
    
    .modal-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 18px 18px 0 0;
        padding: 1.5rem 2rem;
    }
    
    .modal-footer {
        padding: 1.5rem 2rem;
        border-top: 1px solid #f1f3f4;
    }
    
    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 0.8rem;
        }
        
        .quick-action-card {
            text-align: center;
        }
        
        .transfer-header {
            padding: 1.5rem 0;
            text-align: center;
        }
        
        .filter-tabs {
            flex-wrap: wrap;
        }
        
        .filter-tab {
            min-width: calc(50% - 0.3rem);
            margin-bottom: 0.3rem;
        }
    }
</style>
@endpush

@section('content')
    <!-- Page Header -->
    <div class="transfer-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="mb-2"><i class="fas fa-exchange-alt me-3"></i>Para Transferleri</h1>
                    <p class="mb-0 opacity-75">Hesaplar arası transfer, EFT ve havale işlemlerini yönetin</p>
                </div>
                <div class="col-md-4 text-end">
                    <div class="d-flex justify-content-end gap-3">
                        <button class="btn btn-light btn-lg px-4 py-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#modal-virman" style="border-radius: 12px; font-weight: 600; transition: all 0.3s ease;">
                            <i class="fas fa-plus me-2"></i>Yeni Transfer
                        </button>
                        <button class="btn btn-outline-light px-4 py-2" onclick="exportTransfers()" style="border-radius: 12px; font-weight: 500; transition: all 0.3s ease; border: 2px solid rgba(255,255,255,0.3);">
                            <i class="fas fa-download me-2"></i>Dışa Aktar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="stats-grid">
        <div class="stat-card today">
            <div class="stat-value" id="stat-today">₺0</div>
            <div class="stat-label">Bugün Transfer</div>
        </div>
        <div class="stat-card week">
            <div class="stat-value" id="stat-week">₺0</div>
            <div class="stat-label">Bu Hafta</div>
        </div>
        <div class="stat-card month">
            <div class="stat-value" id="stat-month">₺0</div>
            <div class="stat-label">Bu Ay</div>
        </div>
        <div class="stat-card pending">
            <div class="stat-value" id="stat-pending">0</div>
            <div class="stat-label">Onay Bekleyen</div>
        </div>
    </div>

    <div class="row">
        <!-- Quick Actions -->
        <div class="col-lg-4">
            <div class="transfer-card">
                <div class="card-body p-4">
                    <h5 class="card-title mb-4">
                        <i class="fas fa-bolt me-2 text-primary"></i>Hızlı İşlemler
                    </h5>
                    
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="quick-action-card" onclick="openTransferModal()">
                                <div class="card-body text-center p-3">
                                    <div class="quick-action-icon action-transfer mx-auto">
                                        <i class="fas fa-exchange-alt"></i>
                                    </div>
                                    <h6 class="mb-1">Hesap Transferi</h6>
                                    <small class="text-muted">Hesaplar arası virman</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-6">
                            <div class="quick-action-card" onclick="openEftModal()">
                                <div class="card-body text-center p-3">
                                    <div class="quick-action-icon action-eft mx-auto">
                                        <i class="fas fa-university"></i>
                                    </div>
                                    <h6 class="mb-1">EFT/Havale</h6>
                                    <small class="text-muted">Banka transferi</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-6">
                            <div class="quick-action-card" onclick="openBulkModal()">
                                <div class="card-body text-center p-3">
                                    <div class="quick-action-icon action-bulk mx-auto">
                                        <i class="fas fa-list"></i>
                                    </div>
                                    <h6 class="mb-1">Toplu Transfer</h6>
                                    <small class="text-muted">Çoklu işlem</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-6">
                            <div class="quick-action-card" onclick="openScheduledModal()">
                                <div class="card-body text-center p-3">
                                    <div class="quick-action-icon action-scheduled mx-auto">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                    <h6 class="mb-1">Zamanlanmış</h6>
                                    <small class="text-muted">Otomatik transfer</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Approvals -->
            <div class="transfer-card">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-clock me-2 text-warning"></i>Onay Bekleyenler
                        </h5>
                        <span class="badge bg-warning" id="pending-count">0</span>
                    </div>
                    
                    <div id="pending-list">
                        <div class="text-center py-4">
                            <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                            <p class="text-muted mt-2">Yükleniyor...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Transfers -->
        <div class="col-lg-8">
            <div class="transfer-card">
                <div class="card-body p-0">
                    <!-- Filter Tabs -->
                    <div class="p-4 pb-0">
                        <div class="filter-tabs">
                            <button class="filter-tab active" data-filter="all">Tümü</button>
                            <button class="filter-tab" data-filter="transfer">Virman</button>
                            <button class="filter-tab" data-filter="eft">EFT</button>
                            <button class="filter-tab" data-filter="pending">Bekleyen</button>
                        </div>
                    </div>
                    
                    <div class="px-4 pb-3">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-history me-2 text-primary"></i>Son Transfer İşlemleri
                        </h5>
                    </div>
                    
                    <div class="transfer-table">
                        <table class="table mb-0" id="recent-transfers-table">
                            <thead>
                                <tr>
                                    <th>Tarih/Saat</th>
                                    <th>Fiş No</th>
                                    <th>Tür</th>
                                    <th>Kaynak → Hedef</th>
                                    <th>Tutar</th>
                                    <th>Durum</th>
                                    <th>İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                                        <p class="text-muted mt-2">Transfer işlemleri yükleniyor...</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Transfer Modal -->
    <div class="modal fade" id="modal-virman" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-exchange-alt me-2"></i>Hesaplar Arası Transfer
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="form-virman">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Kaynak Hesap <span class="text-danger">*</span></label>
                                    <select name="from_account" class="form-select" required>
                                        <option value="">Kaynak hesap seçin</option>
                                        <option value="1">Garanti BBVA TL - (₺1.250.000,00)</option>
                                        <option value="2">Akbank USD - ($50.000,00)</option>
                                        <option value="3">Ana Kasa - (₺75.000,00)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Hedef Hesap <span class="text-danger">*</span></label>
                                    <select name="to_account" class="form-select" required>
                                        <option value="">Hedef hesap seçin</option>
                                        <option value="4">İş Bankası EUR - (€42.300,25)</option>
                                        <option value="5">Yedek Kasa - (₺15.000,00)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Transfer Tutarı <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">₺</span>
                                        <input name="amount" type="number" step="0.01" class="form-control" required placeholder="0,00">
                                    </div>
                                    <div class="form-text">Kullanılabilir bakiye gösterilecek</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">İşlem Tarihi</label>
                                    <input name="date" type="datetime-local" class="form-control" value="{{ date('Y-m-d\TH:i') }}" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">İşlem Açıklaması</label>
                            <textarea name="description" class="form-control" rows="3" placeholder="Transfer açıklaması ve notları..."></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="requires_approval" id="requires_approval_transfer">
                                    <label class="form-check-label" for="requires_approval_transfer">
                                        Onay gerektir
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="send_notification" id="send_notification" checked>
                                    <label class="form-check-label" for="send_notification">
                                        Bildirim gönder
                                    </label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>İptal
                    </button>
                    <button id="save-virman" type="button" class="btn btn-transfer">
                        <i class="fas fa-paper-plane me-2"></i>Transfer Yap
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- EFT/Havale Modal -->
    <div class="modal fade" id="modal-eft" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-university me-2"></i>Yeni EFT / Havale
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="form-eft">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Kaynak Hesap <span class="text-danger">*</span></label>
                                    <select name="from_account" class="form-select" required>
                                        <option value="">Kaynak hesap seçin</option>
                                        <option value="1">Garanti BBVA TL - (₺1.250.000,00)</option>
                                        <option value="2">Akbank USD - ($50.000,00)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Alıcı Adı/Unvanı <span class="text-danger">*</span></label>
                                    <input name="beneficiary_name" class="form-control" required placeholder="Alıcının tam adı">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Alıcı Türü</label>
                                    <select name="beneficiary_type" class="form-select">
                                        <option value="individual">Bireysel</option>
                                        <option value="corporate">Kurumsal</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">IBAN <span class="text-danger">*</span></label>
                                    <input name="iban" class="form-control" required placeholder="TR00 0000 0000 0000 0000 0000 00" maxlength="32">
                                    <div class="form-text">IBAN numarasını boşluksuz giriniz</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Banka</label>
                                    <input name="bank" class="form-control" placeholder="Banka adı">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Transfer Tutarı <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">₺</span>
                                        <input name="amount" type="number" step="0.01" class="form-control" required placeholder="0,00">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">İşlem Tarihi</label>
                                    <input name="date" type="datetime-local" class="form-control" value="{{ date('Y-m-d\TH:i') }}" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Açıklama</label>
                            <textarea name="description" class="form-control" rows="3" placeholder="Havale açıklaması..."></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="requires_approval_eft" name="requires_approval">
                                    <label class="form-check-label" for="requires_approval_eft">
                                        Onay gerektir
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="urgent" id="urgent">
                                    <label class="form-check-label" for="urgent">
                                        Acil transfer
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="send_sms" id="send_sms">
                                    <label class="form-check-label" for="send_sms">
                                        SMS bilgilendirme
                                    </label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>İptal
                    </button>
                    <button id="save-eft" type="button" class="btn btn-transfer">
                        <i class="fas fa-paper-plane me-2"></i>Havale Gönder
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Transfer Management System
class TransferManager {
    constructor() {
        this.currentFilter = 'all';
        this.transfers = [];
        this.pendingTransfers = [];
        
        this.init();
    }
    
    init() {
        this.bindEvents();
        this.loadData();
        this.startAutoRefresh();
    }
    
    bindEvents() {
        // Filter tabs
        document.querySelectorAll('.filter-tab').forEach(tab => {
            tab.addEventListener('click', (e) => {
                document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
                e.target.classList.add('active');
                this.currentFilter = e.target.dataset.filter;
                this.renderTransfers();
            });
        });
        
        // Modal events
        document.getElementById('save-virman').addEventListener('click', () => this.saveTransfer());
        document.getElementById('save-eft').addEventListener('click', () => this.saveEft());
        
        // IBAN formatting
        const ibanInput = document.querySelector('input[name="iban"]');
        if (ibanInput) {
            ibanInput.addEventListener('input', this.formatIban);
        }
    }
    
    async loadData() {
        await Promise.all([
            this.loadPendingTransfers(),
            this.loadRecentTransfers(),
            this.loadStatistics()
        ]);
    }
    
    async loadPendingTransfers() {
        try {
            const response = await fetch('{{ url('finance/cash/transfers/pending') }}');
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            
            if (data.ok) {
                this.pendingTransfers = data.data || [];
                this.renderPendingTransfers();
                this.updatePendingCount();
            } else {
                throw new Error(data.message || 'Bekleyen transferler yüklenemedi');
            }
        } catch (error) {
            console.error('Error loading pending transfers:', error);
            this.showPendingLoadError();
        }
    }
    
    async loadRecentTransfers() {
        try {
            const response = await fetch('{{ url('finance/cash/transfers/data') }}?per_page=50');
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            
            if (data.ok) {
                this.transfers = data.data || [];
                this.renderTransfers();
            } else {
                throw new Error(data.message || 'Veri yükleme hatası');
            }
        } catch (error) {
            console.error('Error loading transfers:', error);
            this.showTransferLoadError();
        }
    }
    
    async loadStatistics() {
        try {
            // Calculate statistics from loaded transfers
            const today = new Date().toDateString();
            const oneWeekAgo = new Date(Date.now() - 7 * 24 * 60 * 60 * 1000);
            const oneMonthAgo = new Date(Date.now() - 30 * 24 * 60 * 60 * 1000);
            
            const todayTransfers = this.transfers.filter(t => new Date(t.date).toDateString() === today);
            const weekTransfers = this.transfers.filter(t => new Date(t.date) >= oneWeekAgo);
            const monthTransfers = this.transfers.filter(t => new Date(t.date) >= oneMonthAgo);
            
            const stats = {
                today: todayTransfers.reduce((sum, t) => sum + (t.amount || 0), 0),
                week: weekTransfers.reduce((sum, t) => sum + (t.amount || 0), 0),
                month: monthTransfers.reduce((sum, t) => sum + (t.amount || 0), 0),
                pending: this.pendingTransfers.length
            };
            
            // Animate counters
            this.animateCounter('stat-today', stats.today);
            this.animateCounter('stat-week', stats.week);
            this.animateCounter('stat-month', stats.month);
            this.animateCounter('stat-pending', stats.pending, false);
            
        } catch (error) {
            console.error('Error calculating statistics:', error);
            // Set default values on error
            document.getElementById('stat-today').textContent = '₺0';
            document.getElementById('stat-week').textContent = '₺0';
            document.getElementById('stat-month').textContent = '₺0';
            document.getElementById('stat-pending').textContent = '0';
        }
    }
    
    animateCounter(elementId, targetValue, isMoney = true) {
        const element = document.getElementById(elementId);
        const startValue = 0;
        const duration = 2000; // 2 seconds
        const startTime = performance.now();
        
        const updateCounter = (currentTime) => {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            
            const easeOutQuart = 1 - Math.pow(1 - progress, 4);
            const currentValue = startValue + (targetValue - startValue) * easeOutQuart;
            
            if (isMoney) {
                element.textContent = this.formatMoney(currentValue);
            } else {
                element.textContent = Math.round(currentValue);
            }
            
            if (progress < 1) {
                requestAnimationFrame(updateCounter);
            } else {
                if (isMoney) {
                    element.textContent = this.formatMoney(targetValue);
                } else {
                    element.textContent = targetValue;
                }
            }
        };
        
        requestAnimationFrame(updateCounter);
    }
    
    renderPendingTransfers() {
        const container = document.getElementById('pending-list');
        
        if (this.pendingTransfers.length === 0) {
            container.innerHTML = `
                <div class="text-center py-4">
                    <i class="fas fa-check-circle text-success fa-2x mb-2"></i>
                    <p class="text-muted">Onay bekleyen transfer bulunmuyor</p>
                </div>
            `;
            return;
        }
        
        container.innerHTML = this.pendingTransfers.map(transfer => `
            <div class="pending-item" data-id="${transfer.id}">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <div class="fw-bold text-dark mb-1">
                            ${this.escapeHtml(transfer.beneficiary_name || transfer.to_account || 'N/A')}
                        </div>
                        <div class="small text-muted mb-2">
                            <i class="fas fa-calendar me-1"></i>${this.formatDate(transfer.date)}
                            <span class="mx-2">•</span>
                            <i class="fas fa-tag me-1"></i>${transfer.type === 'transfer' ? 'Virman' : 'EFT/Havale'}
                        </div>
                        <div class="amount-display">
                            ${this.formatMoney(transfer.amount)}
                        </div>
                    </div>
                    <div class="ms-3">
                        <div class="btn-group-vertical">
                            <button class="btn btn-success btn-sm" onclick="transferManager.approveTransfer(${transfer.id})">
                                <i class="fas fa-check me-1"></i>Onayla
                            </button>
                            <button class="btn btn-outline-danger btn-sm" onclick="transferManager.rejectTransfer(${transfer.id})">
                                <i class="fas fa-times me-1"></i>Reddet
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `).join('');
    }
    
    renderTransfers() {
        const tbody = document.querySelector('#recent-transfers-table tbody');
        let filteredTransfers = this.transfers;
        
        if (this.currentFilter !== 'all') {
            filteredTransfers = this.transfers.filter(t => {
                if (this.currentFilter === 'pending') return t.status === 'pending';
                return t.type === this.currentFilter;
            });
        }
        
        if (filteredTransfers.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                        <p class="text-muted">Gösterilecek transfer kaydı bulunamadı</p>
                    </td>
                </tr>
            `;
            return;
        }
        
        tbody.innerHTML = filteredTransfers.map(transfer => `
            <tr data-id="${transfer.id}">
                <td>
                    <div class="fw-bold">${this.formatDate(transfer.date)}</div>
                    <small class="text-muted">${this.formatTime(transfer.date)}</small>
                </td>
                <td>
                    <code class="bg-light px-2 py-1 rounded">#${transfer.id}</code>
                </td>
                <td>
                    <span class="transfer-type-badge ${this.getTypeBadgeClass(transfer.type)}">
                        <i class="fas ${this.getTypeIcon(transfer.type)} me-1"></i>
                        ${this.getTypeLabel(transfer.type)}
                    </span>
                </td>
                <td>
                    <div class="small">
                        <div class="fw-bold">${this.escapeHtml(transfer.from_account || 'N/A')}</div>
                        <i class="fas fa-arrow-down text-muted mx-1"></i>
                        <div class="text-muted">${this.escapeHtml(transfer.to_account || transfer.beneficiary_name || 'N/A')}</div>
                    </div>
                </td>
                <td>
                    <div class="amount-display">${this.formatMoney(transfer.amount)}</div>
                </td>
                <td>
                    <span class="status-badge ${this.getStatusBadgeClass(transfer.status)}">
                        ${this.getStatusLabel(transfer.status)}
                    </span>
                </td>
                <td>
                    <div class="btn-group">
                        <button class="btn btn-outline-primary btn-sm" onclick="transferManager.viewTransfer(${transfer.id})" title="Detay">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn btn-outline-secondary btn-sm" onclick="transferManager.printTransfer(${transfer.id})" title="Yazdır">
                            <i class="fas fa-print"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `).join('');
    }
    
    async saveTransfer() {
        const form = document.getElementById('form-virman');
        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());
        
        // Add checkbox values
        data.requires_approval = document.getElementById('requires_approval_transfer').checked ? 1 : 0;
        data.send_notification = document.getElementById('send_notification').checked ? 1 : 0;
        
        try {
            const response = await fetch('{{ route('finance.cash.transfers.transfer') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            });
            
            const result = await response.json();
            
            if (result.ok || response.ok) {
                this.showSuccess('Transfer işlemi başarıyla oluşturuldu');
                bootstrap.Modal.getInstance(document.getElementById('modal-virman')).hide();
                form.reset();
                this.loadData();
            } else {
                this.showError(result.message || 'Transfer işlemi sırasında hata oluştu');
            }
        } catch (error) {
            console.error('Transfer error:', error);
            this.showError('Transfer işlemi sırasında bir hata oluştu');
        }
    }
    
    async saveEft() {
        const form = document.getElementById('form-eft');
        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());
        
        // Add checkbox values
        data.requires_approval = document.getElementById('requires_approval_eft').checked ? 1 : 0;
        data.urgent = document.getElementById('urgent').checked ? 1 : 0;
        data.send_sms = document.getElementById('send_sms').checked ? 1 : 0;
        
        try {
            const response = await fetch('{{ route('finance.cash.transfers.eft') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            });
            
            const result = await response.json();
            
            if (result.ok || response.ok) {
                this.showSuccess('EFT/Havale işlemi başarıyla oluşturuldu');
                bootstrap.Modal.getInstance(document.getElementById('modal-eft')).hide();
                form.reset();
                this.loadData();
            } else {
                this.showError(result.message || 'EFT/Havale işlemi sırasında hata oluştu');
            }
        } catch (error) {
            console.error('EFT error:', error);
            this.showError('EFT/Havale işlemi sırasında bir hata oluştu');
        }
    }
    
    async approveTransfer(id) {
        const result = await Swal.fire({
            title: 'Transfer Onayı',
            text: 'Bu transfer işlemini onaylamak istediğinizden emin misiniz?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#38c66c',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Evet, Onayla',
            cancelButtonText: 'İptal'
        });
        
        if (result.isConfirmed) {
            try {
                const response = await fetch(`{{ url('finance/cash/transfers') }}/${id}/approve`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });
                
                const data = await response.json();
                
                if (data.ok || response.ok) {
                    this.showSuccess('Transfer işlemi onaylandı');
                    this.loadData();
                } else {
                    this.showError(data.message || 'Onay işlemi sırasında hata oluştu');
                }
            } catch (error) {
                console.error('Approval error:', error);
                this.showError('Onay işlemi sırasında bir hata oluştu');
            }
        }
    }
    
    async rejectTransfer(id) {
        const { value: reason } = await Swal.fire({
            title: 'Transfer Reddi',
            text: 'Bu transfer işlemini reddetmek istediğinizden emin misiniz?',
            input: 'textarea',
            inputPlaceholder: 'Red sebebini yazınız...',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Evet, Reddet',
            cancelButtonText: 'İptal',
            inputValidator: (value) => {
                if (!value) {
                    return 'Red sebebi belirtmeniz gerekiyor!'
                }
            }
        });
        
        if (reason) {
            // Handle rejection - implement API call
            this.showSuccess('Transfer işlemi reddedildi');
            this.loadData();
        }
    }
    
    viewTransfer(id) {
        // Implement transfer detail view
        console.log('View transfer:', id);
    }
    
    printTransfer(id) {
        // Implement transfer print
        console.log('Print transfer:', id);
    }
    
    updatePendingCount() {
        document.getElementById('pending-count').textContent = this.pendingTransfers.length;
    }
    
    startAutoRefresh() {
        // Refresh data every 30 seconds
        setInterval(() => {
            this.loadData();
        }, 30000);
    }
    
    // Utility functions
    formatMoney(amount) {
        return new Intl.NumberFormat('tr-TR', {
            style: 'currency',
            currency: 'TRY'
        }).format(amount || 0);
    }
    
    formatDate(dateString) {
        return new Date(dateString).toLocaleDateString('tr-TR');
    }
    
    formatTime(dateString) {
        return new Date(dateString).toLocaleTimeString('tr-TR', {
            hour: '2-digit',
            minute: '2-digit'
        });
    }
    
    formatIban(e) {
        let value = e.target.value.replace(/\s+/g, '').replace(/[^a-zA-Z0-9]/g, '');
        let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
        e.target.value = formattedValue.toUpperCase();
    }
    
    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    getTypeBadgeClass(type) {
        const classes = {
            'transfer': 'type-transfer',
            'eft': 'type-eft',
            'havale': 'type-havale'
        };
        return classes[type] || 'type-transfer';
    }
    
    getTypeIcon(type) {
        const icons = {
            'transfer': 'fa-exchange-alt',
            'eft': 'fa-university',
            'havale': 'fa-paper-plane'
        };
        return icons[type] || 'fa-exchange-alt';
    }
    
    getTypeLabel(type) {
        const labels = {
            'transfer': 'Virman',
            'eft': 'EFT',
            'havale': 'Havale'
        };
        return labels[type] || 'Transfer';
    }
    
    getStatusBadgeClass(status) {
        const classes = {
            'completed': 'status-completed',
            'pending': 'status-pending',
            'failed': 'status-failed'
        };
        return classes[status] || 'status-pending';
    }
    
    getStatusLabel(status) {
        const labels = {
            'completed': 'Tamamlandı',
            'pending': 'Bekliyor',
            'failed': 'Başarısız'
        };
        return labels[status] || 'Bekliyor';
    }
    
    showSuccess(message) {
        Swal.fire({
            icon: 'success',
            title: 'Başarılı!',
            text: message,
            timer: 3000,
            timerProgressBar: true,
            showConfirmButton: false
        });
    }
    
    showError(message) {
        Swal.fire({
            icon: 'error',
            title: 'Hata!',
            text: message,
            confirmButtonColor: '#38c66c'
        });
    }
    
    showTransferLoadError() {
        const tbody = document.querySelector('#recent-transfers-table tbody');
        tbody.innerHTML = `
            <tr>
                <td colspan="7" class="text-center py-5">
                    <i class="fas fa-exclamation-triangle fa-2x text-danger mb-2"></i>
                    <p class="text-danger mb-2">Transfer işlemleri yüklenirken hata oluştu</p>
                    <button class="btn btn-outline-primary btn-sm" onclick="transferManager.loadRecentTransfers()">
                        <i class="fas fa-refresh me-1"></i>Tekrar Dene
                    </button>
                </td>
            </tr>
        `;
    }
    
    showPendingLoadError() {
        const container = document.getElementById('pending-list');
        container.innerHTML = `
            <div class="text-center py-4">
                <i class="fas fa-exclamation-triangle fa-2x text-danger mb-2"></i>
                <p class="text-danger mb-2">Bekleyen transferler yüklenemedi</p>
                <button class="btn btn-outline-primary btn-sm" onclick="transferManager.loadPendingTransfers()">
                    <i class="fas fa-refresh me-1"></i>Tekrar Dene
                </button>
            </div>
        `;
    }
}

// Global functions for quick actions
function openTransferModal() {
    new bootstrap.Modal(document.getElementById('modal-virman')).show();
}

function openEftModal() {
    new bootstrap.Modal(document.getElementById('modal-eft')).show();
}

function openBulkModal() {
    Swal.fire({
        icon: 'info',
        title: 'Yakında',
        text: 'Toplu transfer özelliği yakında eklenecek.'
    });
}

function openScheduledModal() {
    Swal.fire({
        icon: 'info',
        title: 'Yakında',
        text: 'Zamanlanmış transfer özelliği yakında eklenecek.'
    });
}

function exportTransfers() {
    Swal.fire({
        icon: 'info',
        title: 'Yakında',
        text: 'Dışa aktarma özelliği yakında eklenecek.'
    });
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.transferManager = new TransferManager();
});
</script>
@endsection
