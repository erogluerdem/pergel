@extends('layouts.master')

@section('title', 'Fiyatlandırma & Matrah — ' . $unit->unit_no)

@push('styles')
<style>
    .unit-pricing-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #b45309 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.4) !important;
        padding: 32px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .unit-pricing-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #d97706, #2563eb, #16a34a, #d97706);
        background-size: 300% 100%;
        animation: unitPricingGlow 4s linear infinite;
    }

    @keyframes unitPricingGlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .unit-badge-pill {
        background: rgba(245, 158, 11, 0.25) !important;
        border: 1px solid rgba(245, 158, 11, 0.5) !important;
        color: #fef08a !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    .custom-kpi-card-pricing {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 20px 22px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .custom-kpi-card-pricing:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08) !important;
    }

    .custom-pricing-card {
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 18px !important;
        background: #ffffff !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04) !important;
        overflow: hidden;
        margin-bottom: 24px;
    }

    .sim-box {
        background: #f8fafc;
        border: 1.5px solid #cbd5e1;
        border-radius: 14px;
        padding: 18px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-3">

    @php
        $unitPricePerM2 = $unit->area > 0 ? ($unit->selling_price / $unit->area) : 0;
        $maxDiscountVal = $unit->max_discount_amount ?? ($unit->list_price * ($unit->max_discount_rate / 100));
    @endphp

    <!-- HERO HEADER BANNER -->
    <div class="unit-pricing-banner text-white">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill unit-badge-pill font-monospace">
                        <i class="ri-price-tag-3-fill text-warning fs-5"></i> Birim #{{ $unit->unit_no }}
                    </span>
                    <span class="badge bg-warning text-dark rounded-pill px-3 py-2 font-monospace fw-extrabold">
                        Fiyatlandırma & Matrah
                    </span>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2">Fiyatlandırma & Matrah Simülasyonu</h2>
                <p class="mb-0 fs-6" style="max-width: 780px; line-height: 1.6; color: #e2e8f0; font-weight: 500;">
                    <i class="ri-building-line text-warning me-1"></i> Proje: <strong class="text-white">{{ $unit->project->name ?? 'Projesiz' }}</strong> • {{ $unit->unit_type }} ({{ number_format($unit->area, 1, ',', '.') }} m²)
                </p>
            </div>

            <div class="col-lg-5 text-lg-end mt-3 mt-lg-0">
                <div class="d-flex align-items-center justify-content-lg-end gap-2 flex-wrap">
                    <a href="{{ route('units.show', $unit) }}" class="btn btn-outline-light rounded-pill px-3.5 py-2.5 fw-extrabold">
                        <i class="ri-eye-line me-1"></i>Detaya Dön
                    </a>
                    <a href="{{ route('units.edit', $unit) }}" class="btn btn-warning text-dark rounded-pill px-3.5 py-2.5 fw-extrabold shadow-sm">
                        <i class="ri-edit-fill me-1"></i>Fiyatları Düzenle
                    </a>
                    <a href="{{ route('units.index') }}" class="btn btn-outline-light rounded-pill px-3.5 py-2.5 fw-extrabold">
                        <i class="ri-arrow-left-line me-1"></i>Birimler Listesi
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- KPI SUMMARY ROW -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-pricing h-100 border-start border-primary border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">BRÜT LİSTE FİYATI</small>
                        <div class="fw-extrabold text-primary font-monospace" style="font-size: 1.6rem;">₺{{ number_format($unit->list_price, 0, ',', '.') }}</div>
                        <small class="text-primary fw-bold"><i class="ri-price-tag-line me-1"></i>KDV / Vergi Dahil</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #eff6ff !important; border: 1px solid #bfdbfe !important;">
                        <i class="ri-price-tag-3-fill fs-2" style="color: #2563eb !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-pricing h-100 border-start border-success border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">HEDEF NET SATIŞ</small>
                        <div class="fw-extrabold text-success font-monospace" style="font-size: 1.6rem;">₺{{ number_format($unit->selling_price, 0, ',', '.') }}</div>
                        <small class="text-success fw-bold"><i class="ri-checkbox-circle-fill me-1"></i>Sözleşme Taban Fiyatı</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0fdf4 !important; border: 1px solid #bbf7d0 !important;">
                        <i class="ri-money-dollar-circle-fill fs-2" style="color: #16a34a !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-pricing h-100 border-start border-info border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">M² BİRİM FİYATI</small>
                        <div class="fw-extrabold text-info font-monospace" style="font-size: 1.4rem;">₺{{ number_format($unitPricePerM2, 0, ',', '.') }} / m²</div>
                        <small class="text-info fw-bold"><i class="ri-ruler-2-line me-1"></i>Net Kullanım m² Fiyatı</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0f9ff !important; border: 1px solid #bae6fd !important;">
                        <i class="ri-ruler-2-fill fs-2" style="color: #0284c7 !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-pricing h-100 border-start border-warning border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">MAX İSKONTO LİMİTİ</small>
                        <div class="fw-extrabold text-warning font-monospace" style="font-size: 1.4rem;">₺{{ number_format($maxDiscountVal, 0, ',', '.') }}</div>
                        <small class="text-warning fw-bold"><i class="ri-percent-line me-1"></i>%{{ number_format($unit->max_discount_rate, 0) }} İskonto Marjı</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #fffbeb !important; border: 1px solid #fde68a !important;">
                        <i class="ri-percent-fill fs-2" style="color: #d97706 !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- PAYMENT SIMULATION MATRIX -->
    <div class="card custom-pricing-card">
        <div class="card-header bg-dark text-white p-3.5" style="background-color: #0f172a !important;">
            <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                <i class="ri-calculator-line text-warning fs-5"></i>Ödeme Planı & Peşinat Matrah Simülasyonu
            </h5>
        </div>

        <div class="card-body p-4">
            <div class="row g-4">
                <!-- 20% Down Payment -->
                <div class="col-md-4">
                    <div class="sim-box">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-primary text-white rounded-pill px-3 py-1 font-monospace fw-bold">%20 PEŞİNAT</span>
                            <i class="ri-wallet-3-line fs-4 text-primary"></i>
                        </div>
                        <h4 class="fw-extrabold text-dark font-monospace mb-1">₺{{ number_format($unit->selling_price * 0.20, 0, ',', '.') }}</h4>
                        <small class="text-muted fw-semibold d-block mb-3">Kalan Bakiye: ₺{{ number_format($unit->selling_price * 0.80, 0, ',', '.') }}</small>
                        <div class="p-2.5 rounded bg-white border small fw-bold text-dark">
                            • 12 Ay Taksit: ₺{{ number_format(($unit->selling_price * 0.80) / 12, 0, ',', '.') }} / ay<br>
                            • 24 Ay Taksit: ₺{{ number_format(($unit->selling_price * 0.80) / 24, 0, ',', '.') }} / ay
                        </div>
                    </div>
                </div>

                <!-- 35% Down Payment -->
                <div class="col-md-4">
                    <div class="sim-box" style="border-color: #d97706 !important; background: #fffbeb !important;">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-warning text-dark rounded-pill px-3 py-1 font-monospace fw-bold">%35 PEŞİNAT (ÖNERİLEN)</span>
                            <i class="ri-star-fill fs-4 text-warning"></i>
                        </div>
                        <h4 class="fw-extrabold text-dark font-monospace mb-1">₺{{ number_format($unit->selling_price * 0.35, 0, ',', '.') }}</h4>
                        <small class="text-muted fw-semibold d-block mb-3">Kalan Bakiye: ₺{{ number_format($unit->selling_price * 0.65, 0, ',', '.') }}</small>
                        <div class="p-2.5 rounded bg-white border small fw-bold text-dark">
                            • 12 Ay Taksit: ₺{{ number_format(($unit->selling_price * 0.65) / 12, 0, ',', '.') }} / ay<br>
                            • 24 Ay Taksit: ₺{{ number_format(($unit->selling_price * 0.65) / 24, 0, ',', '.') }} / ay
                        </div>
                    </div>
                </div>

                <!-- 50% Down Payment -->
                <div class="col-md-4">
                    <div class="sim-box">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-success text-white rounded-pill px-3 py-1 font-monospace fw-bold" style="background: #16a34a !important;">%50 PEŞİNAT</span>
                            <i class="ri-bank-card-line fs-4 text-success"></i>
                        </div>
                        <h4 class="fw-extrabold text-dark font-monospace mb-1">₺{{ number_format($unit->selling_price * 0.50, 0, ',', '.') }}</h4>
                        <small class="text-muted fw-semibold d-block mb-3">Kalan Bakiye: ₺{{ number_format($unit->selling_price * 0.50, 0, ',', '.') }}</small>
                        <div class="p-2.5 rounded bg-white border small fw-bold text-dark">
                            • 12 Ay Taksit: ₺{{ number_format(($unit->selling_price * 0.50) / 12, 0, ',', '.') }} / ay<br>
                            • 24 Ay Taksit: ₺{{ number_format(($unit->selling_price * 0.50) / 24, 0, ',', '.') }} / ay
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                <a href="{{ route('units.show', $unit) }}" class="btn btn-outline-dark rounded-pill px-4 py-2.5 fw-extrabold">
                    Birim Detayına Dön
                </a>
                <a href="{{ route('units.edit', $unit) }}" class="btn btn-warning text-dark rounded-pill px-5 py-2.5 fw-extrabold shadow-sm">
                    <i class="ri-edit-fill me-1"></i>Fiyatlandırma Parametrelerini Güncelle
                </a>
            </div>
        </div>
    </div>

</div>
@endsection
