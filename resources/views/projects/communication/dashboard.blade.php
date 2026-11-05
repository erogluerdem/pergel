@extends('layouts.master')
@section('title', 'İletişim ve İşbirliği')
@section('topbar-title', 'Projeler')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css" rel="stylesheet">
<style>
    /* ROYAL DARK SLATE & GOLDEN AMBER GLASSMORPHIC THEME */
    .communication-hero {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #b45309 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.4) !important;
        padding: 32px !important;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .communication-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #d97706, #2563eb, #16a34a, #d97706);
        background-size: 300% 100%;
        animation: commGlow 4s linear infinite;
    }

    @keyframes commGlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .comm-badge-pill {
        background: rgba(245, 158, 11, 0.25) !important;
        border: 1px solid rgba(245, 158, 11, 0.5) !important;
        color: #fef08a !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    /* Stat Cards */
    .stat-card {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08) !important;
    }

    .stat-card.rfi-card { border-left: 5px solid #0284c7 !important; }
    .stat-card.submittal-card { border-left: 5px solid #d97706 !important; }
    .stat-card.action-card { border-left: 5px solid #16a34a !important; }
    .stat-card.overdue-card { border-left: 5px solid #dc2626 !important; }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
        margin: 0 auto 1rem;
        transition: all 0.3s ease;
    }

    .trend-indicator {
        position: absolute;
        top: 15px;
        right: 15px;
        padding: 4px 10px;
        background: #f8fafc;
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        font-weight: 800;
    }

    .trend-up { color: #16a34a; }
    .trend-down { color: #dc2626; }
    .trend-neutral { color: #64748b; }

    /* Chart & Custom Cards */
    .chart-card {
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 18px !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04) !important;
        background: #ffffff !important;
        overflow: hidden;
    }

    .activity-item {
        border-radius: 14px !important;
        border: 1.5px solid #cbd5e1 !important;
        border-left-width: 6px !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease !important;
        background: #ffffff !important;
    }

    .activity-item:hover {
        transform: translateX(4px) !important;
        border-color: #d97706 !important;
        box-shadow: 0 4px 15px rgba(15, 23, 42, 0.08) !important;
    }

    .activity-item.rfi { border-left-color: #0284c7 !important; }
    .activity-item.submittal { border-left-color: #d97706 !important; }
    .activity-item.meeting { border-left-color: #16a34a !important; }
    .activity-item.action { border-left-color: #9333ea !important; }

    .quick-action-btn {
        border-radius: 50px !important;
        padding: 12px 24px !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
        transition: all 0.2s ease !important;
    }
    .quick-action-btn:hover {
        transform: translateY(-2px) !important;
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12) !important;
    }

    .custom-input-comm {
        background-color: #f8fafc !important;
        border: 1.5px solid #94a3b8 !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        border-radius: 10px !important;
    }
    .custom-input-comm:focus {
        background-color: #ffffff !important;
        border-color: #d97706 !important;
        box-shadow: 0 0 0 3px rgba(217, 119, 6, 0.15) !important;
    }

    .modal-content {
        border-radius: 20px !important;
        border: 1.5px solid #cbd5e1 !important;
        overflow: hidden;
    }

    .notification-badge {
        position: absolute;
        top: -5px;
        right: -5px;
        background: #dc2626;
        color: white;
        border-radius: 50%;
        min-width: 22px;
        height: 22px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        font-weight: 800;
    }
</style>
@endpush

@section('content')

<div class="container-fluid px-4">
    <!-- HERO HEADER BANNER -->
    <div class="communication-hero text-white mb-4">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill comm-badge-pill">
                        <i class="ri-message-3-fill text-warning fs-5"></i> PROJE İLETİŞİM VE İŞBİRLİĞİ MERKEZİ
                    </span>
                    <span class="badge bg-warning text-dark fw-extrabold px-3 py-2 rounded-pill font-monospace">
                        RFI, SUBMITTAL VE TOPLANTI PORTALI
                    </span>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2">İletişim ve İşbirliği Portalı</h2>
                <p class="mb-3 fs-6" style="max-width: 780px; line-height: 1.6; color: #e2e8f0; font-weight: 500;">
                    Proje RFI taleplerini, submittal onaylarını, şantiye toplantı tutanaklarını ve eylem kararlarını tek bir merkezden yönetin.
                </p>
                <div class="d-flex align-items-center gap-2">
                    <i class="ri-building-2-fill text-warning fs-5"></i>
                    <span class="fs-6 fw-bold">Aktif Seçili Proje: <strong id="current-project-name" class="text-warning font-monospace fs-5">{{ $defaultProject->name ?? 'Tümü' }}</strong></span>
                </div>
            </div>

            <div class="col-lg-5 text-lg-end mt-3 mt-lg-0">
                <div class="d-flex flex-column gap-2 float-lg-end" style="max-width: 320px;">
                    <label for="globalSearch" class="form-label text-white-50 small mb-1 fw-bold text-start">
                        <i class="ri-search-line me-1 text-warning"></i>Hızlı Evrak / RFI Arama
                    </label>
                    <input type="text" class="form-control custom-input-comm py-2" id="globalSearch" placeholder="RFI veya Submittal no ara...">

                    <label for="projectSelector" class="form-label text-white-50 small mb-1 fw-bold text-start">
                        <i class="ri-building-line me-1 text-warning"></i>Aktif Proje Seçin
                    </label>
                    <select id="projectSelector" class="form-select custom-input-comm py-2">
                        <option value="" class="text-dark">Tüm Projeler (Genel Görünüm)</option>
                        @foreach($projects as $p)
                            <option value="{{ $p->id }}" class="text-dark"{{ ($defaultProject && $defaultProject->id == $p->id) ? ' selected' : '' }}>
                                {{ $p->name }}
                            </option>
                        @endforeach
                    </select>

                    <button class="btn btn-warning text-dark rounded-pill py-2.5 fw-extrabold shadow-sm mt-1 new-comm-item" data-type="rfi">
                        <i class="ri-add-circle-fill me-1 fs-5"></i>+ Yeni RFI / Submittal
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Statistics Cards -->
    <div class="row mb-5">
        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card stat-card rfi-card h-100 animate__animated animate__fadeInUp" style="animation-delay: 0.1s;">
                <div class="card-body text-center p-4 position-relative">
                    <div class="trend-indicator trend-up">
                        <i class="fas fa-arrow-up"></i>
                    </div>
                    <div class="stat-icon bg-info-subtle text-info">
                        <i class="fas fa-question-circle fa-2x"></i>
                    </div>
                    <h2 class="mb-2 fw-bold text-info">{{ $assignedRfis }}</h2>
                    <p class="text-muted mb-3 fw-semibold">Bana Atanan RFI</p>

                    <!-- Mini sparkline -->
                    <div class="mini-sparkline"></div>

                    <!-- Circular Progress with enhanced styling -->
                    <div class="d-flex justify-content-center mb-3 position-relative">
                        <svg class="progress-ring" width="70" height="70">
                            <circle class="progress-ring-bg" stroke="rgba(255,255,255,0.2)" cx="35" cy="35" r="30" fill="none" stroke-width="4"></circle>
                            <circle class="progress-ring-circle" stroke="#0dcaf0"
                                    cx="35" cy="35" r="30"
                                    stroke-dasharray="{{ $totalRfis ? (($assignedRfis / max(1,$totalRfis)) * 188.4) : 0 }} 188.4"
                                    fill="none" stroke-width="4" stroke-linecap="round">
                            </circle>
                        </svg>
                        <div class="position-absolute top-50 start-50 translate-middle">
                            <small class="fw-bold text-info">{{ $totalRfis ? round(($assignedRfis / max(1,$totalRfis)) * 100) : 0 }}%</small>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">Toplam: {{ $totalRfis }}</small>
                        <small class="badge bg-info-subtle text-info">{{ $totalRfis ? round(($assignedRfis / max(1,$totalRfis)) * 100) : 0 }}%</small>
                    </div>

                    <!-- Additional info -->
                    <div class="mt-2">
                        <small class="text-muted d-block">
                            <i class="fas fa-clock me-1"></i>Son güncelleme: {{ now()->format('H:i') }}
                        </small>
                    </div>

                    @if($assignedRfis > 0)
                        <div class="notification-badge pulse-animation">{{ $assignedRfis }}</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card stat-card submittal-card h-100 animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
                <div class="card-body text-center p-4 position-relative">
                    <div class="trend-indicator trend-neutral">
                        <i class="fas fa-minus"></i>
                    </div>
                    <div class="stat-icon bg-warning-subtle text-warning">
                        <i class="fas fa-file-signature fa-2x"></i>
                    </div>
                    <h2 class="mb-2 fw-bold text-warning">{{ $assignedSubmittals }}</h2>
                    <p class="text-muted mb-3 fw-semibold">Onay Bekleyen Submittal</p>

                    <!-- Mini sparkline -->
                    <div class="mini-sparkline"></div>

                    <!-- Circular Progress with enhanced styling -->
                    <div class="d-flex justify-content-center mb-3 position-relative">
                        <svg class="progress-ring" width="70" height="70">
                            <circle class="progress-ring-bg" stroke="rgba(255,255,255,0.2)" cx="35" cy="35" r="30" fill="none" stroke-width="4"></circle>
                            <circle class="progress-ring-circle" stroke="#fd7e14"
                                    cx="35" cy="35" r="30"
                                    stroke-dasharray="{{ $totalSubmittals ? (($assignedSubmittals / max(1,$totalSubmittals)) * 188.4) : 0 }} 188.4"
                                    fill="none" stroke-width="4" stroke-linecap="round">
                            </circle>
                        </svg>
                        <div class="position-absolute top-50 start-50 translate-middle">
                            <small class="fw-bold text-warning">{{ $totalSubmittals ? round(($assignedSubmittals / max(1,$totalSubmittals)) * 100) : 0 }}%</small>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">Toplam: {{ $totalSubmittals }}</small>
                        <small class="badge bg-warning-subtle text-warning">{{ $totalSubmittals ? round(($assignedSubmittals / max(1,$totalSubmittals)) * 100) : 0 }}%</small>
                    </div>

                    <!-- Additional info -->
                    <div class="mt-2">
                        <small class="text-muted d-block">
                            <i class="fas fa-clock me-1"></i>Son güncelleme: {{ now()->format('H:i') }}
                        </small>
                    </div>

                    @if($assignedSubmittals > 0)
                        <div class="notification-badge pulse-animation">{{ $assignedSubmittals }}</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card stat-card action-card h-100 animate__animated animate__fadeInUp" style="animation-delay: 0.3s;">
                <div class="card-body text-center p-4 position-relative">
                    <div class="trend-indicator trend-up">
                        <i class="fas fa-arrow-up"></i>
                    </div>
                    <div class="stat-icon bg-success-subtle text-success">
                        <i class="fas fa-tasks fa-2x"></i>
                    </div>
                    <h2 class="mb-2 fw-bold text-success">{{ $assignedActions }}</h2>
                    <p class="text-muted mb-3 fw-semibold">Atanmış Eylemler</p>

                    <!-- Mini sparkline -->
                    <div class="mini-sparkline"></div>

                    <!-- Circular Progress with enhanced styling -->
                    <div class="d-flex justify-content-center mb-3 position-relative">
                        <svg class="progress-ring" width="70" height="70">
                            <circle class="progress-ring-bg" stroke="rgba(255,255,255,0.2)" cx="35" cy="35" r="30" fill="none" stroke-width="4"></circle>
                            <circle class="progress-ring-circle" stroke="#198754"
                                    cx="35" cy="35" r="30"
                                    stroke-dasharray="{{ $openActions ? (($assignedActions / max(1,$openActions)) * 188.4) : 0 }} 188.4"
                                    fill="none" stroke-width="4" stroke-linecap="round">
                            </circle>
                        </svg>
                        <div class="position-absolute top-50 start-50 translate-middle">
                            <small class="fw-bold text-success">{{ $openActions ? round(($assignedActions / max(1,$openActions)) * 100) : 0 }}%</small>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">Toplam: {{ $openActions }}</small>
                        <small class="badge bg-success-subtle text-success">{{ $openActions ? round(($assignedActions / max(1,$openActions)) * 100) : 0 }}%</small>
                    </div>

                    <!-- Additional info -->
                    <div class="mt-2">
                        <small class="text-muted d-block">
                            <i class="fas fa-clock me-1"></i>Son güncelleme: {{ now()->format('H:i') }}
                        </small>
                    </div>

                    @if($assignedActions > 0)
                        <div class="notification-badge pulse-animation">{{ $assignedActions }}</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
            <div class="card stat-card overdue-card h-100 animate__animated animate__fadeInUp" style="animation-delay: 0.4s;">
                <div class="card-body text-center p-4 position-relative">
                    <div class="trend-indicator trend-down">
                        <i class="fas fa-arrow-down"></i>
                    </div>
                    <div class="stat-icon bg-danger-subtle text-danger">
                        <i class="fas fa-exclamation-triangle fa-2x"></i>
                    </div>
                    <h2 class="mb-2 fw-bold text-danger">{{ $overdue }}</h2>
                    <p class="text-muted mb-3 fw-semibold">Gecikmiş Görevler</p>

                    <!-- Mini sparkline -->
                    <div class="mini-sparkline"></div>

                    <!-- Progress bar for overdue -->
                    <div class="mb-3">
                        <div class="progress" style="height: 8px; background: rgba(255,255,255,0.2);">
                            <div class="progress-bar bg-danger" role="progressbar"
                                 style="width: {{ $openActions ? min(($overdue / max(1,$openActions)) * 100, 100) : 0 }}%; border-radius: 4px;"
                                 aria-valuenow="{{ $overdue }}" aria-valuemin="0" aria-valuemax="{{ $openActions }}"></div>
                        </div>
                        <small class="text-muted d-block text-center mt-1">
                            {{ $openActions ? round(($overdue / max(1,$openActions)) * 100) : 0 }}% gecikme oranı
                        </small>
                    </div>

                    <button class="btn {{ $overdue > 0 ? 'btn-danger' : 'btn-outline-success' }} btn-sm rounded-pill" id="show-overdue">
                        <i class="fas {{ $overdue > 0 ? 'fa-eye' : 'fa-check' }} me-1"></i>
                        {{ $overdue > 0 ? 'Detayları Gör' : 'Hepsi Tamam' }}
                    </button>

                    <!-- Additional info -->
                    <div class="mt-2">
                        <small class="text-muted d-block">
                            <i class="fas fa-clock me-1"></i>Son kontrol: {{ now()->format('H:i') }}
                        </small>
                    </div>

                    @if($overdue > 0)
                        <div class="notification-badge pulse-animation">!</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Charts Section -->
    <div class="row mb-5">
        <div class="col-lg-8 mb-4">
            <div class="card chart-card h-100 animate__animated animate__fadeInLeft">
                <div class="card-header bg-gradient-primary text-white border-0 py-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="mb-1 fw-bold">
                                <i class="fas fa-chart-line me-2"></i>Aylık Aktivite Trendi
                            </h5>
                            <p class="mb-0 opacity-75 small">Son 6 aylık iletişim aktivitesi</p>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-light btn-sm dropdown-toggle shadow-sm" type="button" data-bs-toggle="dropdown" title="Grafik ayarları">
                                <i class="fas fa-cog text-primary"></i>
                            </button>
                            <ul class="dropdown-menu shadow-lg border-0">
                                <li><a class="dropdown-item py-2" href="#" data-period="3">
                                    <i class="fas fa-calendar-alt me-2 text-info"></i>Son 3 Ay
                                </a></li>
                                <li><a class="dropdown-item py-2" href="#" data-period="6">
                                    <i class="fas fa-calendar me-2 text-success"></i>Son 6 Ay
                                </a></li>
                                <li><a class="dropdown-item py-2" href="#" data-period="12">
                                    <i class="fas fa-calendar-check me-2 text-warning"></i>Son 12 Ay
                                </a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div id="activityChart" style="width: 100%; height: 350px; display: flex; align-items: center; justify-content: center; background: #f8f9fa; border-radius: 8px;">
                        <div class="text-center">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Grafik yükleniyor...</span>
                            </div>
                            <p class="mt-2 text-muted small">Grafik yükleniyor...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card chart-card h-100 animate__animated animate__fadeInRight">
                <div class="card-header bg-gradient-info text-white border-0 py-4">
                    <h5 class="mb-1 fw-bold">
                        <i class="fas fa-chart-pie me-2"></i>İletişim Dağılımı
                    </h5>
                    <p class="mb-0 opacity-75 small">Tür bazında dağılım</p>
                </div>
                <div class="card-body p-4">
                    <div id="communicationChart" style="width: 100%; height: 300px; display: flex; align-items: center; justify-content: center; background: #f8f9fa; border-radius: 8px;">
                        <div class="text-center">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Grafik yükleniyor...</span>
                            </div>
                            <p class="mt-2 text-muted small">Grafik yükleniyor...</p>
                        </div>
                    </div>

                    <!-- Quick Stats -->
                    <div class="mt-4">
                        @if(isset($chartData) && count($chartData) > 0)
                            @foreach($chartData as $index => $item)
                                @php
                                    $colors = ['#0dcaf0', '#fd7e14', '#198754', '#6f42c1'];
                                    $color = $colors[$index % count($colors)];
                                @endphp
                                <div class="d-flex align-items-center justify-content-between mb-2 p-2 rounded"
                                     style="background: {{ $color }}15; border-left: 3px solid {{ $color }};">
                                    <span class="fw-semibold">{{ $item['type'] }}</span>
                                    <span class="badge" style="background: {{ $color }};">{{ $item['count'] }}</span>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Metrics -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm animate__animated animate__fadeInUp">
                <div class="card-header bg-white border-0 py-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="mb-0 fw-bold text-dark">
                            <i class="fas fa-tachometer-alt text-primary me-2"></i>Performans Metrikleri
                        </h5>
                        <small class="text-muted">Gerçek zamanlı veriler</small>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-lg-3 col-md-6">
                            <div class="text-center p-3 rounded-3 bg-info-subtle">
                                <div class="h4 mb-1 text-info fw-bold">
                                    {{ $totalRfis > 0 ? round(($totalRfis - $assignedRfis) / $totalRfis * 100, 1) : 100 }}%
                                </div>
                                <p class="mb-0 small text-muted">RFI Tamamlanma Oranı</p>
                                <div class="progress mt-2" style="height: 4px;">
                                    <div class="progress-bar bg-info" style="width: {{ $totalRfis > 0 ? round(($totalRfis - $assignedRfis) / $totalRfis * 100, 1) : 100 }}%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="text-center p-3 rounded-3 bg-warning-subtle">
                                <div class="h4 mb-1 text-warning fw-bold">
                                    {{ $approvedSubmittals ?? 0 }}/{{ $totalSubmittals }}
                                </div>
                                <p class="mb-0 small text-muted">Onaylanmış Submittal</p>
                                <div class="progress mt-2" style="height: 4px;">
                                    <div class="progress-bar bg-warning" style="width: {{ $totalSubmittals > 0 ? round(($approvedSubmittals ?? 0) / $totalSubmittals * 100, 1) : 0 }}%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="text-center p-3 rounded-3 bg-success-subtle">
                                <div class="h4 mb-1 text-success fw-bold">
                                    {{ $openActions > 0 ? round(($openActions - $assignedActions) / $openActions * 100, 1) : 100 }}%
                                </div>
                                <p class="mb-0 small text-muted">Eylem Tamamlanma</p>
                                <div class="progress mt-2" style="height: 4px;">
                                    <div class="progress-bar bg-success" style="width: {{ $openActions > 0 ? round(($openActions - $assignedActions) / $openActions * 100, 1) : 100 }}%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="text-center p-3 rounded-3 {{ $overdue > 0 ? 'bg-danger-subtle' : 'bg-success-subtle' }}">
                                <div class="h4 mb-1 {{ $overdue > 0 ? 'text-danger' : 'text-success' }} fw-bold">
                                    {{ $overdue == 0 ? '✓' : $overdue }}
                                </div>
                                <p class="mb-0 small text-muted">{{ $overdue == 0 ? 'Gecikme Yok' : 'Gecikmiş Görev' }}</p>
                                <div class="progress mt-2" style="height: 4px;">
                                    <div class="progress-bar {{ $overdue > 0 ? 'bg-danger' : 'bg-success' }}" style="width: 100%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Main Content -->
    <div class="row">
        <!-- Activity Timeline -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4 animate__animated animate__fadeInLeft">
                <div class="card-header bg-white border-0 py-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="mb-0 fw-bold text-dark">
                            <i class="fas fa-history text-primary me-2"></i>Son Aktiviteler
                        </h5>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-secondary btn-sm activity-filter active" data-filter="all">
                                Tümü
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm activity-filter" data-filter="rfi">
                                RFI
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm activity-filter" data-filter="submittal">
                                Submittal
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm activity-filter" data-filter="meeting">
                                Toplantı
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="activity-timeline">
                        @if($recentRfis->count() > 0 || $recentSubs->count() > 0 || $recentActions->count() > 0)
                            @php
                                $allActivities = collect();

                                // RFI'ları ekle
                                foreach($recentRfis as $rfi) {
                                    $allActivities->push([
                                        'type' => 'rfi',
                                        'title' => $rfi->rfi_no . ' — ' . $rfi->subject,
                                        'description' => Str::limit($rfi->description, 100),
                                        'user' => $rfi->assigned_to,
                                        'time' => $rfi->created_at,
                                        'status' => $rfi->status ?? 'open',
                                        'id' => $rfi->id
                                    ]);
                                }

                                // Submittal'ları ekle
                                foreach($recentSubs as $sub) {
                                    $allActivities->push([
                                        'type' => 'submittal',
                                        'title' => $sub->submittal_no . ' — ' . Str::limit($sub->description, 80),
                                        'description' => 'İnceleyenler: ' . implode(', ', $sub->reviewers ?: []),
                                        'user' => 'Sistem',
                                        'time' => $sub->created_at,
                                        'status' => $sub->status ?? 'pending',
                                        'id' => $sub->id,
                                        'attachments' => $sub->attachments ?? []
                                    ]);
                                }

                                // Eylemleri ekle
                                foreach($recentActions as $action) {
                                    $allActivities->push([
                                        'type' => 'action',
                                        'title' => $action->title,
                                        'description' => Str::limit($action->description ?? '', 100),
                                        'user' => $action->assigned_to,
                                        'time' => $action->created_at,
                                        'status' => $action->status ?? 'open',
                                        'id' => $action->id,
                                        'due_date' => $action->due_date ?? null
                                    ]);
                                }

                                $allActivities = $allActivities->sortByDesc('time')->take(10);
                            @endphp

                            @foreach($allActivities as $activity)
                                <div class="activity-item {{ $activity['type'] }} p-4 m-3 rounded-3 activity-filter-item" data-type="{{ $activity['type'] }}">
                                    <div class="d-flex align-items-start">
                                        <div class="activity-icon me-3">
                                            @if($activity['type'] == 'rfi')
                                                <div class="rounded-circle bg-info-subtle text-info p-2">
                                                    <i class="fas fa-question-circle"></i>
                                                </div>
                                            @elseif($activity['type'] == 'submittal')
                                                <div class="rounded-circle bg-warning-subtle text-warning p-2">
                                                    <i class="fas fa-file-signature"></i>
                                                </div>
                                            @else
                                                <div class="rounded-circle bg-purple-subtle text-purple p-2">
                                                    <i class="fas fa-tasks"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                                <h6 class="mb-0 fw-semibold">{{ $activity['title'] }}</h6>
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="badge bg-{{ $activity['status'] == 'open' ? 'warning' : ($activity['status'] == 'approved' ? 'success' : 'secondary') }}-subtle text-{{ $activity['status'] == 'open' ? 'warning' : ($activity['status'] == 'approved' ? 'success' : 'secondary') }}">
                                                        {{ ucfirst($activity['status']) }}
                                                    </span>
                                                    <small class="text-muted">{{ $activity['time']->diffForHumans() }}</small>
                                                </div>
                                            </div>
                                            @if($activity['description'])
                                                <p class="text-muted mb-2 small">{{ $activity['description'] }}</p>
                                            @endif
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-user me-1 text-muted"></i>
                                                    <small class="text-muted">{{ $activity['user'] }}</small>
                                                    @if(isset($activity['due_date']) && $activity['due_date'])
                                                        <i class="fas fa-calendar ms-3 me-1 text-muted"></i>
                                                        <small class="text-muted">{{ $activity['due_date']->format('d.m.Y') }}</small>
                                                    @endif
                                                </div>
                                                <div class="d-flex gap-2">
                                                    @if($activity['type'] == 'submittal' && !empty($activity['attachments']))
                                                        <button class="btn btn-outline-secondary btn-sm preview-attachment"
                                                                data-project="{{ $defaultProject->id ?? '' }}"
                                                                data-type="submittal" data-id="{{ $activity['id'] }}"
                                                                data-file="{{ $activity['attachments'][0] }}">
                                                            <i class="fas fa-eye me-1"></i>Önizle
                                                        </button>
                                                    @endif
                                                    <button class="btn btn-outline-primary btn-sm"
                                                            onclick="viewDetails('{{ $activity['type'] }}', {{ $activity['id'] }})">
                                                        <i class="fas fa-arrow-right"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            <div class="text-center p-4">
                                <a href="#" class="btn btn-outline-primary btn-sm load-more-activities">
                                    <i class="fas fa-chevron-down me-1"></i>Daha Fazla Göster
                                </a>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <div class="mb-4">
                                    <i class="fas fa-comments fa-4x text-muted opacity-50"></i>
                                </div>
                                <h5 class="text-muted mb-3">Henüz aktivite bulunmuyor</h5>
                                <p class="text-muted">İlk RFI, submittal veya toplantı kaydınızı oluşturun</p>
                                <button class="btn btn-primary new-comm-item" data-type="rfi">
                                    <i class="fas fa-plus me-1"></i>İlk RFI'ı Oluştur
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Enhanced Sidebar -->
        <div class="col-lg-4">
            <!-- Quick Actions Panel -->
            <div class="card border-0 shadow-sm mb-4 animate__animated animate__fadeInRight" style="border-radius: 18px; overflow: hidden; border: 1.5px solid #cbd5e1 !important;">
                <div class="card-header border-0 py-3.5 px-4 text-white" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); border-bottom: 3px solid #d97706;">
                    <h5 class="mb-0 fw-extrabold text-white d-flex align-items-center gap-2 fs-6">
                        <i class="ri-rocket-fill text-warning fs-5"></i> Hızlı İşlem Paneli
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="d-grid gap-3">
                        <button type="button" class="btn btn-primary btn-lg rounded-pill fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2 py-3" onclick="showCommModal('rfi')" style="background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%); border: none;">
                            <i class="ri-questionnaire-line fs-5"></i> Yeni RFI Oluştur
                        </button>
                        <button type="button" class="btn btn-warning btn-lg text-dark rounded-pill fw-extrabold shadow-sm d-flex align-items-center justify-content-center gap-2 py-3" onclick="showCommModal('submittal')" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border: none;">
                            <i class="ri-file-seal-line fs-5"></i> Yeni Submittal Ekle
                        </button>
                        <button type="button" class="btn btn-success btn-lg rounded-pill fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2 py-3" onclick="showCommModal('meeting')" style="background: linear-gradient(135deg, #16a34a 0%, #15803d 100%); border: none;">
                            <i class="ri-calendar-event-line fs-5"></i> Toplantı Kaydet
                        </button>
                    </div>

                    <hr class="my-4" style="border-color: #cbd5e1;">

                    <div class="row g-2">
                        <div class="col-6">
                            <a href="{{ route('projects.communication.rfis', ['project'=>$defaultProject->id ?? ($projects->first()->id ?? 0)]) }}"
                               class="btn btn-outline-dark btn-sm w-100 fw-bold py-2 rounded-pill d-flex align-items-center justify-content-center gap-1">
                                <i class="ri-list-check-2 text-info"></i> RFI Listesi
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('projects.communication.submittals', ['project'=>$defaultProject->id ?? ($projects->first()->id ?? 0)]) }}"
                               class="btn btn-outline-dark btn-sm w-100 fw-bold py-2 rounded-pill d-flex align-items-center justify-content-center gap-1">
                                <i class="ri-file-list-3-line text-warning"></i> Submittal'lar
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('projects.communication.meetings', ['project'=>$defaultProject->id ?? ($projects->first()->id ?? 0)]) }}"
                               class="btn btn-outline-dark btn-sm w-100 fw-bold py-2 rounded-pill d-flex align-items-center justify-content-center gap-1">
                                <i class="ri-group-line text-success"></i> Toplantılar
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('projects.documents.all') }}"
                               class="btn btn-outline-dark btn-sm w-100 fw-bold py-2 rounded-pill d-flex align-items-center justify-content-center gap-1">
                                <i class="ri-folder-open-line text-primary"></i> Dokümanlar
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Priority Tasks -->
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 18px; border: 1.5px solid #cbd5e1 !important; overflow: hidden;">
                <div class="card-header bg-white border-0 py-3.5 px-4">
                    <h6 class="mb-0 fw-extrabold text-dark d-flex align-items-center gap-2">
                        <i class="ri-star-fill text-warning fs-5"></i> Öncelikli Görevler
                    </h6>
                </div>
                <div class="card-body px-4 pb-4 pt-0">
                    @if($recentActions->count() > 0)
                        @foreach($recentActions->take(5) as $a)
                            @php
                                $isOverdue = $a->due_date && $a->due_date->isPast();
                                $isDueSoon = $a->due_date && $a->due_date->diffInDays(today()) <= 3;
                            @endphp
                            <div class="timeline-item mb-3 p-3 rounded-3 {{ $isOverdue ? 'bg-danger-subtle border-danger' : ($isDueSoon ? 'bg-warning-subtle border-warning' : 'bg-light') }}" style="border: 1px solid #cbd5e1;">
                                <div class="d-flex align-items-start">
                                    <div class="me-3">
                                        <div class="rounded-circle {{ $isOverdue ? 'bg-danger text-white' : ($isDueSoon ? 'bg-warning text-dark' : 'bg-success text-white') }} p-2 d-flex align-items-center justify-content-center" style="width:32px; height:32px;">
                                            <i class="ri-{{ $isOverdue ? 'error-warning' : ($isDueSoon ? 'time' : 'check') }}-line"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 fw-bold text-dark fs-6">{{ Str::limit($a->title, 40) }}</h6>
                                        <p class="mb-2 small text-secondary fw-semibold">{{ Str::limit($a->description ?? '', 60) }}</p>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <small class="text-muted fw-bold">
                                                <i class="ri-user-line me-1 text-primary"></i>{{ Str::limit($a->assigned_to ?? 'Atanmadı', 15) }}
                                            </small>
                                            @if($a->due_date)
                                                <small class="{{ $isOverdue ? 'text-danger fw-extrabold' : ($isDueSoon ? 'text-warning fw-bold' : 'text-muted') }}">
                                                    <i class="ri-calendar-line me-1"></i>{{ $a->due_date->format('d.m.Y') }}
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="ri-checkbox-circle-fill fs-1 text-success mb-2"></i>
                            <h6 class="text-dark fw-bold mb-1">Tüm görevler tamamlandı!</h6>
                            <p class="text-muted small mb-0">Atanmış açık eylem kalmadı.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Team Activity -->
            <div class="card border-0 shadow-sm" style="border-radius: 18px; border: 1.5px solid #cbd5e1 !important; overflow: hidden;">
                <div class="card-header bg-white border-0 py-3.5 px-4">
                    <h6 class="mb-0 fw-extrabold text-dark d-flex align-items-center gap-2">
                        <i class="ri-team-fill text-primary fs-5"></i> Takım Aktivitesi
                    </h6>
                </div>
                <div class="card-body px-4 pb-4 pt-0">
                    <div class="mb-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="small fw-extrabold text-dark">Bu Hafta İşlem Hacmi</span>
                            <span class="badge bg-primary px-2.5 py-1.5 font-monospace fw-bold fs-6">{{ ($recentRfis->count() + $recentSubs->count() + $recentActions->count()) }}</span>
                        </div>
                        <div class="progress" style="height: 10px; border-radius: 6px; background-color: #e2e8f0;">
                            <div class="progress-bar bg-info" style="width: 40%" title="RFI"></div>
                            <div class="progress-bar bg-warning" style="width: 35%" title="Submittal"></div>
                            <div class="progress-bar bg-success" style="width: 25%" title="Toplantı"></div>
                        </div>
                        <div class="d-flex justify-content-between mt-2 font-monospace fw-bold">
                            <small class="text-info"><i class="ri-price-tag-3-fill me-1"></i>RFI: {{ $recentRfis->count() }}</small>
                            <small class="text-warning"><i class="ri-file-seal-fill me-1"></i>Submittal: {{ $recentSubs->count() }}</small>
                            <small class="text-success"><i class="ri-checkbox-circle-fill me-1"></i>Eylem: {{ $recentActions->count() }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- NEW COMMUNICATION MODAL OVERHAUL -->
<div class="modal fade" id="newCommModal" tabindex="-1" aria-labelledby="newCommModalLabel" aria-hidden="true" style="z-index: 1055;">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-2xl" style="border-radius: 20px; overflow: hidden; border: 2px solid #cbd5e1 !important;">
            <div class="modal-header text-white px-4 py-3.5" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); border-bottom: 3px solid #d97706;">
                <h5 class="modal-title fw-extrabold text-white d-flex align-items-center gap-2" id="newCommModalLabel">
                    <i class="ri-add-circle-fill text-warning fs-4"></i> Yeni İletişim & Evrak Oluştur
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Kapat"></button>
            </div>

            <div class="modal-body p-4" style="background-color: #ffffff;">
                <!-- Navigation Tabs -->
                <ul class="nav nav-pills nav-justified mb-4 p-1.5 rounded-3 bg-light border" id="newCommTabs" role="tablist" style="border-color: #cbd5e1 !important;">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active fw-extrabold py-2.5 rounded-2 text-dark" id="rfi-tab" data-bs-toggle="pill" data-bs-target="#tab-rfi" type="button" role="tab">
                            <i class="ri-questionnaire-fill text-info me-1 fs-5"></i> RFI Talebi
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-extrabold py-2.5 rounded-2 text-dark" id="sub-tab" data-bs-toggle="pill" data-bs-target="#tab-sub" type="button" role="tab">
                            <i class="ri-file-seal-fill text-warning me-1 fs-5"></i> Submittal Onayı
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-extrabold py-2.5 rounded-2 text-dark" id="meet-tab" data-bs-toggle="pill" data-bs-target="#tab-meet" type="button" role="tab">
                            <i class="ri-calendar-event-fill text-success me-1 fs-5"></i> Toplantı Kaydı
                        </button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content" id="newCommTabContent">
                    <!-- RFI TAB -->
                    <div class="tab-pane fade show active" id="tab-rfi" role="tabpanel">
                        <form id="rfiForm" method="POST" action="{{ route('projects.communication.rfis.store', ['project' => $defaultProject->id ?? ($projects->first()->id ?? 1)]) }}" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-extrabold text-dark">
                                    <i class="ri-building-line text-warning me-1"></i>İlgili Proje Seçin <span class="text-danger">*</span>
                                </label>
                                <select class="form-select custom-input-comm modal-project-select" onchange="updateModalFormActions(this.value)">
                                    @foreach($projects as $p)
                                        <option value="{{ $p->id }}" {{ ($defaultProject && $defaultProject->id == $p->id) ? 'selected' : '' }}>
                                            {{ $p->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="rfi-subject" class="form-label fw-extrabold text-dark">
                                    <i class="ri-heading me-1 text-primary"></i>RFI Konusu / Başlığı <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="subject" id="rfi-subject" class="form-control custom-input-comm py-2.5" placeholder="Örn: Statik Proje Kiriş Detayı Bilgi Talebi" required>
                            </div>

                            <div class="mb-3">
                                <label for="rfi-description" class="form-label fw-extrabold text-dark">
                                    <i class="ri-file-text-line me-1 text-primary"></i>Detaylı Açıklama & Soru
                                </label>
                                <textarea name="description" id="rfi-description" class="form-control custom-input-comm" rows="3" placeholder="Yanıtlanmasını istediğiniz teknik detayları açıklayın..."></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="rfi-assigned" class="form-label fw-extrabold text-dark">
                                        <i class="ri-user-shared-line me-1 text-primary"></i>Atanacak Sorumlu (Email)
                                    </label>
                                    <input type="email" name="assigned_to" id="rfi-assigned" class="form-control custom-input-comm py-2.5" placeholder="mimar@proje.com">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="rfi-due-date" class="form-label fw-extrabold text-dark">
                                        <i class="ri-calendar-check-line me-1 text-primary"></i>Hedef Yanıt Tarihi
                                    </label>
                                    <input type="date" name="due_date" id="rfi-due-date" class="form-control custom-input-comm py-2.5">
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="rfi-attachments" class="form-label fw-extrabold text-dark">
                                    <i class="ri-attachment-line me-1 text-primary"></i>Ek Dosyalar (PDF, DWG, Görsel)
                                </label>
                                <input type="file" name="attachments[]" id="rfi-attachments" multiple class="form-control custom-input-comm">
                            </div>

                            <div class="d-flex justify-content-end gap-2 pt-2 border-top">
                                <button type="button" class="btn btn-outline-secondary rounded-pill px-4 fw-bold" data-bs-dismiss="modal">İptal</button>
                                <button type="submit" class="btn btn-primary rounded-pill px-4 fw-extrabold shadow-sm" style="background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%); border:none;">
                                    <i class="ri-send-plane-fill me-1"></i> RFI Talebini Kaydet
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- SUBMITTAL TAB -->
                    <div class="tab-pane fade" id="tab-sub" role="tabpanel">
                        <form id="subForm" method="POST" action="{{ route('projects.communication.submittals.store', ['project' => $defaultProject->id ?? ($projects->first()->id ?? 1)]) }}" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-extrabold text-dark">
                                    <i class="ri-building-line text-warning me-1"></i>İlgili Proje Seçin <span class="text-danger">*</span>
                                </label>
                                <select class="form-select custom-input-comm modal-project-select" onchange="updateModalFormActions(this.value)">
                                    @foreach($projects as $p)
                                        <option value="{{ $p->id }}" {{ ($defaultProject && $defaultProject->id == $p->id) ? 'selected' : '' }}>
                                            {{ $p->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="sub-description" class="form-label fw-extrabold text-dark">
                                    <i class="ri-file-seal-line me-1 text-warning"></i>Submittal Tanımı / Başlığı <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="description" id="sub-description" class="form-control custom-input-comm py-2.5" placeholder="Örn: Seramik Numune Onayı ve Teknik Föyü" required>
                            </div>

                            <div class="mb-3">
                                <label for="sub-reviewers" class="form-label fw-extrabold text-dark">
                                    <i class="ri-user-follow-line me-1 text-warning"></i>İnceleyen / Onaylayan Yetkililer
                                </label>
                                <input type="text" name="reviewers" id="sub-reviewers" class="form-control custom-input-comm py-2.5" placeholder="kontrol@firma.com, muhendis@kontrolluk.com">
                                <div class="form-text text-muted small">Virgülle ayırarak birden fazla onaycı yazabilirsiniz.</div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6 mb-3">
                                    <label for="sub-due-date" class="form-label fw-extrabold text-dark">
                                        <i class="ri-calendar-line me-1 text-warning"></i>Son Onay Tarihi
                                    </label>
                                    <input type="date" name="due_date" id="sub-due-date" class="form-control custom-input-comm py-2.5">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="sub-attachments" class="form-label fw-extrabold text-dark">
                                        <i class="ri-file-zip-line me-1 text-warning"></i>Teknik Doküman & Numuneler
                                    </label>
                                    <input type="file" name="attachments[]" id="sub-attachments" multiple class="form-control custom-input-comm">
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2 pt-2 border-top">
                                <button type="button" class="btn btn-outline-secondary rounded-pill px-4 fw-bold" data-bs-dismiss="modal">İptal</button>
                                <button type="submit" class="btn btn-warning text-dark rounded-pill px-4 fw-extrabold shadow-sm" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border:none;">
                                    <i class="ri-check-double-line me-1"></i> Submittal Gönder
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- MEETING TAB -->
                    <div class="tab-pane fade" id="tab-meet" role="tabpanel">
                        <form id="meetForm" method="POST" action="{{ route('projects.communication.meetings.store', ['project' => $defaultProject->id ?? ($projects->first()->id ?? 1)]) }}" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-extrabold text-dark">
                                    <i class="ri-building-line text-warning me-1"></i>İlgili Proje Seçin <span class="text-danger">*</span>
                                </label>
                                <select class="form-select custom-input-comm modal-project-select" onchange="updateModalFormActions(this.value)">
                                    @foreach($projects as $p)
                                        <option value="{{ $p->id }}" {{ ($defaultProject && $defaultProject->id == $p->id) ? 'selected' : '' }}>
                                            {{ $p->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="meet-title" class="form-label fw-extrabold text-dark">
                                    <i class="ri-calendar-event-line me-1 text-success"></i>Toplantı Başlığı <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="title" id="meet-title" class="form-control custom-input-comm py-2.5" placeholder="Örn: 12. Hafta Şantiye Koordinasyon Toplantısı" required>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="meet-date" class="form-label fw-extrabold text-dark">
                                        <i class="ri-time-line me-1 text-success"></i>Toplantı Tarihi
                                    </label>
                                    <input type="date" name="meeting_date" id="meet-date" class="form-control custom-input-comm py-2.5" value="{{ date('Y-m-d') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="meet-participants" class="form-label fw-extrabold text-dark">
                                        <i class="ri-group-line me-1 text-success"></i>Katılımcı Listesi
                                    </label>
                                    <input type="text" name="participants" id="meet-participants" class="form-control custom-input-comm py-2.5" placeholder="ahmet@site.com, mehmet@kontrolluk.com">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="meet-notes" class="form-label fw-extrabold text-dark">
                                    <i class="ri-draft-line me-1 text-success"></i>Alınan Kararlar & Notlar
                                </label>
                                <textarea name="notes" id="meet-notes" class="form-control custom-input-comm" rows="3" placeholder="Toplantıda alınan önemli kararları ve notları girin..."></textarea>
                            </div>

                            <div class="mb-4">
                                <label for="meet-attachments" class="form-label fw-extrabold text-dark">
                                    <i class="ri-attachment-2 me-1 text-success"></i>Toplantı Tutanağı & İmzalı Ekler
                                </label>
                                <input type="file" name="attachments[]" id="meet-attachments" multiple class="form-control custom-input-comm">
                            </div>

                            <div class="d-flex justify-content-end gap-2 pt-2 border-top">
                                <button type="button" class="btn btn-outline-secondary rounded-pill px-4 fw-bold" data-bs-dismiss="modal">İptal</button>
                                <button type="submit" class="btn btn-success rounded-pill px-4 fw-extrabold shadow-sm" style="background: linear-gradient(135deg, #16a34a 0%, #15803d 100%); border:none;">
                                    <i class="ri-save-3-fill me-1"></i> Toplantı Kaydını Sakla
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/apexcharts.min.js') }}"></script>
<script>
// Dynamic Modal Form Action Updater
function updateModalFormActions(projectId) {
    if (!projectId) return;
    document.querySelectorAll('.modal-project-select').forEach(sel => sel.value = projectId);

    const rfiForm = document.getElementById('rfiForm');
    const subForm = document.getElementById('subForm');
    const meetForm = document.getElementById('meetForm');

    if (rfiForm) rfiForm.action = `/projects/${projectId}/communication/rfis`;
    if (subForm) subForm.action = `/projects/${projectId}/communication/submittals`;
    if (meetForm) meetForm.action = `/projects/${projectId}/communication/meetings`;
}

// Global modal helper avoiding duplicate backdrops
function showCommModal(type) {
    const modalEl = document.getElementById('newCommModal');
    if (!modalEl) return;

    // Move to body if needed
    if (modalEl.parentElement !== document.body) {
        document.body.appendChild(modalEl);
    }

    // Tab activation
    const tabMap = { 'rfi': 'rfi-tab', 'submittal': 'sub-tab', 'meeting': 'meet-tab' };
    const tabId = tabMap[type] || 'rfi-tab';
    const tabEl = document.getElementById(tabId);
    if (tabEl && window.bootstrap) {
        const bsTab = bootstrap.Tab.getOrCreateInstance(tabEl);
        bsTab.show();
    }

    // Sync current active project from top selector if available
    const topProjSel = document.getElementById('projectSelector');
    if (topProjSel && topProjSel.value) {
        updateModalFormActions(topProjSel.value);
    }

    if (window.bootstrap) {
        const bsModal = bootstrap.Modal.getOrCreateInstance(modalEl);
        bsModal.show();
    }
}

// Chart Fallback & Dashboard Logic
document.addEventListener('DOMContentLoaded', function() {
    // Project selector listener
    const topProjSel = document.getElementById('projectSelector');
    if (topProjSel) {
        topProjSel.addEventListener('change', function() {
            updateModalFormActions(this.value);
            const url = new URL(window.location.href);
            if (this.value) {
                url.searchParams.set('project', this.value);
            } else {
                url.searchParams.delete('project');
            }
            window.location.href = url.toString();
        });
    }

    // Global search listener
    const globalSearch = document.getElementById('globalSearch');
    if (globalSearch) {
        globalSearch.addEventListener('input', function(e) {
            const term = e.target.value.toLowerCase();
            document.querySelectorAll('.activity-filter-item').forEach(item => {
                const text = item.textContent.toLowerCase();
                item.style.display = text.includes(term) ? 'block' : 'none';
            });
        });
    }

    // Activity Filter Tabs
    document.querySelectorAll('.activity-filter').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const filter = this.dataset.filter;
            document.querySelectorAll('.activity-filter').forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            document.querySelectorAll('.activity-filter-item').forEach(item => {
                if (filter === 'all' || item.dataset.type === filter) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });

    // ApexCharts Render
    if (typeof ApexCharts !== 'undefined') {
        const commData = @json($chartData ?? []);
        const commChartEl = document.getElementById('communicationChart');
        if (commChartEl && commData.length > 0) {
            commChartEl.innerHTML = '';
            new ApexCharts(commChartEl, {
                series: commData.map(i => i.count),
                chart: { type: 'donut', height: 300 },
                labels: commData.map(i => i.type),
                colors: ['#0284c7', '#f59e0b', '#16a34a', '#9333ea'],
                legend: { show: false }
            }).render();
        }

        const actData = @json($monthlyActivity ?? []);
        const actChartEl = document.getElementById('activityChart');
        if (actChartEl && actData.length > 0) {
            actChartEl.innerHTML = '';
            new ApexCharts(actChartEl, {
                series: [{ name: 'Aktivite', data: actData.map(i => i.count) }],
                chart: { type: 'area', height: 320, toolbar: { show: false } },
                xaxis: { categories: actData.map(i => i.month) },
                colors: ['#0284c7'],
                stroke: { curve: 'smooth', width: 3 }
            }).render();
        }
    }
});
</script>
@endpush
@endsection
