@extends('layouts.master')

@section('title', 'Satış Hedefleri & Performans Portalı')

@push('styles')
<style>
    .st-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #b45309 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.4) !important;
        padding: 32px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .st-hero-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #d97706, #2563eb, #16a34a, #d97706);
        background-size: 300% 100%;
        animation: stGlow 4s linear infinite;
    }

    @keyframes stGlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .st-badge-pill {
        background: rgba(245, 158, 11, 0.25) !important;
        border: 1px solid rgba(245, 158, 11, 0.5) !important;
        color: #fef08a !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    .custom-kpi-card-st {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 20px 22px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .custom-kpi-card-st:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08) !important;
    }

    .target-card-custom {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 18px !important;
        padding: 24px !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05) !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        position: relative;
    }

    .target-card-custom:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
        border-color: #d97706 !important;
    }

    .owner-avatar-pill {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: #0f172a;
        color: #fef08a;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        border: 2px solid #d97706;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-3">

    <!-- HERO HEADER BANNER -->
    <div class="st-hero-banner text-white">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill st-badge-pill">
                        <i class="ri-target-fill text-warning fs-5"></i> CRM & SATIŞ PERFORMANS YÖNETİMİ
                    </span>
                    <span class="badge bg-success text-white fw-extrabold px-3 py-2 rounded-pill font-monospace" style="background: #16a34a !important;">
                        {{ $targets->total() ?? count($targets) }} Satış Hedefi
                    </span>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2">Satış Hedefleri & Performans</h2>
                <p class="mb-0 fs-6" style="max-width: 780px; line-height: 1.6; color: #e2e8f0; font-weight: 500;">
                    Ekibinizin dönemlik ciro ve satış adedi hedeflerini, gerçekleşme oranlarını ve kalan farklarını anlık takip edin.
                </p>
            </div>

            <div class="col-lg-5 text-lg-end mt-3 mt-lg-0">
                <div class="d-flex align-items-center justify-content-lg-end gap-2 flex-wrap">
                    @if(Route::has('sales-targets.dashboard'))
                        <a href="{{ route('sales-targets.dashboard') }}" class="btn btn-outline-light rounded-pill px-3.5 py-2 fw-extrabold d-inline-flex align-items-center gap-2">
                            <i class="ri-dashboard-3-line fs-5"></i>Analiz Dashboard
                        </a>
                    @endif
                    <a href="{{ route('sales-targets.create') }}" class="btn btn-warning text-dark rounded-pill px-4 py-2.5 fw-extrabold d-inline-flex align-items-center gap-2 shadow-sm">
                        <i class="ri-add-circle-fill fs-5"></i>+ Yeni Hedef Ekle
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- SUMMARY KPI CARDS ROW -->
    @php
        $totalRevenueTarget = $targets->where('target_type', 'revenue')->sum('target_amount');
        $totalRevenueCompleted = $targets->where('target_type', 'revenue')->sum(function($t) { return $t->completed_revenue ?? 0; });
        $totalUnitTarget = $targets->where('target_type', 'unit')->sum('target_amount');
        $avgProgress = $targets->count() > 0 ? round($targets->avg(function($t) { return $t->completion_percentage ?? 0; }), 1) : 0;
    @endphp

    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-st h-100 border-start border-primary border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">TOPLAM HEDEF CİRO</small>
                        <div class="fw-extrabold text-primary font-monospace" style="font-size: 1.6rem;">₺{{ number_format($totalRevenueTarget, 0, ',', '.') }}</div>
                        <small class="text-primary fw-bold"><i class="ri-money-dollar-circle-line me-1"></i>Dönemlik Ciro Hedefleri</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #eff6ff !important; border: 1px solid #bfdbfe !important;">
                        <i class="ri-money-dollar-circle-line fs-2" style="color: #2563eb !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-st h-100 border-start border-success border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">GERÇEKLEŞEN CİRO</small>
                        <div class="fw-extrabold text-success font-monospace" style="font-size: 1.6rem;">₺{{ number_format($totalRevenueCompleted, 0, ',', '.') }}</div>
                        <small class="text-success fw-bold"><i class="ri-checkbox-circle-fill me-1"></i>Tamamlanan Satışlar</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0fdf4 !important; border: 1px solid #bbf7d0 !important;">
                        <i class="ri-coins-fill fs-2" style="color: #16a34a !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-st h-100 border-start border-warning border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">TOPLAM HEDEF ADET</small>
                        <div class="fw-extrabold text-dark font-monospace" style="font-size: 1.6rem;">{{ number_format($totalUnitTarget, 0, ',', '.') }} Adet</div>
                        <small class="text-warning fw-bold"><i class="ri-building-line me-1"></i>Bağımsız Bölüm Hedefleri</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #fffbeb !important; border: 1px solid #fde68a !important;">
                        <i class="ri-building-line fs-2" style="color: #d97706 !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-st h-100 border-start border-info border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">ORTALAMA İLERLEME</small>
                        <div class="fw-extrabold text-info font-monospace" style="font-size: 1.6rem;">%{{ $avgProgress }}</div>
                        <small class="text-info fw-bold"><i class="ri-pie-chart-2-fill me-1"></i>Genel Başarı Oranı</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0f9ff !important; border: 1px solid #bae6fd !important;">
                        <i class="ri-pie-chart-line fs-2" style="color: #0284c7 !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- TARGET CARDS GRID -->
    @if ($targets->isEmpty())
        <div class="card border-1 border-secondary shadow-sm p-5 text-center rounded-4">
            <i class="ri-target-line display-3 text-warning mb-2 d-block"></i>
            <h4 class="fw-bold text-dark mb-1">Henüz Satış Hedefi Tanımlanmamış</h4>
            <p class="text-muted mb-3">Ekibiniz için ciro veya satış adedi hedefleri belirleyerek başarı oranlarını anlık izleyebilirsiniz.</p>
            <div>
                <a href="{{ route('sales-targets.create') }}" class="btn btn-warning text-dark fw-extrabold rounded-pill px-4 py-2">
                    <i class="ri-add-circle-fill me-1"></i>İlk Hedefi Ekleyin
                </a>
            </div>
        </div>
    @else
        <div class="row g-4 mb-4">
            @foreach($targets as $target)
                @php
                    $isRevenue = $target->target_type === 'revenue';
                    $completedVal = $isRevenue ? ($target->completed_revenue ?? 0) : ($target->completed_units ?? 0);
                    $targetVal = $target->target_amount;
                    $percent = min(100, max(0, round($target->completion_percentage ?? 0, 1)));
                    $userName = $target->user->name ?? 'Tanımsız Kullanıcı';
                    $userInitials = mb_substr($userName, 0, 2);
                @endphp
                <div class="col-lg-6">
                    <div class="target-card-custom h-100">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="owner-avatar-pill">
                                    {{ strtoupper($userInitials) }}
                                </div>
                                <div>
                                    <h5 class="fw-extrabold text-dark mb-0 fs-6">{{ $userName }}</h5>
                                    <small class="text-muted font-monospace fw-bold">
                                        📅 {{ $target->period_start ? $target->period_start->format('d.m.Y') : '-' }} — {{ $target->period_end ? $target->period_end->format('d.m.Y') : '-' }}
                                    </small>
                                </div>
                            </div>
                            <div class="text-end">
                                <span class="badge {{ $isRevenue ? 'bg-primary' : 'bg-success' }} rounded-pill px-3 py-1.5 font-monospace fw-bold" style="font-size: 11px;">
                                    {{ $isRevenue ? 'Ciro Hedefi' : 'Satış Adedi' }}
                                </span>
                                <span class="badge bg-dark text-warning rounded-pill px-3 py-1.5 font-monospace fw-bold ms-1" style="font-size: 11px;">
                                    {{ strtoupper($target->status ?? 'AKTİF') }}
                                </span>
                            </div>
                        </div>

                        @if($target->description)
                            <div class="p-3 rounded-3 mb-3" style="background: #f8fafc; border-left: 4px solid #d97706; color: #0f172a; font-weight: 600; font-size: 0.88rem;">
                                "{{ $target->description }}"
                            </div>
                        @endif

                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <small class="text-muted fw-extrabold text-uppercase font-size-11">İlerleme Oranı</small>
                                <strong class="font-monospace {{ $percent >= 100 ? 'text-success' : 'text-primary' }}">%{{ $percent }}</strong>
                            </div>
                            <div class="progress" style="height: 10px; background: #e2e8f0; border-radius: 5px;">
                                <div class="progress-bar {{ $percent >= 100 ? 'bg-success' : 'bg-primary' }}" style="width: {{ $percent }}%;"></div>
                            </div>
                        </div>

                        <div class="row text-center g-2 p-3 rounded-3 mb-3" style="background: #f8fafc; border: 1px solid #cbd5e1;">
                            <div class="col-4 border-end">
                                <small class="text-muted d-block font-size-11 fw-bold">HEDEF</small>
                                <strong class="text-dark font-monospace fs-6">
                                    {{ $isRevenue ? '₺' . number_format($targetVal, 0, ',', '.') : number_format($targetVal, 0, ',', '.') . ' Adet' }}
                                </strong>
                            </div>
                            <div class="col-4 border-end">
                                <small class="text-muted d-block font-size-11 fw-bold">GERÇEKLEŞEN</small>
                                <strong class="text-success font-monospace fs-6">
                                    {{ $isRevenue ? '₺' . number_format($completedVal, 0, ',', '.') : number_format($completedVal, 0, ',', '.') . ' Adet' }}
                                </strong>
                            </div>
                            <div class="col-4">
                                <small class="text-muted d-block font-size-11 fw-bold">KALAN FARK</small>
                                <strong class="text-warning font-monospace fs-6">
                                    {{ $isRevenue ? '₺' . number_format($target->remaining ?? 0, 0, ',', '.') : number_format($target->remaining ?? 0, 0, ',', '.') . ' Adet' }}
                                </strong>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                            <small class="text-muted fw-semibold">
                                🕒 {{ $target->updated_at ? $target->updated_at->diffForHumans() : 'Şimdi' }}
                            </small>
                            <div class="d-inline-flex gap-1">
                                <a href="{{ route('sales-targets.show', $target) }}" class="btn btn-sm btn-outline-dark rounded-circle p-2" title="Detay">
                                    <i class="ri-eye-line"></i>
                                </a>
                                <a href="{{ route('sales-targets.edit', $target) }}" class="btn btn-sm btn-warning text-dark rounded-circle p-2" title="Düzenle">
                                    <i class="ri-pencil-line"></i>
                                </a>
                                <form action="{{ route('sales-targets.destroy', $target) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle p-2" onclick="return confirmDelete(this, 'Satış Hedefini Sil?', 'Bu satış hedefini silmek istediğinizden emin misiniz?')" title="Sil">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-3">
            {{ $targets->links() }}
        </div>
    @endif

</div>
@endsection
