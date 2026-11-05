@extends('layouts.master')
@section('title', 'Stok Raporları')
@section('topbar-title', 'Finans - Stok Yönetimi')

@section('css')
<link href="{{ asset('libs/select2/dist/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet" type="text/css" />
<style>
.stats-card {
    border-radius: 10px;
    overflow: hidden;
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}
.stats-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}
.stats-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: white;
}
.chart-container {
    position: relative;
    height: 350px;
    background: #fff;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}
.status-badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
}
.status-critical { background: #fee2e2; color: #991b1b; }
.status-low { background: #fef3c7; color: #92400e; }
.status-normal { background: #dcfce7; color: #166534; }
.status-overstock { background: #dbeafe; color: #1e40af; }
.progress-custom {
    height: 8px;
    border-radius: 4px;
    overflow: hidden;
}
.table-responsive {
    border-radius: 10px;
    overflow: hidden;
}
.mini-chart {
    height: 60px;
    width: 100%;
}
.filter-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 10px;
}
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Stok Raporları</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Finans</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Stok Yönetimi</a></li>
                        <li class="breadcrumb-item active">Raporlar</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Genel İstatistikler -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card stats-card border-0">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <div>
                                <p class="text-muted fw-medium mb-2">Toplam Stok Değeri</p>
                                <h4 class="mb-0">₺{{ number_format($totalValue, 2, ',', '.') }}</h4>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="stats-icon" style="background: linear-gradient(45deg, #28a745, #20c997);">
                                <i class="mdi mdi-currency-try"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card stats-card border-0">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <div>
                                <p class="text-muted fw-medium mb-2">Toplam Ürün</p>
                                <h4 class="mb-0">{{ number_format($totalItems) }}</h4>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="stats-icon" style="background: linear-gradient(45deg, #007bff, #6610f2);">
                                <i class="mdi mdi-package-variant"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card stats-card border-0">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <div>
                                <p class="text-muted fw-medium mb-2">Kritik Stok</p>
                                <h4 class="mb-0 text-danger">{{ $criticalCount }}</h4>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="stats-icon" style="background: linear-gradient(45deg, #dc3545, #fd7e14);">
                                <i class="mdi mdi-alert-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card stats-card border-0">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <div>
                                <p class="text-muted fw-medium mb-2">Düşük Stok</p>
                                <h4 class="mb-0 text-warning">{{ $lowStockCount }}</h4>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="stats-icon" style="background: linear-gradient(45deg, #ffc107, #fd7e14);">
                                <i class="mdi mdi-alert-outline"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtreler -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card filter-card">
                <div class="card-body">
                    <h5 class="card-title text-white mb-3">
                        <i class="mdi mdi-filter-variant me-2"></i>Rapor Filtreleri
                    </h5>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label text-white-50">Tarih Aralığı</label>
                                <select class="form-select" id="dateRange">
                                    <option value="7">Son 7 Gün</option>
                                    <option value="30" selected>Son 30 Gün</option>
                                    <option value="90">Son 3 Ay</option>
                                    <option value="365">Son 1 Yıl</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label text-white-50">Kategori</label>
                                <select class="form-select" id="categoryFilter">
                                    <option value="">Tüm Kategoriler</option>
                                    @foreach($categoryStats as $cat)
                                        <option value="{{ $cat->category }}">{{ $cat->category }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label text-white-50">Stok Durumu</label>
                                <select class="form-select" id="statusFilter">
                                    <option value="">Tüm Durumlar</option>
                                    <option value="critical">Kritik</option>
                                    <option value="low">Düşük</option>
                                    <option value="normal">Normal</option>
                                    <option value="overstock">Fazla Stok</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label text-white-50">&nbsp;</label>
                                <div class="d-grid">
                                    <button type="button" class="btn btn-light" onclick="applyFilters()">
                                        <i class="mdi mdi-magnify me-1"></i>Filtrele
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Kategori Dağılımı -->
        <div class="col-xl-6 col-lg-12">
            <div class="card">
                <div class="card-header border-0">
                    <h4 class="card-title mb-0">Kategorilere Göre Stok Dağılımı</h4>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="categoryChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stok Durumu Dağılımı -->
        <div class="col-xl-6 col-lg-12">
            <div class="card">
                <div class="card-header border-0">
                    <h4 class="card-title mb-0">Stok Durumu Dağılımı</h4>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Aylık Trend Analizi -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-0">
                    <h4 class="card-title mb-0">Aylık Stok Hareket Trendi (Son 12 Ay)</h4>
                </div>
                <div class="card-body">
                    <div style="height: 400px;">
                        <canvas id="trendChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- En Çok Hareket Eden Ürünler -->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header border-0">
                    <h4 class="card-title mb-0">En Çok Hareket Eden Ürünler</h4>
                    <small class="text-muted">Son 30 gün</small>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Ürün</th>
                                    <th>Kategori</th>
                                    <th class="text-end">Toplam Hareket</th>
                                    <th class="text-end">Trend</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topMovedProducts as $product)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-light rounded me-2">
                                                <span class="avatar-title bg-primary text-white rounded">
                                                    {{ substr($product->stockCard->name, 0, 1) }}
                                                </span>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $product->stockCard->name }}</h6>
                                                <small class="text-muted">{{ $product->stockCard->code }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $product->stockCard->category }}</td>
                                    <td class="text-end">
                                        <span class="badge badge-primary">{{ number_format($product->total_movement, 0) }}</span>
                                    </td>
                                    <td class="text-end">
                                        <div class="mini-chart">
                                            <canvas id="mini-chart-{{ $loop->index }}"></canvas>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Düşük Stoklu Ürünler -->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header border-0 d-flex justify-content-between">
                    <div>
                        <h4 class="card-title mb-0">Dikkat Gereken Stoklar</h4>
                        <small class="text-muted">Kritik ve düşük stok seviyesinde</small>
                    </div>
                    <button class="btn btn-sm btn-outline-danger" onclick="sendLowStockAlert()">
                        <i class="mdi mdi-bell-alert me-1"></i>Uyarı Gönder
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Ürün</th>
                                    <th>Durum</th>
                                    <th class="text-end">Mevcut/Min</th>
                                    <th class="text-end">Seviye</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lowStockProducts as $product)
                                <tr>
                                    <td>
                                        <div>
                                            <h6 class="mb-0">{{ $product->name }}</h6>
                                            <small class="text-muted">{{ $product->code }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="status-badge status-{{ $product->stock_status }}">
                                            {{ $product->stock_status == 'critical' ? 'Kritik' : 'Düşük' }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <strong>{{ number_format($product->current_stock, 0) }}</strong>
                                        <small class="text-muted">/ {{ number_format($product->min_stock, 0) }}</small>
                                    </td>
                                    <td class="text-end">
                                        <div class="progress progress-custom">
                                            <div class="progress-bar bg-{{ $product->stock_status == 'critical' ? 'danger' : 'warning' }}" 
                                                 style="width: {{ $product->stock_level_percentage }}%"></div>
                                        </div>
                                        <small class="text-muted">{{ $product->stock_level_percentage }}%</small>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Son Hareketler -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-0 d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Son Stok Hareketleri</h4>
                    <div>
                        <button class="btn btn-sm btn-outline-primary me-2" onclick="exportReports()">
                            <i class="mdi mdi-download me-1"></i>Dışa Aktar
                        </button>
                        <button class="btn btn-sm btn-primary" onclick="printReports()">
                            <i class="mdi mdi-printer me-1"></i>Yazdır
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th>Tarih</th>
                                    <th>Ürün</th>
                                    <th>Hareket Tipi</th>
                                    <th class="text-end">Miktar</th>
                                    <th class="text-end">Birim Fiyat</th>
                                    <th class="text-end">Toplam</th>
                                    <th>Kullanıcı</th>
                                    <th>Açıklama</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentMovements as $movement)
                                <tr>
                                    <td>
                                        <div>
                                            <strong>{{ $movement->created_at->format('d/m/Y') }}</strong>
                                            <br><small class="text-muted">{{ $movement->created_at->format('H:i') }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $movement->stockCard->name }}</strong>
                                            <br><small class="text-muted">{{ $movement->stockCard->code }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                            $typeClass = $movement->type == 'in' ? 'success' : ($movement->type == 'out' ? 'danger' : 'info');
                                            $typeText = $movement->type == 'in' ? 'Giriş' : ($movement->type == 'out' ? 'Çıkış' : 'Düzeltme');
                                        @endphp
                                        <span class="badge bg-{{ $typeClass }}">
                                            <i class="mdi mdi-{{ $movement->type == 'in' ? 'arrow-down' : ($movement->type == 'out' ? 'arrow-up' : 'pencil') }} me-1"></i>
                                            {{ $typeText }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <strong class="text-{{ $typeClass }}">
                                            {{ $movement->type == 'out' ? '-' : '+' }}{{ number_format($movement->quantity, 2) }}
                                        </strong>
                                    </td>
                                    <td class="text-end">₺{{ number_format($movement->unit_price, 2, ',', '.') }}</td>
                                    <td class="text-end">
                                        <strong>₺{{ number_format($movement->total_amount, 2, ',', '.') }}</strong>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-xs bg-light rounded-circle me-2">
                                                <span class="avatar-title bg-primary text-white rounded-circle">
                                                    {{ substr($movement->user->name ?? 'S', 0, 1) }}
                                                </span>
                                            </div>
                                            <small>{{ $movement->user->name ?? 'Sistem' }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $movement->description ?? '-' }}</small>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toast Container -->
<div id="toast-container" class="position-fixed top-0 end-0 p-3" style="z-index: 11055;"></div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ asset('libs/select2/dist/js/select2.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
$(document).ready(function() {
    // Initialize Charts
    initializeCharts();
    
    // Initialize Select2
    $('.form-select').select2({
        minimumResultsForSearch: Infinity
    });
});

function initializeCharts() {
    // Kategori dağılım grafiği
    const categoryData = @json($categoryStats);
    const categoryCtx = document.getElementById('categoryChart').getContext('2d');
    
    new Chart(categoryCtx, {
        type: 'doughnut',
        data: {
            labels: categoryData.map(item => item.category),
            datasets: [{
                data: categoryData.map(item => item.total_value),
                backgroundColor: [
                    '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0',
                    '#9966FF', '#FF9F40', '#FF6384', '#C9CBCF'
                ],
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
                        padding: 20,
                        usePointStyle: true
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const value = context.parsed;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((value / total) * 100).toFixed(1);
                            return `${context.label}: ₺${value.toLocaleString('tr-TR')} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });

    // Stok durumu grafiği
    const statusData = @json($stockStatusCounts);
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    
    new Chart(statusCtx, {
        type: 'bar',
        data: {
            labels: ['Kritik', 'Düşük', 'Normal', 'Fazla Stok'],
            datasets: [{
                label: 'Ürün Sayısı',
                data: [statusData.critical, statusData.low, statusData.normal, statusData.overstock],
                backgroundColor: ['#dc3545', '#ffc107', '#28a745', '#007bff'],
                borderRadius: 8,
                borderSkipped: false,
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
                        stepSize: 1
                    }
                }
            }
        }
    });

    // Aylık trend grafiği
    const trendData = @json($monthlyTrends);
    const trendCtx = document.getElementById('trendChart').getContext('2d');
    
    new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: trendData.map(item => {
                const date = new Date(item.month + '-01');
                return date.toLocaleDateString('tr-TR', { year: 'numeric', month: 'short' });
            }),
            datasets: [{
                label: 'Stok Girişi',
                data: trendData.map(item => item.total_in),
                borderColor: '#28a745',
                backgroundColor: 'rgba(40, 167, 69, 0.1)',
                fill: true,
                tension: 0.4
            }, {
                label: 'Stok Çıkışı',
                data: trendData.map(item => item.total_out),
                borderColor: '#dc3545',
                backgroundColor: 'rgba(220, 53, 69, 0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                intersect: false,
                mode: 'index'
            },
            plugins: {
                legend: {
                    position: 'top'
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

function applyFilters() {
    const dateRange = $('#dateRange').val();
    const category = $('#categoryFilter').val();
    const status = $('#statusFilter').val();
    
    // Filtrelere göre sayfayı yenile
    const params = new URLSearchParams();
    if (dateRange) params.append('date_range', dateRange);
    if (category) params.append('category', category);
    if (status) params.append('status', status);
    
    window.location.href = window.location.pathname + '?' + params.toString();
}

function sendLowStockAlert() {
    if (confirm('Düşük stoklu ürünler için uyarı e-postası gönderilsin mi?')) {
        $.post('/finance/stock/send-low-stock-alert')
            .done(function(response) {
                showToast('Uyarı e-postası başarıyla gönderildi.', 'success');
            })
            .fail(function() {
                showToast('Uyarı gönderilirken hata oluştu.', 'error');
            });
    }
}

function exportReports() {
    window.open('/finance/stock/reports/export?format=excel', '_blank');
}

function printReports() {
    window.print();
}

function showToast(message, type) {
    // Toast notification implementation
    const toast = $(`
        <div class="toast align-items-center text-white bg-${type === 'success' ? 'success' : 'danger'} border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body">${message}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    `);
    
    $('#toast-container').append(toast);
    toast.toast('show');
    
    setTimeout(() => toast.remove(), 5000);
}
</script>
@endsection
