@extends('layouts.master')

@section('title', 'Satın Alma Onay Bekleyen Talepler')

@section('topbar-title', 'Satın Alma - Onay Mekanizması')

@push('styles')
<style>
    /* Hero Header Banner */
    .approval-hero-banner {
        border-radius: 20px;
        padding: 2.2rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    html[data-bs-theme="light"] .approval-hero-banner,
    :root:not([data-bs-theme="dark"]) .approval-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #d97706 50%, #b45309 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    html[data-bs-theme="dark"] .approval-hero-banner {
        background: linear-gradient(135deg, rgba(180, 83, 9, 0.95) 0%, rgba(15, 23, 42, 0.95) 100%);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #f8fafc;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
    }

    /* KPI Spec Cards */
    .approval-kpi-card {
        border-radius: 16px;
        padding: 1.5rem;
        height: 100%;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .approval-kpi-card:hover {
        transform: translateY(-4px);
    }

    html[data-bs-theme="light"] .approval-kpi-card,
    :root:not([data-bs-theme="dark"]) .approval-kpi-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }

    html[data-bs-theme="dark"] .approval-kpi-card {
        background: rgba(30, 41, 59, 0.75);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
    }

    .approval-stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    /* Glassmorphic Container */
    .approval-glass-card {
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        transition: all 0.3s ease;
    }

    html[data-bs-theme="light"] .approval-glass-card,
    :root:not([data-bs-theme="dark"]) .approval-glass-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    html[data-bs-theme="dark"] .approval-glass-card {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
</style>
@endpush

@section('content')

<!-- Hero Header Banner -->
<div class="approval-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-3 py-1 fw-bold">
                    <i class="ri-checkbox-multiple-line me-1"></i>Purchasing Approval Workflows
                </span>
            </div>
            <h1 class="fw-extrabold text-white mb-1">Satın Alma Onay Bekleyen Talepler Portalı</h1>
            <p class="text-white-50 mb-0 fs-6">Saha talep onayları, finans bütçe limit kontrolleri ve üst yönetim ihale onay mekanizması.</p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('purchasing.approvals.create') }}" class="btn btn-warning text-dark rounded-pill px-4 fw-bold shadow-sm">
                <i class="ri-add-circle-line me-1"></i>Yeni Onay Süreci Başlat
            </a>
        </div>
    </div>
</div>

<!-- 4 Top KPI Spec Cards -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="approval-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Toplam Onay Süreci</span>
                    <h2 class="fw-extrabold mb-0 text-amber" style="color:#d97706;">{{ $totalApprovals }} Kayıt</h2>
                </div>
                <div class="approval-stat-icon bg-warning bg-opacity-10 text-warning">
                    <i class="ri-git-pull-request-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="approval-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Onay Bekleyen Talepler</span>
                    <h2 class="fw-extrabold mb-0 text-warning">{{ $pendingCount }}</h2>
                </div>
                <div class="approval-stat-icon bg-warning bg-opacity-10 text-warning">
                    <i class="ri-time-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="approval-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Onaylanan Talepler</span>
                    <h2 class="fw-extrabold mb-0 text-success">{{ $approvedCount }}</h2>
                </div>
                <div class="approval-stat-icon bg-success bg-opacity-10 text-success">
                    <i class="ri-checkbox-circle-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="approval-kpi-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Reddedilen Talepler</span>
                    <h2 class="fw-extrabold mb-0 text-danger">{{ $rejectedCount }}</h2>
                </div>
                <div class="approval-stat-icon bg-danger bg-opacity-10 text-danger">
                    <i class="ri-close-circle-line"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Approvals Main Table Card -->
<div class="approval-glass-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0 text-dark">
            <i class="ri-file-shield-2-line text-warning me-2"></i>Satın Alma Onay Süreçleri Listesi
        </h5>
        <span class="badge bg-warning bg-opacity-10 text-dark border border-warning border-opacity-30 rounded-pill px-3 py-1 fw-bold">
            {{ count($approvals) }} Onay Adımı
        </span>
    </div>

    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">Talep Ref No</th>
                    <th>Talep Açıklaması & Proje</th>
                    <th>Onay Seviyesi</th>
                    <th>Onaycı Yetkili</th>
                    <th>Tarih</th>
                    <th>Durum</th>
                    <th class="text-end pe-4">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                @forelse($approvals as $app)
                    <tr>
                        <td class="ps-4">
                            <span class="badge bg-dark bg-opacity-10 text-dark border font-monospace px-3 py-1 fw-bold fs-6">
                                {{ 'REQ-2026-' . sprintf('%04d', $app->requisition_id) }}
                            </span>
                        </td>
                        <td>
                            <strong class="text-dark d-block">{{ Str::limit($app->requisition->notes ?? 'Satın Alma Talep Açıklaması', 40) }}</strong>
                            <small class="text-muted">{{ $app->requisition->project->name ?? 'Şantiye Projesi' }}</small>
                        </td>
                        <td>
                            @php
                                $levels = [
                                    'manager' => '1. Kademe: Proje Müdürü',
                                    'finance' => '2. Kademe: Finans Bütçe',
                                    'executive' => '3. Kademe: Genel Müdürlük',
                                    'final' => 'Son Seviye: Yönetim Kurulu',
                                ];
                            @endphp
                            <span class="badge bg-info bg-opacity-10 text-info border px-2 py-1 fs-7">
                                {{ $levels[$app->approval_level] ?? ucfirst($app->approval_level) }}
                            </span>
                        </td>
                        <td>
                            <strong class="text-dark d-block">{{ $app->approver->name ?? 'Onay Yetkilisi' }}</strong>
                        </td>
                        <td>
                            <span class="text-dark small">{{ $app->created_at ? $app->created_at->format('d.m.Y H:i') : '-' }}</span>
                        </td>
                        <td>
                            @if($app->status === 'approved')
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-30 rounded-pill px-3 py-1 fw-bold">Onaylandı</span>
                            @elseif($app->status === 'rejected')
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-30 rounded-pill px-3 py-1 fw-bold">Reddedildi</span>
                            @else
                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-30 rounded-pill px-3 py-1 fw-bold">Onay Bekliyor</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <div class="btn-group gap-1">
                                <a href="{{ route('purchasing.approvals.show', $app->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                    <i class="ri-eye-line me-1"></i>İncele
                                </a>
                                <a href="{{ route('purchasing.approvals.edit', $app->id) }}" class="btn btn-sm btn-outline-warning rounded-circle" title="Düzenle">
                                    <i class="ri-pencil-line"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="ri-checkbox-circle-line fs-1 d-block mb-2 text-success"></i>
                            Tüm onay adımları başarıyla tamamlanmış!
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
