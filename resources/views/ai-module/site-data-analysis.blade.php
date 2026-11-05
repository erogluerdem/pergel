@extends('layouts.master-without-page-title')

@section('title')
    Saha Veri & İSG Analizi - ULTIMATE AI Pro
@endsection

@section('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<style>
    /* THEME INTEGRATION: Dynamically connects to Pergel Multi-Theme System */
    :root,
    [data-theme] {
        --ai-page-bg: var(--pt-body-bg, #090D16);
        --ai-card-bg: var(--pt-card-bg, rgba(21, 29, 44, 0.92));
        --ai-card-border: var(--pt-card-border, rgba(255, 255, 255, 0.12));
        --ai-text-primary: var(--pt-heading-color, #FFFFFF);
        --ai-text-secondary: var(--pt-body-color, #CBD5E1);
        --ai-text-muted: var(--pt-text-muted, #94A3B8);
        --ai-feed-bg: var(--pt-table-head-bg, rgba(15, 23, 42, 0.75));
        --ai-chip-bg: var(--pt-primary-subtle, rgba(245, 158, 11, 0.12));
        --ai-input-bg: var(--pt-input-bg, rgba(15, 23, 42, 0.95));
        --ai-shadow: var(--pt-card-shadow, 0 20px 50px rgba(0, 0, 0, 0.4));
        --ai-primary: var(--pt-primary, #F59E0B);
        --ai-primary-hover: var(--pt-primary-hover, #D97706);
    }

    /* Force background overrides & TOPBAR PADDING OVERLAP FIX */
    .main-content {
        background: var(--ai-page-bg) !important;
        transition: background 0.3s ease;
    }

    .page-content {
        background: var(--ai-page-bg) !important;
        min-height: 100vh;
        padding-top: 95px !important; /* Proper spacing below fixed topbar */
        transition: background 0.3s ease;
    }

    /* Prevent AOS opacity hide bug */
    [data-aos] {
        opacity: 1 !important;
        transform: none !important;
        visibility: visible !important;
    }

    body {
        font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
        background: var(--ai-page-bg) !important;
        color: var(--ai-text-primary) !important;
        transition: background 0.3s ease, color 0.3s ease;
    }

    .site-data-wrapper {
        padding: 5px 0 60px 0;
    }

    /* Hero Header */
    .ai-hero-header {
        background: var(--ai-card-bg);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid var(--ai-card-border);
        border-radius: 24px;
        padding: 24px 30px;
        margin-bottom: 24px;
        box-shadow: var(--ai-shadow);
        position: relative;
        overflow: hidden;
    }

    .ai-hero-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #6366F1, #06B6D4, #10B981, #F59E0B, #6366F1);
        background-size: 300% 100%;
        animation: aiGlowBar 4s linear infinite;
    }

    @keyframes aiGlowBar {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .ai-title-badge {
        background: rgba(99, 102, 241, 0.15);
        border: 1px solid rgba(99, 102, 241, 0.35);
        color: #6366F1;
        padding: 5px 14px;
        border-radius: 30px;
        font-size: 13px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .ai-title-badge .dot {
        width: 8px;
        height: 8px;
        background-color: #10B981;
        border-radius: 50%;
        box-shadow: 0 0 10px #10B981;
        animation: pulseDot 1.8s infinite;
    }

    @keyframes pulseDot {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.4; transform: scale(1.3); }
    }

    .ai-hero-title {
        font-size: 2.1rem;
        font-weight: 800;
        color: var(--ai-text-primary) !important;
        margin: 10px 0 4px 0;
        letter-spacing: -0.5px;
    }

    .ai-hero-title span {
        background: linear-gradient(135deg, #6366F1 0%, #06B6D4 50%, #10B981 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    /* Cards & Panels */
    .ai-card {
        background: var(--ai-card-bg);
        border: 1px solid var(--ai-card-border);
        border-radius: 24px;
        padding: 24px;
        box-shadow: var(--ai-shadow);
        height: 100%;
    }

    .stat-icon-wrapper {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    .icon-indigo { background: rgba(99, 102, 241, 0.18); color: #6366F1; }
    .icon-cyan { background: rgba(6, 182, 212, 0.18); color: #06B6D4; }
    .icon-emerald { background: rgba(16, 185, 129, 0.18); color: #10B981; }
    .icon-rose { background: rgba(244, 63, 94, 0.18); color: #F43F5E; }

    /* High Contrast Badges */
    .ai-badge {
        padding: 6px 14px;
        border-radius: 30px;
        font-size: 0.78rem;
        font-weight: 800 !important;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        letter-spacing: 0.3px;
    }

    .ai-badge-amber { background: rgba(245, 158, 11, 0.15) !important; color: #D97706 !important; border: 1px solid rgba(245, 158, 11, 0.3) !important; }
    .ai-badge-danger { background: rgba(244, 63, 94, 0.15) !important; color: #E11D48 !important; border: 1px solid rgba(244, 63, 94, 0.3) !important; }
    .ai-badge-success { background: rgba(16, 185, 129, 0.15) !important; color: #059669 !important; border: 1px solid rgba(16, 185, 129, 0.3) !important; }
    .ai-badge-info { background: rgba(6, 182, 212, 0.15) !important; color: #0891B2 !important; border: 1px solid rgba(6, 182, 212, 0.3) !important; }


    /* Camera Feed Card */
    .camera-card {
        background: #000;
        border-radius: 18px;
        overflow: hidden;
        position: relative;
        height: 220px;
        border: 1px solid var(--ai-card-border);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
    }

    .camera-overlay {
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at center, transparent 40%, rgba(0, 0, 0, 0.8) 100%);
        padding: 16px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    /* Upload Zone */
    .media-dropzone {
        border: 2px dashed rgba(99, 102, 241, 0.4);
        border-radius: 20px;
        padding: 30px;
        text-align: center;
        background: var(--ai-feed-bg);
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .media-dropzone:hover {
        border-color: #6366F1;
        background: rgba(99, 102, 241, 0.1);
    }
</style>
@endsection

@section('content')
<div class="site-data-wrapper">
    <div class="container-fluid px-2 px-md-4">

        <!-- HERO HEADER -->
        <div class="ai-hero-header">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="d-flex align-items-center gap-2 flex-wrap mb-2">
                        <div class="ai-title-badge">
                            <span class="dot"></span>
                            12 AI Camera & Drone Streams Active
                        </div>

                        <select id="siteSelector" class="form-select form-select-sm rounded-pill fw-bold" style="width: auto; background: var(--ai-chip-bg); color: var(--ai-text-primary); border-color: var(--ai-card-border);" onchange="updateSiteData(this.value)">
                            <option value="all" selected>🏗️ Tüm Şantiyeler (Genel İzleme)</option>
                            <option value="gokturk">🏗️ Göktürk Kemer Country Şantiyesi</option>
                            <option value="bursa">🏭 Bursa OSB Fabrika Şantiyesi</option>
                            <option value="pergel">🏢 Pergel Tower Şantiyesi</option>
                        </select>
                    </div>

                    <h1 class="ai-hero-title">
                        Saha Veri & <span>Computer Vision Analiz Platformu</span>
                    </h1>
                    <p class="ai-hero-subtitle">
                        Şantiyedeki 4K kameralar, drone görüntüleri ve IoT puantaj sensörlerini görüntü işleme (Object Detection) modelleriyle anlık izleyen saha zekası.
                    </p>
                </div>

                <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                    <div class="d-flex align-items-center justify-content-lg-end gap-2 flex-wrap">
                        <button type="button" class="btn btn-outline-secondary rounded-pill px-3 py-2 fw-bold" onclick="if(window.PergelTheme) window.PergelTheme.open();" title="Sektör Teması Seç">
                            <i class="mdi mdi-palette text-warning"></i> Sektör Teması
                        </button>

                        <button type="button" class="btn btn-outline-secondary rounded-pill px-3 py-2 fw-bold" onclick="toggleAiTheme()" title="Gece / Gündüz Modu Değiştir">
                            <span id="themeToggleIcon">☀️</span> <span id="themeToggleText">Gündüz Modu</span>
                        </button>

                        <a href="{{ route('ai-module.index') }}" class="btn btn-primary rounded-pill px-4 py-2 fw-bold">
                            <i class="fas fa-arrow-left me-1"></i> Komuta Merkezi
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- AI SUB-MODULE NAVIGATION RIBBON -->
        @include('ai-module._subnav')

        <!-- SITE METRICS ROW -->
        <div class="row g-4 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="ai-card">
                    <div class="stat-header">
                        <span class="stat-label">Sahadaki Toplam Çalışan</span>
                        <div class="stat-icon-wrapper icon-indigo">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <div class="fs-1 fw-bold mb-1" id="valWorkers">127 Kişi</div>
                    <div class="stat-subtext text-success">
                        <i class="fas fa-check-circle me-1"></i> %100 Baret & Yelek Taraması Tamam
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="ai-card">
                    <div class="stat-header">
                        <span class="stat-label">Aktif İş Makineleri</span>
                        <div class="stat-icon-wrapper icon-amber">
                            <i class="fas fa-truck-monster"></i>
                        </div>
                    </div>
                    <div class="fs-1 fw-bold text-warning mb-1" id="valMachines">15 Araç</div>
                    <div class="stat-subtext text-info">
                        <i class="fas fa-tachometer-alt me-1"></i> %94 Telemetri Verimliliği
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="ai-card">
                    <div class="stat-header">
                        <span class="stat-label">Günlük İmalat Hızı</span>
                        <div class="stat-icon-wrapper icon-emerald">
                            <i class="fas fa-chart-line"></i>
                        </div>
                    </div>
                    <div class="fs-1 fw-bold text-emerald mb-1" style="color: #10B981;" id="valVelocity">+%2.3 / Gün</div>
                    <div class="stat-subtext text-emerald">
                        <i class="fas fa-arrow-up me-1"></i> Hedeflenen Hızın Üzerinde
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="ai-card">
                    <div class="stat-header">
                        <span class="stat-label">İSG & KKD İhlal Tespiti</span>
                        <div class="stat-icon-wrapper icon-rose">
                            <i class="fas fa-user-shield"></i>
                        </div>
                    </div>
                    <div class="fs-1 fw-bold text-success mb-1" id="valViolations">0 Kritik</div>
                    <div class="stat-subtext text-muted">
                        <i class="fas fa-shield-alt me-1"></i> Kamera AI Taraması Aktif
                    </div>
                </div>
            </div>
        </div>

        <!-- LIVE AI CAMERA STREAMS SECTION -->
        <h5 class="fw-bold mb-3 d-flex align-items-center gap-2">
            <i class="fas fa-video text-warning"></i> Canlı Şantiye AI Kamera Taraması & Görüntü İşleme
        </h5>

        <div class="row g-4 mb-4">
            <!-- CAMERA 1 -->
            <div class="col-xl-3 col-md-6">
                <div class="camera-card">
                    <div class="camera-overlay">
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="badge bg-danger rounded-pill px-2 py-1"><i class="fas fa-circle fs-6 me-1"></i> CAM-01 CANLI</span>
                            <span class="badge bg-dark bg-opacity-75 text-light">Şantiye Giriş & Puantaj</span>
                        </div>
                        <div>
                            <div class="fw-bold text-white fs-6">Kamera 1: Ana Giriş Taraması</div>
                            <small class="text-success"><i class="fas fa-check-double me-1"></i> KKD Uyum: %100 (45 Kişi)</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CAMERA 2 -->
            <div class="col-xl-3 col-md-6">
                <div class="camera-card">
                    <div class="camera-overlay">
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="badge bg-danger rounded-pill px-2 py-1"><i class="fas fa-circle fs-6 me-1"></i> CAM-02 CANLI</span>
                            <span class="badge bg-dark bg-opacity-75 text-light">Kule Vinç & Yüksekte Çalışma</span>
                        </div>
                        <div>
                            <div class="fw-bold text-white fs-6">Kamera 2: Vinç & İskele Alanı</div>
                            <small class="text-info"><i class="fas fa-wind me-1"></i> Rüzgar Hızı: 12 km/s (Güvenli)</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CAMERA 3 -->
            <div class="col-xl-3 col-md-6">
                <div class="camera-card">
                    <div class="camera-overlay">
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="badge bg-danger rounded-pill px-2 py-1"><i class="fas fa-circle fs-6 me-1"></i> CAM-03 CANLI</span>
                            <span class="badge bg-dark bg-opacity-75 text-light">Betonarme & Kalıp Sahası</span>
                        </div>
                        <div>
                            <div class="fw-bold text-white fs-6">Kamera 3: Beton Döküm Sahası</div>
                            <small class="text-warning"><i class="fas fa-thermometer-half me-1"></i> Beton Sıcaklığı: 22°C</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CAMERA 4 -->
            <div class="col-xl-3 col-md-6">
                <div class="camera-card">
                    <div class="camera-overlay">
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="badge bg-danger rounded-pill px-2 py-1"><i class="fas fa-circle fs-6 me-1"></i> CAM-04 CANLI</span>
                            <span class="badge bg-dark bg-opacity-75 text-light">Depo & Malzeme Sahası</span>
                        </div>
                        <div>
                            <div class="fw-bold text-white fs-6">Kamera 4: Demir & Çelik Deposu</div>
                            <small class="text-success"><i class="fas fa-lock me-1"></i> Stok Güvenliği: %100 Korumalı</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- AI IMAGE & DRONE FOOTAGE ANALYZER DROPZONE -->
        <div class="row g-4 mb-4">
            <div class="col-lg-7">
                <div class="ai-card">
                    <h5 class="fw-bold mb-3 d-flex align-items-center gap-2">
                        <i class="fas fa-cloud-upload-alt text-indigo" style="color: #6366F1;"></i> Fotoğraf & Drone Görüntüsü AI Taraması
                    </h5>
                    <p class="text-muted font-size-13 mb-3">
                        Şantiyeden çekilen bir fotoğraf veya drone görseli yükleyin; Yapay Zeka donatı, baret ihlali ve imalat ilerlemesini otomatik tespit etsin.
                    </p>

                    <div class="media-dropzone" onclick="triggerSampleScan()">
                        <i class="fas fa-camera-retro fs-1 text-indigo mb-2" style="color: #6366F1;"></i>
                        <h6 class="fw-bold">Görsel Yükleyin veya Örnek Analiz Başlatın</h6>
                        <small class="text-muted">Desteklenen Formatlar: JPG, PNG, MP4, Drone Orthophoto</small>
                    </div>

                    <!-- AI DETECTION RESULT BOX -->
                    <div id="scanResultBox" class="mt-3 p-3 rounded-3" style="display: none; background: rgba(16, 185, 129, 0.12); border: 1px solid rgba(16, 185, 129, 0.4);">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <strong class="text-success">🔍 AI Görüntü Analiz Sonucu:</strong>
                                <p class="mb-0 font-size-13 mt-1">
                                    • <strong>Baret & Yelek Taraması:</strong> 18 Personel Tespit Edildi (%100 KKD Uyumlu)<br>
                                    • <strong>İskele Bağlantı İncelemesi:</strong> Güvenlik ağı tam takılı<br>
                                    • <strong>Beton Kalıp Donatı Skoru:</strong> ISO standartlarına tam uygun
                                </p>
                            </div>
                            <span class="badge bg-success">Doğruluk: %99.1</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- APEXCHARTS FIELD ACTIVITY CHART -->
            <div class="col-lg-5">
                <div class="ai-card">
                    <h5 class="fw-bold mb-3 d-flex align-items-center gap-2">
                        <i class="fas fa-chart-line text-emerald" style="color: #10B981;"></i> Saatlik Saha Çalışan & Makine Yoğunluğu
                    </h5>
                    <div id="siteActivityChart" style="min-height: 280px;"></div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script>
    let activityChart;

    document.addEventListener('DOMContentLoaded', function () {
        if (typeof AOS !== 'undefined') {
            AOS.init({ duration: 800, once: true });
        }

        // Sync AI module toggle UI with Pergel Theme
        const isDark = window.PergelTheme ? window.PergelTheme.getDark() : (localStorage.getItem('pergel_dark') === 'true');
        updateAiThemeToggleUI(isDark);

        // Initialize Activity Chart
        initActivityChart();
    });

    function toggleAiTheme() {
        if (window.PergelTheme) {
            const isDark = window.PergelTheme.getDark();
            window.PergelTheme.apply(window.PergelTheme.getTheme(), !isDark);
            updateAiThemeToggleUI(!isDark);
        } else {
            const isDark = localStorage.getItem('pergel_dark') === 'true';
            localStorage.setItem('pergel_dark', String(!isDark));
            updateAiThemeToggleUI(!isDark);
        }
    }

    function updateAiThemeToggleUI(isDark) {
        const iconEl = document.getElementById('themeToggleIcon');
        const textEl = document.getElementById('themeToggleText');
        if (iconEl && textEl) {
            iconEl.innerText = isDark ? '☀️' : '🌙';
            textEl.innerText = isDark ? 'Gündüz Modu' : 'Gece Modu';
        }
    }

    function updateSiteData(siteKey) {
        const w = document.getElementById('valWorkers');
        const m = document.getElementById('valMachines');
        const v = document.getElementById('valVelocity');

        if (siteKey === 'gokturk') {
            w.innerText = '58 Kişi';
            m.innerText = '7 Araç';
            v.innerText = '+%3.1 / Gün';
        } else if (siteKey === 'bursa') {
            w.innerText = '42 Kişi';
            m.innerText = '5 Araç';
            v.innerText = '+%2.8 / Gün';
        } else if (siteKey === 'pergel') {
            w.innerText = '27 Kişi';
            m.innerText = '3 Araç';
            v.innerText = '+%1.9 / Gün';
        } else {
            w.innerText = '127 Kişi';
            m.innerText = '15 Araç';
            v.innerText = '+%2.3 / Gün';
        }
    }

    function triggerSampleScan() {
        const resBox = document.getElementById('scanResultBox');
        resBox.style.display = 'block';
    }

    function initActivityChart() {
        const options = {
            series: [{
                name: 'Saha Çalışanı',
                data: [20, 45, 110, 127, 125, 90, 40]
            }, {
                name: 'İş Makinesi',
                data: [4, 8, 15, 15, 14, 10, 5]
            }],
            chart: {
                height: 280,
                type: 'line',
                toolbar: { show: false },
                foreColor: '#94A3B8'
            },
            colors: ['#6366F1', '#F59E0B'],
            stroke: { curve: 'smooth', width: 3 },
            xaxis: {
                categories: ['08:00', '10:00', '12:00', '14:00', '16:00', '18:00', '20:00']
            }
        };
        activityChart = new ApexCharts(document.querySelector("#siteActivityChart"), options);
        activityChart.render();
    }
</script>
@endsection