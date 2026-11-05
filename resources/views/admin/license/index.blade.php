@extends('layouts.master')

@section('title', 'Yazılım Lisansı & Pro Modül Yönetimi')

@section('topbar-title', 'Yönetim - Lisans Portalı')

@push('styles')
<style>
    /* Hero Header Banner */
    .lic-adm-hero-banner {
        border-radius: 20px;
        padding: 2.2rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    html[data-bs-theme="light"] .lic-adm-hero-banner,
    :root:not([data-bs-theme="dark"]) .lic-adm-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #d97706 50%, #b45309 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }

    html[data-bs-theme="dark"] .lic-adm-hero-banner {
        background: linear-gradient(135deg, rgba(217, 119, 6, 0.95) 0%, rgba(15, 23, 42, 0.95) 100%);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #f8fafc;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
    }

    /* KPI Spec Cards */
    .lic-adm-card {
        border-radius: 16px;
        padding: 1.5rem;
        height: 100%;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .lic-adm-card:hover {
        transform: translateY(-4px);
    }

    html[data-bs-theme="light"] .lic-adm-card,
    :root:not([data-bs-theme="dark"]) .lic-adm-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }

    html[data-bs-theme="dark"] .lic-adm-card {
        background: rgba(30, 41, 59, 0.75);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
    }

    .lic-adm-stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    /* Glassmorphic Container */
    .lic-adm-glass-card {
        border-radius: 20px;
        padding: 1.8rem;
        margin-bottom: 2rem;
        transition: all 0.3s ease;
    }

    html[data-bs-theme="light"] .lic-adm-glass-card,
    :root:not([data-bs-theme="dark"]) .lic-adm-glass-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    }

    html[data-bs-theme="dark"] .lic-adm-glass-card {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
</style>
@endpush

@section('content')

<!-- Flash Notifications -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-pill px-4" role="alert">
        <i class="ri-checkbox-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show rounded-pill px-4" role="alert">
        <i class="ri-error-warning-fill me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Hero Header Banner -->
<div class="lic-adm-hero-banner">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-3 py-1 fw-bold">
                    <i class="ri-key-2-line me-1"></i>Software License Management
                </span>
            </div>
            <h1 class="fw-extrabold text-white mb-1">PERGEL Pro Yazılım Lisansı</h1>
            <p class="text-white-50 mb-0 fs-6">Lisans durumu, aktif modül yetkileri ve lisans anahtarı aktivasyon merkezi.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ $renewUrl }}" target="_blank" rel="noopener" class="btn btn-light rounded-pill px-4 fw-bold text-amber shadow-sm" style="color:#d97706;">
                <i class="ri-shopping-cart-2-line me-1"></i>Pro Satın Al / Yenile
            </a>
        </div>
    </div>
</div>

<!-- 4 Top KPI Spec Cards -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="lic-adm-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Lisans Sürümü</span>
                    <h2 class="fw-extrabold mb-0 {{ $isPro ? 'text-warning' : 'text-success' }}">
                        {{ $isPro ? 'PRO Sürüm' : 'Community' }}
                    </h2>
                </div>
                <div class="lic-adm-stat-icon {{ $isPro ? 'bg-warning bg-opacity-10 text-warning' : 'bg-success bg-opacity-10 text-success' }}">
                    <i class="{{ $isPro ? 'ri-vip-crown-line' : 'ri-shield-user-line' }}"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="lic-adm-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Aktif Lisanslı Modül</span>
                    <h2 class="fw-extrabold mb-0 text-primary">{{ count($modules) }} Modül</h2>
                </div>
                <div class="lic-adm-stat-icon bg-primary bg-opacity-10 text-primary">
                    <i class="ri-apps-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="lic-adm-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Lisans Bitiş Tarihi</span>
                    <h2 class="fw-extrabold mb-0 text-dark">
                        {{ $expiresAt ? $expiresAt->format('d.m.Y') : 'Süresiz' }}
                    </h2>
                </div>
                <div class="lic-adm-stat-icon bg-secondary bg-opacity-10 text-secondary">
                    <i class="ri-calendar-event-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="lic-adm-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-bold text-uppercase d-block mb-1 small">Offline Tolerans</span>
                    <h2 class="fw-extrabold mb-0 text-amber" style="color:#d97706;">{{ $offlineGraceDays }} Gün</h2>
                </div>
                <div class="lic-adm-stat-icon bg-warning bg-opacity-10 text-amber" style="color:#d97706;">
                    <i class="ri-wifi-off-line"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- License Activation & Installation ID Info Row -->
