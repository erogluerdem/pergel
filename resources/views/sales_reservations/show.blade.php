@extends('layouts.master')

@section('title', 'Satış Opsiyonu Detayı — ' . ($salesReservation->option_no ?? 'Opsiyon'))

@push('styles')
<style>
    .sr-show-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #b45309 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.4) !important;
        padding: 32px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .sr-show-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #d97706, #2563eb, #16a34a, #d97706);
        background-size: 300% 100%;
        animation: srShowGlow 4s linear infinite;
    }

    @keyframes srShowGlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .sr-badge-pill {
        background: rgba(245, 158, 11, 0.25) !important;
        border: 1px solid rgba(245, 158, 11, 0.5) !important;
        color: #fef08a !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    .custom-kpi-card-srshow {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 20px 22px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .custom-kpi-card-srshow:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08) !important;
    }

    .custom-show-card {
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 18px !important;
        background: #ffffff !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04) !important;
        overflow: hidden;
        margin-bottom: 24px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-3">

    @php
        $isExpired = method_exists($salesReservation, 'isExpired') ? $salesReservation->isExpired() : ($salesReservation->expiry_date && $salesReservation->expiry_date->isPast());
        $netPrice = $salesReservation->option_price - ($salesReservation->discount_amount ?? 0);
    @endphp

    <!-- HERO HEADER BANNER -->
    <div class="sr-show-banner text-white">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill sr-badge-pill font-monospace">
                        <i class="ri-hashtag text-warning fs-5"></i> Opsiyon #{{ $salesReservation->option_no }}
                    </span>
                    @if($salesReservation->status === 'confirmed')
                        <span class="badge bg-success text-white rounded-pill px-3 py-2 font-monospace fw-bold" style="background: #16a34a !important;">🟢 ONAYLANDI (KESİNLEŞTİ)</span>
                    @elseif($salesReservation->status === 'pending')
                        <span class="badge bg-warning text-dark rounded-pill px-3 py-2 font-monospace fw-bold">🟡 BEKLEMEDE (KAPARO BEKLENİYOR)</span>
                    @elseif($salesReservation->status === 'expired')
                        <span class="badge bg-secondary text-white rounded-pill px-3 py-2 font-monospace fw-bold">⚪ SÜRESİ DOLDU</span>
                    @else
                        <span class="badge bg-danger text-white rounded-pill px-3 py-2 font-monospace fw-bold" style="background: #ef4444 !important;">🔴 İPTAL EDİLDİ</span>
                    @endif
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2">👤 {{ $salesReservation->customer->name ?? 'Müşteri Belirtilmemiş' }}</h2>
                <p class="mb-0 fs-6" style="max-width: 780px; line-height: 1.6; color: #e2e8f0; font-weight: 500;">
                    <i class="ri-building-line text-warning me-1"></i> Birim No: <strong class="text-white fw-bold">{{ $salesReservation->unit->unit_no ?? '-' }}</strong>
                    ({{ $salesReservation->unit->unit_type ?? 'Konut / Bağımsız Bölüm' }})
                </p>
            </div>

            <div class="col-lg-5 text-lg-end mt-3 mt-lg-0">
                <div class="d-flex align-items-center justify-content-lg-end gap-2 flex-wrap">
                    <a href="{{ route('sales-reservations.edit', $salesReservation) }}" class="btn btn-warning text-dark rounded-pill px-4 py-2.5 fw-extrabold shadow-sm">
                        <i class="ri-edit-fill me-1"></i>Düzenle
                    </a>
                    <a href="{{ route('sales-reservations.index') }}" class="btn btn-outline-light rounded-pill px-4 py-2.5 fw-extrabold">
                        <i class="ri-arrow-left-line me-1"></i>Opsiyonlar Listesi
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- FINANCIAL KPI SUMMARY CARDS -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card custom-kpi-card-srshow h-100 border-start border-primary border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">BRÜT OPSİYON FİYATI</small>
                        <div class="fw-extrabold text-primary font-monospace" style="font-size: 1.7rem;">₺{{ number_format($salesReservation->option_price, 0, ',', '.') }}</div>
                        <small class="text-primary fw-bold"><i class="ri-money-dollar-circle-line me-1"></i>Liste Anlaşma Tutarı</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #eff6ff !important; border: 1px solid #bfdbfe !important;">
                        <i class="ri-money-dollar-circle-fill fs-2" style="color: #2563eb !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card custom-kpi-card-srshow h-100 border-start border-warning border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">İNDİRİM TUTARI</small>
                        <div class="fw-extrabold text-warning font-monospace" style="font-size: 1.7rem;">₺{{ number_format($salesReservation->discount_amount ?? 0, 0, ',', '.') }}</div>
                        <small class="text-warning fw-bold"><i class="ri-percent-line me-1"></i>Uygulanan Özel İskonto</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #fffbeb !important; border: 1px solid #fde68a !important;">
                        <i class="ri-percent-fill fs-2" style="color: #d97706 !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card custom-kpi-card-srshow h-100 border-start border-success border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">NET ANLAŞMA TUTARI</small>
                        <div class="fw-extrabold text-success font-monospace" style="font-size: 1.7rem;">₺{{ number_format($netPrice, 0, ',', '.') }}</div>
                        <small class="text-success fw-bold"><i class="ri-checkbox-circle-fill me-1"></i>Net Portföy Değeri</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0fdf4 !important; border: 1px solid #bbf7d0 !important;">
                        <i class="ri-checkbox-circle-fill fs-2" style="color: #16a34a !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN DETAILS GRID -->
    <div class="row">
        <!-- LEFT COLUMN: GENERAL DETAILS & NOTES -->
        <div class="col-lg-8">
            <div class="card custom-show-card">
                <div class="card-header bg-dark text-white p-3.5" style="background-color: #0f172a !important;">
                    <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                        <i class="ri-information-fill text-warning fs-5"></i>Genel Opsiyon Detayları
                    </h5>
                </div>

                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <small class="text-muted d-block fw-bold text-uppercase" style="font-size: 11px;">Müşteri Adı / Unvanı</small>
                            <span class="fs-6 fw-extrabold text-dark">👤 {{ $salesReservation->customer->name ?? 'Belirtilmemiş' }}</span>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block fw-bold text-uppercase" style="font-size: 11px;">Opsiyon Başlangıç Tarihi</small>
                            <span class="fs-6 fw-extrabold text-dark font-monospace">📅 {{ $salesReservation->option_date ? $salesReservation->option_date->format('d.m.Y') : '-' }}</span>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block fw-bold text-uppercase" style="font-size: 11px;">Son Geçerlilik Tarihi</small>
                            <span class="fs-6 fw-extrabold font-monospace {{ $isExpired ? 'text-danger' : 'text-warning' }}">
                                ⏳ {{ $salesReservation->expiry_date ? $salesReservation->expiry_date->format('d.m.Y') : '-' }}
                                @if($isExpired) (Süresi Doldu) @endif
                            </span>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block fw-bold text-uppercase" style="font-size: 11px;">Rezervasyon Durumu</small>
                            <span class="fw-extrabold fs-6">
                                @if($salesReservation->status === 'confirmed')
                                    <span class="text-success">🟢 Onaylandı</span>
                                @elseif($salesReservation->status === 'pending')
                                    <span class="text-warning">🟡 Beklemede</span>
                                @elseif($salesReservation->status === 'expired')
                                    <span class="text-secondary">⚪ Süresi Doldu</span>
                                @else
                                    <span class="text-danger">🔴 İptal Edildi</span>
                                @endif
                            </span>
                        </div>
                    </div>

                    @if ($salesReservation->notes)
                        <div class="mt-4 pt-3 border-top">
                            <small class="text-dark d-block mb-2 fw-extrabold"><i class="ri-file-text-line text-warning me-1"></i>Özel Notlar & Şartlar</small>
                            <div class="p-3.5 rounded-3 fw-semibold text-dark" style="background: #f8fafc; border: 1px solid #cbd5e1; line-height: 1.6;">
                                {{ $salesReservation->notes }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: UNIT INFO & META -->
        <div class="col-lg-4">
            <!-- UNIT INFO CARD -->
            <div class="card custom-show-card">
                <div class="card-header bg-dark text-white p-3.5" style="background-color: #0f172a !important;">
                    <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                        <i class="ri-building-line text-warning fs-5"></i>Bağımsız Bölüm Bilgileri
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <small class="text-muted d-block fw-bold text-uppercase" style="font-size: 11px;">Birim No / Kodu</small>
                        <span class="fs-6 fw-extrabold text-primary font-monospace">🏢 {{ $salesReservation->unit->unit_no ?? 'Belirtilmemiş' }}</span>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block fw-bold text-uppercase" style="font-size: 11px;">Birim Tipi</small>
                        <span class="fw-extrabold text-dark">{{ $salesReservation->unit->unit_type ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <small class="text-muted d-block fw-bold text-uppercase" style="font-size: 11px;">Proje Adı</small>
                        <span class="fw-extrabold text-success">🏗️ {{ $salesReservation->unit->project->name ?? 'Proje Bağlantısı Yok' }}</span>
                    </div>
                </div>
            </div>

            <!-- CREATED BY META CARD -->
            <div class="card custom-show-card">
                <div class="card-header bg-dark text-white p-3.5" style="background-color: #0f172a !important;">
                    <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                        <i class="ri-user-follow-line text-warning fs-5"></i>Kayıt Geçmişi
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <small class="text-muted d-block fw-bold text-uppercase" style="font-size: 11px;">Opsiyonu Oluşturan Temsilci</small>
                        <span class="fw-extrabold text-dark">👤 {{ $salesReservation->createdBy->name ?? 'Sistem' }}</span>
                    </div>
                    <div>
                        <small class="text-muted d-block fw-bold text-uppercase" style="font-size: 11px;">Oluşturulma Zamanı</small>
                        <span class="fw-extrabold text-dark font-monospace">📅 {{ $salesReservation->created_at ? $salesReservation->created_at->format('d.m.Y H:i') : '-' }}</span>
                    </div>
                </div>
            </div>

            <!-- DELETE FORM CARD -->
            <div class="card custom-show-card">
                <div class="card-body p-4">
                    <form action="{{ route('sales-reservations.destroy', $salesReservation) }}" method="POST" class="d-grid">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger rounded-pill py-2.5 fw-extrabold" onclick="return confirmDelete(this, 'Opsiyon Kaydını Sil?', 'Bu opsiyon kaydı kalıcı olarak silinecektir.')">
                            <i class="ri-delete-bin-line me-1"></i>Opsiyon Kaydını Sil
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
