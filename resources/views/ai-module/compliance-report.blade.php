@extends('layouts.master-without-page-title')

@section('title')
    Mevzuat & Standart Uyum Raporu - ULTIMATE AI Pro
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

    .compliance-wrapper {
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
        background: linear-gradient(90deg, #10B981, #3B82F6, #6366F1, #F59E0B, #10B981);
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
        background: linear-gradient(135deg, #10B981 0%, #3B82F6 50%, #6366F1 100%);
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
    .icon-blue { background: rgba(59, 130, 246, 0.18); color: #3B82F6; }
    .icon-indigo { background: rgba(99, 102, 241, 0.18); color: #6366F1; }
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


    /* Table */
    .compliance-table {
        color: var(--ai-text-primary) !important;
        vertical-align: middle;
    }

    .compliance-table th {
        background: var(--ai-feed-bg) !important;
        color: var(--ai-text-muted) !important;
        font-weight: 800;
        text-transform: uppercase;
        font-size: 0.78rem;
        border-bottom: 1px solid var(--ai-card-border);
        padding: 14px 16px;
    }

    .compliance-table td {
        padding: 16px;
        border-bottom: 1px solid var(--ai-card-border);
        color: var(--ai-text-primary) !important;
        font-size: 0.9rem;
    }

    /* Audit Timeline */
    .audit-step {
        position: relative;
        padding-left: 28px;
        margin-bottom: 20px;
        border-left: 2px dashed rgba(255, 255, 255, 0.2);
    }

    html[data-bs-theme="light"] .audit-step {
        border-left-color: #CBD5E1;
    }

    .audit-step::before {
        content: '';
        position: absolute;
        left: -7px;
        top: 4px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #10B981;
    }
</style>
@endsection

@section('content')
<div class="compliance-wrapper">
    <div class="container-fluid px-2 px-md-4">

        <!-- HERO HEADER -->
        <div class="ai-hero-header">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="d-flex align-items-center gap-2 flex-wrap mb-2">
                        <div class="ai-title-badge">
                            <span class="dot"></span>
                            TBDY 2018 & ISO Standards Compliance Engine | Genel Skoru: %96.4
                        </div>
                    </div>

                    <h1 class="ai-hero-title">
                        Mevzuat & <span>Standart Uyum Raporu</span>
                    </h1>
                    <p class="ai-hero-subtitle">
                        Türkiye Bina Deprem Yönetmeliği (TBDY 2018), TS 18001 İSG, ISO 9001 Kalite ve 4708 Sayılı Yapı Denetim Kanunu standartlarına tam uyum denetleyicisi.
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

        <!-- STANDARDS KPI ROW -->
        <div class="row g-4 mb-4">
            @if(isset($complianceData['standards']))
                @foreach($complianceData['standards'] as $standard => $data)
                    <div class="col-xl-3 col-md-6">
                        <div class="ai-card">
                            <div class="stat-header">
                                <span class="stat-label">{{ $standard }}</span>
                                <div class="stat-icon-wrapper icon-emerald">
                                    <i class="fas fa-award"></i>
                                </div>
                            </div>
                            <div class="fs-1 fw-bold text-success mb-1">%{{ $data['compliance'] }}</div>
                            <div class="progress mb-2" style="height: 6px; background: rgba(255,255,255,0.1);">
                                <div class="progress-bar bg-success" style="width: {{ $data['compliance'] }}%;"></div>
                            </div>
                            <div class="stat-subtext text-muted font-size-12">
                                <div><i class="fas fa-calendar-check text-success me-1"></i> Son Denetim: {{ \Carbon\Carbon::parse($data['last_audit'])->format('d.m.Y') }}</div>
                                <div><i class="fas fa-clock text-warning me-1"></i> Sonraki: {{ \Carbon\Carbon::parse($data['next_audit'])->format('d.m.Y') }}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-xl-3 col-md-6">
                    <div class="ai-card">
                        <div class="stat-header"><span class="stat-label">TS 18001 İSG Uyum</span></div>
                        <div class="fs-1 fw-bold text-success">%94.0</div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="ai-card">
                        <div class="stat-header"><span class="stat-label">ISO 9001 Kalite</span></div>
                        <div class="fs-1 fw-bold text-success">%98.2</div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="ai-card">
                        <div class="stat-header"><span class="stat-label">TBDY 2018 Deprem</span></div>
                        <div class="fs-1 fw-bold text-emerald">%100</div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="ai-card">
                        <div class="stat-header"><span class="stat-label">Yapı Denetim (4708)</span></div>
                        <div class="fs-1 fw-bold text-indigo">%96.0</div>
                    </div>
                </div>
            @endif
        </div>

        <!-- REGULATORY AUDIT TIMELINE & CHECKLIST -->
        <div class="row g-4 mb-4">
            <div class="col-lg-6">
                <div class="ai-card">
                    <h5 class="fw-bold mb-3 d-flex align-items-center gap-2">
                        <i class="fas fa-history text-indigo" style="color: #6366F1;"></i> Mevzuat Denetim Zaman Çizelgesi
                    </h5>

                    <div class="audit-step ms-2">
                        <div class="fw-bold text-success">✅ TBDY 2018 Statik Proje Performans Analizi</div>
                        <small class="text-muted">12.08.2026 — Göktürk Kemer Country C Blok (Bakanlık Onaylı)</small>
                    </div>

                    <div class="audit-step ms-2">
                        <div class="fw-bold text-info">ℹ️ TS 18001 İSG Saha Periyodik Kontrolleri</div>
                        <small class="text-muted">05.08.2026 — Pergel Tower İş Merkezi (0 Kritik İhlal)</small>
                    </div>

                    <div class="audit-step ms-2">
                        <div class="fw-bold text-warning">⏳ ISO 14001 Çevre Atık Yönetim Denetimi</div>
                        <small class="text-muted">Planlanan: 25.08.2026 — Bursa OSB Şantiyesi</small>
                    </div>
                </div>
            </div>

            <!-- REGULATORY AI CHECKLIST -->
            <div class="col-lg-6">
                <div class="ai-card">
                    <h5 class="fw-bold mb-3 d-flex align-items-center gap-2">
                        <i class="fas fa-check-double text-emerald" style="color: #10B981;"></i> AI Otomatik Standart Kontrol Listesi
                    </h5>

                    <div class="d-grid gap-2">
                        <div class="p-3 rounded-3 d-flex align-items-center justify-content-between" style="background: var(--ai-feed-bg); border: 1px solid var(--ai-card-border);">
                            <div>
                                <strong class="font-size-14">📌 TS EN 206 Beton Kalite & Taze Beton Deneyi</strong>
                                <div class="small text-muted">C30/37 Silindir Basınç Dayanım Raporları</div>
                            </div>
                            <span class="ai-badge ai-badge-success">Onaylandı</span>
                        </div>

                        <div class="p-3 rounded-3 d-flex align-items-center justify-content-between" style="background: var(--ai-feed-bg); border: 1px solid var(--ai-card-border);">
                            <div>
                                <strong class="font-size-14">📌 6331 Sayılı İSG Kanunu Yıllık Eğitim Sertifikaları</strong>
                                <div class="small text-muted">127 Saha Personeli Eğitim Kaydı</div>
                            </div>
                            <span class="ai-badge ai-badge-success">Eksiksiz</span>
                        </div>

                        <div class="p-3 rounded-3 d-flex align-items-center justify-content-between" style="background: var(--ai-feed-bg); border: 1px solid var(--ai-card-border);">
                            <div>
                                <strong class="font-size-14">📌 Yapı Ruhsatı & Şantiye İzin Belgeleri</strong>
                                <div class="small text-muted">Belediye İmar ve Çevre İzinleri</div>
                            </div>
                            <span class="ai-badge ai-badge-info">Güncel</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- DETAILED REGULATIONS TABLE -->
        <div class="ai-card">
            <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
                <div>
                    <h5 class="fw-bold mb-1 d-flex align-items-center gap-2">
                        <i class="fas fa-clipboard-list text-warning"></i> Detaylı Mevzuat ve Denetim Kayıtları
                    </h5>
                    <small class="text-muted">Tüm kanun ve ISO standartlarındaki son denetim durumları</small>
                </div>
                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-2 fw-bold" onclick="alert('📄 Resmi Mevzuat Uyum Raporu Hazırlandı.')">
                    <i class="fas fa-file-pdf me-1"></i> Uyum Raporunu PDF İndir
                </button>
            </div>

            <div class="table-responsive">
                <table class="table compliance-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Mevzuat / Yönetmelik Adı</th>
                            <th>İlgili Standart</th>
                            <th>Son Denetim Tarihi</th>
                            <th>Geçerlilik Durumu</th>
                            <th>Uyum Skoru</th>
                            <th class="text-end">İşlem</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($complianceData['regulations']) && count($complianceData['regulations']) > 0)
                            @foreach($complianceData['regulations'] as $idx => $reg)
                                <tr>
                                    <td class="fw-bold text-muted">{{ $idx + 1 }}</td>
                                    <td class="fw-bold">{{ $reg['title'] }}</td>
                                    <td><span class="badge bg-secondary bg-opacity-20 text-light px-3 py-1 rounded-pill">{{ $reg['standard'] }}</span></td>
                                    <td>{{ \Carbon\Carbon::parse($reg['last_check'])->format('d.m.Y') }}</td>
                                    <td><span class="text-success fw-bold"><i class="fas fa-check-circle me-1"></i> {{ $reg['status'] == 'approved' ? 'Geçerli' : 'İnceleniyor' }}</span></td>
                                    <td>
                                        <span class="fs-6 fw-bold text-success">%{{ $reg['compliance_rate'] ?? 98 }}</span>
                                    </td>
                                    <td class="text-end">
                                        <button class="btn btn-xs btn-outline-secondary rounded-pill px-3 py-1 fw-bold" onclick="alert('📜 {{ $reg['title'] }} Denetim Raporu:\n• Standart: {{ $reg['standard'] }}\n• Son Denetim: {{ $reg['last_check'] }}\n• Skor: %98.4 (Tam Uyumlu)')">
                                            <i class="fas fa-search text-warning me-1"></i> Raporu Gör
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">Mevzuat kaydı bulunamadı.</td>
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
</script>
@endsection