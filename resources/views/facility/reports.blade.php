@extends('layouts.master')

@section('title', 'Tesis Raporları')
@section('topbar-title', 'Tesis Raporları')

@section('css')
<style>
.report-card {
    transition: all 0.3s ease;
    border: none;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    position: relative;
    overflow: hidden;
}

.report-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #007bff, #28a745, #ffc107, #dc3545);
}

.report-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.report-card .card-body {
    padding: 2rem;
}

.report-card .icon-wrapper {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1rem;
    font-size: 1.5rem;
}

.report-card .btn {
    transition: all 0.3s ease;
}

.report-card .btn:hover {
    transform: scale(1.05);
}

/* Modal z-index fix */
.modal {
    z-index: 1055 !important;
}

.modal-backdrop {
    z-index: 1050 !important;
}

.modal-content {
    z-index: 1060 !important;
}

.modal-header .btn-close {
    z-index: 1070 !important;
}

.modal-footer .btn {
    z-index: 1070 !important;
    position: relative !important;
    pointer-events: auto !important;
}

.modal-header .btn-close {
    z-index: 1070 !important;
    position: relative !important;
    pointer-events: auto !important;
}

/* Ensure modal content is above backdrop */
.modal-content {
    position: relative !important;
    z-index: 1060 !important;
}

/* Prevent backdrop from blocking interactions */
.modal-backdrop {
    pointer-events: none !important;
}

.modal-backdrop.show {
    opacity: 0.5 !important;
}
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-chart-bar me-2"></i>
                        Tesis Raporları
                    </h4>
                </div>
                <div class="card-body">
                    <p class="text-muted">Farklı kategorilerde tesis raporlarını görüntüleyin ve dışa aktarın.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <!-- Varlık Raporu -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card report-card h-100">
                <div class="card-body text-center">
                    <div class="icon-wrapper bg-primary text-white mx-auto">
                        <i class="fas fa-cogs"></i>
                    </div>
                    <h5 class="card-title">Varlık Raporu</h5>
                    <p class="card-text text-muted">Tüm tesis varlıklarının detaylı listesi ve durum bilgileri.</p>
                    <div class="mt-3">
                        <a href="#" class="btn btn-primary me-2" onclick="generateReport('assets')">
                            <i class="fas fa-eye me-1"></i>Görüntüle
                        </a>
                        <a href="#" class="btn btn-outline-primary" onclick="exportReport('assets')">
                            <i class="fas fa-download me-1"></i>İndir
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bakım Raporu -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card report-card h-100">
                <div class="card-body text-center">
                    <div class="icon-wrapper bg-success text-white mx-auto">
                        <i class="fas fa-tools"></i>
                    </div>
                    <h5 class="card-title">Bakım Raporu</h5>
                    <p class="card-text text-muted">Bakım kayıtları, planlanan ve tamamlanan işlemler.</p>
                    <div class="mt-3">
                        <a href="#" class="btn btn-success me-2" onclick="generateReport('maintenance')">
                            <i class="fas fa-eye me-1"></i>Görüntüle
                        </a>
                        <a href="#" class="btn btn-outline-success" onclick="exportReport('maintenance')">
                            <i class="fas fa-download me-1"></i>İndir
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Arıza Raporu -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card report-card h-100">
                <div class="card-body text-center">
                    <div class="icon-wrapper bg-danger text-white mx-auto">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <h5 class="card-title">Arıza Raporu</h5>
                    <p class="card-text text-muted">Arıza bildirimleri ve çözüm süreleri analizi.</p>
                    <div class="mt-3">
                        <a href="#" class="btn btn-danger me-2" onclick="generateReport('requests')">
                            <i class="fas fa-eye me-1"></i>Görüntüle
                        </a>
                        <a href="#" class="btn btn-outline-danger" onclick="exportReport('requests')">
                            <i class="fas fa-download me-1"></i>İndir
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Performans Raporu -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card report-card h-100">
                <div class="card-body text-center">
                    <div class="icon-wrapper bg-info text-white mx-auto">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h5 class="card-title">Performans Raporu</h5>
                    <p class="card-text text-muted">Tesis performans metrikleri ve KPI'lar.</p>
                    <div class="mt-3">
                        <a href="#" class="btn btn-info me-2" onclick="generateReport('performance')">
                            <i class="fas fa-eye me-1"></i>Görüntüle
                        </a>
                        <a href="#" class="btn btn-outline-info" onclick="exportReport('performance')">
                            <i class="fas fa-download me-1"></i>İndir
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Maliyet Raporu -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card report-card h-100">
                <div class="card-body text-center">
                    <div class="icon-wrapper bg-warning text-white mx-auto">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <h5 class="card-title">Maliyet Raporu</h5>
                    <p class="card-text text-muted">Bakım ve onarım maliyet analizi.</p>
                    <div class="mt-3">
                        <a href="#" class="btn btn-warning me-2" onclick="generateReport('cost')">
                            <i class="fas fa-eye me-1"></i>Görüntüle
                        </a>
                        <a href="#" class="btn btn-outline-warning" onclick="exportReport('cost')">
                            <i class="fas fa-download me-1"></i>İndir
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Özet Rapor -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card report-card h-100">
                <div class="card-body text-center">
                    <div class="icon-wrapper bg-secondary text-white mx-auto">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <h5 class="card-title">Özet Rapor</h5>
                    <p class="card-text text-muted">Tesis durumunun genel özeti.</p>
                    <div class="mt-3">
                        <a href="#" class="btn btn-secondary me-2" onclick="generateReport('summary')">
                            <i class="fas fa-eye me-1"></i>Görüntüle
                        </a>
                        <a href="#" class="btn btn-outline-secondary" onclick="exportReport('summary')">
                            <i class="fas fa-download me-1"></i>İndir
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Geri Dön Butonu -->
    <div class="row mt-4">
        <div class="col-12 text-center">
            <a href="{{ route('facility.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-2"></i>Tesis Yönetimine Dön
            </a>
        </div>
    </div>
