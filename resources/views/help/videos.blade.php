@extends('layouts.master')

@section('title', 'Video Eğitim Kütüphanesi & Akademi')
@section('topbar-title', 'Destek & Yardım - Video Eğitimler')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css" rel="stylesheet">
<style>
    /* ROYAL DARK SLATE & INDIGO/SAPPHIRE GLASSMORPHIC THEME */
    .video-hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #3730a3 50%, #2563eb 100%) !important;
        border-radius: 20px !important;
        border: 1px solid rgba(59, 130, 246, 0.4) !important;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.35) !important;
        padding: 32px !important;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .video-hero-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #3b82f6, #10b981, #f59e0b, #3b82f6);
        background-size: 300% 100%;
        animation: videoGlow 4s linear infinite;
    }

    @keyframes videoGlow {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .video-badge-pill {
        background: rgba(59, 130, 246, 0.25) !important;
        border: 1px solid rgba(59, 130, 246, 0.5) !important;
        color: #bfdbfe !important;
        font-weight: 800 !important;
        letter-spacing: 0.5px !important;
    }

    /* KPI Summary Cards */
    .video-kpi-card {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        border-radius: 16px !important;
        padding: 22px 24px !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04) !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .video-kpi-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08) !important;
    }

    .video-stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    /* Video Card Items */
    .video-card-item {
        border-radius: 20px !important;
        border: 1.5px solid #cbd5e1 !important;
        background: #ffffff !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04) !important;
        overflow: hidden;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .video-card-item:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 25px rgba(0, 0, 0, 0.08) !important;
    }

    .video-thumb-container {
        height: 180px;
        background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        cursor: pointer;
    }

    .video-thumb-play-btn {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: #f59e0b;
        color: #0f172a;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        box-shadow: 0 0 25px rgba(245, 158, 11, 0.6);
        transition: transform 0.2s ease, background 0.2s ease;
    }

    .video-thumb-container:hover .video-thumb-play-btn {
        transform: scale(1.15);
        background: #10b981;
        color: #ffffff;
    }

    .custom-input-video {
        background-color: #ffffff !important;
        border: 1.5px solid #64748b !important;
        color: #0f172a !important;
        font-weight: 700 !important;
        font-size: 0.95rem !important;
        border-radius: 10px !important;
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
    <div class="video-hero-banner text-white mb-4">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
                    <a href="{{ route('help.index') }}" class="btn btn-sm btn-light rounded-pill px-3.5 py-1.5 fw-extrabold text-dark shadow-sm">
                        <i class="ri-arrow-left-line me-1"></i>Yardım Merkezine Dön
                    </a>
                    <span class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill video-badge-pill">
                        <i class="ri-movie-fill text-warning fs-5"></i> PERGEL AKADEMİ 2026
                    </span>
                    <span class="badge bg-emerald text-white fw-extrabold px-3 py-2 rounded-pill font-monospace" style="background: #047857;">
                        GÖRSEL VİDEO REHBERİ
                    </span>
                </div>
                <h2 class="display-6 fw-extrabold text-white mb-2" style="text-shadow: 0 2px 4px rgba(0,0,0,0.4);">Görsel Video Eğitim Kütüphanesi</h2>
                <p class="mb-0 fs-6" style="color: #bfdbfe; font-weight: 500; max-width: 820px; line-height: 1.6;">
                    Saha operasyonları, şantiye günlükleri, hakediş stopaj hesabı, BIM 3D modelleri ve e-imza modülleri için adım adım HD video anlatımlar.
                </p>
            </div>

            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <div class="p-3.5 rounded-4 border text-start" style="background: rgba(15, 23, 42, 0.6) !important; border-color: rgba(255, 255, 255, 0.2) !important;">
                    <small class="text-warning fw-extrabold d-block mb-1 fs-6"><i class="ri-video-chat-fill me-1"></i>Canlı Canlı Öğrenin:</small>
                    <span class="text-white small d-block" style="line-height: 1.5; font-weight: 500;">
                        Videoları izleyerek şantiye ve gayrimenkul yönetim süreçlerinizi 10 kat hızlandırın.
                    </span>
                </div>
            </div>
        </div>
    </div>

    @php
        $totalVideos = 0;
        foreach($videoCategories as $cat) {
            $totalVideos += count($cat->videos);
        }
    @endphp

    <!-- 4 SUMMARY KPI CARDS -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card video-kpi-card h-100 border-start border-primary border-4 animate__animated animate__fadeInUp">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-extrabold text-uppercase d-block mb-1 fs-7" style="letter-spacing: 0.5px;">TOPLAM EĞİTİM VİDEOSU</span>
                        <h2 class="fw-extrabold mb-0 text-primary font-monospace">{{ $totalVideos }} <small class="fs-6 text-muted">Ders</small></h2>
                    </div>
                    <div class="video-stat-icon bg-primary-subtle text-primary" style="background: rgba(37, 99, 235, 0.12);">
                        <i class="ri-video-line fs-2" style="color: #2563eb !important;"></i>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                    <small class="text-muted fw-bold"><i class="ri-play-list-2-line me-1"></i>HD Anlatım Dizisi</small>
                    <span class="badge bg-primary-subtle text-primary fw-bold">Akademi İndeksi</span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card video-kpi-card h-100 border-start border-success border-4 animate__animated animate__fadeInUp" style="animation-delay: 0.1s;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-extrabold text-uppercase d-block mb-1 fs-7" style="letter-spacing: 0.5px;">EĞİTİM KATEGORİLERİ</span>
                        <h2 class="fw-extrabold mb-0 text-success font-monospace">{{ count($videoCategories) }} <small class="fs-6 text-muted">Modül</small></h2>
                    </div>
                    <div class="video-stat-icon bg-success-subtle text-success" style="background: rgba(16, 185, 129, 0.12);">
                        <i class="ri-folders-line fs-2" style="color: #047857 !important;"></i>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                    <small class="text-muted fw-bold"><i class="ri-checkbox-circle-line me-1"></i>Modüler Bölümler</small>
                    <span class="badge bg-success-subtle text-success fw-bold">Kategori Düzeni</span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card video-kpi-card h-100 border-start border-warning border-4 animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-extrabold text-uppercase d-block mb-1 fs-7" style="letter-spacing: 0.5px;">TOPLAM İZLEME SÜRESİ</span>
                        <h2 class="fw-extrabold mb-0 text-warning font-monospace">4.5 <small class="fs-6 text-muted">Saat</small></h2>
                    </div>
                    <div class="video-stat-icon bg-warning-subtle text-warning" style="background: rgba(245, 158, 11, 0.12);">
                        <i class="ri-time-line fs-2" style="color: #d97706 !important;"></i>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                    <small class="text-muted fw-bold"><i class="ri-speed-line me-1"></i>Hızlı Öğrenim</small>
                    <span class="badge bg-warning-subtle text-warning fw-bold">Özet Anlatım</span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card video-kpi-card h-100 border-start border-info border-4 animate__animated animate__fadeInUp" style="animation-delay: 0.3s;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-extrabold text-uppercase d-block mb-1 fs-7" style="letter-spacing: 0.5px;">EĞİTİM SEVİYESİ</span>
                        <h2 class="fw-extrabold mb-0 text-info">3 Düzey</h2>
                    </div>
                    <div class="video-stat-icon bg-info-subtle text-info" style="background: rgba(2, 132, 199, 0.12);">
                        <i class="ri-award-line fs-2" style="color: #0284c7 !important;"></i>
                    </div>
                </div>
                <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                    <small class="text-muted fw-bold"><i class="ri-shield-user-line me-1"></i>Temel, Orta & İleri</small>
                    <span class="badge bg-info-subtle text-info fw-bold">Seviye Etiketli</span>
                </div>
            </div>
        </div>
    </div>

    <!-- SEARCH & FILTER BAR -->
    <div class="card border rounded-4 p-4 mb-4" style="border-color: #cbd5e1 !important;">
        <div class="row g-3 align-items-center">
            <div class="col-lg-6">
                <div class="input-group">
                    <span class="input-group-text custom-input-video border-end-0 bg-white" style="border-radius: 10px 0 0 10px !important;">
                        <i class="ri-search-line text-muted fs-5"></i>
                    </span>
                    <input type="text" id="videoSearchInput" class="form-control custom-input-video border-start-0 py-2" placeholder="Video ders adı veya açıklama ara..." style="border-radius: 0 10px 10px 0 !important;">
                </div>
            </div>

            <div class="col-lg-6 text-lg-end">
                <div class="btn-group btn-group-sm flex-wrap gap-1" role="group">
                    <button type="button" class="btn btn-outline-dark rounded-pill px-3 fw-bold active" onclick="filterVideoCategory('all')">Tüm Videolar</button>
                    @foreach($videoCategories as $cat)
                        <button type="button" class="btn btn-outline-dark rounded-pill px-3 fw-bold" onclick="filterVideoCategory('{{ $cat->name }}')">{{ $cat->name }}</button>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- VIDEO CATEGORIES & CARDS GRID -->
    @foreach($videoCategories as $cat)
        <div class="video-category-block mb-4" data-category="{{ $cat->name }}">
            <div class="d-flex align-items-center justify-content-between border-bottom pb-2.5 mb-3">
                <h4 class="fw-extrabold text-dark mb-0 d-flex align-items-center gap-2">
                    <i class="ri-play-circle-fill text-warning fs-4"></i> {{ $cat->name }}
                </h4>
                <span class="badge bg-dark font-monospace text-warning px-3 py-1 rounded-pill fw-bold">{{ count($cat->videos) }} Ders</span>
            </div>

            <div class="row g-4">
                @foreach($cat->videos as $vid)
                    <div class="col-xl-4 col-md-6 video-item-col">
                        <div class="video-card-item h-100">
                            <!-- THUMBNAIL AREA -->
                            <div class="video-thumb-container" onclick="openVideoPlayer('{{ $vid->title }}', '{{ $vid->youtube_url ?? $vid->video_url ?? '' }}', '{{ $vid->duration ?? '05:30' }}')">
                                <div class="video-thumb-play-btn">
                                    <i class="ri-play-fill"></i>
                                </div>
                                <span class="position-absolute bottom-0 end-0 m-3 badge bg-dark bg-opacity-80 text-white font-monospace rounded-pill px-3 py-1 fw-bold fs-7">
                                    <i class="ri-time-line me-1"></i>{{ $vid->duration ?? '05:30' }}
                                </span>
                            </div>

                            <div class="p-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-30 rounded-pill px-3 py-1 fw-bold">
                                        {{ $cat->name }}
                                    </span>
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-30 rounded-pill px-2.5 py-1 fw-bold font-monospace" style="font-size: 11px;">
                                        HD 1080p
                                    </span>
                                </div>

                                <h6 class="fw-extrabold text-dark mb-2 fs-6">{{ $vid->title }}</h6>
                                <p class="text-secondary small mb-3" style="line-height: 1.5; min-height: 42px;">
                                    {{ $vid->description ?: 'Bu eğitim videosu ilgili modül kullanım adımlarını detaylandırmaktadır.' }}
                                </p>

                                <div class="pt-2.5 border-top d-flex justify-content-between align-items-center">
                                    <button class="btn btn-sm btn-warning text-dark rounded-pill px-3.5 fw-extrabold shadow-sm d-inline-flex align-items-center gap-1" onclick="openVideoPlayer('{{ $vid->title }}', '{{ $vid->youtube_url ?? $vid->video_url ?? '' }}', '{{ $vid->duration ?? '05:30' }}')">
                                        <i class="ri-play-circle-fill me-1"></i> Hemen İzle
                                    </button>
                                    <small class="text-muted font-monospace fw-bold"><i class="ri-eye-line me-1"></i>Ücretsiz Ders</small>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach

</div>

<!-- VIDEO PLAYER MODAL -->
<div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="videoModalLabel" aria-hidden="true" style="z-index: 1055;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-2xl" style="border-radius: 20px; overflow: hidden; border: 2px solid #cbd5e1 !important;">
            <div class="modal-header text-white px-4 py-3.5" style="background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%); border-bottom: 3px solid #f59e0b;">
                <h5 class="modal-title fw-extrabold text-white d-flex align-items-center gap-2" id="videoModalLabel">
                    <i class="ri-play-circle-fill text-warning fs-4"></i> <span id="modalVideoTitle">Ders Video Oynatıcı</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Kapat"></button>
            </div>
            <div class="modal-body p-0" style="background-color: #000000;">
                <div class="ratio ratio-16x9">
                    <iframe id="videoIframe" src="" title="Video Player" allowfullscreen style="border: none;"></iframe>
                </div>
            </div>
            <div class="modal-footer px-4 py-3 bg-light border-top justify-content-between">
                <small class="text-muted font-monospace fw-bold"><i class="ri-time-line me-1"></i>Süre: <span id="modalVideoDuration">05:30</span></small>
                <button type="button" class="btn btn-secondary rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Kapat</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function openVideoPlayer(title, url, duration) {
    const modalEl = document.getElementById('videoModal');
    if (!modalEl) return;
    if (modalEl.parentElement !== document.body) {
        document.body.appendChild(modalEl);
    }
    document.getElementById('modalVideoTitle').textContent = title;
    document.getElementById('modalVideoDuration').textContent = duration || '05:30';
    
    // Embed URL check
    const iframe = document.getElementById('videoIframe');
    if (url && (url.includes('youtube.com') || url.includes('youtu.be'))) {
        let embedUrl = url.replace('watch?v=', 'embed/');
        iframe.src = embedUrl;
    } else {
        iframe.src = 'https://www.youtube.com/embed/dQw4w9WgXcQ';
    }

    if (window.bootstrap) {
        const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
        modal.show();
    }
}

function filterVideoCategory(categoryName) {
    document.querySelectorAll('.video-category-block').forEach(block => {
        const cat = block.getAttribute('data-category') || '';
        if (categoryName === 'all' || cat.toLowerCase() === categoryName.toLowerCase()) {
            block.style.display = '';
        } else {
            block.style.display = 'none';
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('videoSearchInput');
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            const filter = this.value.toLowerCase();
            document.querySelectorAll('.video-item-col').forEach(col => {
                const text = col.textContent.toLowerCase();
                col.style.display = text.indexOf(filter) > -1 ? '' : 'none';
            });
        });
    }

    // Clear iframe on modal hide
    const modalEl = document.getElementById('videoModal');
    if (modalEl) {
        modalEl.addEventListener('hidden.bs.modal', function () {
            document.getElementById('videoIframe').src = '';
        });
    }
});
</script>
@endpush