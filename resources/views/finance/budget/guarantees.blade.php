@extends('layouts.master')
@section('title', 'Teminatlar ve Sigortalar')
@section('topbar-title', 'Finans - Teminatlar ve Sigortalar')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css" rel="stylesheet">
<style>
.guarantee-card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
    transition: all 0.3s ease;
    margin-bottom: 1.5rem;
    background: white;
}

.guarantee-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
}

.page-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 2rem 0;
    margin-bottom: 2rem;
    border-radius: 0 0 20px 20px;
}

.alert-custom {
    border: none;
    border-radius: 12px;
    padding: 1rem 1.5rem;
    margin-bottom: 1.5rem;
    font-weight: 500;
}

.alert-warning-custom {
    background: linear-gradient(135deg, #ffeaa7, #fdcb6e);
    color: #2d3436;
}

.guarantee-summary {
    background: linear-gradient(135deg, #74b9ff, #0984e3);
    color: white;
    border-radius: 15px;
    padding: 2rem;
    margin-bottom: 2rem;
    position: relative;
    overflow: hidden;
}

.guarantee-summary::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    animation: shimmer 3s ease-in-out infinite;
}

@keyframes shimmer {
    0%, 100% { transform: rotate(0deg); }
    50% { transform: rotate(5deg); }
}

.floating-animation {
    animation: float 6s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
}

.notification-dot {
    position: absolute;
    top: -5px;
    right: -5px;
    width: 15px;
    height: 15px;
    background: #e74c3c;
    border-radius: 50%;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); opacity: 1; }
    50% { transform: scale(1.2); opacity: 0.7; }
    100% { transform: scale(1); opacity: 1; }
}

.guarantee-type-badge {
    position: relative;
    display: inline-block;
}

.stats-counter {
    font-size: 2.5rem;
    font-weight: 700;
    background: linear-gradient(45deg, #667eea, #764ba2);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.loading-spinner {
    display: none;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 9999;
}

.overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 9998;
}

/* Responsive improvements */
@media (max-width: 768px) {
    .metric-card {
        margin-bottom: 1rem;
    }
    
    .filter-card {
        padding: 1rem;
    }
    
    .search-box {
        margin-bottom: 1rem;
    }
    
    .table-responsive {
        border-radius: 12px;
        font-size: 0.85rem;
    }
    
    .btn-group .btn {
        padding: 0.25rem 0.5rem;
    }
    
    .guarantee-type-icon {
        width: 40px;
        height: 40px;
        font-size: 1.2rem;
    }
}

@media (max-width: 576px) {
    .page-title-box h4 {
        font-size: 1.25rem;
    }
    
    .metric-card {
        padding: 1rem;
    }
    
    .metric-card h4 {
        font-size: 1.5rem;
    }
    
    .breadcrumb {
        font-size: 0.8rem;
    }
}

