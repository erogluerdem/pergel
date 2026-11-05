@extends('layouts.master')
@section('title', 'E-İrsaliye Yönetimi')
@section('topbar-title', 'Finans')

@push('styles')
<style>
    .stats-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    .stats-card:hover {
        box-shadow: 0 8px 25px rgba(0,0,0,0.12);
        transform: translateY(-2px);
    }
    .stats-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #28a745 0%, #20c997 100%);
    }
    .stats-number {
        font-size: 2.2rem;
        font-weight: 700;
        margin-bottom: 5px;
        color: #2d3436;
    }
    .stats-label {
        color: #636e72;
        font-weight: 500;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }
    .stats-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    }
    .dispatch-status {
        padding: 6px 12px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .status-sent { background: #e3f2fd; color: #1565c0; }
    .status-transit { background: #fff3e0; color: #e65100; }
    .status-delivered { background: #e8f5e8; color: #2e7d32; }
    .status-rejected { background: #ffebee; color: #c62828; }
    .status-draft { background: #f3e5f5; color: #7b1fa2; }
    
    .action-btn {
        padding: 8px 12px;
        border-radius: 8px;
        border: none;
        font-weight: 500;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: all 0.2s ease;
        font-size: 0.85rem;
    }
    .btn-primary-custom {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
    }
    .btn-primary-custom:hover {
        background: linear-gradient(135deg, #218838 0%, #1e7e34 100%);
        transform: translateY(-1px);
    }
    .btn-success-custom {
        background: #28a745;
        color: white;
    }
    .btn-success-custom:hover {
        background: #218838;
        transform: translateY(-1px);
    }
    .btn-warning-custom {
        background: #ffc107;
        color: #212529;
    }
    .btn-warning-custom:hover {
        background: #e0a800;
        transform: translateY(-1px);
    }
    .btn-info-custom {
        background: #17a2b8;
        color: white;
    }
    .btn-info-custom:hover {
        background: #138496;
        transform: translateY(-1px);
    }
    
    .chart-container {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        height: 400px;
    }
    
    .quick-actions {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        border-radius: 12px;
        padding: 25px;
        color: white;
        margin-bottom: 20px;
    }
    
    .search-filter-box {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        margin-bottom: 20px;
    }
    
    .table-container {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    }
    
    .table-modern {
        margin: 0;
    }
    .table-modern thead th {
        background: #f8f9fa;
        border: none;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
        color: #495057;
        padding: 15px;
    }
    .table-modern tbody td {
        border: none;
        padding: 15px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f3f4;
    }
    .table-modern tbody tr:hover {
        background: #f8f9fa;
    }
    
    .page-header {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        padding: 30px;
        border-radius: 12px;
        margin-bottom: 30px;
    }
    
    .integration-status {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        background: rgba(255,255,255,0.2);
        border-radius: 25px;
        font-size: 0.9rem;
    }
    
    .status-indicator {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #4CAF50;
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.5; }
        100% { opacity: 1; }
    }

    .delivery-progress {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    }
    
    .progress-step {
        position: relative;
        padding: 20px 0;
        border-left: 2px solid #e9ecef;
        margin-left: 10px;
    }
    
    .progress-step:last-child {
        border-left: none;
    }
    
    .progress-step.active {
        border-left-color: #28a745;
    }
    
    .progress-step.completed {
        border-left-color: #28a745;
    }
    
    .step-icon {
        position: absolute;
        left: -11px;
        top: 25px;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: #e9ecef;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
    }
    
    .step-icon.active {
        background: #28a745;
        color: white;
    }
    
    .step-icon.completed {
        background: #28a745;
        color: white;
    }
    
    .vehicle-info {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 15px;
    }
    
    .package-item {
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 10px;
        transition: all 0.2s ease;
    }
    
    .package-item:hover {
        border-color: #28a745;
        background: #f8fff8;
    }
</style>
@endpush

@section('content')
<!-- Page Header -->
<div class="page-header">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h2 class="mb-2" style="font-weight: 300;">
                <i class="mdi mdi-truck-delivery-outline me-2"></i>
                E-İrsaliye Yönetim Sistemi
            </h2>
            <p class="mb-0 opacity-75">
                Elektronik irsaliye oluşturma, sevkiyat takibi ve teslimat işlemlerini tek merkezden yönetin
            </p>
        </div>
        <div class="col-md-4 text-end">
            <div class="integration-status">
                <div class="status-indicator"></div>
                GİB Entegrasyonu Aktif
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="quick-actions">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h5 class="mb-2" style="font-weight: 500;">Hızlı İşlemler</h5>
            <p class="mb-0 opacity-75">En sık kullanılan işlemlere hızlı erişim</p>
        </div>
        <div class="col-md-4 text-end">
            <div class="btn-group">
                <button class="btn btn-light me-2">
                    <i class="mdi mdi-plus me-1"></i> Yeni İrsaliye
                </button>
                <button class="btn btn-outline-light me-2">
                    <i class="mdi mdi-truck me-1"></i> Sevkiyat Takip
                </button>
                <button class="btn btn-outline-light">
                    <i class="mdi mdi-upload me-1"></i> Toplu İçe Aktar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-2 col-md-4 col-6">
        <div class="card stats-card h-100">
            <div class="card-body text-center">
                <div class="d-flex justify-content-center mb-3">
                    <div class="stats-icon">
                        <i class="mdi mdi-file-document-multiple"></i>
                    </div>
                </div>
                <div class="stats-number">1,248</div>
                <div class="stats-label">Toplam İrsaliye</div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="card stats-card h-100">
            <div class="card-body text-center">
                <div class="d-flex justify-content-center mb-3">
                    <div class="stats-icon">
                        <i class="mdi mdi-truck-fast"></i>
                    </div>
                </div>
                <div class="stats-number">87</div>
                <div class="stats-label">Yolda</div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="card stats-card h-100">
            <div class="card-body text-center">
                <div class="d-flex justify-content-center mb-3">
                    <div class="stats-icon">
                        <i class="mdi mdi-calendar-today"></i>
                    </div>
                </div>
                <div class="stats-number">23</div>
                <div class="stats-label">Bugün Gönderilen</div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="card stats-card h-100">
            <div class="card-body text-center">
                <div class="d-flex justify-content-center mb-3">
                    <div class="stats-icon">
                        <i class="mdi mdi-check-circle"></i>
                    </div>
                </div>
                <div class="stats-number text-success">156</div>
                <div class="stats-label">Teslim Edildi</div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="card stats-card h-100">
            <div class="card-body text-center">
                <div class="d-flex justify-content-center mb-3">
                    <div class="stats-icon">
                        <i class="mdi mdi-package-variant"></i>
                    </div>
                </div>
                <div class="stats-number">342</div>
                <div class="stats-label">Toplam Paket</div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="card stats-card h-100">
            <div class="card-body text-center">
                <div class="d-flex justify-content-center mb-3">
                    <div class="stats-icon">
                        <i class="mdi mdi-speedometer"></i>
                    </div>
                </div>
                <div class="stats-number text-success">%96.2</div>
                <div class="stats-label">Başarı Oranı</div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Latest Dispatch Activities -->
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="mdi mdi-clipboard-list-outline me-2"></i>
                    Son İrsaliye Hareketleri
                </h5>
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-outline-primary active">Bugün</button>
                    <button class="btn btn-outline-primary">Bu Hafta</button>
                    <button class="btn btn-outline-primary">Bu Ay</button>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-modern">
                        <thead>
                            <tr>
                                <th>İrsaliye No</th>
                                <th>Alıcı</th>
                                <th>Durum</th>
                                <th>Sevkiyat Tarihi</th>
                                <th>Tahmini Teslimat</th>
                                <th>İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <strong>IRS2024-001248</strong>
                                    <br><small class="text-muted">Elektronik Malzeme</small>
                                </td>
                                <td>
                                    <div>
                                        <strong>ABC Teknoloji Ltd.</strong>
                                        <br><small class="text-muted">İstanbul, Kadıköy</small>
                                    </div>
                                </td>
                                <td><span class="dispatch-status status-transit">Yolda</span></td>
                                <td>01.10.2025 09:30</td>
                                <td>02.10.2025 16:00</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button class="action-btn btn-info-custom" title="Takip Et">
                                            <i class="mdi mdi-eye"></i>
                                        </button>
                                        <button class="action-btn btn-primary-custom" title="Düzenle">
                                            <i class="mdi mdi-pencil"></i>
                                        </button>
                                        <button class="action-btn btn-success-custom" title="PDF">
                                            <i class="mdi mdi-file-pdf"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>IRS2024-001247</strong>
                                    <br><small class="text-muted">Ofis Malzemeleri</small>
                                </td>
                                <td>
                                    <div>
                                        <strong>XYZ Danışmanlık A.Ş.</strong>
                                        <br><small class="text-muted">Ankara, Çankaya</small>
                                    </div>
                                </td>
                                <td><span class="dispatch-status status-delivered">Teslim Edildi</span></td>
                                <td>30.09.2025 14:15</td>
                                <td>01.10.2025 10:30</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button class="action-btn btn-info-custom" title="Takip Et">
                                            <i class="mdi mdi-eye"></i>
                                        </button>
                                        <button class="action-btn btn-success-custom" title="PDF">
                                            <i class="mdi mdi-file-pdf"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>IRS2024-001246</strong>
                                    <br><small class="text-muted">İnşaat Malzemesi</small>
                                </td>
                                <td>
                                    <div>
                                        <strong>DEF İnşaat Ltd.</strong>
                                        <br><small class="text-muted">İzmir, Konak</small>
                                    </div>
                                </td>
                                <td><span class="dispatch-status status-sent">Gönderildi</span></td>
                                <td>01.10.2025 11:45</td>
                                <td>03.10.2025 14:00</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button class="action-btn btn-info-custom" title="Takip Et">
                                            <i class="mdi mdi-eye"></i>
                                        </button>
                                        <button class="action-btn btn-primary-custom" title="Düzenle">
                                            <i class="mdi mdi-pencil"></i>
                                        </button>
                                        <button class="action-btn btn-success-custom" title="PDF">
                                            <i class="mdi mdi-file-pdf"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>IRS2024-001245</strong>
                                    <br><small class="text-muted">Gıda Ürünleri</small>
                                </td>
                                <td>
                                    <div>
                                        <strong>GHI Market Zinciri</strong>
                                        <br><small class="text-muted">Bursa, Nilüfer</small>
                                    </div>
                                </td>
                                <td><span class="dispatch-status status-draft">Taslak</span></td>
                                <td>-</td>
                                <td>-</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button class="action-btn btn-primary-custom" title="Düzenle">
                                            <i class="mdi mdi-pencil"></i>
                                        </button>
                                        <button class="action-btn btn-warning-custom" title="Gönder">
                                            <i class="mdi mdi-send"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Delivery Tracking -->
    <div class="col-xl-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="mdi mdi-map-marker-path me-2"></i>
                    Aktif Teslimat Takibi
                </h5>
            </div>
            <div class="card-body">
                <div class="delivery-progress">
                    <h6 class="mb-3">İrsaliye: IRS2024-001248</h6>
                    
                    <div class="vehicle-info">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong>Araç: 34 ABC 123</strong>
                                <br><small class="text-muted">Şoför: Mehmet Yılmaz</small>
                            </div>
                            <div class="text-end">
                                <i class="mdi mdi-phone text-success" style="font-size: 1.2rem;"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="progress-step completed">
                        <div class="step-icon completed">
                            <i class="mdi mdi-check"></i>
                        </div>
                        <div class="ms-4">
                            <h6 class="mb-1">Sevkiyat Hazırlandı</h6>
                            <small class="text-muted">01.10.2025 08:30</small>
                        </div>
                    </div>
                    
                    <div class="progress-step completed">
                        <div class="step-icon completed">
                            <i class="mdi mdi-check"></i>
                        </div>
                        <div class="ms-4">
                            <h6 class="mb-1">Depodan Çıkış</h6>
                            <small class="text-muted">01.10.2025 09:30</small>
                        </div>
                    </div>
                    
                    <div class="progress-step active">
                        <div class="step-icon active">
                            <i class="mdi mdi-truck"></i>
                        </div>
                        <div class="ms-4">
                            <h6 class="mb-1">Yolda</h6>
                            <small class="text-muted">Tahmini varış: 02.10.2025 16:00</small>
                        </div>
                    </div>
                    
                    <div class="progress-step">
                        <div class="step-icon">
                            <i class="mdi mdi-clock-outline"></i>
                        </div>
                        <div class="ms-4">
                            <h6 class="mb-1">Teslimat</h6>
                            <small class="text-muted">Bekleniyor</small>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4">
                    <h6 class="mb-3">Paket Detayları</h6>
                    <div class="package-item">
                        <div class="d-flex justify-content-between">
                            <div>
                                <strong>Elektronik Cihazlar</strong>
                                <br><small class="text-muted">5 adet • 12.5 kg</small>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-primary">Fragile</span>
                            </div>
                        </div>
                    </div>
                    <div class="package-item">
                        <div class="d-flex justify-content-between">
                            <div>
                                <strong>Yedek Parçalar</strong>
                                <br><small class="text-muted">12 adet • 8.2 kg</small>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-info">Normal</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Search and Filter Section -->
<div class="row mt-4">
    <div class="col-12">
        <div class="search-filter-box">
            <div class="row">
                <div class="col-md-3">
                    <label class="form-label">İrsaliye No</label>
                    <input type="text" class="form-control" placeholder="IRS2024-001xxx">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Alıcı Firma</label>
                    <input type="text" class="form-control" placeholder="Firma adı...">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Durum</label>
                    <select class="form-select">
                        <option>Tümü</option>
                        <option>Taslak</option>
                        <option>Gönderildi</option>
                        <option>Yolda</option>
                        <option>Teslim Edildi</option>
                        <option>Reddedilen</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Tarih Aralığı</label>
                    <input type="date" class="form-control">
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid">
                        <button class="btn btn-primary-custom">
                            <i class="mdi mdi-magnify me-1"></i> Ara
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // İstatistik kartlarına animasyon ekle
    document.addEventListener('DOMContentLoaded', function() {
        const statsNumbers = document.querySelectorAll('.stats-number');
        
        statsNumbers.forEach((stat, index) => {
            const finalValue = stat.textContent;
            const isPercentage = finalValue.includes('%');
            const isCurrency = finalValue.includes('₺');
            let numericValue = parseInt(finalValue.replace(/[^\d]/g, ''));
            
            stat.textContent = '0';
            
            setTimeout(() => {
                let current = 0;
                const increment = numericValue / 30;
                const timer = setInterval(() => {
                    current += increment;
                    if (current >= numericValue) {
                        current = numericValue;
                        clearInterval(timer);
                    }
                    
                    let displayValue = Math.floor(current).toLocaleString();
                    if (isPercentage) displayValue = '%' + displayValue;
                    if (isCurrency) displayValue = '₺' + displayValue;
                    
                    stat.textContent = displayValue;
                }, 50);
            }, index * 200);
        });
    });
    
    // Gerçek zamanlı güncelleme simülasyonu
    setInterval(() => {
        const statusIndicator = document.querySelector('.status-indicator');
        if (statusIndicator) {
            statusIndicator.style.opacity = statusIndicator.style.opacity === '0.5' ? '1' : '0.5';
        }
    }, 2000);
</script>
@endpush
