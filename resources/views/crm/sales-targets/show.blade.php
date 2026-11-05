@extends('layouts.master')

@section('title', ($salesTarget->user->name ?? 'Temsilci') . ' - Satış Hedefi Detayı')

@push('styles')
<style>
    .st-show-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #b45309 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.4) !important;
        padding: 32px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .st-show-hero-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #d97706, #2563eb, #16a34a, #d97706);
        background-size: 300% 100%;
        animation: showGlow 4s linear infinite;
    }

    @keyframes showGlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .st-user-avatar-lg {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: #0f172a;
        color: #fef08a;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
        border: 2px solid #d97706;
        box-shadow: 0 4px 15px rgba(217, 119, 6, 0.4);
    }

    .custom-kpi-card-show {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 20px 22px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .custom-kpi-card-show:hover {
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

    .st-opp-item {
        padding: 16px 20px !important;
        border-bottom: 1px solid #cbd5e1 !important;
        transition: background-color 0.18s ease;
    }
    .st-opp-item:hover {
        background-color: #f8fafc !important;
    }
    .st-opp-item:last-child {
        border-bottom: none !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-3">

    @php
        $isRevenue = $salesTarget->target_type === 'revenue';
        $completedVal = $isRevenue ? ($salesTarget->completed_revenue ?? 0) : ($salesTarget->completed_units ?? 0);
        $targetVal = $salesTarget->target_amount;
        $percent = min(100, max(0, round($salesTarget->completion_percentage ?? 0, 1)));
        $userName = $salesTarget->user->name ?? 'Tanımsız Kullanıcı';
        $userInitials = mb_substr($userName, 0, 2);
    @endphp

    <!-- HERO HEADER BANNER -->
    <div class="st-show-hero-banner text-white">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-3">
                    <div class="st-user-avatar-lg flex-shrink-0">
                        {{ strtoupper($userInitials) }}
                    </div>
                    <div>
                        <h2 class="display-6 fw-extrabold text-white mb-2">{{ $userName }}</h2>
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <span class="badge bg-dark text-warning border border-warning rounded-pill px-3 py-1.5 font-monospace fw-bold" style="font-size: 12px;">
                                📅 {{ $salesTarget->period_start ? $salesTarget->period_start->format('d.m.Y') : '-' }} — {{ $salesTarget->period_end ? $salesTarget->period_end->format('d.m.Y') : '-' }}
                            </span>
                            <span class="badge {{ $isRevenue ? 'bg-primary' : 'bg-success' }} rounded-pill px-3 py-1.5 font-monospace fw-extrabold" style="font-size: 12px;">
                                {{ $isRevenue ? 'Ciro Hedefi (₺)' : 'Satış Adedi Hedefi' }}
                            </span>
                            <span class="badge bg-success text-white rounded-pill px-3 py-1.5 font-monospace fw-extrabold" style="background: #16a34a !important; font-size: 12px;">
                                {{ strtoupper($salesTarget->status ?? 'AKTİF') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <div class="d-flex align-items-center justify-content-lg-end gap-2 flex-wrap">
                    <a href="{{ route('sales-targets.index') }}" class="btn btn-outline-light rounded-pill px-3.5 py-2 fw-extrabold d-inline-flex align-items-center gap-2">
                        <i class="ri-arrow-left-line me-1"></i>Hedef Listesi
                    </a>
                    <a href="{{ route('sales-targets.edit', $salesTarget) }}" class="btn btn-warning text-dark rounded-pill px-4 py-2.5 fw-extrabold d-inline-flex align-items-center gap-2 shadow-sm">
                        <i class="ri-pencil-line fs-5"></i>Düzenle
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- SUMMARY KPI CARDS ROW -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-show h-100 border-start border-primary border-4">
                <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">HEDEF TÜRÜ</small>
                <div class="fw-extrabold text-primary font-monospace" style="font-size: 1.5rem;">
                    {{ $isRevenue ? '💰 Ciro Hedefi' : '🎯 Satış Adedi' }}
                </div>
                <small class="text-primary fw-bold">{{ $isRevenue ? 'TL Bazlı Performans' : 'Adet Bazlı Performans' }}</small>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-show h-100 border-start border-warning border-4">
                <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">HEDEFLENEN MİKTAR</small>
                <div class="fw-extrabold text-dark font-monospace" style="font-size: 1.5rem;">
                    {{ $isRevenue ? '₺' . number_format($targetVal, 0, ',', '.') : number_format($targetVal, 0, ',', '.') . ' Adet' }}
                </div>
                <small class="text-warning fw-bold">Dönem Sonu Hedefi</small>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-show h-100 border-start border-success border-4">
                <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">GERÇEKLEŞEN SATIŞ</small>
                <div class="fw-extrabold text-success font-monospace" style="font-size: 1.5rem;">
                    {{ $isRevenue ? '₺' . number_format($completedVal, 0, ',', '.') : number_format($completedVal, 0, ',', '.') . ' Adet' }}
                </div>
                <small class="text-success fw-bold"><i class="ri-checkbox-circle-fill me-1"></i>%{{ $percent }} Başarı Oranı</small>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-show h-100 border-start border-info border-4">
                <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">KALAN FARK</small>
                <div class="fw-extrabold text-info font-monospace" style="font-size: 1.5rem;">
                    {{ $isRevenue ? '₺' . number_format($salesTarget->remaining ?? 0, 0, ',', '.') : number_format($salesTarget->remaining ?? 0, 0, ',', '.') . ' Adet' }}
                </div>
                <small class="text-info fw-bold">Tamamlanması Gereken</small>
            </div>
        </div>
    </div>

    <!-- PROGRESS & PERFORMANCE DETAILS CARD -->
    <div class="card custom-card-container mb-4">
        <div class="card-header bg-dark text-white p-3.5" style="background-color: #0f172a !important;">
            <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                <i class="ri-line-chart-line text-warning fs-5"></i>Hedef İlerleme & Performans Analizi
            </h5>
        </div>
        <div class="card-body p-4">
            @if($salesTarget->description)
                <div class="p-3 rounded-3 mb-4" style="background: #f8fafc; border-left: 4px solid #d97706; color: #0f172a; font-weight: 600;">
                    <i class="ri-quote-text text-warning me-1 fs-5"></i> <strong>Hedef Açıklaması:</strong> "{{ $salesTarget->description }}"
                </div>
            @endif

            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="fw-extrabold text-dark text-uppercase font-size-12">Genel Tamamlanma Yüzdesi</span>
                    <span class="badge rounded-pill {{ $percent >= 100 ? 'bg-success' : 'bg-primary' }} font-monospace fw-extrabold fs-6 px-3">
                        %{{ $percent }}
                    </span>
                </div>
                <div class="progress" style="height: 14px; background: #e2e8f0; border-radius: 7px;">
                    <div class="progress-bar {{ $percent >= 100 ? 'bg-success' : 'bg-primary' }}" style="width: {{ $percent }}%;"></div>
                </div>
            </div>

            <div class="row text-center g-3 pt-2">
                <div class="col-md-4">
                    <div class="p-3 rounded-3 border" style="background: #f8fafc; border-color: #cbd5e1 !important;">
                        <small class="text-muted fw-bold d-block mb-1 text-uppercase">TOPLAM CRM AKTİVİTESİ</small>
                        <div class="fw-extrabold text-primary font-monospace fs-3">{{ $salesTarget->activities_count ?? count($completedOpportunities) }}</div>
                        <small class="text-muted fw-semibold">Dönem İçi Fırsat</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 rounded-3 border" style="background: #f8fafc; border-color: #cbd5e1 !important;">
                        <small class="text-muted fw-bold d-block mb-1 text-uppercase">KAZANILAN SATIŞ</small>
                        <div class="fw-extrabold text-success font-monospace fs-4">
                            {{ $isRevenue ? '₺' . number_format($completedVal, 0, ',', '.') : number_format($completedVal, 0, ',', '.') . ' Adet' }}
                        </div>
                        <small class="text-success fw-semibold">Başarıyla Kapanan</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 rounded-3 border" style="background: #f8fafc; border-color: #cbd5e1 !important;">
                        <small class="text-muted fw-bold d-block mb-1 text-uppercase">HEDEFE ULAŞMA ORANI</small>
                        <div class="fw-extrabold text-warning font-monospace fs-3">%{{ $percent }}</div>
                        <small class="text-warning fw-semibold">Performans Skoru</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- COMPLETED OPPORTUNITIES LIST CARD -->
    <div class="card custom-card-container mb-4">
        <div class="card-header bg-dark text-white p-3.5 d-flex align-items-center justify-content-between" style="background-color: #0f172a !important;">
            <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                <i class="ri-trophy-line text-warning fs-5"></i>Dönem İçi Kazanılan Fırsatlar ({{ $completedOpportunities->count() }})
            </h5>
        </div>
        <div class="card-body p-0">
            @if($completedOpportunities->isEmpty())
                <div class="text-center py-5">
                    <i class="ri-trophy-line display-4 text-warning mb-2 d-block"></i>
                    <h6 class="fw-bold text-dark mb-1">Henüz Kazanılan Fırsat Kaydı Yok</h6>
                    <p class="small text-muted mb-0">Bu hedef döneminde henüz kapatılmış bir fırsat bulunmamaktadır.</p>
                </div>
            @else
                <div class="list-group list-group-flush">
                    @foreach($completedOpportunities as $opp)
                        <div class="st-opp-item">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <div>
                                    <h6 class="fw-extrabold text-dark mb-1 fs-6">
                                        <a href="{{ route('opportunities.show', $opp) }}" class="text-dark text-decoration-none">
                                            🏆 {{ $opp->title }}
                                        </a>
                                    </h6>
                                    <div class="d-flex align-items-center gap-3 text-muted small fw-semibold">
                                        <span>🏢 {{ $opp->customer ?? 'Müşteri Belirtilmemiş' }}</span>
                                        <span class="font-monospace">📅 {{ $opp->updated_at ? $opp->updated_at->format('d.m.Y H:i') : '-' }}</span>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <span class="fs-5 fw-extrabold text-success font-monospace me-2">₺{{ number_format($opp->estimated_value_kdv ?? $opp->estimated_value ?? 0, 0, ',', '.') }}</span>
                                    <span class="badge bg-success text-white rounded-pill px-3 py-1.5 font-monospace fw-bold" style="background: #16a34a !important;">
                                        <i class="ri-checkbox-circle-fill me-1"></i>Kazanıldı
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

</div>
@endsection
