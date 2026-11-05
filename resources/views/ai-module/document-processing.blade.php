@extends('layouts.master-without-page-title')

@section('title')
    Akıllı Doküman İşleme (OCR) - ULTIMATE AI Pro
@endsection

@section('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

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

    .doc-proc-wrapper {
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
        background: linear-gradient(90deg, #10B981, #6366F1, #06B6D4, #F59E0B, #10B981);
        background-size: 300% 100%;
        animation: aiGlowBar 4s linear infinite;
    }

    @keyframes aiGlowBar {
        0% { background-position: 0% 50%; }
        100% { background-position: 300% 50%; }
    }

    .ai-title-badge {
        background: rgba(16, 185, 129, 0.15);
        border: 1px solid rgba(16, 185, 129, 0.35);
        color: #10B981;
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
        background: linear-gradient(135deg, #10B981 0%, #06B6D4 50%, #6366F1 100%);
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

    .icon-emerald { background: rgba(16, 185, 129, 0.18); color: #10B981; }
    .icon-indigo { background: rgba(99, 102, 241, 0.18); color: #6366F1; }
    .icon-cyan { background: rgba(6, 182, 212, 0.18); color: #06B6D4; }
    .icon-amber { background: rgba(245, 158, 11, 0.18); color: #F59E0B; }

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


    /* Dropzone */
    .ocr-dropzone {
        border: 2px dashed rgba(16, 185, 129, 0.4);
        border-radius: 20px;
        padding: 36px;
        text-align: center;
        background: var(--ai-feed-bg);
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .ocr-dropzone:hover {
        border-color: #10B981;
        background: rgba(16, 185, 129, 0.1);
    }

    /* Table */
    .doc-table {
        color: var(--ai-text-primary) !important;
        vertical-align: middle;
    }

    .doc-table th {
        background: var(--ai-feed-bg) !important;
        color: var(--ai-text-muted) !important;
        font-weight: 800;
        text-transform: uppercase;
        font-size: 0.78rem;
        border-bottom: 1px solid var(--ai-card-border);
        padding: 14px 16px;
    }

    .doc-table td {
        padding: 16px;
        border-bottom: 1px solid var(--ai-card-border);
        color: var(--ai-text-primary) !important;
        font-size: 0.9rem;
    }
</style>
@endsection

@section('content')
<div class="doc-proc-wrapper">
    <div class="container-fluid px-2 px-md-4">

        <!-- HERO HEADER -->
        <div class="ai-hero-header">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="d-flex align-items-center gap-2 flex-wrap mb-2">
                        <div class="ai-title-badge">
                            <span class="dot"></span>
                            AI OCR & Multi-Format Contract Parser v4.6 Active | Doğruluk: %99.4
                        </div>
                    </div>

                    <h1 class="ai-hero-title">
                        Akıllı <span>Doküman İşleme & OCR</span>
                    </h1>
                    <p class="ai-hero-subtitle">
                        Hakedişler, faturalar, sözleşmeler ve İSG evraklarını otomatik tarayan, veri ayrıştıran ve hukuki risk maddelerini tespit eden yapay zeka otomasyonu.
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

        <!-- STATS ROW -->
        <div class="row g-4 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="ai-card">
                    <div class="stat-header">
                        <span class="stat-label">Taranan Toplam Evrak</span>
                        <div class="stat-icon-wrapper icon-emerald">
                            <i class="fas fa-file-invoice"></i>
                        </div>
                    </div>
                    <div class="fs-1 fw-bold text-emerald mb-1" style="color: #10B981;">1,428 Evrak</div>
                    <div class="stat-subtext text-success">
                        <i class="fas fa-check-circle me-1"></i> Otomatik Veri Ayrıştırma Tamamlandı
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="ai-card">
                    <div class="stat-header">
                        <span class="stat-label">OCR Veri Doğruluğu</span>
                        <div class="stat-icon-wrapper icon-indigo">
                            <i class="fas fa-eye"></i>
                        </div>
                    </div>
                    <div class="fs-1 fw-bold text-indigo mb-1" style="color: #6366F1;">%99.4</div>
                    <div class="stat-subtext text-indigo">
                        <i class="fas fa-shield-alt me-1"></i> Çapraz Hakediş Kontrolü Aktif
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="ai-card">
                    <div class="stat-header">
                        <span class="stat-label">Tespit Edilen Sözleşme Riski</span>
                        <div class="stat-icon-wrapper icon-amber">
                            <i class="fas fa-exclamation-circle"></i>
                        </div>
                    </div>
                    <div class="fs-1 fw-bold text-warning mb-1">3 Madde</div>
                    <div class="stat-subtext text-muted">
                        <i class="fas fa-balance-scale me-1"></i> Hukuk Birimine İletildi
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="ai-card">
                    <div class="stat-header">
                        <span class="stat-label">Tasarruf Edilen İş Gücü</span>
                        <div class="stat-icon-wrapper icon-cyan">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                    <div class="fs-1 fw-bold text-cyan mb-1" style="color: #06B6D4;">240 Saat/Ay</div>
                    <div class="stat-subtext text-info">
                        <i class="fas fa-bolt me-1"></i> Anlık Fatura & Hakediş İşleme
                    </div>
                </div>
            </div>
        </div>

        <!-- OCR UPLOAD DROPZONE & SIMULATION AREA -->
        <div class="row g-4 mb-4">
            <div class="col-lg-7">
                <div class="ai-card">
                    <h5 class="fw-bold mb-3 d-flex align-items-center gap-2">
                        <i class="fas fa-file-upload text-emerald" style="color: #10B981;"></i> Doküman Yükleme & Anlık AI OCR Taraması
                    </h5>
                    <p class="text-muted font-size-13 mb-3">
                        Hakediş kapakları, faturalar veya taşeron sözleşmelerini sürükleyip bırakın. AI metraj, KDV ve cezai şartları anında çıkartsın.
                    </p>

                    <div class="ocr-dropzone" onclick="runSampleOcrScan()">
                        <i class="fas fa-file-pdf fs-1 text-emerald mb-2" style="color: #10B981;"></i>
                        <h6 class="fw-bold">Doküman Yükleyin veya Örnek Hakediş Taraması Başlatın</h6>
                        <small class="text-muted">Desteklenen Formatlar: PDF, Scanned TIFF, JPG, PNG, DOCX, XLSX</small>
                    </div>

                    <!-- OCR RESULTS BOX -->
                    <div id="ocrResultBox" class="mt-3 p-3 rounded-3" style="display: none; background: rgba(16, 185, 129, 0.12); border: 1px solid rgba(16, 185, 129, 0.4);">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <strong class="text-success"><i class="fas fa-check-circle me-1"></i> AI OCR Ayrıştırma Başarılı:</strong>
                            <span class="badge bg-success">Ayrıştırma Süresi: 0.8sn</span>
                        </div>
                        <div class="row font-size-13">
                            <div class="col-md-6">
                                <div>• <strong>Evrak Tipi:</strong> Taşeron Hakediş Kapağı</div>
                                <div>• <strong>Firma:</strong> A İnşaat Ltd. Şti.</div>
                                <div>• <strong>Hakediş No:</strong> 04 / 2026</div>
                            </div>
                            <div class="col-md-6">
                                <div>• <strong>Toplam Tutar:</strong> ₺2,450,000 + KDV</div>
                                <div>• <strong>Kesinti & Ceza:</strong> ₺0 (Temiz)</div>
                                <div>• <strong>İSG Evrak Uyum:</strong> %100 Güncel</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- QUICK SAMPLE ACCELERATORS -->
            <div class="col-lg-5">
                <div class="ai-card d-flex flex-column justify-content-between">
                    <div>
                        <h5 class="fw-bold mb-3 d-flex align-items-center gap-2">
                            <i class="fas fa-magic text-warning"></i> Örnek Doküman İşleme Şablonları
                        </h5>
                        <p class="text-muted font-size-13 mb-3">
                            Aşağıdaki hazır evrak tiplerinden birini seçerek AI OCR motorunun otomatik veri çıkartmasını test edin:
                        </p>

                        <div class="d-grid gap-2 mb-3">
                            <button type="button" class="btn btn-outline-primary text-start p-3 rounded-3 fw-bold font-size-13" onclick="runSampleOcrScan('hakedis')">
                                📄 <strong>Taşeron Hakediş Raporu Taraması</strong><br>
                                <small class="text-muted font-weight-normal">Metraj, birim fiyat ve hakediş kesintilerini ayırır.</small>
                            </button>

                            <button type="button" class="btn btn-outline-success text-start p-3 rounded-3 fw-bold font-size-13" onclick="runSampleOcrScan('sozlesme')">
                                📜 <strong>Sözleşme & Şartname Risk Taraması</strong><br>
                                <small class="text-muted font-weight-normal">Gecikme cezası, ihbar süreleri ve riskli hukuki maddeleri tespit eder.</small>
                            </button>

                            <button type="button" class="btn btn-outline-info text-start p-3 rounded-3 fw-bold font-size-13" onclick="runSampleOcrScan('sgk')">
                                🏥 <strong>SGK & Personel İSG Evrak Taraması</strong><br>
                                <small class="text-muted font-weight-normal">İşçi TC No, sigorta aktifliği ve sertifika geçerliliğini denetler.</small>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- RECENT PROCESSED DOCUMENTS TABLE -->
        <div class="ai-card">
            <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
                <div>
                    <h5 class="fw-bold mb-1 d-flex align-items-center gap-2">
                        <i class="fas fa-list-alt text-indigo" style="color: #6366F1;"></i> Son İşlenen Dokümanlar ve OCR Çıktıları
                    </h5>
                    <small class="text-muted">Sistem tarafından otomatik işlenen evraklar ve doğrulama durumları</small>
                </div>
                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-2 fw-bold" onclick="alert('📄 Tüm OCR kayıtları Excel olarak dışa aktarıldı.')">
                    <i class="fas fa-file-excel me-1"></i> Excel Dışa Aktar
                </button>
            </div>

            <div class="table-responsive">
                <table class="table doc-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Doküman Adı</th>
                            <th>Doküman Tipi</th>
                            <th>İşlem Tarihi</th>
                            <th>OCR Veri Ayrıştırma</th>
                            <th>Durum</th>
                            <th class="text-end">İşlem</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($recentDocuments) && count($recentDocuments) > 0)
                            @foreach($recentDocuments as $idx => $doc)
                                <tr>
                                    <td class="fw-bold text-muted">{{ $idx + 1 }}</td>
                                    <td class="fw-bold">{{ $doc['name'] }}</td>
                                    <td><span class="badge bg-secondary bg-opacity-20 text-light px-3 py-1 rounded-pill">{{ $doc['type'] }}</span></td>
                                    <td>{{ $doc['date'] }}</td>
                                    <td><span class="text-success fw-bold"><i class="fas fa-check me-1"></i> Tamamlandı (%99.4)</span></td>
                                    <td>
                                        @if($doc['status'] == 'approved')
                                            <span class="ai-badge ai-badge-success">Onaylandı</span>
                                        @elseif($doc['status'] == 'pending')
                                            <span class="ai-badge ai-badge-amber">Onay Bekliyor</span>
                                        @else
                                            <span class="ai-badge ai-badge-info">İncelemede</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <button class="btn btn-xs btn-outline-secondary rounded-pill px-3 py-1 fw-bold" onclick="alert('📄 {{ $doc['name'] }} OCR Ayrıştırılmış Verileri:\n• Evrak Tipi: {{ $doc['type'] }}\n• Tarih: {{ $doc['date'] }}\n• Durum: {{ $doc['status'] }}')">
                                            <i class="fas fa-search text-warning me-1"></i> Ayrıştırmayı Gör
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">İşlenmiş doküman bulunamadı.</td>
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
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof AOS !== 'undefined') {
            AOS.init({ duration: 800, once: true });
        }

        // Sync AI module toggle UI with Pergel Theme
        const isDark = window.PergelTheme ? window.PergelTheme.getDark() : (localStorage.getItem('pergel_dark') === 'true');
        updateAiThemeToggleUI(isDark);
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

    function runSampleOcrScan(type) {
        const resBox = document.getElementById('ocrResultBox');
        resBox.style.display = 'block';
        resBox.scrollIntoView({ behavior: 'smooth' });
    }
</script>
@endsection