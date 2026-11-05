@extends('layouts.master')

@section('title', 'Sistem Durumu & Sunucu Performans Portalı')
@section('topbar-title', 'Yönetim - Sistem Sağlığı')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css" rel="stylesheet">
<style>
    /* ROYAL DARK SLATE & SAPPHIRE/EMERALD GLASSMORPHIC THEME */
    .sys-adm-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #2563eb 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(37, 99, 235, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.35) !important;
        padding: 32px !important;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .sys-adm-hero-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #2563eb, #10b981, #f59e0b, #2563eb);
        background-size: 300% 100%;
        animation: sysGlow 4s linear infinite;
    }

    @keyframes sysGlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    /* Pulse Live Status Dot */
    .pulse-dot-green {
        width: 12px;
        height: 12px;
        background-color: #10b981;
        border-radius: 50%;
        display: inline-block;
        box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
        animation: pulse-green 2s infinite;
    }

    @keyframes pulse-green {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(16, 185, 129, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
    }

    /* KPI Spec Cards */
    .sys-adm-card {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 22px 24px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04) !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .sys-adm-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08) !important;
    }

    .sys-adm-stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    /* Glassmorphic Container Cards */
    .sys-adm-glass-card {
        border-radius: 20px !important;
        padding: 2rem !important;
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04) !important;
        margin-bottom: 1.5rem;
    }
</style>
@endpush

@section('content')

