@extends('layouts.master')

@section('title', 'Satış Hedefleri Analiz Dashboard')

@push('styles')
<style>
    .st-db-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #b45309 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.4) !important;
        padding: 32px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .st-db-hero-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #d97706, #2563eb, #16a34a, #d97706);
        background-size: 300% 100%;
        animation: dbGlow 4s linear infinite;
    }

    @keyframes dbGlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .st-db-badge-pill {
        background: rgba(245, 158, 11, 0.25) !important;
        border: 1px solid rgba(245, 158, 11, 0.5) !important;
        color: #fef08a !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    .custom-kpi-card-db {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 20px 22px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .custom-kpi-card-db:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08) !important;
    }

    .custom-card-container {
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 18px !important;
        background: #ffffff !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04) !important;
        overflow: hidden;
    }

    .custom-db-table thead,
    .custom-db-table thead tr,
    .custom-db-table thead th {
        background-color: #0f172a !important;
        color: #ffffff !important;
        padding: 16px !important;
        font-weight: 800 !important;
        font-size: 0.85rem !important;
        border-bottom: 2px solid #d97706 !important;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        --bs-table-bg: #0f172a !important;
        --bs-table-color: #ffffff !important;
    }

    .custom-db-table td {
        padding: 16px !important;
        vertical-align: middle !important;
        border-bottom: 1px solid #cbd5e1 !important;
        color: #0f172a !important;
        font-weight: 600 !important;
    }

    .owner-avatar-pill {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: #0f172a;
        color: #fef08a;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.95rem;
        border: 2px solid #d97706;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-3">

    <!-- HERO HEADER BANNER -->
    <div class="st-db-hero-banner text-white">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill st-db-badge-pill">
                        <i class="ri-dashboard-3-fill text-warning fs-5"></i> CRM & PERFORMANS ANALİTİK DASHBOARD
                    </span>
                    <span class="badge bg-success text-white fw-extrabold px-3 py-2 rounded-pill font-monospace" style="background: #16a34a !important;">
                        {{ $activeTargets->count() }} Aktif Hedef
                    </span>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2">Satış Hedefleri Performans Dashboard</h2>
                <p class="mb-0 fs-6" style="max-width: 780px; line-height: 1.6; color: #e2e8f0; font-weight: 500;">
                    Cari dönem ekip performansını, aktif hedeflerinizi ve gerçekleşme oranlarınızı tek bir analitik dashboard üzerinden izleyin.
                </p>
            </div>

            <div class="col-lg-5 text-lg-end mt-3 mt-lg-0">
                <div class="d-flex align-items-center justify-content-lg-end gap-2 flex-wrap">
                    <a href="{{ route('sales-targets.index') }}" class="btn btn-outline-light rounded-pill px-3.5 py-2 fw-extrabold d-inline-flex align-items-center gap-2">
                        <i class="ri-list-check me-1"></i>Tüm Hedefler Listesi
                    </a>
                    <a href="{{ route('sales-targets.create') }}" class="btn btn-warning text-dark rounded-pill px-4 py-2.5 fw-extrabold d-inline-flex align-items-center gap-2 shadow-sm">
                        <i class="ri-add-circle-fill fs-5"></i>+ Yeni Hedef
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if ($activeTargets->isEmpty())
        <div class="card border-1 border-secondary shadow-sm p-5 text-center rounded-4 mb-4">
            <i class="ri-dashboard-line display-3 text-warning mb-2 d-block"></i>
            <h4 class="fw-bold text-dark mb-1">Cari Dönemde Aktif Hedef Bulunmamaktadır</h4>
            <p class="text-muted mb-3">Ekibiniz için yeni hedefler tanımlayarak performans analitiğini buradan takip edebilirsiniz.</p>
            <div>
                <a href="{{ route('sales-targets.create') }}" class="btn btn-warning text-dark fw-extrabold rounded-pill px-4 py-2">
                    <i class="ri-add-circle-fill me-1"></i>Yeni Hedef Ekle
                </a>
            </div>
        </div>
    @else
        <!-- SUMMARY KPI CARDS ROW -->
        <div class="row g-3 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="card custom-kpi-card-db h-100 border-start border-primary border-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">AKTİF HEDEF SAYISI</small>
                            <div class="fw-extrabold text-primary font-monospace" style="font-size: 1.8rem;">{{ $activeTargets->count() }} Hedef</div>
                            <small class="text-primary fw-bold"><i class="ri-rocket-line me-1"></i>Cari Dönemdeki Hedefler</small>
                        </div>
                        <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #eff6ff !important; border: 1px solid #bfdbfe !important;">
                            <i class="ri-target-line fs-2" style="color: #2563eb !important;"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card custom-kpi-card-db h-100 border-start border-success border-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">EKİP ORTALAMA BAŞARI</small>
                            <div class="fw-extrabold text-success font-monospace" style="font-size: 1.8rem;">%{{ number_format($activeTargets->avg('completion_percentage') ?? 0, 1) }}</div>
                            <small class="text-success fw-bold"><i class="ri-checkbox-circle-fill me-1"></i>Genel Tamamlanma Oranı</small>
                        </div>
                        <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0fdf4 !important; border: 1px solid #bbf7d0 !important;">
                            <i class="ri-pie-chart-2-fill fs-2" style="color: #16a34a !important;"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card custom-kpi-card-db h-100 border-start border-warning border-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">TOPLAM HEDEF CİRO</small>
                            <div class="fw-extrabold text-dark font-monospace" style="font-size: 1.5rem;">₺{{ number_format($activeTargets->where('target_type', 'revenue')->sum('target_amount'), 0, ',', '.') }}</div>
                            <small class="text-warning fw-bold"><i class="ri-money-dollar-circle-line me-1"></i>Hedeflenen Ciro</small>
                        </div>
                        <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #fffbeb !important; border: 1px solid #fde68a !important;">
                            <i class="ri-money-dollar-circle-line fs-2" style="color: #d97706 !important;"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card custom-kpi-card-db h-100 border-start border-info border-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">TOPLAM HEDEF ADET</small>
                            <div class="fw-extrabold text-info font-monospace" style="font-size: 1.6rem;">{{ number_format($activeTargets->where('target_type', 'unit')->sum('target_amount'), 0, ',', '.') }} Adet</div>
                            <small class="text-info fw-bold"><i class="ri-building-line me-1"></i>Hedeflenen Satış Adedi</small>
                        </div>
                        <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0f9ff !important; border: 1px solid #bae6fd !important;">
                            <i class="ri-building-line fs-2" style="color: #0284c7 !important;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TEAM PERFORMANCE CARD -->
        <div class="card custom-card-container mb-4">
            <div class="card-header bg-dark text-white p-3.5" style="background-color: #0f172a !important;">
                <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                    <i class="ri-team-line text-warning fs-5"></i>Ekip Üyeleri Performans Dağılımı
                </h5>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    @foreach($allUsers as $user)
                        @php
                            $userTargets = $activeTargets->where('user_id', $user->id);
                            if ($userTargets->isEmpty()) continue;

                            $avgCompletion = round($userTargets->avg('completion_percentage') ?? 0, 1);
                            $badgeClass = $avgCompletion >= 100 ? 'bg-success text-white' : ($avgCompletion >= 75 ? 'bg-info text-white' : 'bg-warning text-dark');
                            $userName = $user->name ?? 'Kullanıcı';
                            $userInitials = mb_substr($userName, 0, 2);
                        @endphp

                        <div class="col-lg-6">
                            <div class="p-3.5 rounded-3 border" style="background: #f8fafc; border-color: #cbd5e1 !important;">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="d-flex align-items-center gap-2.5">
                                        <div class="owner-avatar-pill">
                                            {{ strtoupper($userInitials) }}
                                        </div>
                                        <div>
                                            <h6 class="fw-extrabold text-dark mb-0 fs-6">{{ $userName }}</h6>
                                            <small class="text-muted fw-semibold">{{ $user->email }}</small>
                                        </div>
                                    </div>
                                    <span class="badge rounded-pill px-3 py-1.5 font-monospace fw-extrabold {{ $badgeClass }}" style="font-size: 12px;">
                                        %{{ $avgCompletion }} Başarı
                                    </span>
                                </div>

                                <div class="mb-3">
                                    <div class="d-flex justify-content-between small fw-bold text-muted mb-1">
                                        <span>Ortalama İlerleme Barı</span>
                                        <span class="text-dark font-monospace">%{{ $avgCompletion }}</span>
                                    </div>
                                    <div class="progress" style="height: 10px; background: #e2e8f0;">
                                        <div class="progress-bar {{ $avgCompletion >= 100 ? 'bg-success' : 'bg-primary' }}" style="width: {{ min(100, $avgCompletion) }}%;"></div>
                                    </div>
                                </div>

                                <div class="row g-2">
                                    @foreach($userTargets as $target)
                                        @php
                                            $tIsRev = $target->target_type === 'revenue';
                                            $tComp = round($target->completion_percentage ?? 0, 1);
                                        @endphp
                                        <div class="col-6">
                                            <div class="p-2 rounded-2 border bg-white text-center" style="border-color: #cbd5e1 !important;">
                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                    <span class="badge {{ $tIsRev ? 'bg-primary' : 'bg-success' }} rounded-pill" style="font-size: 10px;">
                                                        {{ $tIsRev ? 'Ciro' : 'Adet' }}
                                                    </span>
                                                    <small class="fw-extrabold font-monospace {{ $tComp >= 100 ? 'text-success' : 'text-primary' }}">%{{ $tComp }}</small>
                                                </div>
                                                <div class="fw-extrabold font-monospace text-dark text-truncate" style="font-size: 12px;">
                                                    {{ $tIsRev ? '₺' . number_format($target->target_amount, 0, ',', '.') : number_format($target->target_amount, 0, ',', '.') . ' Adet' }}
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- ACTIVE TARGETS TABLE CARD -->
        <div class="card custom-card-container">
            <div class="card-header bg-dark text-white p-3.5" style="background-color: #0f172a !important;">
                <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                    <i class="ri-list-check-line text-warning fs-5"></i>Aktif Hedef Listesi & Durumu
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table custom-db-table align-middle mb-0">
                        <thead style="background-color: #0f172a !important;">
                            <tr style="background-color: #0f172a !important;">
                                <th class="ps-4 text-warning" style="background-color: #0f172a !important; color: #fef08a !important; font-weight: 800;">Temsilci</th>
                                <th class="text-white" style="background-color: #0f172a !important; color: #ffffff !important; font-weight: 800;">Dönem</th>
                                <th class="text-white" style="background-color: #0f172a !important; color: #ffffff !important; font-weight: 800;">Hedef Türü</th>
                                <th class="text-end text-white" style="background-color: #0f172a !important; color: #ffffff !important; font-weight: 800;">Hedef Miktar</th>
                                <th class="text-end text-white" style="background-color: #0f172a !important; color: #ffffff !important; font-weight: 800;">Gerçekleşen</th>
                                <th class="text-center text-white" style="background-color: #0f172a !important; color: #ffffff !important; font-weight: 800;">İlerleme</th>
                                <th class="pe-4 text-end text-white" style="background-color: #0f172a !important; color: #ffffff !important; font-weight: 800;">İşlem</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($activeTargets as $target)
                                @php
                                    $isRev = $target->target_type === 'revenue';
                                    $compVal = $isRev ? ($target->completed_revenue ?? 0) : ($target->completed_units ?? 0);
                                    $perc = min(100, max(0, round($target->completion_percentage ?? 0, 1)));
                                @endphp
                                <tr>
                                    <td class="ps-4 py-3 fw-extrabold text-dark">
                                        👤 {{ $target->user->name ?? 'Kullanıcı' }}
                                    </td>
                                    <td class="font-monospace text-dark fw-semibold" style="font-size: 13px;">
                                        📅 {{ $target->period_start ? $target->period_start->format('d.m.Y') : '-' }} — {{ $target->period_end ? $target->period_end->format('d.m.Y') : '-' }}
                                    </td>
                                    <td>
                                        <span class="badge {{ $isRev ? 'bg-primary' : 'bg-success' }} rounded-pill px-3 py-1.5 font-monospace fw-extrabold" style="font-size: 11px;">
                                            {{ $isRev ? 'Ciro Hedefi' : 'Satış Adedi' }}
                                        </span>
                                    </td>
                                    <td class="text-end font-monospace fw-extrabold text-dark fs-6">
                                        {{ $isRev ? '₺' . number_format($target->target_amount, 0, ',', '.') : number_format($target->target_amount, 0, ',', '.') . ' Adet' }}
                                    </td>
                                    <td class="text-end font-monospace fw-extrabold text-success fs-6">
                                        {{ $isRev ? '₺' . number_format($compVal, 0, ',', '.') : number_format($compVal, 0, ',', '.') . ' Adet' }}
                                    </td>
                                    <td class="text-center font-monospace">
                                        <div class="d-flex align-items-center justify-content-center gap-1.5">
                                            <div class="progress flex-grow-1" style="height: 8px; max-width: 70px; background: #e2e8f0;">
                                                <div class="progress-bar {{ $perc >= 100 ? 'bg-success' : 'bg-primary' }}" style="width: {{ $perc }}%;"></div>
                                            </div>
                                            <span class="fw-extrabold text-dark" style="font-size: 11px;">%{{ $perc }}</span>
                                        </div>
                                    </td>
                                    <td class="pe-4 text-end">
                                        <a href="{{ route('sales-targets.show', $target) }}" class="btn btn-sm btn-warning text-dark rounded-circle p-2" title="İncele">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

</div>
@endsection
