@extends('layouts.master')

@section('title', 'BIM 3D Model Yönetimi')
@section('topbar-title', 'BIM & Dijital İkiz - 3D Modeller')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css" rel="stylesheet">
<style>
    /* ROYAL DARK SLATE & SAPPHIRE/EMERALD GLASSMORPHIC THEME */
    .bim-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #2563eb 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(37, 99, 235, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.35) !important;
        padding: 32px !important;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .bim-hero-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #3b82f6, #10b981, #f59e0b, #3b82f6);
        background-size: 300% 100%;
        animation: bimGlow 4s linear infinite;
    }

    @keyframes bimGlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .bim-badge-pill {
        background: rgba(37, 99, 235, 0.25) !important;
        border: 1px solid rgba(37, 99, 235, 0.5) !important;
        color: #bfdbfe !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    /* KPI Summary Cards */
    .bim-kpi-card {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 22px 24px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04) !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .bim-kpi-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08) !important;
    }

    .bim-stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    /* Form & Input Styling */
    .custom-input-bim {
        background-color: #ffffff !important;
        border: 1.5px solid #64748b !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        font-size: 0.95rem !important;
        border-radius: 10px !important;
    }

    .custom-input-bim:focus {
        background-color: #ffffff !important;
        border-color: #2563eb !important;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15) !important;
    }

    /* Model Card Design */
    .bim-model-card-item {
        border-radius: 18px !important;
        border: 1.5px solid #cbd5e1 !important;
        background: #ffffff !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04) !important;
        overflow: hidden;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        position: relative;
    }

    .bim-model-card-item:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 25px rgba(0, 0, 0, 0.08) !important;
    }

    .bim-thumb-container {
        height: 170px;
        background: #0f172a;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #64748b;
        position: relative;
        overflow: hidden;
    }

    .bim-thumb-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Modal Safety Fix */
    .modal {
        z-index: 1055 !important;
    }
</style>
@endpush

@section('content')

