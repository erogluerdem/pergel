{{--
    Pergel Sidebar — Çoklu Tema Sistemi Uyumlu
    CSS Custom Properties üzerinden tema renkleri alır.
--}}
<style>
/* ── Tema fontları: tüm temalar için ön-yükleme ── */
@import url('https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=Nunito:wght@300;400;500;600;700;800&family=Roboto:wght@300;400;500;700&family=DM+Sans:wght@300;400;500;600;700&display=swap');

/* ── CSS var fallback'ler (tema yüklenmeden önce) ── */
:root {
    --t-fast: all 0.18s cubic-bezier(0.4,0,0.2,1);
    --t-med:  all 0.28s cubic-bezier(0.4,0,0.2,1);
}

/* ── Sidebar temel yapı ── */
.sidebar-left { display: block !important; visibility: visible !important; left: 0; }

.sidebar-left .vertical-menu {
    background: var(--pt-sidebar-bg, linear-gradient(160deg, #0d1b2a 0%, #1a2744 40%, #0f1e38 100%)) !important;
    border-right: 1px solid var(--pt-sidebar-border, rgba(99,179,237,0.12));
    box-shadow: var(--pt-sidebar-shadow, 0 25px 50px rgba(0,0,0,0.5));
    height: 100vh;
    overflow-y: auto; overflow-x: hidden;
    position: relative;
    padding-top: 2px;
    font-family: var(--pt-font-sans, 'Inter', sans-serif);
    display: block !important; visibility: visible !important; opacity: 1 !important;
    transform: translateX(0) !important;
    scrollbar-width: thin;
    scrollbar-color: var(--pt-sidebar-scrollbar, rgba(79,158,248,0.2)) transparent;
    transition: background 0.35s ease, border-color 0.35s ease, box-shadow 0.35s ease;
}

/* Overlay dekorasyon (temaya göre farklılaşır) */
.sidebar-left .vertical-menu::before {
    content: ''; position: absolute; top: 0; left: 0; right: 0; height: 220px;
    background: var(--pt-sidebar-overlay, radial-gradient(ellipse at 50% 0%, rgba(79,158,248,0.07) 0%, transparent 70%));
    pointer-events: none; z-index: 0;
}

.sidebar-left .vertical-menu::-webkit-scrollbar { width: 3px; }
.sidebar-left .vertical-menu::-webkit-scrollbar-track { background: transparent; }
.sidebar-left .vertical-menu::-webkit-scrollbar-thumb {
    background: var(--pt-sidebar-scrollbar, rgba(79,158,248,0.2));
    border-radius: 10px;
}

/* ── Mobil sidebar ── */
@media (max-width: 991.98px) {
    .sidebar-left {
        position: fixed !important;
        top: 64px !important;
        left: 0 !important;
        bottom: 0 !important;
        width: 270px !important;
        z-index: 1050 !important;
        transform: translateX(-100%);
        transition: transform 0.28s cubic-bezier(0.4, 0, 0.2, 1) !important;
        box-shadow: 10px 0 30px rgba(0,0,0,0.5);
    }
    body.sidebar-is-open .sidebar-left { transform: translateX(0) !important; }
    .sidebar-left .vertical-menu {
        height: calc(100vh - 64px) !important;
        width: 270px !important;
        -webkit-overflow-scrolling: touch;
    }
    .main-content { margin-left: 0 !important; }
}

/* Navbar brand kutusu */
.sidebar-left .navbar-brand-box {
    background: rgba(0,0,0,0.25) !important;
    border-bottom: 1px solid var(--pt-sidebar-border, rgba(79,158,248,0.15)) !important;
    box-shadow: 0 4px 24px rgba(0,0,0,0.3);
    position: relative;
}
.sidebar-left .navbar-brand-box::after {
    content: ''; position: absolute; bottom: 0; left: 15%; right: 15%;
    height: 1px;
    background: linear-gradient(90deg, transparent, var(--pt-sidebar-accent, rgba(79,158,248,0.5)), transparent);
}

.main-content { margin-left: 270px; }
@media (max-width: 991.98px) { .main-content { margin-left: 0 !important; } }

/* ── Grup İsimleri (Menu Title) ── */
.sidebar-left .left-menu .menu-title {
    color: var(--pt-sidebar-section-color, #60a5fa) !important;
    font-size: 9px !important;
    font-weight: 700;
    letter-spacing: 1.2px;
    text-transform: uppercase;
    padding: 7px 12px 2px 12px !important;
    margin-top: 4px;
    display: flex; align-items: center; gap: 6px;
    font-family: var(--pt-font-sans, 'Inter', sans-serif);
    list-style: none;
    text-shadow: 0 1px 2px rgba(0,0,0,0.4);
    transition: color 0.3s ease;
}
.sidebar-left .left-menu .menu-title::after {
    content: ''; flex: 1; height: 1px;
    background: linear-gradient(90deg, var(--pt-sidebar-section-color, rgba(96,165,250,0.35)), transparent);
    transition: background 0.3s ease;
}

/* ── AI asistan shimmer animasyonu ── */
.ai-assistant-text {
    font-weight: 700; font-size: 0.73rem !important;
    font-family: var(--pt-font-sans, 'Inter', sans-serif);
    display: inline-block; white-space: nowrap; overflow: visible;
    background: linear-gradient(90deg, var(--pt-sidebar-accent, #4f9ef8), rgba(167,139,250,0.9), var(--pt-sidebar-accent, #4f9ef8));
    background-size: 200% auto;
    -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
    animation: sb-shimmer 3s linear infinite;
}
@keyframes sb-shimmer { 0% { background-position: 0% center; } 100% { background-position: 200% center; } }

.left-menu, .left-menu .sub-menu { list-style: none; padding: 0; margin: 0; }
.left-menu li { position: relative; }
.left-menu .sub-menu li { display: block; }
.left-menu .sub-menu { display: block !important; }

/* Menü metin taşma kontrolü */
.left-menu a > span:not(.badge) {
    white-space: nowrap !important;
    overflow: hidden !important;
    text-overflow: ellipsis !important;
    display: inline-block !important;
    max-width: 195px !important;
    flex: 1;
    vertical-align: middle !important;
    line-height: 1.3 !important;
    font-family: var(--pt-font-sans, 'Inter', sans-serif);
    font-size: 0.73rem !important;
    transition: color 0.2s ease;
}
.sidebar-left .left-menu a > span.badge,
.sidebar-left .left-menu a .badge {
    max-width: none !important;
    width: auto !important;
    overflow: visible !important;
    text-overflow: clip !important;
    flex-shrink: 0 !important;
    white-space: nowrap !important;
    padding: 2px 6px !important;
    font-size: 9px !important;
    font-weight: 800 !important;
    line-height: 1 !important;
    display: inline-flex !important;
    align-items: center !important;
    border-radius: var(--pt-badge-radius, 6px) !important;
}

/* ── Ana Menü Linkleri ── */
.sidebar-left .left-menu > li > a {
    color: var(--pt-sidebar-text, #b0bec5) !important;
    font-weight: 500;
    display: flex !important; align-items: center;
    padding: 3.5px 7px 3.5px 7px !important;
    margin: 0px 4px !important;
    border-radius: var(--pt-btn-radius, 6px);
    transition: var(--t-fast);
    font-size: 0.73rem !important;
    line-height: 1.25;
    text-decoration: none !important;
    border: 1px solid transparent;
    position: relative; overflow: hidden;
    font-family: var(--pt-font-sans, 'Inter', sans-serif);
    white-space: nowrap !important;
}
.sidebar-left .left-menu > li > a:hover {
    color: var(--pt-sidebar-text-bright, #ffffff) !important;
    background: var(--pt-sidebar-hover-bg, rgba(79,158,248,0.10)) !important;
    border-color: var(--pt-sidebar-accent, rgba(79,158,248,0.22));
    transform: translateX(3px);
}
.sidebar-left .left-menu > li > a.active {
    color: var(--pt-sidebar-active-text, #ffffff) !important;
    background: var(--pt-sidebar-active-bg, rgba(79,158,248,0.18)) !important;
    border-color: rgba(var(--pt-primary-rgb, 79,158,248), 0.45) !important;
    box-shadow: 0 4px 20px rgba(var(--pt-primary-rgb, 79,158,248), 0.12),
                inset 0 1px 0 rgba(255,255,255,0.07);
}

/* Aktif indicator bar — her temada farklı renk */
.sidebar-left .left-menu > li > a.active::before {
    content: ''; position: absolute; left: 0; top: 18%; bottom: 18%; width: 3px;
    background: var(--pt-sidebar-accent, linear-gradient(180deg, #4f9ef8, #7c3aed));
    border-radius: 0 3px 3px 0;
    box-shadow: 0 0 10px rgba(var(--pt-primary-rgb, 79,158,248), 0.3);
}

/* ── Ana menü ikonları ── */
.sidebar-left .left-menu > li > a > i {
    width: 18px; text-align: center; flex-shrink: 0; margin-right: 6px;
    font-size: 0.76rem;
    color: var(--pt-sidebar-icon-color, #60a5fa);
    transition: var(--t-fast);
    line-height: 1; display: inline-block;
    background: none !important; border: none !important;
    box-shadow: none !important; border-radius: 0 !important; height: auto !important;
}
.sidebar-left .left-menu > li > a:hover > i {
    color: var(--pt-sidebar-accent, #4f9ef8);
}
.sidebar-left .left-menu > li > a.active > i {
    color: var(--pt-sidebar-accent, #4f9ef8);
    text-shadow: 0 0 10px rgba(var(--pt-primary-rgb, 79,158,248), 0.5);
}

.left-menu .mm-active > a, .left-menu li.open > a {
    color: var(--pt-sidebar-text-bright, #e2e8f0) !important;
}
.left-menu .has-arrow::after { display: none !important; }

/* ── Sub-Menu Linkleri ── */
.left-menu .sub-menu { padding: 1px 0 2px; }
.sidebar-left .left-menu .sub-menu li > a {
    display: flex !important; align-items: center;
    color: var(--pt-sidebar-text-dim, #78909c) !important;
    font-size: 0.70rem !important;
    font-weight: 400 !important;
    padding: 3px 5px 3px 7px !important;
    margin: 0px 4px 0px 14px !important;
    text-decoration: none !important;
    transition: var(--t-fast);
    line-height: 1.25;
    font-family: var(--pt-font-sans, 'Inter', sans-serif);
    border-left: 2px solid rgba(var(--pt-primary-rgb, 79,158,248), 0.14);
    border-radius: 0 5px 5px 0;
    background: none;
    white-space: nowrap !important;
}
.sidebar-left .left-menu .sub-menu li > a > span {
    white-space: nowrap !important;
    overflow: hidden !important;
    text-overflow: ellipsis !important;
    display: inline-block !important;
    max-width: 180px !important;
    flex: 1;
    vertical-align: middle !important;
    font-weight: 400 !important;
    font-size: 0.70rem !important;
}
.sidebar-left .left-menu .sub-menu li > a:hover {
    color: var(--pt-sidebar-text-bright, #e2e8f0) !important;
    background: var(--pt-sidebar-hover-bg, rgba(79,158,248,0.07)) !important;
    border-left-color: var(--pt-sidebar-accent, rgba(79,158,248,0.5)) !important;
    transform: translateX(2px);
}
.sidebar-left .left-menu .sub-menu li > a.active {
    color: var(--pt-sidebar-active-text, #4f9ef8) !important;
    background: var(--pt-sidebar-submenu-bg, rgba(79,158,248,0.1)) !important;
    border-left-color: var(--pt-sidebar-accent, #4f9ef8) !important;
    font-weight: 500;
}
.sidebar-left .left-menu .sub-menu li > a > i {
    width: 13px; text-align: center; flex-shrink: 0; margin-right: 5px;
    font-size: 0.66rem; opacity: 0.85;
    color: var(--pt-sidebar-icon-color, #4f9ef8);
    transition: var(--t-fast);
    display: inline-block;
    background: none !important; border: none !important;
    box-shadow: none !important; border-radius: 0 !important; height: auto !important;
}
.sidebar-left .left-menu .sub-menu li > a::before { display: none !important; }
.sidebar-left .left-menu .sub-menu li > a:hover > i,
.sidebar-left .left-menu .sub-menu li > a.active > i {
    opacity: 1;
    color: var(--pt-sidebar-accent, #4f9ef8);
}

/* ── Sağlık temasında açık sidebar için koyu renkler ── */
[data-theme="health"]:not([data-dark="true"]) .sidebar-left .left-menu > li > a {
    color: var(--pt-sidebar-text, #374151) !important;
}
[data-theme="health"]:not([data-dark="true"]) .sidebar-left .left-menu > li > a:hover {
    color: var(--pt-sidebar-text-bright, #111827) !important;
}
[data-theme="health"]:not([data-dark="true"]) .sidebar-left .left-menu .sub-menu li > a {
    color: var(--pt-sidebar-text-dim, #6b7280) !important;
}

/* ── PRO Crown ── */
.pro-crown-icon {
    font-size: 13px;
    color: var(--pt-accent, #f59e0b) !important;
    filter: drop-shadow(0 0 5px rgba(var(--pt-primary-rgb, 245,158,11), 0.6));
    flex-shrink: 0;
    margin-left: 4px;
    margin-right: 2px;
}
</style>

<!-- ========== Left Sidebar Start ========== -->
<div class="sidebar-left">
    <div data-simplebar class="h-100 vertical-menu">
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="left-menu list-unstyled" id="side-menu">

                @if(function_exists('pergel_is_pro') && pergel_is_pro())
                <li class="sidebar-pro-header-item px-2 pt-1 pb-1">
                    <div class="sidebar-pro-card p-1 px-2 rounded-3 text-center position-relative overflow-hidden" style="background: linear-gradient(135deg, rgba(245, 158, 11, 0.2) 0%, rgba(124, 58, 237, 0.25) 100%); border: 1px solid rgba(245, 158, 11, 0.4); box-shadow: 0 4px 15px rgba(245, 158, 11, 0.15);">
                        <div class="d-flex align-items-center justify-content-between gap-1">
                            <div class="d-flex align-items-center gap-2">
                                <div class="pro-crown-box rounded-circle d-flex align-items-center justify-content-center" style="width:22px; height:22px; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); box-shadow: 0 0 8px rgba(245, 158, 11, 0.6); flex-shrink: 0;">
                                    <i class="ri-vip-crown-2-fill text-white" style="font-size: 12px;"></i>
                                </div>
                                <div class="text-start lh-1">
                                    <span class="fw-extrabold text-white d-block" style="font-size: 0.70rem; letter-spacing: 0.8px; font-family: 'Inter', sans-serif;">PERGEL PRO</span>
                                    <span class="text-white-50 d-block mt-1" style="font-size: 0.58rem;">Kurumsal Lisans</span>
                                </div>
                            </div>
                            <i class="ri-vip-crown-2-fill text-warning fs-5" title="PERGEL PRO Lisansı" style="filter: drop-shadow(0 0 6px rgba(245,158,11,0.6));"></i>
                        </div>
                    </div>
                </li>
                @else
                <li class="sidebar-pro-header-item px-2 pt-1 pb-1">
                    <a href="{{ route('admin.license.upgrade') }}" class="text-decoration-none d-block">
                        <div class="sidebar-pro-card p-1 px-2 rounded-3 text-center position-relative overflow-hidden" style="background: rgba(255, 255, 255, 0.04); border: 1px dashed rgba(245, 158, 11, 0.45); transition: all 0.2s ease;">
                            <div class="d-flex align-items-center justify-content-between gap-1">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-secondary bg-opacity-25" style="width:22px; height:22px; flex-shrink: 0;">
                                        <i class="ri-shield-user-line text-white-50" style="font-size: 11px;"></i>
                                    </div>
                                    <div class="text-start lh-1">
                                        <span class="fw-bold text-white d-block" style="font-size: 0.68rem; letter-spacing: 0.5px;">PERGEL Topluluk</span>
                                        <span class="text-warning d-block mt-1 fw-bold" style="font-size: 0.58rem;"><i class="ri-vip-crown-fill me-0.5"></i>Pro'ya Yükselt</span>
                                    </div>
                                </div>
                                <span class="badge bg-warning text-dark px-1.5 py-0.5 fw-bold" style="font-size: 8px;">FREE</span>
                            </div>
                        </div>
                    </a>
                </li>
                @endif

                <!-- ================= 1. AI & GENEL BAKIŞ ================= -->
                <li class="menu-title">YAPAY ZEKA & GENEL BAKIŞ</li>

                <li class="{{ request()->routeIs('ai-module.*') ? 'mm-active open' : '' }}">
                    <a href="javascript: void(0);" class="has-arrow {{ request()->routeIs('ai-module.*') ? 'active' : '' }}">
                        <i class="fas fa-robot"></i>
                        <span class="ai-assistant-text">Yapay Zeka Asistanı</span>
                        @unless(function_exists('pergel_has_module') && pergel_has_module('ai'))
                        <i class="ri-vip-crown-2-fill pro-crown-icon" title="PRO Özellik"></i>
                        @endunless
                    </a>
                    <ul class="sub-menu" style="{{ request()->routeIs('ai-module.*') ? 'display: block;' : '' }}">
                        <li><a href="{{ route('ai-module.index') }}" class="{{ request()->routeIs('ai-module.index') ? 'active' : '' }}"><i class="fas fa-microchip"></i> <span>AI Asistan Paneli</span></a></li>
                        <li><a href="{{ route('ai-module.proactive-assistant') }}" class="{{ request()->routeIs('ai-module.proactive-assistant') ? 'active' : '' }}"><i class="fas fa-lightbulb"></i> <span>Proaktif Öneriler</span></a></li>
                        <li><a href="{{ route('ai-module.risk-analysis') }}" class="{{ request()->routeIs('ai-module.risk-analysis') ? 'active' : '' }}"><i class="fas fa-shield-alt"></i> <span>AI Risk Analizi</span></a></li>
                        <li><a href="{{ route('ai-module.site-data-analysis') }}" class="{{ request()->routeIs('ai-module.site-data-analysis') ? 'active' : '' }}"><i class="fas fa-broadcast-tower"></i> <span>Saha Veri Analizi</span></a></li>
                        <li><a href="{{ route('ai-module.predictive-analytics') }}" class="{{ request()->routeIs('ai-module.predictive-analytics') ? 'active' : '' }}"><i class="fas fa-chart-line"></i> <span>Tahminleme & Analitik</span></a></li>
                        <li><a href="{{ route('ai-module.document-processing') }}" class="{{ request()->routeIs('ai-module.document-processing') ? 'active' : '' }}"><i class="fas fa-file-alt"></i> <span>Doküman İşleme</span></a></li>
                        <li><a href="{{ route('ai-module.compliance-report') }}" class="{{ request()->routeIs('ai-module.compliance-report') ? 'active' : '' }}"><i class="fas fa-check-circle"></i> <span>Mevzuat Uyum Raporu</span></a></li>
                    </ul>
                </li>

                <li>
                    <a href="{{ route('root') }}" class="{{ request()->routeIs('root') || request()->routeIs('dashboard') || request()->is('/') ? 'active' : '' }}">
                        <i class="fas fa-chart-pie"></i>
                        <span>Genel Bakış (Dashboard)</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('search.index') }}" class="{{ request()->routeIs('search.*') ? 'active' : '' }}">
                        <i class="fas fa-search"></i>
                        <span>Genel Arama</span>
                    </a>
                </li>

                <!-- ================= 2. PORTFÖY, İHALE & FİZİBİLİTE ================= -->
                <li class="menu-title">PORTFÖY, İHALE & FİZİBİLİTE</li>

                @if(!function_exists('pergel_has_module') || pergel_has_module('portfolio'))
                <li class="{{ request()->routeIs('portfolio.*', 'projects.index') ? 'mm-active open' : '' }}">
                    <a href="javascript: void(0);" class="has-arrow {{ request()->routeIs('portfolio.*', 'projects.index') ? 'active' : '' }}">
                        <i class="fas fa-cubes"></i>
                        <span>Portföy & Projeler</span>
                    </a>
                    <ul class="sub-menu" style="{{ request()->routeIs('portfolio.*', 'projects.index') ? 'display: block;' : '' }}">
                        <li><a href="{{ route('portfolio.management.index') }}" class="{{ request()->routeIs('portfolio.management.index') ? 'active' : '' }}"><i class="fas fa-folder-open"></i> <span>Portföy Yönetimi</span></a></li>
                        <li><a href="{{ route('projects.index') }}" class="{{ request()->routeIs('projects.index') ? 'active' : '' }}"><i class="fas fa-city"></i> <span>Tüm Projeler</span></a></li>
                        <li><a href="{{ route('portfolio.program-management.index') }}" class="{{ request()->routeIs('portfolio.program-management.*') ? 'active' : '' }}"><i class="fas fa-project-diagram"></i> <span>Program Yönetimi</span></a></li>
                        <li><a href="{{ route('portfolio.financials.index') }}" class="{{ request()->routeIs('portfolio.financials.*') ? 'active' : '' }}"><i class="fas fa-wallet"></i> <span>Portföy Finansal Özet</span></a></li>
                    </ul>
                </li>
                @endif

                <li class="{{ request()->routeIs('feasibility.*') ? 'mm-active open' : '' }}">
                    <a href="javascript: void(0);" class="has-arrow {{ request()->routeIs('feasibility.*') ? 'active' : '' }}">
                        <i class="fas fa-calculator"></i>
                        <span>Fizibilite & Sermaye</span>
                    </a>
                    <ul class="sub-menu" style="{{ request()->routeIs('feasibility.*') ? 'display: block;' : '' }}">
                        <li><a href="{{ route('feasibility.index') }}" class="{{ request()->routeIs('feasibility.index') ? 'active' : '' }}"><i class="fas fa-chart-pie"></i> <span>Fizibilite Analizi</span></a></li>
                        <li><a href="{{ route('feasibility.compare.get') }}" class="{{ request()->routeIs('feasibility.compare.*') ? 'active' : '' }}"><i class="fas fa-balance-scale"></i> <span>Senaryo Karşılaştırma</span></a></li>
                    </ul>
                </li>

                <li class="{{ request()->routeIs('tenders.*') || request()->is('portfolio/tenders/*') ? 'mm-active open' : '' }}">
                    <a href="javascript: void(0);" class="has-arrow {{ request()->routeIs('tenders.*') || request()->is('portfolio/tenders/*') ? 'active' : '' }}">
                        <i class="fas fa-file-signature"></i>
                        <span>Teklif & İhale Yönetimi</span>
                        @unless(function_exists('pergel_has_module') && pergel_has_module('tenders'))
                        <i class="ri-vip-crown-2-fill pro-crown-icon" title="PRO Özellik"></i>
                        @endunless
                    </a>
                    <ul class="sub-menu" style="{{ request()->routeIs('tenders.*') || request()->is('portfolio/tenders/*') ? 'display: block;' : '' }}">
                        <li><a href="{{ route('index', ['any' => 'portfolio/tenders/bid-management']) }}" class="{{ request()->is('portfolio/tenders/bid-management') ? 'active' : '' }}"><i class="fas fa-clipboard-list"></i> <span>İhale & Teklif Paneli</span></a></li>
                        <li><a href="{{ route('tenders.package.prepare') }}" class="{{ request()->routeIs('tenders.package.*') ? 'active' : '' }}"><i class="fas fa-file-archive"></i> <span>İhale Paketi Hazırlama</span></a></li>
                    </ul>
                </li>

                <!-- ================= 3. CRM & SATIŞ YÖNETİMİ ================= -->
                <li class="menu-title">CRM & SATIŞ YÖNETİMİ</li>

                <li class="{{ request()->routeIs('opportunities.*') ? 'mm-active open' : '' }}">
                    <a href="javascript: void(0);" class="has-arrow {{ request()->routeIs('opportunities.*') ? 'active' : '' }}">
                        <i class="fas fa-lightbulb"></i>
                        <span>Potansiyel Projeler (Fırsatlar)</span>
                    </a>
                    <ul class="sub-menu" style="{{ request()->routeIs('opportunities.*') ? 'display: block;' : '' }}">
                        <li><a href="{{ route('opportunities.list') }}" class="{{ request()->routeIs('opportunities.list') || request()->routeIs('opportunities.index') ? 'active' : '' }}"><i class="fas fa-tasks"></i> <span>Fırsatlar Listesi</span></a></li>
                        <li><a href="{{ route('opportunities.kanban') }}" class="{{ request()->routeIs('opportunities.kanban') ? 'active' : '' }}"><i class="fas fa-columns"></i> <span>Fırsatlar Kanban Panosu</span></a></li>
                    </ul>
                </li>

                <li class="{{ request()->routeIs('sales-targets.*', 'sales_performance.*') ? 'mm-active open' : '' }}">
                    <a href="javascript: void(0);" class="has-arrow {{ request()->routeIs('sales-targets.*', 'sales_performance.*') ? 'active' : '' }}">
                        <i class="fas fa-bullseye"></i>
                        <span>Satış Hedefleri & Performans</span>
                    </a>
                    <ul class="sub-menu" style="{{ request()->routeIs('sales-targets.*', 'sales_performance.*') ? 'display: block;' : '' }}">
                        <li><a href="{{ route('sales-targets.index') }}" class="{{ request()->routeIs('sales-targets.index') ? 'active' : '' }}"><i class="fas fa-crosshairs"></i> <span>Satış Hedefleri</span></a></li>
                        <li><a href="{{ route('sales-targets.dashboard') }}" class="{{ request()->routeIs('sales-targets.dashboard') ? 'active' : '' }}"><i class="fas fa-tachometer-alt"></i> <span>Hedef Dashboard</span></a></li>
                        <li><a href="{{ route('sales_performance.index') }}" class="{{ request()->routeIs('sales_performance.*') ? 'active' : '' }}"><i class="fas fa-chart-bar"></i> <span>Satış Performansı</span></a></li>
                    </ul>
                </li>

                <li>
                    <a href="{{ route('sales-reservations.index') }}" class="{{ request()->routeIs('sales-reservations.*') ? 'active' : '' }}">
                        <i class="fas fa-file-invoice-dollar"></i>
                        <span>Satış & Rezervasyon</span>
                    </a>
                </li>

                <li class="{{ request()->routeIs('options.*') ? 'mm-active open' : '' }}">
                    <a href="javascript: void(0);" class="has-arrow {{ request()->routeIs('options.*') ? 'active' : '' }}">
                        <i class="fas fa-bookmark"></i>
                        <span>Opsiyon Yönetimi</span>
                    </a>
                    <ul class="sub-menu" style="{{ request()->routeIs('options.*') ? 'display: block;' : '' }}">
                        <li><a href="{{ route('options.index') }}" class="{{ request()->routeIs('options.index') ? 'active' : '' }}"><i class="fas fa-bookmark"></i> <span>Tüm Opsiyonlar</span></a></li>
                        <li><a href="{{ route('options.about-to-expire') }}" class="{{ request()->routeIs('options.about-to-expire') ? 'active' : '' }}"><i class="fas fa-hourglass-half"></i> <span>Süresi Dolacak Opsiyonlar</span></a></li>
                    </ul>
                </li>

                <li class="{{ request()->routeIs('units.*', 'operations.properties.*') ? 'mm-active open' : '' }}">
                    <a href="javascript: void(0);" class="has-arrow {{ request()->routeIs('units.*', 'operations.properties.*') ? 'active' : '' }}">
                        <i class="fas fa-building"></i>
                        <span>Gayrimenkul & Üniteler</span>
                    </a>
                    <ul class="sub-menu" style="{{ request()->routeIs('units.*', 'operations.properties.*') ? 'display: block;' : '' }}">
                        <li><a href="{{ route('units.index') }}" class="{{ request()->routeIs('units.*') ? 'active' : '' }}"><i class="fas fa-building"></i> <span>Birim (Ünite) Yönetimi</span></a></li>
                        <li><a href="{{ route('operations.properties.index') }}" class="{{ request()->routeIs('operations.properties.*') ? 'active' : '' }}"><i class="fas fa-warehouse"></i> <span>Gayrimenkul Portföyü</span></a></li>
                    </ul>
                </li>

                <li class="{{ request()->routeIs('flexible-payments.*') ? 'mm-active open' : '' }}">
                    <a href="javascript: void(0);" class="has-arrow {{ request()->routeIs('flexible-payments.*') ? 'active' : '' }}">
                        <i class="fas fa-calculator"></i>
                        <span>Esnek Ödeme Planları</span>
                    </a>
                    <ul class="sub-menu" style="{{ request()->routeIs('flexible-payments.*') ? 'display: block;' : '' }}">
                        <li><a href="{{ route('flexible-payments.index') }}" class="{{ request()->routeIs('flexible-payments.index') ? 'active' : '' }}"><i class="fas fa-calculator"></i> <span>Ödeme Planları</span></a></li>
                        <li><a href="{{ route('flexible-payments.scenario_builder') }}" class="{{ request()->routeIs('flexible-payments.scenario_builder') ? 'active' : '' }}"><i class="fas fa-magic"></i> <span>Senaryo Oluşturucu</span></a></li>
                    </ul>
                </li>

                <li>
                    <a href="{{ route('digital-contracts.index') }}" class="{{ request()->routeIs('digital-contracts.*') ? 'active' : '' }}">
                        <i class="fas fa-file-contract"></i>
                        <span>Dijital Sözleşmeler</span>
                    </a>
                </li>

                <!-- ================= 4. MÜŞTERİ YÖNETİMİ & SATIŞ SONRASI ================= -->
                <li class="menu-title">MÜŞTERİ YÖNETİMİ & SATIŞ SONRASI</li>

                <li class="{{ request()->routeIs('customer_meetings.*', 'property_documents.*', 'payment-plans.*', 'installments.*', 'promissory_notes.*', 'trade_assets.*', 'consultant-changes.*', 'kvkk-consents.*') ? 'mm-active open' : '' }}">
                    <a href="javascript: void(0);" class="has-arrow {{ request()->routeIs('customer_meetings.*', 'property_documents.*', 'payment-plans.*', 'installments.*', 'promissory_notes.*', 'trade_assets.*', 'consultant-changes.*', 'kvkk-consents.*') ? 'active' : '' }}">
                        <i class="fas fa-handshake"></i>
                        <span>Müşteri İlişkileri & Takip</span>
                    </a>
                    <ul class="sub-menu" style="{{ request()->routeIs('customer_meetings.*', 'property_documents.*', 'payment-plans.*', 'installments.*', 'promissory_notes.*', 'trade_assets.*', 'consultant-changes.*', 'kvkk-consents.*') ? 'display: block;' : '' }}">
                        <li><a href="{{ route('customer_meetings.index') }}" class="{{ request()->routeIs('customer_meetings.*') ? 'active' : '' }}"><i class="fas fa-comments"></i> <span>Müşteri Görüşmeleri</span></a></li>
                        <li><a href="{{ route('property_documents.index') }}" class="{{ request()->routeIs('property_documents.*') ? 'active' : '' }}"><i class="fas fa-file-contract"></i> <span>Tapu & Mesken İşlemleri</span></a></li>
                        <li><a href="{{ route('payment-plans.index') }}" class="{{ request()->routeIs('payment-plans.*') ? 'active' : '' }}"><i class="fas fa-credit-card"></i> <span>Ödeme Planları</span></a></li>
                        <li><a href="{{ route('installments.index') }}" class="{{ request()->routeIs('installments.index') ? 'active' : '' }}"><i class="fas fa-receipt"></i> <span>Taksit Takibi</span></a></li>
                        <li><a href="{{ route('installments.payment_status_report') }}" class="{{ request()->routeIs('installments.payment_status_report') ? 'active' : '' }}"><i class="fas fa-chart-line"></i> <span>Taksit Durum Raporu</span></a></li>
                        <li><a href="{{ route('promissory_notes.index') }}" class="{{ request()->routeIs('promissory_notes.index') ? 'active' : '' }}"><i class="fas fa-file-invoice-dollar"></i> <span>Senetli Satış Takibi</span></a></li>
                        <li><a href="{{ route('promissory_notes.timeline') }}" class="{{ request()->routeIs('promissory_notes.timeline') ? 'active' : '' }}"><i class="fas fa-calendar-alt"></i> <span>Senet Vade Takvimi</span></a></li>
                        <li><a href="{{ route('trade_assets.index') }}" class="{{ request()->routeIs('trade_assets.*') ? 'active' : '' }}"><i class="fas fa-car"></i> <span>Takas Varlıkları</span></a></li>
                        <li><a href="{{ route('consultant-changes.index') }}" class="{{ request()->routeIs('consultant-changes.*') ? 'active' : '' }}"><i class="fas fa-exchange-alt"></i> <span>Danışman Değişimleri</span></a></li>
                        <li><a href="{{ route('kvkk-consents.index') }}" class="{{ request()->routeIs('kvkk-consents.*') ? 'active' : '' }}"><i class="fas fa-shield-alt"></i> <span>KVKK Onayları</span></a></li>
                    </ul>
                </li>

                <!-- ================= 5. PROJE & ŞANTİYE YÖNETİMİ ================= -->
                <li class="menu-title">PROJE & ŞANTİYE YÖNETİMİ</li>

                <li class="{{ request()->routeIs('projects.*', 'closeout.*') ? 'mm-active open' : '' }}">
                    <a href="javascript: void(0);" class="has-arrow {{ request()->routeIs('projects.*', 'closeout.*') ? 'active' : '' }}">
                        <i class="fas fa-sitemap"></i>
                        <span>Proje Yönetimi</span>
                    </a>
                    <ul class="sub-menu" style="{{ request()->routeIs('projects.*', 'closeout.*') ? 'display: block;' : '' }}">
                        <li><a href="{{ route('projects.index') }}" class="{{ request()->routeIs('projects.index') ? 'active' : '' }}"><i class="fas fa-city"></i> <span>Tüm Projeler (Şantiyeler)</span></a></li>
                        <li><a href="{{ route('projects.map') }}" class="{{ request()->routeIs('projects.map') ? 'active' : '' }}"><i class="fas fa-map-marked-alt"></i> <span>GIS Canlı Harita</span></a></li>
                        <li><a href="{{ route('index', ['any' => 'projects/documents']) }}" class="{{ request()->routeIs('projects.documents') ? 'active' : '' }}"><i class="fas fa-file-alt"></i> <span>Doküman Kontrolü</span></a></li>
                        <li><a href="{{ route('index', ['any' => 'projects/planning']) }}" class="{{ request()->routeIs('projects.planning') ? 'active' : '' }}"><i class="fas fa-calendar-alt"></i> <span>Proje Planlama</span></a></li>
                        <li><a href="{{ route('projects.communication.dashboard') }}" class="{{ request()->routeIs('projects.communication.*') ? 'active' : '' }}"><i class="fas fa-comments"></i> <span>Proje İletişimi</span></a></li>
                        <li><a href="{{ route('closeout.index') }}" class="{{ request()->routeIs('closeout.*') ? 'active' : '' }}"><i class="fas fa-flag-checkered"></i> <span>Proje Kapanış & Garanti</span></a></li>
                    </ul>
                </li>

                <li class="{{ request()->routeIs('contracts.*', 'change_orders.*') ? 'mm-active open' : '' }}">
                    <a href="javascript: void(0);" class="has-arrow {{ request()->routeIs('contracts.*', 'change_orders.*') ? 'active' : '' }}">
                        <i class="fas fa-file-signature"></i>
                        <span>Sözleşmeler & Değişiklikler</span>
                    </a>
                    <ul class="sub-menu" style="{{ request()->routeIs('contracts.*', 'change_orders.*') ? 'display: block;' : '' }}">
                        <li><a href="{{ route('index', ['any' => 'contracts/management']) }}" class="{{ request()->is('contracts/management') ? 'active' : '' }}"><i class="fas fa-file-alt"></i> <span>Müteahhit Sözleşmeleri</span></a></li>
                        <li><a href="{{ route('contracts.claims') }}" class="{{ request()->routeIs('contracts.claims*') ? 'active' : '' }}"><i class="fas fa-exclamation-triangle"></i> <span>Hak Talepleri (Claims)</span></a></li>
                        <li><a href="{{ route('contracts.library.index') }}" class="{{ request()->routeIs('contracts.library.*') ? 'active' : '' }}"><i class="fas fa-book"></i> <span>Sözleşme Kütüphanesi</span></a></li>
                        <li><a href="{{ route('change_orders.index') }}" class="{{ request()->routeIs('change_orders.*') ? 'active' : '' }}"><i class="fas fa-exchange-alt"></i> <span>Değişiklik Emirleri</span></a></li>
                    </ul>
                </li>

                @if(!function_exists('pergel_has_module') || pergel_has_module('bim'))
                <li class="{{ request()->routeIs('bim.*') ? 'mm-active open' : '' }}">
                    <a href="javascript: void(0);" class="has-arrow {{ request()->routeIs('bim.*') ? 'active' : '' }}">
                        <i class="fas fa-cube"></i>
                        <span>BIM & Dijital İkiz</span>
                    </a>
                    <ul class="sub-menu" style="{{ request()->routeIs('bim.*') ? 'display: block;' : '' }}">
                        <li><a href="{{ route('index', ['any' => 'bim/models']) }}" class="{{ request()->routeIs('bim.models') ? 'active' : '' }}"><i class="fas fa-cubes"></i> <span>Model Yönetimi</span></a></li>
                        <li><a href="{{ route('index', ['any' => 'bim/simulations']) }}" class="{{ request()->routeIs('bim.simulations') ? 'active' : '' }}"><i class="fas fa-cogs"></i> <span>4D/5D Simülasyonlar</span></a></li>
                    </ul>
                </li>
                @endif

                <!-- ================= 6. SAHA OPERASYONLARI, İSG & KALİTE ================= -->
                <li class="menu-title">SAHA OPERASYONLARI, İSG & KALİTE</li>

                <li class="{{ request()->routeIs('field.*', 'site_diaries.*', 'site_daily_logs.*', 'site_cameras.*') ? 'mm-active open' : '' }}">
                    <a href="javascript: void(0);" class="has-arrow {{ request()->routeIs('field.*', 'site_diaries.*', 'site_daily_logs.*', 'site_cameras.*') ? 'active' : '' }}">
                        <i class="fas fa-hard-hat"></i>
                        <span>Saha Operasyonları & Defterler</span>
                    </a>
                    <ul class="sub-menu" style="{{ request()->routeIs('field.*', 'site_diaries.*', 'site_daily_logs.*', 'site_cameras.*') ? 'display: block;' : '' }}">
                        <li><a href="{{ route('index', ['any' => 'field/operations']) }}"><i class="fas fa-people-carry"></i> <span>Saha Operasyonları</span></a></li>
                        <li><a href="{{ route('site_diaries.index') }}" class="{{ request()->routeIs('site_diaries.*') ? 'active' : '' }}"><i class="fas fa-mobile-alt"></i> <span>Mobil Saha Günlüğü</span></a></li>
                        <li><a href="{{ route('site_daily_logs.index') }}" class="{{ request()->routeIs('site_daily_logs.*') ? 'active' : '' }}"><i class="fas fa-book"></i> <span>Şantiye Günlük Defteri</span></a></li>
                        <li><a href="{{ route('site_cameras.index') }}" class="{{ request()->routeIs('site_cameras.*') ? 'active' : '' }}"><i class="fas fa-video"></i> <span>Canlı CCTV Kameralar</span></a></li>
                    </ul>
                </li>

                <li class="{{ request()->routeIs('field.qhse.*', 'concrete_tests.*', 'subcontractor_workers.*') ? 'mm-active open' : '' }}">
                    <a href="javascript: void(0);" class="has-arrow {{ request()->routeIs('field.qhse.*', 'concrete_tests.*', 'subcontractor_workers.*') ? 'active' : '' }}">
                        <i class="fas fa-user-shield"></i>
                        <span>Kalite Kontrol & İSG (QHSE)</span>
                    </a>
                    <ul class="sub-menu" style="{{ request()->routeIs('field.qhse.*', 'concrete_tests.*', 'subcontractor_workers.*') ? 'display: block;' : '' }}">
                        @if(!function_exists('pergel_has_module') || pergel_has_module('qhse'))
                        <li><a href="{{ route('index', ['any' => 'field/qhse/quality']) }}" class="{{ request()->routeIs('field.qhse.quality') ? 'active' : '' }}"><i class="fas fa-award"></i> <span>Kalite Kontrol Paneli</span></a></li>
                        <li><a href="{{ route('field.qhse.punchlist') }}" class="{{ request()->routeIs('field.qhse.punchlist') ? 'active' : '' }}"><i class="fas fa-check-double"></i> <span>Saha Eksik Listesi (Punchlist)</span></a></li>
                        <li><a href="{{ route('index', ['any' => 'field/qhse/safety']) }}" class="{{ request()->routeIs('field.qhse.safety') ? 'active' : '' }}"><i class="fas fa-user-shield"></i> <span>İş Güvenliği (İSG) Genel</span></a></li>
                        <li><a href="{{ route('field.qhse.safety.incidents.index') }}" class="{{ request()->routeIs('field.qhse.safety.incidents.*') ? 'active' : '' }}"><i class="fas fa-ambulance"></i> <span>İSG Olay & Kaza Kayıtları</span></a></li>
                        <li><a href="{{ route('field.qhse.safety.permits.index') }}" class="{{ request()->routeIs('field.qhse.safety.permits.*') ? 'active' : '' }}"><i class="fas fa-id-card"></i> <span>Çalışma İzinleri (Permits)</span></a></li>
                        <li><a href="{{ route('field.qhse.safety.toolbox.index') }}" class="{{ request()->routeIs('field.qhse.safety.toolbox.*') ? 'active' : '' }}"><i class="fas fa-bullhorn"></i> <span>İSG Bilgilendirme (Toolbox)</span></a></li>
                        @endif
                        <li><a href="{{ route('subcontractor_workers.index') }}" class="{{ request()->routeIs('subcontractor_workers.*') ? 'active' : '' }}"><i class="fas fa-id-badge"></i> <span>Taşeron İSG Kapısı & İşçiler</span></a></li>
                        <li><a href="{{ route('concrete_tests.index') }}" class="{{ request()->routeIs('concrete_tests.*') ? 'active' : '' }}"><i class="fas fa-flask"></i> <span>EBİS Beton Kırım Testleri</span></a></li>
                    </ul>
                </li>

                <!-- ================= 7. TAŞERON & TEDARİK ZİNCİRİ ================= -->
                <li class="menu-title">TAŞERON & TEDARİK ZİNCİRİ</li>

                <li class="{{ request()->routeIs('subcontractor.*', 'subcontractor_deductions.*', 'subcontractor_scores.*') ? 'mm-active open' : '' }}">
                    <a href="javascript: void(0);" class="has-arrow {{ request()->routeIs('subcontractor.*', 'subcontractor_deductions.*', 'subcontractor_scores.*') ? 'active' : '' }}">
                        <i class="fas fa-industry"></i>
                        <span>Taşeron Yönetimi</span>
                        @unless(function_exists('pergel_has_module') && pergel_has_module('subcontractor'))
                        <i class="ri-vip-crown-2-fill pro-crown-icon" title="PRO Özellik"></i>
                        @endunless
                    </a>
                    <ul class="sub-menu" style="{{ request()->routeIs('subcontractor.*', 'subcontractor_deductions.*', 'subcontractor_scores.*') ? 'display: block;' : '' }}">
                        <li><a href="{{ route('subcontractor.index') }}" class="{{ request()->routeIs('subcontractor.index') ? 'active' : '' }}"><i class="fas fa-address-book"></i> <span>Taşeron Rehberi & Dizin</span></a></li>
                        <li><a href="{{ route('subcontractor.contracts.index') }}" class="{{ request()->routeIs('subcontractor.contracts.*') ? 'active' : '' }}"><i class="fas fa-handshake"></i> <span>Taşeron Sözleşmeleri</span></a></li>
                        <li><a href="{{ route('subcontractor.progress.approval') }}" class="{{ request()->routeIs('subcontractor.progress.*') ? 'active' : '' }}"><i class="fas fa-tasks"></i> <span>İlerleme & Hakediş Onayı</span></a></li>
                        <li><a href="{{ route('subcontractor_deductions.index') }}" class="{{ request()->routeIs('subcontractor_deductions.*') ? 'active' : '' }}"><i class="fas fa-cut"></i> <span>Taşeron Yasal Kesintiler</span></a></li>
                        <li><a href="{{ route('subcontractor_scores.index') }}" class="{{ request()->routeIs('subcontractor_scores.*') ? 'active' : '' }}"><i class="fas fa-star-half-alt"></i> <span>Taşeron Puanlama (Scoring)</span></a></li>
                        <li><a href="{{ route('subcontractor.dashboard') }}" class="{{ request()->routeIs('subcontractor.dashboard') ? 'active' : '' }}"><i class="fas fa-external-link-alt"></i> <span>Taşeron Portalı</span></a></li>
                    </ul>
                </li>

                <li class="{{ request()->routeIs('supply.*') ? 'mm-active open' : '' }}">
                    <a href="javascript: void(0);" class="has-arrow {{ request()->routeIs('supply.*') ? 'active' : '' }}">
                        <i class="fas fa-truck-loading"></i>
                        <span>Tedarik & Satınalma</span>
                    </a>
                    <ul class="sub-menu" style="{{ request()->routeIs('supply.*') ? 'display: block;' : '' }}">
                        <li><a href="{{ route('supply.vendors') }}" class="{{ request()->routeIs('supply.vendors*') ? 'active' : '' }}"><i class="fas fa-store"></i> <span>Tedarikçi Yönetimi (Vendors)</span></a></li>
                        <li><a href="{{ route('supply.procurement.requisitions') }}" class="{{ request()->routeIs('supply.procurement.requisitions*') ? 'active' : '' }}"><i class="fas fa-clipboard-list"></i> <span>Satınalma Talepleri (Requisitions)</span></a></li>
                        <li><a href="{{ route('supply.procurement.purchase_orders') }}" class="{{ request()->is('supply/procurement/purchase-orders') ? 'active' : '' }}"><i class="fas fa-shopping-basket"></i> <span>Satınalma Siparişleri & Faturaları</span></a></li>
                    </ul>
                </li>

                <!-- ================= 8. HAKEDİŞ & FİNANSAL YÖNETİM ================= -->
                <li class="menu-title">HAKEDİŞ & FİNANSAL YÖNETİM</li>

                <li class="{{ request()->routeIs('progress-payments.*') ? 'mm-active open' : '' }}">
                    <a href="javascript: void(0);" class="has-arrow {{ request()->routeIs('progress-payments.*') ? 'active' : '' }}">
                        <i class="fas fa-calculator"></i>
                        <span>Hakedişler</span>
                        @unless(function_exists('pergel_has_module') && pergel_has_module('finance'))
                        <i class="ri-vip-crown-2-fill pro-crown-icon" title="PRO Özellik"></i>
                        @endunless
                    </a>
                    <ul class="sub-menu" style="{{ request()->routeIs('progress-payments.*') ? 'display: block;' : '' }}">
                        <li><a href="{{ route('progress-payments.index') }}" class="{{ request()->routeIs('progress-payments.index') ? 'active' : '' }}"><i class="fas fa-list"></i> <span>Hakediş Listesi</span></a></li>
                        <li><a href="{{ route('progress-payments.create') }}" class="{{ request()->routeIs('progress-payments.create') ? 'active' : '' }}"><i class="fas fa-plus-circle"></i> <span>Yeni Hakediş</span></a></li>
                        <li><a href="{{ route('progress-payments.reports') }}" class="{{ request()->routeIs('progress-payments.reports*') ? 'active' : '' }}"><i class="fas fa-chart-bar"></i> <span>Hakediş Raporları</span></a></li>
                    </ul>
                </li>

                <li class="{{ request()->routeIs('finance.*', 'partners.*') ? 'mm-active open' : '' }}">
                    <a href="javascript: void(0);" class="has-arrow {{ request()->routeIs('finance.*', 'partners.*') ? 'active' : '' }}">
                        <i class="fas fa-coins"></i>
                        <span>Finans Paneli</span>
                        @unless(function_exists('pergel_has_module') && pergel_has_module('finance'))
                        <i class="ri-vip-crown-2-fill pro-crown-icon" title="PRO Özellik"></i>
                        @endunless
                    </a>
                    <ul class="sub-menu" style="{{ request()->routeIs('finance.*', 'partners.*') ? 'display: block;' : '' }}">
                        <li><a href="{{ route('index', ['any' => 'finance/dashboard']) }}" class="{{ request()->is('finance/dashboard') ? 'active' : '' }}"><i class="fas fa-tachometer-alt"></i> <span>Finans Panosu</span></a></li>
                        <li><a href="{{ route('index', ['any' => 'finance/accounts/customers']) }}" class="{{ request()->is('finance/accounts/customers') ? 'active' : '' }}"><i class="fas fa-user-friends"></i> <span>Müşteri Hesapları</span></a></li>
                        <li><a href="{{ route('index', ['any' => 'finance/accounts/suppliers']) }}" class="{{ request()->is('finance/accounts/suppliers') ? 'active' : '' }}"><i class="fas fa-people-carry"></i> <span>Tedarikçi Hesapları</span></a></li>
                        <li><a href="{{ route('index', ['any' => 'finance/cash/accounts']) }}" class="{{ request()->is('finance/cash/accounts') ? 'active' : '' }}"><i class="fas fa-wallet"></i> <span>Kasa & Banka Hesapları</span></a></li>
                        <li><a href="{{ route('finance.accounts.vouchers.list') }}" class="{{ request()->routeIs('finance.accounts.vouchers.list') ? 'active' : '' }}"><i class="fas fa-file-invoice"></i> <span>Mahsup & Fiş İşlemleri</span></a></li>
                        <li><a href="{{ route('index', ['any' => 'finance/cash/pos']) }}" class="{{ request()->is('finance/cash/pos') ? 'active' : '' }}"><i class="fas fa-credit-card"></i> <span>POS İşlemleri</span></a></li>
                        <li><a href="{{ route('index', ['any' => 'finance/cheques/customers']) }}" class="{{ request()->is('finance/cheques/customers') ? 'active' : '' }}"><i class="fas fa-money-check-alt"></i> <span>Çek & Senetler</span></a></li>
                        <li><a href="{{ route('index', ['any' => 'finance/expenses/general']) }}" class="{{ request()->is('finance/expenses/general') ? 'active' : '' }}"><i class="fas fa-cash-register"></i> <span>Genel Giderler</span></a></li>
                        <li><a href="{{ route('finance.expenses.categories') }}" class="{{ request()->routeIs('finance.expenses.categories') ? 'active' : '' }}"><i class="fas fa-tags"></i> <span>Gider Kategorileri</span></a></li>
                        <li><a href="{{ route('finance.invoices.index') }}" class="{{ request()->routeIs('finance.invoices.index') ? 'active' : '' }}"><i class="fas fa-file-invoice-dollar"></i> <span>Satış Faturaları</span></a></li>
                        <li><a href="{{ route('partners.index') }}" class="{{ request()->routeIs('partners.*') ? 'active' : '' }}"><i class="fas fa-handshake"></i> <span>Şirket Ortakları</span></a></li>
                    </ul>
                </li>

                <li class="{{ request()->routeIs('finance.forecast.*', 'finance.risks.*', 'finance.trends.*', 'currency-exchanges.*', 'finance.edoc.*') ? 'mm-active open' : '' }}">
                    <a href="javascript: void(0);" class="has-arrow {{ request()->routeIs('finance.forecast.*', 'finance.risks.*', 'finance.trends.*', 'currency-exchanges.*', 'finance.edoc.*') ? 'active' : '' }}">
                        <i class="fas fa-magic"></i>
                        <span>İleri Finans & Dönüşüm</span>
                    </a>
                    <ul class="sub-menu" style="{{ request()->routeIs('finance.forecast.*', 'finance.risks.*', 'finance.trends.*', 'currency-exchanges.*', 'finance.edoc.*') ? 'display: block;' : '' }}">
                        <li><a href="{{ route('finance.forecast.dashboard') }}" class="{{ request()->routeIs('finance.forecast.*') ? 'active' : '' }}"><i class="fas fa-chart-area"></i> <span>Nakit Akış Tahmini</span></a></li>
                        <li><a href="{{ route('finance.risks.dashboard') }}" class="{{ request()->routeIs('finance.risks.*') ? 'active' : '' }}"><i class="fas fa-shield-alt"></i> <span>Finansal Risk Yönetimi</span></a></li>
                        <li><a href="{{ route('finance.trends.dashboard') }}" class="{{ request()->routeIs('finance.trends.*') ? 'active' : '' }}"><i class="fas fa-chart-line"></i> <span>Finansal Trendler & Analiz</span></a></li>
                        <li><a href="{{ route('currency-exchanges.index') }}" class="{{ request()->routeIs('currency-exchanges.*') ? 'active' : '' }}"><i class="fas fa-money-bill-wave"></i> <span>Döviz İşlemleri & Kurlar</span></a></li>
                        <li><a href="{{ route('index', ['any' => 'finance/edoc/efatura']) }}" class="{{ request()->is('finance/edoc/*') ? 'active' : '' }}"><i class="fas fa-sync-alt"></i> <span>E-Dönüşüm (E-Fatura/E-İrsaliye)</span></a></li>
                    </ul>
                </li>

                <!-- ================= 9. KAYNAKLAR, İK & TESİS YÖNETİMİ ================= -->
                <li class="menu-title">KAYNAKLAR, İK & TESİS YÖNETİMİ</li>

                <li class="{{ request()->routeIs('resources.hr.*', 'hr_personnels.*') ? 'mm-active open' : '' }}">
                    <a href="javascript: void(0);" class="has-arrow {{ request()->routeIs('resources.hr.*', 'hr_personnels.*') ? 'active' : '' }}">
                        <i class="fas fa-users"></i>
                        <span>İnsan Kaynakları</span>
                    </a>
                    <ul class="sub-menu" style="{{ request()->routeIs('resources.hr.*', 'hr_personnels.*') ? 'display: block;' : '' }}">
                        <li><a href="{{ route('resources.hr.dashboard') }}" class="{{ request()->routeIs('resources.hr.dashboard') ? 'active' : '' }}"><i class="fas fa-tachometer-alt"></i> <span>İK Panosu</span></a></li>
                        <li><a href="{{ route('resources.hr.employees') }}" class="{{ request()->routeIs('resources.hr.employees') ? 'active' : '' }}"><i class="fas fa-users"></i> <span>Personel Listesi</span></a></li>
                        <li><a href="{{ route('resources.hr.workforce_planning') }}" class="{{ request()->routeIs('resources.hr.workforce_planning') ? 'active' : '' }}"><i class="fas fa-user-clock"></i> <span>İş Gücü Planlaması</span></a></li>
                        <li><a href="{{ route('resources.hr.certificates') }}" class="{{ request()->routeIs('resources.hr.certificates') ? 'active' : '' }}"><i class="fas fa-certificate"></i> <span>Sertifikalar & Yetkinlikler</span></a></li>
                        <li><a href="{{ route('hr_personnels.index') }}" class="{{ request()->routeIs('hr_personnels.*') ? 'active' : '' }}"><i class="fas fa-user-tie"></i> <span>Personel Özlük Dosyaları</span></a></li>
                        <li><a href="{{ route('index', ['any' => 'finance/hr/expenses']) }}" class="{{ request()->is('finance/hr/expenses') ? 'active' : '' }}"><i class="fas fa-receipt"></i> <span>Personel Masrafları</span></a></li>
                        <li><a href="{{ route('index', ['any' => 'finance/hr/advances']) }}" class="{{ request()->is('finance/hr/advances') ? 'active' : '' }}"><i class="fas fa-hand-holding-usd"></i> <span>Avans & Borç Takibi</span></a></li>
                        <li><a href="{{ route('index', ['any' => 'finance/hr/payroll']) }}" class="{{ request()->is('finance/hr/payroll') ? 'active' : '' }}"><i class="fas fa-file-alt"></i> <span>Bordro Tahakkuk</span></a></li>
                    </ul>
                </li>

                <li class="{{ request()->routeIs('purchasing.*', 'finance.stock.*') ? 'mm-active open' : '' }}">
                    <a href="javascript: void(0);" class="has-arrow {{ request()->routeIs('purchasing.*', 'finance.stock.*') ? 'active' : '' }}">
                        <i class="fas fa-boxes"></i>
                        <span>Satınalma & Stok</span>
                    </a>
                    <ul class="sub-menu" style="{{ request()->routeIs('purchasing.*', 'finance.stock.*') ? 'display: block;' : '' }}">
                        <li><a href="{{ route('purchasing.quotations.index') }}" class="{{ request()->routeIs('purchasing.quotations.*') ? 'active' : '' }}"><i class="fas fa-file-alt"></i> <span>Teklif & Satınalma</span></a></li>
                        <li><a href="{{ route('purchasing.approvals.pending') }}" class="{{ request()->routeIs('purchasing.approvals.*') ? 'active' : '' }}"><i class="fas fa-check-circle"></i> <span>Onay Süreçleri</span></a></li>
                        <li><a href="{{ route('purchasing.budgets.index') }}" class="{{ request()->routeIs('purchasing.budgets.*') ? 'active' : '' }}"><i class="fas fa-wallet"></i> <span>Satınalma Bütçeleri</span></a></li>
                        <li><a href="{{ route('index', ['any' => 'finance/stock/cards']) }}" class="{{ request()->is('finance/stock/cards') ? 'active' : '' }}"><i class="fas fa-box"></i> <span>Stok Kartları</span></a></li>
                        <li><a href="{{ route('index', ['any' => 'finance/stock/movements']) }}" class="{{ request()->is('finance/stock/movements') ? 'active' : '' }}"><i class="fas fa-dolly-flatbed"></i> <span>Stok Hareketleri</span></a></li>
                    </ul>
                </li>

                <li class="{{ request()->routeIs('facility.*', 'resources.equipment.*', 'fleet_telemetries.*') ? 'mm-active open' : '' }}">
                    <a href="javascript: void(0);" class="has-arrow {{ request()->routeIs('facility.*', 'resources.equipment.*', 'fleet_telemetries.*') ? 'active' : '' }}">
                        <i class="fas fa-building"></i>
                        <span>Tesis & Varlıklar</span>
                    </a>
                    <ul class="sub-menu" style="{{ request()->routeIs('facility.*', 'resources.equipment.*', 'fleet_telemetries.*') ? 'display: block;' : '' }}">
                        <li><a href="{{ route('facility.index') }}" class="{{ request()->routeIs('facility.index') ? 'active' : '' }}"><i class="fas fa-tachometer-alt"></i> <span>Tesis Panosu</span></a></li>
                        <li><a href="{{ route('facility.assets') }}" class="{{ request()->routeIs('facility.assets') ? 'active' : '' }}"><i class="fas fa-boxes"></i> <span>Tesis Varlıkları</span></a></li>
                        <li><a href="{{ route('resources.equipment.dashboard') }}" class="{{ request()->routeIs('resources.equipment.*') ? 'active' : '' }}"><i class="fas fa-truck-monster"></i> <span>Ekipman Parkı</span></a></li>
                        <li><a href="{{ route('fleet_telemetries.index') }}" class="{{ request()->routeIs('fleet_telemetries.*') ? 'active' : '' }}"><i class="fas fa-truck"></i> <span>İş Makinesi Telemetri</span></a></li>
                        <li><a href="{{ route('index', ['any' => 'finance/assets/list']) }}" class="{{ request()->is('finance/assets/list') ? 'active' : '' }}"><i class="fas fa-city"></i> <span>Duran Varlıklar & Amortisman</span></a></li>
                        <li><a href="{{ route('facility.maintenance') }}" class="{{ request()->routeIs('facility.maintenance') ? 'active' : '' }}"><i class="fas fa-wrench"></i> <span>Bakım & Onarım</span></a></li>
                        <li>
                            <a href="{{ route('facility.qr.index') }}" class="{{ request()->routeIs('facility.qr.*') ? 'active' : '' }}">
                                <i class="fas fa-qrcode"></i> <span>QR Kod Yönetimi</span>
                                @unless(function_exists('pergel_has_module') && pergel_has_module('qr_sla'))
                                <i class="ri-vip-crown-2-fill pro-crown-icon" title="PRO Özellik"></i>
                                @endunless
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('facility.sla.index') }}" class="{{ request()->routeIs('facility.sla.*') ? 'active' : '' }}">
                                <i class="fas fa-shield-alt"></i> <span>SLA Hizmet Seviyesi</span>
                                @unless(function_exists('pergel_has_module') && pergel_has_module('qr_sla'))
                                <i class="ri-vip-crown-2-fill pro-crown-icon" title="PRO Özellik"></i>
                                @endunless
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- ================= 10. TÜRKİYE İNŞAAT MEVZUATI & YASAL UYUM ================= -->
                <li class="menu-title">TR İNŞAAT MEVZUATI & YASAL UYUM</li>

                <li class="{{ request()->routeIs('sgk.*', 'land_owner_contracts.*', 'csb_poz.*', 'price_differences.*', 'ewaybills.*', 'building_inspections.*', 'vat_returns.*', 'real_estate_valuations.*', 'budget_simulators.*', 'ai_timesheet_analyzers.*', 'waste_managements.*', 'e_agreements.*') ? 'mm-active open' : '' }}">
                    <a href="javascript: void(0);" class="has-arrow {{ request()->routeIs('sgk.*', 'land_owner_contracts.*', 'csb_poz.*', 'price_differences.*', 'ewaybills.*', 'building_inspections.*', 'vat_returns.*', 'real_estate_valuations.*', 'budget_simulators.*', 'ai_timesheet_analyzers.*', 'waste_managements.*', 'e_agreements.*') ? 'active' : '' }}">
                        <i class="fas fa-flag"></i>
                        <span>TR Mevzuat & Uyum</span>
                        @unless(function_exists('pergel_has_module') && pergel_has_module('tr_compliance'))
                        <i class="ri-vip-crown-2-fill pro-crown-icon" title="PRO Özellik"></i>
                        @endunless
                    </a>
                    <ul class="sub-menu" style="{{ request()->routeIs('sgk.*', 'land_owner_contracts.*', 'csb_poz.*', 'price_differences.*', 'ewaybills.*', 'building_inspections.*', 'vat_returns.*', 'real_estate_valuations.*', 'budget_simulators.*', 'ai_timesheet_analyzers.*', 'waste_managements.*', 'e_agreements.*') ? 'display: block;' : '' }}">
                        <li><a href="{{ route('sgk.index') }}" class="{{ request()->routeIs('sgk.*') ? 'active' : '' }}"><i class="fas fa-calculator"></i> <span>SGK Asgari İşçilik</span></a></li>
                        <li><a href="{{ route('land_owner_contracts.index') }}" class="{{ request()->routeIs('land_owner_contracts.index') ? 'active' : '' }}"><i class="fas fa-handshake"></i> <span>Kat Karşılığı Matrisi</span></a></li>
                        <li><a href="{{ route('land_owner_contracts.ai_analysis') }}" class="{{ request()->routeIs('land_owner_contracts.ai_analysis') ? 'active' : '' }}"><i class="fas fa-brain"></i> <span>AI Kat Karşılığı Analiz</span></a></li>
                        <li><a href="{{ route('csb_poz.index') }}" class="{{ request()->routeIs('csb_poz.*') ? 'active' : '' }}"><i class="fas fa-book"></i> <span>ÇŞB Poz Kütüphanesi</span></a></li>
                        <li><a href="{{ route('price_differences.index') }}" class="{{ request()->routeIs('price_differences.*') ? 'active' : '' }}"><i class="fas fa-chart-line"></i> <span>Fiyat Farkı (Yİ-ÜFE)</span></a></li>
                        <li><a href="{{ route('ewaybills.index') }}" class="{{ request()->routeIs('ewaybills.*') ? 'active' : '' }}"><i class="fas fa-file-invoice"></i> <span>E-İrsaliye Entegrasyonu</span></a></li>
                        <li><a href="{{ route('building_inspections.index') }}" class="{{ request()->routeIs('building_inspections.*') ? 'active' : '' }}"><i class="fas fa-hard-hat"></i> <span>Yapı Denetim Takibi</span></a></li>
                        <li><a href="{{ route('vat_returns.index') }}" class="{{ request()->routeIs('vat_returns.*') ? 'active' : '' }}"><i class="fas fa-file-signature"></i> <span>KDV İade (YMM)</span></a></li>
                        <li><a href="{{ route('real_estate_valuations.index') }}" class="{{ request()->routeIs('real_estate_valuations.*') ? 'active' : '' }}"><i class="fas fa-building"></i> <span>SPK Gayrimenkul Değerleme</span></a></li>
                        <li><a href="{{ route('budget_simulators.index') }}" class="{{ request()->routeIs('budget_simulators.*') ? 'active' : '' }}"><i class="fas fa-chart-line"></i> <span>Bütçe ve Kur Simülatörü</span></a></li>
                        <li><a href="{{ route('ai_timesheet_analyzers.index') }}" class="{{ request()->routeIs('ai_timesheet_analyzers.*') ? 'active' : '' }}"><i class="fas fa-robot"></i> <span>AI Puantaj Analizörü</span></a></li>
                        <li><a href="{{ route('waste_managements.index') }}" class="{{ request()->routeIs('waste_managements.*') ? 'active' : '' }}"><i class="fas fa-recycle"></i> <span>Şantiye Atık (MoTAT)</span></a></li>
                        <li><a href="{{ route('e_agreements.index') }}" class="{{ request()->routeIs('e_agreements.*') ? 'active' : '' }}"><i class="fas fa-handshake"></i> <span>Cari E-Mutabakat (BA/BS)</span></a></li>
                    </ul>
                </li>

                <!-- ================= 11. İŞ ZEKASI, RAPORLAR & ÇALIŞMA ALANI ================= -->
                <li class="menu-title">İŞ ZEKASI, RAPORLAR & ÇALIŞMA ALANI</li>

                <li>
                    <a href="{{ route('executive_dashboards.index') }}" class="{{ request()->routeIs('executive_dashboards.*') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Executive Dashboard (BI)</span>
                        @unless(function_exists('pergel_has_module') && pergel_has_module('bi_reports'))
                        <i class="ri-vip-crown-2-fill pro-crown-icon" title="PRO Özellik"></i>
                        @endunless
                    </a>
                </li>

                <li class="{{ request()->routeIs('reports.*', 'advanced-reports.*', 'analytics.*', 'dynamic_reports.*') ? 'mm-active open' : '' }}">
                    <a href="javascript: void(0);" class="has-arrow {{ request()->routeIs('reports.*', 'advanced-reports.*', 'analytics.*', 'dynamic_reports.*') ? 'active' : '' }}">
                        <i class="fas fa-chart-area"></i>
                        <span>Raporlar & Analitik</span>
                    </a>
                    <ul class="sub-menu" style="{{ request()->routeIs('reports.*', 'advanced-reports.*', 'analytics.*', 'dynamic_reports.*') ? 'display: block;' : '' }}">
                        <li><a href="{{ route('analytics.index') }}" class="{{ request()->routeIs('analytics.index') ? 'active' : '' }}"><i class="fas fa-chart-pie"></i> <span>Analitik Panosu</span></a></li>
                        <li><a href="{{ route('analytics.risk-map') }}" class="{{ request()->routeIs('analytics.risk-map') ? 'active' : '' }}"><i class="fas fa-map-marked-alt"></i> <span>Risk Haritası</span></a></li>
                        <li><a href="{{ route('reports.projects.financial_analysis') }}" class="{{ request()->routeIs('reports.projects.*') ? 'active' : '' }}"><i class="fas fa-chart-bar"></i> <span>Proje Analizi</span></a></li>
                        <li><a href="{{ route('reports.sales.accounting_summary') }}" class="{{ request()->routeIs('reports.sales.*') ? 'active' : '' }}"><i class="fas fa-receipt"></i> <span>Satış & Muhasebe Raporları</span></a></li>
                        <li><a href="{{ route('reports.general.kpi_dashboard') }}" class="{{ request()->routeIs('reports.general.*') ? 'active' : '' }}"><i class="fas fa-tachometer-alt"></i> <span>KPI & Genel Raporlar</span></a></li>
                        <li><a href="{{ route('advanced-reports.index') }}" class="{{ request()->routeIs('advanced-reports.index') ? 'active' : '' }}"><i class="fas fa-cubes"></i> <span>Gelişmiş Raporlar</span></a></li>
                        <li><a href="{{ route('advanced-reports.consultant_performance') }}" class="{{ request()->routeIs('advanced-reports.consultant_performance') ? 'active' : '' }}"><i class="fas fa-user-check"></i> <span>Danışman Performansı</span></a></li>
                        <li><a href="{{ route('dynamic_reports.index') }}" class="{{ request()->routeIs('dynamic_reports.*') ? 'active' : '' }}"><i class="fas fa-file-pdf"></i> <span>Dinamik Raporlar</span></a></li>
                    </ul>
                </li>

                <li class="{{ request()->routeIs('tasks.*', 'apps-kanban', 'notes.*', 'calendar.*', 'messages.*', 'gallery.*') ? 'mm-active open' : '' }}">
                    <a href="javascript: void(0);" class="has-arrow {{ request()->routeIs('tasks.*', 'apps-kanban', 'notes.*', 'calendar.*', 'messages.*', 'gallery.*') ? 'active' : '' }}">
                        <i class="fas fa-tasks"></i>
                        <span>Çalışma Alanı & Araçlar</span>
                    </a>
                    <ul class="sub-menu" style="{{ request()->routeIs('tasks.*', 'apps-kanban', 'notes.*', 'calendar.*', 'messages.*', 'gallery.*') ? 'display: block;' : '' }}">
                        <li><a href="{{ route('tasks.index') }}" class="{{ request()->routeIs('tasks.*') ? 'active' : '' }}"><i class="fas fa-check-square"></i> <span>Görev Yönetimi</span></a></li>
                        <li><a href="{{ route('apps-kanban') }}" class="{{ request()->routeIs('apps-kanban') ? 'active' : '' }}"><i class="fas fa-columns"></i> <span>Kanban Panosu</span></a></li>
                        <li><a href="{{ route('notes.index') }}" class="{{ request()->routeIs('notes.*') ? 'active' : '' }}"><i class="fas fa-sticky-note"></i> <span>Çalışma Notları</span></a></li>
                        <li><a href="{{ route('calendar.index') }}" class="{{ request()->routeIs('calendar.*') ? 'active' : '' }}"><i class="fas fa-calendar-alt"></i> <span>Etkinlik Takvimi</span></a></li>
                        <li><a href="{{ route('messages.index') }}" class="{{ request()->routeIs('messages.*') ? 'active' : '' }}"><i class="fas fa-comments"></i> <span>İç İletişim / Mesajlar</span></a></li>
                        <li><a href="{{ route('gallery.index') }}" class="{{ request()->routeIs('gallery.*') ? 'active' : '' }}"><i class="fas fa-images"></i> <span>Fotoğraf & Galeri</span></a></li>
                    </ul>
                </li>

                <!-- ================= 12. PLATFORM YÖNETİMİ & DESTEK ================= -->
                <li class="menu-title">PLATFORM YÖNETİMİ & DESTEK</li>

                <li class="{{ request()->routeIs('admin.*', 'activity-logs.*', 'data-import-export.*') ? 'mm-active open' : '' }}">
                    <a href="javascript: void(0);" class="has-arrow {{ request()->routeIs('admin.*', 'activity-logs.*', 'data-import-export.*') ? 'active' : '' }}">
                        <i class="fas fa-cogs"></i>
                        <span>Platform Yönetimi</span>
                    </a>
                    <ul class="sub-menu" style="{{ request()->routeIs('admin.*', 'activity-logs.*', 'data-import-export.*') ? 'display: block;' : '' }}">
                        <li><a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}"><i class="fas fa-user-shield"></i> <span>Kullanıcılar & Roller</span></a></li>
                        <li><a href="{{ route('admin.modules.index') }}" class="{{ request()->routeIs('admin.modules.*') ? 'active' : '' }}"><i class="fas fa-plug text-warning"></i> <span>Modüller & Eklentiler</span> <span class="badge bg-warning text-dark ms-1" style="font-size: 0.65rem;">ZIP</span></a></li>
                        <li><a href="{{ route('admin.license.modules') }}" class="{{ request()->routeIs('admin.license.modules') ? 'active' : '' }}"><i class="fas fa-store"></i> <span>Modül Marketi & Paketler</span> <span class="badge bg-warning text-dark ms-1" style="font-size: 0.65rem;">YENİ</span></a></li>
                        <li><a href="{{ route('admin.license.index') }}" class="{{ request()->routeIs('admin.license.index') ? 'active' : '' }}"><i class="fas fa-key"></i> <span>Yazılım Lisansı</span></a></li>
                        <li><a href="{{ route('index', ['any' => 'admin/standards']) }}" class="{{ request()->routeIs('admin.standards') ? 'active' : '' }}"><i class="fas fa-sliders-h"></i> <span>Şirket Ayarları</span></a></li>
                        <li><a href="{{ route('admin.kdv.index') }}" class="{{ request()->routeIs('admin.kdv.*') ? 'active' : '' }}"><i class="fas fa-percent"></i> <span>KDV Oranları</span></a></li>
                        <li><a href="{{ route('data-import-export.index') }}" class="{{ request()->routeIs('data-import-export.*') ? 'active' : '' }}"><i class="fas fa-exchange-alt"></i> <span>Veri İçe/Dışa Aktar</span></a></li>
                        <li><a href="{{ route('activity-logs.index') }}" class="{{ request()->routeIs('activity-logs.index') ? 'active' : '' }}"><i class="fas fa-history"></i> <span>Aktivite Günlüğü</span></a></li>
                        <li><a href="{{ route('activity-logs.user_audit_trail') }}" class="{{ request()->routeIs('activity-logs.user_audit_trail') ? 'active' : '' }}"><i class="fas fa-user-audit"></i> <span>Kullanıcı Denetim İzi</span></a></li>
                        <li><a href="{{ route('admin.system.index') }}" class="{{ request()->routeIs('admin.system.*') ? 'active' : '' }}"><i class="fas fa-server"></i> <span>Sistem Kayıtları</span></a></li>
                    </ul>
                </li>

                <li class="{{ request()->routeIs('help.*') ? 'mm-active open' : '' }}">
                    <a href="javascript: void(0);" class="has-arrow {{ request()->routeIs('help.*') ? 'active' : '' }}">
                        <i class="fas fa-life-ring"></i>
                        <span>Yardım & Destek</span>
                    </a>
                    <ul class="sub-menu" style="{{ request()->routeIs('help.*') ? 'display: block;' : '' }}">
                        <li><a href="{{ route('help.index') }}" class="{{ request()->routeIs('help.index') ? 'active' : '' }}"><i class="fas fa-question-circle"></i> <span>Yardım Merkezi</span></a></li>
                        <li><a href="{{ route('help.guide') }}" class="{{ request()->routeIs('help.guide') ? 'active' : '' }}"><i class="fas fa-book"></i> <span>Kılavuzlar & Dokümanlar</span></a></li>
                        <li><a href="{{ route('help.videos') }}" class="{{ request()->routeIs('help.videos') ? 'active' : '' }}"><i class="fas fa-video"></i> <span>Video Eğitimler</span></a></li>
                        <li><a href="{{ route('help.faq') }}" class="{{ request()->routeIs('help.faq') ? 'active' : '' }}"><i class="fas fa-comments"></i> <span>Sıkça Sorulan Sorular</span></a></li>
                        <li><a href="{{ route('help.support') }}" class="{{ request()->routeIs('help.support') ? 'active' : '' }}"><i class="fas fa-headset"></i> <span>Destek Talebi / İletişim</span></a></li>
                    </ul>
                </li>

            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->

<!-- Manual menu handler for nested submenus -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Menü açma/kapama tamamen kaldırıldı; tüm alt menüler %100 açık ve sabittir.
    document.querySelectorAll('#side-menu .sub-menu').forEach(function(submenu) {
        submenu.style.setProperty('display', 'block', 'important');
    });
    document.querySelectorAll('#side-menu .has-arrow').forEach(function(arrow) {
        arrow.addEventListener('click', function(e) {
            e.preventDefault();
        });
    });
});
</script>
