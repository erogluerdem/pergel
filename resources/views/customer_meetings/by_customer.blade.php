@extends('layouts.master')

@section('title', $customer->name . ' — Görüşme Geçmişi')

@push('styles')
<style>
    .cm-customer-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #b45309 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.4) !important;
        padding: 32px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .cm-customer-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #d97706, #2563eb, #16a34a, #d97706);
        background-size: 300% 100%;
        animation: cmCustomerGlow 4s linear infinite;
    }

    @keyframes cmCustomerGlow {
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

    .custom-kpi-card-cmc {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 20px 22px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .custom-kpi-card-cmc:hover {
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

    .custom-cmc-table thead,
    .custom-cmc-table thead tr,
    .custom-cmc-table thead th {
        background-color: #0f172a !important;
        color: #ffffff !important;
        padding: 14px 16px !important;
        font-weight: 800 !important;
        font-size: 0.85rem !important;
        border-bottom: 2px solid #d97706 !important;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        --bs-table-bg: #0f172a !important;
        --bs-table-color: #ffffff !important;
    }

    .custom-cmc-table td {
        padding: 14px 16px !important;
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
    <div class="cm-customer-banner text-white">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill cm-badge-pill">
                        <i class="ri-user-star-fill text-warning fs-5"></i> MÜŞTERİ ÖZEL CRM PORTAL
                    </span>
                    <span class="badge bg-success text-white fw-extrabold px-3 py-2 rounded-pill font-monospace" style="background: #16a34a !important;">
                        {{ $meetings->count() }} Kayıtlı Görüşme
                    </span>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2">👤 {{ $customer->name }} — Görüşme Geçmişi</h2>
                <p class="mb-0 fs-6" style="max-width: 780px; line-height: 1.6; color: #e2e8f0; font-weight: 500;">
                    <i class="ri-phone-line text-warning me-1"></i> Tel: <strong class="text-white fw-bold">{{ $customer->phone ?? 'Belirtilmedi' }}</strong> |
                    <i class="ri-mail-line text-warning me-1"></i> E-Posta: <strong class="text-white fw-bold">{{ $customer->email ?? 'Belirtilmedi' }}</strong>
                </p>
            </div>

            <div class="col-lg-5 text-lg-end mt-3 mt-lg-0">
                <div class="d-flex align-items-center justify-content-lg-end gap-2 flex-wrap">
                    <a href="{{ route('customer_meetings.create', ['customer_id' => $customer->id]) }}" class="btn btn-warning text-dark rounded-pill px-3.5 py-2.5 fw-extrabold shadow-sm">
                        <i class="ri-add-circle-fill me-1"></i>+ Yeni Görüşme Ekleyin
                    </a>
                    <a href="{{ route('customers.show', $customer->id) }}" class="btn btn-outline-light rounded-pill px-3.5 py-2.5 fw-extrabold">
                        <i class="ri-user-search-line me-1"></i>360° Müşteri Profili
                    </a>
                    <a href="{{ route('customer_meetings.index') }}" class="btn btn-outline-light rounded-pill px-3.5 py-2.5 fw-extrabold">
                        <i class="ri-arrow-left-line me-1"></i>Tüm Görüşmeler
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- SUMMARY KPI CARDS ROW -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-cmc h-100 border-start border-primary border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">MÜŞTERİ GÖRÜŞMELERİ</small>
                        <div class="fw-extrabold text-primary font-monospace" style="font-size: 1.8rem;">{{ $meetings->count() }} Kayıt</div>
                        <small class="text-primary fw-bold"><i class="ri-customer-service-2-line me-1"></i>Görüşme İrtibat Sayısı</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #eff6ff !important; border: 1px solid #bfdbfe !important;">
                        <i class="ri-customer-service-2-fill fs-2" style="color: #2563eb !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-cmc h-100 border-start border-success border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">SATIŞA DÖNÜŞEN</small>
                        <div class="fw-extrabold text-success font-monospace" style="font-size: 1.8rem;">{{ $meetings->where('outcome', 'converted_to_sale')->count() }} Kayıt</div>
                        <small class="text-success fw-bold"><i class="ri-price-tag-3-fill me-1"></i>Başarılı Satış Kapanışı</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0fdf4 !important; border: 1px solid #bbf7d0 !important;">
                        <i class="ri-price-tag-3-fill fs-2" style="color: #16a34a !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-cmc h-100 border-start border-warning border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">SICAK / İLGİLENEN</small>
                        <div class="fw-extrabold text-warning font-monospace" style="font-size: 1.8rem;">{{ $meetings->where('outcome', 'interested')->count() }} Kayıt</div>
                        <small class="text-warning fw-bold"><i class="ri-fire-fill me-1"></i>Sıcak Potansiyel</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #fffbeb !important; border: 1px solid #fde68a !important;">
                        <i class="ri-fire-fill fs-2" style="color: #d97706 !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-cmc h-100 border-start border-info border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">POTANSİYEL SATIŞ HACMİ</small>
                        <div class="fw-extrabold text-info font-monospace" style="font-size: 1.5rem;">₺{{ number_format($meetings->sum('potential_sale_amount'), 0, ',', '.') }}</div>
                        <small class="text-info fw-bold"><i class="ri-money-dollar-circle-line me-1"></i>Teklif Değer Toplamı</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0f9ff !important; border: 1px solid #bae6fd !important;">
                        <i class="ri-money-dollar-circle-fill fs-2" style="color: #0284c7 !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN CONTENT GRID -->
    <div class="row">
        <!-- LEFT COLUMN: MEETINGS TABLE -->
        <div class="col-lg-8">
            <div class="card custom-card-container mb-5">
                <div class="card-header bg-dark text-white p-3.5 d-flex align-items-center justify-content-between" style="background-color: #0f172a !important;">
                    <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                        <i class="ri-history-line text-warning fs-5"></i>Görüşme Kayıtları Cetveli
                    </h5>
                    <span class="badge bg-warning text-dark font-monospace fw-extrabold px-3 py-1.5 rounded-pill" style="font-size: 11px;">
                        {{ $meetings->count() }} Kayıt
                    </span>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table custom-cmc-table align-middle mb-0">
                            <thead style="background-color: #0f172a !important;">
                                <tr style="background-color: #0f172a !important;">
                                    <th class="ps-4 text-warning" style="background-color: #0f172a !important; color: #fef08a !important;">Tarih / Saat</th>
                                    <th class="text-white" style="background-color: #0f172a !important; color: #ffffff !important;">Görüşme Konusu</th>
                                    <th class="text-white" style="background-color: #0f172a !important; color: #ffffff !important;">Tür</th>
                                    <th class="text-center text-white" style="background-color: #0f172a !important; color: #ffffff !important;">Sonuç</th>
                                    <th class="text-end text-white" style="background-color: #0f172a !important; color: #ffffff !important;">Potansiyel</th>
                                    <th class="pe-4 text-end text-white" style="background-color: #0f172a !important; color: #ffffff !important;">İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($meetings as $meeting)
                                    <tr>
                                        <td class="ps-4 font-monospace text-dark fw-bold">
                                            📅 {{ $meeting->meeting_date ? $meeting->meeting_date->format('d.m.Y H:i') : '-' }}
                                        </td>
                                        <td>
                                            <span class="fw-extrabold text-dark fs-6 d-block">{{ Str::limit($meeting->subject, 32) }}</span>
                                            <small class="text-muted fw-bold">👤 Sorumlu: {{ $meeting->user?->name ?? 'Sistem' }}</small>
                                        </td>
                                        <td class="font-monospace">
                                            @if($meeting->meeting_type == 'visit')
                                                <span class="badge bg-primary text-white rounded-pill px-3 py-1 font-monospace fw-bold">👤 Ziyaret</span>
                                            @elseif($meeting->meeting_type == 'call')
                                                <span class="badge bg-info text-white rounded-pill px-3 py-1 font-monospace fw-bold">☎️ Telefon</span>
                                            @elseif($meeting->meeting_type == 'online')
                                                <span class="badge bg-purple text-white rounded-pill px-3 py-1 font-monospace fw-bold">💻 Online</span>
                                            @else
                                                <span class="badge bg-secondary text-white rounded-pill px-3 py-1 font-monospace fw-bold">✉️ E-Posta</span>
                                            @endif
                                        </td>
                                        <td class="text-center font-monospace">
                                            @if($meeting->outcome == 'converted_to_sale')
                                                <span class="badge bg-success text-white rounded-pill px-3 py-1 font-monospace fw-bold" style="background: #16a34a !important;">💰 SATIŞ</span>
                                            @elseif($meeting->outcome == 'interested')
                                                <span class="badge bg-primary text-white rounded-pill px-3 py-1 font-monospace fw-bold">✓ İLGİLENDİ</span>
                                            @elseif($meeting->outcome == 'pending')
                                                <span class="badge bg-warning text-dark rounded-pill px-3 py-1 font-monospace fw-bold">⏳ BEKLEMEDE</span>
                                            @else
                                                <span class="badge bg-danger text-white rounded-pill px-3 py-1 font-monospace fw-bold" style="background: #ef4444 !important;">✗ İLGİLENMEDİ</span>
                                            @endif
                                        </td>
                                        <td class="text-end font-monospace">
                                            @if($meeting->potential_sale_amount > 0)
                                                <strong class="text-success font-monospace fs-6">₺{{ number_format($meeting->potential_sale_amount, 0, ',', '.') }}</strong>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="pe-4 text-end">
                                            <div class="d-inline-flex gap-1">
                                                <a href="{{ route('customer_meetings.show', $meeting->id) }}" class="btn btn-sm btn-warning text-dark rounded-circle p-2" title="İncele">
                                                    <i class="ri-eye-line"></i>
                                                </a>
                                                <a href="{{ route('customer_meetings.edit', $meeting->id) }}" class="btn btn-sm btn-outline-dark rounded-circle p-2" title="Düzenle">
                                                    <i class="ri-pencil-line"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-5">
                                            <i class="ri-customer-service-line display-4 text-warning mb-2 d-block"></i>
                                            <h6 class="fw-bold text-dark">Görüşme Kaydı Bulunmuyor</h6>
                                            <p class="small text-muted mb-0">{{ $customer->name }} için henüz müşteri görüşmesi kaydedilmemiştir.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: CUSTOMER PROFILE CARD -->
        <div class="col-lg-4">
            <div class="card custom-card-container mb-4">
                <div class="card-header bg-dark text-white p-3.5" style="background-color: #0f172a !important;">
                    <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                        <i class="ri-user-star-line text-warning fs-5"></i>Müşteri Künye Kartı
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <small class="text-muted d-block fw-bold text-uppercase" style="font-size: 11px;">Müşteri Unvanı</small>
                        <strong class="fs-5 text-dark fw-extrabold">👤 {{ $customer->name }}</strong>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted d-block fw-bold text-uppercase" style="font-size: 11px;">Telefon Numarası</small>
                        <span class="fw-extrabold text-primary font-monospace fs-6">📞 {{ $customer->phone ?? 'Telefon Yok' }}</span>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted d-block fw-bold text-uppercase" style="font-size: 11px;">E-Posta Adresi</small>
                        <span class="fw-bold text-dark font-monospace">✉️ {{ $customer->email ?? 'E-Posta Yok' }}</span>
                    </div>

                    @if($customer->city)
                        <div class="mb-3">
                            <small class="text-muted d-block fw-bold text-uppercase" style="font-size: 11px;">Şehir / İlçe</small>
                            <span class="fw-bold text-dark">📍 {{ $customer->city }} / {{ $customer->district ?? '' }}</span>
                        </div>
                    @endif

                    <hr>

                    <a href="{{ route('customers.show', $customer->id) }}" class="btn btn-outline-dark w-100 rounded-pill py-2.5 fw-extrabold">
                        <i class="ri-user-search-line me-1"></i>360° Müşteri Profili
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
