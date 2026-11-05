@extends('layouts.master')
@section('title', 'POS Terminal Yönetimi')
@section('topbar-title', 'Finans - Kasa & Banka')

@section('styles')
<style>
.pos-card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}
.pos-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.15);
}
.metric-card {
    background: #fff;
    border-radius: 15px;
    padding: 1.5rem;
    border-left: 5px solid #007bff;
    transition: all 0.3s ease;
    height: 100%;
}
.metric-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 25px rgba(0,123,255,0.1);
}
.metric-value {
    font-size: 2.5rem;
    font-weight: bold;
    color: #007bff;
    margin: 0;
}
.metric-label {
    color: #6c757d;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 0.5rem;
}
.terminal-status {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    display: inline-block;
    margin-right: 8px;
}
.status-online { background: #28a745; }
.status-offline { background: #dc3545; }
.status-maintenance { background: #ffc107; }
.transaction-item {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 1rem;
    margin-bottom: 0.5rem;
    border-left: 4px solid #007bff;
    transition: all 0.3s ease;
}
.transaction-item:hover {
    background: #e9ecef;
    transform: translateX(5px);
}
.payment-method-card {
    background: #fff;
    border-radius: 12px;
    padding: 1.5rem;
    text-align: center;
    transition: all 0.3s ease;
    border: 2px solid #e9ecef;
}
.payment-method-card:hover {
    border-color: #007bff;
    transform: translateY(-3px);
    box-shadow: 0 5px 20px rgba(0,123,255,0.1);
}
.chart-container {
    background: #fff;
    border-radius: 15px;
    padding: 1.5rem;
    box-shadow: 0 2px 15px rgba(0,0,0,0.05);
    position: relative;
}
.chart-wrapper {
    position: relative;
    height: 300px;
    width: 100%;
}
.chart-wrapper canvas {
    position: absolute;
    top: 0;
    left: 0;
    width: 100% !important;
    height: 100% !important;
}
.filter-tabs .nav-link {
    border-radius: 25px;
    padding: 0.5rem 1.5rem;
    margin-right: 0.5rem;
    background: #f8f9fa;
    border: none;
    color: #6c757d;
    transition: all 0.3s ease;
}
.filter-tabs .nav-link.active {
    background: #007bff;
    color: white;
}
.terminal-card {
    background: #fff;
    border-radius: 12px;
    padding: 1.5rem;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
}
.terminal-card.online {
    border-color: #28a745;
    background: linear-gradient(135deg, #d4edda 0%, #f0fff4 100%);
}
.terminal-card.offline {
    border-color: #dc3545;
    background: linear-gradient(135deg, #f8d7da 0%, #fff5f5 100%);
}
.progress-ring {
    width: 80px;
    height: 80px;
}
.progress-ring__circle {
    stroke: #007bff;
    stroke-linecap: round;
    transition: stroke-dasharray 0.35s;
    transform: rotate(-90deg);
    transform-origin: 50% 50%;
}
.alert-custom {
    border-radius: 10px;
    border: none;
    padding: 1rem 1.5rem;
}
.btn-custom {
    border-radius: 25px;
    padding: 0.5rem 1.5rem;
    font-weight: 500;
    transition: all 0.3s ease;
}
.btn-custom:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}
.live-indicator {
    width: 8px;
    height: 8px;
    background: #28a745;
    border-radius: 50%;
    animation: pulse 2s infinite;
}
@keyframes pulse {
    0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.7); }
    70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(40, 167, 69, 0); }
    100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(40, 167, 69, 0); }
}
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

/* Chart responsive fixes */
@media (max-width: 768px) {
    .chart-wrapper {
        height: 250px;
    }
    .chart-container {
        margin-bottom: 1rem;
    }
}

@media (max-width: 576px) {
    .chart-wrapper {
        height: 200px;
    }
}

