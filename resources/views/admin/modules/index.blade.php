@extends('layouts.master')

@section('title', 'Modül & Eklenti Yönetim Merkezi')
@section('topbar-title', 'Yönetim - Modül & Eklenti Merkezi')

@push('styles')
<style>
    .module-hero-banner {
        border-radius: 20px;
        padding: 2.2rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #d97706 100%);
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25);
    }
    .module-card {
        border-radius: 16px;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
        border: 1px solid rgba(226, 232, 240, 0.8);
    }
    .module-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 25px rgba(0, 0, 0, 0.08);
    }
    .module-icon-box {
        width: 54px;
        height: 54px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }
    .drag-drop-zone {
        border: 2px dashed #cbd5e1;
        border-radius: 16px;
        padding: 1.75rem 1.25rem;
        text-align: center;
        background: #f8fafc;
        transition: all 0.2s ease;
    }
    .drag-drop-zone:hover {
        border-color: #d97706;
        background: #fffbeb;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">

    <!-- Flash Messages -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm" role="alert">
        <i class="ri-checkbox-circle-fill me-2 fs-5 align-middle"></i>
        <strong>Başarılı!</strong> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm" role="alert">
        <i class="ri-error-warning-fill me-2 fs-5 align-middle"></i>
        <strong>Hata:</strong> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Hero Banner -->
    <div class="module-hero-banner text-white">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <span class="badge bg-warning text-dark px-3 py-1.5 rounded-pill fw-bold text-uppercase mb-2">
                    <i class="ri-plug-fill me-1"></i> Açık Çekirdek (Open-Core) & Lisanslı Modül Mimarisi
                </span>
                <h2 class="fw-bold mb-2">PERGEL Modül & Eklenti Merkezi</h2>
                <p class="text-white-50 mb-0 fs-6">
                    PERGEL Topluluk Çekirdeği ücretsizdir; ileri düzey modüller varsayılan olarak kilitlidir. <strong>erdemeroglu.com.tr</strong> üzerinden satın aldığınız modülleri <strong>PERGEL-XXXX</strong> lisans anahtarınızla tek tıkla indirebilir veya .ZIP paketi olarak yükleyebilirsiniz.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0 d-flex flex-wrap justify-content-lg-end gap-2">
                <a href="https://erdemeroglu.com.tr/panel/hizmetler?yazilim=pergel-pro" target="_blank" class="btn btn-warning btn-lg fw-bold px-4 py-2.5 rounded-pill shadow-sm">
                    <i class="ri-shopping-cart-2-line me-1"></i> erdemeroglu.com.tr'den Satın Al
                </a>
            </div>
        </div>
    </div>

    <!-- Lisans Anahtarı ile Tek Tıkla İndir & ZIP Yükleme Kartları -->
    <div class="row g-4 mb-4">
        <!-- 1. Lisans Anahtarı ile Uzaktan İndir & Etkinleştir -->
        <div class="col-lg-6">
            <div class="card module-card h-100 shadow-sm border-0">
                <div class="card-body p-4 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="module-icon-box bg-success bg-opacity-10 text-success">
                                <i class="ri-key-2-line"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-1">Lisans Anahtarı ile Tek Tıkla İndir & Kur</h5>
                                <p class="text-muted mb-0 small">
                                    <strong>erdemeroglu.com.tr → Panel → Hizmetlerim</strong> ekranından aldığınız lisans anahtarını girin; satın aldığınız modül paketi otomatik doğrulanıp kurulacaktır.
                                </p>
                            </div>
                        </div>

                        <form action="{{ route('admin.modules.install-license') }}" method="POST" class="mt-3">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label small fw-bold">PERGEL Lisans Anahtarı</label>
                                <input type="text" name="license_key" class="form-control font-monospace"
                                       placeholder="PERGEL-XXXX-XXXX-XXXX-XXXX"
                                       value="{{ old('license_key', $licenseState->license_key ?? '') }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Kurulacak Paket (İsteğe Bağlı)</label>
                                <select name="module_slug" class="form-select">
                                    <option value="">Lisansa Göre Otomatik Seç (Önerilen)</option>
                                    <option value="pergel-field">Saha & Taşeron Paketi (pergel-field)</option>
                                    <option value="pergel-compliance">Mevzuat & Finans Paketi (pergel-compliance)</option>
                                    <option value="pergel-pro">Full Enterprise Paketi (pergel-pro)</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-success w-100 py-2.5 rounded-pill fw-bold">
                                <i class="ri-download-cloud-2-line me-1"></i> Doğrula, İndir ve Modülü Etkinleştir
                            </button>
                        </form>
                    </div>
                    <div class="pt-3 mt-3 border-top small text-muted d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <span>Kurulum ID: <code class="fw-bold text-dark">{{ $installationId ?? '-' }}</code></span>
                        <a href="https://erdemeroglu.com.tr/panel/lisanslar" target="_blank" class="fw-bold text-decoration-none">
                            Lisanslarımı Görüntüle <i class="ri-external-link-line"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. İndirilen Modül .ZIP Paketini Manuel Yükle -->
        <div class="col-lg-6">
            <div class="card module-card h-100 shadow-sm border-0">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="module-icon-box bg-warning bg-opacity-10 text-warning">
                            <i class="ri-folder-zip-line"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-1">İndirilen Modül Paketini Yükle (.ZIP)</h5>
                            <p class="text-muted mb-0 small">
                                erdemeroglu.com.tr panelinden indirdiğiniz <code>.zip</code> modül arşivini lisans anahtarınızla birlikte yükleyin.
                            </p>
                        </div>
                    </div>

                    <form action="{{ route('admin.modules.upload') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @if(empty($licenseState->license_key))
                        <div class="mb-2">
                            <input type="text" name="license_key" class="form-control form-control-sm font-monospace"
                                   placeholder="Lisans Anahtarı: PERGEL-XXXX-XXXX-XXXX-XXXX" required>
                        </div>
                        @endif
                        <div class="drag-drop-zone">
                            <i class="ri-upload-cloud-2-line fs-2 text-warning mb-1 d-block"></i>
                            <h6 class="fw-bold mb-1">Modül ZIP Arşivini Seçin</h6>
                            <p class="text-muted small mb-2">Desteklenen: pergel-field.zip, pergel-compliance.zip, pergel-pro.zip</p>
                            <input type="file" name="module_zip" class="form-control form-control-sm w-auto d-inline-block mx-auto mb-2" accept=".zip" required>
                            <div>
                                <button type="submit" class="btn btn-dark px-4 py-2 rounded-pill fw-bold btn-sm">
                                    <i class="ri-install-line me-1"></i> ZIP Paketini Yükle ve Kur
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Yüklü Modüller Başlığı -->
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="fw-bold mb-0">
            <i class="ri-apps-2-line text-warning me-2"></i> Resmi Modül Paketleri & Durumları
        </h4>
        <span class="badge bg-secondary rounded-pill px-3 py-1.5">
            {{ count($installed) }} / {{ count($catalog) }} Modül Paketi Kurulu
        </span>
    </div>

    <!-- Modül Kartları Listesi -->
    <div class="row g-4">
        @foreach($catalog as $slug => $cat)
        <div class="col-lg-4 col-md-6">
            <div class="card module-card h-100 shadow-sm border-0">
                <div class="card-body p-4 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="module-icon-box bg-primary bg-opacity-10 text-primary">
                                <i class="{{ $cat['icon'] }}"></i>
                            </div>
                            @if($cat['installed'] && ($isLicenseActive ?? false))
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1.5 rounded-pill fw-bold">
                                    <i class="ri-checkbox-circle-fill me-1"></i> YÜKLÜ & LİSANSLI
                                </span>
                            @elseif($cat['installed'])
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-3 py-1.5 rounded-pill fw-bold">
                                    <i class="ri-lock-2-line me-1"></i> LİSANS BEKLİYOR
                                </span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary border px-3 py-1.5 rounded-pill fw-bold">
                                    <i class="ri-lock-line me-1"></i> KİLİTLİ / KURULU DEĞİL
                                </span>
                            @endif
                        </div>

                        <h5 class="fw-bold mb-1">{{ $cat['name'] }}</h5>
                        <p class="text-muted small mb-3">{{ $cat['description'] }}</p>

                        <div class="bg-light p-3 rounded-3 mb-3">
                            <div class="fw-semibold small text-dark mb-2">İçerdiği Özellikler:</div>
                            <ul class="list-unstyled mb-0 small text-muted">
                                @foreach($cat['features'] as $feat)
                                <li class="mb-1 d-flex align-items-center gap-1.5">
                                    <i class="ri-check-line text-success"></i>
                                    <span>{{ $feat }}</span>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <div class="pt-3 border-top mt-auto">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <div>
                                <span class="text-muted small d-block">Yıllık Lisans</span>
                                <span class="fs-5 fw-bold text-dark">{{ $cat['price_formatted'] }}</span>
                            </div>
                            <div class="d-flex gap-2">
                                @if($cat['installed'])
                                    <form action="{{ route('admin.modules.destroy', $slug) }}" method="POST" onsubmit="return confirm('Bu modülü kaldırmak istediğinize emin misiniz?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3">
                                            <i class="ri-delete-bin-line me-1"></i> Kaldır
                                        </button>
                                    </form>
                                @else
                                    @if(!empty($licenseState->license_key))
                                        <form action="{{ route('admin.modules.install-license') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="license_key" value="{{ $licenseState->license_key }}">
                                            <input type="hidden" name="module_slug" value="{{ $slug }}">
                                            <button type="submit" class="btn btn-success btn-sm fw-bold rounded-pill px-3">
                                                <i class="ri-download-2-line me-1"></i> İndir & Kur
                                            </button>
                                        </form>
                                    @endif
                                    <a href="{{ $cat['buy_url'] }}" target="_blank" class="btn btn-warning btn-sm fw-bold rounded-pill px-3">
                                        <i class="ri-shopping-cart-line me-1"></i> Satın Al & İndir
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

</div>
@endsection
