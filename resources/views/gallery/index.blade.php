@extends('layouts.master')

@section('title', 'Şantiye Medya & Fotoğraf Galerisi')
@section('topbar-title', 'Dijital Arşiv - Medya Galerisi')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css" rel="stylesheet">
<style>
    /* ROYAL DARK SLATE & AMBER GLASSMORPHIC THEME */
    .gallery-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #78350f 50%, #d97706 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.35) !important;
        padding: 32px !important;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .gallery-hero-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #f59e0b, #10b981, #3b82f6, #f59e0b);
        background-size: 300% 100%;
        animation: galleryGlow 4s linear infinite;
    }

    @keyframes galleryGlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .gallery-badge-pill {
        background: rgba(245, 158, 11, 0.25) !important;
        border: 1px solid rgba(245, 158, 11, 0.5) !important;
        color: #fef08a !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    /* KPI Summary Cards */
    .gallery-kpi-card {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 22px 24px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04) !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .gallery-kpi-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08) !important;
    }

    .gallery-stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .custom-input-gallery {
        background-color: #ffffff !important;
        border: 1.5px solid #64748b !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        font-size: 0.95rem !important;
        border-radius: 10px !important;
    }

    .custom-input-gallery:focus {
        background-color: #ffffff !important;
        border-color: #d97706 !important;
        box-shadow: 0 0 0 3px rgba(217, 119, 6, 0.15) !important;
    }

    /* Cards */
    .gallery-card-item {
        border-radius: 16px !important;
        border: 1.5px solid #cbd5e1 !important;
        background: #ffffff !important;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.03) !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
    }

    .gallery-card-item:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08) !important;
        border-color: #d97706 !important;
    }

    .dark-table-header {
        background-color: #0f172a !important;
        color: #ffffff !important;
    }

    .dark-table-header th {
        color: #ffffff !important;
        font-weight: 800 !important;
        border: none !important;
        padding: 14px 16px !important;
        font-size: 0.85rem !important;
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
    <div class="gallery-hero-banner text-white mb-4">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill gallery-badge-pill">
                        <i class="ri-image-fill text-warning fs-5"></i> ŞANTİYE SAHASI DİJİTAL MEDYA ARŞİVİ
                    </span>
                    <span class="badge bg-warning text-dark fw-extrabold px-3 py-2 rounded-pill font-monospace" style="font-size: 11px;">
                        {{ $stats['total_files'] ?? 0 }} Medya Dosyası
                    </span>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2" style="text-shadow: 0 2px 4px rgba(0,0,0,0.4);">Şantiye Medya & Fotoğraf Galerisi</h2>
                <p class="mb-0 fs-6" style="color: #fef08a; font-weight: 500; max-width: 820px; line-height: 1.6;">
                    Şantiye sahası imalat fotoğrafları, drone çekimleri, 3D görselleştirmeler, şantiye arşiv dökümanları ve medya kütüphanesi.
                </p>
            </div>

            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <div class="d-flex justify-content-lg-end gap-2 flex-wrap">
                    <button class="btn btn-warning text-dark rounded-pill px-4 py-2.5 fw-extrabold d-inline-flex align-items-center gap-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#uploadModal">
                        <i class="ri-upload-cloud-fill fs-5"></i>+ Dosya Yükle
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- 4 SUMMARY KPI CARDS BAR -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card gallery-kpi-card h-100 border-start border-warning border-4 animate__animated animate__fadeInUp">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-extrabold text-uppercase d-block mb-1 fs-7" style="letter-spacing: 0.5px;">TOPLAM DOSYA</span>
                        <h2 class="fw-extrabold mb-0 text-warning font-monospace">{{ $stats['total_files'] ?? 0 }} <small class="fs-6 text-muted">Öğe</small></h2>
                    </div>
                    <div class="gallery-stat-icon bg-warning-subtle text-warning" style="background: rgba(245, 158, 11, 0.12);">
                        <i class="ri-folder-open-line fs-2" style="color: #d97706 !important;"></i>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                    <small class="text-muted fw-bold"><i class="ri-file-list-3-line me-1"></i>Kayıtlı Medya</small>
                    <span class="badge bg-warning-subtle text-warning fw-bold">Tüm Arşiv</span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card gallery-kpi-card h-100 border-start border-success border-4 animate__animated animate__fadeInUp" style="animation-delay: 0.1s;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-extrabold text-uppercase d-block mb-1 fs-7" style="letter-spacing: 0.5px;">FOTOĞRAF ARŞİVİ</span>
                        <h2 class="fw-extrabold mb-0 text-success font-monospace">{{ $stats['images'] ?? 0 }} <small class="fs-6 text-muted">Resim</small></h2>
                    </div>
                    <div class="gallery-stat-icon bg-success-subtle text-success" style="background: rgba(16, 185, 129, 0.12);">
                        <i class="ri-image-2-line fs-2" style="color: #047857 !important;"></i>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                    <small class="text-muted fw-bold"><i class="ri-camera-line me-1"></i>JPG, PNG, WEBP</small>
                    <span class="badge bg-success-subtle text-success fw-bold">Yüksek Çözünürlük</span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card gallery-kpi-card h-100 border-start border-primary border-4 animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-extrabold text-uppercase d-block mb-1 fs-7" style="letter-spacing: 0.5px;">VİDEOLAR & DRONE</span>
                        <h2 class="fw-extrabold mb-0 text-primary font-monospace">{{ $stats['videos'] ?? 0 }} <small class="fs-6 text-muted">Video</small></h2>
                    </div>
                    <div class="gallery-stat-icon bg-primary-subtle text-primary" style="background: rgba(37, 99, 235, 0.12);">
                        <i class="ri-video-line fs-2" style="color: #2563eb !important;"></i>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                    <small class="text-muted fw-bold"><i class="ri-film-line me-1"></i>MP4, MOV</small>
                    <span class="badge bg-primary-subtle text-primary fw-bold">4K Çekimler</span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card gallery-kpi-card h-100 border-start border-info border-4 animate__animated animate__fadeInUp" style="animation-delay: 0.3s;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-extrabold text-uppercase d-block mb-1 fs-7" style="letter-spacing: 0.5px;">DİSK KULLANIMI</span>
                        <h2 class="fw-extrabold mb-0 text-info font-monospace">{{ $stats['total_size'] ?? '2.5 GB' }}</h2>
                    </div>
                    <div class="gallery-stat-icon bg-info-subtle text-info" style="background: rgba(2, 132, 199, 0.12);">
                        <i class="ri-hard-drive-2-line fs-2" style="color: #0284c7 !important;"></i>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                    <small class="text-muted fw-bold"><i class="ri-cloud-line me-1"></i>Bulut Depolama</small>
                    <span class="badge bg-info-subtle text-info fw-bold">Aktif Altyapı</span>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN CONTENT CONTAINER -->
    <div class="card border rounded-4 shadow-sm overflow-hidden bg-white mb-4" style="border-color: #cbd5e1 !important;">
        <div class="card-header bg-dark text-white p-3.5 d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
            <h5 class="card-title text-white mb-0 d-flex align-items-center gap-2">
                <i class="ri-gallery-fill text-warning fs-5"></i>Saha Medya Kütüphanesi
            </h5>
            <div class="d-flex align-items-center gap-2">
                <div class="btn-group btn-group-sm">
                    <button type="button" class="btn btn-outline-light active" id="btnGridView" onclick="setView('grid')"><i class="ri-grid-fill me-1"></i>Izgara</button>
                    <button type="button" class="btn btn-outline-light" id="btnListView" onclick="setView('list')"><i class="ri-list-check me-1"></i>Liste</button>
                </div>
            </div>
        </div>

        <div class="card-body p-4">
            <!-- SEARCH AND FILTERS BAR -->
            <div class="row g-3 mb-4">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text custom-input-gallery border-end-0 bg-white" style="border-radius: 10px 0 0 10px !important;">
                            <i class="ri-search-2-line text-muted fs-5"></i>
                        </span>
                        <input type="text" id="gallerySearchInput" class="form-control custom-input-gallery border-start-0 py-2.5" placeholder="Klasör veya dosya adı ara..." style="border-radius: 0 10px 10px 0 !important;">
                    </div>
                </div>
                <div class="col-md-3">
                    <select class="form-select custom-input-gallery py-2.5" id="fileTypeFilter">
                        <option value="">Tüm Dosya Türleri</option>
                        <option value="image">🖼️ Fotoğraflar</option>
                        <option value="video">🎥 Videolar</option>
                        <option value="document">📄 Dökümanlar</option>
                    </select>
                </div>
                <div class="col-md-4 text-md-end">
                    <button class="btn btn-warning text-dark rounded-pill px-4 py-2.5 fw-extrabold shadow-sm d-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#uploadModal">
                        <i class="ri-upload-cloud-line fs-5"></i> Dosya Yükle
                    </button>
                </div>
            </div>

            <!-- FOLDERS SECTION -->
            <h6 class="fw-extrabold text-dark text-uppercase mb-3 d-flex align-items-center gap-2" style="letter-spacing: 0.5px;">
                <i class="ri-folder-shared-fill text-warning fs-5"></i> Şantiye Klasörleri
            </h6>
            <div class="row g-3 mb-4">
                @foreach($folders as $folder)
                    <div class="col-xl-3 col-md-4 col-sm-6 gallery-search-item">
                        <div class="card gallery-card-item p-3.5 h-100" style="cursor: pointer;" onclick="navigateToFolder('{{ $folder['path'] }}')">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div class="avatar-sm bg-warning bg-opacity-10 rounded-3 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                                    <i class="ri-folder-fill fs-2" style="color: #d97706 !important;"></i>
                                </div>
                                <span class="badge bg-dark font-monospace text-warning">{{ $folder['item_count'] }} Öğe</span>
                            </div>
                            <h6 class="fw-extrabold text-dark mb-1 search-item-title">{{ $folder['name'] }}</h6>
                            <small class="text-muted font-monospace"><i class="ri-time-line me-1"></i>Güncelleme: {{ $folder['modified_date'] }}</small>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- FILES GRID VIEW -->
            <h6 class="fw-extrabold text-dark text-uppercase mb-3 d-flex align-items-center gap-2" style="letter-spacing: 0.5px;">
                <i class="ri-file-list-fill text-primary fs-5"></i> Medya Dosyaları
            </h6>
            
            <div id="grid-view" class="row g-3">
                @foreach($files as $file)
                    <div class="col-xl-3 col-md-4 col-sm-6 gallery-search-item" data-file-type="{{ $file['type'] }}">
                        <div class="card gallery-card-item p-3 h-100">
                            <div class="position-relative mb-2 rounded-3 overflow-hidden" style="height: 150px; background: #f8fafc;">
                                @if($file['type'] == 'image')
                                    <img src="{{ $file['url'] }}" class="w-100 h-100" style="object-fit: cover;" alt="{{ $file['name'] }}" onerror="this.src='https://images.unsplash.com/photo-1541888946425-d0fbb186a5b7?auto=format&fit=crop&w=400&q=80'">
                                @elseif($file['type'] == 'video')
                                    <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-dark text-white">
                                        <i class="ri-play-circle-fill display-4 text-warning"></i>
                                    </div>
                                @else
                                    <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center text-primary">
                                        <i class="ri-file-pdf-fill display-4 text-danger"></i>
                                        <span class="badge bg-danger text-white mt-1 font-monospace">{{ strtoupper($file['extension']) }}</span>
                                    </div>
                                @endif
                                <span class="badge bg-dark font-monospace text-warning position-absolute top-0 end-0 m-2">{{ $file['size'] }}</span>
                            </div>

                            <h6 class="fw-extrabold text-dark mb-1 small search-item-title text-truncate">{{ $file['name'] }}</h6>
                            <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                                <small class="text-muted font-monospace">{{ $file['date'] }}</small>
                                <div class="d-flex gap-1">
                                    <button class="btn btn-sm btn-outline-primary rounded-circle p-1.5" onclick="viewFile('{{ $file['name'] }}', '{{ $file['url'] }}')" title="Görüntüle">
                                        <i class="ri-eye-line"></i>
                                    </button>
                                    <a href="{{ $file['download_url'] }}" download class="btn btn-sm btn-outline-success rounded-circle p-1.5" title="İndir">
                                        <i class="ri-download-2-line"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- FILES LIST VIEW -->
            <div id="list-view" class="table-responsive" style="display: none;">
                <table class="table table-hover align-middle mb-0">
                    <thead class="dark-table-header text-uppercase">
                        <tr>
                            <th class="ps-4">Dosya Adı</th>
                            <th>Tür</th>
                            <th>Boyut</th>
                            <th>Tarih</th>
                            <th class="pe-4 text-end">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($files as $file)
                            <tr class="gallery-search-item">
                                <td class="ps-4 py-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="ri-file-fill text-warning fs-4"></i>
                                        <span class="fw-extrabold text-dark search-item-title">{{ $file['name'] }}</span>
                                    </div>
                                </td>
                                <td><span class="badge bg-dark text-warning font-monospace">{{ strtoupper($file['extension']) }}</span></td>
                                <td class="font-monospace fw-bold text-dark">{{ $file['size'] }}</td>
                                <td class="font-monospace text-muted">{{ $file['date'] }}</td>
                                <td class="pe-4 text-end">
                                    <div class="d-inline-flex gap-1">
                                        <button class="btn btn-sm btn-outline-primary rounded-circle p-2" onclick="viewFile('{{ $file['name'] }}', '{{ $file['url'] }}')">
                                            <i class="ri-eye-line"></i>
                                        </button>
                                        <a href="{{ $file['download_url'] }}" download class="btn btn-sm btn-outline-success rounded-circle p-2">
                                            <i class="ri-download-2-line"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</div>

<!-- UPLOAD MODAL -->
<div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true" style="z-index: 1055;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-2xl" style="border-radius: 20px; overflow: hidden; border: 2px solid #cbd5e1 !important;">
            <div class="modal-header text-white px-4 py-3.5" style="background: linear-gradient(135deg, #0f172a 0%, #78350f 100%); border-bottom: 3px solid #f59e0b;">
                <h5 class="modal-title fw-extrabold text-white d-flex align-items-center gap-2" id="uploadModalLabel">
                    <i class="ri-upload-cloud-fill text-warning fs-4"></i> Medya Dosyası Yükle
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Kapat"></button>
            </div>
            
            <div class="modal-body p-4 bg-white text-center">
                <div class="p-5 border-2 border-dashed rounded-4 mb-3" style="border-color: #cbd5e1 !important; background: #f8fafc;">
                    <i class="ri-cloud-upload-line display-3 text-warning mb-3 d-block"></i>
                    <h5 class="fw-extrabold text-dark mb-1">Saha Dosyalarını Buraya Sürükleyin</h5>
                    <p class="text-muted small mb-3">Fotoğraflar, Drone Videoları, Proje PDF Dökümanları (Max 50MB)</p>
                    <input type="file" class="form-control custom-input-gallery w-75 mx-auto" multiple accept="image/*,video/*,.pdf">
                </div>
            </div>

            <div class="modal-footer px-4 py-3 bg-light border-top justify-content-between">
                <button type="button" class="btn btn-outline-secondary rounded-pill px-4 fw-bold" data-bs-dismiss="modal">İptal</button>
                <button type="button" class="btn btn-warning text-dark rounded-pill px-5 py-2.5 fw-extrabold shadow-sm" data-bs-dismiss="modal">
                    <i class="ri-check-line me-1"></i> Yüklemeyi Tamamla
                </button>
            </div>
        </div>
    </div>
</div>

<!-- FILE VIEWER MODAL -->
<div class="modal fade" id="fileViewerModal" tabindex="-1" aria-hidden="true" style="z-index: 1055;">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content border-0 shadow-2xl" style="border-radius: 20px; overflow: hidden; border: 2px solid #cbd5e1 !important;">
            <div class="modal-header bg-dark text-white px-4 py-3">
                <h5 class="modal-title fw-extrabold text-white" id="viewerFileName">Dosya Önizleme</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 text-center bg-dark">
                <img id="viewerImg" src="" class="img-fluid rounded-3 shadow" style="max-height: 70vh;" alt="Önizleme" onerror="this.src='https://images.unsplash.com/photo-1541888946425-d0fbb186a5b7?auto=format&fit=crop&w=800&q=80'">
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function setView(view) {
    if (view === 'grid') {
        document.getElementById('grid-view').style.display = '';
        document.getElementById('list-view').style.display = 'none';
        document.getElementById('btnGridView').classList.add('active');
        document.getElementById('btnListView').classList.remove('active');
    } else {
        document.getElementById('grid-view').style.display = 'none';
        document.getElementById('list-view').style.display = '';
        document.getElementById('btnListView').classList.add('active');
        document.getElementById('btnGridView').classList.remove('active');
    }
}

function viewFile(name, url) {
    document.getElementById('viewerFileName').textContent = name;
    document.getElementById('viewerImg').src = url;
    const modal = new bootstrap.Modal(document.getElementById('fileViewerModal'));
    modal.show();
}

function navigateToFolder(path) {
    console.log('Navigating to folder:', path);
}

document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('gallerySearchInput');
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            const filter = this.value.toLowerCase();
            document.querySelectorAll('.gallery-search-item').forEach(item => {
                const title = item.querySelector('.search-item-title')?.textContent.toLowerCase() || '';
                if (title.indexOf(filter) > -1) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    }

    // Modal safety reparent
    ['uploadModal', 'fileViewerModal'].forEach(id => {
        const el = document.getElementById(id);
        if (el && el.parentElement !== document.body) {
            document.body.appendChild(el);
        }
    });
});
</script>
@endpush