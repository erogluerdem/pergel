@extends('layouts.master')

@section('title', 'Süresi Dolmak Üzere Olan Opsiyonlar')

@push('styles')
<style>
    .opt-expire-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #b45309 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.4) !important;
        padding: 32px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .opt-expire-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #d97706, #ef4444, #d97706);
        background-size: 300% 100%;
        animation: optExpireGlow 4s linear infinite;
    }

    @keyframes optExpireGlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .opt-badge-pill {
        background: rgba(245, 158, 11, 0.25) !important;
        border: 1px solid rgba(245, 158, 11, 0.5) !important;
        color: #fef08a !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    .custom-kpi-card-expire {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 20px 22px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
    }

    .custom-card-container {
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 18px !important;
        background: #ffffff !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04) !important;
        overflow: hidden;
    }

    .custom-expire-table thead,
    .custom-expire-table thead tr,
    .custom-expire-table thead th {
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

    .custom-expire-table td {
        padding: 16px !important;
        vertical-align: middle !important;
        border-bottom: 1px solid #cbd5e1 !important;
        color: #0f172a !important;
        font-weight: 600 !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-3">

    <!-- HERO HEADER BANNER -->
    <div class="opt-expire-banner text-white">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill opt-badge-pill">
                        <i class="ri-alarm-warning-fill text-warning fs-5"></i> CRM & UYARI SİSTEMİ
                    </span>
                    <span class="badge bg-danger text-white fw-extrabold px-3 py-2 rounded-pill font-monospace" style="background: #ef4444 !important;">
                        {{ $expiringOptions->count() }} Süresi Dolacak Opsiyon
                    </span>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2">Süresi Dolmak Üzere Olan Opsiyonlar</h2>
                <p class="mb-0 fs-6" style="max-width: 780px; line-height: 1.6; color: #e2e8f0; font-weight: 500;">
                    24 saat içinde süresi dolacak kritik gayrimenkul opsiyonlarını inceleyin, uzatın veya projeye dönüştürün.
                </p>
            </div>

            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <a href="{{ route('options.index') }}" class="btn btn-outline-light rounded-pill px-4 py-2.5 fw-extrabold d-inline-flex align-items-center gap-2">
                    <i class="ri-arrow-left-line fs-5"></i>Tüm Opsiyonlar Listesi
                </a>
            </div>
        </div>
    </div>

    <!-- SUMMARY KPI CARDS ROW -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card custom-kpi-card-expire h-100 border-start border-warning border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">KRİTİK OPSİYON SAYISI</small>
                        <div class="fw-extrabold text-warning font-monospace" style="font-size: 1.8rem;">{{ $expiringOptions->count() }} Kayıt</div>
                        <small class="text-warning fw-bold"><i class="ri-time-line me-1"></i>Son 24 Saat İçinde Dolacak</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #fffbeb !important; border: 1px solid #fde68a !important;">
                        <i class="ri-time-fill fs-2" style="color: #d97706 !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card custom-kpi-card-expire h-100 border-start border-danger border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">EKSİLEN ZAMAN AŞIMI</small>
                        <div class="fw-extrabold text-danger font-monospace" style="font-size: 1.8rem;">Acil Eylem</div>
                        <small class="text-danger fw-bold"><i class="ri-alarm-line me-1"></i>Takip & İletişim Gerekli</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #fff1f2 !important; border: 1px solid #fecdd3 !important;">
                        <i class="ri-error-warning-fill fs-2" style="color: #ef4444 !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card custom-kpi-card-expire h-100 border-start border-success border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">OTOMASYON DURUMU</small>
                        <div class="fw-extrabold text-success font-monospace" style="font-size: 1.8rem;">Otomatik</div>
                        <small class="text-success fw-bold"><i class="ri-shield-check-line me-1"></i>Gece Otomatik Zaman Aşımı</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0fdf4 !important; border: 1px solid #bbf7d0 !important;">
                        <i class="ri-shield-check-fill fs-2" style="color: #16a34a !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- EXPIRING TABLE CARD -->
    <div class="card custom-card-container mb-5">
        <div class="card-header bg-dark text-white p-3.5 d-flex align-items-center justify-content-between" style="background-color: #0f172a !important;">
            <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                <i class="ri-time-line text-warning fs-5"></i>Süresi Dolan / Dolmak Üzere Olan Kayıtlar
            </h5>
            <span class="badge bg-warning text-dark font-monospace fw-extrabold px-3 py-1.5 rounded-pill" style="font-size: 11px;">
                {{ $expiringOptions->count() }} Kayıt
            </span>
        </div>

        <div class="card-body p-0">
            @if($expiringOptions->isEmpty())
                <div class="text-center py-5">
                    <i class="ri-checkbox-circle-line display-3 text-success mb-2 d-block"></i>
                    <h5 class="fw-extrabold text-dark mb-1">Acil Uyarılı Opsiyon Yok</h5>
                    <p class="text-muted mb-3">Şu anda süresi dolmak üzere olan herhangi bir opsiyon bulunmamaktadır.</p>
                    <a href="{{ route('options.index') }}" class="btn btn-warning text-dark fw-extrabold rounded-pill px-4 py-2">
                        Tüm Opsiyonlara Git
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table custom-expire-table align-middle mb-0">
                        <thead style="background-color: #0f172a !important;">
                            <tr style="background-color: #0f172a !important;">
                                <th class="ps-4 text-warning" style="background-color: #0f172a !important; color: #fef08a !important; font-weight: 800;">Gayrimenkul / Varlık</th>
                                <th class="text-white" style="background-color: #0f172a !important; color: #ffffff !important; font-weight: 800;">Opsiyon Sahibi Müşteri</th>
                                <th class="text-white" style="background-color: #0f172a !important; color: #ffffff !important; font-weight: 800;">Bitme Tarihi</th>
                                <th class="text-center text-white" style="background-color: #0f172a !important; color: #ffffff !important; font-weight: 800;">Kalan Süre</th>
                                <th class="pe-4 text-end text-white" style="background-color: #0f172a !important; color: #ffffff !important; font-weight: 800;">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($expiringOptions as $option)
                                <tr>
                                    <td class="ps-4 py-3">
                                        <div class="fw-extrabold text-dark fs-6">🏢 {{ $option->asset?->name ?? 'Varlık Belirtilmemiş' }}</div>
                                        @if($option->asset)
                                            <small class="text-muted fw-semibold">📍 {{ $option->asset->location ?? 'Konum yok' }}</small>
                                        @endif
                                    </td>
                                    <td class="fw-extrabold text-dark fs-6">
                                        👤 {{ $option->customer?->name ?? 'Müşteri Belirtilmemiş' }}
                                    </td>
                                    <td class="font-monospace fw-bold text-danger" style="font-size: 13px;">
                                        📅 {{ $option->expiry_date ? $option->expiry_date->format('d.m.Y H:i') : '-' }}
                                    </td>
                                    <td class="text-center font-monospace">
                                        <span class="badge bg-warning text-dark rounded-pill px-3 py-1.5 font-monospace fw-extrabold">
                                            ⚠️ 1 Gün İçi
                                        </span>
                                    </td>
                                    <td class="pe-4 text-end">
                                        <div class="d-inline-flex gap-1">
                                            <a href="{{ route('options.show', $option) }}" class="btn btn-sm btn-warning text-dark rounded-circle p-2" title="Detay İncele">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                            <form action="{{ route('options.convert-to-project', $option) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success rounded-pill px-3 py-1.5 fw-bold" style="background: #16a34a !important;" title="Projeye Dönüştür">
                                                    <i class="ri-rocket-fill me-1"></i>Satışa Dönüştür
                                                </button>
                                            </form>
                                            <form action="{{ route('options.cancel', $option) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-danger rounded-pill px-3 py-1.5 fw-bold" style="background: #ef4444 !important;" title="İptal Et">
                                                    <i class="ri-close-circle-line me-1"></i>İptal
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

</div>
@endsection