</div>

<!-- Report Modal -->
<div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reportModalLabel">Rapor Görüntüleme</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="reportContent">
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Yükleniyor...</span>
                        </div>
                        <p class="mt-3">Rapor hazırlanıyor...</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
                <button type="button" class="btn btn-primary" id="downloadReportBtn">
                    <i class="fas fa-download me-1"></i>İndir
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
function generateReport(type) {
    // Show loading modal
    const modal = new bootstrap.Modal(document.getElementById('reportModal'));
    document.getElementById('reportModalLabel').textContent = getReportTitle(type) + ' - Görüntüleme';
    modal.show();

    // Generate report content based on type
    let content = '';

    switch(type) {
        case 'assets':
            content = generateAssetsReport();
            break;
        case 'maintenance':
            content = generateMaintenanceReport();
            break;
        case 'requests':
            content = generateRequestsReport();
            break;
        case 'performance':
            content = generatePerformanceReport();
            break;
        case 'cost':
            content = generateCostReport();
            break;
        case 'summary':
            content = generateSummaryReport();
            break;
        default:
            content = '<div class="alert alert-warning">Rapor türü bulunamadı.</div>';
    }

    // Simulate loading delay
    setTimeout(() => {
        document.getElementById('reportContent').innerHTML = content;
    }, 500);
}

function generateAssetsReport() {
    let html = `
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Kod</th>
                        <th>İsim</th>
                        <th>Kategori</th>
                        <th>Konum</th>
                        <th>Durum</th>
                        <th>Son Bakım</th>
                    </tr>
                </thead>
                <tbody>`;

    reportData.assets.forEach(asset => {
        const statusClass = asset.status === 'active' ? 'success' : (asset.status === 'maintenance' ? 'warning' : 'secondary');
        html += `
                    <tr>
                        <td>${asset.asset_code || '-'}</td>
                        <td>${asset.name || '-'}</td>
                        <td>${asset.category || '-'}</td>
                        <td>${asset.location || '-'}</td>
                        <td>
                            <span class="badge bg-${statusClass}">
                                ${asset.status ? asset.status.charAt(0).toUpperCase() + asset.status.slice(1) : 'Unknown'}
                            </span>
                        </td>
                        <td>${asset.last_maintenance_date || '-'}</td>
                    </tr>`;
    });

    html += `
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            <h6>Durum Dağılımı:</h6>
            <ul class="list-unstyled">`;

    reportData.assetsByStatus.forEach(status => {
        html += `<li><strong>${status.status.charAt(0).toUpperCase() + status.status.slice(1)}:</strong> ${status.count} varlık</li>`;
    });

    html += `
            </ul>
        </div>`;

    return html;
}

