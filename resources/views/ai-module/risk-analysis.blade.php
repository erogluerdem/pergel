@extends('layouts.master-without-page-title')

@section('title')
    AI Risk Analiz Motoru - ULTIMATE AI Pro
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

    .risk-analysis-wrapper {
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
        background: linear-gradient(90deg, #F43F5E, #F59E0B, #6366F1, #10B981, #F43F5E);
        background-size: 300% 100%;
        animation: aiGlowBar 4s linear infinite;
    }

    @keyframes aiGlowBar {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .ai-title-badge {
        background: rgba(244, 63, 94, 0.15);
        border: 1px solid rgba(244, 63, 94, 0.35);
        color: #F43F5E;
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
        background: linear-gradient(135deg, #F43F5E 0%, #F59E0B 50%, #6366F1 100%);
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

    .icon-rose { background: rgba(244, 63, 94, 0.18); color: #F43F5E; }
    .icon-amber { background: rgba(245, 158, 11, 0.18); color: #F59E0B; }
    .icon-indigo { background: rgba(99, 102, 241, 0.18); color: #6366F1; }
    .icon-emerald { background: rgba(16, 185, 129, 0.18); color: #10B981; }

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


    /* Risk Table */
    .risk-table {
        color: var(--ai-text-primary) !important;
        vertical-align: middle;
    }

    .risk-table th {
        background: var(--ai-feed-bg) !important;
        color: var(--ai-text-muted) !important;
        font-weight: 800;
        text-transform: uppercase;
        font-size: 0.78rem;
        border-bottom: 1px solid var(--ai-card-border);
        padding: 14px 16px;
    }

    .risk-table td {
        padding: 16px;
        border-bottom: 1px solid var(--ai-card-border);
        color: var(--ai-text-primary) !important;
        font-size: 0.9rem;
    }

    /* Simulator Controls */
    .sim-slider {
        accent-color: #6366F1;
    }
</style>
@endsection

@section('content')
<div class="risk-analysis-wrapper">
    <div class="container-fluid px-2 px-md-4">

        <!-- HERO HEADER -->
        <div class="ai-hero-header">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="d-flex align-items-center gap-2 flex-wrap mb-2">
                        <div class="ai-title-badge">
                            <span class="dot"></span>
                            ISO 31000 Risk Standard & Prediction Engine
                        </div>
                    </div>

                    <h1 class="ai-hero-title">
                        AI <span>Risk Analiz Motoru & Simülatörü</span>
                    </h1>
                    <p class="ai-hero-subtitle">
                        Şantiyelerin bütçe, zaman, İSG, kalite ve yasal risk haritasını 5 farklı boyutta simüle eden yapay zeka karar destek motoru.
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

        <!-- RISK OVERVIEW KPI CARDS -->
        <div class="row g-4 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="ai-card">
                    <div class="stat-header">
                        <span class="stat-label">Genel Portföy Risk Skoru</span>
                        <div class="stat-icon-wrapper icon-rose">
                            <i class="fas fa-shield-virus"></i>
                        </div>
                    </div>
                    <div class="fs-1 fw-bold text-danger mb-1">%14.2</div>
                    <div class="stat-subtext text-success">
                        <i class="fas fa-check-circle me-1"></i> Düşük Risk Seviyesinde
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="ai-card">
                    <div class="stat-header">
                        <span class="stat-label">Zaman & Gecikme Riski</span>
                        <div class="stat-icon-wrapper icon-amber">
                            <i class="fas fa-history"></i>
                        </div>
                    </div>
                    <div class="fs-1 fw-bold text-warning mb-1">%16.5</div>
                    <div class="stat-subtext text-muted">
                        <i class="fas fa-clock me-1"></i> Ortalama 4 Gün Tolerans
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="ai-card">
                    <div class="stat-header">
                        <span class="stat-label">Bütçe Aşım İhtimali</span>
                        <div class="stat-icon-wrapper icon-indigo">
                            <i class="fas fa-coins"></i>
                        </div>
                    </div>
                    <div class="fs-1 fw-bold text-indigo mb-1" style="color: #6366F1;">%11.8</div>
                    <div class="stat-subtext text-success">
                        <i class="fas fa-shield-alt me-1"></i> Fiyat Dalgalanması Kontrol Altında
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="ai-card">
                    <div class="stat-header">
                        <span class="stat-label">İSG & Güvenlik Uyum Skoru</span>
                        <div class="stat-icon-wrapper icon-emerald">
                            <i class="fas fa-hard-hat"></i>
                        </div>
                    </div>
                    <div class="fs-1 fw-bold text-emerald mb-1" style="color: #10B981;">%94.0</div>
                    <div class="stat-subtext text-emerald">
                        <i class="fas fa-check-double me-1"></i> TS 18001 Tam Uyumlu
                    </div>
                </div>
            </div>
        </div>

        <!-- CHARTS & RISK SIMULATOR SECTION -->
        <div class="row g-4 mb-4">
            <!-- 5-AXIS RISK RADAR -->
            <div class="col-lg-6">
                <div class="ai-card">
                    <h5 class="fw-bold mb-3 d-flex align-items-center gap-2">
                        <i class="fas fa-crosshairs text-warning"></i> 5 Boyutlu Risk Matrisi Simülasyonu
                    </h5>
                    <div id="riskMatrixRadar" style="min-height: 320px;"></div>
                </div>
            </div>

            <!-- INTERACTIVE AI RISK SIMULATOR WIDGET -->
            <div class="col-lg-6">
                <div class="ai-card d-flex flex-column justify-content-between">
                    <div>
                        <h5 class="fw-bold mb-3 d-flex align-items-center gap-2">
                            <i class="fas fa-sliders-h text-indigo" style="color: #6366F1;"></i> İnteraktif AI Risk Senaryo Simülatörü
                        </h5>
                        <p class="text-muted font-size-13 mb-4">
                            Aşağıdaki parametreleri değiştirerek portföyünüz üzerindeki simüle edilmiş risk artışını canlı gözlemleyin:
                        </p>

                        <!-- SLIDER 1: MATERIAL PRICE -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between font-size-13 fw-bold mb-1">
                                <span>🏗️ Malzeme & Fiyat Artış Artışı:</span>
                                <span class="text-warning" id="simValMaterial">+%10</span>
                            </div>
                            <input type="range" class="form-range sim-slider" min="0" max="40" value="10" oninput="runRiskSimulation()">
                        </div>

                        <!-- SLIDER 2: WEATHER DAYS -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between font-size-13 fw-bold mb-1">
                                <span>🌧️ Olumsuz Hava Şartı Durdurması:</span>
                                <span class="text-info" id="simValWeather">5 Gün</span>
                            </div>
                            <input type="range" class="form-range sim-slider" min="0" max="30" value="5" oninput="runRiskSimulation()">
                        </div>

                        <!-- SLIDER 3: SUBCONTRACTOR DELAY -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between font-size-13 fw-bold mb-1">
                                <span>👷 Taşeron İş Gücü Gecikmesi:</span>
                                <span class="text-danger" id="simValSubcontractor">%15 Kapasite</span>
                            </div>
                            <input type="range" class="form-range sim-slider" min="0" max="50" value="15" oninput="runRiskSimulation()">
                        </div>
                    </div>

                    <div class="p-3 rounded-3 mt-3 d-flex align-items-center justify-content-between" style="background: rgba(244, 63, 94, 0.12); border: 1px dashed rgba(244, 63, 94, 0.4);">
                        <div>
                            <div class="fw-bold text-danger">Simüle Edilmiş Portföy Risk Skoru</div>
                            <small class="text-muted">Anlık AI Hesabı</small>
                        </div>
                        <div class="fs-2 fw-bold text-danger" id="simResultScore">%19.4</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- DETAILED PROJECTS RISK TABLE -->
        <div class="ai-card">
            <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
                <div>
                    <h5 class="fw-bold mb-1 d-flex align-items-center gap-2">
                        <i class="fas fa-list-alt text-warning"></i> Proje Bazlı Detaylı Risk Haritası ve AI Tavsiyeleri
                    </h5>
                    <small class="text-muted">Aktif 20 projenizin anlık risk değerleri ve önerilen aksiyonlar</small>
                </div>
                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-2 fw-bold" onclick="alert('⚡ AI Risk Raporu PDF olarak hazırlandı.')">
                    <i class="fas fa-file-export me-1"></i> Tüm Raporu Dışa Aktar (PDF)
                </button>
            </div>

            <div class="table-responsive">
                <table class="table risk-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Proje Adı</th>
                            <th>İlerleme %</th>
                            <th>Gecikme Riski</th>
                            <th>Maliyet Riski</th>
                            <th>İSG Riski</th>
                            <th>Genel Risk Skoru</th>
                            <th>Durum</th>
                            <th class="text-end">AI Aksiyon Önerisi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($risks) && count($risks) > 0)
                            @foreach($risks as $index => $r)
                                <tr>
                                    <td class="fw-bold text-muted">{{ $index + 1 }}</td>
                                    <td class="fw-bold">{{ $r['project'] }}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="progress flex-grow-1" style="height: 6px; background: rgba(255,255,255,0.1);">
                                                <div class="progress-bar bg-success" style="width: {{ $r['progress'] ?? 50 }}%;"></div>
                                            </div>
                                            <span class="fw-bold font-size-13">%{{ $r['progress'] ?? 50 }}</span>
                                        </div>
                                    </td>
                                    <td><span class="text-warning fw-bold">%{{ $r['delay_risk'] }}</span></td>
                                    <td><span class="text-indigo fw-bold" style="color: #6366F1;">%{{ $r['cost_risk'] }}</span></td>
                                    <td><span class="text-success fw-bold">%{{ $r['safety_risk'] }}</span></td>
                                    <td>
                                        <span class="fs-6 fw-bold {{ $r['overall_risk'] > 40 ? 'text-danger' : ($r['overall_risk'] > 25 ? 'text-warning' : 'text-success') }}">
                                            %{{ $r['overall_risk'] }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($r['overall_risk'] > 40)
                                            <span class="ai-badge ai-badge-danger">Kritik Risk</span>
                                        @elseif($r['overall_risk'] > 25)
                                            <span class="ai-badge ai-badge-amber">Orta Risk</span>
                                        @else
                                            <span class="ai-badge ai-badge-success">Düşük Risk</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <button class="btn btn-xs btn-outline-secondary rounded-pill px-3 py-1 fw-bold" onclick="alert('📌 AI Önerisi: {{ implode(', ', $r['recommendations'] ?? ['İş programı stabil']) }}')">
                                            <i class="fas fa-magic text-warning me-1"></i> Tavsiyeyi Gör
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">Veri yükleniyor...</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script>
    let radarChartInstance;

    document.addEventListener('DOMContentLoaded', function () {
        if (typeof AOS !== 'undefined') {
            AOS.init({ duration: 800, once: true });
        }

        // Sync AI module toggle UI with Pergel Theme
        const isDark = window.PergelTheme ? window.PergelTheme.getDark() : (localStorage.getItem('pergel_dark') === 'true');
        updateAiThemeToggleUI(isDark);

        // Initialize Radar Chart
        initRadarChart();
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

    function initRadarChart() {
        const options = {
            series: [{
                name: 'Portföy Risk Skoru (%)',
                data: [16.5, 11.8, 6.0, 8.2, 5.4]
            }],
            chart: {
                height: 320,
                type: 'radar',
                toolbar: { show: false },
                foreColor: '#94A3B8'
            },
            colors: ['#F43F5E'],
            xaxis: {
                categories: ['Gecikme Riski', 'Maliyet Riski', 'İSG Riski', 'Kalite Riski', 'Yasal Uyum']
            },
            fill: { opacity: 0.4 },
            markers: { size: 5 }
        };
        radarChartInstance = new ApexCharts(document.querySelector("#riskMatrixRadar"), options);
        radarChartInstance.render();
    }

    function runRiskSimulation() {
        const mat = parseInt(document.querySelectorAll('.sim-slider')[0].value);
        const wea = parseInt(document.querySelectorAll('.sim-slider')[1].value);
        const sub = parseInt(document.querySelectorAll('.sim-slider')[2].value);

        document.getElementById('simValMaterial').innerText = '+' + mat + '%';
        document.getElementById('simValWeather').innerText = wea + ' Gün';
        document.getElementById('simValSubcontractor').innerText = '%' + sub + ' Kapasite';

        const newScore = (14.2 + (mat * 0.35) + (wea * 0.4) + (sub * 0.25)).toFixed(1);
        document.getElementById('simResultScore').innerText = '%' + newScore;
    }
</script>
@endsection