/* Fix for chart containers */
.chart-container .chart-wrapper {
    overflow: hidden;
}
</style>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="pos-card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <i class="fas fa-credit-card fa-3x"></i>
                                    </div>
                                    <div>
                                        <h2 class="mb-1">POS Terminal Yönetimi</h2>
                                        <p class="mb-0 opacity-75">Tüm POS terminallerinizi tek noktadan yönetin ve satış performansınızı takip edin</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 text-end">
                                <div class="live-indicator d-inline-block me-2"></div>
                                <span class="small">Canlı Takip</span>
                                <div class="mt-2">
                                    <button class="btn btn-light btn-custom me-2">
                                        <i class="fas fa-plus me-1"></i>Terminal Ekle
                                    </button>
                                    <button class="btn btn-outline-light btn-custom">
                                        <i class="fas fa-cog me-1"></i>Ayarlar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Overview -->
        <div class="stats-grid">
            <div class="metric-card">
                <div class="metric-label">Bugünkü Satış</div>
                <div class="metric-value" id="dailySales">₺45,280</div>
                <div class="text-success mt-2">
                    <i class="fas fa-arrow-up me-1"></i>
                    <small>%12.5 artış</small>
                </div>
            </div>
            <div class="metric-card">
                <div class="metric-label">İşlem Sayısı</div>
                <div class="metric-value text-success" id="transactionCount">248</div>
                <div class="text-info mt-2">
                    <i class="fas fa-clock me-1"></i>
                    <small>Son işlem: 2 dk önce</small>
                </div>
            </div>
            <div class="metric-card">
                <div class="metric-label">Aktif Terminal</div>
                <div class="metric-value text-warning" id="activeTerminals">7/8</div>
                <div class="text-muted mt-2">
                    <i class="fas fa-exclamation-triangle me-1"></i>
                    <small>1 terminal bakımda</small>
                </div>
            </div>
            <div class="metric-card">
                <div class="metric-label">Ortalama İşlem</div>
                <div class="metric-value text-info" id="avgTransaction">₺182.58</div>
                <div class="text-success mt-2">
                    <i class="fas fa-arrow-up me-1"></i>
                    <small>%8.3 artış</small>
                </div>
            </div>
        </div>

        <!-- Filter Tabs -->
        <div class="row mb-4">
            <div class="col-12">
                <ul class="nav nav-pills filter-tabs" id="timePeriodTabs">
                    <li class="nav-item">
                        <a class="nav-link active" data-period="today" href="#today">Bugün</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-period="week" href="#week">Bu Hafta</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-period="month" href="#month">Bu Ay</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-period="year" href="#year">Bu Yıl</a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Charts and Analytics -->
        <div class="row mb-4">
            <div class="col-lg-8">
                <div class="chart-container">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">
                            <i class="fas fa-chart-line me-2 text-primary"></i>
                            Satış Grafiği
                        </h5>
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-outline-primary active" data-chart="sales">Satış</button>
                            <button class="btn btn-outline-primary" data-chart="transactions">İşlem</button>
                            <button class="btn btn-outline-primary" data-chart="average">Ortalama</button>
                        </div>
                    </div>
                    <div class="chart-wrapper">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="chart-container">
                    <h5 class="mb-3">
                        <i class="fas fa-chart-pie me-2 text-success"></i>
                        Ödeme Yöntemleri
                    </h5>
                    <div class="chart-wrapper">
                        <canvas id="paymentMethodsChart"></canvas>
                    </div>
                    <div class="mt-3">
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="text-primary fw-bold">65%</div>
                                <small class="text-muted">Kredi Kartı</small>
                            </div>
                            <div class="col-4">
                                <div class="text-success fw-bold">25%</div>
                                <small class="text-muted">Nakit</small>
                            </div>
                            <div class="col-4">
                                <div class="text-warning fw-bold">10%</div>
                                <small class="text-muted">Dijital</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- POS Terminals -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-desktop me-2"></i>
                            POS Terminalleri
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row" id="terminalsContainer">
                            <!-- Terminals will be loaded here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Live Transactions -->
        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-exchange-alt me-2"></i>
                                Canlı İşlemler
                            </h5>
                            <div class="live-indicator"></div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive" style="max-height: 400px;">
                            <table class="table table-hover mb-0" id="liveTransactionsTable">
                                <thead class="table-success sticky-top">
                                    <tr>
                                        <th>Zaman</th>
                                        <th>Terminal</th>
                                        <th>Ödeme Yöntemi</th>
                                        <th>Tutar</th>
                                        <th>Durum</th>
                                        <th>İşlem</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-bell me-2"></i>
                            Sistem Bildirimleri
                        </h5>
                    </div>
                    <div class="card-body" id="notificationsContainer">
                        <!-- Notifications will be loaded here -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Reports Section -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-file-alt me-2"></i>
                                Hızlı Raporlar
                            </h5>
                            <button class="btn btn-outline-light btn-sm btn-custom">
                                <i class="fas fa-download me-1"></i>
                                Tüm Raporları İndir
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="payment-method-card">
                                    <i class="fas fa-chart-bar fa-2x text-primary mb-2"></i>
                                    <h6>Günlük Rapor</h6>
                                    <button class="btn btn-primary btn-sm btn-custom">İndir</button>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="payment-method-card">
                                    <i class="fas fa-calendar-week fa-2x text-success mb-2"></i>
                                    <h6>Haftalık Rapor</h6>
                                    <button class="btn btn-success btn-sm btn-custom">İndir</button>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="payment-method-card">
                                    <i class="fas fa-calendar-alt fa-2x text-warning mb-2"></i>
                                    <h6>Aylık Rapor</h6>
                                    <button class="btn btn-warning btn-sm btn-custom">İndir</button>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="payment-method-card">
                                    <i class="fas fa-cog fa-2x text-info mb-2"></i>
                                    <h6>Özel Rapor</h6>
                                    <button class="btn btn-info btn-sm btn-custom">Oluştur</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// POS Management System - Namespace to avoid conflicts