function generateMaintenanceReport() {
    return `
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Varlık</th>
                        <th>Tür</th>
                        <th>Durum</th>
                        <th>Tarih</th>
                        <th>Maliyet</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($maintenances->take(20) as $maintenance)
                    <tr>
                        <td>{{ $maintenance->asset->name ?? 'Bilinmeyen' }}</td>
                        <td>{{ $maintenance->type ?? '-' }}</td>
                        <td>
                            <span class="badge bg-${{ $maintenance->status == 'completed' ? 'success' : ($maintenance->status == 'in_progress' ? 'warning' : 'secondary') }}">
                                {{ ucfirst(str_replace('_', ' ', $maintenance->status ?? 'unknown')) }}
                            </span>
                        </td>
                        <td>{{ $maintenance->created_at->format('d.m.Y') }}</td>
                        <td>{{ $maintenance->cost ? '₺' . number_format($maintenance->cost, 2) : '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            <h6>Durum Dağılımı:</h6>
            <ul class="list-unstyled">
                @foreach($maintenanceByStatus as $status)
                <li><strong>{{ ucfirst(str_replace('_', ' ', $status->status)) }}:</strong> {{ $status->count }} bakım</li>
                @endforeach
            </ul>
        </div>
    `;
}

function generateRequestsReport() {
    let html = `
        <div class="table-responsive">
            <table class="table table-striped table-sm">
                <thead>
                    <tr>
                        <th>Konu</th>
                        <th>Konum</th>
                        <th>Durum</th>
                        <th>Oluşturulma</th>
                    </tr>
                </thead>
                <tbody>`;

    reportData.serviceRequests.forEach(request => {
        const statusClass = request.status === 'completed' ? 'success' : (request.status === 'in_progress' ? 'warning' : (request.status === 'pending' ? 'secondary' : 'danger'));
        const statusText = request.status ? request.status.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase()) : 'Pending';
        html += `
                    <tr>
                        <td>${request.subject}</td>
                        <td>${request.location}</td>
                        <td><span class="badge bg-${statusClass}">${statusText}</span></td>
                        <td>${new Date(request.created_at).toLocaleString('tr-TR')}</td>
                    </tr>`;
    });

    html += `
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            <h6>İstatistikler:</h6>
            <ul class="list-unstyled">
                <li><strong>Toplam Talep:</strong> ${reportData.serviceRequests.length}</li>
                <li><strong>Çözülen:</strong> ${reportData.serviceRequests.filter(r => r.status === 'completed').length}</li>
                <li><strong>Ortalama Çözüm Süresi:</strong> ${reportData.avgResolutionTime.toFixed(1)} saat</li>
            </ul>
        </div>`;

    return html;
}

function generatePerformanceReport() {
    return `
        <div class="row">
            <div class="col-md-6">
                <h6>Genel Performans</h6>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between">
                        Toplam Varlık <span class="badge bg-primary">{{ $totalAssets }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        Aktif Varlık <span class="badge bg-success">{{ $activeAssets }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        Tamamlanan Bakım <span class="badge bg-info">{{ $maintenanceCompleted }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        Ortalama Çözüm Süresi <span class="badge bg-warning">{{ number_format($avgResolutionTime, 1) }} saat</span>
                    </li>
                </ul>
            </div>
            <div class="col-md-6">
                <h6>KPI'lar</h6>
                <div class="progress mb-3">
                    <div class="progress-bar bg-success" style="width: {{ $activeAssets / max($totalAssets, 1) * 100 }}%">
                        Varlık Aktifliği
                    </div>
                </div>
                <div class="progress mb-3">
                    <div class="progress-bar bg-info" style="width: {{ $maintenanceCompleted / max($maintenances->count(), 1) * 100 }}%">
                        Bakım Tamamlanma
                    </div>
                </div>
                <div class="progress mb-3">
                    <div class="progress-bar bg-warning" style="width: {{ min($avgResolutionTime / 24, 1) * 100 }}%">
                        Çözüm Hızı
                    </div>
                </div>
            </div>
        </div>
    `;
}

