@extends('layouts.master')
@section('title', 'Amortisman Yönetimi')
@section('topbar-title', 'Finans - Varlık Yönetimi')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.min.css" rel="stylesheet">
<style>
    .amortization-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        overflow: hidden;
        position: relative;
        background: white;
    }
    
    .amortization-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.15);
    }
    
    .amortization-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--card-gradient-start), var(--card-gradient-end));
    }
    
    .amortization-card.primary { --card-gradient-start: #667eea; --card-gradient-end: #764ba2; }
    .amortization-card.success { --card-gradient-start: #4facfe; --card-gradient-end: #00f2fe; }
    .amortization-card.danger { --card-gradient-start: #f093fb; --card-gradient-end: #f5576c; }
    .amortization-card.warning { --card-gradient-start: #43e97b; --card-gradient-end: #38f9d7; }
    .amortization-card.info { --card-gradient-start: #fa709a; --card-gradient-end: #fee140; }
    
    .metric-icon {
        width: 70px;
        height: 70px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        color: white;
        margin-bottom: 20px;
        background: linear-gradient(135deg, var(--icon-gradient-start), var(--icon-gradient-end));
    }
    
    .metric-icon.primary { --icon-gradient-start: #667eea; --icon-gradient-end: #764ba2; }
    .metric-icon.success { --icon-gradient-start: #11998e; --icon-gradient-end: #38ef7d; }
    .metric-icon.danger { --icon-gradient-start: #fc466b; --icon-gradient-end: #3f5efb; }
    .metric-icon.warning { --icon-gradient-start: #fdbb2d; --icon-gradient-end: #22c1c3; }
    .metric-icon.info { --icon-gradient-start: #fa709a; --icon-gradient-end: #fee140; }
    
    .metric-value {
        font-size: 2.5rem;
        font-weight: 800;
        color: #2c3e50;
        margin: 15px 0 5px 0;
        line-height: 1.2;
    }
    
    .metric-label {
        color: #7f8c8d;
        font-size: 0.95rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin-bottom: 10px;
    }
    
    .metric-subtitle {
        color: #95a5a6;
        font-size: 0.85rem;
        font-weight: 500;
    }
    
    .progress-custom {
        height: 8px;
        border-radius: 10px;
        background: #f8f9fa;
        overflow: hidden;
        margin: 15px 0;
    }
    
    .progress-custom .progress-bar {
        border-radius: 10px;
        background: linear-gradient(90deg, var(--progress-start), var(--progress-end));
        transition: width 0.8s ease;
    }
    
    .progress-custom.danger { --progress-start: #ff6b6b; --progress-end: #ee5a52; }
    .progress-custom.success { --progress-start: #51cf66; --progress-end: #40c057; }
    .progress-custom.warning { --progress-start: #ffd43b; --progress-end: #fab005; }
    
    .chart-container {
        position: relative;
        height: 350px;
        background: white;
        border-radius: 15px;
        padding: 25px;
        margin: 20px 0;
    }
    
    .chart-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 20px;
        text-align: center;
    }
    
    .asset-row {
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 15px;
        background: white;
        border: 1px solid #e9ecef;
        transition: all 0.3s ease;
    }
    
    .asset-row:hover {
        background: #f8f9fa;
        border-color: #dee2e6;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }
    
    .asset-avatar {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 20px;
        margin-right: 15px;
    }
    
    .asset-info h6 {
        margin: 0 0 5px 0;
        font-weight: 700;
        color: #2c3e50;
    }
    
    .asset-info small {
        color: #7f8c8d;
        font-weight: 500;
    }
    
    .depreciation-badge {
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    
    .depreciation-low { background: #d1ecf1; color: #0c5460; }
    .depreciation-medium { background: #fff3cd; color: #856404; }
    .depreciation-high { background: #f8d7da; color: #721c24; }
    
    .category-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
        border: 1px solid #e9ecef;
        transition: all 0.3s ease;
    }
    
    .category-card:hover {
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        border-color: #dee2e6;
    }
    
    .category-header {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
    }
    
    .category-icon {
        width: 45px;
        height: 45px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 20px;
        margin-right: 15px;
        background: linear-gradient(135deg, #667eea, #764ba2);
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin: 30px 0;
    }
    
    .trend-indicator {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 0.8rem;
        padding: 4px 8px;
        border-radius: 12px;
        font-weight: 600;
    }
    
    .trend-up { background: #d4edda; color: #155724; }
    .trend-down { background: #f8d7da; color: #721c24; }
    .trend-stable { background: #d1ecf1; color: #0c5460; }
    
    .filter-tabs {
        background: white;
        border-radius: 12px;
        padding: 8px;
        margin-bottom: 25px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    
    .filter-tab {
        padding: 12px 24px;
        border: none;
        background: transparent;
        color: #7f8c8d;
        font-weight: 600;
        border-radius: 8px;
        transition: all 0.3s ease;
        margin-right: 5px;
    }
    
    .filter-tab.active {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }
    
    /* Animasyonlar */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(-20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    .amortization-card {
        animation: fadeInUp 0.6s ease forwards;
    }
    
    .amortization-card:nth-child(1) { animation-delay: 0.1s; }
    .amortization-card:nth-child(2) { animation-delay: 0.2s; }
    .amortization-card:nth-child(3) { animation-delay: 0.3s; }
    .amortization-card:nth-child(4) { animation-delay: 0.4s; }
    
    .asset-row {
        animation: slideIn 0.5s ease forwards;
    }
    
    /* Loading skeleton */
    .skeleton {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: loading 1.5s infinite;
    }
    
    @keyframes loading {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }
    
    /* Tooltip stilleri */
    .tooltip-custom {
        position: relative;
        cursor: help;
    }
    
    .tooltip-custom:hover::after {
        content: attr(data-tooltip);
        position: absolute;
        bottom: 100%;
        left: 50%;
        transform: translateX(-50%);
        background: rgba(0,0,0,0.8);
        color: white;
        padding: 8px 12px;
        border-radius: 6px;
        font-size: 12px;
        white-space: nowrap;
        z-index: 1000;
    }
    
    /* Print stilleri */
    @media print {
        .filter-tabs, .btn { display: none !important; }
        .amortization-card { box-shadow: none; border: 1px solid #ddd; }
        .chart-container { height: 300px; }
    }

    @media (max-width: 768px) {
        .metric-value { font-size: 2rem; }
        .metric-icon { width: 55px; height: 55px; font-size: 22px; }
        .stats-grid { grid-template-columns: 1fr; gap: 15px; }
        .chart-container { height: 280px; padding: 20px; }
        
        .filter-tab {
            padding: 10px 16px;
            font-size: 0.9rem;
            margin-bottom: 5px;
            display: block;
            text-align: center;
        }
        
        .table-responsive {
            font-size: 0.85rem;
        }
        
        .asset-avatar {
            width: 40px;
            height: 40px;
            font-size: 16px;
        }
    }
    
    @media (max-width: 576px) {
        .filter-tabs {
            padding: 5px;
        }
        
        .filter-tab {
            padding: 8px 12px;
            font-size: 0.8rem;
            margin: 2px;
            border-radius: 6px;
        }
        
        .metric-icon {
            width: 50px;
            height: 50px;
            font-size: 20px;
        }
        
        .metric-value {
            font-size: 1.6rem;
        }
        
        .chart-container {
            height: 250px;
            padding: 15px;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    
    <!-- Sayfa Başlığı ve Özet -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-2">
                        <i class="mdi mdi-trending-down text-danger me-2"></i>
                        Amortisman Yönetimi
                    </h4>
                    <p class="text-muted mb-0">
                        Varlıklarınızın amortisman analizi ve değer kaybı takibi
                        <span class="badge bg-light text-dark ms-2">
                            Son güncelleme: {{ now()->format('d.m.Y H:i') }}
                        </span>
                    </p>
                </div>
                <div class="d-flex gap-2">
                    <div class="dropdown">
                        <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="mdi mdi-cog"></i> Ayarlar
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#" onclick="exportToExcel()"><i class="mdi mdi-file-excel me-2"></i>Excel'e Aktar</a></li>
                            <li><a class="dropdown-item" href="#" onclick="printReport()"><i class="mdi mdi-printer me-2"></i>Yazdır</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#"><i class="mdi mdi-email me-2"></i>E-posta Gönder</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Ana Metrikler -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="amortization-card primary">
                <div class="card-body text-center">
                    <div class="metric-icon primary">
                        <i class="mdi mdi-cash-multiple"></i>
                    </div>
                    <div class="metric-value">₺{{ number_format($totalPurchaseValue, 0, ',', '.') }}</div>
                    <div class="metric-label">Toplam Varlık Değeri</div>
                    <div class="metric-subtitle">{{ $assets->count() }} adet varlık</div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="amortization-card success">
                <div class="card-body text-center">
                    <div class="metric-icon success">
                        <i class="mdi mdi-trending-up"></i>
                    </div>
                    <div class="metric-value">₺{{ number_format($totalCurrentValue, 0, ',', '.') }}</div>
                    <div class="metric-label">Güncel Değer</div>
                    <div class="metric-subtitle">Mevcut piyasa değeri</div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="amortization-card danger">
                <div class="card-body text-center">
                    <div class="metric-icon danger">
                        <i class="mdi mdi-trending-down"></i>
                    </div>
                    <div class="metric-value">₺{{ number_format($totalDepreciation, 0, ',', '.') }}</div>
                    <div class="metric-label">Toplam Amortisman</div>
                    <div class="metric-subtitle">
                        %{{ $totalPurchaseValue > 0 ? number_format(($totalDepreciation / $totalPurchaseValue) * 100, 1) : 0 }} değer kaybı
                    </div>
                    <div class="progress-custom danger">
                        <div class="progress-bar" style="width: {{ $totalPurchaseValue > 0 ? ($totalDepreciation / $totalPurchaseValue) * 100 : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="amortization-card warning">
                <div class="card-body text-center">
                    <div class="metric-icon warning">
                        <i class="mdi mdi-calendar-clock"></i>
                    </div>
                    <div class="metric-value">₺{{ number_format($monthlyDepreciation, 0, ',', '.') }}</div>
                    <div class="metric-label">Aylık Amortisman</div>
                    <div class="metric-subtitle">₺{{ number_format($annualDepreciation, 0, ',', '.') }} yıllık</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtre Sekmeleri -->
    <div class="filter-tabs">
        <button class="filter-tab active" onclick="showTab('overview')">
            <i class="mdi mdi-view-dashboard"></i> Genel Bakış
        </button>
        <button class="filter-tab" onclick="showTab('categories')">
            <i class="mdi mdi-shape"></i> Kategoriler
        </button>
        <button class="filter-tab" onclick="showTab('age-analysis')">
            <i class="mdi mdi-clock-outline"></i> Yaş Analizi
        </button>
        <button class="filter-tab" onclick="showTab('assets')">
            <i class="mdi mdi-format-list-bulleted"></i> Varlık Listesi
        </button>
    </div>

    <!-- Genel Bakış Tab -->
    <div id="overview-tab" class="tab-content">
        <div class="row">
            <div class="col-xl-8">
                <div class="amortization-card">
                    <div class="card-body">
                        <div class="chart-title">
                            <i class="mdi mdi-chart-donut text-primary"></i>
                            Kategoriye Göre Amortisman Dağılımı
                        </div>
                        <div class="chart-container">
                            <canvas id="categoryChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-4">
                <div class="amortization-card">
                    <div class="card-body">
                        <h5 class="card-title mb-4">
                            <i class="mdi mdi-information text-info"></i>
                            Amortisman Metodları
                        </h5>
                        
                        @forelse($methodDistribution as $method => $data)
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ ucfirst($method ?: 'Belirtilmemiş') }}</h6>
                                <small class="text-muted">{{ $data['count'] }} varlık</small>
                            </div>
                            <div class="text-end">
                                <div class="h6 mb-0">₺{{ number_format($data['depreciation'], 0, ',', '.') }}</div>
                                <small class="text-muted">amortisman</small>
                            </div>
                        </div>
                        @empty
                        <p class="text-muted text-center">Henüz amortisman verisi yok</p>
                        @endforelse
                    </div>
                </div>
                
                <div class="amortization-card mt-3">
                    <div class="card-body">
                        <h5 class="card-title mb-4">
                            <i class="mdi mdi-alert-circle text-warning"></i>
                            En Çok Değer Kaybedenler
                        </h5>
                        
                        @forelse($topDepreciatedAssets->take(5) as $asset)
                        <div class="d-flex align-items-center mb-3">
                            <div class="asset-avatar" style="background: linear-gradient(135deg, #{{ sprintf('%06X', mt_rand(0, 0xFFFFFF)) }}, #{{ sprintf('%06X', mt_rand(0, 0xFFFFFF)) }});">
                                <i class="mdi {{ $asset->category_icon }}"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ Str::limit($asset->name, 20) }}</h6>
                                <small class="text-muted">{{ $asset->category }}</small>
                            </div>
                            <div class="text-end">
                                <div class="depreciation-badge depreciation-high">
                                    <i class="mdi mdi-trending-down"></i>
                                    %{{ number_format($asset->depreciation_percentage, 1) }}
                                </div>
                            </div>
                        </div>
                        @empty
                        <p class="text-muted text-center">Veri bulunamadı</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Kategoriler Tab -->
    <div id="categories-tab" class="tab-content" style="display: none;">
        <div class="row">
            @forelse($depreciationByCategory as $category => $data)
            <div class="col-xl-6 col-lg-12">
                <div class="category-card">
                    <div class="category-header">
                        <div class="category-icon">
                            @php
                                $categoryIcons = [
                                    'computer' => 'mdi-laptop',
                                    'furniture' => 'mdi-sofa',
                                    'vehicle' => 'mdi-car',
                                    'equipment' => 'mdi-tools',
                                    'machinery' => 'mdi-factory',
                                    'building' => 'mdi-office-building',
                                    'land' => 'mdi-terrain',
                                    'software' => 'mdi-monitor',
                                    'mobile' => 'mdi-cellphone',
                                    'other' => 'mdi-package-variant'
                                ];
                            @endphp
                            <i class="mdi {{ $categoryIcons[$category] ?? 'mdi-package-variant' }}"></i>
                        </div>
                        <div>
                            <h5 class="mb-1">{{ $data['category_name'] }}</h5>
                            <small class="text-muted">{{ $data['count'] }} varlık</small>
                        </div>
                    </div>
                    
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="metric-value text-primary" style="font-size: 1.5rem;">
                                ₺{{ number_format($data['purchase_value'], 0, ',', '.') }}
                            </div>
                            <div class="metric-label" style="font-size: 0.8rem;">Alış Değeri</div>
                        </div>
                        <div class="col-4">
                            <div class="metric-value text-success" style="font-size: 1.5rem;">
                                ₺{{ number_format($data['current_value'], 0, ',', '.') }}
                            </div>
                            <div class="metric-label" style="font-size: 0.8rem;">Güncel Değer</div>
                        </div>
                        <div class="col-4">
                            <div class="metric-value text-danger" style="font-size: 1.5rem;">
                                %{{ number_format($data['depreciation_rate'], 1) }}
                            </div>
                            <div class="metric-label" style="font-size: 0.8rem;">Amortisman</div>
                        </div>
                    </div>
                    
                    <div class="progress-custom {{ $data['depreciation_rate'] > 70 ? 'danger' : ($data['depreciation_rate'] > 40 ? 'warning' : 'success') }}">
                        <div class="progress-bar" style="width: {{ $data['depreciation_rate'] }}%"></div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="mdi mdi-package-variant-closed display-1 text-muted"></i>
                    <h5 class="mt-3 text-muted">Henüz varlık verisi bulunmuyor</h5>
                </div>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Yaş Analizi Tab -->
    <div id="age-analysis-tab" class="tab-content" style="display: none;">
        <div class="row">
            <div class="col-xl-8">
                <div class="amortization-card">
                    <div class="card-body">
                        <div class="chart-title">
                            <i class="mdi mdi-chart-bar text-info"></i>
                            Varlık Yaşına Göre Amortisman Analizi
                        </div>
                        <div class="chart-container">
                            <canvas id="ageChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-4">
                <div class="amortization-card">
                    <div class="card-body">
                        <h5 class="card-title mb-4">
                            <i class="mdi mdi-clock-outline text-warning"></i>
                            Yaş Grupları Detayı
                        </h5>
                        
                        @forelse($depreciationByAge as $ageGroup => $data)
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="mb-0">{{ $ageGroup }}</h6>
                                <span class="badge bg-primary">{{ $data['count'] }}</span>
                            </div>
                            
                            <div class="row text-center small">
                                <div class="col-6">
                                    <div class="text-success fw-bold">₺{{ number_format($data['current_value'], 0, ',', '.') }}</div>
                                    <div class="text-muted">Güncel Değer</div>
                                </div>
                                <div class="col-6">
                                    <div class="text-danger fw-bold">%{{ number_format($data['depreciation_rate'], 1) }}</div>
                                    <div class="text-muted">Amortisman</div>
                                </div>
                            </div>
                            
                            <div class="progress-custom {{ $data['depreciation_rate'] > 60 ? 'danger' : ($data['depreciation_rate'] > 30 ? 'warning' : 'success') }} mt-2">
                                <div class="progress-bar" style="width: {{ $data['depreciation_rate'] }}%"></div>
                            </div>
                        </div>
                        @empty
                        <p class="text-muted text-center">Yaş analizi verisi yok</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Varlık Listesi Tab -->
    <div id="assets-tab" class="tab-content" style="display: none;">
        <div class="amortization-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="card-title mb-0">
                        <i class="mdi mdi-format-list-bulleted text-primary"></i>
                        Varlık Listesi ve Amortisman Detayları
                    </h5>
                    <div class="d-flex gap-2 flex-wrap">
                        <input type="text" class="form-control" placeholder="Varlık ara..." id="assetSearch" style="min-width: 200px; max-width: 300px;">
                        <div class="btn-group" role="group">
                            <button class="btn btn-outline-success" onclick="exportToExcel()" title="Excel'e Aktar">
                                <i class="mdi mdi-file-excel"></i> Excel
                            </button>
                            <button class="btn btn-outline-info" onclick="printReport()" title="Yazdır">
                                <i class="mdi mdi-printer"></i> Yazdır
                            </button>
                            <button class="btn btn-outline-secondary" onclick="location.reload()" title="Yenile">
                                <i class="mdi mdi-refresh"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-hover" id="assetsTable">
                        <thead class="table-dark">
                            <tr>
                                <th>Varlık</th>
                                <th>Kategori</th>
                                <th>Alış Tarihi</th>
                                <th>Alış Değeri</th>
                                <th>Güncel Değer</th>
                                <th>Amortisman</th>
                                <th>Yaş</th>
                                <th>Durum</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($assets as $asset)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="asset-avatar me-3" style="background: linear-gradient(135deg, #{{ sprintf('%06X', mt_rand(0, 0xFFFFFF)) }}, #{{ sprintf('%06X', mt_rand(0, 0xFFFFFF)) }});">
                                            <i class="mdi {{ $asset->category_icon }}"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-1">{{ $asset->name }}</h6>
                                            <small class="text-muted">{{ $asset->asset_code }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark">
                                        {{ $categories[$asset->category] ?? $asset->category }}
                                    </span>
                                </td>
                                <td>{{ $asset->purchase_date ? $asset->purchase_date->format('d.m.Y') : '-' }}</td>
                                <td class="fw-bold text-primary">{{ $asset->formatted_purchase_price }}</td>
                                <td class="fw-bold text-success">{{ $asset->formatted_current_value }}</td>
                                <td>
                                    <div class="depreciation-badge {{ $asset->depreciation_percentage > 70 ? 'depreciation-high' : ($asset->depreciation_percentage > 40 ? 'depreciation-medium' : 'depreciation-low') }}">
                                        <i class="mdi mdi-trending-down"></i>
                                        %{{ number_format($asset->depreciation_percentage, 1) }}
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $asset->age_in_years }} yıl</span>
                                </td>
                                <td>{!! $asset->status_badge !!}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <i class="mdi mdi-package-variant-closed display-1 text-muted"></i>
                                    <div class="mt-3">
                                        <h5 class="text-muted">Henüz varlık bulunmuyor</h5>
                                        <p class="text-muted">Amortisman hesaplaması için varlık ekleyin</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script>
// Kategori icons mapping
const categoryIcons = {
    'computer': 'mdi-laptop',
    'furniture': 'mdi-sofa', 
    'vehicle': 'mdi-car',
    'equipment': 'mdi-tools',
    'machinery': 'mdi-factory',
    'building': 'mdi-office-building',
    'land': 'mdi-terrain',
    'software': 'mdi-monitor',
    'mobile': 'mdi-cellphone',
    'other': 'mdi-package-variant'
};

// Tab geçişleri
function showTab(tabName) {
    // Tüm tabları gizle
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.style.display = 'none';
    });
    
    // Tüm tab butonlarından active classını kaldır
    document.querySelectorAll('.filter-tab').forEach(btn => {
        btn.classList.remove('active');
    });
    
    // Seçilen tabı göster
    document.getElementById(tabName + '-tab').style.display = 'block';
    
    // Tıklanan butona active class ekle
    event.target.classList.add('active');
    
    // Chart'ları yeniden çiz
    if (tabName === 'overview') {
        setTimeout(() => {
            drawCategoryChart();
        }, 100);
    } else if (tabName === 'age-analysis') {
        setTimeout(() => {
            drawAgeChart();  
        }, 100);
    }
}

// Kategori Chart
function drawCategoryChart() {
    const ctx = document.getElementById('categoryChart');
    if (!ctx) return;
    
    const categoryData = @json($depreciationByCategory);
    const labels = Object.keys(categoryData).map(key => categoryData[key].category_name);
    const depreciationAmounts = Object.values(categoryData).map(item => item.depreciation_amount);
    const colors = ['#667eea', '#764ba2', '#f093fb', '#f5576c', '#4facfe', '#00f2fe', '#43e97b', '#38f9d7'];
    
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: depreciationAmounts,
                backgroundColor: colors,
                borderWidth: 0,
                hoverOffset: 10
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
                        usePointStyle: true,
                        font: {
                            size: 12,
                            weight: '600'
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const value = context.parsed;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((value / total) * 100).toFixed(1);
                            return context.label + ': ₺' + value.toLocaleString('tr-TR') + ' (' + percentage + '%)';
                        }
                    }
                }
            }
        }
    });
}

// Yaş Chart
function drawAgeChart() {
    const ctx = document.getElementById('ageChart');
    if (!ctx) return;
    
    const ageData = @json($depreciationByAge);
    const labels = Object.keys(ageData);
    const counts = Object.values(ageData).map(item => item.count);
    const depreciationRates = Object.values(ageData).map(item => item.depreciation_rate);
    
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Varlık Sayısı',
                data: counts,
                backgroundColor: '#667eea',
                yAxisID: 'y'
            }, {
                label: 'Amortisman Oranı (%)',
                data: depreciationRates,
                type: 'line',
                borderColor: '#f5576c',
                backgroundColor: 'rgba(245, 87, 108, 0.1)',
                yAxisID: 'y1'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Varlık Sayısı'
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Amortisman Oranı (%)'
                    },
                    grid: {
                        drawOnChartArea: false,
                    },
                }
            },
            plugins: {
                legend: {
                    labels: {
                        usePointStyle: true,
                        font: {
                            weight: '600'
                        }
                    }
                }
            }
        }
    });
}

// DOM yüklendiğinde
document.addEventListener('DOMContentLoaded', function() {
    // Advanced search'i başlat
    initAdvancedSearch();
    
    // İlk yüklemede overview tabını göster ve chart çiz
    setTimeout(() => {
        drawCategoryChart();
    }, 500);
    
    // Tooltips'i aktifleştir
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    if (typeof bootstrap !== 'undefined') {
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
    
    // Progress bar animasyonları
    setTimeout(() => {
        document.querySelectorAll('.progress-bar').forEach(bar => {
            const width = bar.style.width;
            bar.style.width = '0%';
            setTimeout(() => {
                bar.style.width = width;
            }, 100);
        });
    }, 1000);
});

// Excel export
function exportToExcel() {
    try {
        const table = document.getElementById('assetsTable');
        const wb = XLSX.utils.table_to_book(table, {sheet: "Amortisman Raporu"});
        
        // Workbook'a ek sayfa ekle - özet bilgiler
        const summaryData = [
            ['Amortisman Özet Raporu', '', '', ''],
            ['', '', '', ''],
            ['Toplam Varlık Değeri', '₺{{ number_format($totalPurchaseValue, 0, ",", ".") }}', '', ''],
            ['Güncel Değer', '₺{{ number_format($totalCurrentValue, 0, ",", ".") }}', '', ''],
            ['Toplam Amortisman', '₺{{ number_format($totalDepreciation, 0, ",", ".") }}', '', ''],
            ['Aylık Amortisman', '₺{{ number_format($monthlyDepreciation, 0, ",", ".") }}', '', ''],
            ['Yıllık Amortisman', '₺{{ number_format($annualDepreciation, 0, ",", ".") }}', '', ''],
            ['', '', '', ''],
            ['Rapor Tarihi', new Date().toLocaleDateString('tr-TR'), '', ''],
            ['Varlık Sayısı', '{{ $assets->count() }}', '', '']
        ];
        
        const summaryWS = XLSX.utils.aoa_to_sheet(summaryData);
        XLSX.utils.book_append_sheet(wb, summaryWS, "Özet");
        
        // Dosya adını tarihle oluştur
        const today = new Date();
        const filename = `amortization-report-${today.getFullYear()}-${(today.getMonth()+1).toString().padStart(2,'0')}-${today.getDate().toString().padStart(2,'0')}.xlsx`;
        
        XLSX.writeFile(wb, filename);
        
        // Başarı mesajı göster
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Başarılı!',
                text: 'Excel raporu başarıyla indirildi.',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            });
        }
    } catch (error) {
        console.error('Excel export error:', error);
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Hata!',
                text: 'Excel raporu oluşturulurken bir hata oluştu.',
                icon: 'error'
            });
        }
    }
}

// Print raporu
function printReport() {
    window.print();
}

// Fullscreen chart
function toggleFullscreen(chartId) {
    const chartContainer = document.querySelector(`#${chartId}`).closest('.chart-container');
    if (!document.fullscreenElement) {
        chartContainer.requestFullscreen().catch(err => {
            console.log('Fullscreen error:', err);
        });
    } else {
        document.exitFullscreen();
    }
}

// Advanced search with filters
function initAdvancedSearch() {
    const searchInput = document.getElementById('assetSearch');
    if (!searchInput) return;
    
    let searchTimeout;
    searchInput.addEventListener('keyup', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            const filter = this.value.toLowerCase().trim();
            const tbody = document.querySelector('#assetsTable tbody');
            const rows = tbody.querySelectorAll('tr');
            let visibleCount = 0;
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                const isVisible = text.includes(filter);
                row.style.display = isVisible ? '' : 'none';
                if (isVisible) visibleCount++;
            });
            
            // Sonuç sayısını göster
            updateSearchResults(visibleCount, rows.length);
        }, 300);
    });
}

function updateSearchResults(visible, total) {
    let resultDiv = document.getElementById('searchResults');
    if (!resultDiv) {
        resultDiv = document.createElement('div');
        resultDiv.id = 'searchResults';
        resultDiv.className = 'small text-muted mt-2';
        document.getElementById('assetSearch').parentNode.appendChild(resultDiv);
    }
    
    if (visible === total) {
        resultDiv.textContent = '';
    } else {
        resultDiv.textContent = `${visible} / ${total} varlık gösteriliyor`;
    }
}

// Loading states
function showLoading(elementId) {
    const element = document.getElementById(elementId);
    if (element) {
        element.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary" role="status"><span class="sr-only">Yükleniyor...</span></div></div>';
    }
}

function hideLoading(elementId, originalContent) {
    const element = document.getElementById(elementId);
    if (element) {
        element.innerHTML = originalContent;
    }
}
</script>
@endpush