.status-badge {
    padding: 0.4rem 0.8rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-active { background: linear-gradient(135deg, #28a745, #20c997); color: white; }
.status-warning { background: linear-gradient(135deg, #ffc107, #fd7e14); color: white; }
.status-expired { background: linear-gradient(135deg, #dc3545, #e83e8c); color: white; }
.status-pending { background: linear-gradient(135deg, #6f42c1, #6610f2); color: white; }

.metric-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 15px;
    color: white;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    position: relative;
    overflow: hidden;
}

.metric-card::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 100px;
    height: 100px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    transform: translate(30px, -30px);
}

.metric-card.primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
.metric-card.success { background: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%); }
.metric-card.warning { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
.metric-card.info { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }

.progress-ring {
    width: 80px;
    height: 80px;
    position: relative;
}

.progress-ring svg {
    width: 100%;
    height: 100%;
    transform: rotate(-90deg);
}

.progress-ring circle {
    fill: none;
    stroke-width: 6;
    r: 32;
    cx: 40;
    cy: 40;
}

.guarantee-type-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    margin-right: 1rem;
}

.btn-custom {
    border-radius: 8px;
    padding: 0.5rem 1.2rem;
    font-weight: 600;
    transition: all 0.3s ease;
    border: none;
}

.btn-primary-custom {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn-primary-custom:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

.table-modern {
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
}

.table-modern thead {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.table-modern tbody tr {
    transition: all 0.3s ease;
}

.table-modern tbody tr:hover {
    background-color: #f8f9fe;
    transform: scale(1.002);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    cursor: pointer;
}

.filter-card {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    border-radius: 15px;
    color: white;
    padding: 1.5rem;
    margin-bottom: 2rem;
}

.search-box {
    border-radius: 25px;
    border: 2px solid #e9ecef;
    padding: 0.75rem 1.5rem;
    transition: all 0.3s ease;
}

.search-box:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Teminatlar ve Sigortalar</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('finance.dashboard') }}">Finans</a></li>
                        <li class="breadcrumb-item"><a href="#">Bütçe</a></li>
                        <li class="breadcrumb-item active">Teminatlar ve Sigortalar</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert for expiring guarantees -->
    @if($expiringGuarantees > 0)
    <div class="row">
        <div class="col-12">
            <div class="alert alert-warning-custom alert-custom animate__animated animate__fadeInDown">
                <div class="d-flex align-items-center">
                    <i class="mdi mdi-alert-circle me-2" style="font-size: 1.5rem;"></i>
                    <div>
                        <strong>Dikkat!</strong> {{ $expiringGuarantees }} teminatın süresi 30 gün içinde dolacak. 
                        <a href="#" class="text-decoration-underline">Detayları görüntüle</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Key Metrics Row -->
    <div class="row animate__animated animate__fadeInUp">
        <div class="col-xl-3 col-md-6">
            <div class="metric-card primary animate__animated animate__zoomIn" style="animation-delay: 0.1s;">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-white-75 mb-2">Toplam Teminat</p>
                        <h4 class="mb-0 text-white">₺{{ number_format($totalGuaranteeAmount, 0, ',', '.') }}</h4>
                        <small class="text-white-75">{{ $activeGuarantees }} Aktif Teminat</small>
                    </div>
                    <div class="progress-ring floating-animation">
                        <svg>
                            <circle class="progress-ring-bg" stroke="rgba(255,255,255,0.2)" stroke-dasharray="201.06" stroke-dashoffset="0"></circle>
                            <circle class="progress-ring-fill" stroke="#ffffff" stroke-dasharray="201.06" stroke-dashoffset="60.32"></circle>
                        </svg>
                        <div class="position-absolute top-50 start-50 translate-middle text-white font-weight-bold">70%</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="metric-card success animate__animated animate__zoomIn" style="animation-delay: 0.2s;">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-white-75 mb-2">Sigorta Primleri</p>
                        <h4 class="mb-0 text-white">₺{{ number_format($monthlyInsurancePremium, 0, ',', '.') }}</h4>
                        <small class="text-white-75">Aylık Prim Tutarı</small>
                    </div>
                    <div class="text-white" style="font-size: 2.5rem;">
                        <i class="mdi mdi-shield-check"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="metric-card warning animate__animated animate__zoomIn" style="animation-delay: 0.3s;">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-white-75 mb-2">Son Kullanma</p>
                        <h4 class="mb-0 text-white">{{ $expiringGuarantees }} Teminat</h4>
                        <small class="text-white-75">30 Gün İçinde</small>
                    </div>
                    <div class="text-white" style="font-size: 2.5rem;">
                        <i class="mdi mdi-clock-alert"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="metric-card info animate__animated animate__zoomIn" style="animation-delay: 0.4s;">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-white-75 mb-2">Risk Skoru</p>
                        <h4 class="mb-0 text-white">Düşük</h4>
                        <small class="text-white-75">Portföy Riski</small>
                    </div>
                    <div class="text-white" style="font-size: 2.5rem;">
                        <i class="mdi mdi-chart-line"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter and Search Section -->
    <div class="row">
        <div class="col-12">
            <div class="filter-card">
                <div class="row align-items-center">
                    <div class="col-lg-4">
                        <div class="mb-3 mb-lg-0">
                            <input type="text" class="form-control search-box text-dark" placeholder="Teminat ara...">
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="d-flex flex-wrap gap-2">
                            <select class="form-select" style="width: auto;">
                                <option>Tüm Türler</option>
                                <option>Kesin Teminat</option>
                                <option>Geçici Teminat</option>
                                <option>Avans Teminatı</option>
                                <option>İş Bitirme Teminatı</option>
                            </select>
                            <select class="form-select" style="width: auto;">
                                <option>Tüm Durumlar</option>
                                <option>Aktif</option>
                                <option>Süresi Dolacak</option>
                                <option>Süresi Dolmuş</option>
                                <option>Beklemede</option>
                            </select>
                            <button class="btn btn-light">
                                <i class="mdi mdi-filter"></i> Filtrele
                            </button>
                            <button class="btn btn-light" data-export>
                                <i class="mdi mdi-download"></i> Dışa Aktar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Tabs -->
    <div class="row">
        <div class="col-12">
            <div class="card guarantee-card animate__animated animate__fadeInUp" style="animation-delay: 0.6s;">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#guarantees-tab" role="tab">
                                <i class="mdi mdi-bank me-2"></i>Teminatlar
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#insurance-tab" role="tab">
                                <i class="mdi mdi-shield me-2"></i>Sigortalar
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#reports-tab" role="tab">
                                <i class="mdi mdi-chart-bar me-2"></i>Raporlar
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#calendar-tab" role="tab">
                                <i class="mdi mdi-calendar me-2"></i>Takvim
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <!-- Guarantees Tab -->
                        <div class="tab-pane fade show active" id="guarantees-tab" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h5 class="mb-0">Teminat Yönetimi</h5>
                                <button class="btn btn-primary-custom">
                                    <i class="mdi mdi-plus me-2"></i>Yeni Teminat Ekle
                                </button>
                            </div>
                            
                            <div class="table-responsive">
                                <table class="table table-modern table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>Teminat No</th>
                                            <th>Tür</th>
                                            <th>Proje</th>
                                            <th>Tutar</th>
                                            <th>Banka</th>
                                            <th>Başlangıç</th>
                                            <th>Bitiş</th>
                                            <th>Durum</th>
                                            <th>İşlemler</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($guarantees as $guarantee)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="guarantee-type-icon" style="background: rgba(102, 126, 234, 0.1); color: #667eea;">
                                                        <i class="mdi mdi-bank"></i>
                                                    </div>
                                                    <div>
                                                        <strong>{{ $guarantee['guarantee_no'] }}</strong>
                                                        <br><small class="text-muted">Referans: {{ $guarantee['reference'] }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $guarantee['type_color'] }}">{{ $guarantee['type'] }}</span>
                                            </td>
                                            <td>
                                                <strong>{{ $guarantee['project_name'] }}</strong>
                                                <br><small class="text-muted">{{ $guarantee['project_detail'] }}</small>
                                            </td>
                                            <td>
                                                <strong>₺{{ number_format($guarantee['amount'], 0, ',', '.') }}</strong>
                                                <br><small class="text-muted">%{{ $guarantee['percentage'] }} Oranında</small>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ asset('images/banks/' . $guarantee['bank_logo']) }}" class="rounded me-2" width="24" height="24" style="object-fit: contain;" onerror="this.style.display='none'">
                                                    <span>{{ $guarantee['bank'] }}</span>
                                                </div>
                                            </td>
                                            <td>{{ date('d.m.Y', strtotime($guarantee['start_date'])) }}</td>
                                            <td>{{ date('d.m.Y', strtotime($guarantee['end_date'])) }}</td>
                                            <td><span class="status-badge status-{{ $guarantee['status'] }}">{{ $guarantee['status_text'] }}</span></td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button class="btn btn-sm btn-outline-primary" title="Görüntüle">
                                                        <i class="mdi mdi-eye"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-warning" title="Düzenle">
                                                        <i class="mdi mdi-pencil"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-info" title="Uzat">
                                                        <i class="mdi mdi-calendar-plus"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <!-- Insurance Tab -->
                        <div class="tab-pane fade" id="insurance-tab" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h5 class="mb-0">Sigorta Poliçeleri</h5>
                                <button class="btn btn-primary-custom">
                                    <i class="mdi mdi-plus me-2"></i>Yeni Poliçe Ekle
                                </button>
                            </div>
                            
                            <div class="row">
                                @foreach($insurances as $insurance)
                                <div class="col-lg-4 col-md-6 mb-4">
                                    <div class="card guarantee-card h-100">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="guarantee-type-icon" style="background: {{ $insurance['icon_color'] }}20; color: {{ $insurance['icon_color'] }};">
                                                    <i class="{{ $insurance['icon'] }}"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-1">{{ $insurance['type'] }}</h6>
                                                    <small class="text-muted">{{ $insurance['policy_no'] }}</small>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <p class="mb-1"><strong>Sigorta Şirketi:</strong> {{ $insurance['company'] }}</p>
                                                <p class="mb-1"><strong>Sigorta Bedeli:</strong> ₺{{ number_format($insurance['coverage_amount'], 0, ',', '.') }}</p>
                                                <p class="mb-1"><strong>Prim Tutarı:</strong> ₺{{ number_format($insurance['premium_amount'], 0, ',', '.') }}/yıl</p>
                                                <p class="mb-1"><strong>Geçerlilik:</strong> {{ date('d.m.Y', strtotime($insurance['start_date'])) }} - {{ date('d.m.Y', strtotime($insurance['end_date'])) }}</p>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="status-badge status-{{ $insurance['status'] }}">{{ $insurance['status_text'] }}</span>
                                                <div class="btn-group" role="group">
                                                    <button class="btn btn-sm btn-outline-primary">
                                                        <i class="mdi mdi-eye"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-warning">
                                                        <i class="mdi mdi-pencil"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        
                        <!-- Reports Tab -->
                        <div class="tab-pane fade" id="reports-tab" role="tabpanel">
                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="card guarantee-card">
                                        <div class="card-header">
                                            <h5 class="mb-0">Teminat Dağılımı</h5>
                                        </div>
                                        <div class="card-body">
                                            <div id="guaranteeChart" style="height: 350px;"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="card guarantee-card">
                                        <div class="card-header">
                                            <h5 class="mb-0">Risk Analizi</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-4">
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span>Yüksek Risk</span>
                                                    <span class="text-danger font-weight-bold">{{ $riskAnalysis['high_risk'] }}%</span>
                                                </div>
                                                <div class="progress mb-3" style="height: 8px;">
                                                    <div class="progress-bar bg-danger" style="width: {{ $riskAnalysis['high_risk'] }}%"></div>
                                                </div>
                                            </div>
                                            <div class="mb-4">
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span>Orta Risk</span>
                                                    <span class="text-warning font-weight-bold">{{ $riskAnalysis['medium_risk'] }}%</span>
                                                </div>
                                                <div class="progress mb-3" style="height: 8px;">
                                                    <div class="progress-bar bg-warning" style="width: {{ $riskAnalysis['medium_risk'] }}%"></div>
                                                </div>
                                            </div>
                                            <div class="mb-4">
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span>Düşük Risk</span>
                                                    <span class="text-success font-weight-bold">{{ $riskAnalysis['low_risk'] }}%</span>
                                                </div>
                                                <div class="progress mb-3" style="height: 8px;">
                                                    <div class="progress-bar bg-success" style="width: {{ $riskAnalysis['low_risk'] }}%"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Calendar Tab -->
                        <div class="tab-pane fade" id="calendar-tab" role="tabpanel">
                            <div class="card guarantee-card">
                                <div class="card-header">
                                    <h5 class="mb-0">Teminat ve Sigorta Takvimi</h5>
                                </div>
                                <div class="card-body">
                                    <div id="calendar" style="height: 600px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Guarantee Modal -->
<div class="modal fade" id="guaranteeModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Yeni Teminat Ekle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Teminat Türü</label>
                                <select class="form-select">
                                    <option>Kesin Teminat</option>
                                    <option>Geçici Teminat</option>
                                    <option>Avans Teminatı</option>
                                    <option>İş Bitirme Teminatı</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Proje</label>
                                <select class="form-select">
                                    <option>Metro Projesi</option>
                                    <option>Hastane İnşaatı</option>
                                    <option>Okul İnşaatı</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Banka</label>
                                <select class="form-select">
                                    <option>Garanti BBVA</option>
                                    <option>İş Bankası</option>
                                    <option>Akbank</option>
                                    <option>Yapı Kredi</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Teminat Tutarı</label>
                                <input type="number" class="form-control" placeholder="0.00">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Başlangıç Tarihi</label>
                                <input type="date" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Bitiş Tarihi</label>
                                <input type="date" class="form-control">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                <button type="button" class="btn btn-primary-custom">Kaydet</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Show loading spinner
    function showLoading() {
        document.querySelector('.overlay').style.display = 'block';
        document.querySelector('.loading-spinner').style.display = 'block';
    }
    
    function hideLoading() {
        document.querySelector('.overlay').style.display = 'none';
        document.querySelector('.loading-spinner').style.display = 'none';
    }
    
    // Animate counters
    function animateCounters() {
        const counters = document.querySelectorAll('.stats-counter');
        counters.forEach(counter => {
            const target = parseInt(counter.textContent.replace(/[^\d]/g, ''));
            let current = 0;
            const increment = target / 100;
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    counter.textContent = counter.textContent.replace(/[\d,]+/, target.toLocaleString());
                    clearInterval(timer);
                } else {
                    counter.textContent = counter.textContent.replace(/[\d,]+/, Math.floor(current).toLocaleString());
                }
            }, 20);
        });
    }
    
    // Initialize animations
    setTimeout(animateCounters, 500);
    
    // Search functionality
    const searchBox = document.querySelector('.search-box');
    if (searchBox) {
        searchBox.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const tableRows = document.querySelectorAll('#guarantees-tab tbody tr');
            
            tableRows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    row.style.display = '';
                    row.classList.add('animate__animated', 'animate__fadeIn');
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }
    
    // Guarantee Distribution Chart
    var guaranteeOptions = {
        series: [{
            name: 'Tutar',
            data: @json($guaranteeChartData['data'])
        }],
        chart: {
            type: 'bar',
            height: 350,
            toolbar: {
                show: false
            },
            animations: {
                enabled: true,
                easing: 'easeinout',
                speed: 800,
                animateGradually: {
                    enabled: true,
                    delay: 150
                },
                dynamicAnimation: {
                    enabled: true,
                    speed: 350
                }
            }
        },
        colors: ['#667eea'],
        plotOptions: {
            bar: {
                borderRadius: 8,
                horizontal: false,
                columnWidth: '60%',
                dataLabels: {
                    position: 'top',
                }
            }
        },
        dataLabels: {
            enabled: true,
            formatter: function (val) {
                return "₺" + val.toLocaleString();
            },
            offsetY: -20,
            style: {
                fontSize: '10px',
                colors: ["#304758"]
            }
        },
        xaxis: {
            categories: @json($guaranteeChartData['categories']),
            labels: {
                style: {
                    fontSize: '12px'
                }
            }
        },
        yaxis: {
            labels: {
                formatter: function (val) {
                    return "₺" + val.toLocaleString()
                }
            }
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return "₺" + val.toLocaleString()
                }
            }
        },
        grid: {
            borderColor: '#f1f1f1',
        }
    };
    
    var guaranteeChart = new ApexCharts(document.querySelector("#guaranteeChart"), guaranteeOptions);
    guaranteeChart.render();
    
    // Calendar
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        height: 600,
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: [
            {
                title: 'Kesin Teminat Sona Erer',
                start: '2024-09-15',
                color: '#dc3545',
                textColor: 'white'
            },
            {
                title: 'Avans Teminatı Sona Erer',
                start: '2024-11-01',
                color: '#ffc107',
                textColor: 'black'
            },
            {
                title: 'Yangın Sigortası Yenileme',
                start: '2025-01-15',
                color: '#28a745',
                textColor: 'white'
            },
            {
                title: 'İş Kazası Sigortası Yenileme',
                start: '2025-03-01',
                color: '#17a2b8',
                textColor: 'white'
            }
        ],
        eventClick: function(info) {
            Swal.fire({
                title: info.event.title,
                text: 'Tarih: ' + info.event.start.toLocaleDateString('tr-TR'),
                icon: 'info',
                confirmButtonText: 'Tamam',
                confirmButtonColor: '#667eea'
            });
        },
        locale: 'tr'
    });
    calendar.render();
    
    // Button click handlers for actions
    document.querySelectorAll('.btn-outline-primary').forEach(btn => {
        if (btn.title === 'Görüntüle') {
            btn.addEventListener('click', function() {
                Swal.fire({
                    title: 'Teminat Detayları',
                    html: '<p>Teminat detayları burada gösterilecek.</p>',
                    icon: 'info',
                    confirmButtonColor: '#667eea'
                });
            });
        }
    });
    
    document.querySelectorAll('.btn-outline-warning').forEach(btn => {
        if (btn.title === 'Düzenle') {
            btn.addEventListener('click', function() {
                const modal = new bootstrap.Modal(document.getElementById('guaranteeModal'));
                modal.show();
            });
        }
    });
    
    document.querySelectorAll('.btn-outline-info').forEach(btn => {
        if (btn.title === 'Uzat') {
            btn.addEventListener('click', function() {
                Swal.fire({
                    title: 'Teminat Süresini Uzat',
                    input: 'date',
                    inputLabel: 'Yeni bitiş tarihi',
                    showCancelButton: true,
                    confirmButtonText: 'Uzat',
                    cancelButtonText: 'İptal',
                    confirmButtonColor: '#667eea'
                }).then((result) => {
                    if (result.isConfirmed) {
                        showLoading();
                        setTimeout(() => {
                            hideLoading();
                            Swal.fire({
                                title: 'Başarılı!',
                                text: 'Teminat süresi uzatıldı.',
                                icon: 'success',
                                confirmButtonColor: '#28a745'
                            });
                        }, 1500);
                    }
                });
            });
        }
    });
    
    // Tab change handlers
    document.querySelectorAll('[data-bs-toggle="tab"]').forEach(function(tab) {
        tab.addEventListener('shown.bs.tab', function(e) {
            if (e.target.getAttribute('href') === '#calendar-tab') {
                setTimeout(function() {
                    calendar.render();
                }, 100);
            } else if (e.target.getAttribute('href') === '#reports-tab') {
                setTimeout(function() {
                    guaranteeChart.render();
                }, 100);
            }
        });
    });
    
    // Add new guarantee button
    document.querySelector('.btn-primary-custom').addEventListener('click', function() {
        const modal = new bootstrap.Modal(document.getElementById('guaranteeModal'));
        modal.show();
    });
    
    // Filter functionality
    const filterSelects = document.querySelectorAll('.form-select');
    filterSelects.forEach(select => {
        select.addEventListener('change', function() {
            // Filter logic would go here
            console.log('Filter changed:', this.value);
        });
    });
    
    // Export functionality
    document.querySelector('[data-export]')?.addEventListener('click', function() {
        showLoading();
        setTimeout(() => {
            hideLoading();
            Swal.fire({
                title: 'Dışa Aktarma Tamamlandı',
                text: 'Dosya indirme işlemi başlatıldı.',
                icon: 'success',
                confirmButtonColor: '#28a745'
            });
        }, 2000);
    });
});
</script>

<!-- Loading Overlay -->
<div class="overlay"></div>
<div class="loading-spinner">
    <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
        <span class="visually-hidden">Yükleniyor...</span>
    </div>
</div>
@endpush
