@extends('layouts.master')
@section('title', 'Hesap Hareketleri')
@section('topbar-title', 'Finans - Hesap Hareketleri')

@push('styles')
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #38c66c 0%, #41c3a9 100%);
        --success-gradient: linear-gradient(135deg, #41c3a9 0%, #38c66c 100%);
        --danger-gradient: linear-gradient(135deg, #fe5b5b 0%, #dc3545 100%);
        --info-gradient: linear-gradient(135deg, #4e7adf 0%, #3b82f6 100%);
        --warning-gradient: linear-gradient(135deg, #ffd166 0%, #f59e0b 100%);
        --secondary-gradient: linear-gradient(135deg, #74788d 0%, #6c757d 100%);
        --shadow-light: 0 2px 10px rgba(56, 198, 108, 0.1);
        --shadow-medium: 0 4px 20px rgba(56, 198, 108, 0.15);
        --shadow-heavy: 0 8px 30px rgba(56, 198, 108, 0.2);
        --border-radius: 12px;
        --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    }
    
    .stats-card {
        border: none;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-light);
        transition: var(--transition);
        overflow: hidden;
        position: relative;
        backdrop-filter: blur(10px);
    }
    .stats-card:hover {
        transform: translateY(-10px) scale(1.02);
        box-shadow: var(--shadow-heavy);
    }
    .stats-card .card-body {
        padding: 2.5rem;
        position: relative;
        z-index: 2;
    }
    .stats-card.income { 
        background: linear-gradient(135deg, #38c66c 0%, #2d9653 100%);
        color: white;
        border-left: 4px solid #41c3a9;
    }
    .stats-card.expense { 
        background: linear-gradient(135deg, #fe5b5b 0%, #dc3545 100%);
        color: white;
        border-left: 4px solid #ff4757;
    }
    .stats-card.balance { 
        background: linear-gradient(135deg, #4e7adf 0%, #3b5998 100%);
        color: white;
        border-left: 4px solid #5dade2;
    }
    .stats-card.transactions { 
        background: linear-gradient(135deg, #74788d 0%, #5a6c7d 100%);
        color: white;
        border-left: 4px solid #95a5a6;
    }
    
    .stats-icon {
        position: absolute;
        right: 25px;
        top: 25px;
        font-size: 3.5rem;
        opacity: 0.15;
        transform: rotate(-15deg);
        transition: var(--transition);
    }
    .stats-card:hover .stats-icon {
        opacity: 0.25;
        transform: rotate(0deg) scale(1.1);
    }
    
    .stats-value {
        font-size: 2.2rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
        text-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .stats-label {
        font-size: 1rem;
        font-weight: 600;
        opacity: 0.9;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    .stats-sublabel {
        font-size: 0.85rem;
        opacity: 0.7;
        margin-top: 0.3rem;
    }
    
    .main-content-card {
        border: none;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-medium);
        background: white;
        overflow: hidden;
        position: relative;
    }
    .main-content-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: var(--primary-gradient);
    }
    
    .filter-section {
        background: linear-gradient(135deg, rgba(255,255,255,0.95) 0%, rgba(248,249,250,0.95) 100%);
        border-radius: var(--border-radius);
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: var(--shadow-light);
        border: 1px solid rgba(56, 198, 108, 0.1);
        position: relative;
    }
    .filter-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: var(--primary-gradient);
        border-radius: var(--border-radius) var(--border-radius) 0 0;
    }
    
    .filter-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid rgba(56, 198, 108, 0.1);
    }
    
    .form-control, .form-select {
        border-radius: 12px;
        border: 2px solid #e8e8e8;
        padding: 0.85rem 1.2rem;
        transition: var(--transition);
        background: white;
        font-weight: 500;
    }
    .form-control:focus, .form-select:focus {
        border-color: #38c66c;
        box-shadow: 0 0 0 0.2rem rgba(56, 198, 108, 0.15);
        transform: translateY(-2px);
        background: rgba(56, 198, 108, 0.02);
    }
    
    .form-label {
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .btn-primary {
        background: var(--primary-gradient);
        border: none;
        border-radius: 12px;
        padding: 0.85rem 2.5rem;
        font-weight: 700;
        transition: var(--transition);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: var(--shadow-light);
    }
    .btn-primary:hover {
        transform: translateY(-3px) scale(1.02);
        box-shadow: 0 8px 25px rgba(56, 198, 108, 0.3);
        background: linear-gradient(135deg, #2d9653 0%, #38c66c 100%);
    }
    
    .btn-outline-secondary {
        border: 2px solid #6c757d;
        border-radius: 12px;
        padding: 0.85rem 2rem;
        font-weight: 600;
        transition: var(--transition);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .btn-outline-secondary:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-light);
    }
    
    .export-btn {
        border-radius: 12px;
        padding: 0.6rem 1.2rem;
        font-weight: 600;
        transition: var(--transition);
        border: 2px solid;
        margin: 0.3rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .export-btn:hover {
        transform: translateY(-3px) scale(1.05);
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }
    
    .table-modern {
        border-radius: var(--border-radius);
        overflow: hidden;
        box-shadow: var(--shadow-medium);
        background: white;
        border: none;
    }
    
    .table-modern thead th {
        background: var(--primary-gradient);
        color: white;
        border: none;
        padding: 1.5rem 1.2rem;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 1px;
        position: relative;
        overflow: hidden;
    }
    .table-modern thead th::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, rgba(255,255,255,0.1) 0%, transparent 100%);
        pointer-events: none;
    }
    .table-modern thead th i {
        margin-right: 0.5rem;
        opacity: 0.8;
    }
    
    .table-modern tbody tr {
        transition: var(--transition);
        border: none;
        position: relative;
    }
    .table-modern tbody tr:hover {
        background: linear-gradient(135deg, rgba(56, 198, 108, 0.05) 0%, rgba(65, 195, 169, 0.05) 100%);
        transform: translateX(3px);
        box-shadow: 0 2px 10px rgba(56, 198, 108, 0.1);
    }
    .table-modern tbody tr:hover::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 3px;
        background: var(--primary-gradient);
    }
    .table-modern tbody td {
        padding: 1.2rem 1rem;
        border: none;
        border-bottom: 1px solid rgba(0,0,0,0.03);
        vertical-align: middle;
        font-weight: 500;
    }
    .table-modern tbody tr:nth-child(even) {
        background: rgba(248, 249, 250, 0.3);
    }
    
    .amount-positive {
        color: #38c66c;
        font-weight: 700;
        text-shadow: 0 1px 2px rgba(56, 198, 108, 0.2);
        background: linear-gradient(135deg, rgba(56, 198, 108, 0.1) 0%, rgba(56, 198, 108, 0.05) 100%);
        padding: 0.3rem 0.6rem;
        border-radius: 6px;
        display: inline-block;
    }
    .amount-negative {
        color: #fe5b5b;
        font-weight: 700;
        text-shadow: 0 1px 2px rgba(254, 91, 91, 0.2);
        background: linear-gradient(135deg, rgba(254, 91, 91, 0.1) 0%, rgba(254, 91, 91, 0.05) 100%);
        padding: 0.3rem 0.6rem;
        border-radius: 6px;
        display: inline-block;
    }
    .amount-neutral {
        color: #6c757d;
        font-weight: 700;
        background: rgba(108, 117, 125, 0.1);
        padding: 0.3rem 0.6rem;
        border-radius: 8px;
        display: inline-block;
    }
    
    .transaction-badge {
        border-radius: 25px;
        padding: 0.5rem 1rem;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        border: 2px solid;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }
    .badge-income { 
        background: rgba(56, 198, 108, 0.1);
        color: #38c66c;
        border-color: rgba(56, 198, 108, 0.3);
    }
    .badge-expense { 
        background: rgba(254, 91, 91, 0.1);
        color: #fe5b5b;
        border-color: rgba(254, 91, 91, 0.3);
    }
    .badge-transfer { 
        background: rgba(255, 209, 102, 0.1);
        color: #ffd166;
        border-color: rgba(255, 209, 102, 0.3);
    }
    
    .chart-container {
        background: white;
        border-radius: var(--border-radius);
        padding: 2.5rem;
        box-shadow: var(--shadow-medium);
        margin-bottom: 2rem;
        position: relative;
        height: 450px;
        border: 1px solid rgba(102, 126, 234, 0.1);
        overflow: hidden;
    }
    .chart-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: var(--primary-gradient);
    }
    .chart-container h5 {
        font-weight: 700;
        color: #495057;
        margin-bottom: 1.5rem;
        font-size: 1.1rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    .voucher-link {
        color: #38c66c;
        text-decoration: none;
        font-weight: 600;
        padding: 0.4rem 0.8rem;
        border-radius: 8px;
        transition: var(--transition);
        display: inline-block;
        border: 1px solid rgba(56, 198, 108, 0.3);
        background: rgba(56, 198, 108, 0.05);
    }
    .voucher-link:hover {
        background: rgba(56, 198, 108, 0.1);
        transform: scale(1.05) translateY(-1px);
        box-shadow: 0 4px 15px rgba(56, 198, 108, 0.2);
        border-color: rgba(56, 198, 108, 0.5);
    }
    
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255,255,255,0.95);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        backdrop-filter: blur(5px);
    }
    .loading-overlay.show {
        display: flex;
    }
    
    .loading-spinner {
        width: 50px;
        height: 50px;
        border: 4px solid rgba(56, 198, 108, 0.1);
        border-top: 4px solid #38c66c;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        box-shadow: 0 2px 15px rgba(56, 198, 108, 0.2);
    }
    
    .loading-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1rem;
    }
    
    .loading-text {
        color: #38c66c;
        font-weight: 600;
        font-size: 1rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
    
    .account-selector {
        background: white;
        border: 2px solid #e8e8e8;
        border-radius: 15px;
        padding: 1rem;
        transition: all 0.3s ease;
    }
    .account-selector:focus-within {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
    }
    
    .quick-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        padding: 2rem;
        background: linear-gradient(135deg, rgba(56, 198, 108, 0.05) 0%, rgba(65, 195, 169, 0.05) 100%);
        border-radius: var(--border-radius);
        margin: 2rem 0;
        box-shadow: var(--shadow-light);
        border: 1px solid rgba(56, 198, 108, 0.1);
    }
    .quick-stat-item {
        text-align: center;
        padding: 1rem;
        background: white;
        border-radius: 15px;
        box-shadow: var(--shadow-light);
        transition: var(--transition);
    }
    .quick-stat-item:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-medium);
    }
    .quick-stat-value {
        font-size: 1.8rem;
        font-weight: 800;
        color: #38c66c;
        margin-bottom: 0.5rem;
        text-shadow: 0 1px 3px rgba(56, 198, 108, 0.15);
    }
    .quick-stat-label {
        font-size: 0.8rem;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 600;
    }
    
    .empty-state {
        text-align: center;
        padding: 5rem 2rem;
        color: #6c757d;
        background: linear-gradient(135deg, rgba(248, 249, 250, 0.8) 0%, rgba(233, 236, 239, 0.8) 100%);
        border-radius: var(--border-radius);
        margin: 2rem 0;
    }
    .empty-state i {
        font-size: 5rem;
        margin-bottom: 2rem;
        opacity: 0.3;
        color: #74788d;
    }
    .empty-state h4 {
        font-weight: 700;
        margin-bottom: 1rem;
        color: #495057;
    }
    .empty-state p {
        font-size: 1.1rem;
        line-height: 1.6;
    }
    
    /* Advanced Responsive Design */
    @media (max-width: 1200px) {
        .stats-card .card-body { padding: 2rem; }
        .filter-section { padding: 2rem; }
        .chart-container { height: 400px; padding: 2rem; }
    }
    
    @media (max-width: 992px) {
        .quick-stats { grid-template-columns: repeat(2, 1fr); }
        .chart-container { height: 350px; }
    }
    
    @media (max-width: 768px) {
        :root { --border-radius: 15px; }
        .stats-card .card-body { padding: 1.5rem; }
        .filter-section { padding: 1.5rem; }
        .chart-container { height: 300px; padding: 1.5rem; }
        .export-btn { margin: 0.2rem; padding: 0.5rem 1rem; }
        .quick-stats { grid-template-columns: 1fr; gap: 1rem; }
        .table-modern thead th { padding: 1rem 0.8rem; font-size: 0.75rem; }
        .table-modern tbody td { padding: 1rem 0.8rem; }
    }
    
    @media (max-width: 576px) {
        .stats-value { font-size: 1.8rem; }
        .filter-section { padding: 1rem; }
        .chart-container { height: 250px; padding: 1rem; }
        .btn-primary { padding: 0.7rem 2rem; }
    }
    
    /* Modern Notification System */
    .notification {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 1rem 1.5rem;
        border-radius: 12px;
        color: white;
        font-weight: 600;
        z-index: 10000;
        transform: translateX(400px);
        transition: var(--transition);
        box-shadow: var(--shadow-medium);
        min-width: 300px;
    }
    .notification.show {
        transform: translateX(0);
    }
    .notification.success { background: var(--success-gradient); }
    .notification.error { background: var(--danger-gradient); }
    .notification.warning { background: var(--warning-gradient); }
    .notification.info { background: var(--info-gradient); }
    
    /* Advanced Table Features */
    .table-actions {
        display: flex;
        gap: 0.5rem;
        justify-content: center;
    }
    
    .action-btn {
        padding: 0.3rem 0.6rem;
        border-radius: 8px;
        border: none;
        color: white;
        font-size: 0.8rem;
        cursor: pointer;
        transition: var(--transition);
    }
    .action-btn:hover {
        transform: scale(1.1);
    }
    .action-btn.view { background: var(--info-gradient); }
    .action-btn.edit { background: var(--warning-gradient); }
    .action-btn.delete { background: var(--danger-gradient); }
    
    /* Enhanced Search and Filter */
    .search-wrapper {
        position: relative;
    }
    .search-wrapper::before {
        content: '\f002';
        font-family: 'Font Awesome 5 Free';
        font-weight: 900;
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #667eea;
        z-index: 1;
    }
    .search-wrapper input {
        padding-left: 3rem;
    }
    
    /* Data Animation */
    .fade-in {
        animation: fadeIn 0.5s ease-in;
    }
    
    @keyframes fadeIn {
        from { 
            opacity: 0; 
            transform: translateY(20px); 
        }
        to { 
            opacity: 1; 
            transform: translateY(0); 
        }
    }
    
    /* Chart Legends */
    .chart-legends {
        padding-top: 1rem;
        border-top: 1px solid rgba(0,0,0,0.1);
    }
    
    .legend-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9rem;
        font-weight: 600;
    }
    
    .legend-color {
        width: 16px;
        height: 16px;
        border-radius: 50%;
        display: inline-block;
    }
    
    .chart-summary .summary-item {
        padding: 0.5rem;
        background: rgba(56, 198, 108, 0.05);
        border-radius: 8px;
        border: 1px solid rgba(56, 198, 108, 0.1);
    }
    
    .summary-value {
        font-size: 1.2rem;
        font-weight: 700;
        color: #38c66c;
    }
    
    .summary-label {
        font-size: 0.8rem;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    /* Scrollbar Styling */
    .table-responsive::-webkit-scrollbar {
        height: 8px;
    }
    .table-responsive::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    .table-responsive::-webkit-scrollbar-thumb {
        background: var(--primary-gradient);
        border-radius: 10px;
    }
    .table-responsive::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(135deg, #5a6fd8 0%, #6a4c93 100%);
    }
    
    /* Table Sorting Styles */
    .sortable {
        cursor: pointer;
        user-select: none;
        position: relative;
        transition: var(--transition);
    }
    
    .sortable:hover {
        background: rgba(255,255,255,0.1) !important;
    }
    
    .sort-icon {
        position: absolute;
        right: 8px;
        top: 50%;
        transform: translateY(-50%);
        opacity: 0.5;
        font-size: 0.8rem;
    }
    
    .sortable:hover .sort-icon {
        opacity: 1;
    }
</style>
@endpush

@section('content')
    <!-- Advanced Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-content">
            <div class="loading-spinner"></div>
            <div class="loading-text">Veriler Yüklenliyor...</div>
            <small class="text-muted">Lütfen bekleyiniz</small>
        </div>
    </div>

    <!-- Advanced Statistics Cards -->
    <div class="row mb-5">
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card stats-card income">
                <div class="card-body">
                    <div class="stats-icon">
                        <i class="fas fa-trending-up"></i>
                    </div>
                    <div class="stats-value" id="totalIncome">₺0,00</div>
                    <div class="stats-label">Toplam Gelir</div>
                    <div class="stats-sublabel" id="incomeCount">0 işlem</div>
                    <div class="mt-2">
                        <i class="fas fa-arrow-up me-1"></i>
                        <span id="incomeChange">%0</span>
                        <small class="ms-1">önceki döneme göre</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card stats-card expense">
                <div class="card-body">
                    <div class="stats-icon">
                        <i class="fas fa-trending-down"></i>
                    </div>
                    <div class="stats-value" id="totalExpense">₺0,00</div>
                    <div class="stats-label">Toplam Gider</div>
                    <div class="stats-sublabel" id="expenseCount">0 işlem</div>
                    <div class="mt-2">
                        <i class="fas fa-arrow-down me-1"></i>
                        <span id="expenseChange">%0</span>
                        <small class="ms-1">önceki döneme göre</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card stats-card balance">
                <div class="card-body">
                    <div class="stats-icon">
                        <i class="fas fa-balance-scale-right"></i>
                    </div>
                    <div class="stats-value" id="netBalance">₺0,00</div>
                    <div class="stats-label">Net Bakiye</div>
                    <div class="stats-sublabel" id="balanceChange">Bu dönem</div>
                    <div class="mt-2">
                        <i class="fas fa-chart-line me-1"></i>
                        <span id="balanceStatus">Stabil</span>
                        <small class="ms-1">durum</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card stats-card transactions">
                <div class="card-body">
                    <div class="stats-icon">
                        <i class="fas fa-exchange-alt"></i>
                    </div>
                    <div class="stats-value" id="totalTransactions">0</div>
                    <div class="stats-label">Toplam İşlem</div>
                    <div class="stats-sublabel" id="avgTransactionAmount">Ortalama: ₺0,00</div>
                    <div class="mt-2">
                        <i class="fas fa-clock me-1"></i>
                        <span id="transactionFrequency">-</span>
                        <small class="ms-1">işlem/gün</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Advanced Charts Section -->
    <div class="row mb-5">
        <div class="col-xl-8 col-lg-7 mb-4">
            <div class="chart-container">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-area me-2 text-primary"></i>
                        Hesap Hareketleri Trend Analizi
                    </h5>
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-outline-primary active" data-period="7">7 Gün</button>
                        <button class="btn btn-outline-primary" data-period="30">30 Gün</button>
                        <button class="btn btn-outline-primary" data-period="90">90 Gün</button>
                    </div>
                </div>
                <canvas id="movementsChart"></canvas>
                <div class="chart-legends mt-3">
                    <div class="d-flex justify-content-center gap-4">
                        <div class="legend-item">
                            <span class="legend-color" style="background: #38c66c;"></span>
                            <span>Gelirler</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-color" style="background: #fe5b5b;"></span>
                            <span>Giderler</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-color" style="background: #4e7adf;"></span>
                            <span>Net Akış</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-5 mb-4">
            <div class="chart-container">
                <h5 class="mb-3">
                    <i class="fas fa-chart-donut me-2 text-primary"></i>
                    İşlem Türü Dağılımı
                </h5>
                <canvas id="transactionTypesChart"></canvas>
                <div class="chart-summary mt-3">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="summary-item">
                                <div class="summary-value" id="summaryIncome">0</div>
                                <div class="summary-label">Gelir İşlemi</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="summary-item">
                                <div class="summary-value" id="summaryExpense">0</div>
                                <div class="summary-label">Gider İşlemi</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Advanced Filter Section -->
    <div class="filter-section">
        <div class="filter-header">
            <div>
                <h5 class="mb-0">
                    <i class="fas fa-sliders-h me-2 text-primary"></i>
                    Gelişmiş Filtreleme Sistemi
                </h5>
                <small class="text-muted">Detaylı arama ve filtreleme seçenekleri</small>
            </div>
            <div class="filter-actions">
                <button class="btn btn-sm btn-outline-info" id="savedFilters">
                    <i class="fas fa-bookmark me-1"></i>Kayıtlı Filtreler
                </button>
                <button class="btn btn-sm btn-outline-success" id="saveCurrentFilter">
                    <i class="fas fa-save me-1"></i>Filtreyi Kaydet
                </button>
            </div>
        </div>
        
        <!-- Primary Filters -->
        <div class="row mb-4">
            <div class="col-xl-4 col-lg-6 mb-3">
                <label class="form-label">Hesap Seçimi</label>
                <select id="mov-account" class="form-select account-selector">
                    <option value="">📋 Tüm Hesaplar</option>
                </select>
            </div>
            <div class="col-xl-2 col-lg-3 col-md-6 mb-3">
                <label class="form-label">Başlangıç Tarihi</label>
                <input type="date" id="mov-start" class="form-control" value="{{ date('Y-m-d', strtotime('-30 days')) }}">
            </div>
            <div class="col-xl-2 col-lg-3 col-md-6 mb-3">
                <label class="form-label">Bitiş Tarihi</label>
                <input type="date" id="mov-end" class="form-control" value="{{ date('Y-m-d') }}">
            </div>
            <div class="col-xl-4 col-lg-12 mb-3">
                <label class="form-label">Hızlı Tarih Seçimleri</label>
                <div class="btn-group w-100" role="group">
                    <button type="button" class="btn btn-outline-secondary" data-days="7">Son 7 Gün</button>
                    <button type="button" class="btn btn-outline-secondary" data-days="30">Son 30 Gün</button>
                    <button type="button" class="btn btn-outline-secondary" data-days="90">Son 90 Gün</button>
                    <button type="button" class="btn btn-outline-secondary" data-days="365">Son 1 Yıl</button>
                </div>
            </div>
        </div>
        
        <!-- Secondary Filters -->
        <div class="row mb-4">
            <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
                <label class="form-label">İşlem Türü</label>
                <select id="mov-type" class="form-select">
                    <option value="">🔄 Tüm İşlem Türleri</option>
                    <option value="collection">💰 Tahsilat</option>
                    <option value="payment">💸 Ödeme</option>
                    <option value="transfer">🔄 Virman</option>
                    <option value="expense">📊 Masraf</option>
                    <option value="income">📈 Gelir</option>
                </select>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
                <label class="form-label">Tutar Aralığı</label>
                <div class="input-group">
                    <input type="number" id="mov-min-amount" class="form-control" placeholder="Min.">
                    <span class="input-group-text">-</span>
                    <input type="number" id="mov-max-amount" class="form-control" placeholder="Max.">
                </div>
            </div>
            <div class="col-xl-6 col-lg-12 mb-3">
                <label class="form-label">Açıklama / Fiş No / Referans Arama</label>
                <div class="search-wrapper">
                    <input type="text" id="mov-search" class="form-control" placeholder="Arama yapın... (Açıklama, fiş no, referans)">
                </div>
            </div>
        </div>
        
        <!-- Action Buttons -->
        <div class="row">
            <div class="col-12">
                <div class="d-flex flex-wrap justify-content-between align-items-center">
                    <div class="filter-controls">
                        <button id="mov-load" class="btn btn-primary me-3">
                            <i class="fas fa-search me-2"></i>Hareketleri Listele
                        </button>
                        <button id="mov-clear" class="btn btn-outline-secondary me-3">
                            <i class="fas fa-eraser me-2"></i>Filtreleri Temizle
                        </button>
                        <button id="mov-refresh" class="btn btn-outline-info me-3">
                            <i class="fas fa-sync-alt me-2"></i>Yenile
                        </button>
                    </div>
                    <div class="export-controls">
                        <div class="btn-group">
                            <button class="btn btn-outline-success export-btn" id="mov-export-csv" title="CSV formatında dışa aktar">
                                <i class="fas fa-file-csv me-1"></i>CSV
                            </button>
                            <button class="btn btn-outline-primary export-btn" id="mov-export-xlsx" title="Excel formatında dışa aktar">
                                <i class="fas fa-file-excel me-1"></i>XLSX
                            </button>
                            <button class="btn btn-outline-danger export-btn" id="mov-export-pdf" title="PDF formatında dışa aktar">
                                <i class="fas fa-file-pdf me-1"></i>PDF
                            </button>
                            <button class="btn btn-outline-info export-btn" id="mov-export-print" title="Yazdır">
                                <i class="fas fa-print me-1"></i>Yazdır
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Quick Stats -->
    <div class="quick-stats" id="quickStats" style="display: none;">
        <div class="quick-stat-item">
            <div class="quick-stat-value" id="quickTotalTransactions">0</div>
            <div class="quick-stat-label">İşlem Sayısı</div>
            <small class="text-muted">Filtrelenen</small>
        </div>
        <div class="quick-stat-item">
            <div class="quick-stat-value" id="quickAverageAmount">₺0</div>
            <div class="quick-stat-label">Ortalama Tutar</div>
            <small class="text-muted">Per işlem</small>
        </div>
        <div class="quick-stat-item">
            <div class="quick-stat-value" id="quickLargestTransaction">₺0</div>
            <div class="quick-stat-label">En Büyük İşlem</div>
            <small class="text-muted">Tek seferde</small>
        </div>
        <div class="quick-stat-item">
            <div class="quick-stat-value" id="quickLastTransaction">-</div>
            <div class="quick-stat-label">Son İşlem</div>
            <small class="text-muted">Tarih</small>
        </div>
        <div class="quick-stat-item">
            <div class="quick-stat-value" id="quickTotalVolume">₺0</div>
            <div class="quick-stat-label">Toplam Hacim</div>
            <small class="text-muted">Tüm işlemler</small>
        </div>
        <div class="quick-stat-item">
            <div class="quick-stat-value" id="quickTransactionFreq">0</div>
            <div class="quick-stat-label">İşlem Sıklığı</div>
            <small class="text-muted">İşlem/gün</small>
        </div>
    </div>

    <!-- Simplified Main Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="fas fa-list me-2"></i>Hesap Hareketleri
            </h5>
            <div>
                <span class="badge bg-primary" id="movementsCount">0 hareket</span>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="mov-table">
                    <thead class="table-light">
                        <tr>
                            <th>Tarih</th>
                            <th>Fiş No</th>
                            <th>Açıklama</th>
                            <th>Tür</th>
                            <th class="text-end">Gelen</th>
                            <th class="text-end">Giden</th>
                            <th class="text-end">Bakiye</th>
                        </tr>
                    </thead>
                    <tbody id="mov-tbody">
                        <!-- Dinamik içerik -->
                    </tbody>
                </table>
            </div>
            
            <!-- Simple Empty State -->
            <div class="text-center py-5" id="emptyState" style="display: none;">
                <div class="mb-3">
                    <i class="fas fa-inbox fa-3x text-muted"></i>
                </div>
                <h5>Henüz Hareket Bulunamadı</h5>
                <p class="text-muted">Seçtiğiniz hesap için hareket bulunmuyor.<br>Filtreleri değiştirerek tekrar deneyin.</p>
            </div>
        </div>
    </div>


@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function(){
    // Enhanced Global Variables
    let movementsChart = null;
    let transactionTypesChart = null;
    let currentData = [];
    let filteredData = [];
    let selectedAccount = null;
    let currentSort = { field: 'date', direction: 'desc' };
    let currentPage = 1;
    let itemsPerPage = 25;
    let savedFilters = JSON.parse(localStorage.getItem('movementsFilters') || '{}');
    
    const dataUrl = '{{ route("finance.cash.accounts.data") }}';
    const movementsUrlTemplate = '{{ url("finance/cash/accounts") }}/:id/movements';
    
    // Enhanced Initialize
    init();
    
    function init() {
        loadAccounts();
        setupEventListeners();
        initializeCharts();
        setupKeyboardShortcuts();
        initializeTooltips();
        loadSavedFilters();
    }
    
    function loadAccounts() {
        fetch(dataUrl)
            .then(r => r.json())
            .then(json => {
                const accounts = json.accounts || [];
                const sel = document.getElementById('mov-account');
                sel.innerHTML = '<option value="">Hesap seçiniz...</option>';
                
                accounts.forEach(a => {
                    const opt = document.createElement('option');
                    opt.value = a.id;
                    opt.textContent = `${a.name} (${a.currency || 'TRY'}) - ₺${formatMoney(a.balance || 0)}`;
                    sel.appendChild(opt);
                });
            })
            .catch(err => {
                console.error('Hesaplar yüklenirken hata:', err);
                showNotification('Hesaplar yüklenirken hata oluştu', 'error');
            });
    }
    
    function setupEventListeners() {
        document.getElementById('mov-load').addEventListener('click', loadMovements);
        document.getElementById('mov-clear').addEventListener('click', clearFilters);
        document.getElementById('mov-account').addEventListener('change', onAccountChange);
        
        // Export buttons
        document.getElementById('mov-export-csv').addEventListener('click', () => exportData('csv'));
        document.getElementById('mov-export-xlsx').addEventListener('click', () => exportData('xlsx'));
        document.getElementById('mov-export-pdf').addEventListener('click', () => exportData('pdf'));
        
        // Auto-search with debounce
        document.getElementById('mov-search').addEventListener('input', debounce(autoSearch, 500));
        
        // Date change handlers
        document.getElementById('mov-start').addEventListener('change', validateDateRange);
        document.getElementById('mov-end').addEventListener('change', validateDateRange);
    }
    
    function loadMovements() {
        const accId = document.getElementById('mov-account').value;
        if (!accId) {
            showNotification('Lütfen önce bir hesap seçin', 'warning');
            return;
        }
        
        showLoading(true);
        
        const params = getFilterParams();
        let url = movementsUrlTemplate.replace(':id', accId) + '?' + new URLSearchParams(params).toString();
        
        fetch(url)
            .then(r => r.json())
            .then(json => {
                if (!json.ok) {
                    throw new Error(json.message || 'Hata oluştu');
                }
                
                currentData = json.data || [];
                renderMovements(currentData);
                updateStatistics(currentData);
                updateCharts(currentData);
                updateQuickStats(currentData);
                
                document.getElementById('movementsCount').textContent = `${currentData.length} hareket`;
                
                if (currentData.length === 0) {
                    showEmptyState();
                } else {
                    hideEmptyState();
                }
                
                showNotification(`${currentData.length} hareket yüklendi`, 'success');
            })
            .catch(err => {
                console.error('Hareketler yüklenirken hata:', err);
                showNotification('Hareketler yüklenirken hata oluştu: ' + err.message, 'error');
                showEmptyState();
            })
            .finally(() => {
                showLoading(false);
            });
    }
    
    function getFilterParams() {
        return {
            start: document.getElementById('mov-start').value,
            end: document.getElementById('mov-end').value,
            type: document.getElementById('mov-type').value,
            q: document.getElementById('mov-search').value,
            min_amount: document.getElementById('mov-min-amount').value,
            max_amount: document.getElementById('mov-max-amount').value
        };
    }
    
    function renderMovements(movements) {
        const tbody = document.getElementById('mov-tbody');
        tbody.innerHTML = '';
        
        if (movements.length === 0) {
            return;
        }
        
        movements.forEach((movement, index) => {
            const tr = document.createElement('tr');
            tr.style.animationDelay = `${index * 50}ms`;
            tr.classList.add('fade-in');
            
            const voucherCell = movement.voucher_no ? 
                `<a href="${movement.voucher_link || '#'}" target="_blank" class="voucher-link">${movement.voucher_no}</a>` : 
                '<span class="text-muted">-</span>';
            
            const transactionType = getTransactionType(movement);
            const transactionBadge = `<span class="transaction-badge badge-${transactionType.type}">${transactionType.label}</span>`;
            
            const debitAmount = movement.debit ? 
                `<span class="amount-positive">+₺${formatMoney(movement.debit)}</span>` : 
                '<span class="text-muted">-</span>';
            
            const creditAmount = movement.credit ? 
                `<span class="amount-negative">-₺${formatMoney(movement.credit)}</span>` : 
                '<span class="text-muted">-</span>';
            
            const balanceAmount = movement.balance ? 
                `<span class="amount-neutral">₺${formatMoney(movement.balance)}</span>` : 
                '<span class="text-muted">-</span>';
            
            tr.innerHTML = `
                <td><strong>${formatDate(movement.date)}</strong></td>
                <td>${voucherCell}</td>
                <td class="text-truncate" style="max-width: 200px;" title="${escapeHtml(movement.description || '')}">${escapeHtml(movement.description || '-')}</td>
                <td>${transactionBadge}</td>
                <td>${debitAmount}</td>
                <td>${creditAmount}</td>
                <td>${balanceAmount}</td>
            `;
            
            tbody.appendChild(tr);
        });
    }
    
    function updateStatistics(movements) {
        const stats = calculateStatistics(movements);
        
        document.getElementById('totalIncome').textContent = `₺${formatMoney(stats.totalIncome)}`;
        document.getElementById('totalExpense').textContent = `₺${formatMoney(stats.totalExpense)}`;
        document.getElementById('netBalance').textContent = `₺${formatMoney(stats.netBalance)}`;
        document.getElementById('totalTransactions').textContent = stats.totalTransactions;
        
        document.getElementById('incomeCount').textContent = `${stats.incomeCount} işlem`;
        document.getElementById('expenseCount').textContent = `${stats.expenseCount} işlem`;
        document.getElementById('avgTransactionAmount').textContent = `Ortalama: ₺${formatMoney(stats.avgAmount)}`;
        
        const balanceChange = stats.netBalance >= 0 ? 'Pozitif' : 'Negatif';
        document.getElementById('balanceChange').textContent = balanceChange;
    }
    
    function updateQuickStats(movements) {
        const stats = calculateStatistics(movements);
        const quickStats = document.getElementById('quickStats');
        
        if (movements.length > 0) {
            document.getElementById('quickTotalTransactions').textContent = stats.totalTransactions;
            document.getElementById('quickAverageAmount').textContent = `₺${formatMoney(stats.avgAmount)}`;
            document.getElementById('quickLargestTransaction').textContent = `₺${formatMoney(stats.largestTransaction)}`;
            document.getElementById('quickLastTransaction').textContent = formatDate(stats.lastTransactionDate);
            
            quickStats.style.display = 'flex';
        } else {
            quickStats.style.display = 'none';
        }
    }
    
    function calculateStatistics(movements) {
        let totalIncome = 0;
        let totalExpense = 0;
        let incomeCount = 0;
        let expenseCount = 0;
        let largestTransaction = 0;
        let lastTransactionDate = null;
        
        movements.forEach(movement => {
            if (movement.debit && movement.debit > 0) {
                totalIncome += parseFloat(movement.debit);
                incomeCount++;
            }
            if (movement.credit && movement.credit > 0) {
                totalExpense += parseFloat(movement.credit);
                expenseCount++;
            }
            
            const amount = Math.max(parseFloat(movement.debit || 0), parseFloat(movement.credit || 0));
            if (amount > largestTransaction) {
                largestTransaction = amount;
            }
            
            if (!lastTransactionDate || movement.date > lastTransactionDate) {
                lastTransactionDate = movement.date;
            }
        });
        
        const totalTransactions = movements.length;
        const netBalance = totalIncome - totalExpense;
        const avgAmount = totalTransactions > 0 ? (totalIncome + totalExpense) / totalTransactions : 0;
        
        return {
            totalIncome,
            totalExpense,
            netBalance,
            totalTransactions,
            incomeCount,
            expenseCount,
            avgAmount,
            largestTransaction,
            lastTransactionDate
        };
    }
    
    function initializeCharts() {
        // Movements trend chart
        const movementsCtx = document.getElementById('movementsChart').getContext('2d');
        movementsChart = new Chart(movementsCtx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Günlük Bakiye',
                    data: [],
                    borderColor: 'rgb(102, 126, 234)',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
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
                        beginAtZero: false,
                        ticks: {
                            callback: function(value) {
                                return '₺' + formatMoney(value);
                            }
                        }
                    }
                }
            }
        });
        
        // Transaction types chart
        const typesCtx = document.getElementById('transactionTypesChart').getContext('2d');
        transactionTypesChart = new Chart(typesCtx, {
            type: 'doughnut',
            data: {
                labels: ['Gelir', 'Gider'],
                datasets: [{
                    data: [0, 0],
                    backgroundColor: [
                        'rgba(102, 126, 234, 0.8)',
                        'rgba(240, 147, 251, 0.8)'
                    ],
                    borderWidth: 0
                }]
            },
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
    }
    
    function updateCharts(movements) {
        updateMovementsChart(movements);
        updateTransactionTypesChart(movements);
    }
    
    function updateMovementsChart(movements) {
        const dailyBalance = {};
        
        movements.forEach(movement => {
            const date = movement.date;
            if (!dailyBalance[date]) {
                dailyBalance[date] = 0;
            }
            dailyBalance[date] = parseFloat(movement.balance || 0);
        });
        
        const sortedDates = Object.keys(dailyBalance).sort();
        const balances = sortedDates.map(date => dailyBalance[date]);
        
        movementsChart.data.labels = sortedDates.map(date => formatDate(date));
        movementsChart.data.datasets[0].data = balances;
        movementsChart.update();
    }
    
    function updateTransactionTypesChart(movements) {
        const stats = calculateStatistics(movements);
        
        transactionTypesChart.data.datasets[0].data = [stats.totalIncome, stats.totalExpense];
        transactionTypesChart.update();
    }
    
    function getTransactionType(movement) {
        if (movement.debit && movement.debit > 0) {
            return { type: 'income', label: 'Gelir' };
        } else if (movement.credit && movement.credit > 0) {
            return { type: 'expense', label: 'Gider' };
        } else {
            return { type: 'transfer', label: 'Transfer' };
        }
    }
    
    function clearFilters() {
        document.getElementById('mov-account').value = '';
        document.getElementById('mov-start').value = '{{ date("Y-m-d", strtotime("-30 days")) }}';
        document.getElementById('mov-end').value = '{{ date("Y-m-d") }}';
        document.getElementById('mov-type').value = '';
        document.getElementById('mov-search').value = '';
        document.getElementById('mov-min-amount').value = '';
        document.getElementById('mov-max-amount').value = '';
        
        // Clear data
        currentData = [];
        document.getElementById('mov-tbody').innerHTML = '';
        resetStatistics();
        resetCharts();
        document.getElementById('quickStats').style.display = 'none';
        showEmptyState();
        
        showNotification('Filtreler temizlendi', 'info');
    }
    
    function exportData(format) {
        const accId = document.getElementById('mov-account').value;
        if (!accId) {
            showNotification('Lütfen önce bir hesap seçin', 'warning');
            return;
        }
        
        const params = getFilterParams();
        const baseExport = `{{ url("finance/cash/accounts") }}/${accId}/movements/export`;
        const url = baseExport + '?' + new URLSearchParams({...params, format}).toString();
        
        if (format === 'pdf') {
            window.open(url, '_blank');
        } else {
            window.location = url;
        }
        
        showNotification(`${format.toUpperCase()} formatında dışa aktarılıyor...`, 'info');
    }
    
    function onAccountChange() {
        const accId = document.getElementById('mov-account').value;
        if (accId) {
            selectedAccount = accId;
            // Otomatik yükleme (isteğe bağlı)
            // loadMovements();
        }
    }
    
    function autoSearch() {
        const accId = document.getElementById('mov-account').value;
        if (accId && document.getElementById('mov-search').value.length > 2) {
            loadMovements();
        }
    }
    
    function validateDateRange() {
        const start = new Date(document.getElementById('mov-start').value);
        const end = new Date(document.getElementById('mov-end').value);
        
        if (start > end) {
            showNotification('Başlangıç tarihi bitiş tarihinden sonra olamaz', 'warning');
            return false;
        }
        
        const diffTime = Math.abs(end - start);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        
        if (diffDays > 365) {
            showNotification('Tarih aralığı 1 yıldan fazla olamaz', 'warning');
            return false;
        }
        
        return true;
    }
    
    function showLoading(show) {
        const overlay = document.getElementById('loadingOverlay');
        overlay.style.display = show ? 'flex' : 'none';
    }
    
    function showEmptyState() {
        document.getElementById('emptyState').style.display = 'block';
        document.getElementById('mov-table').style.display = 'none';
    }
    
    function hideEmptyState() {
        document.getElementById('emptyState').style.display = 'none';
        document.getElementById('mov-table').style.display = 'table';
    }
    
    function resetStatistics() {
        document.getElementById('totalIncome').textContent = '₺0,00';
        document.getElementById('totalExpense').textContent = '₺0,00';
        document.getElementById('netBalance').textContent = '₺0,00';
        document.getElementById('totalTransactions').textContent = '0';
        document.getElementById('incomeCount').textContent = '0 işlem';
        document.getElementById('expenseCount').textContent = '0 işlem';
        document.getElementById('avgTransactionAmount').textContent = 'Ortalama: ₺0,00';
        document.getElementById('balanceChange').textContent = 'Bu dönem';
        document.getElementById('movementsCount').textContent = '0 hareket';
    }
    
    function resetCharts() {
        if (movementsChart) {
            movementsChart.data.labels = [];
            movementsChart.data.datasets[0].data = [];
            movementsChart.update();
        }
        
        if (transactionTypesChart) {
            transactionTypesChart.data.datasets[0].data = [0, 0];
            transactionTypesChart.update();
        }
    }
    
    function showNotification(message, type = 'info') {
        // Basit notification sistemi
        const notification = document.createElement('div');
        notification.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show position-fixed`;
        notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        notification.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 5000);
    }
    
    // Utility functions
    function formatMoney(value) {
        try {
            const num = parseFloat(value);
            return num.toLocaleString('tr-TR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        } catch (e) {
            return '0,00';
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
    
    // Essential missing functions
    function setupKeyboardShortcuts() {
        document.addEventListener('keydown', (e) => {
            if (e.ctrlKey || e.metaKey) {
                switch(e.key) {
                    case 'f':
                        e.preventDefault();
                        document.getElementById('mov-search').focus();
                        break;
                    case 'r':
                        e.preventDefault();
                        refreshData();
                        break;
                    case 'e':
                        if (e.shiftKey) {
                            e.preventDefault();
                            exportData('xlsx');
                        }
                        break;
                }
            }
        });
    }
    
    function initializeTooltips() {
        // Initialize Bootstrap tooltips if available
        if (window.bootstrap && bootstrap.Tooltip) {
            document.querySelectorAll('[title]').forEach(element => {
                new bootstrap.Tooltip(element);
            });
        }
    }
    
    function refreshData() {
        window.location.reload();
    }
    
    function formatDate(dateString, format = 'DD/MM/YYYY') {
        if (!dateString) return '-';
        // Simple date formatting since moment.js might not be loaded
        try {
            const date = new Date(dateString);
            return date.toLocaleDateString('tr-TR');
        } catch {
            return dateString;
        }
    }
    
    function formatDateTime(dateString) {
        if (!dateString) return '-';
        try {
            const date = new Date(dateString);
            return date.toLocaleString('tr-TR');
        } catch {
            return dateString;
        }
    }
    

});
</script>
@endpush