function generateCostReport() {
    return `
        <div class="row">
            <div class="col-md-6">
                <h6>Maliyet Özeti</h6>
                <div class="card">
                    <div class="card-body">
                        <h4 class="text-primary">₺{{ number_format($totalMaintenanceCost, 2) }}</h4>
                        <p class="text-muted mb-0">Toplam Bakım Maliyeti</p>
                    </div>
                </div>
                <div class="mt-3">
                    <p><strong>Varlık Başına Ortalama:</strong> ₺{{ number_format($avgCostPerAsset, 2) }}</p>
                </div>
            </div>
            <div class="col-md-6">
                <h6>Aylık Maliyet Dağılımı</h6>
                <canvas id="costChart" width="200" height="200"></canvas>
            </div>
        </div>
        <div class="mt-3">
            <h6>Aylık Detaylar:</h6>
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Ay</th>
                            <th>Maliyet</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($maintenanceCostByMonth as $cost)
                        <tr>
                            <td>{{ date('F', mktime(0, 0, 0, $cost->month, 1)) }}</td>
                            <td>₺{{ number_format($cost->total_cost, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    `;
}

function generateSummaryReport() {
    return `
        <div class="row">
            <div class="col-md-4">
                <div class="card text-center mb-3">
                    <div class="card-body">
                        <h3 class="text-primary">{{ $summary['total_assets'] }}</h3>
                        <p class="text-muted">Toplam Varlık</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center mb-3">
                    <div class="card-body">
                        <h3 class="text-success">{{ $summary['active_assets'] }}</h3>
                        <p class="text-muted">Aktif Varlık</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center mb-3">
                    <div class="card-body">
                        <h3 class="text-info">{{ $summary['total_maintenances'] }}</h3>
                        <p class="text-muted">Toplam Bakım</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center mb-3">
                    <div class="card-body">
                        <h3 class="text-warning">{{ $summary['total_requests'] }}</h3>
                        <p class="text-muted">Toplam Talep</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center mb-3">
                    <div class="card-body">
                        <h3 class="text-danger">₺{{ number_format($summary['total_cost'], 0) }}</h3>
                        <p class="text-muted">Toplam Maliyet</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center mb-3">
                    <div class="card-body">
                        <h3 class="text-secondary">{{ $summary['upcoming_maintenances'] }}</h3>
                        <p class="text-muted">Yaklaşan Bakım</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="alert alert-info">
            <h6><i class="fas fa-info-circle me-2"></i>Sistem Durumu</h6>
            <p class="mb-1"><strong>Aktif Varlık Oranı:</strong> {{ $summary['total_assets'] > 0 ? round($summary['active_assets'] / $summary['total_assets'] * 100, 1) : 0 }}%</p>
            <p class="mb-1"><strong>Bakım Tamamlanma Oranı:</strong> {{ $summary['total_maintenances'] > 0 ? round($summary['completed_maintenances'] / $summary['total_maintenances'] * 100, 1) : 0 }}%</p>
            <p class="mb-0"><strong>Talep Çözüm Oranı:</strong> {{ $summary['total_requests'] > 0 ? round($summary['resolved_requests'] / $summary['total_requests'] * 100, 1) : 0 }}%</p>
        </div>
    `;
}

function exportReport(type) {
    // In real implementation, this would trigger a download
    alert(`${getReportTitle(type)} dışa aktarılıyor... (PDF/Excel formatında)`);
}

function getReportTitle(type) {
    const titles = {
        'assets': 'Varlık Raporu',
        'maintenance': 'Bakım Raporu',
        'requests': 'Arıza Raporu',
        'performance': 'Performans Raporu',
        'cost': 'Maliyet Raporu',
        'summary': 'Özet Rapor'
    };
    return titles[type] || 'Rapor';
}

// Pass PHP data to JavaScript
const reportData = {
    assets: @json($assets),
    assetsByStatus: @json($assetsByStatus),
    maintenances: @json($maintenances->take(20)),
    maintenanceByStatus: @json($maintenanceByStatus),
    serviceRequests: @json($serviceRequests->take(20)),
    requestsByStatus: @json($requestsByStatus),
    totalAssets: @json($totalAssets),
    activeAssets: @json($activeAssets),
    maintenanceCompleted: @json($maintenanceCompleted),
    avgResolutionTime: @json($avgResolutionTime),
    totalMaintenanceCost: @json($totalMaintenanceCost),
    maintenanceCostByMonth: @json($maintenanceCostByMonth),
    avgCostPerAsset: @json($avgCostPerAsset),
    summary: @json($summary)
};

