@extends('layouts.master')

@section('title', 'Görüşme Detayı — ' . $meeting->subject)

@push('styles')
<style>
    .cm-show-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #b45309 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.4) !important;
        padding: 32px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .cm-show-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #d97706, #2563eb, #16a34a, #d97706);
        background-size: 300% 100%;
        animation: cmShowGlow 4s linear infinite;
    }

    @keyframes cmShowGlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .cm-badge-pill {
        background: rgba(245, 158, 11, 0.25) !important;
        border: 1px solid rgba(245, 158, 11, 0.5) !important;
        color: #fef08a !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    .custom-kpi-card-cmshow {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 20px 22px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .custom-kpi-card-cmshow:hover {
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

    <!-- HERO HEADER BANNER -->
    <div class="cm-show-banner text-white">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
                    @if($meeting->outcome == 'converted_to_sale')
                        <span class="badge bg-success text-white rounded-pill px-3 py-2 font-monospace fw-bold" style="background: #16a34a !important;">💰 SATIŞA DÖNÜŞTÜ</span>
                    @elseif($meeting->outcome == 'interested')
                        <span class="badge bg-primary text-white rounded-pill px-3 py-2 font-monospace fw-bold">✓ İLGİLENDİ (SICAK)</span>
                    @elseif($meeting->outcome == 'pending')
                        <span class="badge bg-warning text-dark rounded-pill px-3 py-2 font-monospace fw-bold">⏳ BEKLEMEDE</span>
                    @else
                        <span class="badge bg-danger text-white rounded-pill px-3 py-2 font-monospace fw-bold" style="background: #ef4444 !important;">✗ İLGİLENMEDİ</span>
                    @endif

                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill cm-badge-pill font-monospace">
                        <i class="ri-shapes-line text-warning fs-5"></i> {{ $meeting->getMeetingTypeLabel() }}
                    </span>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2">💬 {{ $meeting->subject }}</h2>
                <p class="mb-0 fs-6" style="max-width: 780px; line-height: 1.6; color: #e2e8f0; font-weight: 500;">
                    <i class="ri-user-3-line text-warning me-1"></i> Müşteri: <strong class="text-white fw-bold">{{ $meeting->customer?->name ?? 'Belirtilmedi' }}</strong> |
                    <i class="ri-calendar-event-line text-warning me-1"></i> Tarih: <strong class="text-white fw-bold">{{ $meeting->meeting_date ? $meeting->meeting_date->format('d.m.Y H:i') : '-' }}</strong>
                </p>
            </div>

            <div class="col-lg-5 text-lg-end mt-3 mt-lg-0">
                <div class="d-flex align-items-center justify-content-lg-end gap-2 flex-wrap">
                    @if($meeting->customer)
                        <a href="{{ route('customers.show', $meeting->customer->id) }}" class="btn btn-outline-light rounded-pill px-3.5 py-2.5 fw-extrabold">
                            <i class="ri-user-search-line me-1"></i>Müşteri Profili
                        </a>
                    @endif
                    <a href="{{ route('customer_meetings.edit', $meeting->id) }}" class="btn btn-warning text-dark rounded-pill px-3.5 py-2.5 fw-extrabold shadow-sm">
                        <i class="ri-edit-fill me-1"></i>Düzenle
                    </a>
                    <a href="{{ route('customer_meetings.index') }}" class="btn btn-outline-light rounded-pill px-3.5 py-2.5 fw-extrabold">
                        <i class="ri-arrow-left-line me-1"></i>Görüşmeler Listesi
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- SPEC KPI CARDS -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card custom-kpi-card-cmshow h-100 border-start border-success border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">POTANSİYEL SATIŞ TUTARI</small>
                        <div class="fw-extrabold text-success font-monospace" style="font-size: 1.7rem;">
                            {{ $meeting->potential_sale_amount ? ('₺' . number_format($meeting->potential_sale_amount, 0, ',', '.')) : 'Belirtilmedi' }}
                        </div>
                        <small class="text-success fw-bold"><i class="ri-price-tag-3-fill me-1"></i>Teklif Büyüklüğü</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0fdf4 !important; border: 1px solid #bbf7d0 !important;">
                        <i class="ri-money-dollar-circle-fill fs-2" style="color: #16a34a !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card custom-kpi-card-cmshow h-100 border-start border-primary border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">GÖRÜŞMEYİ YAPAN DANIŞMAN</small>
                        <div class="fw-extrabold text-primary font-monospace" style="font-size: 1.6rem;">👤 {{ $meeting->user?->name ?? 'Sistem Temsilcisi' }}</div>
                        <small class="text-primary fw-bold"><i class="ri-user-star-line me-1"></i>Sorumlu Müşteri Temsilcisi</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #eff6ff !important; border: 1px solid #bfdbfe !important;">
                        <i class="ri-user-follow-fill fs-2" style="color: #2563eb !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card custom-kpi-card-cmshow h-100 border-start border-warning border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">GÖRÜŞME LOKASYONU</small>
                        <div class="fw-extrabold text-warning font-monospace" style="font-size: 1.4rem;">📍 {{ $meeting->location ?? 'Lokasyon Girilmedi' }}</div>
                        <small class="text-warning fw-bold"><i class="ri-map-pin-fill me-1"></i>Toplantı / Görüşme Yeri</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #fffbeb !important; border: 1px solid #fde68a !important;">
                        <i class="ri-map-pin-2-fill fs-2" style="color: #d97706 !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN DETAILS GRID -->
    <div class="row">
        <!-- LEFT COLUMN: NOTES & ACTION PLAN -->
        <div class="col-lg-8">
            <!-- NOTES CARD -->
            <div class="card custom-show-card">
                <div class="card-header bg-dark text-white p-3.5" style="background-color: #0f172a !important;">
                    <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                        <i class="ri-file-text-line text-warning fs-5"></i>Görüşme Detayları & Notlar
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="p-4 rounded-3 text-dark fw-semibold" style="background: #f8fafc; border: 1px solid #cbd5e1; line-height: 1.8; white-space: pre-wrap;">
                        {{ $meeting->notes ?? 'Bu görüşme için ekstra not girilmemiştir.' }}
                    </div>
                </div>
            </div>

            <!-- NEXT FOLLOWUP CARD -->
            @if($meeting->next_followup)
                <div class="card custom-show-card">
                    <div class="card-body p-4" style="background: #fffbeb; border-left: 4px solid #d97706 !important;">
                        <div class="d-flex align-items-center gap-2 text-dark fw-extrabold fs-5 mb-2">
                            <i class="ri-alarm-warning-fill text-warning"></i>Sonraki Takip & Aksiyon Planı
                        </div>
                        <p class="fs-6 text-dark fw-semibold mb-3" style="line-height: 1.6;">{{ $meeting->next_followup }}</p>
                        @if($meeting->next_followup_date)
                            <span class="badge bg-warning text-dark font-monospace px-3 py-2 fw-extrabold" style="font-size: 13px;">
                                📅 Hedef Takip Tarihi: {{ $meeting->next_followup_date->format('d.m.Y') }}
                            </span>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- RIGHT COLUMN: CUSTOMER INFO & ACTIONS -->
        <div class="col-lg-4">
            <!-- CUSTOMER PROFILE CARD -->
            <div class="card custom-show-card">
                <div class="card-header bg-dark text-white p-3.5" style="background-color: #0f172a !important;">
                    <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                        <i class="ri-user-star-line text-warning fs-5"></i>Müşteri İletişim Kartı
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <small class="text-muted d-block fw-bold text-uppercase" style="font-size: 11px;">Müşteri Adı Soyadı</small>
                        <strong class="fs-5 text-dark fw-extrabold">👤 {{ $meeting->customer?->name ?? 'Belirtilmedi' }}</strong>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block fw-bold text-uppercase" style="font-size: 11px;">Telefon Numarası</small>
                        <span class="fw-extrabold text-primary font-monospace fs-6">📞 {{ $meeting->customer?->phone ?? 'Telefon Yok' }}</span>
                    </div>
                    <div class="mb-4">
                        <small class="text-muted d-block fw-bold text-uppercase" style="font-size: 11px;">E-Posta Adresi</small>
                        <span class="fw-bold text-dark font-monospace">✉️ {{ $meeting->customer?->email ?? 'E-Posta Yok' }}</span>
                    </div>

                    @if($meeting->customer)
                        <a href="{{ route('customer.meetings', $meeting->customer->id) }}" class="btn btn-outline-dark w-100 rounded-pill py-2.5 fw-extrabold">
                            <i class="ri-history-line me-1"></i>Bu Müşterinin Tüm Görüşmeleri
                        </a>
                    @endif
                </div>
            </div>

            <!-- META & DELETE ACTIONS CARD -->
            <div class="card custom-show-card">
                <div class="card-body p-4">
                    <small class="text-muted d-block fw-bold text-uppercase" style="font-size: 11px;">Görüşme Kayıt Tarihi</small>
                    <span class="fw-bold text-dark d-block mb-3 font-monospace">📅 {{ $meeting->created_at ? $meeting->created_at->format('d.m.Y H:i') : '-' }}</span>
                    
                    <div class="d-grid gap-2">
                        <a href="{{ route('customer_meetings.edit', $meeting->id) }}" class="btn btn-warning text-dark rounded-pill py-2.5 fw-extrabold shadow-sm">
                            <i class="ri-edit-fill me-1"></i>Görüşmeyi Düzenle
                        </a>
                        <form action="{{ route('customer_meetings.destroy', $meeting->id) }}" method="POST" class="d-grid">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger rounded-pill py-2.5 fw-extrabold" onclick="return confirmDelete(this, 'Görüşme Kaydını Sil?', 'Bu müşteri görüşme kaydı silinecektir.')">
                                <i class="ri-delete-bin-line me-1"></i>Görüşmeyi Sil
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
