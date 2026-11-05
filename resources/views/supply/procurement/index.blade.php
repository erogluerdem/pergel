@extends('layouts.master')

@section('title')
    Tedarik Yönetimi
@endsection

@section('css')
<style>
/* Modern Procurement Dashboard Styles */
.procurement-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 15px;
    padding: 30px;
    margin-bottom: 30px;
    color: white;
}

.kpi-card {
    background: white;
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    border: none;
    position: relative;
    overflow: hidden;
    margin-bottom: 20px;
}

.kpi-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--accent-color, #007bff);
}

.kpi-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.15);
}

.kpi-card.primary::before { background: linear-gradient(90deg, #007bff, #0056b3); }
.kpi-card.success::before { background: linear-gradient(90deg, #28a745, #1e7e34); }
.kpi-card.warning::before { background: linear-gradient(90deg, #ffc107, #e0a800); }
.kpi-card.danger::before { background: linear-gradient(90deg, #dc3545, #bd2130); }
.kpi-card.info::before { background: linear-gradient(90deg, #17a2b8, #138496); }

.kpi-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: white;
    margin-bottom: 15px;
}

.kpi-icon.primary { background: linear-gradient(135deg, #007bff, #0056b3); }
.kpi-icon.success { background: linear-gradient(135deg, #28a745, #1e7e34); }
.kpi-icon.warning { background: linear-gradient(135deg, #ffc107, #e0a800); }
.kpi-icon.danger { background: linear-gradient(135deg, #dc3545, #bd2130); }
.kpi-icon.info { background: linear-gradient(135deg, #17a2b8, #138496); }

.kpi-value {
    font-size: 2.5rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 5px;
}

.kpi-label {
    font-size: 0.9rem;
    color: #7f8c8d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 10px;
}

.kpi-trend {
    font-size: 0.8rem;
    padding: 4px 8px;
    border-radius: 12px;
    font-weight: 600;
}

.kpi-trend.up {
    background: rgba(40, 167, 69, 0.1);
    color: #28a745;
}

.kpi-trend.down {
    background: rgba(220, 53, 69, 0.1);
    color: #dc3545;
}

.kpi-trend.stable {
    background: rgba(108, 117, 125, 0.1);
    color: #6c757d;
}

.card-modern {
    border: none;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    margin-bottom: 30px;
}

.card-modern .card-header {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    border: none;
    border-radius: 15px 15px 0 0;
    padding: 20px 25px;
}

.card-modern .card-body {
    padding: 25px;
}

.action-btn {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    border: none;
    padding: 12px 25px;
    border-radius: 25px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    margin-right: 15px;
    margin-bottom: 15px;
}

.action-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    color: white;
    text-decoration: none;
}

.status-badge {
    padding: 4px 12px;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
}

.status-badge.pending {
    background: rgba(255, 193, 7, 0.1);
    color: #ffc107;
    border: 1px solid rgba(255, 193, 7, 0.2);
}

.status-badge.approved {
    background: rgba(40, 167, 69, 0.1);
    color: #28a745;
    border: 1px solid rgba(40, 167, 69, 0.2);
}

.status-badge.completed {
    background: rgba(23, 162, 184, 0.1);
    color: #17a2b8;
    border: 1px solid rgba(23, 162, 184, 0.2);
}

.status-badge.rejected {
    background: rgba(220, 53, 69, 0.1);
    color: #dc3545;
    border: 1px solid rgba(220, 53, 69, 0.2);
}

/* Responsive */
@media (max-width: 768px) {
    .kpi-value {
        font-size: 2rem;
    }
    
    .action-btn {
        width: 100%;
        justify-content: center;
        margin-bottom: 10px;
    }
}
</style>
@endsection

@section('content')
<div class="container-fluid">
    
    <!-- Header Section -->
    <div class="procurement-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h2 class="mb-2">
                    <i class="fas fa-shopping-cart me-3"></i>
                    Tedarik Yönetimi Merkezi
                </h2>
                <p class="mb-0 opacity-75">
                    Satın alma süreçleri, vendor yönetimi ve tedarik zinciri optimizasyonu
                </p>
            </div>
            <div class="col-md-4 text-end">
                <div class="d-flex align-items-center justify-content-end">
                    <div class="me-3">
                        <small class="opacity-75">Son Güncelleme</small><br>
                        <strong>{{ now()->format('d.m.Y H:i') }}</strong>
                    </div>
                    <div class="bg-white bg-opacity-25 rounded-circle p-3">
                        <i class="fas fa-sync-alt fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mb-4">
        <a href="#" class="action-btn">
            <i class="fas fa-plus"></i>
            Yeni Talep Oluştur
        </a>
        <a href="#" class="action-btn">
            <i class="fas fa-list"></i>
            Tüm Talepler
        </a>
        <a href="#" class="action-btn">
            <i class="fas fa-shopping-bag"></i>
            Satın Alma Siparişleri
        </a>
        <a href="#" class="action-btn">
            <i class="fas fa-building"></i>
            Vendor Yönetimi
        </a>
    </div>

    <!-- KPI Dashboard -->
    <div class="row mb-4">
        <div class="col-xl-2 col-lg-4 col-md-6 mb-4">
            <div class="kpi-card primary">
                <div class="kpi-icon primary">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <div class="kpi-value">{{ $kpis['pending_requisitions'] ?? '0' }}</div>
                <div class="kpi-label">Bekleyen Talepler</div>
                <div class="kpi-trend up">
                    <i class="fas fa-arrow-up"></i> +12%
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-lg-4 col-md-6 mb-4">
            <div class="kpi-card success">
                <div class="kpi-icon success">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="kpi-value">{{ $kpis['approved_requisitions'] ?? '0' }}</div>
                <div class="kpi-label">Onaylanan Talepler</div>
                <div class="kpi-trend up">
                    <i class="fas fa-arrow-up"></i> +8%
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-lg-4 col-md-6 mb-4">
            <div class="kpi-card info">
                <div class="kpi-icon info">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="kpi-value">{{ $kpis['open_orders'] ?? '0' }}</div>
                <div class="kpi-label">Açık Siparişler</div>
                <div class="kpi-trend stable">
                    <i class="fas fa-minus"></i> 0%
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-lg-4 col-md-6 mb-4">
            <div class="kpi-card warning">
                <div class="kpi-icon warning">
                    <i class="fas fa-lira-sign"></i>
                </div>
                <div class="kpi-value">{{ number_format(($kpis['open_orders_value'] ?? 0) / 1000, 0) }}K</div>
                <div class="kpi-label">Açık Sipariş Tutarı</div>
                <div class="kpi-trend up">
                    <i class="fas fa-arrow-up"></i> +15%
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-lg-4 col-md-6 mb-4">
            <div class="kpi-card danger">
                <div class="kpi-icon danger">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="kpi-value">{{ $kpis['delayed_deliveries'] ?? '0' }}</div>
                <div class="kpi-label">Geciken Teslimatlar</div>
                <div class="kpi-trend down">
                    <i class="fas fa-arrow-down"></i> -5%
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-lg-4 col-md-6 mb-4">
            <div class="kpi-card success">
                <div class="kpi-icon success">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="kpi-value">{{ $kpis['avg_processing_time'] ?? '0' }}</div>
                <div class="kpi-label">Ort. İşlem Süresi (Gün)</div>
                <div class="kpi-trend down">
                    <i class="fas fa-arrow-down"></i> -3%
                </div>
            </div>
        </div>
    </div>

    <!-- Main Dashboard Content -->
    <div class="row">
        <!-- Spending Analysis -->
        <div class="col-xl-8 col-lg-12 mb-4">
            <div class="card-modern">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-pie me-2"></i>
                        Harcama Analizi
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="mb-3">Kategori Bazında Dağılım</h6>
                            <canvas id="spendingByCategory" style="height: 300px;"></canvas>
                        </div>
                        <div class="col-md-6">
                            <h6 class="mb-3">Aylık Trend</h6>
                            <canvas id="monthlySpendingTrend" style="height: 300px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Budget Overview -->
        <div class="col-xl-4 col-lg-12 mb-4">
            <div class="card-modern">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-wallet me-2"></i>
                        Bütçe Durumu
                    </h5>
                </div>
                <div class="card-body text-center">
                    <div class="mb-4">
                        <canvas id="budgetGauge" width="200" height="200"></canvas>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="border-end">
                                <h5 class="text-primary mb-1">{{ number_format(($budgetComparison['spent'] ?? 750000) / 1000, 0) }}K</h5>
                                <small class="text-muted">Harcanan</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h5 class="text-success mb-1">{{ number_format(($budgetComparison['remaining'] ?? 250000) / 1000, 0) }}K</h5>
                            <small class="text-muted">Kalan</small>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="progress" style="height: 12px;">
                            <div class="progress-bar bg-gradient" style="width: {{ $budgetComparison['utilization_rate'] ?? 75 }}%"></div>
                        </div>
                        <small class="text-muted mt-2 d-block">Kullanım Oranı: {{ $budgetComparison['utilization_rate'] ?? 75 }}%</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Requisitions & Purchase Orders -->
    <div class="row">
        <div class="col-xl-6 col-lg-12 mb-4">
            <div class="card-modern">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-clipboard-list me-2"></i>
                        Son Satın Alma Talepleri
                    </h5>
                    <a href="#" class="btn btn-sm btn-outline-light">
                        Tümünü Gör
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Talep No</th>
                                    <th>Proje</th>
                                    <th>Durum</th>
                                    <th>Tarih</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($requisitions) && $requisitions->count() > 0)
                                    @foreach($requisitions->take(5) as $req)
                                    <tr>
                                        <td><strong>#REQ-{{ str_pad($req->id, 4, '0', STR_PAD_LEFT) }}</strong></td>
                                        <td>{{ $req->project->name ?? 'N/A' }}</td>
                                        <td>
                                            <span class="status-badge {{ $req->status }}">
                                                {{ ucfirst($req->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $req->created_at->format('d.m.Y') }}</td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">
                                            Henüz satın alma talebi bulunmuyor.
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6 col-lg-12 mb-4">
            <div class="card-modern">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-shopping-bag me-2"></i>
                        Son Satın Alma Siparişleri
                    </h5>
                    <a href="#" class="btn btn-sm btn-outline-light">
                        Tümünü Gör
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Sipariş No</th>
                                    <th>Vendor</th>
                                    <th>Tutar</th>
                                    <th>Durum</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($purchaseOrders) && $purchaseOrders->count() > 0)
                                    @foreach($purchaseOrders->take(5) as $order)
                                    <tr>
                                        <td><strong>#PO-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</strong></td>
                                        <td>{{ $order->vendor->name ?? 'N/A' }}</td>
                                        <td><strong>{{ number_format($order->total_amount, 0, ',', '.') }} ₺</strong></td>
                                        <td>
                                            <span class="status-badge {{ $order->status }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">
                                            Henüz satın alma siparişi bulunmuyor.
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('script')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Sample data for charts
const spendingCategoryData = [
    {category: 'Malzeme', amount: 450000, color: '#667eea'},
    {category: 'Hizmet', amount: 320000, color: '#f093fb'},
    {category: 'Ekipman', amount: 280000, color: '#4facfe'},
    {category: 'Yazılım', amount: 180000, color: '#43e97b'}
];

const monthlyTrendData = [
    {month: 'Ocak', amount: 280000},
    {month: 'Şubat', amount: 320000},
    {month: 'Mart', amount: 390000},
    {month: 'Nisan', amount: 450000},
    {month: 'Mayıs', amount: 380000},
    {month: 'Haziran', amount: 420000}
];

// Spending by Category Chart
const spendingCtx = document.getElementById('spendingByCategory');
if (spendingCtx) {
    new Chart(spendingCtx, {
        type: 'doughnut',
        data: {
            labels: spendingCategoryData.map(item => item.category),
            datasets: [{
                data: spendingCategoryData.map(item => item.amount),
                backgroundColor: spendingCategoryData.map(item => item.color),
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
                        usePointStyle: true,
                        padding: 20
                    }
                }
            }
        }
    });
}

// Monthly Spending Trend
const trendCtx = document.getElementById('monthlySpendingTrend');
if (trendCtx) {
    new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: monthlyTrendData.map(item => item.month),
            datasets: [{
                label: 'Aylık Harcama',
                data: monthlyTrendData.map(item => item.amount),
                borderColor: '#667eea',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                fill: true,
                tension: 0.4
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
                    ticks: {
                        callback: function(value) {
                            return (value / 1000) + 'K ₺';
                        }
                    }
                }
            }
        }
    });
}

// Budget Gauge
const budgetCtx = document.getElementById('budgetGauge');
if (budgetCtx) {
    const utilization = {{ $budgetComparison['utilization_rate'] ?? 75 }};
    
    new Chart(budgetCtx, {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [utilization, 100 - utilization],
                backgroundColor: ['#667eea', '#f8f9fa'],
                borderWidth: 0,
                cutout: '75%'
            }]
        },
        options: {
            responsive: false,
            plugins: {
                legend: {
                    display: false
                }
            }
        },
        plugins: [{
            beforeDraw: function(chart) {
                const ctx = chart.ctx;
                ctx.save();
                const centerX = chart.getDatasetMeta(0).data[0].x;
                const centerY = chart.getDatasetMeta(0).data[0].y;
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                ctx.font = 'bold 24px Arial';
                ctx.fillStyle = '#667eea';
                ctx.fillText(utilization + '%', centerX, centerY);
                ctx.restore();
            }
        }]
    });
}

// Real-time updates
setInterval(function() {
    // Update KPIs every 30 seconds
    console.log('Dashboard data updated at:', new Date().toLocaleTimeString());
}, 30000);
</script>
@endsection