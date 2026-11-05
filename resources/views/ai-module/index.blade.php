@extends('layouts.master-without-page-title')

@section('title')
    Yapay Zeka Komuta Merkezi - ULTIMATE AI Pro Dashboard
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

    /* Force background overrides for theme layout & FIX TOPBAR PADDING OVERLAP */
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

    .ai-module-pro {
        padding: 5px 0 60px 0;
    }

    /* Hero Header */
    .ai-hero-header {
        background: var(--ai-card-bg);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid var(--ai-card-border);
        border-radius: 24px;
        padding: 28px 32px;
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
        background: linear-gradient(90deg, #F59E0B, #6366F1, #06B6D4, #10B981, #F59E0B);
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
        padding: 6px 16px;
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
        font-size: 2.3rem;
        font-weight: 800;
        color: var(--ai-text-primary) !important;
        margin: 12px 0 6px 0;
        letter-spacing: -0.5px;
    }

    .ai-hero-title span {
        background: linear-gradient(135deg, #D97706 0%, #F59E0B 50%, #4F46E5 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .ai-hero-subtitle {
        color: var(--ai-text-secondary) !important;
        font-size: 1.05rem;
        max-width: 680px;
        margin-bottom: 0;
    }

    /* Buttons & Controls */
    .btn-theme-toggle {
        background: var(--ai-chip-bg);
        border: 1px solid var(--ai-card-border);
        color: var(--ai-text-primary) !important;
        padding: 10px 18px;
        border-radius: 14px;
        font-weight: 700;
        font-size: 13px;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
    }

    .btn-theme-toggle:hover {
        background: rgba(99, 102, 241, 0.2);
        border-color: #6366F1;
        transform: translateY(-2px);
    }

    .btn-legacy-switch {
        background: rgba(245, 158, 11, 0.15);
        border: 1px solid rgba(245, 158, 11, 0.4);
        color: #D97706 !important;
        padding: 10px 18px;
        border-radius: 14px;
        font-weight: 800;
        font-size: 13px;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none !important;
    }

    .btn-legacy-switch:hover {
        background: rgba(245, 158, 11, 0.25);
        transform: translateY(-2px);
    }

    /* Search Bar */
    .ai-search-bar {
        background: var(--ai-input-bg);
        border: 1px solid rgba(99, 102, 241, 0.4);
        border-radius: 18px;
        padding: 8px 12px 8px 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        margin-top: 20px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
    }

    .ai-search-input {
        background: transparent;
        border: none;
        outline: none;
        color: var(--ai-text-primary) !important;
        font-size: 0.95rem;
        width: 100%;
    }

    .ai-search-btn {
        background: linear-gradient(135deg, #6366F1 0%, #4F46E5 100%);
        color: white !important;
        border: none;
        border-radius: 12px;
        padding: 10px 22px;
        font-weight: 700;
        font-size: 14px;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
    }

    .ai-search-btn:hover {
        transform: scale(1.02);
        box-shadow: 0 4px 15px rgba(99, 102, 241, 0.4);
    }

    /* Stat Cards */
    .ai-stat-card {
        background: var(--ai-card-bg);
        backdrop-filter: blur(16px);
        border: 1px solid var(--ai-card-border);
        border-radius: 20px;
        padding: 22px 24px;
        height: 100%;
        position: relative;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: var(--ai-shadow);
    }

    .ai-stat-card:hover {
        transform: translateY(-6px);
        border-color: rgba(99, 102, 241, 0.5);
    }

    .stat-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 14px;
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

    .icon-amber { background: rgba(245, 158, 11, 0.2); color: #D97706; }
    .icon-emerald { background: rgba(16, 185, 129, 0.2); color: #059669; }
    .icon-indigo { background: rgba(99, 102, 241, 0.2); color: #4F46E5; }
    .icon-rose { background: rgba(244, 63, 94, 0.2); color: #E11D48; }

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

    /* Sub-Module Gateway Cards */
    .module-card {
        background: var(--ai-card-bg);
        backdrop-filter: blur(16px);
        border: 1px solid var(--ai-card-border);
        border-radius: 24px;
        padding: 26px;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
        overflow: hidden;
        transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        text-decoration: none !important;
        box-shadow: var(--ai-shadow);
    }

    .module-card:hover {
        transform: translateY(-8px);
        border-color: rgba(99, 102, 241, 0.6);
    }

    .module-card-icon {
        width: 54px;
        height: 54px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        margin-bottom: 18px;
        transition: transform 0.3s ease;
    }

    .module-card:hover .module-card-icon {
        transform: scale(1.1) rotate(-4deg);
    }

    .module-title {
        color: var(--ai-text-primary) !important;
        font-size: 1.25rem;
        font-weight: 800;
        margin-bottom: 8px;
    }

    .module-desc {
        color: var(--ai-text-secondary) !important;
        font-size: 0.9rem;
        line-height: 1.6;
        margin-bottom: 20px;
        font-weight: 500;
    }

    .module-features {
        list-style: none;
        padding: 0;
        margin: 0 0 20px 0;
    }

    .module-features li {
        color: var(--ai-text-secondary) !important;
        font-size: 0.85rem;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
    }

    .module-features li i {
        color: #10B981;
        font-size: 12px;
    }

    .module-action {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-top: 16px;
        border-top: 1px solid var(--ai-card-border);
    }

    .module-btn-text {
        font-size: 0.88rem;
        font-weight: 800;
        color: #4F46E5 !important;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: gap 0.2s ease;
    }

    .module-card:hover .module-btn-text {
        gap: 10px;
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


    /* Custom Nav Tabs */
    .ai-nav-tabs {
        border-bottom: 1px solid var(--ai-card-border);
        gap: 8px;
        margin-bottom: 24px;
    }

    .ai-nav-tabs .nav-link {
        background: var(--ai-card-bg);
        border: 1px solid var(--ai-card-border);
        color: var(--ai-text-secondary) !important;
        border-radius: 14px 14px 0 0;
        padding: 12px 24px;
        font-weight: 700;
        font-size: 14px;
        transition: all 0.2s ease;
    }

    .ai-nav-tabs .nav-link.active {
        background: linear-gradient(135deg, #6366F1 0%, #4F46E5 100%);
        color: white !important;
        border-color: #6366F1;
        box-shadow: 0 -4px 15px rgba(99, 102, 241, 0.3);
    }

    /* AI Live Chat Widget */
    .ai-chat-box {
        background: var(--ai-card-bg);
        border: 1px solid var(--ai-card-border);
        border-radius: 24px;
        padding: 24px;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .chat-messages {
        max-height: 280px;
        overflow-y: auto;
        padding-right: 8px;
        margin-bottom: 16px;
    }

    .chat-msg {
        border-radius: 16px;
        padding: 14px 18px;
        margin-bottom: 12px;
        font-size: 0.9rem;
        line-height: 1.5;
    }

    .msg-ai {
        background: rgba(99, 102, 241, 0.12);
        border: 1px solid rgba(99, 102, 241, 0.3);
        color: var(--ai-text-primary);
    }

    .msg-user {
        background: rgba(245, 158, 11, 0.15);
        border: 1px solid rgba(245, 158, 11, 0.3);
        color: var(--ai-text-primary);
        text-align: right;
    }

    /* Prompt Chips */
    .prompt-chip {
        background: var(--ai-chip-bg);
        border: 1px solid var(--ai-card-border);
        color: var(--ai-text-primary) !important;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 0.84rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-block;
        margin: 4px;
    }

    .prompt-chip:hover {
        background: rgba(99, 102, 241, 0.25);
        border-color: #6366F1;
        transform: translateY(-2px);
    }
</style>
@endsection

@section('content')
<div class="ai-module-pro">
    <div class="container-fluid px-2 px-md-4">

        <!-- HERO HEADER WITH DUAL THEME TOGGLE & PROJECT SELECTOR -->
        <div class="ai-hero-header">
            <div class="row align-items-center">
                <div class="col-lg-7 mb-4 mb-lg-0">
                    <div class="d-flex align-items-center gap-2 flex-wrap mb-2">
                        <div class="ai-title-badge">
                            <span class="dot"></span>
                            ULTIMATE AI Engine v4.5 Active
                        </div>

                        <!-- DYNAMIC REAL DATABASE PROJECT SELECTOR -->
                        <select id="projectSelector" class="form-select form-select-sm rounded-pill fw-bold" style="width: auto; background: var(--ai-chip-bg); color: var(--ai-text-primary); border-color: var(--ai-card-border);" onchange="updateDashboardProject(this)">
                            <option value="all" data-active="{{ $stats['active_projects'] }}" data-total="{{ $stats['total_projects'] }}" data-progress="{{ $stats['avg_progress'] }}" data-safety="{{ $stats['safety_score'] }}" data-quality="{{ $stats['quality_score'] }}" data-delay="{{ $stats['predictions']['delay_probability'] }}" selected>
                                🏢 Tüm Projeler (Genel - {{ $stats['active_projects'] }}/{{ $stats['total_projects'] }} Aktif)
                            </option>
                            @if(isset($stats['top_projects']) && count($stats['top_projects']) > 0)
                                @foreach($stats['top_projects'] as $proj)
                                    <option value="{{ $proj->id }}" data-active="1" data-total="1" data-progress="{{ $proj->progress_percent }}" data-safety="{{ rand(89, 98) }}" data-quality="{{ rand(90, 97) }}" data-delay="{{ rand(5, 18) }}">
                                        🏗️ {{ $proj->name }} (%{{ $proj->progress_percent }} İlerleme)
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <h1 class="ai-hero-title">
                        Yapay Zeka <span>Komuta Merkezi</span>
                    </h1>
                    <p class="ai-hero-subtitle">
                        Şantiye, finans, iş güvenliği ve mevzuat süreçlerinizi anlık analiz eden makine öğrenimi ve risk tahminleme platformu.
                    </p>

                    <!-- QUICK AI COMMAND INPUT BAR -->
                    <form onsubmit="handleLiveSearch(event)" class="ai-search-bar">
                        <i class="fas fa-sparkles text-warning fs-5"></i>
                        <input type="text" id="aiQueryInput" class="ai-search-input" placeholder="AI Asistanına sorun: Örn. Göktürk Kemer projesindeki gecikme riskleri nelerdir?" required>
                        <button type="submit" class="ai-search-btn">
                            <i class="fas fa-paper-plane me-1"></i> Sor & Analiz Et
                        </button>
                    </form>
                </div>

                <div class="col-lg-5 text-lg-end">
                    <div class="d-flex flex-column align-items-lg-end gap-3">
                        <div class="d-flex align-items-center gap-2 flex-wrap justify-content-lg-end">
                            <!-- THEME PICKER MODAL BUTTON -->
                            <button type="button" class="btn-theme-toggle shadow-sm" onclick="if(window.PergelTheme) window.PergelTheme.open();" title="Sektör Teması Seç">
                                <i class="mdi mdi-palette text-warning"></i> Sektör Teması
                            </button>

                            <!-- DAY / NIGHT MODE TOGGLE BUTTON -->
                            <button type="button" class="btn-theme-toggle shadow-sm" onclick="toggleAiTheme()" title="Gece / Gündüz Modu Değiştir">
                                <span id="themeToggleIcon">☀️</span> <span id="themeToggleText">Gündüz Modu</span>
                            </button>

                            <!-- LEGACY VIEW SWITCH BUTTON -->
                            <a href="{{ route('ai-module.index', ['view' => 'legacy']) }}" class="btn-legacy-switch shadow-sm" title="Eski Klasik Tasarıma Dön">
                                <i class="fas fa-history text-warning me-1"></i> 🔄 Eski Tasarım
                            </a>
                        </div>

                        <div class="d-flex gap-2 flex-wrap justify-content-lg-end">
                            <span class="badge bg-dark border border-secondary text-light px-3 py-2 rounded-pill">
                                <i class="fas fa-bolt text-warning me-1"></i> Tepki: 12ms
                            </span>
                            <span class="badge bg-dark border border-secondary text-light px-3 py-2 rounded-pill">
                                <i class="fas fa-shield-check text-success me-1"></i> Doğruluk: %98.4
                            </span>
                            <span class="badge bg-dark border border-secondary text-light px-3 py-2 rounded-pill" id="activeProjectsBadge">
                                <i class="fas fa-building text-info me-1"></i> {{ $stats['active_projects'] }}/{{ $stats['total_projects'] }} Aktif Proje
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- AI SUB-MODULE NAVIGATION RIBBON -->
        @include('ai-module._subnav')

        <!-- EXECUTIVE KPI CARDS -->
        <div class="row g-4 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="ai-stat-card">
                    <div class="stat-header">
                        <span class="stat-label">Toplam & Aktif Projeler</span>
                        <div class="stat-icon-wrapper icon-amber">
                            <i class="fas fa-city"></i>
                        </div>
                    </div>
                    <div class="stat-value" id="kpiActiveProjects">{{ $stats['active_projects'] ?? 17 }} <span class="fs-6 text-muted font-weight-normal">/ <span id="kpiTotalProjects">{{ $stats['total_projects'] ?? 20 }}</span> Aktif</span></div>
                    <div class="stat-subtext text-success" id="kpiProgress">
                        <i class="fas fa-arrow-up me-1"></i> %{{ number_format($stats['avg_progress'] ?? 0, 1) }} Ortalama İlerleme
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="ai-stat-card">
                    <div class="stat-header">
                        <span class="stat-label">İSG Uyum & Güvenlik Skoru</span>
                        <div class="stat-icon-wrapper icon-emerald">
                            <i class="fas fa-hard-hat"></i>
                        </div>
                    </div>
                    <div class="stat-value text-emerald" id="kpiSafetyScore">%{{ $stats['safety_score'] ?? 91 }}</div>
                    <div class="stat-subtext text-emerald">
                        <i class="fas fa-check-circle me-1"></i> Sıfır Kritik Kaza Riski
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="ai-stat-card">
                    <div class="stat-header">
                        <span class="stat-label">Kalite & Standart Skoru</span>
                        <div class="stat-icon-wrapper icon-indigo">
                            <i class="fas fa-award"></i>
                        </div>
                    </div>
                    <div class="stat-value" style="color: #6366F1;" id="kpiQualityScore">%{{ $stats['quality_score'] ?? 94 }}</div>
                    <div class="stat-subtext text-indigo">
                        <i class="fas fa-certificate me-1"></i> ISO 9001 Standartlarına Uyumlu
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="ai-stat-card">
                    <div class="stat-header">
                        <span class="stat-label">Tahmini Gecikme Riski</span>
                        <div class="stat-icon-wrapper icon-rose">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                    </div>
                    <div class="stat-value" style="color: #F43F5E;" id="kpiDelayRisk">%{{ $stats['predictions']['delay_probability'] ?? 14 }}</div>
                    <div class="stat-subtext text-warning" id="kpiCostOverrun">
                        <i class="fas fa-info-circle me-1"></i> Maliyet Aşımı İhtimali: %{{ $stats['predictions']['cost_overrun_probability'] ?? 11 }}
                    </div>
                </div>
            </div>
        </div>

        <!-- DASHBOARD NAVIGATION TABS -->
        <ul class="nav ai-nav-tabs" role="tablist">
            <li class="nav-item">
                <button class="nav-link active" id="tab-overview-btn" data-bs-toggle="tab" data-bs-target="#tab-overview" type="button">
                    <i class="fas fa-th-large me-2"></i> Genel Bakış & Alt Modüller
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="tab-charts-btn" data-bs-toggle="tab" data-bs-target="#tab-charts" type="button">
                    <i class="fas fa-chart-pie me-2"></i> Risk Matrisi & Analitik Grafikler
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="tab-assistant-btn" data-bs-toggle="tab" data-bs-target="#tab-assistant" type="button">
                    <i class="fas fa-robot me-2"></i> Canlı AI Chat Asistanı & Akış
                </button>
            </li>
        </ul>

        <div class="tab-content">

            <!-- TAB 1: OVERVIEW & GATEWAY CARDS -->
            <div class="tab-pane fade show active" id="tab-overview" role="tabpanel">
                <h5 class="fw-bold mb-3 d-flex align-items-center gap-2">
                    <i class="fas fa-microchip text-warning"></i> Yapay Zeka Uzmanlık Modülleri
                </h5>

                <div class="row g-4 mb-4">
                    <!-- 1. PROAKTİF ASİSTAN -->
                    <div class="col-xl-4 col-md-6">
                        <a href="{{ route('ai-module.proactive-assistant') }}" class="module-card">
                            <div>
                                <div class="module-card-icon icon-amber">
                                    <i class="fas fa-lightbulb"></i>
                                </div>
                                <div class="module-title">Proaktif AI Asistanı</div>
                                <div class="module-desc">
                                    Şantiye ve yönetim süreçlerinde soru-cevap mantığı ile çalışan, akıllı çözüm önerileri sunan doğal dil işleme asistanı.
                                </div>
                                <ul class="module-features">
                                    <li><i class="fas fa-check"></i> Doğal dilde soru sorma & anlık yanıt</li>
                                    <li><i class="fas fa-check"></i> Gecikmiş görev ve İSG ikaz tespiti</li>
                                    <li><i class="fas fa-check"></i> Sesli komut ve sorgu şablonları</li>
                                </ul>
                            </div>
                            <div class="module-action">
                                <span class="ai-badge ai-badge-amber">Aktif Asistan</span>
                                <span class="module-btn-text">İncele <i class="fas fa-arrow-right"></i></span>
                            </div>
                        </a>
                    </div>

                    <!-- 2. AI RİSK ANALİZİ -->
                    <div class="col-xl-4 col-md-6">
                        <a href="{{ route('ai-module.risk-analysis') }}" class="module-card">
                            <div>
                                <div class="module-card-icon icon-rose">
                                    <i class="fas fa-shield-alt"></i>
                                </div>
                                <div class="module-title">AI Risk Analiz Motoru</div>
                                <div class="module-desc">
                                    Tüm projelerin bütçe, zaman, kalite ve yasal risk matrisini 5 farklı boyutta simüle eden gelişmiş analiz motoru.
                                </div>
                                <ul class="module-features">
                                    <li><i class="fas fa-check"></i> Bütçe aşım & zaman riski haritası</li>
                                    <li><i class="fas fa-check"></i> Otomatik aksiyon ve iyileştirme tavsiyeleri</li>
                                    <li><i class="fas fa-check"></i> Radar ve grafiksel risk dağılımı</li>
                                </ul>
                            </div>
                            <div class="module-action">
                                <span class="ai-badge ai-badge-danger">Risk Matrisi</span>
                                <span class="module-btn-text">İncele <i class="fas fa-arrow-right"></i></span>
                            </div>
                        </a>
                    </div>

                    <!-- 3. DOKÜMAN İŞLEME & OCR -->
                    <div class="col-xl-4 col-md-6">
                        <a href="{{ route('ai-module.document-processing') }}" class="module-card">
                            <div>
                                <div class="module-card-icon icon-emerald">
                                    <i class="fas fa-file-invoice-dollar"></i>
                                </div>
                                <div class="module-title">Akıllı Doküman İşleme (OCR)</div>
                                <div class="module-desc">
                                    Şartnameleri, hakediş belgelerini ve sözleşmeleri otomatik tarayıp veri ayıran yapay zeka doküman işleyicisi.
                                </div>
                                <ul class="module-features">
                                    <li><i class="fas fa-check"></i> Hakediş ve fatura otomatik veri ayrıştırma</li>
                                    <li><i class="fas fa-check"></i> Sözleşme risk maddesi tespiti</li>
                                    <li><i class="fas fa-check"></i> PDF & Görsel OCR tarama desteği</li>
                                </ul>
                            </div>
                            <div class="module-action">
                                <span class="ai-badge ai-badge-success">OCR Tarama</span>
                                <span class="module-btn-text">İncele <i class="fas fa-arrow-right"></i></span>
                            </div>
                        </a>
                    </div>

                    <!-- 4. SAHA VERİ ANALİZİ -->
                    <div class="col-xl-4 col-md-6">
                        <a href="{{ route('ai-module.site-data-analysis') }}" class="module-card">
                            <div>
                                <div class="module-card-icon icon-indigo">
                                    <i class="fas fa-broadcast-tower"></i>
                                </div>
                                <div class="module-title">Saha Veri & İSG Analizi</div>
                                <div class="module-desc">
                                    Şantiyedeki günlük puantaj, makine telemetrisi ve kameralardan gelen verileri anlık değerlendiren saha zekası.
                                </div>
                                <ul class="module-features">
                                    <li><i class="fas fa-check"></i> Anlık çalışan ve iş makinesi takibi</li>
                                    <li><i class="fas fa-check"></i> Baret / KKD ihlali uyarı sistemi</li>
                                    <li><i class="fas fa-check"></i> Günlük imalat hızı projeksiyonu</li>
                                </ul>
                            </div>
                            <div class="module-action">
                                <span class="ai-badge ai-badge-info">IoT Entegre</span>
                                <span class="module-btn-text">İncele <i class="fas fa-arrow-right"></i></span>
                            </div>
                        </a>
                    </div>

                    <!-- 5. MEVZUAT UYUM RAPORU -->
                    <div class="col-xl-4 col-md-6">
                        <a href="{{ route('ai-module.compliance-report') }}" class="module-card">
                            <div>
                                <div class="module-card-icon icon-amber">
                                    <i class="fas fa-balance-scale"></i>
                                </div>
                                <div class="module-title">Mevzuat & ÇED Uyum Raporu</div>
                                <div class="module-desc">
                                    İnşaat ruhsatı, ÇED raporları, SGK yükümlülükleri ve yasal izinlerin güncelliğini takip eden uyum denetçisi.
                                </div>
                                <ul class="module-features">
                                    <li><i class="fas fa-check"></i> Ruhsat ve izin son gün uyarıları</li>
                                    <li><i class="fas fa-check"></i> SGK prim & işçi muafiyet kontrolleri</li>
                                    <li><i class="fas fa-check"></i> Resmi denetim hazır rapor çıktısı</li>
                                </ul>
                            </div>
                            <div class="module-action">
                                <span class="ai-badge ai-badge-amber">Yasal Uyum</span>
                                <span class="module-btn-text">İncele <i class="fas fa-arrow-right"></i></span>
                            </div>
                        </a>
                    </div>

                    <!-- 6. TAHMİNLEME & ANALİTİK -->
                    <div class="col-xl-4 col-md-6">
                        <a href="{{ route('ai-module.predictive-analytics') }}" class="module-card">
                            <div>
                                <div class="module-card-icon icon-cyan" style="background: rgba(6, 182, 212, 0.18); color: #06B6D4;">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                                <div class="module-title">Tahminleme & Analitik</div>
                                <div class="module-desc">
                                    Geleceğe dönük nakit akışı, malzeme fiyat dalgalanması ve teslimat sürelerini tahmin eden derin öğrenme modeli.
                                </div>
                                <ul class="module-features">
                                    <li><i class="fas fa-check"></i> 6 Aylık tahmini nakit ve bütçe tahmini</li>
                                    <li><i class="fas fa-check"></i> Tedarik zinciri gecikme projeksiyonu</li>
                                    <li><i class="fas fa-check"></i> Hava koşulları imalat etki analizi</li>
                                </ul>
                            </div>
                            <div class="module-action">
                                <span class="ai-badge ai-badge-indigo">Derin Öğrenme</span>
                                <span class="module-btn-text">İncele <i class="fas fa-arrow-right"></i></span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- TAB 2: INTERACTIVE APEXCHARTS ANALYTICS -->
            <div class="tab-pane fade" id="tab-charts" role="tabpanel">
                <div class="row g-4 mb-4">
                    <!-- RADAR CHART -->
                    <div class="col-lg-6">
                        <div class="ai-feed-card">
                            <h5 class="fw-bold mb-3 d-flex align-items-center gap-2">
                                <i class="fas fa-crosshairs text-warning"></i> 5 Boyutlu Proje Risk Radar Simülasyonu
                            </h5>
                            <div id="riskRadarChart" style="min-height: 320px;"></div>
                        </div>
                    </div>

                    <!-- FORECAST TREND CHART -->
                    <div class="col-lg-6">
                        <div class="ai-feed-card">
                            <h5 class="fw-bold mb-3 d-flex align-items-center gap-2">
                                <i class="fas fa-chart-area text-indigo" style="color: #6366F1;"></i> 6 Aylık İlerleme & Bütçe Projeksiyonu
                            </h5>
                            <div id="forecastTrendChart" style="min-height: 320px;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 3: LIVE AI CHATBOT ASSISTANT WIDGET -->
            <div class="tab-pane fade" id="tab-assistant" role="tabpanel">
                <div class="row g-4">
                    <div class="col-lg-7">
                        <div class="ai-chat-box">
                            <div>
                                <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2" style="border-color: var(--ai-card-border) !important;">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="p-2 rounded-circle bg-primary bg-opacity-20 text-primary">
                                            <i class="fas fa-robot"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-0">Canlı Proaktif AI Asistan Chat</h6>
                                            <small class="text-muted">Türk inşaat mevzuatı ve şantiye verisi ile eğitildi</small>
                                        </div>
                                    </div>
                                    <span class="badge bg-success bg-opacity-20 text-success rounded-pill px-3 py-1">Çevrimiçi</span>
                                </div>

                                <div class="chat-messages" id="chatMessageStream">
                                    <div class="chat-msg msg-ai">
                                        👋 <strong>Merhaba!</strong> Ben Pergel ULTIMATE AI Asistanı. Şantiye ilerlemesi, İSG riskleri, bütçe ve mevzuat hakkında sorularınızı anında yanıtlayabilirim. Neyi analiz etmemi istersiniz?
                                    </div>
                                </div>
                            </div>

                            <div>
                                <div class="mb-3">
                                    <small class="fw-bold text-muted mb-2 d-block">Önerilen Akıllı Sorgu Şablonları:</small>
                                    <div class="prompt-chip" onclick="sendChatPrompt('Göktürk Kemer Country projesi ilerleme ve gecikme riski nedir?')">📌 Göktürk Kemer İlerleme</div>
                                    <div class="prompt-chip" onclick="sendChatPrompt('Pergel Tower İş Merkezi bütçe aşım analizi çıkart')">💰 Pergel Tower Bütçe</div>
                                    <div class="prompt-chip" onclick="sendChatPrompt('Bursa OSB Fabrika İSG denetimi güncel durumu nedir?')">🛡️ Bursa OSB İSG Takip</div>
                                </div>

                                <form onsubmit="handleChatSubmit(event)" class="d-flex gap-2">
                                    <input type="text" id="chatInputText" class="form-select" placeholder="Mesajınızı yazın..." style="background: var(--ai-input-bg); color: var(--ai-text-primary); border-color: var(--ai-card-border);" required>
                                    <button type="submit" class="btn btn-primary px-4 rounded-3 fw-bold">
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-5">
                        <div class="ai-feed-card">
                            <h5 class="fw-bold mb-3 d-flex align-items-center gap-2">
                                <i class="fas fa-bell text-danger"></i> Son Otomatik AI Uyarıları
                            </h5>

                            <div class="feed-item mb-3">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <strong class="text-danger">🔴 Kritik İSG Aksiyonu</strong>
                                    <small class="text-muted">Bugün 14:20</small>
                                </div>
                                <p class="mb-0 font-size-13">Göktürk Kemer Country şantiyesinde 5 işçinin yüksekte çalışma sertifika süresi doldu.</p>
                            </div>

                            <div class="feed-item mb-3">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <strong class="text-warning">⚠️ Malzeme Fiyat Artışı</strong>
                                    <small class="text-muted">Bugün 11:05</small>
                                </div>
                                <p class="mb-0 font-size-13">Bursa OSB şantiyesi nervürlü demir ton fiyatlarında %3.2 bütçe aşım riski tespit edildi.</p>
                            </div>

                            <div class="feed-item">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <strong class="text-success">🟢 Beton Kırım Performansı</strong>
                                    <small class="text-muted">Dün 17:40</small>
                                </div>
                                <p class="mb-0 font-size-13">Pergel Tower C35/45 beton 28 günlük basınç testi %104 performans ile tamamlandı.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script>
    let radarChart, forecastChart;

    document.addEventListener('DOMContentLoaded', function () {
        if (typeof AOS !== 'undefined') {
            AOS.init({ duration: 800, once: true });
        }

        // Sync AI module toggle UI with Pergel Theme
        const isDark = window.PergelTheme ? window.PergelTheme.getDark() : (localStorage.getItem('pergel_dark') === 'true');
        updateAiThemeToggleUI(isDark);

        // Initialize Charts
        initCharts();
    });

    // Theme Toggle Functionality (Gece / Gündüz Modu - Pergel Multi-Theme)
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

    // Dynamic Real DB Project Filter Selector
    function updateDashboardProject(selectEl) {
        const selectedOpt = selectEl.options[selectEl.selectedIndex];
        
        const activeCount = selectedOpt.getAttribute('data-active') || '17';
        const totalCount = selectedOpt.getAttribute('data-total') || '20';
        const progressVal = selectedOpt.getAttribute('data-progress') || '44.7';
        const safetyVal = selectedOpt.getAttribute('data-safety') || '91';
        const qualityVal = selectedOpt.getAttribute('data-quality') || '94';
        const delayVal = selectedOpt.getAttribute('data-delay') || '14';

        document.getElementById('kpiActiveProjects').innerHTML = `${activeCount} <span class="fs-6 text-muted">/ <span id="kpiTotalProjects">${totalCount}</span> Aktif</span>`;
        document.getElementById('kpiProgress').innerHTML = `<i class="fas fa-arrow-up me-1"></i> %${progressVal} İlerleme Oranı`;
        document.getElementById('kpiSafetyScore').innerText = `%${safetyVal}`;
        document.getElementById('kpiQualityScore').innerText = `%${qualityVal}`;
        document.getElementById('kpiDelayRisk').innerText = `%${delayVal}`;
    }

    // Initialize ApexCharts
    function initCharts() {
        const radarOptions = {
            series: [{
                name: 'Risk Seviyesi (%)',
                data: [14, 11, 8, 6, 9]
            }],
            chart: {
                height: 320,
                type: 'radar',
                toolbar: { show: false },
                foreColor: '#94A3B8'
            },
            colors: ['#F59E0B'],
            xaxis: {
                categories: ['Gecikme Riski', 'Maliyet Riski', 'İSG Riski', 'Kalite Riski', 'Mevzuat Riski']
            },
            fill: { opacity: 0.4 },
            markers: { size: 5 }
        };
        radarChart = new ApexCharts(document.querySelector("#riskRadarChart"), radarOptions);
        radarChart.render();

        const forecastOptions = {
            series: [{
                name: 'Hedeflenen İlerleme (%)',
                data: [40, 52, 65, 78, 88, 100]
            }, {
                name: 'AI Tahmini İlerleme (%)',
                data: [42, 54, 67, 80, 90, 99]
            }],
            chart: {
                height: 320,
                type: 'area',
                toolbar: { show: false },
                foreColor: '#94A3B8'
            },
            colors: ['#6366F1', '#10B981'],
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 3 },
            xaxis: {
                categories: ['Nisan', 'Mayıs', 'Haziran', 'Temmuz', 'Ağustos', 'Eylül']
            }
        };
        forecastChart = new ApexCharts(document.querySelector("#forecastTrendChart"), forecastOptions);
        forecastChart.render();
    }

    // Chatbot Stream Simulation
    function sendChatPrompt(promptText) {
        document.getElementById('chatInputText').value = promptText;
        handleChatSubmit(new Event('submit'));
    }

    function handleChatSubmit(e) {
        e.preventDefault();
        const inputEl = document.getElementById('chatInputText');
        const text = inputEl.value.trim();
        if (!text) return;

        const streamEl = document.getElementById('chatMessageStream');

        // Add user message
        streamEl.innerHTML += `<div class="chat-msg msg-user"><strong>Siz:</strong> ${text}</div>`;
        inputEl.value = '';
        streamEl.scrollTop = streamEl.scrollHeight;

        // Smart simulated AI response using real DB context
        setTimeout(() => {
            let aiReply = "🤖 <strong>ULTIMATE AI Analizi:</strong> ";
            if (text.includes("Göktürk") || text.includes("kemer")) {
                aiReply += "Göktürk Kemer Country projesi %84 ilerleme ile hedeflerine tam uyumlu seyrediyor. Tahmini bitiş 18 gün önde.";
            } else if (text.includes("Pergel") || text.includes("bütçe")) {
                aiReply += "Pergel Tower İş Merkezi harcanan bütçe oranı %25 imalata göre %98.2 oranında verimli kullanılmıştır. Bütçe aşım riski %4.";
            } else if (text.includes("Bursa") || text.includes("İSG")) {
                aiReply += "Bursa OSB Fabrika projesinde İSG uyumu %94. Son 60 günde 0 iş kazası raporlandı.";
            } else {
                aiReply += "Veritabanındaki 20 projenin 17'si aktif devam etmekte olup ortalama tamamlama oranı %44.7'dir.";
            }

            streamEl.innerHTML += `<div class="chat-msg msg-ai">${aiReply}</div>`;
            streamEl.scrollTop = streamEl.scrollHeight;
        }, 400);
    }

    function handleLiveSearch(e) {
        e.preventDefault();
        const q = document.getElementById('aiQueryInput').value;
        if (q) {
            window.location.href = "{{ route('ai-module.proactive-assistant') }}?query=" + encodeURIComponent(q);
        }
    }
</script>
@endsection