(function() {
    'use strict';
    
    // Private variables
    var posSystem = {
        salesChart: null,
        paymentChart: null,
        currentPeriod: 'today',
        liveTransactions: [],
        terminals: []
    };

    // Initialize page
    document.addEventListener('DOMContentLoaded', function() {
        initializeCharts();
        loadTerminals();
        startLiveUpdates();
        initializeEventListeners();
        loadNotifications();
    });

function initializeEventListeners() {
        // Period filter tabs
        document.querySelectorAll('.filter-tabs .nav-link').forEach(tab => {
            tab.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelectorAll('.filter-tabs .nav-link').forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                posSystem.currentPeriod = this.dataset.period;
                updateCharts();
            });
        });

    // Chart type buttons
    document.querySelectorAll('[data-chart]').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('[data-chart]').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            updateSalesChart(this.dataset.chart);
        });
    });
}

function initializeCharts() {
        // Sales Chart
        const salesCtx = document.getElementById('salesChart').getContext('2d');
        posSystem.salesChart = new Chart(salesCtx, {
        type: 'line',
        data: {
            labels: ['09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00'],
            datasets: [{
                label: 'Satış (₺)',
                data: [1200, 1900, 3000, 2500, 4200, 3800, 5100, 4600, 5800, 6200],
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointRadius: 6,
                pointHoverRadius: 8,
                pointBackgroundColor: '#007bff',
                pointBorderColor: '#fff',
                pointBorderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            aspectRatio: 2,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0,0,0,0.1)'
                    },
                    ticks: {
                        callback: function(value) {
                            return '₺' + value.toLocaleString();
                        }
                    }
                },
                x: {
                    grid: {
                        color: 'rgba(0,0,0,0.1)'
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            }
        }
    });

        // Payment Methods Chart
        const paymentCtx = document.getElementById('paymentMethodsChart').getContext('2d');
        posSystem.paymentChart = new Chart(paymentCtx, {
        type: 'doughnut',
        data: {
            labels: ['Kredi Kartı', 'Nakit', 'Dijital Ödemeler'],
            datasets: [{
                data: [65, 25, 10],
                backgroundColor: ['#007bff', '#28a745', '#ffc107'],
                borderWidth: 3,
                borderColor: '#fff',
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            aspectRatio: 1,
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

function updateCharts() {
        // Update charts based on selected period
        const periodData = getPeriodData(posSystem.currentPeriod);
        
        posSystem.salesChart.data.labels = periodData.labels;
        posSystem.salesChart.data.datasets[0].data = periodData.sales;
        posSystem.salesChart.update('active');
    
    // Update stats
    updateStats(periodData);
}

function updateSalesChart(type) {
        const periodData = getPeriodData(posSystem.currentPeriod);
    let data, label, color;
    
    switch(type) {
        case 'transactions':
            data = periodData.transactions;
            label = 'İşlem Sayısı';
            color = '#28a745';
            break;
        case 'average':
            data = periodData.average;
            label = 'Ortalama (₺)';
            color = '#ffc107';
            break;
        default:
            data = periodData.sales;
            label = 'Satış (₺)';
            color = '#007bff';
    }
    
        posSystem.salesChart.data.datasets[0].data = data;
        posSystem.salesChart.data.datasets[0].label = label;
        posSystem.salesChart.data.datasets[0].borderColor = color;
        posSystem.salesChart.data.datasets[0].backgroundColor = color + '20';
        posSystem.salesChart.data.datasets[0].pointBackgroundColor = color;
        posSystem.salesChart.update('active');
}

function getPeriodData(period) {
    const data = {
        today: {
            labels: ['09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00'],
            sales: [1200, 1900, 3000, 2500, 4200, 3800, 5100, 4600, 5800, 6200],
            transactions: [8, 12, 18, 15, 25, 22, 30, 28, 35, 38],
            average: [150, 158, 167, 167, 168, 173, 170, 164, 166, 163]
        },
        week: {
            labels: ['Pzt', 'Sal', 'Çar', 'Per', 'Cum', 'Cmt', 'Paz'],
            sales: [25000, 28000, 32000, 35000, 42000, 38000, 30000],
            transactions: [180, 195, 220, 240, 285, 260, 210],
            average: [139, 144, 145, 146, 147, 146, 143]
        },
        month: {
            labels: ['Hf1', 'Hf2', 'Hf3', 'Hf4'],
            sales: [185000, 220000, 195000, 240000],
            transactions: [1200, 1450, 1380, 1620],
            average: [154, 152, 141, 148]
        },
        year: {
            labels: ['Oca', 'Şub', 'Mar', 'Nis', 'May', 'Haz', 'Tem', 'Ağu', 'Eyl', 'Eki', 'Kas', 'Ara'],
            sales: [850000, 920000, 1100000, 980000, 1200000, 1350000, 1450000, 1380000, 1250000, 1420000, 1150000, 1300000],
            transactions: [5200, 5800, 6500, 6100, 7200, 7800, 8200, 7900, 7400, 8100, 6900, 7600],
            average: [163, 159, 169, 161, 167, 173, 177, 175, 169, 175, 167, 171]
        }
    };
    
    return data[period] || data.today;
}

function updateStats(data) {
    const totalSales = data.sales.reduce((a, b) => a + b, 0);
    const totalTransactions = data.transactions.reduce((a, b) => a + b, 0);
    const avgTransaction = totalSales / totalTransactions;
    
    document.getElementById('dailySales').textContent = '₺' + totalSales.toLocaleString();
    document.getElementById('transactionCount').textContent = totalTransactions.toLocaleString();
    document.getElementById('avgTransaction').textContent = '₺' + Math.round(avgTransaction).toLocaleString();
}

function loadTerminals() {
        posSystem.terminals = [
        { id: 1, name: 'Terminal 1 - Kasa Ana', status: 'online', location: 'Ana Kasa', lastTransaction: '2 dk önce', dailySales: 8500 },
        { id: 2, name: 'Terminal 2 - Kasa Yan', status: 'online', location: 'Yan Kasa', lastTransaction: '5 dk önce', dailySales: 6200 },
        { id: 3, name: 'Terminal 3 - Mobil', status: 'online', location: 'Mobil POS', lastTransaction: '1 dk önce', dailySales: 4800 },
        { id: 4, name: 'Terminal 4 - Self Servis', status: 'maintenance', location: 'Self Servis', lastTransaction: '2 saat önce', dailySales: 0 },
        { id: 5, name: 'Terminal 5 - Drive Thru', status: 'online', location: 'Arabalı Servis', lastTransaction: '30 sn önce', dailySales: 12500 },
        { id: 6, name: 'Terminal 6 - VIP', status: 'online', location: 'VIP Salon', lastTransaction: '15 dk önce', dailySales: 9800 },
        { id: 7, name: 'Terminal 7 - Online', status: 'online', location: 'Web Mağaza', lastTransaction: '3 dk önce', dailySales: 5400 },
        { id: 8, name: 'Terminal 8 - Yedek', status: 'offline', location: 'Yedek Terminal', lastTransaction: '6 saat önce', dailySales: 0 }
    ];
    
    renderTerminals();
}

function renderTerminals() {
        const container = document.getElementById('terminalsContainer');
        container.innerHTML = '';
        
        posSystem.terminals.forEach(terminal => {
        const statusClass = terminal.status === 'online' ? 'online' : 
                           terminal.status === 'maintenance' ? 'maintenance' : 'offline';
        const statusIcon = terminal.status === 'online' ? 'fa-check-circle text-success' :
                          terminal.status === 'maintenance' ? 'fa-tools text-warning' : 'fa-times-circle text-danger';
        
        const terminalHtml = `
            <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                <div class="terminal-card ${statusClass}">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h6 class="mb-0">${terminal.name}</h6>
                        <i class="fas ${statusIcon}"></i>
                    </div>
                    <p class="text-muted small mb-2">${terminal.location}</p>
                    <div class="row">
                        <div class="col-6">
                            <div class="small text-muted">Günlük Satış</div>
                            <div class="fw-bold">₺${terminal.dailySales.toLocaleString()}</div>
                        </div>
                        <div class="col-6">
                            <div class="small text-muted">Son İşlem</div>
                            <div class="fw-bold small">${terminal.lastTransaction}</div>
                        </div>
                    </div>
                    <div class="mt-2">
                        <button class="btn btn-sm btn-outline-primary me-1">
                            <i class="fas fa-chart-line"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-info me-1">
                            <i class="fas fa-cog"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-${terminal.status === 'online' ? 'warning' : 'success'}">
                            <i class="fas fa-power-off"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
        
        container.innerHTML += terminalHtml;
    });
}

function startLiveUpdates() {
        // Simulate live transaction updates
        setInterval(() => {
            addRandomTransaction();
            updateLiveTransactionsTable();
        }, 3000);
    
    // Update stats periodically
    setInterval(() => {
        updateRealTimeStats();
    }, 5000);
}

function addRandomTransaction() {
        const paymentMethods = ['Kredi Kartı', 'Nakit', 'Dijital Cüzdan', 'Çek'];
        const statuses = ['Başarılı', 'Başarılı', 'Başarılı', 'Beklemede', 'İptal'];
        const amounts = [25, 50, 75, 100, 150, 200, 250, 300, 450, 600, 850, 1200];
        
        const transaction = {
            id: Date.now(),
            time: new Date().toLocaleTimeString('tr-TR'),
            terminal: `Terminal ${Math.floor(Math.random() * 8) + 1}`,
            paymentMethod: paymentMethods[Math.floor(Math.random() * paymentMethods.length)],
            amount: amounts[Math.floor(Math.random() * amounts.length)],
            status: statuses[Math.floor(Math.random() * statuses.length)]
        };
        
        posSystem.liveTransactions.unshift(transaction);
        
        // Keep only last 20 transactions
        if (posSystem.liveTransactions.length > 20) {
            posSystem.liveTransactions = posSystem.liveTransactions.slice(0, 20);
        }
}

function updateLiveTransactionsTable() {
        const tbody = document.querySelector('#liveTransactionsTable tbody');
        tbody.innerHTML = '';
        
        posSystem.liveTransactions.forEach(transaction => {
        const statusClass = transaction.status === 'Başarılı' ? 'success' :
                           transaction.status === 'Beklemede' ? 'warning' : 'danger';
        
        const row = `
            <tr class="transaction-fade-in">
                <td>${transaction.time}</td>
                <td>${transaction.terminal}</td>
                <td>
                    <i class="fas ${getPaymentIcon(transaction.paymentMethod)} me-1"></i>
                    ${transaction.paymentMethod}
                </td>
                <td class="fw-bold">₺${transaction.amount.toLocaleString()}</td>
                <td>
                    <span class="badge bg-${statusClass}">${transaction.status}</span>
                </td>
                <td>
                    <button class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-eye"></i>
                    </button>
                </td>
            </tr>
        `;
        
        tbody.innerHTML += row;
    });
}

function getPaymentIcon(method) {
        switch(method) {
            case 'Kredi Kartı': return 'fa-credit-card text-primary';
            case 'Nakit': return 'fa-money-bill text-success';
            case 'Dijital Cüzdan': return 'fa-mobile-alt text-info';
            case 'Çek': return 'fa-file-invoice text-warning';
            default: return 'fa-question-circle text-muted';
        }
}

function updateRealTimeStats() {
        // Simulate real-time stat updates
    const currentSales = parseInt(document.getElementById('dailySales').textContent.replace(/[^\d]/g, ''));
    const newSales = currentSales + Math.floor(Math.random() * 1000);
    document.getElementById('dailySales').textContent = '₺' + newSales.toLocaleString();
    
    const currentTransactions = parseInt(document.getElementById('transactionCount').textContent);
    document.getElementById('transactionCount').textContent = (currentTransactions + Math.floor(Math.random() * 3)).toLocaleString();
}

function loadNotifications() {
        const notifications = [
        {
            type: 'success',
            title: 'Terminal Bağlantı Başarılı',
            message: 'Terminal 5 yeniden bağlandı',
            time: '2 dk önce'
        },
        {
            type: 'warning',
            title: 'Yüksek İşlem Hacmi',
            message: 'Terminal 1\'de normalden %200 fazla işlem',
            time: '15 dk önce'
        },
        {
            type: 'info',
            title: 'Günlük Rapor Hazır',
            message: 'Bugünün raporu indirilebilir',
            time: '1 saat önce'
        },
        {
            type: 'danger',
            title: 'Terminal Bakım Gerekli',
            message: 'Terminal 4 bakıma alındı',
            time: '2 saat önce'
        }
    ];
    
    const container = document.getElementById('notificationsContainer');
    container.innerHTML = '';
    
    notifications.forEach(notification => {
        const notificationHtml = `
            <div class="alert alert-${notification.type} alert-custom mb-2">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="alert-heading mb-1">${notification.title}</h6>
                        <p class="mb-1 small">${notification.message}</p>
                        <small class="text-muted">${notification.time}</small>
                    </div>
                    <button type="button" class="btn-close btn-close-sm"></button>
                </div>
            </div>
        `;
        
        container.innerHTML += notificationHtml;
    });
}

    // Utility functions
    function showAlert(message, type = 'info') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(alertDiv);
    
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
    }

    // Add CSS animation for new transactions
    const style = document.createElement('style');
    style.textContent = `
        .transaction-fade-in {
            animation: fadeIn 0.5s ease-in;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    `;
    document.head.appendChild(style);

})(); // End of namespace
</script>
@endsection