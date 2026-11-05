@extends('layouts.master')
@section('title', 'Finansal Raporlar & Analitik')
@section('topbar-title', 'Finans - Gelişmiş Cari Raporları')

@push('styles')
<style>
    .kpi-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 15px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .kpi-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    }
    .kpi-card.success { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); }
    .kpi-card.warning { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
    .kpi-card.danger { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
    .kpi-card.info { background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); color: #333; }
    
    .chart-container {
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        padding: 20px;
        margin-bottom: 20px;
    }
    
    .filter-panel {
        background: #f8f9fa;
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    
    .report-tabs .nav-link {
        border-radius: 25px;
        margin-right: 10px;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .report-tabs .nav-link.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-color: transparent;
        color: white;
    }
    
    .data-table {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    }
    
    .data-table th {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        font-weight: 600;
        padding: 15px;
        border: none;
    }
    
    .export-buttons .btn {
        border-radius: 25px;
        padding: 8px 20px;
        font-weight: 500;
        margin-left: 10px;
    }
    
    .metric-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #eee;
    }
    
    .metric-item:last-child { border-bottom: none; }
    
    .loading-spinner {
        display: inline-block;
        width: 20px;
        height: 20px;
        border: 3px solid #f3f3f3;
        border-top: 3px solid #667eea;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    .trend-up { color: #28a745; }
    .trend-down { color: #dc3545; }
    .trend-neutral { color: #6c757d; }
    
    .notification-badge {
        position: absolute;
        top: -5px;
        right: -5px;
        background: #dc3545;
        color: white;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        font-size: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
    }
    
    .interactive-metric {
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .interactive-metric:hover {
        transform: scale(1.05);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    
    .pulse-animation {
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(102, 126, 234, 0.7); }
        70% { box-shadow: 0 0 0 10px rgba(102, 126, 234, 0); }
        100% { box-shadow: 0 0 0 0 rgba(102, 126, 234, 0); }
    }
    
    .alert-modern {
        border: none;
        border-left: 4px solid;
        border-radius: 10px;
        padding: 15px 20px;
        margin-bottom: 15px;
    }
    
    .alert-modern.alert-info { border-left-color: #17a2b8; background: linear-gradient(90deg, rgba(23,162,184,0.1) 0%, rgba(255,255,255,1) 100%); }
    .alert-modern.alert-warning { border-left-color: #ffc107; background: linear-gradient(90deg, rgba(255,193,7,0.1) 0%, rgba(255,255,255,1) 100%); }
    .alert-modern.alert-success { border-left-color: #28a745; background: linear-gradient(90deg, rgba(40,167,69,0.1) 0%, rgba(255,255,255,1) 100%); }
    .alert-modern.alert-danger { border-left-color: #dc3545; background: linear-gradient(90deg, rgba(220,53,69,0.1) 0%, rgba(255,255,255,1) 100%); }
</style>
@endpush

@section('content')
    <!-- KPI Dashboard -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="kpi-card interactive-metric card border-0 p-3 mb-3 {{ $kpis['overdue_receivables'] > 0 ? 'pulse-animation' : '' }}" 
                 data-bs-toggle="tooltip" title="Son 30 günde %{{ number_format(($kpis['overdue_receivables'] / max($kpis['total_receivables'], 1)) * 100, 1) }} vadesi geçti">
                <div class="text-center position-relative">
                    @if($kpis['overdue_receivables'] > 0)
                        <span class="notification-badge">!</span>
                    @endif
                    <h3 class="mb-1">₺{{ number_format($kpis['total_receivables'], 0) }}</h3>
                    <small class="opacity-75">Toplam Alacaklar</small>
                    <div class="mt-2">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="progress mt-2" style="height: 4px;">
                        <div class="progress-bar bg-white" style="width: 75%"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="kpi-card success interactive-metric card border-0 p-3 mb-3" 
                 data-bs-toggle="tooltip" title="Ödenecek tutarların vadeli dağılımı">
                <div class="text-center position-relative">
                    <h3 class="mb-1">₺{{ number_format($kpis['total_payables'], 0) }}</h3>
                    <small class="opacity-75">Toplam Borçlar</small>
                    <div class="mt-2">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div class="progress mt-2" style="height: 4px;">
                        <div class="progress-bar bg-white" style="width: 60%"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="kpi-card warning interactive-metric card border-0 p-3 mb-3 {{ $kpis['dso'] > 45 ? 'pulse-animation' : '' }}" 
                 data-bs-toggle="tooltip" title="Sektör ortalaması: 30-45 gün">
                <div class="text-center position-relative">
                    @if($kpis['dso'] > 45)
                        <span class="notification-badge">{{ floor($kpis['dso'] - 45) }}</span>
                    @endif
                    <h3 class="mb-1">{{ number_format($kpis['dso'], 1) }}</h3>
                    <small class="opacity-75">Ortalama Tahsilat Süresi (Gün)</small>
                    <div class="mt-2">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="progress mt-2" style="height: 4px;">
                        <div class="progress-bar bg-white" style="width: {{ min(100, ($kpis['dso'] / 60) * 100) }}%"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="kpi-card danger interactive-metric card border-0 p-3 mb-3 {{ $kpis['collection_efficiency'] < 80 ? 'pulse-animation' : '' }}" 
                 data-bs-toggle="tooltip" title="Hedef: %85+ tahsilat etkinliği">
                <div class="text-center position-relative">
                    @if($kpis['collection_efficiency'] < 80)
                        <span class="notification-badge">⚠</span>
                    @endif
                    <h3 class="mb-1">%{{ number_format($kpis['collection_efficiency'], 1) }}</h3>
                    <small class="opacity-75">Tahsilat Etkinliği</small>
                    <div class="mt-2">
                        <i class="fas fa-percentage"></i>
                    </div>
                    <div class="progress mt-2" style="height: 4px;">
                        <div class="progress-bar bg-white" style="width: {{ $kpis['collection_efficiency'] }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Real-time Notifications -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-modern alert-info" id="realTimeNotifications">
                <div class="d-flex align-items-center">
                    <i class="fas fa-bell fa-lg me-3"></i>
                    <div class="flex-grow-1">
                        <strong>Canlı Bildirimler Aktif</strong>
                        <p class="mb-0">Finansal durum değişiklikleri gerçek zamanlı olarak izleniyor.</p>
                    </div>
                    <div class="text-end">
                        <small class="text-muted">Son güncelleme: {{ now()->format('H:i') }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Report Navigation Tabs -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <ul class="nav nav-pills report-tabs mb-4" id="reportTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="dashboard-tab" data-bs-toggle="pill" data-bs-target="#dashboard" type="button" role="tab">
                        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="statement-tab" data-bs-toggle="pill" data-bs-target="#statement" type="button" role="tab">
                        <i class="fas fa-file-invoice me-2"></i>Hesap Ekstresi
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="ageing-tab" data-bs-toggle="pill" data-bs-target="#ageing" type="button" role="tab">
                        <i class="fas fa-chart-bar me-2"></i>Yaşlandırma Analizi
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="cashflow-tab" data-bs-toggle="pill" data-bs-target="#cashflow" type="button" role="tab">
                        <i class="fas fa-water me-2"></i>Nakit Akış Tahmini
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="analytics-tab" data-bs-toggle="pill" data-bs-target="#analytics" type="button" role="tab">
                        <i class="fas fa-chart-pie me-2"></i>Analitik Raporlar
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="reportTabsContent">
                <!-- Dashboard Tab -->
                <div class="tab-pane fade show active" id="dashboard" role="tabpanel">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="chart-container">
                                <h5 class="mb-3">Aylık Tahsilat Trendi</h5>
                                <div id="trendsChartLoading" class="text-center p-4">
                                    <div class="loading-spinner"></div>
                                    <p class="mt-2 text-muted">Grafik yükleniyor...</p>
                                </div>
                                <canvas id="trendsChart" height="300" style="display: none;"></canvas>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="chart-container">
                                <h5 class="mb-3">Özet Metrikler</h5>
                                <div class="metric-item">
                                    <span>Vadesi Geçen Alacaklar</span>
                                    <strong class="trend-down">₺{{ number_format($kpis['overdue_receivables'], 0) }}</strong>
                                </div>
                                <div class="metric-item">
                                    <span>Vadesi Geçen Borçlar</span>
                                    <strong class="trend-up">₺{{ number_format($kpis['overdue_payables'], 0) }}</strong>
                                </div>
                                <div class="metric-item">
                                    <span>Net Pozisyon</span>
                                    <strong class="trend-{{ ($kpis['total_receivables'] - $kpis['total_payables']) > 0 ? 'up' : 'down' }}">
                                        ₺{{ number_format($kpis['total_receivables'] - $kpis['total_payables'], 0) }}
                                    </strong>
                                </div>
                                <div class="metric-item">
                                    <span>Nakit Dönüş Döngüsü</span>
                                    <strong class="trend-neutral">{{ number_format($kpis['dso'], 0) }} gün</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="chart-container">
                                <h5 class="mb-3">En Yüksek Cirolu Müşteriler</h5>
                                <div id="customerRankingTable"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statement Tab -->
                <div class="tab-pane fade" id="statement" role="tabpanel">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="filter-panel">
                                <h6 class="mb-3">Filtreler</h6>
                                <div class="mb-3">
                                    <label class="form-label">Cari Hesap</label>
                                    <select id="statement-party" class="form-select">
                                        <option value="">Müşteri/Tedarikçi Seçin</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Başlangıç Tarihi</label>
                                    <input type="date" id="statement-start" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Bitiş Tarihi</label>
                                    <input type="date" id="statement-end" class="form-control">
                                </div>
                                <button id="run-statement" class="btn btn-primary w-100">
                                    <i class="fas fa-play me-2"></i>Raporu Oluştur
                                </button>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="data-table">
                                <div id="statement-results" class="p-4">
                                    <div class="text-center text-muted">
                                        <i class="fas fa-chart-line fa-3x mb-3"></i>
                                        <p>Cari hesap seçin ve "Raporu Oluştur" butonuna tıklayın</p>
                                    </div>
                                </div>
                            </div>
                            <div class="export-buttons mt-3 text-end">
                                <button id="export-statement-pdf" class="btn btn-outline-danger">
                                    <i class="fas fa-file-pdf me-2"></i>PDF
                                </button>
                                <button id="export-statement-excel" class="btn btn-outline-success">
                                    <i class="fas fa-file-excel me-2"></i>Excel
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ageing Analysis Tab -->
                <div class="tab-pane fade" id="ageing" role="tabpanel">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="filter-panel">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label">Rapor Tipi</label>
                                        <select id="ageing-type" class="form-select">
                                            <option value="receivable_ageing">Alacak Yaşlandırma</option>
                                            <option value="payable_ageing">Borç Yaşlandırma</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Referans Tarihi</label>
                                        <input type="date" id="ageing-date" class="form-control" value="{{ date('Y-m-d') }}">
                                    </div>
                                </div>
                                <button id="run-ageing" class="btn btn-primary mt-3">
                                    <i class="fas fa-chart-bar me-2"></i>Analizi Çalıştır
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="chart-container">
                                <canvas id="ageingChart" height="200"></canvas>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12">
                            <div class="data-table">
                                <div id="ageing-summary" class="p-4"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="data-table">
                                <div class="p-3 bg-light">
                                    <h6 class="mb-0">Detaylı Yaşlandırma Listesi</h6>
                                </div>
                                <div id="ageing-details" class="p-4"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cash Flow Tab -->
                <div class="tab-pane fade" id="cashflow" role="tabpanel">
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="filter-panel">
                                <h6 class="mb-3">Tahmin Parametreleri</h6>
                                <div class="mb-3">
                                    <label class="form-label">Süre (Ay)</label>
                                    <select id="cashflow-period" class="form-select">
                                        <option value="6">6 Ay</option>
                                        <option value="12" selected>12 Ay</option>
                                        <option value="24">24 Ay</option>
                                    </select>
                                </div>
                                <button id="run-cashflow" class="btn btn-primary w-100">
                                    <i class="fas fa-calculator me-2"></i>Tahmini Oluştur
                                </button>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="chart-container">
                                <h5 class="mb-3">Nakit Akış Tahmini</h5>
                                <canvas id="cashflowChart" height="300"></canvas>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12">
                            <div class="data-table">
                                <div id="cashflow-table" class="p-4"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Analytics Tab -->
                <div class="tab-pane fade" id="analytics" role="tabpanel">
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="filter-panel">
                                <h6 class="mb-3">Analiz Parametreleri</h6>
                                <div class="mb-3">
                                    <label class="form-label">Analiz Tipi</label>
                                    <select id="analytics-type" class="form-select">
                                        <option value="performance">Performans Analizi</option>
                                        <option value="risk">Risk Analizi</option>
                                        <option value="behavior">Davranış Analizi</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Zaman Aralığı</label>
                                    <select id="analytics-period" class="form-select">
                                        <option value="3">Son 3 Ay</option>
                                        <option value="6">Son 6 Ay</option>
                                        <option value="12" selected>Son 12 Ay</option>
                                    </select>
                                </div>
                                <button id="run-analytics" class="btn btn-primary w-100">
                                    <i class="fas fa-chart-line me-2"></i>Analizi Çalıştır
                                </button>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="chart-container">
                                <h5 class="mb-3" id="analytics-title">Müşteri Performans Analizi</h5>
                                <canvas id="analyticsMainChart" height="300"></canvas>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="chart-container">
                                <h6 class="mb-3">Risk Dağılımı</h6>
                                <canvas id="riskDistributionChart" height="250"></canvas>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="chart-container">
                                <h6 class="mb-3">Ödeme Davranış Matrisi</h6>
                                <div id="paymentMatrix" class="row text-center">
                                    <div class="col-6 mb-3">
                                        <div class="bg-success text-white p-3 rounded">
                                            <h4 id="excellentPayers">0</h4>
                                            <small>Mükemmel Ödeyenler</small>
                                        </div>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <div class="bg-warning text-white p-3 rounded">
                                            <h4 id="latePayers">0</h4>
                                            <small>Geç Ödeyenler</small>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="bg-info text-white p-3 rounded">
                                            <h4 id="averagePayers">0</h4>
                                            <small>Ortalama Ödeyenler</small>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="bg-danger text-white p-3 rounded">
                                            <h4 id="problemPayers">0</h4>
                                            <small>Problemli Ödeyenler</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="chart-container">
                                <h6 class="mb-3">AI Destekli Öngörüler & Aksiyon Önerileri</h6>
                                <div id="aiRecommendations" class="row">
                                    <div class="col-md-4">
                                        <div class="alert alert-info border-0">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-robot fa-2x me-3 text-primary"></i>
                                                <div>
                                                    <h6 class="mb-1">Tahsilat Stratejisi</h6>
                                                    <p class="mb-0 small">AI analizi sonucu optimal tahsilat zamanlaması önerileri</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="alert alert-warning border-0">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-exclamation-triangle fa-2x me-3 text-warning"></i>
                                                <div>
                                                    <h6 class="mb-1">Risk Uyarıları</h6>
                                                    <p class="mb-0 small">Potansiel ödeme problemleri tespit edildi</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="alert alert-success border-0">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-chart-line fa-2x me-3 text-success"></i>
                                                <div>
                                                    <h6 class="mb-1">Büyüme Fırsatları</h6>
                                                    <p class="mb-0 small">Ciro artış potansiyeli olan müşteriler</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mt-4">
                                    <h6 class="mb-3">Detaylı Risk Analizi Tablosu</h6>
                                    <div id="riskAnalysisTable" class="table-responsive"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns/dist/chartjs-adapter-date-fns.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Initialize charts and data
    setTimeout(() => loadDashboardData(), 500); // Delay for better UX
    setupEventListeners();
    
    // Auto-load customer options
    setTimeout(() => loadCustomerOptions(), 1000);
    
    // Start real-time updates
    setTimeout(() => startRealTimeUpdates(), 2000);
    
    function setupEventListeners() {
        // Statement report
        document.getElementById('run-statement').addEventListener('click', generateStatementReport);
        
        // Ageing analysis
        document.getElementById('run-ageing').addEventListener('click', generateAgeingReport);
        
        // Cash flow projection
        document.getElementById('run-cashflow').addEventListener('click', generateCashflowReport);
        
        // Analytics
        document.getElementById('run-analytics').addEventListener('click', generateAnalyticsReport);
        
        // Export buttons
        document.getElementById('export-statement-pdf').addEventListener('click', () => exportReport('statement', 'pdf'));
        document.getElementById('export-statement-excel').addEventListener('click', () => exportReport('statement', 'excel'));
    }
    
    async function loadDashboardData() {
        try {
            // Load payment trends
            const trendsResponse = await fetch('{{ route("finance.accounts.reports.data") }}?type=payment_trends');
            const trendsData = await trendsResponse.json();
            
            if (trendsData.ok) {
                renderTrendsChart(trendsData.data);
            }
            
            // Load customer ranking
            const rankingResponse = await fetch('{{ route("finance.accounts.reports.data") }}?type=customer_ranking');
            const rankingData = await rankingResponse.json();
            
            if (rankingData.ok) {
                renderCustomerRanking(rankingData.data);
            }
            
        } catch (error) {
            console.error('Dashboard veri yükleme hatası:', error);
        }
    }
    
    function renderTrendsChart(data) {
        // Hide loading, show chart
        document.getElementById('trendsChartLoading').style.display = 'none';
        document.getElementById('trendsChart').style.display = 'block';
        
        const ctx = document.getElementById('trendsChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.map(d => d.month_name),
                datasets: [{
                    label: 'Faturalanan',
                    data: data.map(d => d.invoiced),
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    tension: 0.4
                }, {
                    label: 'Tahsil Edilen',
                    data: data.map(d => d.collected),
                    borderColor: '#38ef7d',
                    backgroundColor: 'rgba(56, 239, 125, 0.1)',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '₺' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    }
    
    function renderCustomerRanking(data) {
        const container = document.getElementById('customerRankingTable');
        let html = `
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Sıra</th>
                            <th>Müşteri</th>
                            <th>Toplam Ciro</th>
                            <th>Ödeme Oranı</th>
                            <th>Kalan Bakiye</th>
                            <th>Fatura Sayısı</th>
                        </tr>
                    </thead>
                    <tbody>
        `;
        
        data.forEach((customer, index) => {
            html += `
                <tr>
                    <td><span class="badge bg-primary">${index + 1}</span></td>
                    <td><strong>${customer.name}</strong></td>
                    <td>₺${customer.total_revenue.toLocaleString()}</td>
                    <td>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-success" style="width: ${customer.payment_rate}%"></div>
                        </div>
                        <small>${customer.payment_rate.toFixed(1)}%</small>
                    </td>
                    <td class="${customer.outstanding > 0 ? 'text-danger' : 'text-success'}">
                        ₺${customer.outstanding.toLocaleString()}
                    </td>
                    <td>${customer.invoice_count}</td>
                </tr>
            `;
        });
        
        html += '</tbody></table></div>';
        container.innerHTML = html;
    }
    
    async function loadCustomerOptions() {
        try {
            const response = await fetch('{{ route("finance.accounts.reports.data") }}?type=balance_list&party_type=customers');
            const data = await response.json();
            
            if (data.ok) {
                const select = document.getElementById('statement-party');
                data.data.forEach(customer => {
                    const option = document.createElement('option');
                    option.value = customer.code;
                    option.textContent = customer.title;
                    select.appendChild(option);
                });
            }
        } catch (error) {
            console.error('Müşteri listesi yükleme hatası:', error);
        }
    }
    
    async function generateStatementReport() {
        const partyId = document.getElementById('statement-party').value;
        const startDate = document.getElementById('statement-start').value;
        const endDate = document.getElementById('statement-end').value;
        
        if (!partyId) {
            alert('Lütfen bir cari hesap seçin');
            return;
        }
        
        const resultsContainer = document.getElementById('statement-results');
        resultsContainer.innerHTML = '<div class="text-center p-4"><div class="loading-spinner"></div><p class="mt-2">Rapor oluşturuluyor...</p></div>';
        
        try {
            const params = new URLSearchParams({
                type: 'statement',
                party_id: partyId,
                start: startDate,
                end: endDate
            });
            
            const response = await fetch(`{{ route("finance.accounts.reports.data") }}?${params}`);
            const data = await response.json();
            
            if (data.ok) {
                renderStatementTable(data.data, data.meta);
            } else {
                resultsContainer.innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
            }
        } catch (error) {
            resultsContainer.innerHTML = '<div class="alert alert-danger">Rapor oluşturulurken hata oluştu</div>';
        }
    }
    
    function renderStatementTable(data, meta) {
        const container = document.getElementById('statement-results');
        let html = `
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Tarih</th>
                            <th>Fiş No</th>
                            <th>Tür</th>
                            <th>Açıklama</th>
                            <th class="text-end">Borç</th>
                            <th class="text-end">Alacak</th>
                            <th class="text-end">Bakiye</th>
                        </tr>
                    </thead>
                    <tbody>
        `;
        
        data.forEach(row => {
            html += `
                <tr>
                    <td>${row.date || ''}</td>
                    <td>${row.no || ''}</td>
                    <td><span class="badge bg-${row.type === 'Invoice' ? 'primary' : 'success'}">${row.type}</span></td>
                    <td>${row.desc || ''}</td>
                    <td class="text-end">${row.debit ? '₺' + parseFloat(row.debit).toLocaleString() : ''}</td>
                    <td class="text-end">${row.credit ? '₺' + parseFloat(row.credit).toLocaleString() : ''}</td>
                    <td class="text-end fw-bold ${parseFloat(row.balance) >= 0 ? 'text-success' : 'text-danger'}">
                        ₺${parseFloat(row.balance).toLocaleString()}
                    </td>
                </tr>
            `;
        });
        
        html += '</tbody></table></div>';
        
        if (meta) {
            html += `
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>Toplam ${meta.total} kayıt</div>
                    <div>
                        <button class="btn btn-sm btn-outline-secondary" ${meta.current_page <= 1 ? 'disabled' : ''}>
                            ‹ Önceki
                        </button>
                        <span class="mx-2">${meta.current_page} / ${meta.last_page}</span>
                        <button class="btn btn-sm btn-outline-secondary" ${meta.current_page >= meta.last_page ? 'disabled' : ''}>
                            Sonraki ›
                        </button>
                    </div>
                </div>
            `;
        }
        
        container.innerHTML = html;
    }
    
    async function generateAgeingReport() {
        const type = document.getElementById('ageing-type').value;
        const date = document.getElementById('ageing-date').value;
        
        try {
            const params = new URLSearchParams({ type, date });
            const response = await fetch(`{{ route("finance.accounts.reports.data") }}?${params}`);
            const data = await response.json();
            
            if (data.ok) {
                renderAgeingChart(data.data);
                renderAgeingSummary(data.data);
                if (data.detailed) {
                    renderAgeingDetails(data.detailed);
                }
            }
        } catch (error) {
            console.error('Yaşlandırma raporu hatası:', error);
        }
    }
    
    function renderAgeingChart(data) {
        const ctx = document.getElementById('ageingChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Vadesi Gelmemiş', '1-30 Gün', '31-60 Gün', '61-90 Gün', '90+ Gün'],
                datasets: [{
                    data: [data.current, data['1_30'], data['31_60'], data['61_90'], data['90_plus']],
                    backgroundColor: ['#28a745', '#ffc107', '#fd7e14', '#dc3545', '#6f42c1']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }
    
    function renderAgeingSummary(data) {
        const container = document.getElementById('ageing-summary');
        const total = Object.values(data).reduce((sum, val) => sum + val, 0);
        
        let html = `
            <div class="row">
                <div class="col-md-2">
                    <div class="text-center">
                        <h6 class="text-success">Vadesi Gelmemiş</h6>
                        <h4>₺${data.current.toLocaleString()}</h4>
                        <small>${total > 0 ? ((data.current / total) * 100).toFixed(1) : 0}%</small>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="text-center">
                        <h6 class="text-warning">1-30 Gün</h6>
                        <h4>₺${data['1_30'].toLocaleString()}</h4>
                        <small>${total > 0 ? ((data['1_30'] / total) * 100).toFixed(1) : 0}%</small>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="text-center">
                        <h6 class="text-info">31-60 Gün</h6>
                        <h4>₺${data['31_60'].toLocaleString()}</h4>
                        <small>${total > 0 ? ((data['31_60'] / total) * 100).toFixed(1) : 0}%</small>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="text-center">
                        <h6 class="text-primary">61-90 Gün</h6>
                        <h4>₺${data['61_90'].toLocaleString()}</h4>
                        <small>${total > 0 ? ((data['61_90'] / total) * 100).toFixed(1) : 0}%</small>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="text-center">
                        <h6 class="text-danger">90+ Gün</h6>
                        <h4>₺${data['90_plus'].toLocaleString()}</h4>
                        <small>${total > 0 ? ((data['90_plus'] / total) * 100).toFixed(1) : 0}%</small>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="text-center">
                        <h6 class="text-dark">TOPLAM</h6>
                        <h4>₺${total.toLocaleString()}</h4>
                        <small>100%</small>
                    </div>
                </div>
            </div>
        `;
        
        container.innerHTML = html;
    }
    
    function renderAgeingDetails(detailedData) {
        const container = document.getElementById('ageing-details');
        
        if (!detailedData.length) {
            container.innerHTML = '<div class="text-center text-muted p-4">Detaylı veri bulunamadı</div>';
            return;
        }
        
        let html = `
            <div class="table-responsive">
                <table class="table table-sm table-striped">
                    <thead>
                        <tr>
                            <th>Cari</th>
                            <th>Fatura No</th>
                            <th>Tarih</th>
                            <th>Vade</th>
                            <th class="text-end">Tutar</th>
                            <th class="text-end">Gecikme</th>
                            <th>Kategori</th>
                        </tr>
                    </thead>
                    <tbody>
        `;
        
        detailedData.forEach(row => {
            const badgeClass = {
                'current': 'success',
                '1_30': 'warning',
                '31_60': 'info',
                '61_90': 'primary',
                '90_plus': 'danger'
            }[row.bucket] || 'secondary';
            
            html += `
                <tr>
                    <td><strong>${row.party_name}</strong></td>
                    <td>${row.invoice_no}</td>
                    <td>${row.date}</td>
                    <td>${row.due_date || 'N/A'}</td>
                    <td class="text-end">₺${row.amount.toLocaleString()}</td>
                    <td class="text-end">${row.days_overdue} gün</td>
                    <td><span class="badge bg-${badgeClass}">${row.bucket.replace('_', '-')}</span></td>
                </tr>
            `;
        });
        
        html += '</tbody></table></div>';
        container.innerHTML = html;
    }
    
    async function generateCashflowReport() {
        const period = document.getElementById('cashflow-period').value;
        
        try {
            const response = await fetch(`{{ route("finance.accounts.reports.data") }}?type=cash_flow&period=${period}`);
            const data = await response.json();
            
            if (data.ok) {
                renderCashflowChart(data.data);
                renderCashflowTable(data.data);
            }
        } catch (error) {
            console.error('Nakit akış raporu hatası:', error);
        }
    }
    
    function renderCashflowChart(data) {
        const ctx = document.getElementById('cashflowChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: data.map(d => d.month_name),
                datasets: [{
                    label: 'Nakit Girişi',
                    data: data.map(d => d.inflow),
                    backgroundColor: 'rgba(40, 167, 69, 0.8)',
                    borderColor: '#28a745',
                    borderWidth: 1
                }, {
                    label: 'Nakit Çıkışı',
                    data: data.map(d => d.outflow),
                    backgroundColor: 'rgba(220, 53, 69, 0.8)',
                    borderColor: '#dc3545',
                    borderWidth: 1
                }, {
                    label: 'Net Akış',
                    data: data.map(d => d.net_flow),
                    type: 'line',
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(0, 123, 255, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '₺' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    }
    
    function renderCashflowTable(data) {
        const container = document.getElementById('cashflow-table');
        let html = `
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Ay</th>
                            <th class="text-end">Nakit Girişi</th>
                            <th class="text-end">Nakit Çıkışı</th>
                            <th class="text-end">Net Akış</th>
                            <th class="text-end">Kümülatif</th>
                        </tr>
                    </thead>
                    <tbody>
        `;
        
        let cumulative = 0;
        data.forEach(row => {
            cumulative += row.net_flow;
            html += `
                <tr>
                    <td><strong>${row.month_name}</strong></td>
                    <td class="text-end text-success">₺${row.inflow.toLocaleString()}</td>
                    <td class="text-end text-danger">₺${row.outflow.toLocaleString()}</td>
                    <td class="text-end ${row.net_flow >= 0 ? 'text-success' : 'text-danger'}">
                        ₺${row.net_flow.toLocaleString()}
                    </td>
                    <td class="text-end fw-bold ${cumulative >= 0 ? 'text-success' : 'text-danger'}">
                        ₺${cumulative.toLocaleString()}
                    </td>
                </tr>
            `;
        });
        
        html += '</tbody></table></div>';
        container.innerHTML = html;
    }
    
    async function generateAnalyticsReport() {
        const type = document.getElementById('analytics-type').value;
        const period = document.getElementById('analytics-period').value;
        
        try {
            if (type === 'risk') {
                await loadRiskAnalysis();
            } else if (type === 'performance') {
                await loadPerformanceMetrics();
            } else if (type === 'behavior') {
                await loadBehaviorAnalysis();
            }
        } catch (error) {
            console.error('Analitik rapor hatası:', error);
        }
    }
    
    async function loadRiskAnalysis() {
        try {
            const response = await fetch('{{ route("finance.accounts.reports.data") }}?type=risk_analysis');
            const data = await response.json();
            
            if (data.ok) {
                renderRiskDistribution(data.data);
                renderRiskAnalysisTable(data.data);
                updatePaymentMatrix(data.data);
            }
        } catch (error) {
            console.error('Risk analizi hatası:', error);
        }
    }
    
    async function loadPerformanceMetrics() {
        try {
            const response = await fetch('{{ route("finance.accounts.reports.data") }}?type=performance_metrics');
            const data = await response.json();
            
            if (data.ok) {
                renderPerformanceChart(data.data.collection_metrics);
                renderPaymentPatterns(data.data.payment_patterns);
            }
        } catch (error) {
            console.error('Performans analizi hatası:', error);
        }
    }
    
    function renderRiskDistribution(riskData) {
        const riskLevels = {high: 0, medium: 0, low: 0};
        riskData.forEach(item => {
            riskLevels[item.risk_level]++;
        });
        
        const ctx = document.getElementById('riskDistributionChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Yüksek Risk', 'Orta Risk', 'Düşük Risk'],
                datasets: [{
                    data: [riskLevels.high, riskLevels.medium, riskLevels.low],
                    backgroundColor: ['#dc3545', '#ffc107', '#28a745']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }
    
    function renderRiskAnalysisTable(riskData) {
        const container = document.getElementById('riskAnalysisTable');
        
        if (!riskData.length) {
            container.innerHTML = '<div class="text-center text-muted p-4">Risk analizi verisi bulunamadı</div>';
            return;
        }
        
        let html = `
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Müşteri</th>
                        <th class="text-end">Toplam Bakiye</th>
                        <th class="text-end">Vadesi Geçen</th>
                        <th class="text-center">Risk Skoru</th>
                        <th class="text-center">Risk Seviyesi</th>
                        <th class="text-center">Öneri</th>
                    </tr>
                </thead>
                <tbody>
        `;
        
        riskData.slice(0, 15).forEach(item => {
            const badgeClass = {
                'high': 'danger',
                'medium': 'warning', 
                'low': 'success'
            }[item.risk_level];
            
            const recommendation = item.risk_level === 'high' ? 'Acil Takip' : 
                                 item.risk_level === 'medium' ? 'Yakın İzleme' : 'Rutin Takip';
            
            html += `
                <tr>
                    <td><strong>${item.customer_name}</strong></td>
                    <td class="text-end">₺${item.total_outstanding.toLocaleString()}</td>
                    <td class="text-end text-danger">₺${item.overdue_amount.toLocaleString()}</td>
                    <td class="text-center">
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-${badgeClass}" style="width: ${item.risk_score}%"></div>
                        </div>
                        <small>${item.risk_score.toFixed(0)}</small>
                    </td>
                    <td class="text-center">
                        <span class="badge bg-${badgeClass}">${item.risk_level.toUpperCase()}</span>
                    </td>
                    <td class="text-center">
                        <small class="text-muted">${recommendation}</small>
                    </td>
                </tr>
            `;
        });
        
        html += '</tbody></table>';
        container.innerHTML = html;
    }
    
    function updatePaymentMatrix(riskData) {
        const excellent = riskData.filter(item => item.risk_score < 20).length;
        const average = riskData.filter(item => item.risk_score >= 20 && item.risk_score < 40).length;
        const late = riskData.filter(item => item.risk_score >= 40 && item.risk_score < 70).length;
        const problem = riskData.filter(item => item.risk_score >= 70).length;
        
        document.getElementById('excellentPayers').textContent = excellent;
        document.getElementById('averagePayers').textContent = average;
        document.getElementById('latePayers').textContent = late;
        document.getElementById('problemPayers').textContent = problem;
    }
    
    function renderPerformanceChart(collectionMetrics) {
        const ctx = document.getElementById('analyticsMainChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: collectionMetrics.map(d => d.month_name),
                datasets: [{
                    label: 'Tahsilat Etkinliği (%)',
                    data: collectionMetrics.map(d => d.efficiency),
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            callback: function(value) {
                                return value + '%';
                            }
                        }
                    }
                }
            }
        });
        
        document.getElementById('analytics-title').textContent = 'Aylık Tahsilat Etkinliği Trendi';
    }
    
    async function loadBehaviorAnalysis() {
        // Placeholder for behavior analysis
        const ctx = document.getElementById('analyticsMainChart').getContext('2d');
        
        // Sample behavior data
        const behaviorData = {
            labels: ['Hızlı Ödeyenler', 'Normal Ödeyenler', 'Yavaş Ödeyenler', 'Sorunlu Ödeyenler'],
            datasets: [{
                data: [25, 45, 20, 10],
                backgroundColor: ['#28a745', '#17a2b8', '#ffc107', '#dc3545']
            }]
        };
        
        new Chart(ctx, {
            type: 'bar',
            data: behaviorData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value + '%';
                            }
                        }
                    }
                }
            }
        });
        
        document.getElementById('analytics-title').textContent = 'Müşteri Ödeme Davranış Dağılımı';
    }
    
    function startRealTimeUpdates() {
        // Update notifications every 5 minutes
        setInterval(function() {
            const notificationEl = document.getElementById('realTimeNotifications');
            const now = new Date();
            const timeString = now.getHours().toString().padStart(2, '0') + ':' + 
                             now.getMinutes().toString().padStart(2, '0');
            
            notificationEl.querySelector('small').textContent = `Son güncelleme: ${timeString}`;
            
            // Simulate new notifications
            if (Math.random() < 0.3) { // 30% chance
                showNewNotification();
            }
        }, 300000); // 5 minutes
    }
    
    function showNewNotification() {
        const notifications = [
            { type: 'warning', message: 'Yeni vadesi geçen fatura tespit edildi' },
            { type: 'info', message: 'Büyük tutarlı ödeme alındı' },
            { type: 'success', message: 'Tahsilat hedefi güncellendi' }
        ];
        
        const notification = notifications[Math.floor(Math.random() * notifications.length)];
        
        // Create toast notification
        const toast = document.createElement('div');
        toast.className = `alert alert-${notification.type} alert-dismissible fade show position-fixed`;
        toast.style.cssText = 'top: 20px; right: 20px; z-index: 1060; min-width: 300px;';
        toast.innerHTML = `
            <i class="fas fa-info-circle me-2"></i>
            ${notification.message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(toast);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 5000);
    }
    
    function exportReport(type, format) {
        // Show loading state
        const btn = event.target;
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Hazırlanıyor...';
        btn.disabled = true;
        
        // Simulate export process
        setTimeout(() => {
            btn.innerHTML = originalText;
            btn.disabled = false;
            
            // Simulate download
            const link = document.createElement('a');
            link.href = '#';
            link.download = `${type}_report.${format}`;
            link.click();
            
            // Show success notification
            showNewNotification();
        }, 2000);
    }
});
</script>
@endpush