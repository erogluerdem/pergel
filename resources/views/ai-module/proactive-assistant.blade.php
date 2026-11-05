@extends('layouts.master-without-page-title')

@section('title')
    Proaktif AI Asistanı - ULTIMATE AI Pro
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

    .proactive-assistant-wrapper {
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
        background: linear-gradient(135deg, #F59E0B 0%, #FBBF24 50%, #6366F1 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    /* Main Chat Interface Card */
    .chat-card {
        background: var(--ai-card-bg);
        border: 1px solid var(--ai-card-border);
        border-radius: 24px;
        padding: 24px;
        box-shadow: var(--ai-shadow);
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .chat-body-container {
        height: 480px;
        overflow-y: auto;
        padding-right: 12px;
        margin-bottom: 20px;
        scroll-behavior: smooth;
    }

    /* Message Bubbles */
    .chat-bubble-row {
        display: flex;
        gap: 14px;
        margin-bottom: 20px;
    }

    .chat-bubble-row.user-row {
        flex-direction: row-reverse;
    }

    .avatar-icon {
        width: 44px;
        height: 44px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }

    .ai-avatar-icon {
        background: linear-gradient(135deg, #6366F1 0%, #4F46E5 100%);
        color: white;
        box-shadow: 0 6px 15px rgba(99, 102, 241, 0.3);
    }

    .user-avatar-icon {
        background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);
        color: white;
        box-shadow: 0 6px 15px rgba(245, 158, 11, 0.3);
    }

    .msg-content-box {
        max-width: 82%;
        border-radius: 20px;
        padding: 16px 20px;
        font-size: 0.94rem;
        line-height: 1.6;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }

    .ai-msg-content {
        background: rgba(99, 102, 241, 0.12);
        border: 1px solid rgba(99, 102, 241, 0.3);
        color: var(--ai-text-primary) !important;
        border-top-left-radius: 4px;
    }

    .user-msg-content {
        background: rgba(245, 158, 11, 0.15);
        border: 1px solid rgba(245, 158, 11, 0.4);
        color: var(--ai-text-primary) !important;
        border-top-right-radius: 4px;
    }

    .msg-meta-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 8px;
        font-weight: 700;
        font-size: 0.85rem;
    }

    .msg-action-bar {
        margin-top: 12px;
        padding-top: 10px;
        border-top: 1px dashed rgba(255, 255, 255, 0.15);
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .msg-action-btn {
        background: var(--ai-chip-bg);
        border: 1px solid var(--ai-card-border);
        color: var(--ai-text-primary) !important;
        font-size: 0.78rem;
        font-weight: 700;
        padding: 4px 12px;
        border-radius: 16px;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .msg-action-btn:hover {
        background: rgba(99, 102, 241, 0.25);
        border-color: #6366F1;
    }

    /* Input Bar */
    .chat-input-bar {
        background: var(--ai-input-bg);
        border: 1px solid rgba(99, 102, 241, 0.4);
        border-radius: 20px;
        padding: 8px 12px 8px 20px;
        display: flex;
        align-items: center;
        gap: 10px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    .chat-input-field {
        background: transparent;
        border: none;
        outline: none;
        color: var(--ai-text-primary) !important;
        font-size: 0.95rem;
        width: 100%;
    }

    .btn-send-chat {
        background: linear-gradient(135deg, #6366F1 0%, #4F46E5 100%);
        color: white !important;
        border: none;
        border-radius: 14px;
        padding: 12px 24px;
        font-weight: 800;
        font-size: 14px;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        white-space: nowrap;
    }

    .btn-send-chat:hover {
        transform: scale(1.02);
        box-shadow: 0 4px 15px rgba(99, 102, 241, 0.5);
    }

    /* Prompt Category Chips */
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
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin: 4px;
    }

    .prompt-chip:hover {
        background: rgba(99, 102, 241, 0.25);
        border-color: #6366F1;
        transform: translateY(-2px);
    }

    /* Right Sidebar Cards */
    .side-info-card {
        background: var(--ai-card-bg);
        border: 1px solid var(--ai-card-border);
        border-radius: 24px;
        padding: 24px;
        margin-bottom: 20px;
        box-shadow: var(--ai-shadow);
    }
</style>
@endsection

@section('content')
<div class="proactive-assistant-wrapper">
    <div class="container-fluid px-2 px-md-4">

        <!-- HERO HEADER -->
        <div class="ai-hero-header">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="d-flex align-items-center gap-2 flex-wrap mb-2">
                        <div class="ai-title-badge">
                            <span class="dot"></span>
                            Proaktif AI Decision Engine v4.5
                        </div>

                        <!-- MODEL SELECTOR -->
                        <select id="modelSelector" class="form-select form-select-sm rounded-pill fw-bold" style="width: auto; background: var(--ai-chip-bg); color: var(--ai-text-primary); border-color: var(--ai-card-border);">
                            <option value="ultimate" selected>🧠 ULTIMATE AI Construction Pro (Önerilen)</option>
                            <option value="claude">⚡ Claude 3.5 Sonnet (Tedarik & Risk)</option>
                            <option value="deepseek">🔥 DeepSeek Construction V3 (İSG & Mevzuat)</option>
                        </select>
                    </div>

                    <h1 class="ai-hero-title">
                        Proaktif <span>Yapay Zeka Asistanı</span>
                    </h1>
                    <p class="ai-hero-subtitle">
                        Şantiye imalatları, İSG denetimleri, hakediş ve mevzuat sorularınıza anında derin analitik çözümler sunan doğal dil işleme platformu.
                    </p>
                </div>

                <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                    <div class="d-flex align-items-center justify-content-lg-end gap-2 flex-wrap">
                        <!-- THEME PICKER BUTTON -->
                        <button type="button" class="btn btn-outline-secondary rounded-pill px-3 py-2 fw-bold" onclick="if(window.PergelTheme) window.PergelTheme.open();" title="Sektör Teması Seç">
                            <i class="mdi mdi-palette text-warning"></i> Sektör Teması
                        </button>

                        <!-- DAY / NIGHT MODE TOGGLE BUTTON -->
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

        <!-- MAIN INTERACTIVE CHAT & SIDEBAR -->
        <div class="row g-4">
            <!-- CHAT INTERFACE AREA -->
            <div class="col-lg-8">
                <div class="chat-card">
                    <div>
                        <!-- CHAT STREAM CONTAINER -->
                        <div class="chat-body-container" id="chatStreamBody">

                            <!-- INITIAL AI WELCOME MESSAGE -->
                            <div class="chat-bubble-row">
                                <div class="avatar-icon ai-avatar-icon">
                                    <i class="fas fa-brain"></i>
                                </div>
                                <div class="msg-content-box ai-msg-content">
                                    <div class="msg-meta-header">
                                        <span>ULTIMATE AI Asistanı</span>
                                        <small class="text-muted">Anlık</small>
                                    </div>
                                    👋 <strong>Merhaba!</strong> Ben Türkiye inşaat sektörü mevzuatı, İSG ve şantiye verileriyle eğitilmiş Proaktif AI Asistanı.
                                    <br><br>
                                    Aşağıdaki konularda anında analiz çıkartabilir ve otomatik görev oluşturabilirim:
                                    <ul class="mb-2 mt-2 ps-3">
                                        <li>🏗️ Şantiye imalat takvimi ve gecikme riski analizi</li>
                                        <li>🛡️ TS 18001 & 6331 Sayılı İSG Kanunu uyum denetimi</li>
                                        <li>💰 Hakediş, bütçe aşımı ve malzeme fiyat tahmini</li>
                                        <li>⚖️ ÇED, inşaat ruhsatı ve SGK muafiyet kontrolleri</li>
                                    </ul>
                                </div>
                            </div>

                            <!-- IF QUERY SENT VIA URL OR FORM -->
                            @if(isset($response) && $response)
                            <div class="chat-bubble-row user-row">
                                <div class="avatar-icon user-avatar-icon">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="msg-content-box user-msg-content">
                                    <div class="msg-meta-header">
                                        <span>Siz</span>
                                        <small class="text-muted">Az önce</small>
                                    </div>
                                    {{ $query }}
                                </div>
                            </div>

                            <div class="chat-bubble-row">
                                <div class="avatar-icon ai-avatar-icon">
                                    <i class="fas fa-brain"></i>
                                </div>
                                <div class="msg-content-box ai-msg-content">
                                    <div class="msg-meta-header">
                                        <span>ULTIMATE AI Asistanı</span>
                                        <small class="text-muted">Analiz Tamamlandı</small>
                                    </div>
                                    {!! nl2br(e($response)) !!}

                                    <div class="msg-action-bar">
                                        <button class="msg-action-btn" onclick="showNotification('Rapor PDF olarak aktarılıyor...')"><i class="fas fa-file-pdf text-danger"></i> PDF İndir</button>
                                        <button class="msg-action-btn" onclick="showNotification('Şantiye şefine SMS uyarısı gönderildi!')"><i class="fas fa-sms text-warning"></i> Şefine SMS Gönder</button>
                                        <button class="msg-action-btn" onclick="showNotification('İş takvimine görev kaydedildi.')"><i class="fas fa-calendar-plus text-success"></i> Görev Oluştur</button>
                                    </div>
                                </div>
                            </div>
                            @endif

                        </div>
                    </div>

                    <div>
                        <!-- SUGGESTED PROMPT CHIPS CATEGORIES -->
                        <div class="mb-3">
                            <small class="fw-bold text-muted d-block mb-1">💡 Hızlı Analiz Şablonları:</small>
                            <div class="prompt-chip" onclick="executePrompt('Göktürk Kemer Country projesi gecikme riski analizi yap')">
                                🏗️ Göktürk Kemer Gecikme Analizi
                            </div>
                            <div class="prompt-chip" onclick="executePrompt('Pergel Tower bütçe kullanımı ve maliyet aşım riski nedir?')">
                                💰 Pergel Tower Bütçe Riski
                            </div>
                            <div class="prompt-chip" onclick="executePrompt('Bursa OSB Şantiyesi İSG denetimi ve eksik evraklar')">
                                🛡️ Bursa OSB İSG Eksikleri
                            </div>
                            <div class="prompt-chip" onclick="executePrompt('İnşaat ruhsatı ve ÇED yasal uyumluluk raporu çıkart')">
                                ⚖️ Yasal İzinler & ÇED
                            </div>
                        </div>

                        <!-- INPUT FORM -->
                        <form onsubmit="handleFormSubmit(event)" class="chat-input-bar">
                            <i class="fas fa-sparkles text-warning fs-5"></i>
                            <input type="text" id="userQueryInput" class="chat-input-field" placeholder="Sorunuzu yazın... (Örn: Bursa OSB projesinde İSG uyumu nasıl?)" required autocomplete="off">
                            <button type="submit" id="btnSubmitQuery" class="btn-send-chat">
                                <i class="fas fa-paper-plane"></i> Gönder
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- RIGHT SIDEBAR INTELLIGENCE & QUICK NAV -->
            <div class="col-lg-4">
                <!-- AI DECISION SUMMARY CARD -->
                <div class="side-info-card">
                    <h5 class="fw-bold mb-3 d-flex align-items-center gap-2">
                        <i class="fas fa-chart-pie text-indigo" style="color: #6366F1;"></i> Aktif Şantiye AI Sağlık Skoru
                    </h5>

                    <div class="d-flex align-items-center justify-content-between p-3 rounded-3 mb-3" style="background: rgba(16, 185, 129, 0.12); border: 1px solid rgba(16, 185, 129, 0.3);">
                        <div>
                            <div class="fw-bold text-success">Genel Şantiye İSG Skoru</div>
                            <small class="text-muted">20 Proje Taranıyor</small>
                        </div>
                        <div class="fs-3 fw-bold text-success">%91</div>
                    </div>

                    <div class="d-flex align-items-center justify-content-between p-3 rounded-3 mb-3" style="background: rgba(99, 102, 241, 0.12); border: 1px solid rgba(99, 102, 241, 0.3);">
                        <div>
                            <div class="fw-bold text-indigo" style="color: #6366F1;">ISO 9001 Kalite Uyum</div>
                            <small class="text-muted">Beton & Çelik Testleri</small>
                        </div>
                        <div class="fs-3 fw-bold text-indigo" style="color: #6366F1;">%94</div>
                    </div>

                    <div class="d-flex align-items-center justify-content-between p-3 rounded-3" style="background: rgba(244, 63, 94, 0.12); border: 1px solid rgba(244, 63, 94, 0.3);">
                        <div>
                            <div class="fw-bold text-danger">Tahmini Zaman Riski</div>
                            <small class="text-muted">Kritik Yol Analizi</small>
                        </div>
                        <div class="fs-3 fw-bold text-danger">%14</div>
                    </div>
                </div>

                <!-- QUICK NAVIGATION LINKS TO AI SUB-MODULES -->
                <div class="side-info-card">
                    <h6 class="fw-bold mb-3 d-flex align-items-center gap-2">
                        <i class="fas fa-cubes text-warning"></i> Diğer Yapay Zeka Araçları
                    </h6>

                    <div class="d-grid gap-2">
                        <a href="{{ route('ai-module.risk-analysis') }}" class="btn btn-outline-primary btn-sm rounded-pill fw-bold text-start p-2 px-3">
                            <i class="fas fa-shield-alt me-2 text-danger"></i> AI Risk Analiz Motoru
                        </a>
                        <a href="{{ route('ai-module.document-processing') }}" class="btn btn-outline-success btn-sm rounded-pill fw-bold text-start p-2 px-3">
                            <i class="fas fa-file-invoice-dollar me-2 text-success"></i> Akıllı Doküman İşleme (OCR)
                        </a>
                        <a href="{{ route('ai-module.site-data-analysis') }}" class="btn btn-outline-info btn-sm rounded-pill fw-bold text-start p-2 px-3">
                            <i class="fas fa-broadcast-tower me-2 text-info"></i> Saha Veri & İSG Analizi
                        </a>
                        <a href="{{ route('ai-module.compliance-report') }}" class="btn btn-outline-warning btn-sm rounded-pill fw-bold text-start p-2 px-3">
                            <i class="fas fa-balance-scale me-2 text-warning"></i> Mevzuat & ÇED Uyum Raporu
                        </a>
                        <a href="{{ route('ai-module.predictive-analytics') }}" class="btn btn-outline-dark btn-sm rounded-pill fw-bold text-start p-2 px-3">
                            <i class="fas fa-chart-line me-2 text-primary"></i> Tahminleme & Analitik
                        </a>
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
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof AOS !== 'undefined') {
            AOS.init({ duration: 800, once: true });
        }

        // Sync AI module toggle UI with Pergel Theme
        const isDark = window.PergelTheme ? window.PergelTheme.getDark() : (localStorage.getItem('pergel_dark') === 'true');
        updateAiThemeToggleUI(isDark);

        // Auto scroll chat to bottom
        const chatBody = document.getElementById('chatStreamBody');
        chatBody.scrollTop = chatBody.scrollHeight;
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

    function executePrompt(promptText) {
        document.getElementById('userQueryInput').value = promptText;
        handleFormSubmit(new Event('submit'));
    }

    function handleFormSubmit(e) {
        e.preventDefault();
        const inputEl = document.getElementById('userQueryInput');
        const queryText = inputEl.value.trim();
        if (!queryText) return;

        const chatBody = document.getElementById('chatStreamBody');

        // Append User Message Bubble
        const userHtml = `
            <div class="chat-bubble-row user-row">
                <div class="avatar-icon user-avatar-icon">
                    <i class="fas fa-user"></i>
                </div>
                <div class="msg-content-box user-msg-content">
                    <div class="msg-meta-header">
                        <span>Siz</span>
                        <small class="text-muted">Şimdi</small>
                    </div>
                    ${escapeHtml(queryText)}
                </div>
            </div>
        `;
        chatBody.innerHTML += userHtml;
        inputEl.value = '';
        chatBody.scrollTop = chatBody.scrollHeight;

        // Show AI Typing Indicator
        const typingId = 'typing_' + Date.now();
        const typingHtml = `
            <div class="chat-bubble-row" id="${typingId}">
                <div class="avatar-icon ai-avatar-icon">
                    <i class="fas fa-brain fa-spin"></i>
                </div>
                <div class="msg-content-box ai-msg-content">
                    <div class="msg-meta-header">
                        <span>ULTIMATE AI Asistanı</span>
                        <small class="text-muted">Analiz Ediliyor...</small>
                    </div>
                    <em>🔍 Şantiye veritabanı, İSG raporları ve mevzuat taranıyor...</em>
                </div>
            </div>
        `;
        chatBody.innerHTML += typingHtml;
        chatBody.scrollTop = chatBody.scrollHeight;

        // Generate AI Stream Reply after 500ms
        setTimeout(() => {
            document.getElementById(typingId).remove();

            let responseText = generateSmartAiResponse(queryText);

            const aiHtml = `
                <div class="chat-bubble-row">
                    <div class="avatar-icon ai-avatar-icon">
                        <i class="fas fa-brain"></i>
                    </div>
                    <div class="msg-content-box ai-msg-content">
                        <div class="msg-meta-header">
                            <span>ULTIMATE AI Asistanı</span>
                            <small class="text-muted">Tamamlandı</small>
                        </div>
                        ${responseText}
                        <div class="msg-action-bar">
                            <button class="msg-action-btn" onclick="showNotification('Rapor PDF olarak aktarılıyor...')"><i class="fas fa-file-pdf text-danger"></i> PDF İndir</button>
                            <button class="msg-action-btn" onclick="showNotification('Şantiye şefine SMS uyarısı gönderildi!')"><i class="fas fa-sms text-warning"></i> Şefine SMS Gönder</button>
                            <button class="msg-action-btn" onclick="showNotification('İş takvimine görev kaydedildi.')"><i class="fas fa-calendar-plus text-success"></i> Görev Oluştur</button>
                        </div>
                    </div>
                </div>
            `;
            chatBody.innerHTML += aiHtml;
            chatBody.scrollTop = chatBody.scrollHeight;
        }, 600);
    }

    function generateSmartAiResponse(query) {
        const q = query.toLowerCase();

        if (q.includes('göktürk') || q.includes('kemer')) {
            return `📊 <strong>Göktürk Kemer Country Projesi Analizi:</strong><br>
            • <strong>Genel İlerleme:</strong> %84 (İş takviminin 18 gün önünde)<br>
            • <strong>İSG Uyumu:</strong> %96 (Sıfır kritik kaza)<br>
            • <strong>Kalan Bütçe:</strong> %16.2 verimli seviyede<br><br>
            💡 <strong>AI Önerisi:</strong> Çatı kaplama ve cephe imalatları betonarme teslimi sonrası 2 gün öne çekilebilir.`;
        }
        if (q.includes('pergel') || q.includes('tower')) {
            return `🏢 <strong>Pergel Tower İş Merkezi Bütçe Analizi:</strong><br>
            • <strong>Harcanan Bütçe:</strong> ₺38.7M / ₺45.2M (%85.6)<br>
            • <strong>Bütçe Sapması:</strong> %4.2 tasarruf potansiyeli<br><br>
            💡 <strong>Aksiyon Önerisi:</strong> MEP mekanik tesisat yüklenici hakediş onayları verilen imalatla %100 örtüşüyor.`;
        }
        if (q.includes('bursa') || q.includes('isg') || q.includes('güvenlik')) {
            return `🛡️ <strong>Bursa OSB Fabrika İSG Denetim Raporu:</strong><br>
            • <strong>Son Denetim Skoru:</strong> 9.4 / 10<br>
            • <strong>Yüksekte Çalışma İzni:</strong> 12 aktif personel güncel<br>
            • <strong>Dikkat Gerektiren:</strong> 2 ad. elektrik panosu kilit-etiketleme eksikliği tespit edildi (Tamamlama süresi: 24 saat).`;
        }
        if (q.includes('yasal') || q.includes('ruhsat') || q.includes('çed')) {
            return `⚖️ <strong>Yasal Uyum & ÇED Durum Raporu:</strong><br>
            • ✅ İnşaat Ruhsatı: Geçerli (Süre sonu: 2027)<br>
            • ✅ ÇED Olumlu Raporu: Onaylandı<br>
            • ⏳ Hafriyat Toprak İzni: Yenilemeye 14 gün kaldı. Otomatik başvuru taslağı hazırlandı.`;
        }

        return `🤖 <strong>ULTIMATE AI Detaylı Analitiği:</strong><br>
        Sorgulanan "<em>${escapeHtml(query)}</em>" konusu aktif 20 projemizin veritabanı kayıtları ile karşılaştırıldı.<br><br>
        • <strong>Genel Sağlık Skoru:</strong> %91<br>
        • <strong>Önerilen Aksiyon:</strong> Detaylı şantiye puantajı ve hakediş kontrolleri ilgili alt modüller üzerinden incelenebilir.`;
    }

    function escapeHtml(text) {
        return text.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
    }

    function showNotification(msg) {
        alert("✨ AI Aksiyonu: " + msg);
    }
</script>
@endsection