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
        padding: 2.5rem 1.5rem;
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
                    <i class="ri-plug-fill me-1"></i> Açık Çekirdek & Modüler Mimari
                </span>
                <h2 class="fw-bold mb-2">PERGEL Modül & Eklenti Merkezi</h2>
                <p class="text-white-50 mb-0 fs-6">
                    PERGEL Topluluk Çekirdeği ücretsiz ve açık kaynaktır. Resmi inşaat hakedişleri, taşeron portalı, ÇŞB pozları ve yapay zeka gibi ileri düzey modülleri <strong>erdemeroglu.com.tr</strong> üzerinden temin edebilir ve tek tıkla yükleyebilirsiniz.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <a href="https://erdemeroglu.com.tr/yazilimlar/pergel" target="_blank" class="btn btn-warning btn-lg fw-bold px-4 py-2.5 rounded-pill shadow-sm">
                    <i class="ri-shopping-cart-2-line me-1"></i> Modül Mağazasına Git
                </a>
            </div>
        </div>
    </div>

    <!-- ZIP Yükleme Kartı -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card module-card shadow-sm border-0">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                        <div class="d-flex align-items-center gap-3">
                            <div class="module-icon-box bg-warning bg-opacity-10 text-warning">
                                <i class="ri-folder-zip-line"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-1">Yeni Modül Paketi Yükle (.ZIP)</h5>
                                <p class="text-muted mb-0 small">erdemeroglu.com.tr üzerinden satın aldığınız veya indirdiğiniz modül .zip paketini buraya yükleyin.</p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('admin.modules.upload') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="drag-drop-zone">
                            <i class="ri-upload-cloud-2-line fs-1 text-warning mb-2 d-block"></i>
                            <h6 class="fw-bold mb-1">Modül ZIP Arşivini Seçin</h6>
                            <p class="text-muted small mb-3">Desteklenen: .zip (Örn: pergel-field.zip, pergel-compliance.zip)</p>
                            <input type="file" name="module_zip" class="form-control w-auto d-inline-block mx-auto mb-3" accept=".zip" required>
                            <div>
                                <button type="submit" class="btn btn-dark px-4 py-2 rounded-pill fw-bold">
                                    <i class="ri-install-line me-1"></i> Paketi Yükle ve Aktifleştir
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
            {{ count($installed) }} / {{ count($catalog) }} Modül Aktif
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
                            @if($cat['installed'])
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1.5 rounded-pill fw-bold">
                                    <i class="ri-checkbox-circle-fill me-1"></i> YÜKLÜ & AKTİF
                                </span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary border px-3 py-1.5 rounded-pill fw-bold">
                                    KURULU DEĞİL
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
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted small d-block">Paket Bedeli</span>
                                <span class="fs-5 fw-bold text-dark">{{ $cat['price_formatted'] }}</span>
                            </div>
                            <div>
                                @if($cat['installed'])
                                    <form action="{{ route('admin.modules.destroy', $slug) }}" method="POST" onsubmit="return confirm('Bu modülü kaldırmak istediğinize emin misiniz?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3">
                                            <i class="ri-delete-bin-line me-1"></i> Kaldır
                                        </button>
                                    </form>
                                @else
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