function generateReport(type) {
    // Show loading modal
    const modal = new bootstrap.Modal(document.getElementById('reportModal'));
    document.getElementById('reportModalLabel').textContent = getReportTitle(type) + ' - Görüntüleme';
    modal.show();

    // Generate report content based on type
    let content = '';

    switch(type) {
        case 'assets':
            content = generateAssetsReport();
            break;
        case 'maintenance':
            content = generateMaintenanceReport();
            break;
        case 'requests':
            content = generateRequestsReport();
            break;
        case 'performance':
            content = generatePerformanceReport();
            break;
        case 'cost':
            content = generateCostReport();
            break;
        case 'summary':
            content = generateSummaryReport();
            break;
        default:
            content = '<div class="alert alert-warning">Rapor türü bulunamadı.</div>';
    }

    // Show content immediately (no loading delay needed)
    document.getElementById('reportContent').innerHTML = content;
}

function generateAssetsReport() {
    let html = `
        <div class="table-responsive">
            <table class="table table-striped table-sm">
                <thead>
                    <tr>
                        <th>Kod</th>
                        <th>İsim</th>
                        <th>Kategori</th>
                        <th>Konum</th>
                        <th>Durum</th>
                        <th>Son Bakım</th>
                    </tr>
                </thead>
                <tbody>`;

    reportData.assets.forEach(asset => {
        const statusClass = asset.status === 'active' ? 'success' : (asset.status === 'maintenance' ? 'warning' : 'secondary');
        html += `
                    <tr>
                        <td>${asset.asset_code}</td>
                        <td>${asset.name}</td>
                        <td>${asset.category || '-'}</td>
                        <td>${asset.location || '-'}</td>
                        <td><span class="badge bg-${statusClass}">${asset.status ? asset.status.charAt(0).toUpperCase() + asset.status.slice(1) : 'Unknown'}</span></td>
                        <td>${asset.last_maintenance_date ? new Date(asset.last_maintenance_date).toLocaleDateString('tr-TR') : '-'}</td>
                    </tr>`;
    });

    html += `
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            <h6>Durum Dağılımı:</h6>
            <ul class="list-unstyled">`;

    reportData.assetsByStatus.forEach(status => {
        html += `<li><strong>${status.status.charAt(0).toUpperCase() + status.status.slice(1)}:</strong> ${status.count} varlık</li>`;
    });

    html += `
            </ul>
        </div>`;

    return html;
}

function generateMaintenanceReport() {
    let html = `
        <div class="table-responsive">
            <table class="table table-striped table-sm">
                <thead>
                    <tr>
                        <th>Varlık</th>
                        <th>Tür</th>
                        <th>Durum</th>
                        <th>Tarih</th>
                        <th>Maliyet</th>
                    </tr>
                </thead>
                <tbody>`;

    reportData.maintenances.forEach(maintenance => {
        const statusClass = maintenance.status === 'completed' ? 'success' : (maintenance.status === 'in_progress' ? 'warning' : 'secondary');
        const statusText = maintenance.status ? maintenance.status.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase()) : 'Unknown';
        html += `
                    <tr>
                        <td>${maintenance.asset ? maintenance.asset.name : 'Bilinmeyen'}</td>
                        <td>${maintenance.type || '-'}</td>
                        <td><span class="badge bg-${statusClass}">${statusText}</span></td>
                        <td>${new Date(maintenance.created_at).toLocaleDateString('tr-TR')}</td>
                        <td>${maintenance.cost ? '₺' + parseFloat(maintenance.cost).toLocaleString('tr-TR', {minimumFractionDigits: 2}) : '-'}</td>
                    </tr>`;
    });

    html += `
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            <h6>Durum Dağılımı:</h6>
            <ul class="list-unstyled">`;

    reportData.maintenanceByStatus.forEach(status => {
        const statusText = status.status.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase());
        html += `<li><strong>${statusText}:</strong> ${status.count} bakım</li>`;
    });

    html += `
            </ul>
        </div>`;

    return html;
}

function generateRequestsReport() {
    let html = `
        <div class="table-responsive">
            <table class="table table-striped table-sm">
                <thead>
                    <tr>
                        <th>Konu</th>
                        <th>Konum</th>
                        <th>Durum</th>
                        <th>Oluşturulma</th>
                    </tr>
                </thead>
                <tbody>`;

    reportData.serviceRequests.forEach(request => {
        const statusClass = request.status === 'completed' ? 'success' : (request.status === 'in_progress' ? 'warning' : (request.status === 'pending' ? 'secondary' : 'danger'));
        const statusText = request.status ? request.status.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase()) : 'Pending';
        html += `
                    <tr>
                        <td>${request.subject}</td>
                        <td>${request.location}</td>
                        <td><span class="badge bg-${statusClass}">${statusText}</span></td>
                        <td>${new Date(request.created_at).toLocaleString('tr-TR')}</td>
                    </tr>`;
    });

    html += `
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            <h6>İstatistikler:</h6>
            <ul class="list-unstyled">
                <li><strong>Toplam Talep:</strong> ${reportData.serviceRequests.length}</li>
                <li><strong>Çözülen:</strong> ${reportData.serviceRequests.filter(r => r.status === 'completed').length}</li>
                <li><strong>Ortalama Çözüm Süresi:</strong> ${reportData.avgResolutionTime.toFixed(1)} saat</li>
            </ul>
        </div>`;

    return html;
}

