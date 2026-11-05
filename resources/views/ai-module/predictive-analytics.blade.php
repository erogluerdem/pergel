@extends('layouts.master-without-page-title')

@section('title')
    AI Tahminleme & Analitik - ULTIMATE AI Pro
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

    .predictive-wrapper {
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
        background: linear-gradient(90deg, #06B6D4, #6366F1, #10B981, #F59E0B, #06B6D4);
        background-size: 300% 100%;
        animation: aiGlowBar 4s linear infinite;
    }

    @keyframes aiGlowBar {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .ai-title-badge {
        background: rgba(6, 182, 212, 0.15);
        border: 1px solid rgba(6, 182, 212, 0.35);
        color: #06B6D4;
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
        background: linear-gradient(135deg, #06B6D4 0%, #6366F1 50%, #10B981 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .ai-hero-subtitle {
        color: var(--ai-text-secondary) !important;
        font-size: 1.05rem;
        max-width: 680px;
        margin-bottom: 0;
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

    .icon-cyan { background: rgba(6, 182, 212, 0.18); color: #06B6D4; }
    .icon-indigo { background: rgba(99, 102, 241, 0.18); color: #6366F1; }
    .icon-emerald { background: rgba(16, 185, 129, 0.18); color: #10B981; }
    .icon-amber { background: rgba(245, 158, 11, 0.18); color: #F59E0B; }

    .stat-label {
        color: var(--ai-text-muted) !important;
        font-size: 0.82rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-value {
        font-size: 2.2rem;
        font-weight: 800;
        color: var(--ai-text-primary) !important;
        line-height: 1.2;
        margin: 4px 0 8px 0;
    }

    .stat-subtext {
        font-size: 0.85rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    /* HIGH-CONTRAST DUAL-THEME BADGES */
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
    .ai-badge-indigo { background: var(--pt-primary-subtle, rgba(99, 102, 241, 0.15)) !important; color: var(--pt-primary, #6366F1) !important; border: 1px solid rgba(var(--pt-primary-rgb, 99, 102, 241), 0.3) !important; }


    /* Table */
    .predict-table {
        color: var(--ai-text-primary) !important;
        vertical-align: middle;
    }

    .predict-table th {
        background: var(--ai-feed-bg) !important;
        color: var(--ai-text-muted) !important;
        font-weight: 800;
        text-transform: uppercase;
        font-size: 0.78rem;
        border-bottom: 1px solid var(--ai-card-border);
        padding: 14px 16px;
    }

    .predict-table td {
        padding: 16px;
        border-bottom: 1px solid var(--ai-card-border);
        color: var(--ai-text-primary) !important;
        font-size: 0.9rem;
    }
</style>
@endsection

@section('content')
<div class="predictive-wrapper">
    <div class="container-fluid px-2 px-md-4">

        <!-- HERO HEADER -->
        <div class="ai-hero-header">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="d-flex align-items-center gap-2 flex-wrap mb-2">
                        <div class="ai-title-badge">
                            <span class="dot"></span>
                            DeepLearning Time-Series Model v4.8 Active | Güvenilirlik: %98.2
                        </div>

                        <select id="projPredictSelector" class="form-select form-select-sm rounded-pill fw-bold" style="width: auto; background: var(--ai-chip-bg); color: var(--ai-text-primary); border-color: var(--ai-card-border);" onchange="updatePredictionData(this.value)">
                            <option value="all" selected>🏢 Tüm Portföy (Genel Tahminleme)</option>
                            <option value="gokturk">🏗️ Göktürk Kemer Country</option>
                            <option value="pergel">🏢 Pergel Tower İş Merkezi</option>
                            <option value="bursa">🏭 Bursa OSB Fabrika</option>
                        </select>
                    </div>

                    <h1 class="ai-hero-title">
                        Yapay Zeka <span>Tahminleme & Analitik</span>
                    </h1>
                    <p class="ai-hero-subtitle">
                        Geleceğe yönelik bütçe sapmaları, teslimat gecikmeleri, malzeme fiyat artışları ve nakit akışını tahmin eden zaman serisi makine öğrenmesi platformu.
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

        <!-- PREDICTION KPI METRICS ROW -->
        <div class="row g-4 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="ai-card">
                    <div class="stat-header">
                        <span class="stat-label">Tahmini Bitiş Projeksiyonu</span>
                        <div class="stat-icon-wrapper icon-emerald">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                    </div>
                    <div class="fs-1 fw-bold text-success mb-1" id="kpiFinish">14 Gün Önde</div>
                    <div class="stat-subtext text-success">
                        <i class="fas fa-arrow-up me-1"></i> İmalat Hızı %104 Seviyesinde
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="ai-card">
                    <div class="stat-header">
                        <span class="stat-label">Tahmini Nakit Sapması</span>
                        <div class="stat-icon-wrapper icon-indigo">
                            <i class="fas fa-chart-pie"></i>
                        </div>
                    </div>
                    <div class="fs-1 fw-bold text-indigo mb-1" style="color: #6366F1;" id="kpiCashflow">+%1.8 Verim</div>
                    <div class="stat-subtext text-success">
                        <i class="fas fa-shield-alt me-1"></i> Bütçe Sınırları İçerisinde
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="ai-card">
                    <div class="stat-header">
                        <span class="stat-label">Tedarik Zinciri Risk Skoru</span>
                        <div class="stat-icon-wrapper icon-cyan">
                            <i class="fas fa-truck-loading"></i>
                        </div>
                    </div>
                    <div class="fs-1 fw-bold text-cyan mb-1" style="color: #06B6D4;" id="kpiSupply">%8.5 Düşük</div>
                    <div class="stat-subtext text-info">
                        <i class="fas fa-check-circle me-1"></i> Malzeme Teslimatları Zamanında
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="ai-card">
                    <div class="stat-header">
                        <span class="stat-label">Hava Şartları İmalat Etkisi</span>
                        <div class="stat-icon-wrapper icon-amber">
                            <i class="fas fa-cloud-sun-rain"></i>
                        </div>
                    </div>
                    <div class="fs-1 fw-bold text-warning mb-1" id="kpiWeather">2 Gün Duraklama</div>
                    <div class="stat-subtext text-muted">
                        <i class="fas fa-info-circle me-1"></i> Gelecek 30 Gün Yağış Tahmini
                    </div>
                </div>
            </div>
        </div>

        <!-- CHARTS SECTION -->
        <div class="row g-4 mb-4">
            <!-- CHART 1: 12-MONTH CASHFLOW & BUDGET PREDICTION -->
            <div class="col-lg-6">
                <div class="ai-card">
                    <h5 class="fw-bold mb-3 d-flex align-items-center gap-2">
                        <i class="fas fa-chart-area text-indigo" style="color: #6366F1;"></i> 12 Aylık Tahmini Nakit Akışı & Bütçe Eğrisi
                    </h5>
                    <div id="cashflowPredictChart" style="min-height: 320px;"></div>
                </div>
            </div>

            <!-- CHART 2: RAW MATERIAL PRICE PREDICTIONS -->
            <div class="col-lg-6">
                <div class="ai-card">
                    <h5 class="fw-bold mb-3 d-flex align-items-center gap-2">
                        <i class="fas fa-tags text-warning"></i> 6 Aylık Malzeme Fiyat Tahmin Modeli (Nervürlü Demir & Beton)
                    </h5>
                    <div id="materialPriceChart" style="min-height: 320px;"></div>
                </div>
            </div>
        </div>

        <!-- DETAILED PREDICTIONS TABLE -->
        <div class="ai-card">
            <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
                <div>
                    <h5 class="fw-bold mb-1 d-flex align-items-center gap-2">
                        <i class="fas fa-crystal-ball text-cyan" style="color: #06B6D4;"></i> Proje Bitiş ve Maliyet Tahmin Tablosu
                    </h5>
                    <small class="text-muted">Makine öğrenmesi modellerimizin projelerinize özel hedeflenen vs tahmini değerleri</small>
                </div>
                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-2 fw-bold" onclick="alert('📈 Gelecek Tahmin Analitiği Raporu Hazırlandı.')">
                    <i class="fas fa-download me-1"></i> Tahmin Raporunu İndir
                </button>
            </div>

            <div class="table-responsive">
                <table class="table predict-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Proje Adı</th>
                            <th>Model Güvenilirliği</th>
                            <th>Hedeflenen Bitiş</th>
                            <th>AI Tahmini Bitiş</th>
                            <th>Tahmini Bütçe Durumu</th>
                            <th>Risk Durumu</th>
                            <th class="text-end">AI Tavsiyesi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="fw-bold text-muted">1</td>
                            <td class="fw-bold">Göktürk Kemer Country Genişletme</td>
                            <td><span class="ai-badge ai-badge-success">%99.2 Güven</span></td>
                            <td>15.11.2026</td>
                            <td class="text-success fw-bold">28.10.2026 (18 Gün Önce)</td>
                            <td><span class="text-success fw-bold">₺18.4M / ₺19.0M (%96.8)</span></td>
                            <td><span class="ai-badge ai-badge-success">Çok Güvenli</span></td>
                            <td class="text-end">
                                <button class="btn btn-xs btn-outline-secondary rounded-pill px-3 py-1 fw-bold" onclick="alert('📌 AI Önerisi: İmalat hızı ideal, malzeme tedariki 1 hafta öne çekilebilir.')">
                                    <i class="fas fa-magic text-warning me-1"></i> İncele
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-bold text-muted">2</td>
                            <td class="fw-bold">Bursa Organize Sanayi Fabrika</td>
                            <td><span class="ai-badge ai-badge-success">%98.4 Güven</span></td>
                            <td>10.12.2026</td>
                            <td class="text-success fw-bold">02.12.2026 (8 Gün Önce)</td>
                            <td><span class="text-success fw-bold">₺24.2M / ₺25.0M (%96.8)</span></td>
                            <td><span class="ai-badge ai-badge-success">Güvenli</span></td>
                            <td class="text-end">
                                <button class="btn btn-xs btn-outline-secondary rounded-pill px-3 py-1 fw-bold" onclick="alert('📌 AI Önerisi: Çelik çatı makas montajları başlamalı.')">
                                    <i class="fas fa-magic text-warning me-1"></i> İncele
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-bold text-muted">3</td>
                            <td class="fw-bold">Adana Çukurova Kuleleri</td>
                            <td><span class="ai-badge ai-badge-amber">%95.1 Güven</span></td>
                            <td>15.01.2027</td>
                            <td class="text-warning fw-bold">12.01.2027 (3 Gün Önce)</td>
                            <td><span class="text-warning fw-bold">₺41.0M / ₺42.5M (%96.4)</span></td>
                            <td><span class="ai-badge ai-badge-amber">Orta</span></td>
                            <td class="text-end">
                                <button class="btn btn-xs btn-outline-secondary rounded-pill px-3 py-1 fw-bold" onclick="alert('📌 AI Önerisi: Asansör tesisatı tedarik zinciri takip edilmeli.')">
                                    <i class="fas fa-magic text-warning me-1"></i> İncele
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-bold text-muted">4</td>
                            <td class="fw-bold">Pergel Tower İş Merkezi</td>
                            <td><span class="ai-badge ai-badge-success">%97.8 Güven</span></td>
                            <td>30.03.2027</td>
                            <td class="text-success fw-bold">20.03.2027 (10 Gün Önce)</td>
                            <td><span class="text-success fw-bold">₺38.7M / ₺45.2M (%85.6)</span></td>
                            <td><span class="ai-badge ai-badge-indigo">Düşük Risk</span></td>
                            <td class="text-end">
                                <button class="btn btn-xs btn-outline-secondary rounded-pill px-3 py-1 fw-bold" onclick="alert('📌 AI Önerisi: Cephe cam kaplama ekibi 2 hafta öne alınabilir.')">
                                    <i class="fas fa-magic text-warning me-1"></i> İncele
                                </button>
                            </td>
                        </tr>
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
    let cashflowChart, materialChart;

    document.addEventListener('DOMContentLoaded', function () {
        if (typeof AOS !== 'undefined') {
            AOS.init({ duration: 800, once: true });
        }

        // Sync AI module toggle UI with Pergel Theme
        const isDark = window.PergelTheme ? window.PergelTheme.getDark() : (localStorage.getItem('pergel_dark') === 'true');
        updateAiThemeToggleUI(isDark);

        // Initialize Charts
        initPredictCharts();
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

    function updatePredictionData(val) {
        const fin = document.getElementById('kpiFinish');
        const cash = document.getElementById('kpiCashflow');
        const sup = document.getElementById('kpiSupply');

        if (val === 'gokturk') {
            fin.innerText = '18 Gün Önde';
            cash.innerText = '+%3.2 Verim';
            sup.innerText = '%4.1 Çok Düşük';
        } else if (val === 'bursa') {
            fin.innerText = '8 Gün Önde';
            cash.innerText = '+%2.1 Verim';
            sup.innerText = '%6.5 Düşük';
        } else if (val === 'pergel') {
            fin.innerText = '10 Gün Önde';
            cash.innerText = '+%4.4 Verim';
            sup.innerText = '%5.0 Düşük';
        } else {
            fin.innerText = '14 Gün Önde';
            cash.innerText = '+%1.8 Verim';
            sup.innerText = '%8.5 Düşük';
        }
    }

    function initPredictCharts() {
        const isLight = document.documentElement.getAttribute('data-bs-theme') === 'light';
        const foreColor = isLight ? '#334155' : '#94A3B8';

        // Cashflow Forecast Chart
        const cashOptions = {
            series: [{
                name: 'Planlanan Bütçe Harcaması (₺M)',
                data: [12, 18, 25, 32, 40, 48, 55, 62, 70, 78, 85, 92]
            }, {
                name: 'AI Tahmini Harcama (₺M)',
                data: [11.5, 17.2, 24.0, 31.1, 38.8, 46.5, 53.2, 60.1, 68.0, 75.9, 83.1, 90.0]
            }],
            chart: {
                height: 320,
                type: 'area',
                toolbar: { show: false },
                foreColor: foreColor
            },
            colors: ['#6366F1', '#10B981'],
            stroke: { curve: 'smooth', width: 3 },
            xaxis: {
                categories: ['Oca', 'Şub', 'Mar', 'Nis', 'May', 'Haz', 'Tem', 'Ağu', 'Eyl', 'Eki', 'Kas', 'Ara']
            }
        };
        cashflowChart = new ApexCharts(document.querySelector("#cashflowPredictChart"), cashOptions);
        cashflowChart.render();

        // Material Price Prediction Chart
        const matOptions = {
            series: [{
                name: 'Nervürlü Demir Ton (₺)',
                data: [24000, 24500, 25200, 25800, 26100, 26400]
            }, {
                name: 'C35 Beton m³ (₺)',
                data: [2800, 2850, 2900, 2980, 3020, 3050]
            }],
            chart: {
                height: 320,
                type: 'line',
                toolbar: { show: false },
                foreColor: foreColor
            },
            colors: ['#D97706', '#06B6D4'],
            stroke: { curve: 'smooth', width: 3 },
            xaxis: {
                categories: ['Nisan', 'Mayıs', 'Haziran', 'Temmuz', 'Ağustos', 'Eylül']
            }
        };
        materialChart = new ApexCharts(document.querySelector("#materialPriceChart"), matOptions);
        materialChart.render();
    }
</script>
@endsection
