@extends('layouts.master')

@section('title', 'Gayrimenkul Opsiyon Yönetimi')

@push('styles')
<style>
    .opt-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #b45309 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.4) !important;
        padding: 32px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .opt-hero-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #d97706, #2563eb, #16a34a, #d97706);
        background-size: 300% 100%;
        animation: optGlow 4s linear infinite;
    }

    @keyframes optGlow {
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

    .custom-kpi-card-opt {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 20px 22px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .custom-kpi-card-opt:hover {
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

    .custom-opt-table thead,
    .custom-opt-table thead tr,
    .custom-opt-table thead th {
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

    .custom-opt-table td {
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

    @php
        $totalOptions = \App\Models\Option::count();
        $activeOptions = \App\Models\Option::where('status', 'active')->count();
        $expiredOptions = \App\Models\Option::where('status', 'expired')->count();
        $convertedOptions = \App\Models\Option::where('status', 'converted_to_project')->count();
    @endphp

    <!-- HERO HEADER BANNER -->
    <div class="opt-hero-banner text-white">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill opt-badge-pill">
                        <i class="ri-bookmark-3-fill text-warning fs-5"></i> CRM & GAYRİMENKUL OPSİYON YÖNETİMİ
                    </span>
                    <span class="badge bg-success text-white fw-extrabold px-3 py-2 rounded-pill font-monospace" style="background: #16a34a !important;">
                        {{ $totalOptions }} Toplam Opsiyon
                    </span>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2">Gayrimenkul Opsiyon Yönetimi</h2>
                <p class="mb-0 fs-6" style="max-width: 780px; line-height: 1.6; color: #e2e8f0; font-weight: 500;">
                    Potansiyel alıcıların opsiyon sürelerini, geçerlilik tarihlerini ve projeye dönüşüm süreçlerini anlık takip edin.
                </p>
            </div>

            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <div class="d-flex align-items-center justify-content-lg-end gap-2 flex-wrap">
                    <a href="{{ route('options.about-to-expire') }}" class="btn btn-outline-light rounded-pill px-3.5 py-2.5 fw-extrabold">
                        <i class="ri-time-line me-1"></i>Süresi Yaklaşanlar
                    </a>
                    <a href="{{ route('options.create') }}" class="btn btn-warning text-dark rounded-pill px-4 py-2.5 fw-extrabold d-inline-flex align-items-center gap-2 shadow-sm">
                        <i class="ri-add-circle-fill fs-5"></i>+ Yeni Opsiyon Oluştur
                    </a>
                </div>
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
            <div class="card custom-kpi-card-opt h-100 border-start border-primary border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">TOPLAM OPSİYON</small>
                        <div class="fw-extrabold text-primary font-monospace" style="font-size: 1.8rem;">{{ $totalOptions }} Kayıt</div>
                        <small class="text-primary fw-bold"><i class="ri-bookmark-line me-1"></i>Portföy Opsiyon Kaydı</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #eff6ff !important; border: 1px solid #bfdbfe !important;">
                        <i class="ri-bookmark-3-fill fs-2" style="color: #2563eb !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-opt h-100 border-start border-success border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">AKTİF OPSİYONLAR</small>
                        <div class="fw-extrabold text-success font-monospace" style="font-size: 1.8rem;">{{ $activeOptions }} Kayıt</div>
                        <small class="text-success fw-bold"><i class="ri-checkbox-circle-fill me-1"></i>Süresi Devam Edenler</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0fdf4 !important; border: 1px solid #bbf7d0 !important;">
                        <i class="ri-checkbox-circle-fill fs-2" style="color: #16a34a !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-opt h-100 border-start border-warning border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">PROJEYE DÖNÜŞEN</small>
                        <div class="fw-extrabold text-warning font-monospace" style="font-size: 1.8rem;">{{ $convertedOptions }} Kayıt</div>
                        <small class="text-warning fw-bold"><i class="ri-rocket-line me-1"></i>Kazanılmış Satışlar</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #fffbeb !important; border: 1px solid #fde68a !important;">
                        <i class="ri-rocket-fill fs-2" style="color: #d97706 !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card custom-kpi-card-opt h-100 border-start border-danger border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">SÜRESİ DOLAN</small>
                        <div class="fw-extrabold text-danger font-monospace" style="font-size: 1.8rem;">{{ $expiredOptions }} Kayıt</div>
                        <small class="text-danger fw-bold"><i class="ri-time-line me-1"></i>Zaman Aşımına Uğrayan</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #fff1f2 !important; border: 1px solid #fecdd3 !important;">
                        <i class="ri-history-line fs-2" style="color: #e11d48 !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN TABLE CARD -->
    <div class="card custom-card-container mb-5">
        <div class="card-header bg-dark text-white p-3.5 d-flex align-items-center justify-content-between" style="background-color: #0f172a !important;">
            <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                <i class="ri-list-check-2 text-warning fs-5"></i>Opsiyon Kayıt Listesi Cetveli
            </h5>
            <span class="badge bg-warning text-dark font-monospace fw-extrabold px-3 py-1.5 rounded-pill" style="font-size: 11px;">
                {{ $options->total() }} Kayıt
            </span>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table custom-opt-table align-middle mb-0">
                    <thead style="background-color: #0f172a !important;">
                        <tr style="background-color: #0f172a !important;">
                            <th class="ps-4 text-warning" style="background-color: #0f172a !important; color: #fef08a !important; font-weight: 800;">Gayrimenkul / Varlık</th>
                            <th class="text-white" style="background-color: #0f172a !important; color: #ffffff !important; font-weight: 800;">Müşteri</th>
                            <th class="text-white" style="background-color: #0f172a !important; color: #ffffff !important; font-weight: 800;">Opsiyon Tarihi</th>
                            <th class="text-white" style="background-color: #0f172a !important; color: #ffffff !important; font-weight: 800;">Bitme Tarihi</th>
                            <th class="text-center text-white" style="background-color: #0f172a !important; color: #ffffff !important; font-weight: 800;">Kalan Süre</th>
                            <th class="text-center text-white" style="background-color: #0f172a !important; color: #ffffff !important; font-weight: 800;">Durum</th>
                            <th class="pe-4 text-end text-white" style="background-color: #0f172a !important; color: #ffffff !important; font-weight: 800;">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($options as $option)
                            @php
                                $isAboutToExpire = method_exists($option, 'isAboutToExpire') ? $option->isAboutToExpire() : false;
                                $isExpired = method_exists($option, 'isExpired') ? $option->isExpired() : ($option->expiry_date && $option->expiry_date->isPast());
                            @endphp
                            <tr>
                                <td class="ps-4 py-3">
                                    <div class="fw-extrabold text-dark fs-6">🏢 {{ $option->asset?->name ?? 'Gayrimenkul Silinmiş' }}</div>
                                    @if($option->asset)
                                        <small class="text-muted fw-semibold">📍 {{ $option->asset->location ?? 'Konum yok' }}</small>
                                    @endif
                                </td>
                                <td class="fw-extrabold text-dark fs-6">
                                    👤 {{ $option->customer?->name ?? 'Müşteri Belirtilmemiş' }}
                                </td>
                                <td class="font-monospace text-dark fw-semibold" style="font-size: 13px;">
                                    📅 {{ $option->option_date ? $option->option_date->format('d.m.Y') : '-' }}
                                </td>
                                <td class="font-monospace fw-bold" style="font-size: 13px;">
                                    <span class="{{ $isExpired ? 'text-danger' : 'text-primary' }}">
                                        📅 {{ $option->expiry_date ? $option->expiry_date->format('d.m.Y') : '-' }}
                                    </span>
                                </td>
                                <td class="text-center font-monospace">
                                    @if($isExpired)
                                        <span class="badge bg-danger text-white rounded-pill px-3 py-1.5 font-monospace fw-bold" style="background: #ef4444 !important;">
                                            <i class="ri-alert-line me-1"></i>Süresi Doldu
                                        </span>
                                    @elseif($isAboutToExpire)
                                        <span class="badge bg-warning text-dark rounded-pill px-3 py-1.5 font-monospace fw-bold">
                                            <i class="ri-time-line me-1"></i>1 Gün Kaldı
                                        </span>
                                    @else
                                        <span class="badge bg-primary text-white rounded-pill px-3 py-1.5 font-monospace fw-bold">
                                            <i class="ri-time-line me-1"></i>{{ $option->remaining_days ?? 0 }} Gün
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center font-monospace">
                                    @if($option->status === 'active')
                                        <span class="badge bg-success text-white rounded-pill px-3 py-1.5 font-monospace fw-bold" style="background: #16a34a !important;">🟢 AKTİF</span>
                                    @elseif($option->status === 'converted_to_project')
                                        <span class="badge bg-warning text-dark rounded-pill px-3 py-1.5 font-monospace fw-bold">🚀 PROJEYE DÖNÜŞTÜ</span>
                                    @elseif($option->status === 'expired')
                                        <span class="badge bg-secondary text-white rounded-pill px-3 py-1.5 font-monospace fw-bold">⚪ SÜRESİ DOLDU</span>
                                    @else
                                        <span class="badge bg-danger text-white rounded-pill px-3 py-1.5 font-monospace fw-bold" style="background: #ef4444 !important;">🔴 İPTAL</span>
                                    @endif
                                </td>
                                <td class="pe-4 text-end">
                                    <div class="d-inline-flex gap-1">
                                        <a href="{{ route('options.show', $option) }}" class="btn btn-sm btn-warning text-dark rounded-circle p-2" title="Detay İncele">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                        @if($option->status === 'active')
                                            <a href="{{ route('options.edit', $option) }}" class="btn btn-sm btn-outline-dark rounded-circle p-2" title="Düzenle">
                                                <i class="ri-pencil-line"></i>
                                            </a>
                                        @endif
                                        @if($option->status !== 'converted_to_project')
                                            <form action="{{ route('options.destroy', $option) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle p-2" onclick="return confirmDelete(this, 'Gayrimenkul Opsiyonunu Sil?', 'Bu opsiyon kaydı silinecektir.')" title="Sil">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-5">
                                    <i class="ri-bookmark-3-line display-4 text-warning mb-2 d-block"></i>
                                    <h6 class="fw-bold text-dark">Henüz Opsiyon Kaydı Bulunmuyor</h6>
                                    <p class="small text-muted mb-0">Üstteki "+ Yeni Opsiyon Oluştur" butonuna tıklayarak ilk opsiyonunuzu ekleyebilirsiniz.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-transparent border-0 py-3 d-flex justify-content-center">
            {{ $options->links() }}
        </div>
    </div>

</div>
@endsection
