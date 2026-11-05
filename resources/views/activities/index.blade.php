@extends('layouts.master')

@section('title', 'Şantiye Aktivite & Canlı İşlem Akışı')
@section('topbar-title', 'Yönetim - Aktivite Takibi')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css" rel="stylesheet">
<style>
    /* ROYAL DARK SLATE & AMBER GLASSMORPHIC THEME */
    .activities-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #78350f 50%, #d97706 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.35) !important;
        padding: 32px !important;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .activities-hero-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #f59e0b, #10b981, #3b82f6, #f59e0b);
        background-size: 300% 100%;
        animation: actGlow 4s linear infinite;
    }

    @keyframes actGlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .act-badge-pill {
        background: rgba(245, 158, 11, 0.25) !important;
        border: 1px solid rgba(245, 158, 11, 0.5) !important;
        color: #fef08a !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    /* KPI Summary Cards */
    .act-kpi-card {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 22px 24px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04) !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .act-kpi-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08) !important;
    }

    .act-stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    /* Timeline Cards */
    .act-item-card {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-left: 4px solid #d97706 !important;
        border-radius: 14px !important;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.03) !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .act-item-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08) !important;
    }

    .act-item-card.success { border-left-color: #047857 !important; }
    .act-item-card.info { border-left-color: #2563eb !important; }
    .act-item-card.primary { border-left-color: #d97706 !important; }
</style>
@endpush

@section('content')

<div class="container-fluid px-4 py-3">

    <!-- HERO HEADER BANNER -->
    <div class="activities-hero-banner text-white mb-4">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill act-badge-pill">
                        <i class="ri-pulse-line text-warning fs-5"></i> CANLI SAHA ZAMAN ÇİZELGESİ
                    </span>
                    <span class="badge bg-warning text-dark fw-extrabold px-3 py-2 rounded-pill font-monospace" style="font-size: 11px;">
                        {{ $stats['total_activities_today'] ?? 0 }} Bugün | {{ $stats['total_activities_week'] ?? 0 }} Haftalık
                    </span>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2" style="text-shadow: 0 2px 4px rgba(0,0,0,0.4);">Şantiye Aktivite & Canlı İşlem Akışı</h2>
                <p class="mb-0 fs-6" style="color: #fef08a; font-weight: 500; max-width: 820px; line-height: 1.6;">
                    Tamamlanan imalatlar, açılan yeni projeler, atanan mühendis görevleri ve saha anlık işlem zaman çizelgesi.
                </p>
            </div>

            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <div class="d-flex justify-content-lg-end gap-2 flex-wrap">
                    <a href="{{ route('calendar.index') }}" class="btn btn-outline-light rounded-pill px-3.5 py-2.5 fw-extrabold d-inline-flex align-items-center gap-2">
                        <i class="ri-calendar-event-line fs-5"></i>Takvim Görünümü
                    </a>
                    <a href="{{ route('tasks.create') }}" class="btn btn-warning text-dark rounded-pill px-4 py-2.5 fw-extrabold d-inline-flex align-items-center gap-2 shadow-sm">
                        <i class="ri-add-circle-fill fs-5"></i>+ Yeni Görev
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- 4 SUMMARY KPI CARDS BAR -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card act-kpi-card h-100 border-start border-success border-4 animate__animated animate__fadeInUp">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-extrabold text-uppercase d-block mb-1 fs-7" style="letter-spacing: 0.5px;">BUGÜN TAMAMLANAN</span>
                        <h2 class="fw-extrabold mb-0 text-success font-monospace">{{ $stats['today_completed_tasks'] ?? 0 }} <small class="fs-6 text-muted">Görev</small></h2>
                    </div>
                    <div class="act-stat-icon bg-success-subtle text-success" style="background: rgba(16, 185, 129, 0.12);">
                        <i class="ri-checkbox-circle-fill fs-2" style="color: #047857 !important;"></i>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                    <small class="text-muted fw-bold"><i class="ri-check-double-line me-1"></i>Saha İmalatları</small>
                    <span class="badge bg-success-subtle text-success fw-bold">Teslim Edildi</span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card act-kpi-card h-100 border-start border-primary border-4 animate__animated animate__fadeInUp" style="animation-delay: 0.1s;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-extrabold text-uppercase d-block mb-1 fs-7" style="letter-spacing: 0.5px;">BUGÜN OLUŞTURULAN</span>
                        <h2 class="fw-extrabold mb-0 text-primary font-monospace">{{ $stats['today_created_projects'] ?? 0 }} <small class="fs-6 text-muted">Proje</small></h2>
                    </div>
                    <div class="act-stat-icon bg-primary-subtle text-primary" style="background: rgba(37, 99, 235, 0.12);">
                        <i class="ri-building-2-line fs-2" style="color: #2563eb !important;"></i>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                    <small class="text-muted fw-bold"><i class="ri-add-line me-1"></i>Yeni Şantiyeler</small>
                    <span class="badge bg-primary-subtle text-primary fw-bold">Kayıt Edildi</span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card act-kpi-card h-100 border-start border-warning border-4 animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-extrabold text-uppercase d-block mb-1 fs-7" style="letter-spacing: 0.5px;">TOPLAM AKTİVİTE</span>
                        <h2 class="fw-extrabold mb-0 text-warning font-monospace">{{ $stats['total_activities_today'] ?? 0 }} <small class="fs-6 text-muted">İşlem</small></h2>
                    </div>
                    <div class="act-stat-icon bg-warning-subtle text-warning" style="background: rgba(245, 158, 11, 0.12);">
                        <i class="ri-line-chart-line fs-2" style="color: #d97706 !important;"></i>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                    <small class="text-muted fw-bold"><i class="ri-time-line me-1"></i>Günlük Log</small>
                    <span class="badge bg-warning-subtle text-warning fw-bold">Canlı Akış</span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card act-kpi-card h-100 border-start border-info border-4 animate__animated animate__fadeInUp" style="animation-delay: 0.3s;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-extrabold text-uppercase d-block mb-1 fs-7" style="letter-spacing: 0.5px;">AKTİF PROJELER</span>
                        <h2 class="fw-extrabold mb-0 text-info font-monospace">{{ $stats['active_projects'] ?? 0 }} <small class="fs-6 text-muted">Şantiye</small></h2>
                    </div>
                    <div class="act-stat-icon bg-info-subtle text-info" style="background: rgba(2, 132, 199, 0.12);">
                        <i class="ri-copper-coin-line fs-2" style="color: #0284c7 !important;"></i>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                    <small class="text-muted fw-bold"><i class="ri-hammer-line me-1"></i>Saha İşlemleri</small>
                    <span class="badge bg-info-subtle text-info fw-bold">Devam Ediyor</span>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN TIMELINE GRID -->
    <div class="row g-4 mb-4">
        <!-- BUGÜNÜN AKTİVİTELERİ -->
        <div class="col-xl-6">
            <div class="card border rounded-4 shadow-sm overflow-hidden bg-white h-100" style="border-color: #cbd5e1 !important;">
                <div class="card-header bg-dark text-white p-3.5 d-flex align-items-center justify-content-between">
                    <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                        <i class="ri-history-line text-warning fs-5"></i>Bugünün İşlem Zaman Çizelgesi
                    </h5>
                    <span class="badge bg-warning text-dark font-monospace fw-bold">{{ count($todayActivities) }} Aktivite</span>
                </div>

                <div class="card-body p-4">
                    @forelse($todayActivities as $activity)
                        <div class="act-item-card p-3.5 mb-3 {{ $activity['color'] ?? 'primary' }}">
                            <div class="d-flex align-items-start gap-3">
                                <div class="avatar-md bg-dark font-monospace text-warning rounded-circle d-flex align-items-center justify-content-center fw-bold flex-shrink-0" style="width: 40px; height: 40px;">
                                    <i class="{{ $activity['icon'] ?? 'ri-notification-3-line' }} fs-5"></i>
                                </div>
                                <div class="w-100">
                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                        <h6 class="fw-extrabold text-dark mb-0 fs-6">{{ $activity['title'] }}</h6>
                                        <span class="badge bg-light text-dark font-monospace border">{{ $activity['time'] }}</span>
                                    </div>
                                    <p class="text-secondary small mb-2" style="line-height: 1.5;">{{ $activity['description'] }}</p>
                                    <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                                        <small class="text-muted font-monospace"><i class="ri-building-line me-1"></i>{{ $activity['project'] }}</small>
                                        <small class="text-muted font-monospace"><i class="ri-user-3-line me-1"></i>{{ $activity['user'] }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="ri-calendar-event-line display-3 text-muted mb-2 d-block"></i>
                            <h6 class="fw-bold text-dark">Bugün Henüz Bir Aktivite Kaydedilmedi</h6>
                            <small class="text-muted">Gün içinde yapılan imalat ve proje güncellemeleri burada anlık akacaktır.</small>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- BU HAFTANIN AKTİVİTELERİ -->
        <div class="col-xl-6">
            <div class="card border rounded-4 shadow-sm overflow-hidden bg-white h-100" style="border-color: #cbd5e1 !important;">
                <div class="card-header bg-dark text-white p-3.5 d-flex align-items-center justify-content-between">
                    <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                        <i class="ri-calendar-check-line text-success fs-5"></i>Bu Haftanın İşlem Akışı
                    </h5>
                    <span class="badge bg-success text-white font-monospace fw-bold" style="background: #047857;">{{ count($weekActivities) }} Aktivite</span>
                </div>

                <div class="card-body p-4" style="max-height: 600px; overflow-y: auto;">
                    @forelse($weekActivities->take(15) as $activity)
                        <div class="act-item-card p-3.5 mb-3 {{ $activity['color'] ?? 'primary' }}">
                            <div class="d-flex align-items-start gap-3">
                                <div class="avatar-md bg-dark font-monospace text-success rounded-circle d-flex align-items-center justify-content-center fw-bold flex-shrink-0" style="width: 40px; height: 40px;">
                                    <i class="{{ $activity['icon'] ?? 'ri-checkbox-circle-line' }} fs-5"></i>
                                </div>
                                <div class="w-100">
                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                        <h6 class="fw-extrabold text-dark mb-0 fs-6">{{ $activity['title'] }}</h6>
                                        <span class="badge bg-light text-dark font-monospace border">{{ $activity['date'] ?? '' }} {{ $activity['time'] ?? '' }}</span>
                                    </div>
                                    <p class="text-secondary small mb-2" style="line-height: 1.5;">{{ $activity['description'] }}</p>
                                    <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                                        <small class="text-muted font-monospace"><i class="ri-building-line me-1"></i>{{ $activity['project'] }}</small>
                                        <small class="text-muted font-monospace"><i class="ri-user-3-line me-1"></i>{{ $activity['user'] }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="ri-calendar-2-line display-3 text-muted mb-2 d-block"></i>
                            <h6 class="fw-bold text-dark">Bu Hafta Henüz Bir Aktivite Yok</h6>
                            <small class="text-muted">Haftalık saha aktiviteleri burada kayıt edilecektir.</small>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- HIZLI AKSİYONLAR KARTLARI -->
    <div class="card border rounded-4 shadow-sm overflow-hidden bg-white mb-4" style="border-color: #cbd5e1 !important;">
        <div class="card-header bg-dark text-white p-3.5">
            <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                <i class="ri-flashlight-line text-warning fs-5"></i>Hızlı Yönetim Aksiyonları
            </h5>
        </div>
        <div class="card-body p-4">
            <div class="row g-3">
                <div class="col-xl-3 col-md-6">
                    <a href="{{ route('projects.create') }}" class="btn btn-outline-primary w-100 rounded-4 p-3.5 text-start fw-extrabold d-flex align-items-center gap-3">
                        <i class="ri-add-circle-fill fs-2 text-primary"></i>
                        <div>
                            <div class="text-dark fs-6">Yeni Proje Oluştur</div>
                            <small class="text-muted font-monospace fw-normal">Saha tanımı aç</small>
                        </div>
                    </a>
                </div>
                <div class="col-xl-3 col-md-6">
                    <a href="{{ route('tasks.create') }}" class="btn btn-outline-warning w-100 rounded-4 p-3.5 text-start fw-extrabold d-flex align-items-center gap-3">
                        <i class="ri-task-fill fs-2 text-warning"></i>
                        <div>
                            <div class="text-dark fs-6">Yeni Görev Ataması</div>
                            <small class="text-muted font-monospace fw-normal">İmalat kartı aç</small>
                        </div>
                    </a>
                </div>
                <div class="col-xl-3 col-md-6">
                    <a href="{{ route('calendar.index') }}" class="btn btn-outline-success w-100 rounded-4 p-3.5 text-start fw-extrabold d-flex align-items-center gap-3">
                        <i class="ri-calendar-todo-fill fs-2 text-success"></i>
                        <div>
                            <div class="text-dark fs-6">Şantiye Takvimi</div>
                            <small class="text-muted font-monospace fw-normal">Aylık program</small>
                        </div>
                    </a>
                </div>
                <div class="col-xl-3 col-md-6">
                    <a href="{{ route('projects.index') }}" class="btn btn-outline-info w-100 rounded-4 p-3.5 text-start fw-extrabold d-flex align-items-center gap-3">
                        <i class="ri-pie-chart-2-fill fs-2 text-info"></i>
                        <div>
                            <div class="text-dark fs-6">Proje Raporları</div>
                            <small class="text-muted font-monospace fw-normal">Performans analizi</small>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection