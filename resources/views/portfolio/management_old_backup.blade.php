@extends('layouts.master')

@section('title', 'Portföy Yönetimi')

@section('page-title', 'Portföy Yönetimi')

@section('suppress_page_title')
    {{-- Suppress master layout page title - this view has its own modern header --}}
@endsection

@section('content')
@php
    // Prepare markers for portfolio map if $projects is available
    $markers = collect([]);
    if(isset($projects)){
        $markers = $projects->map(function($p){
            $loc = $p->location ?? null;
            $lat = null; $lng = null;
            if(is_array($loc)){
                $lat = $loc['lat'] ?? null; $lng = $loc['lng'] ?? null;
            } elseif(is_object($loc)){
                $lat = $loc->lat ?? null; $lng = $loc->lng ?? null;
            }
            return [
                'id' => $p->id ?? null,
                'name' => $p->name ?? '',
                'code' => $p->code ?? '',
                'budget' => $p->budget ?? 0,
                'progress_percent' => $p->progress_percent ?? 0,
                'lat' => $lat,
                'lng' => $lng,
                'status' => $p->status ?? '',
            ];
        })->filter(function($m){
            return $m['lat'] !== null && $m['lng'] !== null;
        })->values();
    }
@endphp

<div class="content-wrapper">
<!-- Modern Header Section -->
<div class="modern-header mb-4">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="header-content">
                    <div class="breadcrumb-modern mb-2">
                        <span class="text-muted">Anasayfa</span>
                        <i class="mdi mdi-chevron-right mx-2 text-muted"></i>
                        <span class="text-primary fw-semibold">Portföy Yönetimi</span>
                    </div>
                    <h1 class="header-title mb-2">
                        <i class="mdi mdi-view-dashboard-variant text-primary me-3"></i>
                        Portföy Kontrol Merkezi
                    </h1>
                    <p class="header-subtitle text-muted mb-0">
                        Gerçek zamanlı proje takibi ve stratejik karar destek sistemi
                    </p>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="header-actions text-end">
                    <div class="action-buttons d-flex justify-content-end gap-2">
                        <button class="btn btn-outline-primary btn-modern" data-bs-toggle="dropdown">
                            <i class="mdi mdi-filter-variant me-2"></i>
                            Filtrele
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="#"><i class="mdi mdi-calendar me-2"></i>Bu Ay</a>
                            <a class="dropdown-item" href="#"><i class="mdi mdi-calendar-range me-2"></i>Bu Çeyrek</a>
                            <a class="dropdown-item" href="#"><i class="mdi mdi-calendar-check me-2"></i>Bu Yıl</a>
                        </div>

                        <button class="btn btn-outline-success btn-modern" data-bs-toggle="modal" data-bs-target="#exportModal">
                            <i class="mdi mdi-download me-2"></i>
                            Dışa Aktar
                        </button>

                        <button class="btn btn-primary btn-modern" id="refresh-dashboard">
                            <i class="mdi mdi-refresh me-2"></i>
                            Yenile
                        </button>
                    </div>

                    <div class="last-update-info mt-2">
                        <small class="text-muted">
                            <i class="mdi mdi-clock-outline me-1"></i>
                            Son güncelleme: {{ now()->format('d.m.Y H:i') }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modern KPI Dashboard -->
<div class="modern-kpi-section modern-only-section mb-5" data-portfolio-version="v2.1">
    <div class="row g-4">
        <!-- Ana Metrikler -->
        <div class="col-12">
            <div class="section-header mb-4">
                <h4 class="section-title">
                    <i class="mdi mdi-speedometer text-primary me-2"></i>
                    Portföy Performans Metrikleri
                </h4>
                <p class="section-subtitle text-muted mb-0">Gerçek zamanlı performans göstergeleri</p>
            </div>
        </div>

        <!-- KPI Kartları -->
        <div class="col-lg-3 col-md-6">
            <div class="modern-kpi-card" data-aos="fade-up" data-aos-delay="100">
                <div class="kpi-card-header">
                    <div class="kpi-icon-modern bg-primary">
                        <i class="mdi mdi-briefcase-outline"></i>
                    </div>
                    <div class="kpi-trend positive">
                        <i class="mdi mdi-trending-up"></i>
                        <span>+3</span>
                    </div>
                </div>
                <div class="kpi-content">
                    <h2 class="kpi-value text-primary">{{ $totalProjects }}</h2>
                    <p class="kpi-label">Toplam Proje</p>
                    <div class="kpi-progress">
                        <div class="progress-line bg-primary" style="width: 85%"></div>
                    </div>
                    <small class="kpi-description text-muted">Bu ay 3 yeni proje eklendi</small>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="modern-kpi-card" data-aos="fade-up" data-aos-delay="200">
                <div class="kpi-card-header">
                    <div class="kpi-icon-modern bg-success">
                        <i class="mdi mdi-currency-try"></i>
                    </div>
                    <div class="kpi-trend positive">
                        <i class="mdi mdi-trending-up"></i>
                        <span>+12%</span>
                    </div>
                </div>
                <div class="kpi-content">
                    <h2 class="kpi-value text-success">₺{{ number_format($totalPortfolioValue/1000000, 1) }}M</h2>
                    <p class="kpi-label">Portföy Değeri</p>
                    <div class="kpi-progress">
                        <div class="progress-line bg-success" style="width: 92%"></div>
                    </div>
                    <small class="kpi-description text-muted">Çeyreklik büyüme %12</small>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="modern-kpi-card" data-aos="fade-up" data-aos-delay="300">
                <div class="kpi-card-header">
                    <div class="kpi-icon-modern bg-info">
                        <i class="mdi mdi-heart-pulse"></i>
                    </div>
                    <div class="kpi-trend {{ $portfolioHealth >= 80 ? 'positive' : 'negative' }}">
                        <i class="mdi mdi-{{ $portfolioHealth >= 80 ? 'trending-up' : 'trending-down' }}"></i>
                        <span>{{ $portfolioHealth }}%</span>
                    </div>
                </div>
                <div class="kpi-content">
                    <h2 class="kpi-value text-info">{{ $portfolioHealth }}%</h2>
                    <p class="kpi-label">Portföy Sağlığı</p>
                    <div class="kpi-progress">
                        <div class="progress-line bg-info" style="width: {{ $portfolioHealth }}%"></div>
                    </div>
                    <small class="kpi-description text-muted">
                        @if($portfolioHealth >= 80)
                            Mükemmel performans
                        @elseif($portfolioHealth >= 60)
                            İyi durumda
                        @else
                            Dikkat gerekli
                        @endif
                    </small>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="modern-kpi-card" data-aos="fade-up" data-aos-delay="400">
                <div class="kpi-card-header">
                    <div class="kpi-icon-modern bg-warning">
                        <i class="mdi mdi-clock-outline"></i>
                    </div>
                    <div class="kpi-trend neutral">
                        <i class="mdi mdi-minus"></i>
                        <span>{{ $onTimeCompletionRate }}%</span>
                    </div>
                </div>
                <div class="kpi-content">
                    <h2 class="kpi-value text-warning">{{ $onTimeCompletionRate }}%</h2>
                    <p class="kpi-label">Zamanında Tamamlama</p>
                    <div class="kpi-progress">
                        <div class="progress-line bg-warning" style="width: {{ $onTimeCompletionRate }}%"></div>
                    </div>
                    <small class="kpi-description text-muted">Ortalama teslim performansı</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- (Charts block kept once further below - removed duplicate) -->

<!-- Modern Action Center -->
<div class="modern-action-center mb-5">
    <div class="section-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h4 class="section-title">
                    <i class="mdi mdi-radar text-primary me-2"></i>
                    Aksiyon Merkezi
                </h4>
                <p class="section-subtitle text-muted mb-0">Öncelikli görevler ve kritik durumlar</p>
            </div>
            <div class="col-auto">
                <div class="action-center-controls">
                    <button class="btn btn-outline-primary btn-sm" data-bs-toggle="dropdown">
                        <i class="mdi mdi-tune me-1"></i>
                        Filtrele
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="#"><i class="mdi mdi-alert-circle me-2 text-danger"></i>Kritik</a>
                        <a class="dropdown-item" href="#"><i class="mdi mdi-clock me-2 text-warning"></i>Yaklaşan</a>
                        <a class="dropdown-item" href="#"><i class="mdi mdi-shield-alert me-2 text-info"></i>Riskler</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Kritik Projeler -->
        <div class="col-lg-4">
            <div class="modern-action-card critical" data-aos="fade-up" data-aos-delay="100">
                <div class="action-card-header">
                    <div class="action-icon-wrapper">
                        <div class="action-icon bg-danger">
                            <i class="mdi mdi-alert-octagon"></i>
                        </div>
                        <div class="action-count">
                            <span class="count-number">{{ count($criticalProjects) }}</span>
                            <span class="count-label">Kritik</span>
                        </div>
                    </div>
                    <div class="action-title">
                        <h5 class="mb-1">Kritik Projeler</h5>
                        <p class="text-muted small mb-0">Acil müdahale gerekli</p>
                    </div>
                </div>
                            <div class="focus-content mt-3">
                                @forelse($criticalProjects as $index => $project)
                                    @if($index < 3)
                                    <div class="critical-project-item">
                                        <div class="d-flex align-items-start">
                                            <div class="project-status-dot bg-danger"></div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="mb-1 fw-semibold">{{ $project->name }}</h6>
                                                <div class="progress-info">
                                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                                        <span class="small text-muted">İlerleme</span>
                                                        <span class="small fw-semibold text-danger">{{ $project->progress_percent ?? 0 }}%</span>
                                                    </div>
                                                    <div class="progress" style="height: 4px;">
                                                        <div class="progress-bar bg-danger" style="width: {{ $project->progress_percent ?? 0 }}%"></div>
                                                    </div>
                                                </div>
                                                <div class="mt-2">
                                                    <a href="{{ route('portfolio.project.show', $project->id) }}" class="btn btn-sm btn-outline-danger rounded-pill">
                                                        <i class="mdi mdi-eye me-1"></i> Detay
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                @empty
                                    <div class="empty-state text-center py-3">
                                        <i class="mdi mdi-check-circle text-success display-6"></i>
                                        <p class="text-muted mt-2 mb-0">Kritik proje bulunmuyor</p>
                                    </div>
                                @endforelse
                                @if(count($criticalProjects) > 3)
                                    <div class="text-center mt-3">
                                        <button class="btn btn-sm btn-link text-danger p-0">
                                            +{{ count($criticalProjects) - 3 }} daha göster
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Yaklaşan Kilometre Taşları -->
                    <div class="col-lg-4">
                        <div class="focus-area-card">
                            <div class="focus-header">
                                <div class="focus-icon bg-warning-subtle text-warning">
                                    <i class="mdi mdi-calendar-clock"></i>
                                </div>
                                <div class="ms-3">
                                    <h5 class="mb-1 fw-bold">Yaklaşan Kilometre Taşları</h5>
                                    <p class="text-muted mb-0 small">Önümüzdeki 30 gün</p>
                                </div>
                                <div class="ms-auto">
                                    <span class="badge bg-warning rounded-pill">{{ count($upcomingMilestones) }}</span>
                                </div>
                            </div>
                            <div class="focus-content mt-3">
                                @forelse($upcomingMilestones as $index => $milestone)
                                    @if($index < 3)
                                    <div class="milestone-item">
                                        <div class="d-flex align-items-start">
                                            <div class="milestone-date-box">
                                                <div class="milestone-day">{{ $milestone->due_date ? $milestone->due_date->format('d') : '--' }}</div>
                                                <div class="milestone-month">{{ $milestone->due_date ? $milestone->due_date->format('M') : '--' }}</div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="mb-1 fw-semibold">{{ $milestone->title }}</h6>
                                                <p class="text-muted mb-1 small">{{ $milestone->program->name ?? 'Program Adı Yok' }}</p>
                                                <div class="d-flex align-items-center">
                                                    @php
                                                        $daysLeft = $milestone->due_date ? now()->diffInDays($milestone->due_date, false) : 0;
                                                    @endphp
                                                    @if($daysLeft <= 7)
                                                        <span class="badge bg-danger-subtle text-danger rounded-pill small">{{ $daysLeft }} gün kaldı</span>
                                                    @elseif($daysLeft <= 15)
                                                        <span class="badge bg-warning-subtle text-warning rounded-pill small">{{ $daysLeft }} gün kaldı</span>
                                                    @else
                                                        <span class="badge bg-info-subtle text-info rounded-pill small">{{ $daysLeft }} gün kaldı</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                @empty
                                    <div class="empty-state text-center py-3">
                                        <i class="mdi mdi-calendar-check text-success display-6"></i>
                                        <p class="text-muted mt-2 mb-0">Yaklaşan kilometre taşı yok</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Portföy Geneli Riskler -->
                    <div class="col-lg-4">
                        <div class="focus-area-card">
                            <div class="focus-header">
                                <div class="focus-icon bg-info-subtle text-info">
                                    <i class="mdi mdi-shield-alert"></i>
                                </div>
                                <div class="ms-3">
                                    <h5 class="mb-1 fw-bold">Aktif Riskler</h5>
                                    <p class="text-muted mb-0 small">Yönetim gerektiren riskler</p>
                                </div>
                                <div class="ms-auto">
                                    <span class="badge bg-info rounded-pill">{{ count($portfolioRisks) }}</span>
                                </div>
                            </div>
                            <div class="focus-content mt-3">
                                @forelse($portfolioRisks as $index => $risk)
                                    @if($index < 3)
                                    <div class="risk-item">
                                        <div class="d-flex align-items-start">
                                            <div class="risk-severity-indicator">
                                                @php
                                                    $severity = strtolower($risk->severity ?? 'medium');
                                                    $severityColor = $severity === 'high' ? 'danger' : ($severity === 'medium' ? 'warning' : 'info');
                                                @endphp
                                                <div class="severity-dot bg-{{ $severityColor }}"></div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="mb-1 fw-semibold">{{ $risk->title }}</h6>
                                                <p class="text-muted mb-1 small">{{ Str::limit($risk->impact ?? 'Etki belirtilmemiş', 60) }}</p>
                                                <div class="risk-tags">
                                                    <span class="badge bg-{{ $severityColor }}-subtle text-{{ $severityColor }} rounded-pill small me-1">
                                                        {{ ucfirst($severity) }}
                                                    </span>
                                                    @if($risk->likelihood)
                                                        <span class="badge bg-secondary-subtle text-secondary rounded-pill small">
                                                            {{ $risk->likelihood }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                @empty
                                    <div class="empty-state text-center py-3">
                                        <i class="mdi mdi-shield-check text-success display-6"></i>
                                        <p class="text-muted mt-2 mb-0">Aktif risk bulunmuyor</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- Modern Analytics Dashboard -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom">
                <div class="row align-items-center">
                    <div class="col">
                        <h4 class="card-title mb-0 fw-bold">Portföy Analizi ve Trendler</h4>
                        <p class="text-muted mb-0 mt-1">Detaylı performans metrikleri ve trend analizleri</p>
                    </div>
                    <div class="col-auto">
                        <div class="btn-group" role="group">
                            <button class="btn btn-sm btn-outline-primary" data-period="3m">3 Ay</button>
                            <button class="btn btn-sm btn-primary" data-period="6m">6 Ay</button>
                            <button class="btn btn-sm btn-outline-primary" data-period="1y">1 Yıl</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="row g-4">
                    <!-- Portföy Sağlık Trendi -->
                    <div class="col-lg-6">
                        <div class="analytics-card">
                            <div class="analytics-header mb-3">
                                <h5 class="fw-bold mb-1">Portföy Sağlık Trendi</h5>
                                <p class="text-muted mb-0 small">Zaman içindeki sağlık durumu değişimi</p>
                            </div>
                            <div class="chart-container">
                                <div id="health-trend-chart" style="height: 350px;"></div>
                            </div>
                            <div class="chart-insights mt-3">
                                <div class="row">
                                    <div class="col-4 text-center">
                                        <div class="insight-item">
                                            <h6 class="text-success mb-1">{{ $healthTrendData ? collect($healthTrendData)->last()['healthy'] ?? 0 : 0 }}</h6>
                                            <p class="small text-muted mb-0">Sağlıklı</p>
                                        </div>
                                    </div>
                                    <div class="col-4 text-center">
                                        <div class="insight-item">
                                            <h6 class="text-warning mb-1">{{ $healthTrendData ? collect($healthTrendData)->last()['risky'] ?? 0 : 0 }}</h6>
                                            <p class="small text-muted mb-0">Riskli</p>
                                        </div>
                                    </div>
                                    <div class="col-4 text-center">
                                        <div class="insight-item">
                                            <h6 class="text-danger mb-1">{{ $healthTrendData ? collect($healthTrendData)->last()['critical'] ?? 0 : 0 }}</h6>
                                            <p class="small text-muted mb-0">Kritik</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bütçe-Zaman Performansı -->
                    <div class="col-lg-6">
                        <div class="analytics-card">
                            <div class="analytics-header mb-3">
                                <h5 class="fw-bold mb-1">Bütçe-Zaman Performansı</h5>
                                <p class="text-muted mb-0 small">Proje performans dağılımı</p>
                            </div>
                            <div class="chart-container">
                                <div id="budget-time-scatter" style="height: 350px;"></div>
                            </div>
                            <div class="performance-quadrants mt-3">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="quadrant-info p-2 rounded bg-success-subtle">
                                            <small class="text-success fw-semibold">✓ İdeal Performans</small>
                                            <div class="small text-muted">Bütçe içinde & Zamanında</div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="quadrant-info p-2 rounded bg-danger-subtle">
                                            <small class="text-danger fw-semibold">⚠ Dikkat Gerekli</small>
                                            <div class="small text-muted">Bütçe aşımı & Gecikme</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Performans Karşılaştırması - Full Width -->
                    <div class="col-12">
                        <div class="analytics-card">
                            <div class="analytics-header mb-3">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h5 class="fw-bold mb-1">Kategori Bazlı Performans Karşılaştırması</h5>
                                        <p class="text-muted mb-0 small">Proje türü ve bölgeye göre detaylı analiz</p>
                                    </div>
                                    <div class="col-auto">
                                        <select class="form-select form-select-sm" id="comparison-filter">
                                            <option value="all">Tüm Kategoriler</option>
                                            <option value="region">Bölgesel</option>
                                            <option value="type">Proje Türü</option>
                                            <option value="size">Proje Büyüklüğü</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="chart-container">
                                <div id="performance-comparison-chart" style="height: 400px;"></div>
                            </div>
                            <div class="comparison-summary mt-3">
                                <div class="row">
                                    <div class="col-md-3 text-center">
                                        <div class="summary-item">
                                            <h6 class="text-primary mb-1">{{ number_format(collect($performanceComparisonData)->avg('on_time_rate'), 1) }}%</h6>
                                            <p class="small text-muted mb-0">Ortalama Zamanında Tamamlanma</p>
                                        </div>
                                    </div>
                                    <div class="col-md-3 text-center">
                                        <div class="summary-item">
                                            <h6 class="text-success mb-1">{{ number_format(collect($performanceComparisonData)->avg('profit_margin'), 1) }}%</h6>
                                            <p class="small text-muted mb-0">Ortalama Kârlılık Marjı</p>
                                        </div>
                                    </div>
                                    <div class="col-md-3 text-center">
                                        <div class="summary-item">
                                            <h6 class="text-info mb-1">{{ collect($performanceComparisonData)->where('on_time_rate', '>', 80)->count() }}</h6>
                                            <p class="small text-muted mb-0">Yüksek Performanslı Kategori</p>
                                        </div>
                                    </div>
                                    <div class="col-md-3 text-center">
                                        <div class="summary-item">
                                            <h6 class="text-warning mb-1">{{ collect($performanceComparisonData)->where('on_time_rate', '<', 60)->count() }}</h6>
                                            <p class="small text-muted mb-0">İyileştirme Gerekli</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Enhanced Project Portfolio Grid -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <h4 class="card-title mb-0 fw-bold">Proje Portföyü</h4>
                        <p class="text-muted mb-0 mt-1">Toplam {{ isset($projects) ? count($projects) : 0 }} proje</p>
                    </div>
                    <div class="col-lg-6">
                        <div class="row g-2">
                            <div class="col-md-5">
                                <div class="position-relative">
                                    <input id="project-search" class="form-control form-control-sm ps-5" placeholder="Proje ara..." />
                                    <i class="mdi mdi-magnify position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <select id="status-filter" class="form-select form-select-sm">
                                    <option value="">Tüm Durumlar</option>
                                    <option value="active">🟢 Aktif</option>
                                    <option value="paused">🟡 Beklemede</option>
                                    <option value="completed">🔵 Tamamlandı</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <div class="btn-group w-100" role="group">
                                    <button id="toggle-view" class="btn btn-sm btn-outline-secondary" data-bs-toggle="tooltip" title="Görünüm Değiştir">
                                        <i class="mdi mdi-view-grid-outline"></i>
                                    </button>
                                    <button id="export-csv" class="btn btn-sm btn-outline-success" data-bs-toggle="tooltip" title="CSV Dışa Aktar">
                                        <i class="mdi mdi-download"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#portfolioModal" data-bs-toggle="tooltip" title="Portföy Seç">
                                        <i class="mdi mdi-view-dashboard"></i>
                                    </button>
                                    <button class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="Yeni Proje">
                                        <i class="mdi mdi-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body p-4">
                <!-- Filter and Sort Bar -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="project-filters bg-light rounded-3 p-3">
                            <div class="row align-items-center">
                                <div class="col-md-3">
                                    <label class="form-label small fw-semibold text-muted mb-1">İlerleme Durumu</label>
                                    <select class="form-select form-select-sm" id="progress-filter">
                                        <option value="">Tümü</option>
                                        <option value="0-25">%0-25 (Başlangıç)</option>
                                        <option value="26-50">%26-50 (Devam Ediyor)</option>
                                        <option value="51-75">%51-75 (İlerlemede)</option>
                                        <option value="76-100">%76-100 (Tamamlanmaya Yakın)</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small fw-semibold text-muted mb-1">Bütçe Aralığı</label>
                                    <select class="form-select form-select-sm" id="budget-filter">
                                        <option value="">Tümü</option>
                                        <option value="0-1000000">1M₺'nin altında</option>
                                        <option value="1000000-5000000">1M₺ - 5M₺</option>
                                        <option value="5000000-10000000">5M₺ - 10M₺</option>
                                        <option value="10000000-999999999">10M₺'nin üstünde</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small fw-semibold text-muted mb-1">Sıralama</label>
                                    <select class="form-select form-select-sm" id="sort-projects">
                                        <option value="name">İsme Göre</option>
                                        <option value="progress">İlerlemeye Göre</option>
                                        <option value="value">Bütçeye Göre</option>
                                        <option value="status">Duruma Göre</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small fw-semibold text-muted mb-1">Eylemler</label>
                                    <div class="d-flex gap-1">
                                        <button class="btn btn-sm btn-outline-primary" id="bulk-actions" data-bs-toggle="tooltip" title="Toplu İşlemler">
                                            <i class="mdi mdi-checkbox-multiple-marked"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-info" id="print-report" data-bs-toggle="tooltip" title="Rapor Yazdır">
                                            <i class="mdi mdi-printer"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Projects Grid -->
                <div class="row" id="projects-grid">
                    @if(isset($projects) && count($projects))
                        @foreach($projects as $project)
                        @php
                            $projThumb = null;
                            if(!empty($project->attachments) && is_array($project->attachments) && count($project->attachments)){
                                $projThumb = $project->attachments[0];
                            }
                            $projThumbUrl = $projThumb ? asset('storage/' . $projThumb) : '';
                            $status = strtolower($project->status ?? 'unknown');
                            $statusConfig = [
                                'active' => ['class' => 'success', 'icon' => 'play-circle', 'text' => 'Aktif'],
                                'paused' => ['class' => 'warning', 'icon' => 'pause-circle', 'text' => 'Beklemede'],
                                'completed' => ['class' => 'primary', 'icon' => 'check-circle', 'text' => 'Tamamlandı'],
                                'cancelled' => ['class' => 'secondary', 'icon' => 'close-circle', 'text' => 'İptal'],
                            ];
                            $currentStatus = $statusConfig[$status] ?? ['class' => 'secondary', 'icon' => 'help-circle', 'text' => 'Bilinmiyor'];
                        @endphp
                        <div class="col-lg-4 col-md-6 mb-4 project-card"
                             data-id="{{ $project->id }}"
                             data-name="{{ strtolower($project->name) }}"
                             data-code="{{ strtolower($project->code ?? '') }}"
                             data-status="{{ $status }}"
                             data-progress="{{ $project->progress_percent ?? 0 }}"
                             data-value="{{ $project->value ?? 0 }}"
                             data-thumb="{{ $projThumbUrl }}"
                             data-url="{{ route('portfolio.project.show', $project->id) }}">
                            <div class="card project-card-enhanced h-100 border-0 shadow-sm">
                                <!-- Project Image/Thumbnail -->
                                <div class="project-header position-relative">
                                    @if($projThumb)
                                        @php
                                            $ext = strtolower(pathinfo($projThumb, PATHINFO_EXTENSION));
                                            $imageExts = ['jpg','jpeg','png','gif','webp','svg'];
                                        @endphp
                                        @if(in_array($ext, $imageExts))
                                            <img src="{{ asset('storage/' . $projThumb) }}" alt="{{ $project->name }}" class="project-header-img" loading="lazy" />
                                        @else
                                            <div class="project-placeholder bg-gradient-primary">
                                                <i class="mdi mdi-file-document-outline text-white"></i>
                                            </div>
                                        @endif
                                    @else
                                        <div class="project-placeholder bg-gradient-{{ $currentStatus['class'] }}">
                                            <i class="mdi mdi-office-building-outline text-white"></i>
                                        </div>
                                    @endif

                                    <!-- Status Badge -->
                                    <div class="position-absolute top-0 end-0 m-2">
                                        <span class="badge bg-{{ $currentStatus['class'] }}-subtle text-{{ $currentStatus['class'] }} rounded-pill">
                                            <i class="mdi mdi-{{ $currentStatus['icon'] }} me-1"></i>{{ $currentStatus['text'] }}
                                        </span>
                                    </div>

                                    <!-- Progress Overlay -->
                                    <div class="progress-overlay">
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar bg-{{ $currentStatus['class'] }}" style="width: {{ $project->progress_percent ?? 0 }}%"></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Project Info -->
                                <div class="card-body p-3">
                                    <div class="project-info">
                                        <h5 class="project-title mb-1 fw-bold">{{ $project->name }}</h5>
                                        <p class="project-code text-muted small mb-2">{{ $project->code ?? 'KOD-BELIRTILMEMIS' }}</p>

                                        <!-- Key Metrics -->
                                        <div class="row project-metrics g-2 mb-3">
                                            <div class="col-6">
                                                <div class="metric-item">
                                                    <span class="metric-label small text-muted">İlerleme</span>
                                                    <span class="metric-value fw-bold text-{{ $currentStatus['class'] }}">{{ $project->progress_percent ?? 0 }}%</span>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="metric-item">
                                                    <span class="metric-label small text-muted">Bütçe</span>
                                                    <span class="metric-value fw-bold">{{ number_format(($project->value ?? 0)/1000000, 1) }}M₺</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Action Buttons -->
                                        <div class="project-actions">
                                            <div class="row g-2">
                                                <div class="col-8">
                                                    <a href="{{ route('portfolio.project.show', $project->id) }}" class="btn btn-{{ $currentStatus['class'] }} btn-sm w-100 rounded-pill">
                                                        <i class="mdi mdi-eye me-1"></i> Detaylı Görünüm
                                                    </a>
                                                </div>
                                                <div class="col-4">
                                                    <div class="dropdown">
                                                        <button class="btn btn-outline-secondary btn-sm w-100 rounded-pill dropdown-toggle" data-bs-toggle="dropdown">
                                                            <i class="mdi mdi-dots-horizontal"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                            <li><a class="dropdown-item" href="#"><i class="mdi mdi-pencil me-2"></i>Düzenle</a></li>
                                                            <li><a class="dropdown-item" href="#"><i class="mdi mdi-share me-2"></i>Paylaş</a></li>
                                                            <li><a class="dropdown-item" href="#"><i class="mdi mdi-heart me-2"></i>Favorile</a></li>
                                                            <li><hr class="dropdown-divider"></li>
                                                            <li><a class="dropdown-item text-danger" href="#"><i class="mdi mdi-delete me-2"></i>Sil</a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="col-12">
                            <div class="empty-state text-center py-5">
                                <i class="mdi mdi-folder-open-outline display-1 text-muted mb-3"></i>
                                <h5 class="text-muted mb-2">Henüz Proje Bulunmuyor</h5>
                                <p class="text-muted mb-3">İlk projenizi oluşturarak portföyünüzü oluşturmaya başlayın.</p>
                                <button class="btn btn-primary rounded-pill">
                                    <i class="mdi mdi-plus me-2"></i>İlk Projeyi Oluştur
                                </button>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Pagination -->
                @if(isset($projects) && method_exists($projects, 'links'))
                    <div class="d-flex justify-content-center mt-4">
                        {{ $projects->links() }}
                    </div>
                @endif
            </div>
                                </div>
                            </div>
                        </div>

        </div>
    </div>
</div>

<!-- Enhanced Portfolio Map -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom">
                <div class="row align-items-center">
                    <div class="col">
                        <h4 class="card-title mb-0 fw-bold">Portföy Coğrafi Dağılımı</h4>
                        <p class="text-muted mb-0 mt-1">Projelerin coğrafi konumları ve durumları</p>
                    </div>
                    <div class="col-auto">
                        <div class="map-controls">
                            <div class="btn-group" role="group">
                                <button class="btn btn-sm btn-outline-primary active" data-map-view="all" data-bs-toggle="tooltip" title="Tüm Projeler">
                                    <i class="mdi mdi-all-inclusive"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-success" data-map-view="active" data-bs-toggle="tooltip" title="Aktif Projeler">
                                    <i class="mdi mdi-play-circle"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger" data-map-view="critical" data-bs-toggle="tooltip" title="Kritik Projeler">
                                    <i class="mdi mdi-alert-circle"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-info" data-map-view="fullscreen" data-bs-toggle="tooltip" title="Tam Ekran">
                                    <i class="mdi mdi-fullscreen"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body p-0 position-relative portfolio-map-container">
                <div id="portfolio-map" style="height: 600px; width: 100%; position: relative; z-index: 1;"></div>
                <div class="map-legend position-absolute bottom-0 start-0 m-3 bg-white rounded shadow-sm p-3">
                    <h6 class="mb-2 fw-bold small">Gösterge</h6>
                    <div class="legend-item">
                        <span class="legend-dot bg-success"></span>
                        <span class="small">İlerleme > 80%</span>
                    </div>
                    <div class="legend-item">
                        <span class="legend-dot bg-warning"></span>
                        <span class="small">İlerleme 50-80%</span>
                    </div>
                    <div class="legend-item">
                        <span class="legend-dot bg-danger"></span>
                        <span class="small">İlerleme < 50%</span>
                    </div>
                </div>
                <div class="map-stats position-absolute top-0 end-0 m-3 bg-white rounded shadow-sm p-3">
                    <div class="d-flex align-items-center">
                        <i class="mdi mdi-map-marker text-primary me-2"></i>
                        <span class="small fw-semibold" id="map-project-count">{{ count($markers) }} Proje</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Portfolio Settings Modal -->
<div class="modal fade" id="portfolioSettingsModal" tabindex="-1" aria-labelledby="portfolioSettingsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="portfolioSettingsModalLabel">
                    <i class="mdi mdi-cog me-2"></i>Portföy Panel Ayarları
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="fw-bold mb-3">Görünüm Ayarları</h6>
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" id="showKPIs" checked>
                            <label class="form-check-label" for="showKPIs">KPI Kartlarını Göster</label>
                        </div>
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" id="showMap" checked>
                            <label class="form-check-label" for="showMap">Portfolyo Haritasını Göster</label>
                        </div>
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" id="showCharts" checked>
                            <label class="form-check-label" for="showCharts">Analiz Grafiklerini Göster</label>
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="showFocusAreas" checked>
                            <label class="form-check-label" for="showFocusAreas">Odak Alanlarını Göster</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold mb-3">Veri Ayarları</h6>
                        <div class="mb-3">
                            <label for="refreshInterval" class="form-label">Otomatik Yenileme Süresi</label>
                            <select class="form-select" id="refreshInterval">
                                <option value="0">Otomatik yenileme yok</option>
                                <option value="30">30 saniye</option>
                                <option value="60" selected>1 dakika</option>
                                <option value="300">5 dakika</option>
                                <option value="900">15 dakika</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="defaultView" class="form-label">Varsayılan Proje Görünümü</label>
                            <select class="form-select" id="defaultView">
                                <option value="grid" selected>Kart Görünümü</option>
                                <option value="list">Liste Görünümü</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                <button type="button" class="btn btn-primary" id="saveSettings">Ayarları Kaydet</button>
            </div>
        </div>
    </div>
</div>

<!-- Portföy Seç Modal -->
<div class="modal fade" id="portfolioModal" tabindex="-1" aria-labelledby="portfolioModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="portfolioModalLabel">
                    <i class="mdi mdi-view-dashboard me-2"></i>Portföy Seçimi
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted mb-3">Görüntülemek istediğiniz portföyü seçin:</p>
                <div class="list-group">
                    <a href="#" class="list-group-item list-group-item-action d-flex align-items-center" onclick="selectPortfolio('ana-portfoy')">
                        <div class="me-3">
                            <i class="mdi mdi-view-dashboard text-primary" style="font-size: 1.5rem;"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">Ana Portföy</h6>
                            <p class="mb-0 text-muted small">Tüm projelerin genel görünümü</p>
                        </div>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action d-flex align-items-center" onclick="selectPortfolio('konut-portfoy')">
                        <div class="me-3">
                            <i class="mdi mdi-home text-success" style="font-size: 1.5rem;"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">Konut Portföyü</h6>
                            <p class="mb-0 text-muted small">Konut projeleri portföyü</p>
                        </div>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action d-flex align-items-center" onclick="selectPortfolio('ticari-portfoy')">
                        <div class="me-3">
                            <i class="mdi mdi-office-building text-info" style="font-size: 1.5rem;"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">Ticari Portföy</h6>
                            <p class="mb-0 text-muted small">Ticari projeler portföyü</p>
                        </div>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action d-flex align-items-center" onclick="selectPortfolio('endustriyel-portfoy')">
                        <div class="me-3">
                            <i class="mdi mdi-factory text-warning" style="font-size: 1.5rem;"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">Endüstriyel Portföy</h6>
                            <p class="mb-0 text-muted small">Endüstriyel projeler portföyü</p>
                        </div>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action d-flex align-items-center" onclick="selectPortfolio('altyapi-portfoy')">
                        <div class="me-3">
                            <i class="mdi mdi-bridge text-danger" style="font-size: 1.5rem;"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">Altyapı Portföyü</h6>
                            <p class="mb-0 text-muted small">Altyapı projeleri portföyü</p>
                        </div>
                    </a>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                <button type="button" class="btn btn-primary" onclick="applyPortfolioSelection()">Seç</button>
            </div>
        </div>
    </div>
</div>

<!-- Enhanced Project Preview Modal -->
<div class="modal fade" id="projectPreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light border-0">
                <div class="d-flex align-items-center">
                    <div class="modal-project-icon me-3">
                        <i class="mdi mdi-folder-open-outline text-primary"></i>
                    </div>
                    <div>
                        <h5 class="modal-title mb-0" id="previewTitle">Proje Önizleme</h5>
                        <small class="text-muted" id="previewSubtitle">Detaylar yükleniyor...</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
            </div>
            <div class="modal-body p-0">
                <div class="row g-0">
                    <div class="col-md-5">
                        <div class="preview-image-section p-4 bg-light h-100">
                            <img id="previewImage" src="" alt="Proje Görseli" class="img-fluid rounded mb-3" style="width: 100%; height: 250px; object-fit: cover;" />
                            <div class="preview-quick-stats">
                                <div class="row g-2">
                                    <div class="col-6">
                                        <div class="stat-card bg-white p-3 rounded text-center">
                                            <i class="mdi mdi-cash text-success mb-2"></i>
                                            <h6 class="mb-1">Bütçe</h6>
                                            <p class="mb-0 fw-bold text-success" id="previewValue">-</p>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="stat-card bg-white p-3 rounded text-center">
                                            <i class="mdi mdi-chart-line text-primary mb-2"></i>
                                            <h6 class="mb-1">İlerleme</h6>
                                            <p class="mb-0 fw-bold text-primary" id="previewProgress">-</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="preview-content-section p-4">
                            <div class="mb-4">
                                <h6 class="text-muted mb-2">Proje Açıklaması</h6>
                                <p id="previewDescription" class="mb-0">Detaylar yükleniyor...</p>
                            </div>
                            <div class="mb-4">
                                <h6 class="text-muted mb-3">Proje Durumu</h6>
                                <div id="previewStatus" class="d-flex align-items-center mb-2">
                                    <span class="badge bg-secondary me-2">Durum Yükleniyor</span>
                                </div>
                                <div class="progress mb-2" style="height: 8px;">
                                    <div id="previewProgressBar" class="progress-bar" style="width: 0%"></div>
                                </div>
                            </div>
                            <div class="mb-4">
                                <h6 class="text-muted mb-3">Hızlı Eylemler</h6>
                                <div class="d-flex gap-2 flex-wrap">
                                    <button class="btn btn-sm btn-outline-primary">
                                        <i class="mdi mdi-heart me-1"></i>Favorile
                                    </button>
                                    <button class="btn btn-sm btn-outline-success">
                                        <i class="mdi mdi-share me-1"></i>Paylaş
                                    </button>
                                    <button class="btn btn-sm btn-outline-info">
                                        <i class="mdi mdi-download me-1"></i>Rapor Al
                                    </button>
                                    <button class="btn btn-sm btn-outline-warning">
                                        <i class="mdi mdi-pencil me-1"></i>Düzenle
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light border-0">
                <div class="w-100 d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted">Son güncelleme: <span id="previewLastUpdate">-</span></small>
                    </div>
                    <div>
                        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Kapat</button>
                        <a id="previewDetailsBtn" href="#" class="btn btn-primary">
                            <i class="mdi mdi-arrow-right me-1"></i>Detaylı Görünüm
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div> <!-- Close content-wrapper -->

@endsection

@section('scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<!-- MarkerCluster CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<!-- MarkerCluster JS -->
<script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
window.addEventListener('load', function() {
    try {
    // --- Charts: Portföy Sağlık Trendi ---
    const _healthMonths = @json(collect($healthTrendData)->pluck('month'));
    const _healthHealthy = @json(collect($healthTrendData)->pluck('healthy'));
    const _healthRisky = @json(collect($healthTrendData)->pluck('risky'));
    const _healthCompleted = @json(collect($healthTrendData)->pluck('completed'));
        const healthTrendOptions = {
            series: [
                {
                    name: 'Sağlıklı',
                    data: @json(collect($healthTrendData)->pluck('healthy'))
                },
                {
                    name: 'Riskli',
                    data: @json(collect($healthTrendData)->pluck('risky'))
                },
                {
                    name: 'Kritik',
                    data: @json(collect($healthTrendData)->pluck('critical'))
                }
            ],
            chart: { type: 'line', height: 300 },
            xaxis: { categories: @json(collect($healthTrendData)->pluck('month')) },
            legend: { position: 'top' }
        };
    try {
        const el = document.querySelector('#health-trend-chart');
        if (el) new ApexCharts(el, healthTrendOptions).render();
    } catch(e){ console.warn('healthTrendChart error', e); }

        // --- Charts: Bütçe ve Zaman Çizelgesi Performansı (Scatter) ---
    const _budgetTimeScatterData = @json($budgetTimeScatterData);
        const scatterOptions = {
            series: [{ name: 'Projeler', data: _budgetTimeScatterData }],
            chart: { type: 'scatter', height: 300, zoom: { enabled: true, type: 'xy' } },
            xaxis: { title: { text: 'Zaman Çizelgesi Sapması (%)' } },
            yaxis: { title: { text: 'Bütçe Sapması (%)' } },
            legend: { position: 'top' }
        };
        try {
            const el = document.querySelector('#budget-time-scatter');
            if (el) new ApexCharts(el, scatterOptions).render();
        } catch(e){ console.warn('scatterChart error', e); }

        // --- Charts: Performans Karşılaştırması ---
    const _comparisonOnTime = @json(collect($performanceComparisonData)->pluck('on_time_rate'));
    const _comparisonProfit = @json(collect($performanceComparisonData)->pluck('profit_margin'));
    const _comparisonCategories = @json(collect($performanceComparisonData)->pluck('category'));
        const comparisonOptions = {
            series: [{
                name: 'Zamanında Tamamlanma (%)',
                data: _comparisonOnTime
            }, {
                name: 'Kârlılık Marjı (%)',
                data: _comparisonProfit
            }],
            chart: {
                type: 'bar',
                height: 300,
                toolbar: { show: false }
            },
            plotOptions: { bar: { horizontal: false, columnWidth: '55%', endingShape: 'rounded' } },
            dataLabels: { enabled: false },
            xaxis: { categories: @json(collect($performanceComparisonData)->pluck('category')) },
            yaxis: { title: { text: 'Yüzde (%)' } },
            legend: { show: true, position: 'top' },
            tooltip: { enabled: true }
        };
        // Grafik container'ı ve veri kontrolü ile güvenli render
        const comparisonChartEl = document.querySelector('#performance-comparison-chart');
        if (window.ApexCharts && comparisonChartEl && comparisonOptions && comparisonOptions.series && comparisonOptions.series.length) {
            try {
                const chart = new ApexCharts(comparisonChartEl, comparisonOptions);
                chart.render();
                setTimeout(function() {
                    try {
                        if (chart && comparisonChartEl && comparisonChartEl._apexcharts) {
                            comparisonChartEl._apexcharts.updateOptions({});
                        }
                    } catch(e) { /* ignore safely */ }
                }, 400);
            } catch(e) {
                console.warn('comparisonChart error', e);
            }
        }
    // --- Map ---
        // Sayfa tam yüklendikten sonra grafiği tekrar boyutlandır
        setTimeout(function() {
            try {
                const perfEl = document.querySelector('#performance-comparison-chart');
                if (window.ApexCharts && perfEl && perfEl._apexcharts) {
                    perfEl._apexcharts.updateOptions({});
                }
            } catch(e) { /* ignore */ }
        }, 400);
        // Eski harita varsa kaldır
        if (window.portfolioMap) {
            window.portfolioMap.remove();
        }

        // Check if map container exists
        if (!document.getElementById('portfolio-map')) {
            console.warn('Map container not found, skipping map initialization');
            return;
        }

        const map = window.portfolioMap = L.map('portfolio-map', {
            zoomControl: true,
            attributionControl: true
        }).setView([39.0, 35.0], 6);

        // Force map container z-index
        const mapContainer = document.getElementById('portfolio-map');
        if (mapContainer) {
            mapContainer.style.zIndex = '1';
            mapContainer.style.position = 'relative';
        }
        const tile = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Ensure map renders correctly in hidden/complex layouts
        tile.on('load', function(){
            try{ map.invalidateSize(); }catch(e){/*ignore*/}
        });

        // Marker ekleme with clustering
        const markers = @json($markers);
        const markerLayers = [];
        try{
            const clusterGroup = L.markerClusterGroup();
            if(Array.isArray(markers) && markers.length){
                markers.forEach(function(m){
                    try{
                        const lat = parseFloat(m.lat);
                        const lng = parseFloat(m.lng);
                        if(Number.isNaN(lat) || Number.isNaN(lng)) return;

                        const color = (m.progress_percent < 50) ? '#dc3545' : (m.progress_percent < 80 ? '#ffc107' : '#28a745');
                        const radius = Math.min(Math.max(Math.sqrt(Number(m.budget || 0)) / 1000, 8), 40);
                        const circle = L.circleMarker([lat, lng], {
                            color: color,
                            fillColor: color,
                            fillOpacity: 0.85,
                            radius: radius
                        }).bindPopup('<b>' + (m.name || '') + '</b><br>Bütçe: ' + (Number(m.budget || 0).toLocaleString()) + ' ₺<br>İlerleme: ' + (m.progress_percent || 0) + '%');
                        clusterGroup.addLayer(circle);
                        markerLayers.push(circle);
                    } catch(inner){ console.warn('marker error', inner, m); }
                });

                map.addLayer(clusterGroup);
                // Fit map to markers
                try{
                    const group = L.featureGroup(markerLayers);
                    map.fitBounds(group.getBounds().pad(0.15));
                } catch(e){ console.warn('fitBounds error', e); }
            } else {
                // fallback marker
                const demo = L.circleMarker([39.0, 35.0], { radius: 8, color: '#007bff', fillColor: '#007bff', fillOpacity: 0.6 }).addTo(map).bindPopup('Henüz konum bilgisi girilmiş proje yok.');
                markerLayers.push(demo);
                map.addLayer(clusterGroup);
            }
        } catch(e){
            console.warn('marker clustering error', e);
        }

    } catch (error) {
        console.error('Harita veya grafikler yüklenirken hata:', error);
    }
});
</script>
<script>
// Enhanced Client-side interactions
(function(){
    const qs = (s, el=document) => el.querySelector(s);
    const qsa = (s, el=document) => Array.from(el.querySelectorAll(s));

    // Elements
    const grid = qs('#projects-grid');
    const search = qs('#project-search');
    const statusFilter = qs('#status-filter');
    const progressFilter = qs('#progress-filter');
    const budgetFilter = qs('#budget-filter');
    const sortSelect = qs('#sort-projects');
    const toggleView = qs('#toggle-view');
    const exportBtn = qs('#export-csv');
    const refreshBtn = qs('#refresh-dashboard');

    // Enhanced filtering function
    function applyFilters(){
        const term = (search?.value||'').trim().toLowerCase();
        const status = (statusFilter?.value||'').toLowerCase();
        const progress = progressFilter?.value || '';
        const budget = budgetFilter?.value || '';

        qsa('.project-card', grid).forEach(card => {
            const name = card.dataset.name || '';
            const code = card.dataset.code || '';
            const cardStatus = card.dataset.status || '';
            const cardProgress = parseInt(card.dataset.progress || 0);
            const cardValue = parseInt(card.dataset.value || 0);

            let matches = true;

            // Text search
            if(term && !name.includes(term) && !code.includes(term)){
                matches = false;
            }

            // Status filter
            if(status && cardStatus !== status){
                matches = false;
            }

            // Progress filter
            if(progress){
                const [min, max] = progress.split('-').map(Number);
                if(cardProgress < min || cardProgress > max){
                    matches = false;
                }
            }

            // Budget filter
            if(budget){
                const [min, max] = budget.split('-').map(Number);
                if(cardValue < min || cardValue > max){
                    matches = false;
                }
            }

            card.style.display = matches ? '' : 'none';
        });

        updateProjectCount();
    }

    // Update visible project count
    function updateProjectCount(){
        const visible = qsa('.project-card', grid).filter(card => card.style.display !== 'none').length;
        const total = qsa('.project-card', grid).length;
        // Update any count displays if they exist
    }

    // Enhanced sorting function
    function sortProjects(){
        if(!sortSelect) return;

        const sortBy = sortSelect.value;
        const cards = qsa('.project-card', grid);

        cards.sort((a, b) => {
            let valueA, valueB;

            switch(sortBy){
                case 'name':
                    valueA = a.dataset.name || '';
                    valueB = b.dataset.name || '';
                    return valueA.localeCompare(valueB);

                case 'progress':
                    valueA = parseInt(a.dataset.progress || 0);
                    valueB = parseInt(b.dataset.progress || 0);
                    return valueB - valueA; // Descending

                case 'value':
                    valueA = parseInt(a.dataset.value || 0);
                    valueB = parseInt(b.dataset.value || 0);
                    return valueB - valueA; // Descending

                case 'status':
                    valueA = a.dataset.status || '';
                    valueB = b.dataset.status || '';
                    return valueA.localeCompare(valueB);

                default:
                    return 0;
            }
        });

        // Reorder DOM elements
        cards.forEach(card => grid.appendChild(card));
    }

    // View toggle functionality
    let listMode = false;
    if(toggleView){
        toggleView.addEventListener('click', function(e){
            listMode = !listMode;
            this.innerHTML = listMode ? '<i class="mdi mdi-view-list"></i>' : '<i class="mdi mdi-view-grid"></i>';

            qsa('.project-card', grid).forEach(card => {
                if(listMode){
                    card.className = card.className.replace(/col-lg-\d+/g, 'col-12');
                    card.className = card.className.replace(/col-md-\d+/g, '');
                } else {
                    card.className = card.className.replace(/col-12/g, 'col-lg-4 col-md-6');
                }
            });
        });
    }

    // Enhanced CSV export
    if(exportBtn){
        exportBtn.addEventListener('click', function(){
            const visibleCards = qsa('.project-card', grid).filter(card => card.style.display !== 'none');
            const headers = ['ID', 'Proje Adı', 'Kod', 'Durum', 'İlerleme (%)', 'Bütçe (₺)', 'URL'];
            const rows = [headers];

            visibleCards.forEach(card => {
                rows.push([
                    card.dataset.id || '',
                    `"${card.dataset.name || ''}"`,
                    `"${card.dataset.code || ''}"`,
                    card.dataset.status || '',
                    card.dataset.progress || '0',
                    card.dataset.value || '0',
                    card.dataset.url || ''
                ]);
            });

            const csv = rows.map(row => row.join(',')).join('\n');
            const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
            const url = URL.createObjectURL(blob);
            const link = document.createElement('a');
            link.href = url;
            link.download = `portfolio_projects_${new Date().toISOString().split('T')[0]}.csv`;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            URL.revokeObjectURL(url);

            // Show success toast
            showToast('CSV dosyası başarıyla indirildi!', 'success');
        });
    }

    // Dashboard refresh functionality
    if(refreshBtn){
        refreshBtn.addEventListener('click', function(){
            this.innerHTML = '<i class="mdi mdi-loading mdi-spin me-1"></i> Yenileniyor...';
            this.disabled = true;

            // Simulate refresh (replace with actual API call)
            setTimeout(() => {
                location.reload();
            }, 1000);
        });
    }

    // Enhanced project preview modal
    function setupProjectPreview(){
        qsa('.project-card', grid).forEach(card => {
            card.addEventListener('click', function(e){
                // Don't trigger on button/link clicks
                if(e.target.closest('a, button, .dropdown')) return;

                const id = card.dataset.id;
                if(!id) return;

                const modal = qs('#projectPreviewModal');
                const titleEl = qs('#previewTitle');
                const subtitleEl = qs('#previewSubtitle');
                const imgEl = qs('#previewImage');
                const valueEl = qs('#previewValue');
                const progressEl = qs('#previewProgress');
                const progressBarEl = qs('#previewProgressBar');
                const descEl = qs('#previewDescription');
                const statusEl = qs('#previewStatus');
                const lastUpdateEl = qs('#previewLastUpdate');
                const detailsBtn = qs('#previewDetailsBtn');

                // Reset modal content
                titleEl.textContent = card.dataset.name || 'Proje';
                subtitleEl.textContent = card.dataset.code || 'Kod belirtilmemiş';
                imgEl.src = card.dataset.thumb || '/images/project-placeholder.jpg';
                valueEl.textContent = parseInt(card.dataset.value || 0).toLocaleString() + ' ₺';
                progressEl.textContent = (card.dataset.progress || 0) + '%';
                progressBarEl.style.width = (card.dataset.progress || 0) + '%';
                detailsBtn.href = card.dataset.url || '#';
                lastUpdateEl.textContent = new Date().toLocaleDateString('tr-TR');

                // Status badge
                const status = card.dataset.status || 'unknown';
                const statusConfig = {
                    'active': { class: 'success', icon: 'play-circle', text: 'Aktif' },
                    'paused': { class: 'warning', icon: 'pause-circle', text: 'Beklemede' },
                    'completed': { class: 'primary', icon: 'check-circle', text: 'Tamamlandı' }
                };
                const currentStatus = statusConfig[status] || { class: 'secondary', icon: 'help-circle', text: 'Bilinmiyor' };

                statusEl.innerHTML = `
                    <span class="badge bg-${currentStatus.class} me-2">
                        <i class="mdi mdi-${currentStatus.icon} me-1"></i>${currentStatus.text}
                    </span>
                `;

                // Set progress bar color
                progressBarEl.className = `progress-bar bg-${currentStatus.class}`;

                // Show modal
                const bootstrapModal = new bootstrap.Modal(modal);
                bootstrapModal.show();

                // Load additional details via AJAX
                fetch(`/api/projects/${id}`, {
                    headers: { 'Accept': 'application/json' }
                })
                .then(response => response.json())
                .then(data => {
                    if(data.description){
                        descEl.textContent = data.description;
                    }
                })
                .catch(err => {
                    console.warn('Failed to load project details:', err);
                    descEl.textContent = 'Detay bilgisi yüklenemedi.';
                });
            });
        });
    }

    // Portfolio settings
    function setupPortfolioSettings(){
        const saveBtn = qs('#saveSettings');
        if(!saveBtn) return;

        saveBtn.addEventListener('click', function(){
            const settings = {
                showKPIs: qs('#showKPIs')?.checked,
                showMap: qs('#showMap')?.checked,
                showCharts: qs('#showCharts')?.checked,
                showFocusAreas: qs('#showFocusAreas')?.checked,
                refreshInterval: qs('#refreshInterval')?.value,
                defaultView: qs('#defaultView')?.value
            };

            // Save to localStorage
            localStorage.setItem('portfolioSettings', JSON.stringify(settings));

            // Apply settings immediately
            applyPortfolioSettings(settings);

            // Close modal
            bootstrap.Modal.getInstance(qs('#portfolioSettingsModal')).hide();

            showToast('Ayarlar başarıyla kaydedildi!', 'success');
        });
    }

    // Apply portfolio settings
    function applyPortfolioSettings(settings){
    function applyPortfolioSettings(settings){
        // Implementation would hide/show sections based on settings
    }

    // Load saved settings
    function loadPortfolioSettings(){
        const saved = localStorage.getItem('portfolioSettings');
        if(saved){
            const settings = JSON.parse(saved);
            applyPortfolioSettings(settings);
        }
    }

    // Toast notification helper
    function showToast(message, type = 'info'){
        // Create toast element if it doesn't exist
        let toastContainer = qs('.toast-container');
        if(!toastContainer){
            toastContainer = document.createElement('div');
            toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
            document.body.appendChild(toastContainer);
        }

        const toast = document.createElement('div');
        toast.className = `toast align-items-center text-white bg-${type} border-0`;
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">${message}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;

        toastContainer.appendChild(toast);
        const bootstrapToast = new bootstrap.Toast(toast);
        bootstrapToast.show();

        // Remove toast after it's hidden
        toast.addEventListener('hidden.bs.toast', () => {
            toast.remove();
        });
    }

    // Event listeners
    if(search) search.addEventListener('input', applyFilters);
    if(statusFilter) statusFilter.addEventListener('change', applyFilters);
    if(progressFilter) progressFilter.addEventListener('change', applyFilters);
    if(budgetFilter) budgetFilter.addEventListener('change', applyFilters);
    if(sortSelect) sortSelect.addEventListener('change', sortProjects);

    // Initialize
    setupProjectPreview();
    setupPortfolioSettings();
    loadPortfolioSettings();

    // Map view controls
    qsa('[data-map-view]').forEach(btn => {
        btn.addEventListener('click', function(){
            const view = this.dataset.mapView;

            // Update active button
            qsa('[data-map-view]').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
        });
    });

})();

// Modern Portfolio Management Enhancements v2.0
document.addEventListener('DOMContentLoaded', function() {
    // AGGRESSIVE KPI Duplicate Removal
    function hideDuplicateKPIs() {
        // Hide any old KPI cards that might be cached
        const oldKpiCards = document.querySelectorAll('.card.kpi-card:not(.modern-kpi-card)');
        oldKpiCards.forEach(card => {
            card.style.display = 'none';
            card.style.visibility = 'hidden';
            card.style.opacity = '0';
            card.style.height = '0';
            card.style.overflow = 'hidden';
        });

        // Hide cards that contain KPI-like content but aren't modern
        const suspiciousCards = document.querySelectorAll('.card:not(.modern-kpi-card)');
        suspiciousCards.forEach(card => {
            const hasKpiContent = card.querySelector('.display-4, .h2, .fw-bold.text-success, .fw-bold.text-danger, .fw-bold.text-warning, .fw-bold.text-primary');
            const hasKpiValue = card.textContent.includes('₺') || card.textContent.includes('%') || card.textContent.includes('Toplam');
            const isInKpiRow = card.closest('.row')?.previousElementSibling?.textContent?.includes('Performans Metrikleri');

            if ((hasKpiContent || hasKpiValue || isInKpiRow) && !card.classList.contains('modern-kpi-card')) {
                card.style.display = 'none !important';
                card.remove(); // Complete removal
            }
        });

        // Hide any remaining duplicate content
        const allCards = document.querySelectorAll('.card');
        const modernKpiExists = document.querySelector('.modern-kpi-card');

        if (modernKpiExists) {
            allCards.forEach(card => {
                if (!card.classList.contains('modern-kpi-card') &&
                    !card.classList.contains('modern-action-card') &&
                    !card.classList.contains('analytics-card') &&
                    !card.classList.contains('project-card-enhanced')) {

                    const cardText = card.textContent;
                    if (cardText.includes('Toplam Proje') ||
                        cardText.includes('Portföy Sağlık') ||
                        cardText.includes('₺') && cardText.length < 200) {
                        card.remove();
                    }
                }
            });
        }
    }

    // Run immediately
    hideDuplicateKPIs();

    // Run again after a short delay to catch any dynamically loaded content
    setTimeout(hideDuplicateKPIs, 100);
    setTimeout(hideDuplicateKPIs, 500);
    setTimeout(hideDuplicateKPIs, 1000);

    // Initialize AOS (Animate On Scroll)
    if (typeof AOS !== 'undefined') {
        AOS.init({
            duration: 600,
            easing: 'ease-out-cubic',
            once: true,
            offset: 50
        });
    }

    // Smooth hover effects for modern cards
    const modernCards = document.querySelectorAll('.modern-kpi-card, .modern-action-card');
    modernCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-8px) scale(1.02)';
        });

        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });

    // Progressive loading effect for KPI values
    const kpiValues = document.querySelectorAll('.kpi-value');
    kpiValues.forEach(value => {
        const targetValue = parseInt(value.textContent);
        if (!isNaN(targetValue)) {
            animateValue(value, 0, targetValue, 1500);
        }
    });

    // Progress bar animations
    const progressLines = document.querySelectorAll('.progress-line');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.transform = 'scaleX(1)';
                entry.target.style.transformOrigin = 'left';
            }
        });
    }, { threshold: 0.1 });

    progressLines.forEach(line => {
        line.style.transform = 'scaleX(0)';
        line.style.transition = 'transform 1.5s ease-out';
        observer.observe(line);
    });

    // Real-time updates simulation
    setInterval(() => {
        updateLastUpdateTime();
    }, 60000); // Update every minute
});