<div class="container-fluid px-4 py-3">

    <!-- HERO HEADER BANNER -->
    <div class="bim-hero-banner text-white mb-4">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill bim-badge-pill">
                        <i class="ri-box-3-fill text-warning fs-5"></i> BIM 3D YAPI BİLGİ MODELLEMESİ
                    </span>
                    <span class="badge bg-emerald text-white fw-extrabold px-3 py-2 rounded-pill font-monospace" style="background: #047857;">
                        IFC, RVT & DWG UYUMLU
                    </span>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2" style="text-shadow: 0 2px 4px rgba(0,0,0,0.4);">BIM 3D Model Yönetimi & Çakışma Analizi</h2>
                <p class="mb-0 fs-6" style="color: #bfdbfe; font-weight: 500; max-width: 820px; line-height: 1.6;">
                    Yapı disiplinlerine ait (Mimari, Statik, MEPA) 3D BIM modellerini yükleyin, web tabanlı kanvas ile görüntüleyin ve otomatik çakışma matrisi testleri çalıştırın.
                </p>
            </div>

            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <div class="d-flex flex-column gap-2 float-lg-end">
                    <button type="button" class="btn btn-warning text-dark rounded-pill py-2.5 px-4 fw-extrabold shadow-lg d-inline-flex align-items-center justify-content-center gap-2" onclick="showUploadModal()">
                        <i class="ri-upload-cloud-fill fs-4"></i> + Yeni 3D Model Yükle
                    </button>
                    <a href="{{ route('bim.simulations.index') }}" class="btn btn-outline-light rounded-pill py-2 px-3.5 fw-bold d-inline-flex align-items-center justify-content-center gap-1.5">
                        <i class="ri-play-circle-line fs-5"></i> 4D/5D Simülasyon Paneli
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm p-3 mb-4 d-flex align-items-center gap-2 bg-success text-white" role="alert">
            <i class="ri-checkbox-circle-fill fs-4"></i>
            <span class="fw-bold fs-6">{{ session('success') }}</span>
            <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- 4 SUMMARY KPI CARDS -->
    @php
        $readyCount = $models->where('status', 'ready')->count();
        $processingCount = $models->where('status', 'processing')->count();
    @endphp

    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card bim-kpi-card h-100 border-start border-primary border-4 animate__animated animate__fadeInUp">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-extrabold text-uppercase d-block mb-1 fs-7" style="letter-spacing: 0.5px;">KAYITLI 3D MODELLER</span>
                        <h2 class="fw-extrabold mb-0 text-primary font-monospace">{{ $models->total() }} <small class="fs-6 text-muted">Model</small></h2>
                    </div>
                    <div class="bim-stat-icon bg-primary-subtle text-primary" style="background: rgba(37, 99, 235, 0.12);">
                        <i class="ri-box-3-line fs-2" style="color: #2563eb !important;"></i>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                    <small class="text-muted fw-bold"><i class="ri-database-2-line me-1"></i>3D Proje İndeksi</small>
                    <span class="badge bg-primary-subtle text-primary fw-bold">BIM Portföy</span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card bim-kpi-card h-100 border-start border-success border-4 animate__animated animate__fadeInUp" style="animation-delay: 0.1s;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-extrabold text-uppercase d-block mb-1 fs-7" style="letter-spacing: 0.5px;">GÖRÜNTÜLEMEYE HAZIR</span>
                        <h2 class="fw-extrabold mb-0 text-success font-monospace">{{ $readyCount }} <small class="fs-6 text-muted">Model</small></h2>
                    </div>
                    <div class="bim-stat-icon bg-success-subtle text-success" style="background: rgba(16, 185, 129, 0.12);">
                        <i class="ri-checkbox-circle-fill fs-2" style="color: #047857 !important;"></i>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                    <small class="text-muted fw-bold"><i class="ri-eye-line me-1"></i>Web Canvas Hazır</small>
                    <span class="badge bg-success-subtle text-success fw-bold">Web3D Active</span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card bim-kpi-card h-100 border-start border-warning border-4 animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-extrabold text-uppercase d-block mb-1 fs-7" style="letter-spacing: 0.5px;">İŞLENEN MODELLER</span>
                        <h2 class="fw-extrabold mb-0 text-warning font-monospace">{{ $processingCount }} <small class="fs-6 text-muted">Model</small></h2>
                    </div>
                    <div class="bim-stat-icon bg-warning-subtle text-warning" style="background: rgba(245, 158, 11, 0.12);">
                        <i class="ri-loader-4-line fs-2" style="color: #d97706 !important;"></i>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                    <small class="text-muted fw-bold"><i class="ri-refresh-line me-1"></i>IFC Geometri Dönüşümü</small>
                    <span class="badge bg-warning-subtle text-warning fw-bold">Dönüştürülüyor</span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card bim-kpi-card h-100 border-start border-info border-4 animate__animated animate__fadeInUp" style="animation-delay: 0.3s;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-extrabold text-uppercase d-block mb-1 fs-7" style="letter-spacing: 0.5px;">BIM DEPOLAMA BOYUTU</span>
                        <h2 class="fw-extrabold mb-0 text-info font-monospace">2.4 GB</h2>
                    </div>
                    <div class="bim-stat-icon bg-info-subtle text-info" style="background: rgba(2, 132, 199, 0.12);">
                        <i class="ri-hard-drive-2-fill fs-2" style="color: #0284c7 !important;"></i>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                    <small class="text-muted fw-bold"><i class="ri-cloud-line me-1"></i>Bulut Depolama Hacmi</small>
                    <span class="badge bg-info-subtle text-info fw-bold">Sınırsız Kotası</span>
                </div>
            </div>
        </div>
    </div>

    <!-- FILTER & SEARCH BAR -->
    <div class="card border rounded-4 p-4 mb-4" style="border-color: #cbd5e1 !important;">
        <form method="GET" action="{{ route('bim.models.index') }}">
            <div class="row g-3 align-items-center">
                <div class="col-lg-5">
                    <label for="bim-search" class="form-label fw-extrabold text-dark small mb-1">Arama Kriteri</label>
                    <div class="input-group">
                        <span class="input-group-text custom-input-bim border-end-0 bg-white" style="border-radius: 10px 0 0 10px !important;">
                            <i class="ri-search-line text-muted fs-5"></i>
                        </span>
                        <input type="text" name="q" id="bim-search" class="form-control custom-input-bim border-start-0 py-2" placeholder="Model adı, disiplin veya yükleyen kişi..." value="{{ request('q') }}" style="border-radius: 0 10px 10px 0 !important;">
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <label for="discipline" class="form-label fw-extrabold text-dark small mb-1">Disiplin Filtresi</label>
                    <select name="discipline" id="discipline" class="form-select custom-input-bim py-2">
                        <option value="">Tüm Disiplinler (Mimari / Statik / MEPA)</option>
                        @foreach($disciplines as $d)
                            <option value="{{ $d }}" {{ request('discipline') == $d ? 'selected' : '' }}>{{ $d }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-2 col-md-6">
                    <label for="status" class="form-label fw-extrabold text-dark small mb-1">Durum</label>
                    <select name="status" id="status" class="form-select custom-input-bim py-2">
                        <option value="">Tüm Durumlar</option>
                        <option value="ready" {{ request('status') == 'ready' ? 'selected' : '' }}>🟢 Hazır</option>
                        <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>🟡 İşleniyor</option>
                        <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>⚪ Arşivlenmiş</option>
                    </select>
                </div>

                <div class="col-lg-2 d-flex gap-2 align-self-end">
                    <button type="submit" class="btn btn-primary text-white rounded-pill flex-fill py-2 fw-extrabold shadow-sm" style="background: #2563eb; border: none;">
                        <i class="ri-filter-3-line me-1"></i> Filtrele
                    </button>
                    <a href="{{ route('bim.models.index') }}" class="btn btn-outline-secondary rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;" title="Sıfırla">
                        <i class="ri-refresh-line"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- MODELS GRID CONTAINER -->
    <div class="row g-4 mb-4">
        @forelse($models as $m)
            <div class="col-xl-4 col-md-6">
                <div class="bim-model-card-item h-100">
                    
                    <!-- THUMBNAIL AREA -->
                    <div class="bim-thumb-container">
                        @if($m->thumbnail_path && \Illuminate\Support\Facades\Storage::exists($m->thumbnail_path))
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($m->thumbnail_path) }}" alt="{{ $m->name }}">
                        @else
                            <div class="text-center text-light">
                                <i class="ri-box-3-line display-3 d-block text-primary mb-1"></i>
                                <span class="badge bg-dark font-monospace text-warning px-3 py-1 rounded-pill" style="font-size: 11px;">
                                    {{ strtoupper($m->file_type) }} 3D MODEL
                                </span>
                            </div>
                        @endif

                        <span class="position-absolute top-0 end-0 m-3 badge bg-dark bg-opacity-75 text-white font-monospace rounded-pill px-3 py-1 fw-bold">
                            v{{ $m->version ?? '1.0' }}
                        </span>
                    </div>

                    <!-- CARD CONTENT BODY -->
                    <div class="p-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-30 rounded-pill px-3 py-1 fw-bold">
                                {{ $m->discipline ?? 'Genel Mimari' }}
                            </span>
                            @if($m->status === 'ready')
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-30 rounded-pill px-3 py-1 fw-bold">
                                    <i class="ri-checkbox-circle-fill me-1"></i>Hazır
                                </span>
                            @else
                                <span class="badge bg-warning bg-opacity-10 text-dark border border-warning border-opacity-30 rounded-pill px-3 py-1 fw-bold">
                                    <i class="ri-loader-4-line me-1"></i>İşleniyor
                                </span>
                            @endif
                        </div>

                        <h5 class="fw-extrabold text-dark mb-2 text-truncate" title="{{ $m->name }}">{{ $m->name }}</h5>
                        <p class="text-muted small mb-3" style="line-height: 1.5; min-height: 38px;">
                            {{ Str::limit($m->version_notes ?: 'Model versiyon detay açıklaması girilmemiş.', 75) }}
                        </p>

                        <div class="pt-3 border-top d-flex align-items-center justify-content-between">
                            <small class="text-muted font-monospace"><i class="ri-calendar-line me-1"></i>{{ $m->created_at ? $m->created_at->format('d.m.Y H:i') : '-' }}</small>
                            <div class="btn-group gap-1">
                                @if($m->status === 'ready')
                                    <a href="{{ route('bim.models.viewer', $m->id) }}" class="btn btn-sm btn-primary text-white rounded-pill px-3.5 fw-extrabold shadow-sm d-inline-flex align-items-center gap-1">
                                        <i class="ri-eye-line"></i> 3D Gör
                                    </a>
                                @else
                                    <button class="btn btn-sm btn-secondary rounded-pill px-3 fw-bold" disabled>İşleniyor</button>
                                @endif
                                <a href="{{ route('bim.models.file', $m->id) }}" target="_blank" class="btn btn-sm btn-outline-dark rounded-circle p-2" title="İndir">
                                    <i class="ri-download-2-line"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="card p-5 rounded-4 border border-2 border-dashed" style="border-color: #cbd5e1 !important;">
                    <i class="ri-box-3-line display-2 text-secondary mb-3 d-block"></i>
                    <h5 class="fw-bold text-dark">Henüz BIM 3D Model Bulunmuyor</h5>
                    <p class="text-muted small mb-4">İlk IFC, RVT veya DWG modelinizi sistem kaydına almak için yükleme butonunu kullanabilirsiniz.</p>
                    <div>
                        <button type="button" class="btn btn-primary rounded-pill px-4 py-2.5 fw-extrabold shadow-sm" onclick="showUploadModal()">
                            <i class="ri-upload-cloud-line me-1"></i> İlk 3D Modeli Yükle
                        </button>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-between align-items-center">
        <small class="text-muted font-monospace fw-bold">Toplam {{ $models->total() }} 3D model gösteriliyor</small>
        <div>{{ $models->links() }}</div>
    </div>