<div class="container-fluid px-4 py-3">

    <!-- HERO HEADER BANNER -->
    <div class="sys-adm-hero-banner text-white mb-4">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
                    <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-3 py-1.5 fw-bold d-inline-flex align-items-center">
                        <span class="pulse-dot-green me-2"></span>Sunucu & Veritabanı Canlı Monitoring
                    </span>
                    <span class="badge bg-warning text-dark rounded-pill px-3 py-2 font-monospace fw-extrabold" style="font-size: 11px;">
                        PHP {{ $systemInfo['php_version'] ?? '8.3' }} | LARAVEL {{ $systemInfo['laravel_version'] ?? '11.x' }}
                    </span>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2" style="text-shadow: 0 2px 4px rgba(0,0,0,0.4);">Sistem Sağlığı & Sunucu Performans Portalı</h2>
                <p class="mb-0 fs-6" style="color: #bfdbfe; font-weight: 500; max-width: 820px; line-height: 1.6;">
                    Sunucu kaynak kullanımı, bellek tüketimi, veritabanı tablo boyutu, sistem önbelleği ve log metriklerini canlı izleyin.
                </p>
            </div>

            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <div class="d-flex justify-content-lg-end gap-2 flex-wrap">
                    <button onclick="window.location.reload();" class="btn btn-light rounded-pill px-4 py-2.5 fw-extrabold text-dark shadow-sm d-inline-flex align-items-center gap-2">
                        <i class="ri-refresh-line fs-5 text-primary"></i>Metrikleri Yenile
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- 4 TOP KPI SPEC CARDS -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card sys-adm-card h-100 border-start border-success border-4 animate__animated animate__fadeInUp">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-extrabold text-uppercase d-block mb-1 fs-7" style="letter-spacing: 0.5px;">SİSTEM SAĞLIK SKORU</span>
                        <h2 class="fw-extrabold mb-0 text-success font-monospace">%{{ $maintenanceInfo['system_health'] ?? 98 }} <small class="fs-6 text-muted">Sağlıklı</small></h2>
                    </div>
                    <div class="sys-adm-stat-icon bg-success-subtle text-success" style="background: rgba(16, 185, 129, 0.12);">
                        <i class="ri-heart-pulse-line fs-2" style="color: #047857 !important;"></i>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                    <small class="text-muted fw-bold"><i class="ri-checkbox-circle-line me-1"></i>Sistem Stabil</small>
                    <span class="badge bg-success-subtle text-success fw-bold">Optimum</span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card sys-adm-card h-100 border-start border-primary border-4 animate__animated animate__fadeInUp" style="animation-delay: 0.1s;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-extrabold text-uppercase d-block mb-1 fs-7" style="letter-spacing: 0.5px;">RAM BELLEK KULLANIMI</span>
                        <h2 class="fw-extrabold mb-0 text-primary font-monospace">
                            {{ round(($performanceMetrics['memory_usage']['current'] ?? 0) / 1024 / 1024, 1) }} <small class="fs-6 text-muted">MB</small>
                        </h2>
                    </div>
                    <div class="sys-adm-stat-icon bg-primary-subtle text-primary" style="background: rgba(37, 99, 235, 0.12);">
                        <i class="ri-cpu-line fs-2" style="color: #2563eb !important;"></i>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                    <small class="text-muted fw-bold"><i class="ri-speed-line me-1"></i>Limit: {{ $systemInfo['memory_limit'] ?? '512M' }}</small>
                    <span class="badge bg-primary-subtle text-primary fw-bold">Düşük Yük</span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card sys-adm-card h-100 border-start border-info border-4 animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-extrabold text-uppercase d-block mb-1 fs-7" style="letter-spacing: 0.5px;">VERİTABANI BOYUTU</span>
                        <h2 class="fw-extrabold mb-0 text-info font-monospace">
                            {{ round(($databaseInfo['database_size'] ?? 0) / 1024 / 1024, 2) }} <small class="fs-6 text-muted">MB</small>
                        </h2>
                    </div>
                    <div class="sys-adm-stat-icon bg-info-subtle text-info" style="background: rgba(2, 132, 199, 0.12);">
                        <i class="ri-database-2-line fs-2" style="color: #0284c7 !important;"></i>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                    <small class="text-muted fw-bold"><i class="ri-table-line me-1"></i>{{ number_format($databaseInfo['total_tables'] ?? 0) }} Tablo</small>
                    <span class="badge bg-info-subtle text-info fw-bold">{{ number_format($databaseInfo['total_records'] ?? 0) }} Kayıt</span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card sys-adm-card h-100 border-start border-danger border-4 animate__animated animate__fadeInUp" style="animation-delay: 0.3s;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-extrabold text-uppercase d-block mb-1 fs-7" style="letter-spacing: 0.5px;">SON 24H HATA SAYISI</span>
                        <h2 class="fw-extrabold mb-0 text-danger font-monospace">{{ $logAnalysis['error_count_24h'] ?? 0 }} <small class="fs-6 text-muted">Hata</small></h2>
                    </div>
                    <div class="sys-adm-stat-icon bg-danger-subtle text-danger" style="background: rgba(239, 68, 68, 0.12);">
                        <i class="ri-bug-line fs-2" style="color: #ef4444 !important;"></i>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                    <small class="text-muted fw-bold"><i class="ri-file-shield-line me-1"></i>Laravel Log Analizi</small>
                    <span class="badge bg-danger-subtle text-danger fw-bold">Temiz Log</span>
                </div>
            </div>
        </div>
    </div>

    <!-- DETAILED GRID CARDS -->
    <div class="row g-4 mb-4">
        <!-- SERVER & ENVIRONMENT INFO -->
        <div class="col-lg-6">
            <div class="sys-adm-glass-card h-100">
                <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-3">
                    <h5 class="fw-extrabold text-dark mb-0 d-flex align-items-center gap-2">
                        <i class="ri-server-line text-primary fs-4" style="color: #2563eb !important;"></i> Sunucu & Çalışma Zamanı Parametreleri
                    </h5>
                    <span class="badge bg-primary bg-opacity-10 text-primary font-monospace px-3 py-1 rounded-pill fw-bold">PHP CONFIG</span>
                </div>

                <div class="list-group list-group-flush">
                    <div class="list-group-item bg-transparent d-flex justify-content-between align-items-center py-3">
                        <span class="text-muted fw-bold">Çalışma Ortamı (ENV)</span>
                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-30 rounded-pill px-3 py-1 font-monospace fw-bold">
                            {{ strtoupper($systemInfo['environment'] ?? 'production') }}
                        </span>
                    </div>

                    <div class="list-group-item bg-transparent d-flex justify-content-between align-items-center py-3">
                        <span class="text-muted fw-bold">Hata Ayıklama Modu (Debug)</span>
                        <span class="badge bg-{{ ($systemInfo['debug_mode'] ?? false) ? 'warning' : 'success' }} bg-opacity-10 text-{{ ($systemInfo['debug_mode'] ?? false) ? 'warning' : 'success' }} border border-{{ ($systemInfo['debug_mode'] ?? false) ? 'warning' : 'success' }} border-opacity-30 rounded-pill px-3 py-1 fw-bold">
                            {{ ($systemInfo['debug_mode'] ?? false) ? 'AÇIK (Debug ON)' : 'KAPALI (Production Safe)' }}
                        </span>
                    </div>

                    <div class="list-group-item bg-transparent d-flex justify-content-between align-items-center py-3">
                        <span class="text-muted fw-bold">PHP Memory Limit</span>
                        <strong class="font-monospace text-dark fs-6">{{ $systemInfo['memory_limit'] ?? '512M' }}</strong>
                    </div>

                    <div class="list-group-item bg-transparent d-flex justify-content-between align-items-center py-3">
                        <span class="text-muted fw-bold">Maksimum Dosya Yükleme (Upload)</span>
                        <strong class="font-monospace text-dark fs-6">{{ $systemInfo['upload_max_filesize'] ?? '64M' }}</strong>
                    </div>

                    <div class="list-group-item bg-transparent d-flex justify-content-between align-items-center py-3">
                        <span class="text-muted fw-bold">Zaman Dilimi (Timezone)</span>
                        <strong class="font-monospace text-dark fs-6">{{ $systemInfo['timezone'] ?? 'Europe/Istanbul' }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- DATABASE STATUS -->
        <div class="col-lg-6">
            <div class="sys-adm-glass-card h-100">
                <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-3">
                    <h5 class="fw-extrabold text-dark mb-0 d-flex align-items-center gap-2">
                        <i class="ri-database-2-line text-info fs-4" style="color: #0284c7 !important;"></i> Veritabanı Durumu & Tablo İstatistikleri
                    </h5>
                    <span class="badge bg-info bg-opacity-10 text-info font-monospace px-3 py-1 rounded-pill fw-bold">SQL ENGINE</span>
                </div>

                <div class="list-group list-group-flush">
                    <div class="list-group-item bg-transparent d-flex justify-content-between align-items-center py-3">
                        <span class="text-muted fw-bold">Veritabanı Sürücüsü (Driver)</span>
                        <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-30 rounded-pill px-3 py-1 font-monospace fw-bold text-uppercase">
                            {{ $databaseInfo['driver'] ?? 'MySQL' }}
                        </span>
                    </div>

                    <div class="list-group-item bg-transparent d-flex justify-content-between align-items-center py-3">
                        <span class="text-muted fw-bold">Veritabanı Adı</span>
                        <strong class="font-monospace text-dark fs-6">{{ $databaseInfo['database_name'] ?? 'pergel_db' }}</strong>
                    </div>

                    <div class="list-group-item bg-transparent d-flex justify-content-between align-items-center py-3">
                        <span class="text-muted fw-bold">Toplam Tablo Sayısı</span>
                        <strong class="text-dark font-monospace fs-6">{{ number_format($databaseInfo['total_tables'] ?? 0) }} Tablo</strong>
                    </div>

                    <div class="list-group-item bg-transparent d-flex justify-content-between align-items-center py-3">
                        <span class="text-muted fw-bold">Toplam Veri Kayıt Sayısı</span>
                        <strong class="text-dark font-monospace fs-6">{{ number_format($databaseInfo['total_records'] ?? 0) }} Kayıt</strong>
                    </div>

                    <div class="list-group-item bg-transparent d-flex justify-content-between align-items-center py-3">
                        <span class="text-muted fw-bold">Bağlantı Durumu</span>
                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-30 rounded-pill px-3 py-1 fw-bold">
                            <i class="ri-checkbox-circle-fill me-1"></i>{{ $databaseInfo['connection_status'] ?? 'Connected' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CACHE & MAINTENANCE PANEL -->
    <div class="card border rounded-4 p-4 text-white shadow-lg" style="background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%); border-color: #cbd5e1 !important;">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h4 class="fw-extrabold text-white mb-2"><i class="ri-shield-flash-line text-warning me-2"></i>Sistem Önbelleği & Bakım Araçları</h4>
                <p class="text-white-50 mb-0">Blade şablonları, rota önbelleği ve uygulama verilerini optimize edin.</p>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <button onclick="window.location.reload();" class="btn btn-warning text-dark rounded-pill px-4 py-2.5 fw-extrabold shadow-sm">
                    <i class="ri-magic-line me-1"></i> Sistem Sağlığını Yeniden Taraması Yap
                </button>
            </div>
        </div>
    </div>

</div>

@endsection