function animateValue(element, start, end, duration) {
    const startTimestamp = performance.now();
    const step = (timestamp) => {
        const elapsed = timestamp - startTimestamp;
        const progress = Math.min(elapsed / duration, 1);
        const currentValue = Math.floor(progress * (end - start) + start);

        // Preserve original formatting (M, %, etc.)
        const originalText = element.textContent;
        const suffix = originalText.replace(/[\d,.-]/g, '');

        if (suffix.includes('M')) {
            element.textContent = (currentValue / 1000000).toFixed(1) + 'M';
        } else if (suffix.includes('₺')) {
            element.textContent = '₺' + currentValue.toLocaleString();
        } else if (suffix.includes('%')) {
            element.textContent = currentValue + '%';
        } else {
            element.textContent = currentValue + suffix;
        }

        if (progress < 1) {
            requestAnimationFrame(step);
        }
    };
    requestAnimationFrame(step);
}

function updateLastUpdateTime() {
    const timeElements = document.querySelectorAll('.last-update-info small');
    const now = new Date();
    const timeString = now.toLocaleString('tr-TR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });

    timeElements.forEach(element => {
        element.innerHTML = '<i class="mdi mdi-clock-outline me-1"></i>Son güncelleme: ' + timeString;
    });
}

// Portföy Seçimi Fonksiyonları
let selectedPortfolio = 'ana-portfoy';

function selectPortfolio(portfolioId) {
    // Önceki seçimi kaldır
    document.querySelectorAll('.list-group-item').forEach(item => {
        item.classList.remove('active');
    });

    // Yeni seçimi işaretle
    event.target.closest('.list-group-item').classList.add('active');
    selectedPortfolio = portfolioId;
}

function applyPortfolioSelection() {
    const portfolioNames = {
        'ana-portfoy': 'Ana Portföy',
        'konut-portfoy': 'Konut Portföyü',
        'ticari-portfoy': 'Ticari Portföy',
        'endustriyel-portfoy': 'Endüstriyel Portföy',
        'altyapi-portfoy': 'Altyapı Portföyü'
    };

    showNotification(`${portfolioNames[selectedPortfolio]} seçildi`, 'info');

    // Modal'ı kapat
    const modal = bootstrap.Modal.getInstance(document.getElementById('portfolioModal'));
    modal.hide();

    // Portföy başlığını güncelle (eğer varsa)
    const titleElement = document.querySelector('.page-title-box h4');
    if (titleElement) {
        titleElement.innerHTML = `
            <i class="mdi mdi-view-dashboard-variant me-2"></i>
            ${portfolioNames[selectedPortfolio]}
        `;
    }
}

// Bildirim Fonksiyonu
function showNotification(message, type = 'info') {
    // Toast bildirimi oluştur
    const toastContainer = document.querySelector('.toast-container') || createToastContainer();
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white bg-${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'info'} border-0`;
    toast.setAttribute('role', 'alert');
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                <i class="mdi mdi-${type === 'success' ? 'check-circle' : type === 'error' ? 'alert-circle' : 'information'} me-2"></i>
                ${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;

    toastContainer.appendChild(toast);
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();

    // Toast otomatik temizlik
    toast.addEventListener('hidden.bs.toast', () => {
        toast.remove();
    });
}

function createToastContainer() {
    const container = document.createElement('div');
    container.className = 'toast-container position-fixed top-0 end-0 p-3';
    container.style.zIndex = '1055';
    document.body.appendChild(container);
    return container;
}
</script>

<!-- AOS Library -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js?v={{ time() }}"></script>
<link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css?v={{ time() }}">

<!-- Force browser cache refresh -->
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="0">
<meta name="cache-buster" content="{{ time() }}">
<meta name="portfolio-version" content="v2.1-{{ time() }}">

@endsection

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
/* Enhanced Portfolio Management Styles */

/* ŞantiyeConnect Modern Color Palette Variables */
:root {
    --santiye-primary: #F59E0B;      /* amber-500 - Ana butonlar */
    --santiye-accent: #3B82F6;       /* blue-500 - Linkler/İkonlar */
    --santiye-success: #10B981;      /* emerald-500 - Başarı */
    --santiye-danger: #DC2626;       /* red-600 - Hata/Tehlike */
    --santiye-warning: #F59E0B;      /* amber-500 - Uyarı */
    --santiye-info: #3B82F6;         /* blue-500 - Bilgi */
    --santiye-dark: #334155;         /* Koyu bölümler */
    --santiye-light: #F3F4F6;        /* Arka plan */

    /* Modern Design System */
    --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);

    --radius-sm: 0.375rem;
    --radius-md: 0.5rem;
    --radius-lg: 0.75rem;
    --radius-xl: 1rem;

    --spacing-xs: 0.5rem;
    --spacing-sm: 1rem;
    --spacing-md: 1.5rem;
    --spacing-lg: 2rem;
    --spacing-xl: 3rem;
}

/* Z-Index Management System */
body {
    position: relative;
    z-index: 0;
}

.main-content {
    position: relative;
    z-index: 1;
}

/* Override Bootstrap Navigation Z-Index if needed */
.navbar,
.navbar-nav,
.dropdown-menu {
    z-index: 1050 !important;
}

/* General Z-Index Reset for Common Elements */
.container,
.container-fluid {
    position: relative;
    z-index: auto;
}

.row {
    position: relative;
    z-index: auto;
}

.col,
.col-1, .col-2, .col-3, .col-4, .col-5, .col-6,
.col-7, .col-8, .col-9, .col-10, .col-11, .col-12,
.col-sm, .col-md, .col-lg, .col-xl, .col-xxl {
    position: relative;
    z-index: auto;
}

/* Modern Layout Styles */
.modern-header {
    background: linear-gradient(135deg, rgba(255,255,255,0.95) 0%, rgba(248,250,252,0.95) 100%);
    backdrop-filter: blur(10px);
    border-radius: var(--radius-lg);
    padding: var(--spacing-lg);
    box-shadow: var(--shadow-md);
    border: 1px solid rgba(255,255,255,0.8);
    position: relative;
    z-index: 10;
}

.header-title {
    font-size: 2rem;
    font-weight: 700;
    color: var(--santiye-dark);
    margin-bottom: 0.5rem;
}

.header-subtitle {
    font-size: 1.1rem;
    font-weight: 400;
}

.breadcrumb-modern {
    font-size: 0.875rem;
    font-weight: 500;
}

.btn-modern {
    border-radius: var(--radius-md);
    padding: 0.625rem 1.25rem;
    font-weight: 600;
    font-size: 0.875rem;
    transition: all 0.2s ease;
    border: 1px solid transparent;
}

.btn-modern:hover {
    transform: translateY(-1px);
    box-shadow: var(--shadow-md);
}

.last-update-info {
    font-size: 0.75rem;
    opacity: 0.8;
}

/* Modern KPI Cards */
.modern-kpi-section {
    margin-bottom: var(--spacing-xl);
    margin-top: 1rem;
}

.modern-only-section {
    position: relative;
    z-index: 100 !important;
}

.modern-only-section::before {
    content: '';
    position: absolute;
    top: -50px;
    left: -50px;
    right: -50px;
    bottom: -50px;
    background: rgba(255, 255, 255, 0.95);
    z-index: -1;
    border-radius: 20px;
}

.section-header {
    margin-bottom: var(--spacing-lg);
}

.section-title {
    font-size: 1.375rem;
    font-weight: 700;
    color: var(--santiye-dark);
    margin-bottom: 0.25rem;
}

.section-subtitle {
    font-size: 0.875rem;
    font-weight: 500;
}

.modern-kpi-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.8);
    border-radius: var(--radius-xl);
    padding: var(--spacing-lg);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
    height: 100%;
}

.modern-kpi-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--santiye-primary) 0%, var(--santiye-accent) 100%);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.modern-kpi-card:hover {
    transform: translateY(-8px);
    box-shadow: var(--shadow-xl);
    border-color: rgba(245, 158, 11, 0.3);
}

.modern-kpi-card:hover::before {
    opacity: 1;
}

.kpi-card-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: var(--spacing-md);
}

.kpi-icon-modern {
    width: 3.5rem;
    height: 3.5rem;
    border-radius: var(--radius-lg);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
    position: relative;
    overflow: hidden;
}

.kpi-icon-modern::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: linear-gradient(45deg, transparent, rgba(255,255,255,0.2), transparent);
    transform: rotate(45deg);
    transition: transform 0.6s ease;
}

.modern-kpi-card:hover .kpi-icon-modern::before {
    transform: rotate(45deg) translate(100%, 100%);
}

.kpi-trend {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    font-size: 0.75rem;
    font-weight: 600;
    padding: 0.25rem 0.5rem;
    border-radius: var(--radius-sm);
}

.kpi-trend.positive {
    background: rgba(16, 185, 129, 0.1);
    color: var(--santiye-success);
}

.kpi-trend.negative {
    background: rgba(220, 38, 38, 0.1);
    color: var(--santiye-danger);
}

.kpi-trend.neutral {
    background: rgba(107, 114, 128, 0.1);
    color: #6B7280;
}

.kpi-content {
    text-align: left;
}

.kpi-value {
    font-size: 2.25rem;
    font-weight: 800;
    line-height: 1.1;
    margin-bottom: 0.5rem;
}

.kpi-label {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--santiye-dark);
    margin-bottom: var(--spacing-sm);
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.kpi-progress {
    width: 100%;
    height: 0.25rem;
    background: rgba(0, 0, 0, 0.05);
    border-radius: var(--radius-sm);
    margin-bottom: var(--spacing-sm);
    overflow: hidden;
}

.progress-line {
    height: 100%;
    border-radius: var(--radius-sm);
    transition: width 1s ease-in-out;
    position: relative;
    overflow: hidden;
}

.progress-line::after {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
    animation: shimmer 2s infinite;
}

@keyframes shimmer {
    0% { left: -100%; }
    100% { left: 100%; }
}

.kpi-description {
    font-size: 0.75rem;
    line-height: 1.4;
}

/* Modern Action Center */
.modern-action-center {
    margin-bottom: var(--spacing-xl);
}

.modern-action-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.8);
    border-radius: var(--radius-xl);
    padding: var(--spacing-lg);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    height: 100%;
    position: relative;
    overflow: hidden;
}

.modern-action-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.modern-action-card.critical::before {
    background: linear-gradient(90deg, var(--santiye-danger) 0%, #FF6B6B 100%);
}

.modern-action-card:hover {
    transform: translateY(-6px);
    box-shadow: var(--shadow-xl);
}

.modern-action-card:hover::before {
    opacity: 1;
}

.action-card-header {
    display: flex;
    align-items: center;
    gap: var(--spacing-md);
    margin-bottom: var(--spacing-md);
}

.action-icon-wrapper {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
}

.action-icon {
    width: 3rem;
    height: 3rem;
    border-radius: var(--radius-lg);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    color: white;
}

.action-count {
    text-align: center;
}

.count-number {
    display: block;
    font-size: 1.5rem;
    font-weight: 800;
    line-height: 1;
}

.count-label {
    display: block;
    font-size: 0.625rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    opacity: 0.8;
}

.action-title h5 {
    font-size: 1.125rem;
    font-weight: 700;
    margin-bottom: 0.25rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .header-title {
        font-size: 1.5rem;
    }

    .modern-kpi-card {
        padding: var(--spacing-md);
    }

    .kpi-value {
        font-size: 1.875rem;
    }

    .modern-action-card {
        padding: var(--spacing-md);
    }
}

/* Animation Classes */
[data-aos] {
    opacity: 0;
    transition: opacity 0.6s ease, transform 0.6s ease;
}

[data-aos].aos-animate {
    opacity: 1;
}

/* Hide any duplicate KPI cards - AGGRESSIVE HIDING */
.card.kpi-card:not(.modern-kpi-card),
.kpi-card:not(.modern-kpi-card),
.card:has(.kpi-value):not(.modern-kpi-card),
.row .col:has(.card .kpi-value):not(:has(.modern-kpi-card)),
[class*="kpi"]:not(.modern-kpi-card):not(.modern-kpi-section):not(.kpi-icon-modern):not(.kpi-card-header):not(.kpi-content):not(.kpi-value):not(.kpi-label):not(.kpi-progress):not(.kpi-description):not(.kpi-trend) {
    display: none !important;
    visibility: hidden !important;
    opacity: 0 !important;
    height: 0 !important;
    overflow: hidden !important;
}

/* Ensure only modern KPI cards are visible */
.modern-kpi-card {
    display: block !important;
    visibility: visible !important;
    opacity: 1 !important;
}

/* Force hide old KPI elements by common patterns */
.card:not(.modern-kpi-card):has(h3),
.card:not(.modern-kpi-card):has(.text-success),
.card:not(.modern-kpi-card):has(.text-danger),
.card:not(.modern-kpi-card):has(.text-warning),
.card:not(.modern-kpi-card):has(.text-primary) {
    display: none !important;
}

/* Specific hiding for old KPI structures */
.card-body:has(.display-4):not(.modern-kpi-card .card-body),
.card-body:has(.h2):not(.modern-kpi-card .card-body),
.card-body:has(.fw-bold.text-success):not(.modern-kpi-card .card-body),
.card-body:has(.fw-bold.text-danger):not(.modern-kpi-card .card-body) {
    display: none !important;
}

/* Focus Areas Enhancement */
.focus-area-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    border: 1px solid #e9ecef;
    height: 100%;
    transition: all 0.3s ease;
}

.focus-area-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(245, 158, 11, 0.1) !important;
    border-left: 3px solid var(--santiye-primary);
}

.focus-header {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
}

.focus-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
}

.critical-project-item {
    padding: 12px 0;
    border-bottom: 1px solid #f8f9fa;
}

.critical-project-item:last-child {
    border-bottom: none;
}

.project-status-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    margin-top: 8px;
}

.milestone-item {
    padding: 12px 0;
    border-bottom: 1px solid #f8f9fa;
}

.milestone-item:last-child {
    border-bottom: none;
}

.milestone-date-box {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 8px;
    text-align: center;
    min-width: 50px;
}

.milestone-day {
    font-weight: bold;
    font-size: 16px;
    line-height: 1;
}

.milestone-month {
    font-size: 10px;
    text-transform: uppercase;
    color: #6c757d;
}

.risk-item {
    padding: 12px 0;
    border-bottom: 1px solid #f8f9fa;
}

.risk-item:last-child {
    border-bottom: none;
}

.severity-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    margin-top: 8px;
}

.empty-state {
    padding: 40px 20px;
}

/* Map Legend and Stats */
.map-legend {
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(0,0,0,0.1);
    min-width: 140px;
    z-index: 400 !important;
    position: relative;
}

.legend-item {
    display: flex;
    align-items: center;
    margin-bottom: 5px;
}

.legend-dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    margin-right: 8px;
}

.map-stats {
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(0,0,0,0.1);
    z-index: 400 !important;
    position: relative;
}

/* Map Container Z-Index Fix */
#portfolio-map {
    z-index: 1 !important;
    position: relative;
}

/* Leaflet Map Z-Index Override */
.leaflet-pane {
    z-index: 1 !important;
}

.leaflet-tile-pane {
    z-index: 1 !important;
}

.leaflet-overlay-pane {
    z-index: 2 !important;
}

.leaflet-shadow-pane {
    z-index: 3 !important;
}

.leaflet-marker-pane {
    z-index: 4 !important;
}

.leaflet-tooltip-pane {
    z-index: 5 !important;
}

.leaflet-popup-pane {
    z-index: 6 !important;
}

.leaflet-map-pane {
    z-index: 1 !important;
}

.leaflet-control-container {
    z-index: 400 !important;
}

.leaflet-control {
    z-index: 400 !important;
}

.leaflet-top,
.leaflet-bottom {
    z-index: 400 !important;
}

/* Force map container to stay below navigation */
.portfolio-map-container {
    position: relative;
    z-index: 1;
    max-height: 500px;
    overflow: hidden;
}

/* Container Z-Index Management */
.container-fluid,
.row,
.col-12 {
    position: relative;
    z-index: 5;
}

/* Card Z-Index Fix */
.card {
    position: relative;
    z-index: 10;
}

.modern-kpi-card,
.modern-action-card,
.analytics-card,
.project-card-enhanced {
    position: relative;
    z-index: 10;
}

/* Section Spacing Fix */
.mb-4 {
    margin-bottom: 1.5rem !important;
}

/* Page Content Wrapper */
.content-wrapper {
    min-height: 100vh;
    padding-top: 20px;
    position: relative;
    z-index: 5;
}

/* Analytics Cards */
.analytics-card {
    background: white;
    border-radius: 12px;
    padding: 24px;
    border: 1px solid #e9ecef;
    height: 100%;
    transition: all 0.3s ease;
}

.analytics-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(245, 158, 11, 0.1) !important;
    border-left: 3px solid var(--santiye-primary);
}

.analytics-header h5 {
    color: #495057;
}

.chart-insights {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 15px;
}

.insight-item h6 {
    font-size: 18px;
    margin-bottom: 4px;
}

.quadrant-info {
    text-align: center;
}

.comparison-summary {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 20px;
}

.summary-item h6 {
    font-size: 20px;
    margin-bottom: 4px;
}

/* Enhanced Project Cards */
.project-card-enhanced {
    transition: all 0.3s ease;
    border-radius: 16px;
    overflow: hidden;
    background: white;
}

.project-card-enhanced:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 28px rgba(245, 158, 11, 0.15) !important;
    border: 1px solid var(--santiye-primary);
}

.project-header {
    height: 200px;
    position: relative;
    overflow: hidden;
}

.project-header-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.project-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 48px;
}

.progress-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: rgba(0,0,0,0.1);
    padding: 0;
}

.project-title {
    font-size: 1.1rem;
    color: #2d3748;
    line-height: 1.3;
}

.project-code {
    font-family: 'Courier New', monospace;
    font-size: 0.8rem;
    background: #f7fafc;
    padding: 2px 6px;
    border-radius: 4px;
    display: inline-block;
}

.project-metrics {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 12px;
}

.metric-item {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.metric-label {
    margin-bottom: 4px;
}

.metric-value {
    font-size: 1.1rem;
}

.project-actions .btn {
    font-weight: 500;
    font-size: 0.85rem;
}

/* Filter Bar */
.project-filters {
    border: 1px solid #e9ecef;
}

/* Responsive Design */
@media (max-width: 768px) {
    .kpi-card {
        margin-bottom: 1rem;
    }

    .focus-area-card {
        margin-bottom: 1.5rem;
    }

    .analytics-card {
        margin-bottom: 1.5rem;
    }

    .project-header {
        height: 160px;
    }

    .project-filters .row > div {
        margin-bottom: 0.5rem;
    }
}

@media (max-width: 576px) {
    .project-header {
        height: 140px;
    }

    .project-placeholder {
        font-size: 36px;
    }
}

/* ApexCharts Customization */
.apexcharts-canvas {
    font-family: inherit !important;
}

.apexcharts-legend {
    font-size: 12px !important;
}

/* Animation Effects */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.project-card-enhanced {
    animation: fadeInUp 0.5s ease forwards;
}

/* Loading States */
.loading-skeleton {
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: loading 1.5s infinite;
}

@keyframes loading {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}

/* Button Enhancements */
.btn-outline-primary {
    border-color: var(--santiye-primary);
    color: var(--santiye-primary);
}

.btn-outline-primary:hover,
.btn-outline-primary.active {
    background-color: var(--santiye-primary);
    border-color: var(--santiye-primary);
    color: white;
}

.btn-primary {
    background-color: var(--santiye-primary);
    border-color: var(--santiye-primary);
}

.btn-primary:hover {
    background-color: #E08E00;
    border-color: #E08E00;
}

.btn-outline-danger:hover {
    background-color: var(--santiye-danger);
    border-color: var(--santiye-danger);
}

.btn-outline-info:hover {
    background-color: var(--santiye-info);
    border-color: var(--santiye-info);
}

/* Gradient Backgrounds for Project Placeholders */
.bg-gradient-primary {
    background: linear-gradient(135deg, var(--santiye-primary) 0%, #E08E00 100%);
}

.bg-gradient-success {
    background: linear-gradient(135deg, var(--santiye-success) 0%, #059669 100%);
}

.bg-gradient-danger {
    background: linear-gradient(135deg, var(--santiye-danger) 0%, #B91C1C 100%);
}

.bg-gradient-warning {
    background: linear-gradient(135deg, var(--santiye-warning) 0%, #D97706 100%);
}

.bg-gradient-info {
    background: linear-gradient(135deg, var(--santiye-info) 0%, #2563EB 100%);
}

.bg-gradient-secondary {
    background: linear-gradient(135deg, #6B7280 0%, #4B5563 100%);
}

/* Badge Enhancements */
.badge {
    font-weight: 600;
    font-size: 0.7rem;
    padding: 0.4em 0.65em;
    border-radius: var(--radius-md);
}

.bg-success-subtle {
    background-color: rgba(16, 185, 129, 0.1) !important;
}

.text-success {
    color: var(--santiye-success) !important;
}

.bg-danger-subtle {
    background-color: rgba(220, 38, 38, 0.1) !important;
}

.text-danger {
    color: var(--santiye-danger) !important;
}

.bg-primary-subtle {
    background-color: rgba(245, 158, 11, 0.1) !important;
}

.text-primary {
    color: var(--santiye-primary) !important;
}

.bg-warning-subtle {
    background-color: rgba(245, 158, 11, 0.1) !important;
}

.text-warning {
    color: var(--santiye-warning) !important;
}

.bg-info-subtle {
    background-color: rgba(59, 130, 246, 0.1) !important;
}

.text-info {
    color: var(--santiye-info) !important;
}

.bg-secondary-subtle {
    background-color: rgba(107, 114, 128, 0.1) !important;
}

.text-secondary {
    color: #6B7280 !important;
}

/* Chart Container */
.chart-container {
    position: relative;
    width: 100%;
    height: auto;
}

.chart-container .apexcharts-canvas {
    background: transparent !important;
}
</style>
@endpush