<div class="row g-4 mb-4">
    <!-- Activation Form -->
    <div class="col-lg-6">
        <div class="lic-adm-glass-card h-100 mb-0">
            <h5 class="fw-bold text-dark mb-3">
                <i class="ri-key-line text-amber me-2" style="color:#d97706;"></i>Pro Lisans Anahtarı Girin
            </h5>
            <form method="POST" action="{{ route('admin.license.activate') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label small fw-bold text-dark">Lisans Anahtarı Kodu</label>
                    <input type="text" name="license_key" class="form-control font-monospace @error('license_key') is-invalid @enderror"
                           placeholder="PERGEL-XXXX-XXXX-XXXX-XXXX" value="{{ old('license_key') }}" required>
                    @error('license_key') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    <div class="form-text small">
                        Anahtarınızı <a href="https://erdemeroglu.com.tr/panel/hizmetler?yazilim=pergel-pro" target="_blank" rel="noopener" class="text-amber fw-bold" style="color:#d97706;">erdemeroglu.com.tr panel</a> -> Hizmetlerim menüsünden alabilirsiniz.
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-amber text-white rounded-pill px-4 fw-bold" style="background:#d97706;">
                        <i class="ri-shield-flash-line me-1"></i>Lisansı Aktifleştir
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Installation ID & Domain Transfer Info -->
    <div class="col-lg-6">
        <div class="lic-adm-glass-card h-100 mb-0">
            <h5 class="fw-bold text-dark mb-3">
                <i class="ri-server-line text-primary me-2"></i>Sunucu & Kurulum ID Eşleşmesi
            </h5>
            <div class="p-3 bg-light rounded-3 mb-3 border">
                <small class="text-muted d-block fw-bold mb-1">BU SUNUCUNUN KURULUM ID KODU:</small>
                <code class="fs-6 user-select-all fw-bold text-dark">{{ $installationId }}</code>
            </div>
            <p class="small text-muted mb-2">
                Lisans domain'e bağlı değildir; sunucu <strong>Kurulum ID</strong>'niz ile eşleşir. Sunucu veya veritabanı taşırken anahtarınız değişmez — yalnızca <strong>«Durumu Yenile»</strong> yapmanız yeterlidir.
            </p>
            <div class="d-flex gap-2 pt-2">
                <form method="POST" action="{{ route('admin.license.refresh') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-secondary rounded-pill px-3 btn-sm fw-bold">
                        <i class="ri-refresh-line me-1"></i>Durumu Yenile
                    </button>
                </form>
                @if($hasStoredKey)
                    <form method="POST" action="{{ route('admin.license.clear') }}" class="d-inline" onsubmit="return confirm('Pro lisans kaldırılıp Topluluk sürümüne dönülsün mü?');">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger rounded-pill px-3 btn-sm fw-bold">
                            <i class="ri-delete-bin-line me-1"></i>Lisansı Kaldır
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modules Comparison Matrix -->
<div class="row g-4">
    <div class="col-md-6">
        <div class="lic-adm-glass-card h-100 mb-0">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold text-dark mb-0">
                    <i class="ri-shield-check-line text-success me-2"></i>Topluluk Modülleri (Ücretsiz)
                </h5>
                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-1">Standard</span>
            </div>
            <ul class="list-group list-group-flush">
                @foreach($communityModules as $mod)
                    @php $meta = $moduleMeta[$mod] ?? null; @endphp
                    <li class="list-group-item bg-transparent d-flex justify-content-between align-items-center py-3">
                        <div class="d-flex align-items-center gap-2">
                            <i class="{{ $meta['icon'] ?? 'ri-checkbox-circle-line' }} text-success"></i>
                            <div>
                                <span class="fw-bold text-dark d-block">{{ $meta['name'] ?? ucfirst($mod) }}</span>
                                <small class="text-muted font-monospace">{{ $mod }}</small>
                            </div>
                        </div>
                        @if(in_array($mod, $modules))
                            <span class="badge bg-success bg-opacity-10 text-success border border-success rounded-pill px-3 py-1 fw-bold">
                                <i class="ri-check-line me-1"></i>Açık & Ücretsiz
                            </span>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <div class="col-md-6">
        <div class="lic-adm-glass-card h-100 mb-0">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold text-dark mb-0">
                    <i class="ri-vip-crown-line text-amber me-2" style="color:#d97706;"></i>Pro Modüller (Lisanslı)
                </h5>
                <span class="badge bg-warning bg-opacity-10 text-amber rounded-pill px-3 py-1 fw-bold" style="color:#d97706;">Enterprise Pro</span>
            </div>
            <ul class="list-group list-group-flush">
                @foreach($proModules as $mod)
                    @php $meta = $moduleMeta[$mod] ?? null; @endphp
                    <li class="list-group-item bg-transparent d-flex justify-content-between align-items-center py-3">
                        <div class="d-flex align-items-center gap-2">
                            <i class="{{ $meta['icon'] ?? 'ri-vip-crown-line' }} {{ in_array($mod, $modules) ? 'text-warning' : 'text-muted' }}"></i>
                            <div>
                                <span class="fw-bold text-dark d-block">{{ $meta['name'] ?? ucfirst($mod) }}</span>
                                <small class="text-muted font-monospace">{{ $mod }}</small>
                            </div>
                        </div>
                        @if(in_array($mod, $modules))
                            <span class="badge bg-success bg-opacity-10 text-success border border-success rounded-pill px-3 py-1 fw-bold">
                                <i class="ri-check-line me-1"></i>Lisanslı & Açık
                            </span>
                        @else
                            <a href="{{ route('admin.license.upgrade', ['module' => $mod]) }}" class="badge bg-warning bg-opacity-20 text-dark border border-warning text-decoration-none rounded-pill px-3 py-1 fw-bold">
                                <i class="ri-lock-line me-1"></i>Pro Lisans Al
                            </a>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

@endsection
