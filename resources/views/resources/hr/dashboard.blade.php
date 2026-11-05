@extends('layouts.master')

@section('title', 'İnsan Kaynakları & Şantiye Kadro Yönetimi')

@section('topbar-title', 'İK & Şantiye Kadro Panosu')

@push('styles')
<style>
    /* Hero Header Banner */
    .hr-hero-banner {
        border-radius: 20px;
        padding: 2.2rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    html[data-bs-theme="light"] .hr-hero-banner,
    :root:not([data-bs-theme="dark"]) .hr-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #4f46e5 50%, #6366f1 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    html[data-bs-theme="dark"] .hr-hero-banner {
        background: linear-gradient(135deg, rgba(79, 70, 229, 0.95) 0%, rgba(15, 23, 42, 0.95) 100%);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #f8fafc;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
    }

    /* KPI Spec Cards */
    .hr-kpi-card {
        border-radius: 16px;
        padding: 1.5rem;
        height: 100%;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .hr-kpi-card:hover {
        transform: translateY(-4px);
    }

    html[data-bs-theme="light"] .hr-kpi-card,
    :root:not([data-bs-theme="dark"]) .hr-kpi-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }

    html[data-bs-theme="dark"] .hr-kpi-card {
        background: rgba(30, 41, 59, 0.75);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
    }

    .hr-stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    /* Glassmorphic Container */
    .hr-glass-card {
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        transition: all 0.3s ease;
    }

    html[data-bs-theme="light"] .hr-glass-card,
    :root:not([data-bs-theme="dark"]) .hr-glass-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    html[data-bs-theme="dark"] .hr-glass-card {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
</style>
@endpush

@section('content')

<!-- Hero Header Banner -->
<div class="hr-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-3 py-1 fw-bold">
                    <i class="ri-user-star-line me-1"></i>Human Resources & Site Workforce
                </span>
            </div>
            <h1 class="fw-extrabold text-white mb-1">İnsan Kaynakları & Şantiye Kadro Yönetimi</h1>
            <p class="text-white-50 mb-0 fs-6">Mühendis, şantiye şefi, teknik personel atamaları ve İSG sertifika süresi takipleri.</p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('resources.hr.employees') }}" class="btn btn-light text-indigo rounded-pill px-4 fw-bold shadow-sm" style="color:#4f46e5;">
                <i class="ri-user-search-line me-1"></i>Personel Listesi
            </a>
            <a href="{{ route('resources.hr.workforce_planning') }}" class="btn btn-indigo text-white rounded-pill px-4 fw-bold shadow-sm" style="background:#4f46e5;">
                <i class="ri-calendar-event-line me-1"></i>İş Gücü Planlama
            </a>
        </div>
    </div>
</div>

<!-- 4 Top KPI Spec Cards -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="hr-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Toplam Kadrolu Personel</span>
                    <h2 class="fw-extrabold mb-0 text-indigo" style="color:#4f46e5;">{{ $totalEmployees }}</h2>
                </div>
                <div class="hr-stat-icon bg-indigo bg-opacity-10 text-indigo" style="color:#4f46e5;">
                    <i class="ri-team-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="hr-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Projelere Atanmış</span>
                    <h2 class="fw-extrabold mb-0 text-success">{{ $assignedEmployees }}</h2>
                </div>
                <div class="hr-stat-icon bg-success bg-opacity-10 text-success">
                    <i class="ri-building-4-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="hr-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Merkez / Serbest Kadro</span>
                    <h2 class="fw-extrabold mb-0 text-warning">{{ $idleEmployees }}</h2>
                </div>
                <div class="hr-stat-icon bg-warning bg-opacity-10 text-warning">
                    <i class="ri-user-shared-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="hr-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Yenilenecek Sertifika (30g)</span>
                    <h2 class="fw-extrabold mb-0 text-danger">{{ $expiringCertificates }}</h2>
                </div>
                <div class="hr-stat-icon bg-danger bg-opacity-10 text-danger">
                    <i class="ri-shield-cross-line"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart & Quick Links Row -->
<div class="row">
    <!-- Chart Column -->
    <div class="col-lg-8">
        <div class="hr-glass-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0 text-dark">
                    <i class="ri-pie-chart-2-line text-indigo me-2" style="color:#4f46e5;"></i>Projelere Göre Mühendis & Teknik Kadro Dağılımı
                </h5>
                <a href="{{ route('resources.hr.workforce_planning') }}" class="btn btn-sm btn-outline-indigo rounded-pill px-3" style="color:#4f46e5; border-color:#4f46e5;">
                    <i class="ri-external-link-line me-1"></i>Planlama Sayfası
                </a>
            </div>
            <div id="projectDistributionChart" style="min-height: 320px;"></div>
        </div>
    </div>

    <!-- Quick Navigation Column -->
    <div class="col-lg-4">
        <div class="hr-glass-card">
            <h6 class="fw-bold text-dark mb-3"><i class="ri-compass-3-line text-indigo me-2" style="color:#4f46e5;"></i>İK Hızlı Erişim & Modüller</h6>
            <div class="d-grid gap-2 mb-3">
                <a href="{{ route('resources.hr.employees') }}" class="btn btn-outline-primary text-start rounded-3 p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <strong class="d-block text-dark">Personel & Mühendis Listesi</strong>
                            <small class="text-muted">Tüm özlük dosyaları ve iletişim rehberi</small>
                        </div>
                        <i class="ri-arrow-right-s-line fs-4"></i>
                    </div>
                </a>
                <a href="{{ route('resources.hr.workforce_planning') }}" class="btn btn-outline-success text-start rounded-3 p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <strong class="d-block text-dark">Şantiye İş Gücü Planlaması</strong>
                            <small class="text-muted">Proje bazlı personel atama ve takvimler</small>
                        </div>
                        <i class="ri-arrow-right-s-line fs-4"></i>
                    </div>
                </a>
                <a href="{{ route('resources.hr.certificates') }}" class="btn btn-outline-warning text-start rounded-3 p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <strong class="d-block text-dark">İSG & Mesleki Sertifikalar</strong>
                            <small class="text-muted">Geçerlilik süresi biten yetki belgeleri</small>
                        </div>
                        <i class="ri-arrow-right-s-line fs-4"></i>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const projectData = @json($projectDistribution ?? []);

    const projectOptions = {
        series: projectData.length ? projectData.map(i => i.count) : [4, 3, 2, 2],
        chart: { type: 'donut', height: 320 },
        labels: projectData.length ? projectData.map(i => i.project) : ['Aksu Ticaret Merkezi', 'Güneşli Konutları', 'Bosphorus Rezidans', 'Merkez Ofis'],
        colors: ['#4f46e5', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'],
        legend: { position: 'bottom' },
        dataLabels: { enabled: true }
    };

    const projectChart = new ApexCharts(document.querySelector('#projectDistributionChart'), projectOptions);
    projectChart.render();
});
</script>
@endpush