function generatePerformanceReport() {
    const assetActivityRate = reportData.totalAssets > 0 ? (reportData.activeAssets / reportData.totalAssets * 100).toFixed(1) : 0;
    const maintenanceCompletionRate = reportData.maintenances.length > 0 ? (reportData.maintenanceCompleted / reportData.maintenances.length * 100).toFixed(1) : 0;
    const resolutionSpeedRate = Math.min(reportData.avgResolutionTime / 24 * 100, 100).toFixed(1);

    return `
        <div class="row">
            <div class="col-md-6">
                <h6>Genel Performans</h6>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between">
                        Toplam Varlık <span class="badge bg-primary">${reportData.totalAssets}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        Aktif Varlık <span class="badge bg-success">${reportData.activeAssets}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        Tamamlanan Bakım <span class="badge bg-info">${reportData.maintenanceCompleted}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        Ortalama Çözüm Süresi <span class="badge bg-warning">${reportData.avgResolutionTime.toFixed(1)} saat</span>
                    </li>
                </ul>
            </div>
            <div class="col-md-6">
                <h6>KPI'lar</h6>
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Varlık Aktifliği</span>
                        <span>${assetActivityRate}%</span>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-success" style="width: ${assetActivityRate}%"></div>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Bakım Tamamlanma</span>
                        <span>${maintenanceCompletionRate}%</span>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-info" style="width: ${maintenanceCompletionRate}%"></div>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Çözüm Hızı</span>
                        <span>${resolutionSpeedRate}%</span>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-warning" style="width: ${resolutionSpeedRate}%"></div>
                    </div>
                </div>
            </div>
        </div>
    `;
}

function generateCostReport() {
    let html = `
        <div class="row">
            <div class="col-md-6">
                <h6>Maliyet Özeti</h6>
                <div class="card">
                    <div class="card-body">
                        <h4 class="text-primary">₺${reportData.totalMaintenanceCost.toLocaleString('tr-TR', {minimumFractionDigits: 2})}</h4>
                        <p class="text-muted mb-0">Toplam Bakım Maliyeti</p>
                    </div>
                </div>
                <div class="mt-3">
                    <p><strong>Varlık Başına Ortalama:</strong> ₺${reportData.avgCostPerAsset.toLocaleString('tr-TR', {minimumFractionDigits: 2})}</p>
                </div>
            </div>
            <div class="col-md-6">
                <h6>Aylık Maliyet Dağılımı</h6>
                <div id="costChartContainer" style="height: 200px;">
                    <canvas id="costChart"></canvas>
                </div>
            </div>
        </div>
        <div class="mt-3">
            <h6>Aylık Detaylar:</h6>
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Ay</th>
                            <th>Maliyet</th>
                        </tr>
                    </thead>
                    <tbody>`;

    const monthNames = ['Ocak', 'Şubat', 'Mart', 'Nisan', 'Mayıs', 'Haziran', 'Temmuz', 'Ağustos', 'Eylül', 'Ekim', 'Kasım', 'Aralık'];
    reportData.maintenanceCostByMonth.forEach(cost => {
        html += `
                        <tr>
                            <td>${monthNames[cost.month - 1]}</td>
                            <td>₺${parseFloat(cost.total_cost).toLocaleString('tr-TR', {minimumFractionDigits: 2})}</td>
                        </tr>`;
    });

    html += `
                    </tbody>
                </table>
            </div>
        </div>`;

    // Initialize chart after modal is shown
    setTimeout(() => {
        initCostChart();
    }, 100);

    return html;
}

