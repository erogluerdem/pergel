@extends('layouts.master')

@section('title', 'PERGEL Modül Marketi & Paket Yönetimi')

@section('topbar-title', 'Modül Marketi & Paketler')

@push('styles')
<style>
    .marketplace-hero {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0d9488 100%);
        border-radius: 20px;
        padding: 2.5rem;
        color: #ffffff;
        position: relative;
        overflow: hidden;
        margin-bottom: 2rem;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.25);
    }
    .marketplace-hero::after {
        content: '';
        position: absolute;
        top: -40%;
        right: -5%;
        width: 350px;
        height: 350px;
        background: radial-gradient(circle, rgba(13, 148, 136, 0.3) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }
    .package-card {
        border-radius: 18px;
        transition: all 0.3s ease;
        background: #ffffff;
        border: 1px solid rgba(226, 232, 240, 0.8);
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
    }
    .package-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.08);
    }
    html[data-bs-theme="dark"] .package-card {
        background: rgba(30, 41, 59, 0.85);
        border-color: rgba(255, 255, 255, 0.08);
    }
    .package-pro-card {
        border: 2px solid rgba(245, 158, 11, 0.5) !important;
        background: linear-gradient(180deg, rgba(245, 158, 11, 0.04) 0%, rgba(255, 255, 255, 1) 100%);
    }
    html[data-bs-theme="dark"] .package-pro-card {
        background: linear-gradient(180deg, rgba(245, 158, 11, 0.08) 0%, rgba(30, 41, 59, 0.9) 100%);
    }
    .popular-badge {
        position: absolute;
        top: -14px;
        left: 50%;
        transform: translateX(-50%);
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: #ffffff;
        font-size: 0.72rem;
        font-weight: 800;
        letter-spacing: 0.5px;
        padding: 5px 16px;
        border-radius: 50px;
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4);
        z-index: 2;
    }
    .module-item-card {
        border-radius: 14px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        padding: 1rem 1.25rem;
        transition: all 0.2s ease;
    }
    html[data-bs-theme="dark"] .module-item-card {
        background: rgba(30, 41, 59, 0.6);
        border-color: rgba(255, 255, 255, 0.08);
    }
    .module-icon-box {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        flex-shrink: 0;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-3">

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm p-3 mb-4 d-flex align-items-center gap-3 bg-success text-white" role="alert">
            <i class="ri-checkbox-circle-fill fs-3"></i>
            <div>
                <h6 class="fw-bold mb-0">{{ session('success') }}</h6>
            </div>
            <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm p-3 mb-4 d-flex align-items-center gap-3 bg-danger text-white" role="alert">
            <i class="ri-error-warning-fill fs-3"></i>
            <div>
                <h6 class="fw-bold mb-0">{{ session('error') }}</h6>
            </div>
            <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Hero Header -->
    <div class="marketplace-hero">
        <div class="row align-items-center position-relative" style="z-index: 2;">
            <div class="col-lg-8">
                <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill bg-white bg-opacity-20 text-white mb-3">
                    <i class="ri-store-2-fill text-warning"></i>
                    <span class="fw-bold small">PERGEL MODÜL MARKETİ & PAKETLER</span>
                </div>
                <h1 class="fw-extrabold text-white mb-2 display-6">
                    İhtiyacınıza Göre Ölçekleyin, Anında Yükleyin
                </h1>
                <p class="text-white-50 fs-6 mb-3" style="max-width: 700px;">
                    Şantiye ve ofis ihtiyaçlarınıza özel 3 modüler paketten birini seçin; lisans anahtarınızı girerek ilgili modülleri hemen aktif hale getirin.
                </p>
                <div class="d-flex flex-wrap align-items-center gap-3">
                    <span class="badge bg-white bg-opacity-15 text-white py-2 px-3 rounded-pill fw-normal">
                        <i class="ri-shield-check-fill text-success me-1"></i>
                        Mevcut Paket: 
                        <strong class="text-warning text-uppercase ms-1">
                            @if($isPro)
                                PERGEL FULL ENTERPRISE PRO
                            @elseif($tier === 'field' || str_contains($tier, 'field'))
                                SAHA & TAŞERON PAKETİ
                            @elseif($tier === 'compliance' || str_contains($tier, 'compliance'))
                                MEVZUAT & FİNANS PAKETİ
                            @else
                                TOPLULUK SÜRÜMÜ (FREE)
                            @endif
                        </strong>
                    </span>
                    <span class="badge bg-white bg-opacity-15 text-white py-2 px-3 rounded-pill fw-normal">
                        <i class="ri-apps-2-fill text-info me-1"></i>
                        {{ count($modules) }} Aktif Modül
                    </span>
                </div>
            </div>
            <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                <div class="d-flex flex-column flex-sm-row flex-lg-column align-items-lg-end gap-2">
                    <button type="button" class="btn btn-warning text-dark rounded-pill px-4 py-2.5 fw-bold shadow-lg d-inline-flex align-items-center justify-content-center gap-2" data-bs-toggle="modal" data-bs-target="#activateModal">
                        <i class="ri-key-fill fs-5"></i> Lisans Anahtarı Gir & Yükle
                    </button>
                    <a href="{{ route('admin.license.index') }}" class="btn btn-outline-light rounded-pill px-4 py-2 fw-semibold d-inline-flex align-items-center justify-content-center gap-2">
                        <i class="ri-settings-4-line"></i> Lisans Durumu & Detaylar
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- 3'LÜ PAKET KARTLARI -->
    <div class="mb-5">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h4 class="fw-extrabold mb-1 d-flex align-items-center gap-2">
                    <i class="ri-box-3-fill text-primary"></i> Modüler Paket Seçenekleri
                </h4>
                <p class="text-muted small mb-0">erdemeroglu.com.tr üzerinden satın alıp anında kurulumunuza tanımlayabilirsiniz.</p>
            </div>
        </div>

        <div class="row g-4">
            @foreach($packages as $pkgKey => $pkg)
                @php
                    $isFullPro = ($pkgKey === 'full_enterprise' || ($pkg['slug'] ?? '') === 'pergel-pro');
                    $isCurrentPackage = false;
                    if ($isPro && $isFullPro) {
                        $isCurrentPackage = true;
                    } elseif (!$isPro && ($tier === $pkgKey || ($pkg['slug'] ?? '') === $tier || str_contains($tier, $pkgKey))) {
                        $isCurrentPackage = true;
                    }
                    $buyUrl = "https://erdemeroglu.com.tr/panel/hizmetler?yazilim=" . ($pkg['slug'] ?? 'pergel-pro');
                @endphp
                <div class="col-lg-4">
                    <div class="package-card p-4 {{ $isFullPro ? 'package-pro-card shadow' : 'shadow-sm' }}">
                        @if($isFullPro)
                            <span class="popular-badge"><i class="ri-vip-crown-fill me-1"></i>EN ÇOK TERCİH EDİLEN</span>
                        @endif

                        <div>
                            <!-- Header -->
                            <div class="d-flex align-items-center justify-content-between mb-3 {{ $isFullPro ? 'mt-2' : '' }}">
                                <span class="badge {{ $isFullPro ? 'bg-warning text-dark' : 'bg-primary bg-opacity-10 text-primary' }} fw-bold px-3 py-1.5 rounded-pill font-monospace">
                                    {{ $pkg['badge'] ?? 'Paket' }}
                                </span>
                                @if($isCurrentPackage)
                                    <span class="badge bg-success text-white px-2.5 py-1 rounded-pill">
                                        <i class="ri-check-line me-1"></i>Şu An Aktif
                                    </span>
                                @endif
                            </div>

                            <h4 class="fw-extrabold text-dark mb-1">{{ $pkg['name'] }}</h4>
                            <p class="text-muted small mb-3" style="min-height: 40px;">{{ $pkg['description'] }}</p>

                            <!-- Pricing -->
                            <div class="p-3 rounded-4 bg-light bg-opacity-75 mb-3 border border-light-subtle">
                                <div class="d-flex align-items-baseline gap-1">
                                    <span class="display-6 fw-extrabold text-dark font-monospace">
                                        {{ number_format($pkg['price'], 0, ',', '.') }} ₺
                                    </span>
                                    <span class="text-muted small fw-bold">/ yıl</span>
                                </div>
                                <div class="text-muted" style="font-size: 0.75rem;">KDV hariç • Yıllık güncelleme ve lisans dahil</div>
                            </div>

                            <!-- Limits Specs -->
                            <div class="mb-3 p-2.5 rounded-3 border border-dashed border-secondary border-opacity-25 bg-body-tertiary">
                                <div class="row g-2 text-center" style="font-size: 0.78rem;">
                                    <div class="col-4 border-end">
                                        <div class="text-muted">Proje</div>
                                        <strong class="text-dark">{{ $pkg['limits']['max_projects'] === -1 ? 'Sınırsız' : $pkg['limits']['max_projects'] }}</strong>
                                    </div>
                                    <div class="col-4 border-end">
                                        <div class="text-muted">Kullanıcı</div>
                                        <strong class="text-dark">{{ $pkg['limits']['max_users'] === -1 ? 'Sınırsız' : $pkg['limits']['max_users'] }}</strong>
                                    </div>
                                    <div class="col-4">
                                        <div class="text-muted">Varlık</div>
                                        <strong class="text-dark">{{ $pkg['limits']['max_assets'] === -1 ? 'Sınırsız' : $pkg['limits']['max_assets'] }}</strong>
                                    </div>
                                </div>
                            </div>

                            <!-- Feature List -->
                            <h6 class="fw-bold small text-uppercase text-muted mb-2">Pakete Dahil Modüller:</h6>
                            <ul class="list-unstyled mb-4 small space-y-2">
                                @foreach($pkg['features'] ?? [] as $feature)
                                    <li class="d-flex align-items-start gap-2 mb-2 text-secondary">
                                        <i class="ri-checkbox-circle-fill text-success fs-6 mt-0.5 shrink-0"></i>
                                        <span>{{ $feature }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <!-- Action Footer -->
                        <div class="pt-3 border-top">
                            @if($isCurrentPackage)
                                <button class="btn btn-outline-success w-100 rounded-pill fw-bold py-2 disabled" disabled>
                                    <i class="ri-check-double-line me-1"></i>Bu Paket Kullanımda
                                </button>
                            @else
                                <div class="d-flex flex-column gap-2">
                                    <a href="{{ $buyUrl }}" target="_blank" rel="noopener" class="btn {{ $isFullPro ? 'btn-warning text-dark' : 'btn-primary' }} w-100 rounded-pill fw-bold py-2 shadow-sm d-flex align-items-center justify-content-center gap-2">
                                        <i class="ri-shopping-bag-3-fill"></i> Satın Al (erdemeroglu.com.tr)
                                    </a>
                                    <button type="button" class="btn btn-light border w-100 rounded-pill fw-semibold py-1.5 small" data-bs-toggle="modal" data-bs-target="#activateModal">
                                        <i class="ri-key-2-line me-1"></i>Anahtarım Var, Aktifleştir
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- MODÜL ENVENTARİ VE DURUM TABLOSU -->
    <div class="card border-0 shadow-sm rounded-4 mb-5">
        <div class="card-header bg-transparent border-0 pt-4 px-4">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div>
                    <h5 class="fw-extrabold mb-1 text-dark d-flex align-items-center gap-2">
                        <i class="ri-grid-fill text-warning"></i> PERGEL Modül Kataloğu & Kilit Durumu
                    </h5>
                    <p class="text-muted small mb-0">Tüm sistem bileşenlerinin lisansınıza göre anlık erişilebilirlik durumu.</p>
                </div>
            </div>
        </div>
        <div class="card-body p-4">
            <div class="row g-3">
                @foreach($moduleMeta as $modKey => $meta)
                    @php
                        $isUnlocked = in_array($modKey, $modules, true);
                        $isCommunity = in_array($modKey, $communityModules, true);
                    @endphp
                    <div class="col-md-6 col-xl-4">
                        <div class="module-item-card h-100 d-flex align-items-center justify-content-between gap-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="module-icon-box {{ $isUnlocked ? 'bg-success bg-opacity-10 text-success' : 'bg-secondary bg-opacity-10 text-muted' }}">
                                    <i class="{{ $meta['icon'] ?? 'fas fa-cube' }}"></i>
                                </div>
                                <div>
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <h6 class="fw-bold mb-0 text-dark">{{ $meta['name'] ?? $modKey }}</h6>
                                        @if($isCommunity)
                                            <span class="badge bg-secondary bg-opacity-25 text-secondary" style="font-size: 0.65rem;">FREE</span>
                                        @else
                                            <span class="badge bg-warning bg-opacity-25 text-warning-emphasis" style="font-size: 0.65rem;">PRO</span>
                                        @endif
                                    </div>
                                    <p class="text-muted small mb-0 line-clamp-1" style="font-size: 0.78rem;">
                                        {{ Str::limit($meta['description'] ?? '', 55) }}
                                    </p>
                                </div>
                            </div>

                            <div class="text-end shrink-0">
                                @if($isUnlocked)
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-2.5 py-1 fw-bold small">
                                        <i class="ri-checkbox-circle-fill me-1"></i>Aktif
                                    </span>
                                @else
                                    <a href="{{ route('admin.license.upgrade', ['module' => $modKey]) }}" class="btn btn-sm btn-outline-warning rounded-pill px-2.5 py-1 fw-semibold small">
                                        <i class="ri-lock-2-fill me-1"></i>Kilidi Aç
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

</div>

<!-- Lisans Anahtarı Aktifleştirme Modalı -->
<div class="modal fade" id="activateModal" tabindex="-1" aria-labelledby="activateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-extrabold d-flex align-items-center gap-2" id="activateModalLabel">
                    <i class="ri-key-fill text-warning"></i> Lisans Anahtarı İle Yükle
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
            </div>
            <form action="{{ route('admin.license.activate') }}" method="POST">
                @csrf
                <div class="modal-body py-4">
                    <p class="text-muted small mb-3">
                        erdemeroglu.com.tr üzerinden satın aldığınız <strong>PERGEL-XXXX</strong> formatındaki paket veya Pro lisans anahtarınızı girin.
                    </p>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-uppercase text-muted">Lisans Anahtarı</label>
                        <input type="text" name="license_key" class="form-control form-control-lg rounded-3 font-monospace text-uppercase" placeholder="PERGEL-XXXX-XXXX-XXXX-XXXX" required autofocus>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-uppercase text-muted">Kurulum Kimliği (UUID)</label>
                        <input type="text" class="form-control font-monospace text-muted small bg-light" value="{{ $installationId }}" readonly>
                        <div class="form-text text-muted" style="font-size: 0.75rem;">Bu sunucu veya alan adı için tekil oluşturulmuş kimliktir.</div>
                    </div>

                    <div class="alert alert-info border-0 rounded-3 p-2.5 small mb-0 d-flex align-items-center gap-2">
                        <i class="ri-information-fill fs-5 text-info"></i>
                        <span>Satın aldığınız paketin kapsadığı tüm modüller anahtar doğrulandığında otomatik devreye alınır.</span>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">İptal</button>
                    <button type="submit" class="btn btn-warning text-dark rounded-pill px-4 fw-bold shadow-sm">
                        <i class="ri-checkbox-circle-fill me-1"></i> Lisansı Doğrula & Aktif Et
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
