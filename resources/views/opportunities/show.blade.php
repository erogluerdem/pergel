@extends('layouts.master')

@section('title', $opp->title . ' - CRM Fırsat Detayı')

@push('styles')
<style>
    .opp-show-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #b45309 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.4) !important;
        padding: 32px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .opp-show-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #d97706, #2563eb, #16a34a, #d97706);
        background-size: 300% 100%;
        animation: oppShowGlow 4s linear infinite;
    }

    @keyframes oppShowGlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .opp-stage-pill {
        background: rgba(245, 158, 11, 0.25) !important;
        border: 1px solid rgba(245, 158, 11, 0.5) !important;
        color: #fef08a !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
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
        overflow: visible !important;
    }

    .custom-card-container .card-header {
        border-top-left-radius: 16px !important;
        border-top-right-radius: 16px !important;
    }

    .dropdown-menu {
        z-index: 1050 !important;
    }

    .owner-avatar-pill {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: #0f172a;
        color: #fef08a;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        border: 2px solid #d97706;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-3">

    @php
        $est = (float)($opp->estimated_value ?: 0);
        if (!is_null($opp->estimated_value_kdv)) {
            $estKdv = (float)$opp->estimated_value_kdv;
            $kdvAmount = (float)$opp->kdv_amount;
            $kdvPercent = $opp->kdv_percent ?? \App\Services\FinanceService::resolveKdvPercent(null);
        } else {
            $kdvPercent = \App\Services\FinanceService::resolveKdvPercent($opp->kdv_percent ?? null);
            $kdvAmount = \App\Services\FinanceService::kdvAmount($est, $kdvPercent);
            $estKdv = \App\Services\FinanceService::applyKdv($est, $kdvPercent);
        }

        $stageBadges = [
            'lead' => 'bg-secondary text-white',
            'qualification' => 'bg-info text-white',
            'proposal' => 'bg-warning text-dark',
            'negotiation' => 'bg-primary text-white',
            'won' => 'bg-success text-white',
            'lost' => 'bg-danger text-white'
        ];
        $stageLabels = [
            'lead' => 'Fırsat Belirlendi',
            'qualification' => 'Değerlendirme',
            'proposal' => 'Teklif Hazırlığı',
            'negotiation' => 'Teklif Sunuldu',
            'won' => 'Kazanıldı',
            'lost' => 'Kaybedildi'
        ];

        $daysUntilClose = $opp->expected_close_date ? \Carbon\Carbon::parse($opp->expected_close_date)->diffInDays(now(), false) : null;
        $isOverdue = $daysUntilClose !== null && $daysUntilClose < 0;
        $prob = intval($opp->probability ?? 0);
    @endphp

    <!-- HERO HEADER BANNER -->
    <div class="opp-show-banner text-white">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill opp-stage-pill">
                        <i class="ri-lightbulb-fill text-warning fs-5"></i> CRM FIRSAT KARTI #{{ $opp->id }}
                    </span>
                    <span class="badge rounded-pill px-3.5 py-2 fw-extrabold font-monospace {{ $stageBadges[$opp->stage] ?? 'bg-secondary text-white' }}" style="font-size: 12px;">
                        AŞAMA: {{ $stageLabels[$opp->stage] ?? ucfirst($opp->stage) }}
                    </span>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2">{{ $opp->title }}</h2>
                <p class="mb-0 fs-6 d-flex align-items-center gap-2" style="color: #fef08a; font-weight: 700;">
                    <i class="ri-building-line fs-5"></i> Müşteri / Yatırımcı: {{ $opp->customer ?? 'Belirtilmemiş' }}
                </p>
            </div>

            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <div class="d-flex align-items-center justify-content-lg-end gap-2 flex-wrap">
                    <a href="{{ route('opportunities.list') }}" class="btn btn-outline-light rounded-pill px-3.5 py-2 fw-extrabold">
                        <i class="ri-arrow-left-line me-1"></i>Listeye Dön
                    </a>
                    <a href="{{ route('opportunities.edit', $opp->id) }}" class="btn btn-outline-light rounded-pill px-3.5 py-2 fw-extrabold">
                        <i class="ri-pencil-line me-1"></i>Düzenle
                    </a>
                    <a href="{{ route('feasibility.create') }}?opportunity_id={{ $opp->id }}" class="btn btn-warning text-dark rounded-pill px-4 py-2.5 fw-extrabold shadow-sm">
                        <i class="ri-calculator-fill me-1"></i>Fizibilite Başlat
                    </a>
                    @if($opp->stage == 'won')
                        <form method="post" action="{{ route('opportunities.convert', $opp->id) }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success text-white rounded-pill px-4 py-2.5 fw-extrabold shadow-sm" style="background: #16a34a !important;">
                                <i class="ri-rocket-fill me-1"></i>Projeye Dönüştür
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- SUMMARY KPI CARDS ROW -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-show h-100 border-start border-warning border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">TAHMİNİ BÜTÇE (KDV HARİÇ)</small>
                        <div class="fw-extrabold text-dark font-monospace" style="font-size: 1.6rem;">₺{{ number_format($est, 0, ',', '.') }}</div>
                        <small class="text-warning fw-bold"><i class="ri-add-line me-1"></i>KDV: ₺{{ number_format($kdvAmount, 0, ',', '.') }}</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #fffbeb !important; border: 1px solid #fde68a !important;">
                        <i class="ri-money-dollar-circle-line fs-2" style="color: #d97706 !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-show h-100 border-start border-success border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">TOPLAM TOPLAM (KDV DAHİL)</small>
                        <div class="fw-extrabold text-success font-monospace" style="font-size: 1.6rem;">₺{{ number_format($estKdv, 0, ',', '.') }}</div>
                        <small class="text-success fw-bold"><i class="ri-percent-line me-1"></i>Oran: %{{ $kdvPercent }} KDV</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0fdf4 !important; border: 1px solid #bbf7d0 !important;">
                        <i class="ri-coins-fill fs-2" style="color: #16a34a !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-show h-100 border-start border-primary border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="w-100 me-2">
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">KAZANMA OLASILIĞI</small>
                        <div class="fw-extrabold text-primary font-monospace" style="font-size: 1.6rem;">%{{ $prob }}</div>
                        <div class="progress mt-1" style="height: 6px; background: #e2e8f0;">
                            <div class="progress-bar {{ $prob >= 70 ? 'bg-success' : ($prob >= 40 ? 'bg-warning' : 'bg-danger') }}" style="width: {{ $prob }}%;"></div>
                        </div>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #eff6ff !important; border: 1px solid #bfdbfe !important;">
                        <i class="ri-pie-chart-2-fill fs-2" style="color: #2563eb !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-show h-100 border-start border-info border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase tracking-wider d-block mb-1" style="font-size: 11px;">HEDEF KAPANIŞ TARİHİ</small>
                        <div class="fw-extrabold text-dark font-monospace" style="font-size: 1.3rem;">
                            {{ $opp->expected_close_date ? \Carbon\Carbon::parse($opp->expected_close_date)->format('d.m.Y') : 'Belirtilmemiş' }}
                        </div>
                        @if($daysUntilClose !== null)
                            @if($isOverdue)
                                <small class="text-danger fw-bold"><i class="ri-alert-fill me-1"></i>{{ abs($daysUntilClose) }} gün gecikti</small>
                            @else
                                <small class="text-success fw-bold"><i class="ri-time-fill me-1"></i>{{ $daysUntilClose }} gün kaldı</small>
                            @endif
                        @endif
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0f9ff !important; border: 1px solid #bae6fd !important;">
                        <i class="ri-calendar-event-fill fs-2" style="color: #0284c7 !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN DETAILS GRID -->
    <div class="row g-4 mb-4">
        <!-- LEFT COLUMN: DETAILS & FEASIBILITIES -->
        <div class="col-lg-8">
            <!-- OPPORTUNITY DETAILS CARD -->
            <div class="card custom-card-container mb-4">
                <div class="card-header bg-dark text-white p-3.5" style="background-color: #0f172a !important;">
                    <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                        <i class="ri-file-info-line text-warning fs-5"></i>Fırsat Bilgileri & Detaylar
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <small class="text-muted fw-bold d-block">Müşteri / Yatırımcı Firma</small>
                            <p class="fw-extrabold text-dark fs-6 mb-3">🏢 {{ $opp->customer ?? 'Belirtilmemiş' }}</p>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted fw-bold d-block">Kayıt Kaynağı</small>
                            <p class="fw-extrabold text-dark fs-6 mb-3">🌐 {{ $opp->source ?? 'Belirtilmemiş' }}</p>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted fw-bold d-block">Sorumlu Yönetici</small>
                            <div class="d-flex align-items-center gap-2 mt-1 mb-3">
                                <div class="owner-avatar-pill">
                                    {{ $opp->owner ? strtoupper(substr($opp->owner->name, 0, 1)) : '?' }}
                                </div>
                                <span class="fw-extrabold text-dark fs-6">{{ $opp->owner->name ?? 'Atanmamış' }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted fw-bold d-block">Oluşturulma & Güncelleme</small>
                            <p class="fw-extrabold text-dark fs-6 mb-3">📅 {{ $opp->created_at->format('d.m.Y H:i') }}</p>
                        </div>
                    </div>

                    @if($opp->description)
                        <div class="mt-2 pt-3 border-top">
                            <small class="text-muted fw-bold d-block mb-2">Açıklama & Detaylı Notlar</small>
                            <div class="p-3 rounded-3" style="background: #f8fafc; border: 1.5px solid #cbd5e1; color: #0f172a; font-weight: 600; line-height: 1.6;">
                                {{ $opp->description }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- FEASIBILITY STUDIES CARD -->
            <div class="card custom-card-container mb-4">
                <div class="card-header bg-dark text-white p-3.5 d-flex align-items-center justify-content-between" style="background-color: #0f172a !important;">
                    <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                        <i class="ri-calculator-line text-warning fs-5"></i>İlişkili Fizibilite Çalışmaları
                    </h5>
                    <a href="{{ route('feasibility.create') }}?opportunity_id={{ $opp->id }}" class="btn btn-sm btn-warning text-dark rounded-pill px-3 fw-bold">
                        + Yeni Fizibilite
                    </a>
                </div>
                <div class="card-body p-4">
                    @if($feas->isEmpty())
                        <div class="text-center py-4">
                            <i class="ri-calculator-line display-4 text-warning mb-2 d-block"></i>
                            <h6 class="fw-bold text-dark">Henüz Fizibilite Çalışması Yok</h6>
                            <p class="small text-muted mb-0">Bu fırsata bağlı bir maliyet-fizibilite analizi oluşturmak için butona tıklayın.</p>
                        </div>
                    @else
                        <div class="row g-3">
                            @foreach($feas as $f)
                                <div class="col-md-6">
                                    <div class="p-3 rounded-3 border d-flex align-items-center justify-content-between" style="background: #f8fafc; border-color: #cbd5e1 !important;">
                                        <div>
                                            <a href="{{ route('feasibility.show', $f->id) }}" class="fw-extrabold text-dark text-decoration-none d-block">
                                                📊 {{ $f->name }}
                                            </a>
                                            <small class="text-muted font-monospace">{{ $f->created_at->format('d.m.Y') }}</small>
                                        </div>
                                        <a href="{{ route('feasibility.show', $f->id) }}" class="btn btn-sm btn-outline-dark rounded-circle p-2">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: ACTIONS & STAGE CONTROL -->
        <div class="col-lg-4">
            <!-- QUICK ACTIONS CARD -->
            <div class="card custom-card-container mb-4">
                <div class="card-header bg-dark text-white p-3.5" style="background-color: #0f172a !important;">
                    <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                        <i class="ri-rocket-line text-warning fs-5"></i>Hızlı İşlemler & Aşama
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="d-grid gap-2">
                        <!-- Stage Dropdown -->
                        <div class="dropdown">
                            <button class="btn btn-warning text-dark fw-extrabold w-100 rounded-pill py-2.5 dropdown-toggle shadow-sm" type="button" data-bs-toggle="dropdown">
                                <i class="ri-arrow-right-circle-line me-1"></i>Aşama Değiştir
                            </button>
                            <ul class="dropdown-menu w-100 shadow-lg">
                                <li><a class="dropdown-item fw-bold" href="#" onclick="changeStage('lead')">Fırsat Belirlendi</a></li>
                                <li><a class="dropdown-item fw-bold" href="#" onclick="changeStage('qualification')">Değerlendirme</a></li>
                                <li><a class="dropdown-item fw-bold" href="#" onclick="changeStage('proposal')">Teklif Hazırlığı</a></li>
                                <li><a class="dropdown-item fw-bold" href="#" onclick="changeStage('negotiation')">Teklif Sunuldu</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item fw-extrabold text-success" href="#" onclick="changeStage('won')">🏆 Kazanıldı</a></li>
                                <li><a class="dropdown-item fw-extrabold text-danger" href="#" onclick="changeStage('lost')">❌ Kaybedildi</a></li>
                            </ul>
                        </div>

                        <a href="{{ route('opportunities.edit', $opp->id) }}" class="btn btn-outline-dark rounded-pill py-2.5 fw-bold">
                            <i class="ri-pencil-line me-1"></i>Fırsat Bilgilerini Düzenle
                        </a>
                        <a href="{{ route('opportunities.list') }}" class="btn btn-outline-secondary rounded-pill py-2.5 fw-bold">
                            <i class="ri-arrow-left-line me-1"></i>Fırsat Listesine Dön
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
function changeStage(newStage) {
    if (confirm('Fırsat aşamasını değiştirmek istediğinizden emin misiniz?')) {
        fetch(`/opportunities/{{ $opp->id }}/move`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ stage: newStage })
        })
        .then(res => res.json())
        .then(data => {
            if (data.ok || data.success) {
                location.reload();
            } else {
                alert('Hata: ' + (data.message || 'Güncellenemedi'));
            }
        })
        .catch(err => {
            console.error(err);
            alert('Sunucu ile iletişim hatası.');
        });
    }
}
</script>
@endpush
