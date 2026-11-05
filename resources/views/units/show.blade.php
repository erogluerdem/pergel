@extends('layouts.master')

@section('title', 'Birim Detayı — ' . $unit->unit_no)

@push('styles')
<style>
    .unit-show-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #b45309 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.4) !important;
        padding: 32px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .unit-show-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #d97706, #2563eb, #16a34a, #d97706);
        background-size: 300% 100%;
        animation: unitShowGlow 4s linear infinite;
    }

    @keyframes unitShowGlow {
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

    .custom-kpi-card-unitshow {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 20px 22px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .custom-kpi-card-unitshow:hover {
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
    <div class="unit-show-banner text-white">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill unit-badge-pill font-monospace">
                        <i class="ri-building-line text-warning fs-5"></i> Birim #{{ $unit->unit_no }}
                    </span>
                    @if($unit->status === 'available')
                        <span class="badge bg-success text-white rounded-pill px-3 py-2 font-monospace fw-bold" style="background: #16a34a !important;">🟢 MÜSAİT (SATIŞTA)</span>
                    @elseif($unit->status === 'reserved')
                        <span class="badge bg-warning text-dark rounded-pill px-3 py-2 font-monospace fw-bold">🟡 OPSİYONLU / REZERVE</span>
                    @elseif($unit->status === 'sold')
                        <span class="badge bg-danger text-white rounded-pill px-3 py-2 font-monospace fw-bold" style="background: #ef4444 !important;">🔴 SATILDI</span>
                    @else
                        <span class="badge bg-secondary text-white rounded-pill px-3 py-2 font-monospace fw-bold">🔧 BAKIMDA</span>
                    @endif
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2">{{ $unit->unit_type }}</h2>
                <p class="mb-0 fs-6" style="max-width: 780px; line-height: 1.6; color: #e2e8f0; font-weight: 500;">
                    <i class="ri-building-2-fill text-warning me-1"></i> Proje: <strong class="text-white fw-bold">{{ $unit->project->name ?? 'Projesiz / Serbest' }}</strong>
                    @if($unit->block || $unit->floor)
                        • {{ $unit->block ? $unit->block : '' }}{{ $unit->floor ? ' (Kat ' . $unit->floor . ')' : '' }}
                    @endif
                </p>
            </div>

            <div class="col-lg-5 text-lg-end mt-3 mt-lg-0">
                <div class="d-flex align-items-center justify-content-lg-end gap-2 flex-wrap">
                    <a href="{{ route('units.edit', $unit) }}" class="btn btn-warning text-dark rounded-pill px-3.5 py-2.5 fw-extrabold shadow-sm">
                        <i class="ri-edit-fill me-1"></i>Düzenle
                    </a>
                    <a href="{{ route('units.pricing', $unit) }}" class="btn btn-outline-light rounded-pill px-3.5 py-2.5 fw-extrabold">
                        <i class="ri-price-tag-3-line me-1"></i>Fiyatlandırma
                    </a>
                    <a href="{{ route('units.index') }}" class="btn btn-outline-light rounded-pill px-3.5 py-2.5 fw-extrabold">
                        <i class="ri-arrow-left-line me-1"></i>Birimler Listesi
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- FINANCIAL KPI SUMMARY CARDS -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card custom-kpi-card-unitshow h-100 border-start border-primary border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">LİSTE FİYATI</small>
                        <div class="fw-extrabold text-primary font-monospace" style="font-size: 1.7rem;">₺{{ number_format($unit->list_price, 0, ',', '.') }}</div>
                        <small class="text-primary fw-bold"><i class="ri-price-tag-line me-1"></i>Brüt Liste Fiyatı</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #eff6ff !important; border: 1px solid #bfdbfe !important;">
                        <i class="ri-price-tag-3-fill fs-2" style="color: #2563eb !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card custom-kpi-card-unitshow h-100 border-start border-success border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">NET SATIŞ FİYATI</small>
                        <div class="fw-extrabold text-success font-monospace" style="font-size: 1.7rem;">₺{{ number_format($unit->selling_price, 0, ',', '.') }}</div>
                        <small class="text-success fw-bold"><i class="ri-checkbox-circle-fill me-1"></i>Hedef Net Tutar</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #f0fdf4 !important; border: 1px solid #bbf7d0 !important;">
                        <i class="ri-money-dollar-circle-fill fs-2" style="color: #16a34a !important;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card custom-kpi-card-unitshow h-100 border-start border-warning border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 11px;">MAX İNDİRİM ORANI (%{{ number_format($unit->max_discount_rate, 0) }})</small>
                        <div class="fw-extrabold text-warning font-monospace" style="font-size: 1.7rem;">₺{{ number_format($unit->max_discount_amount ?? 0, 0, ',', '.') }}</div>
                        <small class="text-warning fw-bold"><i class="ri-percent-line me-1"></i>Maksimum İskonto Tutarı</small>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 52px; height: 52px; background: #fffbeb !important; border: 1px solid #fde68a !important;">
                        <i class="ri-percent-fill fs-2" style="color: #d97706 !important;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN DETAILS GRID -->
    <div class="row">
        <!-- LEFT COLUMN: SPECS & TECHNICAL DETAILS -->
        <div class="col-lg-8">
            <div class="card custom-show-card">
                <div class="card-header bg-dark text-white p-3.5" style="background-color: #0f172a !important;">
                    <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                        <i class="ri-ruler-2-line text-warning fs-5"></i>Teknik Mimari & Ölçüm Bilgileri
                    </h5>
                </div>

                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <small class="text-muted d-block fw-bold text-uppercase" style="font-size: 11px;">Birim No / Kod</small>
                            <span class="fs-6 fw-extrabold text-primary font-monospace">🏢 {{ $unit->unit_no }}</span>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block fw-bold text-uppercase" style="font-size: 11px;">Birim Tipi / Formatı</small>
                            <span class="fs-6 fw-extrabold text-dark">{{ $unit->unit_type }}</span>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block fw-bold text-uppercase" style="font-size: 11px;">Net Kullanım Alanı</small>
                            <span class="fs-6 fw-extrabold text-success font-monospace">📐 {{ number_format($unit->area, 2, ',', '.') }} m²</span>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block fw-bold text-uppercase" style="font-size: 11px;">Blok ve Kat Konumu</small>
                            <span class="fs-6 fw-extrabold text-dark">📍 {{ $unit->block ?? 'Blok Belirtilmemiş' }} {{ $unit->floor ? '(Kat ' . $unit->floor . ')' : '' }}</span>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block fw-bold text-uppercase" style="font-size: 11px;">Oda Düzeni</small>
                            <span class="fs-6 fw-extrabold text-warning">🛏️ {{ $unit->room_count }} Oda</span>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block fw-bold text-uppercase" style="font-size: 11px;">Banyo Sayısı</small>
                            <span class="fs-6 fw-extrabold text-info">🚿 {{ $unit->bathroom_count }} Banyo</span>
                        </div>
                    </div>

                    @if ($unit->description)
                        <div class="mt-4 pt-3 border-top">
                            <small class="text-dark d-block mb-2 fw-extrabold"><i class="ri-file-text-line text-warning me-1"></i>Birim Açıklaması & Notlar</small>
                            <div class="p-3.5 rounded-3 fw-semibold text-dark" style="background: #f8fafc; border: 1px solid #cbd5e1; line-height: 1.6;">
                                {{ $unit->description }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: PROJECT & RESERVATIONS -->
        <div class="col-lg-4">
            <!-- PROJECT CARD -->
            <div class="card custom-show-card">
                <div class="card-header bg-dark text-white p-3.5" style="background-color: #0f172a !important;">
                    <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                        <i class="ri-building-line text-warning fs-5"></i>Bağlı Olduğu Proje
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <small class="text-muted d-block fw-bold text-uppercase" style="font-size: 11px;">Proje Adı</small>
                        <span class="fs-6 fw-extrabold text-primary">🏗️ {{ $unit->project->name ?? 'Proje Bağlantısı Yok' }}</span>
                    </div>
                    @if($unit->project && $unit->project->code)
                        <div>
                            <small class="text-muted d-block fw-bold text-uppercase" style="font-size: 11px;">Proje Kodu</small>
                            <span class="fw-extrabold text-dark font-monospace">{{ $unit->project->code }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- RESERVATION HISTORY CARD -->
            <div class="card custom-show-card">
                <div class="card-header bg-dark text-white p-3.5" style="background-color: #0f172a !important;">
                    <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                        <i class="ri-history-line text-warning fs-5"></i>Opsiyon & Rezervasyon Geçmişi
                    </h5>
                </div>
                <div class="card-body p-3.5">
                    @if ($unit->reservations && $unit->reservations->count() > 0)
                        <div class="list-group list-group-flush rounded-3">
                            @foreach ($unit->reservations->take(5) as $reservation)
                                <a href="{{ route('sales-reservations.show', $reservation) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-2.5 px-2 rounded-3 mb-1">
                                    <div>
                                        <strong class="d-block text-primary font-monospace">{{ $reservation->option_no }}</strong>
                                        <small class="text-dark fw-bold">👤 {{ $reservation->customer->name ?? 'Müşteri' }}</small>
                                    </div>
                                    <span class="badge bg-warning text-dark rounded-pill px-2.5 py-1 font-monospace fw-bold" style="font-size: 11px;">{{ ucfirst($reservation->status) }}</span>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted mb-0 small fw-semibold py-2 text-center">
                            <i class="ri-inbox-line me-1 text-warning"></i>Bu birim için henüz aktif opsiyon kaydı yok.
                        </p>
                    @endif
                </div>
            </div>

            <!-- SYSTEM META & DELETE -->
            <div class="card custom-show-card">
                <div class="card-body p-4">
                    <small class="text-muted d-block fw-bold text-uppercase" style="font-size: 11px;">Sistem Kayıt Tarihi</small>
                    <span class="fw-bold text-dark d-block mb-3 font-monospace">📅 {{ $unit->created_at ? $unit->created_at->format('d.m.Y H:i') : '-' }}</span>
                    
                    <form action="{{ route('units.destroy', $unit) }}" method="POST" class="d-grid">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger rounded-pill py-2.5 fw-extrabold" onclick="return confirmDelete(this, 'Bağımsız Bölüm Kaydını Sil?', 'Bu birim kaydı kalıcı olarak silinecektir.')">
                            <i class="ri-delete-bin-line me-1"></i>Birim Kaydını Sil
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
