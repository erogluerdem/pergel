@extends('layouts.master')

@section('title', 'Müşteri Görüşmeleri & CRM Takibi')

@push('styles')
<style>
    .cm-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #b45309 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.4) !important;
        padding: 32px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .cm-hero-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #d97706, #2563eb, #16a34a, #d97706);
        background-size: 300% 100%;
        animation: cmGlow 4s linear infinite;
    }

    @keyframes cmGlow {
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

    .custom-kpi-card-cm {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 20px 22px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .custom-kpi-card-cm:hover {
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

    .custom-input-cm {
        background-color: #f8fafc !important;
        border: 1.5px solid #94a3b8 !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        border-radius: 10px !important;
        padding: 8px 12px !important;
    }

    .custom-input-cm:focus {
        background-color: #ffffff !important;
        border-color: #d97706 !important;
        box-shadow: 0 0 0 3px rgba(217, 119, 6, 0.15) !important;
    }

    .custom-cm-table thead,
    .custom-cm-table thead tr,
    .custom-cm-table thead th {
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

    .custom-cm-table td {
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
    <div class="cm-hero-banner text-white">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill cm-badge-pill">
                        <i class="ri-phone-find-line text-warning fs-5"></i> CRM & GÖRÜŞME YÖNETİMİ
                    </span>
                    <span class="badge bg-success text-white fw-extrabold px-3 py-2 rounded-pill font-monospace" style="background: #16a34a !important;">
                        {{ $meetings->total() }} Kayıtlı Görüşme
                    </span>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2">Müşteri Görüşmeleri & İletişim Takibi</h2>
                <p class="mb-0 fs-6" style="max-width: 780px; line-height: 1.6; color: #e2e8f0; font-weight: 500;">
                    Tüm potansiyel alıcı görüşmelerini, sunumları, toplantıları ve satış fırsatlarını yönetin.
                </p>
            </div>

            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <a href="{{ route('customer_meetings.create') }}" class="btn btn-warning text-dark rounded-pill px-4 py-2.5 fw-extrabold d-inline-flex align-items-center gap-2 shadow-sm">
                    <i class="ri-add-circle-fill fs-5"></i>+ Yeni Görüşme Kaydet
                </a>
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm p-3 mb-4 d-flex align-items-center gap-2 bg-success text-white" role="alert">
            <i class="ri-checkbox-circle-fill fs-4"></i>
            <span class="fw-bold fs-6">{{ $message }}</span>
            <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- SUMMARY KPI CARDS ROW -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-cm h-100 border-start border-primary border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">TOPLAM GÖRÜŞME</small>
                        <div class="fw-extrabold text-primary font-monospace" style="font-size: 1.8rem;">{{ $meetings->total() }} Kayıt</div>
                        <small class="text-primary fw-bold"><i class="ri-customer-service-2-line me-1"></i>Tüm Görüşme İrtibatları</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #eff6ff !important; border: 1px solid #bfdbfe !important;">
                        <i class="ri-customer-service-2-fill fs-2" style="color: #2563eb !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-cm h-100 border-start border-success border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">SATIŞA DÖNÜŞEN</small>
                        <div class="fw-extrabold text-success font-monospace" style="font-size: 1.8rem;">{{ \App\Models\CustomerMeeting::where('outcome', 'converted_to_sale')->count() }} Kayıt</div>
                        <small class="text-success fw-bold"><i class="ri-price-tag-3-fill me-1"></i>Başarılı Satış Kapanışı</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0fdf4 !important; border: 1px solid #bbf7d0 !important;">
                        <i class="ri-price-tag-3-fill fs-2" style="color: #16a34a !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-cm h-100 border-start border-warning border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">SICAK / İLGİLENEN</small>
                        <div class="fw-extrabold text-warning font-monospace" style="font-size: 1.8rem;">{{ \App\Models\CustomerMeeting::where('outcome', 'interested')->count() }} Kayıt</div>
                        <small class="text-warning fw-bold"><i class="ri-fire-fill me-1"></i>Potansiyel Alıcılar</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #fffbeb !important; border: 1px solid #fde68a !important;">
                        <i class="ri-fire-fill fs-2" style="color: #d97706 !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-cm h-100 border-start border-info border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">POTANSİYEL SATIŞ HACMİ</small>
                        <div class="fw-extrabold text-info font-monospace" style="font-size: 1.5rem;">₺{{ number_format(\App\Models\CustomerMeeting::sum('potential_sale_amount'), 0, ',', '.') }}</div>
                        <small class="text-info fw-bold"><i class="ri-money-dollar-circle-line me-1"></i>Teklif Değer Toplamı</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0f9ff !important; border: 1px solid #bae6fd !important;">
                        <i class="ri-money-dollar-circle-fill fs-2" style="color: #0284c7 !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- FILTER CARD -->
    <div class="card custom-card-container mb-4">
        <div class="card-header bg-dark text-white p-3.5" style="background-color: #0f172a !important;">
            <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                <i class="ri-filter-3-line text-warning fs-5"></i>Görüşme Filtreleme Modülü
            </h5>
        </div>
        <div class="card-body p-4">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-extrabold text-dark small">Müşteri</label>
                    <select name="customer_id" class="form-select custom-input-cm">
                        <option value="">-- Tüm Müşteriler --</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                                👤 {{ $customer->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-extrabold text-dark small">Görüşme Sonucu</label>
                    <select name="outcome" class="form-select custom-input-cm">
                        <option value="">-- Tüm Sonuçlar --</option>
                        @foreach($outcomes as $key => $label)
                            <option value="{{ $key }}" {{ request('outcome') == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-extrabold text-dark small">Görüşme Türü</label>
                    <select name="type" class="form-select custom-input-cm">
                        <option value="">-- Tüm Türler --</option>
                        @foreach($types as $key => $label)
                            <option value="{{ $key }}" {{ request('type') == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <button type="submit" class="btn btn-warning text-dark fw-extrabold rounded-pill w-100 py-2 shadow-sm">
                        <i class="ri-search-line me-1"></i>Filtrele
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MAIN TABLE CARD -->
    <div class="card custom-card-container mb-5">
        <div class="card-header bg-dark text-white p-3.5 d-flex align-items-center justify-content-between" style="background-color: #0f172a !important;">
            <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                <i class="ri-history-line text-warning fs-5"></i>Görüşme Kayıtları Cetveli
            </h5>
            <span class="badge bg-warning text-dark font-monospace fw-extrabold px-3 py-1.5 rounded-pill" style="font-size: 11px;">
                {{ $meetings->total() }} Kayıt
            </span>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table custom-cm-table align-middle mb-0">
                    <thead style="background-color: #0f172a !important;">
                        <tr style="background-color: #0f172a !important;">
                            <th class="ps-4 text-warning" style="background-color: #0f172a !important; color: #fef08a !important; font-weight: 800;">Müşteri & İletişim</th>
                            <th class="text-white" style="background-color: #0f172a !important; color: #ffffff !important; font-weight: 800;">Görüşme Konusu</th>
                            <th class="text-white" style="background-color: #0f172a !important; color: #ffffff !important; font-weight: 800;">Tür</th>
                            <th class="text-white" style="background-color: #0f172a !important; color: #ffffff !important; font-weight: 800;">Tarih / Saat</th>
                            <th class="text-center text-white" style="background-color: #0f172a !important; color: #ffffff !important; font-weight: 800;">Sonuç</th>
                            <th class="text-end text-white" style="background-color: #0f172a !important; color: #ffffff !important; font-weight: 800;">Potansiyel Satış</th>
                            <th class="text-white" style="background-color: #0f172a !important; color: #ffffff !important; font-weight: 800;">Danışman</th>
                            <th class="pe-4 text-end text-white" style="background-color: #0f172a !important; color: #ffffff !important; font-weight: 800;">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($meetings as $meeting)
                            <tr>
                                <td class="ps-4 py-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary p-2 fs-5">
                                            <i class="ri-user-follow-line"></i>
                                        </div>
                                        <div>
                                            @if($meeting->customer)
                                                <a href="{{ route('customers.show', $meeting->customer->id) }}" class="fw-extrabold text-dark fs-6 text-decoration-none">
                                                    👤 {{ $meeting->customer->name }}
                                                </a>
                                                <small class="text-muted d-block font-monospace fw-semibold">📞 {{ $meeting->customer->phone ?? 'Telefon Yok' }}</small>
                                            @else
                                                <span class="fw-bold text-muted">Müşteri Bulunamadı</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="fw-extrabold text-dark fs-6 d-block">{{ Str::limit($meeting->subject, 35) }}</span>
                                    @if($meeting->location)
                                        <small class="text-muted fw-semibold"><i class="ri-map-pin-line me-1"></i>{{ $meeting->location }}</small>
                                    @endif
                                </td>
                                <td class="font-monospace">
                                    @if($meeting->meeting_type == 'visit')
                                        <span class="badge bg-primary text-white rounded-pill px-3 py-1.5 font-monospace fw-bold">👤 Ziyaret</span>
                                    @elseif($meeting->meeting_type == 'call')
                                        <span class="badge bg-info text-white rounded-pill px-3 py-1.5 font-monospace fw-bold">☎️ Telefon</span>
                                    @elseif($meeting->meeting_type == 'online')
                                        <span class="badge bg-purple text-white rounded-pill px-3 py-1.5 font-monospace fw-bold">💻 Online</span>
                                    @else
                                        <span class="badge bg-secondary text-white rounded-pill px-3 py-1.5 font-monospace fw-bold">✉️ E-Posta</span>
                                    @endif
                                </td>
                                <td class="font-monospace text-dark fw-bold">
                                    📅 {{ $meeting->meeting_date ? $meeting->meeting_date->format('d.m.Y H:i') : '-' }}
                                </td>
                                <td class="text-center font-monospace">
                                    @if($meeting->outcome == 'converted_to_sale')
                                        <span class="badge bg-success text-white rounded-pill px-3 py-1.5 font-monospace fw-bold" style="background: #16a34a !important;">💰 SATIŞA DÖNÜŞTÜ</span>
                                    @elseif($meeting->outcome == 'interested')
                                        <span class="badge bg-primary text-white rounded-pill px-3 py-1.5 font-monospace fw-bold">✓ İLGİLENDİ</span>
                                    @elseif($meeting->outcome == 'pending')
                                        <span class="badge bg-warning text-dark rounded-pill px-3 py-1.5 font-monospace fw-bold">⏳ BEKLEMEDE</span>
                                    @else
                                        <span class="badge bg-danger text-white rounded-pill px-3 py-1.5 font-monospace fw-bold" style="background: #ef4444 !important;">✗ İLGİLENMEDİ</span>
                                    @endif
                                </td>
                                <td class="text-end font-monospace">
                                    @if($meeting->potential_sale_amount)
                                        <strong class="text-success font-monospace fs-6">₺{{ number_format($meeting->potential_sale_amount, 0, ',', '.') }}</strong>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <small class="fw-extrabold text-dark">👤 {{ $meeting->user?->name ?? 'Sistem' }}</small>
                                </td>
                                <td class="pe-4 text-end">
                                    <div class="d-inline-flex gap-1">
                                        <a href="{{ route('customer_meetings.show', $meeting->id) }}" class="btn btn-sm btn-warning text-dark rounded-circle p-2" title="İncele">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                        <a href="{{ route('customer_meetings.edit', $meeting->id) }}" class="btn btn-sm btn-outline-dark rounded-circle p-2" title="Düzenle">
                                            <i class="ri-pencil-line"></i>
                                        </a>
                                        <form action="{{ route('customer_meetings.destroy', $meeting->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle p-2" onclick="return confirmDelete(this, 'Görüşme Kaydını Sil?', 'Bu müşteri görüşme kaydı silinecektir.')" title="Sil">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-5">
                                    <i class="ri-customer-service-line display-4 text-warning mb-2 d-block"></i>
                                    <h6 class="fw-bold text-dark">Görüşme Kaydı Bulunmuyor</h6>
                                    <p class="small text-muted mb-0">Üstteki "+ Yeni Görüşme Kaydet" butonuna tıklayarak ilk görüşmenizi ekleyebilirsiniz.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-transparent border-0 py-3 d-flex justify-content-center">
            {{ $meetings->links() }}
        </div>
    </div>

</div>
@endsection
