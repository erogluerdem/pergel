@extends('layouts.master')

@section('title', 'Opsiyon Detayı — ' . ($option->asset?->name ?? 'Opsiyon'))

@section('topbar-title', 'CRM - Opsiyon Detay')

@push('styles')
<style>
    /* Hero Banner */
    .opt-show-hero {
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    html[data-bs-theme="light"] .opt-show-hero,
    :root:not([data-bs-theme="dark"]) .opt-show-hero {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    html[data-bs-theme="dark"] .opt-show-hero {
        background: linear-gradient(135deg, rgba(30, 41, 59, 0.95) 0%, rgba(15, 23, 42, 0.95) 100%);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #f8fafc;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
    }

    .opt-card-modern {
        border-radius: 18px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
    }

    html[data-bs-theme="light"] .opt-card-modern,
    :root:not([data-bs-theme="dark"]) .opt-card-modern {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
    }

    html[data-bs-theme="dark"] .opt-card-modern {
        background: rgba(30, 41, 59, 0.85);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.25);
    }
</style>
@endpush

@section('content')

@php
    $isAboutToExpire = method_exists($option, 'isAboutToExpire') ? $option->isAboutToExpire() : false;
    $isExpired = method_exists($option, 'isExpired') ? $option->isExpired() : ($option->expiry_date && $option->expiry_date->isPast());
@endphp

<!-- Hero Banner Header -->
<div class="opt-show-hero">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                @if($option->status === 'active')
                    <span class="badge bg-success rounded-pill px-3 py-1 fw-bold">🟢 AKTİF OPSİYON</span>
                @elseif($option->status === 'converted_to_project')
                    <span class="badge bg-primary rounded-pill px-3 py-1 fw-bold">🚀 PROJEYE DÖNÜŞTÜ</span>
                @elseif($option->status === 'expired')
                    <span class="badge bg-secondary rounded-pill px-3 py-1 fw-bold">⚪ SÜRESİ DOLDU</span>
                @else
                    <span class="badge bg-danger rounded-pill px-3 py-1 fw-bold">🔴 İPTAL EDİLDİ</span>
                @endif
                @if($option->asset && $option->asset->location)
                    <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-3 py-1">
                        <i class="ri-map-pin-line me-1"></i>{{ $option->asset->location }}
                    </span>
                @endif
            </div>
            <h2 class="fw-bold text-white mb-1">
                {{ $option->asset?->name ?? 'Gayrimenkul Silinmiş' }}
            </h2>
            <p class="text-white-50 mb-0">
                <i class="ri-user-3-line me-1 text-warning"></i>
                Opsiyon Sahibi Müşteri: <strong class="text-white">{{ $option->customer?->name ?? 'Belirtilmemiş' }}</strong>
            </p>
        </div>
        <div class="d-flex gap-2">
            @if($option->status === 'active')
                <a href="{{ route('options.edit', $option) }}" class="btn btn-warning text-dark fw-bold rounded-pill px-4">
                    <i class="ri-edit-line me-1"></i>Düzenle
                </a>
            @endif
            <a href="{{ route('options.index') }}" class="btn btn-outline-light rounded-pill px-4">
                <i class="ri-arrow-left-line me-1"></i>Opsiyonlar Listesi
            </a>
        </div>
    </div>
</div>

<div class="row">
    <!-- Left Column: Asset, Customer & Opportunity -->
    <div class="col-lg-7">
        <!-- Asset Card -->
        <div class="opt-card-modern">
            <h5 class="fw-bold mb-3">
                <i class="ri-building-4-line text-primary me-2"></i>Gayrimenkul / Varlık Detayları
            </h5>
            <div class="row g-3">
                <div class="col-md-6">
                    <small class="text-muted d-block">Varlık Kodu & Adı</small>
                    <span class="fs-6 fw-bold text-primary">{{ $option->asset?->name ?? 'N/A' }}</span>
                </div>
                <div class="col-md-6">
                    <small class="text-muted d-block">Alt Kategori / Tip</small>
                    <span class="fs-6 fw-bold">{{ $option->asset?->subcategory ?? ($option->asset?->category ?? 'Gayrimenkul') }}</span>
                </div>
                <div class="col-md-6">
                    <small class="text-muted d-block">Konum / Adres</small>
                    <span class="fs-6 fw-bold"><i class="ri-map-pin-line me-1 text-danger"></i>{{ $option->asset?->location ?? 'Belirtilmemiş' }}</span>
                </div>
                <div class="col-md-6">
                    <small class="text-muted d-block">Güncel Piyasa Değeri</small>
                    <span class="fs-6 fw-bold text-success">
                        ₺{{ number_format($option->asset?->current_value ?? $option->asset?->purchase_price ?? 0, 0, ',', '.') }}
                    </span>
                </div>
            </div>
            @if($option->asset && $option->asset->description)
                <div class="mt-3 pt-3 border-top border-opacity-10">
                    <small class="text-muted d-block mb-1">Açıklama</small>
                    <p class="mb-0 text-muted">{{ $option->asset->description }}</p>
                </div>
            @endif
        </div>

        <!-- Customer Card -->
        <div class="opt-card-modern">
            <h5 class="fw-bold mb-3">
                <i class="ri-user-star-line text-warning me-2"></i>Müşteri İletişim Bilgileri
            </h5>
            <div class="row g-3">
                <div class="col-md-6">
                    <small class="text-muted d-block">Müşteri Adı / Unvanı</small>
                    <span class="fs-6 fw-bold">{{ $option->customer?->name ?? 'Belirtilmemiş' }}</span>
                </div>
                <div class="col-md-6">
                    <small class="text-muted d-block">Telefon Numarası</small>
                    <span class="fs-6 fw-bold"><i class="ri-phone-line me-1 text-primary"></i>{{ $option->customer?->phone ?? 'Belirtilmemiş' }}</span>
                </div>
                <div class="col-md-6">
                    <small class="text-muted d-block">E-posta Adresi</small>
                    <span class="fs-6 fw-bold"><i class="ri-mail-line me-1 text-info"></i>{{ $option->customer?->email ?? 'Belirtilmemiş' }}</span>
                </div>
                <div class="col-md-6">
                    <small class="text-muted d-block">Şehir / Adres</small>
                    <span class="fs-6 fw-bold">{{ $option->customer?->address ?? '-' }}</span>
                </div>
            </div>
        </div>

        <!-- Related Opportunity -->
        @if($option->opportunity)
            <div class="opt-card-modern">
                <h5 class="fw-bold mb-3">
                    <i class="ri-lightbulb-line text-info me-2"></i>İlişkili Fırsat
                </h5>
                <h6 class="fw-bold mb-1">
                    <a href="{{ route('opportunities.show', $option->opportunity) }}" class="text-primary text-decoration-none">
                        {{ $option->opportunity->title }}
                    </a>
                </h6>
                <p class="text-muted small mb-3">{{ $option->opportunity->description }}</p>
                <div class="row g-2">
                    <div class="col-md-6">
                        <small class="text-muted d-block">Tahmini Fırsat Değeri</small>
                        <span class="fw-bold text-success">₺{{ number_format($option->opportunity->estimated_value_kdv ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Aşama</small>
                        <span class="badge bg-info rounded-pill px-3 py-1">{{ ucfirst($option->opportunity->stage) }}</span>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Right Column: Expiry & Actions -->
    <div class="col-lg-5">
        <!-- Expiry Card -->
        <div class="opt-card-modern text-center">
            <h5 class="fw-bold mb-3 text-start">
                <i class="ri-time-line text-danger me-2"></i>Opsiyon Süre & Geçerlilik
            </h5>
            <div class="row mb-3">
                <div class="col-6">
                    <small class="text-muted d-block mb-1">Başlama Tarihi</small>
                    <span class="fs-5 fw-bold text-primary">{{ $option->option_date ? $option->option_date->format('d.m.Y') : '-' }}</span>
                </div>
                <div class="col-6">
                    <small class="text-muted d-block mb-1">Son Geçerlilik</small>
                    <span class="fs-5 fw-bold {{ $isExpired ? 'text-danger' : 'text-success' }}">
                        {{ $option->expiry_date ? $option->expiry_date->format('d.m.Y') : '-' }}
                    </span>
                </div>
            </div>
            <div class="p-3 bg-light bg-opacity-50 rounded-4">
                <small class="text-muted d-block mb-1 fw-bold">Kalan Geçerlilik Süresi</small>
                @if($isExpired)
                    <div class="fs-4 fw-extrabold text-danger">
                        <i class="ri-alarm-warning-line me-1"></i>Süresi Doldu!
                    </div>
                @elseif($isAboutToExpire)
                    <div class="fs-4 fw-extrabold text-warning">
                        <i class="ri-time-line me-1"></i>Son 1 Gün!
                    </div>
                @else
                    <div class="fs-3 fw-extrabold text-success">
                        <i class="ri-timer-flash-line me-1"></i>{{ $option->remaining_days }} Gün Kaldı
                    </div>
                @endif
            </div>
        </div>

        <!-- Notes Card -->
        @if($option->notes)
            <div class="opt-card-modern">
                <h5 class="fw-bold mb-2">
                    <i class="ri-file-text-line text-warning me-2"></i>Özel Notlar & Şartlar
                </h5>
                <p class="text-muted mb-0">{{ $option->notes }}</p>
            </div>
        @endif

        <!-- Actions Card -->
        <div class="opt-card-modern">
            <h5 class="fw-bold mb-3">
                <i class="ri-settings-4-line text-primary me-2"></i>Opsiyon Aksiyonları
            </h5>
            <div class="d-grid gap-2">
                @if($option->status === 'active')
                    @if($option->opportunity_id)
                        <form method="POST" action="{{ route('options.convert-to-project', $option) }}">
                            @csrf
                            <button type="submit" class="btn btn-success fw-bold rounded-pill w-100 py-2" onclick="return confirm('Opsiyonu projeye dönüştürmek istediğinizden emin misiniz?')">
                                <i class="ri-rocket-line me-1"></i>Projeye Dönüştür
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('options.edit', $option) }}" class="btn btn-warning text-dark fw-bold rounded-pill py-2">
                        <i class="ri-edit-line me-1"></i>Opsiyon Şartlarını Düzenle
                    </a>

                    <form method="POST" action="{{ route('options.cancel', $option) }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger rounded-pill w-100 py-2" onclick="return confirm('Opsiyonu iptal etmek istediğinizden emin misiniz?')">
                            <i class="ri-close-circle-line me-1"></i>Opsiyonu İptal Et
                        </button>
                    </form>
                @endif

                @if($option->status !== 'converted_to_project')
                    <form action="{{ route('options.destroy', $option) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger fw-bold rounded-pill w-100 py-2" onclick="return confirm('Bu opsiyon kaydını kalıcı olarak silmek istediğinizden emin misiniz?')">
                            <i class="ri-delete-bin-line me-1"></i>Opsiyon Kaydını Sil
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

