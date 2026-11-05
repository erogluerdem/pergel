@extends('layouts.master')

@section('title', 'Gelişmiş Finansal & İşletme Raporları')

@section('topbar-title', 'Gelişmiş Raporlar Hub')

@push('styles')
<style>
    /* Hero Header Banner */
    .adv-rep-hero-banner {
        border-radius: 20px;
        padding: 2.2rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    html[data-bs-theme="light"] .adv-rep-hero-banner,
    :root:not([data-bs-theme="dark"]) .adv-rep-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #0284c7 50%, #0369a1 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    html[data-bs-theme="dark"] .adv-rep-hero-banner {
        background: linear-gradient(135deg, rgba(2, 132, 199, 0.95) 0%, rgba(15, 23, 42, 0.95) 100%);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #f8fafc;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
    }

    /* Module Hub Cards */
    .adv-hub-card {
        border-radius: 18px;
        padding: 1.8rem;
        height: 100%;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .adv-hub-card:hover {
        transform: translateY(-5px);
    }

    html[data-bs-theme="light"] .adv-hub-card,
    :root:not([data-bs-theme="dark"]) .adv-hub-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }

    html[data-bs-theme="dark"] .adv-hub-card {
        background: rgba(30, 41, 59, 0.75);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
    }

    .adv-hub-icon {
        width: 56px;
        height: 56px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        margin-bottom: 1.25rem;
    }
</style>
@endpush

@section('content')

<!-- Hero Header Banner -->
<div class="adv-rep-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-3 py-1 fw-bold">
                    <i class="ri-pulse-line me-1"></i>Advanced Business & Financial Intelligence
                </span>
            </div>
            <h1 class="fw-extrabold text-white mb-1">Gelişmiş İşletme & Finansal Raporlar</h1>
            <p class="text-white-50 mb-0 fs-6">Danışman performansı, mevsimsel trendler, taksit ödemeleri ve çok boyutlu analitik raporlar.</p>
        </div>
    </div>
</div>

<!-- Report Module Grid -->
<div class="row g-4">
    <!-- Module 1: Consultant Performance -->
    <div class="col-md-6 col-lg-4">
        <div class="adv-hub-card">
            <div class="adv-hub-icon bg-primary bg-opacity-10 text-primary">
                <i class="ri-user-star-line"></i>
            </div>
            <h5 class="fw-bold text-dark mb-2">Danışman Performansı</h5>
            <p class="text-muted small mb-4">Satış temsilcilerinin ciro, kesilen fatura sayısı, ödeme tamamlama oranı ve ortalama işlem büyüklüğü analizi.</p>
            <a href="{{ route('advanced_reports.consultant_performance') }}" class="btn btn-primary rounded-pill px-4 fw-bold w-100" style="background:#0284c7;">
                <i class="ri-eye-line me-1"></i>Raporu İncele
            </a>
        </div>
    </div>

    <!-- Module 2: Seasonal Analysis -->
    <div class="col-md-6 col-lg-4">
        <div class="adv-hub-card">
            <div class="adv-hub-icon bg-success bg-opacity-10 text-success">
                <i class="ri-calendar-line"></i>
            </div>
            <h5 class="fw-bold text-dark mb-2">Mevsimsel & Aylık Trendler</h5>
            <p class="text-muted small mb-4">Yılın 12 ayına yayılan satış tutarları, tahsil edilen bakiyeler ve mevsimsel dalgalanma grafikleri.</p>
            <a href="{{ route('advanced_reports.seasonal_analysis') }}" class="btn btn-success rounded-pill px-4 fw-bold w-100">
                <i class="ri-line-chart-line me-1"></i>Raporu İncele
            </a>
        </div>
    </div>

    <!-- Module 3: Payment & Installment Analysis -->
    <div class="col-md-6 col-lg-4">
        <div class="adv-hub-card">
            <div class="adv-hub-icon bg-warning bg-opacity-10 text-warning">
                <i class="ri-coin-line"></i>
            </div>
            <h5 class="fw-bold text-dark mb-2">Taksit & Ödeme Analizi</h5>
            <p class="text-muted small mb-4">Sözleşme taksit planları, ödenen hakedişler, vadesi geçen borçlar ve tahsilat başarı skorları.</p>
            <a href="{{ route('advanced_reports.payment_analysis') }}" class="btn btn-warning rounded-pill px-4 fw-bold w-100 text-dark">
                <i class="ri-scales-line me-1"></i>Raporu İncele
            </a>
        </div>
    </div>

    <!-- Module 4: Activity Log & Audit Dashboard -->
    <div class="col-md-6 col-lg-4">
        <div class="adv-hub-card">
            <div class="adv-hub-icon bg-info bg-opacity-10 text-info">
                <i class="ri-history-line"></i>
            </div>
            <h5 class="fw-bold text-dark mb-2">Sistem Aktivite Paneli</h5>
            <p class="text-muted small mb-4">Kullanıcı işlem günlükleri, audit izleri ve sistemde gerçekleştirilen veri değişiklikleri.</p>
            <a href="{{ route('advanced_reports.activity_dashboard') }}" class="btn btn-info rounded-pill px-4 fw-bold w-100 text-white">
                <i class="ri-pulse-line me-1"></i>Raporu İncele
            </a>
        </div>
    </div>

    <!-- Module 5: Multi-Dimensional Analytical Report -->
    <div class="col-md-6 col-lg-4">
        <div class="adv-hub-card">
            <div class="adv-hub-icon bg-danger bg-opacity-10 text-danger">
                <i class="ri-stack-line"></i>
            </div>
            <h5 class="fw-bold text-dark mb-2">Çok Boyutlu Analitik Rapor</h5>
            <p class="text-muted small mb-4">Danışman, şantiye projesi, fatura ödeme durumu ve para birimi boyutlarına göre çapraz matris analizleri.</p>
            <a href="{{ route('advanced_reports.multi_dimensional') }}" class="btn btn-danger rounded-pill px-4 fw-bold w-100">
                <i class="ri-layout-grid-line me-1"></i>Raporu İncele
            </a>
        </div>
    </div>
</div>

@endsection