</div>

<!-- UPLOAD MODAL -->
<div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true" style="z-index: 1055;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-2xl" style="border-radius: 20px; overflow: hidden; border: 2px solid #cbd5e1 !important;">
            <div class="modal-header text-white px-4 py-3.5" style="background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%); border-bottom: 3px solid #2563eb;">
                <h5 class="modal-title fw-extrabold text-white d-flex align-items-center gap-2" id="uploadModalLabel">
                    <i class="ri-upload-cloud-fill text-warning fs-4"></i> Yeni BIM 3D Model Yükle
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Kapat"></button>
            </div>
            <form action="{{ route('bim.models.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4" style="background-color: #ffffff;">
                    <div class="row g-3 mb-3">
                        <div class="col-md-7">
                            <label for="model_name" class="form-label fw-extrabold text-dark">MODEL ADI <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="model_name" class="form-control custom-input-bim py-2.5" placeholder="Örn: A-Blok Mimari 3D Modeli" required>
                        </div>
                        <div class="col-md-5">
                            <label for="model_discipline" class="form-label fw-extrabold text-dark">DİSİPLİN <span class="text-danger">*</span></label>
                            <select name="discipline" id="model_discipline" class="form-select custom-input-bim py-2.5" required>
                                <option value="Mimari" selected>🏛️ Mimari (Architectural)</option>
                                <option value="Statik">🏗️ Statik & Betonarme (Structural)</option>
                                <option value="MEPA">⚡ MEPA / Tesisat (MEP)</option>
                                <option value="Altyapı">🛣️ Altyapı & Çevre</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="model_file" class="form-label fw-extrabold text-dark">BIM MODEL DOSYASI (.IFC, .RVT, .DWG, .PDF) <span class="text-danger">*</span></label>
                        <input type="file" name="file" id="model_file" class="form-control custom-input-bim py-2.5" accept=".ifc,.rvt,.dwg,.pdf" required>
                        <small class="text-muted mt-1 d-block">Desteklenen BIM standartları: IFC 2x3 / IFC4, Autodesk Revit RVT, AutoCAD DWG.</small>
                    </div>

                    <div class="mb-2">
                        <label for="version_notes" class="form-label fw-extrabold text-dark">VERSİYON NOTLARI & DEĞİŞİKLİK ÖZETİ</label>
                        <textarea name="version_notes" id="version_notes" class="form-control custom-input-bim" rows="3" placeholder="Bu versiyonda yapılan revizyonlar ve değişiklikler..."></textarea>
                    </div>
                </div>

                <div class="modal-footer px-4 py-3 bg-light border-top">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4 fw-bold" data-bs-dismiss="modal">İptal</button>
                    <button type="submit" class="btn btn-primary text-white rounded-pill px-5 fw-extrabold shadow-sm" style="background: #2563eb; border: none;">
                        <i class="ri-upload-2-fill me-1"></i> Modeli Yükle ve Dönüştür
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function showUploadModal() {
    const modalEl = document.getElementById('uploadModal');
    if (!modalEl) return;
    if (modalEl.parentElement !== document.body) {
        document.body.appendChild(modalEl);
    }
    if (window.bootstrap) {
        const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
        modal.show();
    }
}
</script>
@endpush