function generateSummaryReport() {
    const assetActivityRate = reportData.summary.total_assets > 0 ? (reportData.summary.active_assets / reportData.summary.total_assets * 100).toFixed(1) : 0;
    const maintenanceCompletionRate = reportData.summary.total_maintenances > 0 ? (reportData.summary.completed_maintenances / reportData.summary.total_maintenances * 100).toFixed(1) : 0;
    const requestResolutionRate = reportData.summary.total_requests > 0 ? (reportData.summary.resolved_requests / reportData.summary.total_requests * 100).toFixed(1) : 0;

    return `
        <div class="row">
            <div class="col-md-4">
                <div class="card text-center mb-3 border-primary">
                    <div class="card-body">
                        <h3 class="text-primary">${reportData.summary.total_assets}</h3>
                        <p class="text-muted mb-0">Toplam Varlık</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center mb-3 border-success">
                    <div class="card-body">
                        <h3 class="text-success">${reportData.summary.active_assets}</h3>
                        <p class="text-muted mb-0">Aktif Varlık</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center mb-3 border-info">
                    <div class="card-body">
                        <h3 class="text-info">${reportData.summary.total_maintenances}</h3>
                        <p class="text-muted mb-0">Toplam Bakım</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center mb-3 border-warning">
                    <div class="card-body">
                        <h3 class="text-warning">${reportData.summary.total_requests}</h3>
                        <p class="text-muted mb-0">Toplam Talep</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center mb-3 border-danger">
                    <div class="card-body">
                        <h3 class="text-danger">₺${reportData.summary.total_cost.toLocaleString('tr-TR')}</h3>
                        <p class="text-muted mb-0">Toplam Maliyet</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center mb-3 border-secondary">
                    <div class="card-body">
                        <h3 class="text-secondary">${reportData.summary.upcoming_maintenances}</h3>
                        <p class="text-muted mb-0">Yaklaşan Bakım</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="alert alert-info">
            <h6><i class="fas fa-info-circle me-2"></i>Sistem Durumu</h6>
            <p class="mb-1"><strong>Aktif Varlık Oranı:</strong> ${assetActivityRate}%</p>
            <p class="mb-1"><strong>Bakım Tamamlanma Oranı:</strong> ${maintenanceCompletionRate}%</p>
            <p class="mb-0"><strong>Talep Çözüm Oranı:</strong> ${requestResolutionRate}%</p>
        </div>
    `;
}

function initCostChart() {
    const ctx = document.getElementById('costChart');
    if (!ctx) return;

    const labels = [];
    const data = [];

    const monthNames = ['Ocak', 'Şubat', 'Mart', 'Nisan', 'Mayıs', 'Haziran', 'Temmuz', 'Ağustos', 'Eylül', 'Ekim', 'Kasım', 'Aralık'];
    reportData.maintenanceCostByMonth.forEach(cost => {
        labels.push(monthNames[cost.month - 1]);
        data.push(parseFloat(cost.total_cost));
    });

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Bakım Maliyeti (₺)',
                data: data,
                backgroundColor: 'rgba(255, 193, 7, 0.6)',
                borderColor: 'rgba(255, 193, 7, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '₺' + value.toLocaleString('tr-TR');
                        }
                    }
                }
            }
        }
    });
}

function exportReport(type) {
    // In real implementation, this would trigger a download
    alert(`${getReportTitle(type)} dışa aktarılıyor... (PDF/Excel formatında)`);
}

function getReportTitle(type) {
    const titles = {
        'assets': 'Varlık Raporu',
        'maintenance': 'Bakım Raporu',
        'requests': 'Arıza Raporu',
        'performance': 'Performans Raporu',
        'cost': 'Maliyet Raporu',
        'summary': 'Özet Rapor'
    };
    return titles[type] || 'Rapor';
}

// Handle download button in modal
document.getElementById('downloadReportBtn').addEventListener('click', function() {
    alert('Rapor indirme işlemi başlatıldı. (PDF/Excel formatında)');
});

// Ensure modal buttons are clickable
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('reportModal');
    if (modal) {
        modal.addEventListener('shown.bs.modal', function() {
            // Ensure buttons are focusable and clickable
            const closeBtn = modal.querySelector('.btn-close');
            const cancelBtn = modal.querySelector('.btn-secondary');
            const downloadBtn = modal.querySelector('#downloadReportBtn');

            if (closeBtn) closeBtn.style.pointerEvents = 'auto';
            if (cancelBtn) cancelBtn.style.pointerEvents = 'auto';
            if (downloadBtn) downloadBtn.style.pointerEvents = 'auto';
        });
    }
});
</script>
@endsection
