<!-- Start topbar - Pergel Multi-Theme -->
<style>
/* ── Topbar temel yapı — CSS Custom Properties üzerinden tema alır ── */
#page-topbar {
    background: var(--pt-topbar-bg, #0f172a) !important;
    border-bottom: 1px solid var(--pt-topbar-border, rgba(255,255,255,0.1)) !important;
    box-shadow: var(--pt-topbar-shadow, 0 4px 20px rgba(0,0,0,0.3)) !important;
    min-height: 64px !important;
    transition: background 0.3s ease, border-color 0.3s ease !important;
}

.navbar-header {
    background: var(--pt-topbar-bg, #0f172a) !important;
    display: flex !important;
    align-items: center !important;
    min-height: 64px !important;
    transition: background 0.3s ease !important;
}

/* ── Logo Kutusu ── */
.navbar-logo-box {
    background: var(--pt-logo-box-bg, #0f172a) !important;
    padding: 8px 14px !important;
    display: flex !important;
    align-items: center !important;
    gap: 10px !important;
    border-right: 1px solid var(--pt-logo-box-border, rgba(255,255,255,0.08)) !important;
    min-height: 64px !important;
    transition: background 0.3s ease !important;
}

.sidebar-btn,
#sidebar-btn {
    display: none !important;
}

.pergel-brand-logo {
    display: inline-flex !important;
    align-items: center !important;
    gap: 10px;
    text-decoration: none !important;
    transition: transform 0.2s ease;
}
.pergel-brand-logo:hover { transform: translateY(-1px); }

.pergel-logo-emblem {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    background: var(--pt-logo-gradient, linear-gradient(135deg, #2563eb 0%, #1d4ed8 50%, #d97706 100%)) !important;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 0 16px rgba(var(--pt-primary-rgb, 59,130,246), 0.35), inset 0 1px 1px rgba(255,255,255,0.3);
    position: relative;
    flex-shrink: 0;
    transition: background 0.3s ease, box-shadow 0.3s ease;
}

.pergel-logo-emblem i {
    color: #ffffff !important;
    font-size: 20px;
    filter: drop-shadow(0 2px 4px rgba(0,0,0,0.3));
}

.pergel-logo-text-box {
    display: flex;
    flex-direction: column;
    text-align: left;
    line-height: 1;
}

.pergel-logo-title {
    font-size: 20px !important;
    font-weight: 900 !important;
    letter-spacing: 2px !important;
    text-transform: uppercase !important;
    color: var(--pt-logo-text-color, #ffffff) !important;
    font-family: var(--pt-font-heading, 'Inter', sans-serif);
    display: flex;
    align-items: center;
    gap: 6px;
    transition: color 0.3s ease;
}

.pergel-logo-subtitle {
    font-size: 8px;
    font-weight: 700;
    letter-spacing: 1.5px;
    color: var(--pt-primary, #f59e0b) !important;
    text-transform: uppercase;
    margin-top: 3px;
    opacity: 0.95;
    transition: color 0.3s ease;
}

@media (max-width: 768px) {
    .navbar-logo-box {
        padding: 6px 10px !important;
        gap: 8px !important;
        border-right: none !important;
    }
    .pergel-logo-emblem { width: 32px; height: 32px; border-radius: 8px; }
    .pergel-logo-emblem i { font-size: 17px; }
    .pergel-logo-title { font-size: 16px !important; letter-spacing: 1.2px !important; }
    .pergel-logo-subtitle { display: none !important; }
}

/* ── Sidebar toggle button ── */
.sidebar-btn {
    color: var(--pt-topbar-text, #ffffff) !important;
    background: transparent !important;
    border: none !important;
    font-size: 20px !important;
}
.sidebar-btn:hover {
    background: var(--pt-topbar-icon-hover-bg, rgba(255,255,255,0.1)) !important;
    color: var(--pt-accent, #3b82f6) !important;
}

/* ── Radio Equalizer Indicator ── */
.topbar-eq-bars {
    display: inline-flex;
    align-items: flex-end;
    gap: 2px;
    height: 12px;
    margin-left: 3px;
    vertical-align: middle;
}
.topbar-eq-bars span {
    width: 2px;
    height: 3px;
    background: var(--pt-primary, #f59e0b);
    border-radius: 1px;
    transition: height 0.2s ease;
}
.topbar-eq-bars.playing span:nth-child(1) { animation: eqBounce 0.8s ease infinite alternate; }
.topbar-eq-bars.playing span:nth-child(2) { animation: eqBounce 0.6s ease 0.2s infinite alternate; }
.topbar-eq-bars.playing span:nth-child(3) { animation: eqBounce 0.7s ease 0.4s infinite alternate; }
@keyframes eqBounce { 0% { height: 3px; } 100% { height: 12px; } }

/* ── Topbar butonlar ── */
.navbar-header .btn-primary {
    background: var(--pt-primary, #f59e0b) !important;
    border-color: var(--pt-primary, #f59e0b) !important;
    color: var(--pt-btn-primary-text, #ffffff) !important;
    font-weight: 500 !important;
    border-radius: var(--pt-btn-radius, 8px) !important;
}
.navbar-header .btn-primary:hover {
    background: var(--pt-primary-hover, #d97706) !important;
    border-color: var(--pt-primary-hover, #d97706) !important;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(var(--pt-primary-rgb, 245,158,11), 0.3) !important;
}

/* ── Dropdown ── */
[data-theme] .dropdown-menu {
    background: var(--pt-dropdown-bg, #1e293b) !important;
    border: 1px solid var(--pt-dropdown-border, rgba(255,255,255,0.1)) !important;
    box-shadow: var(--pt-dropdown-shadow, 0 8px 24px rgba(0,0,0,0.4)) !important;
    border-radius: var(--pt-card-radius, 8px) !important;
}

[data-theme] .dropdown-item {
    color: var(--pt-dropdown-item-color, #cbd5e1) !important;
    font-weight: 500 !important;
    padding: 8px 16px !important;
    border-radius: calc(var(--pt-card-radius, 8px) - 2px) !important;
    margin: 2px 4px !important;
    transition: all 0.2s ease !important;
    font-family: var(--pt-font-sans, 'Inter', sans-serif) !important;
}

[data-theme] .dropdown-item:hover,
[data-theme] .dropdown-item:focus {
    background: var(--pt-dropdown-item-hover-bg, rgba(51,65,85,0.6)) !important;
    color: var(--pt-dropdown-item-hover-color, #3b82f6) !important;
    transform: translateX(3px);
}

[data-theme] .dropdown-item i {
    color: var(--pt-text-muted, #94a3b8) !important;
    margin-right: 8px !important;
    transition: color 0.2s;
}
[data-theme] .dropdown-item:hover i,
[data-theme] .dropdown-item:focus i {
    color: var(--pt-dropdown-item-hover-color, #3b82f6) !important;
}
[data-theme] .dropdown-divider {
    border-top-color: var(--pt-dropdown-border, rgba(255,255,255,0.1)) !important;
}

/* ── Search Results ── */
[data-theme] .search-results-dropdown {
    background: var(--pt-dropdown-bg, #1e293b) !important;
    border: 1px solid var(--pt-dropdown-border) !important;
    border-radius: var(--pt-card-radius, 8px) !important;
    box-shadow: var(--pt-dropdown-shadow) !important;
}
[data-theme] .search-item {
    color: var(--pt-dropdown-item-color) !important;
    border-bottom-color: var(--pt-card-border) !important;
}
[data-theme] .search-item:hover,
[data-theme] .search-item.focused {
    background: var(--pt-dropdown-item-hover-bg) !important;
    color: var(--pt-dropdown-item-hover-color) !important;
}
[data-theme] .search-item-title {
    color: var(--pt-heading-color) !important;
}
[data-theme] .search-item-subtitle {
    color: var(--pt-text-muted) !important;
}

/* ── Notification Items ── */
[data-theme] .notification-item {
    border-bottom-color: var(--pt-card-border) !important;
}
[data-theme] .notification-item:hover {
    background: var(--pt-dropdown-item-hover-bg) !important;
}
[data-theme] .notification-item h6 {
    color: var(--pt-heading-color) !important;
}

/* ── Grid Nav (App Menu) ── */
[data-theme] .grid-nav-item {
    background: var(--pt-card-bg) !important;
    color: var(--pt-body-color) !important;
    border-radius: var(--pt-card-radius, 8px) !important;
}
[data-theme] .grid-nav-item:hover {
    background: var(--pt-sidebar-hover-bg) !important;
    color: var(--pt-sidebar-accent) !important;
}
[data-theme] .app-menu-label {
    color: var(--pt-heading-color) !important;
}

/* ── Search box ── */
.app-search .form-control {
    background: rgba(var(--pt-primary-rgb, 255,255,255), 0.12) !important;
    border: 1px solid rgba(255,255,255,0.18) !important;
    color: var(--pt-topbar-text, #ffffff) !important;
    border-radius: 20px !important;
    padding: 8px 40px 8px 16px !important;
    width: 250px !important;
    transition: all 0.3s ease !important;
    font-family: var(--pt-font-sans, 'Inter', sans-serif) !important;
}
.app-search .form-control:focus {
    background: var(--pt-input-bg, rgba(255,255,255,0.9)) !important;
    border-color: var(--pt-input-focus-border, #3b82f6) !important;
    color: var(--pt-body-color, #334155) !important;
    box-shadow: 0 0 0 2px var(--pt-input-focus-shadow, rgba(59,130,246,0.2)) !important;
    width: 300px !important;
}
.app-search .form-control::placeholder {
    color: rgba(255,255,255,0.65) !important;
    text-align: center !important;
}
.app-search .form-control:focus::placeholder {
    color: var(--pt-input-placeholder) !important;
    text-align: center !important;
}
.app-search .form-control { text-align: center !important; }
.app-search .form-control:focus { text-align: left !important; }
.app-search .search-icon {
    position: absolute !important;
    right: 12px !important;
    top: 50% !important;
    transform: translateY(-50%) !important;
    color: rgba(255,255,255,0.65) !important;
    pointer-events: none !important;
    z-index: 5 !important;
}
.app-search .form-control:focus + .search-icon { color: var(--pt-text-muted) !important; }
.app-search .search-spinner { color: var(--pt-primary) !important; }

/* ── Search Results Dropdown light ── */
.search-results-dropdown {
    position: absolute !important;
    top: 100% !important;
    left: 0 !important;
    right: 0 !important;
    background: var(--pt-dropdown-bg, #ffffff) !important;
    border: 1px solid var(--pt-dropdown-border) !important;
    border-radius: var(--pt-card-radius, 8px) !important;
    box-shadow: var(--pt-dropdown-shadow) !important;
    z-index: 1000 !important;
    margin-top: 4px !important;
    max-height: 400px !important;
    overflow-y: auto !important;
}

.search-results-content { padding: 8px 0 !important; }

.search-category-title {
    padding: 8px 16px !important;
    font-size: 12px !important;
    font-weight: 600 !important;
    color: var(--pt-text-muted) !important;
    text-transform: uppercase !important;
    letter-spacing: 0.5px !important;
    border-bottom: 1px solid var(--pt-card-border) !important;
    margin-bottom: 4px !important;
    font-family: var(--pt-font-sans) !important;
}

.search-item {
    padding: 8px 16px !important;
    display: flex !important;
    align-items: center !important;
    text-decoration: none !important;
    color: var(--pt-dropdown-item-color, #334155) !important;
    transition: all 0.2s ease !important;
    border-bottom: 1px solid var(--pt-card-border) !important;
}
.search-item:hover {
    background: var(--pt-dropdown-item-hover-bg) !important;
    color: var(--pt-dropdown-item-hover-color) !important;
    text-decoration: none !important;
}
.search-item-icon {
    width: 32px !important; height: 32px !important;
    border-radius: var(--pt-btn-radius, 6px) !important;
    display: flex !important; align-items: center !important;
    justify-content: center !important;
    margin-right: 12px !important;
    font-size: 14px !important;
    flex-shrink: 0 !important;
}
.search-item-icon.projects { background: var(--pt-primary-subtle) !important; color: var(--pt-primary) !important; }
.search-item-icon.tasks { background: rgba(16,185,129,0.12) !important; color: var(--pt-success, #10b981) !important; }
.search-item-icon.users { background: var(--pt-primary-subtle) !important; color: var(--pt-accent, #f59e0b) !important; }
.search-item-icon.documents { background: rgba(236,72,153,0.1) !important; color: #ec4899 !important; }

.search-item-content { flex: 1 !important; min-width: 0 !important; }
.search-item-title {
    font-weight: 500 !important;
    color: var(--pt-heading-color, #334155) !important;
    margin-bottom: 2px !important;
    white-space: nowrap !important;
    overflow: hidden !important;
    text-overflow: ellipsis !important;
}
.search-item-subtitle {
    font-size: 12px !important;
    color: var(--pt-text-muted) !important;
    white-space: nowrap !important;
    overflow: hidden !important;
    text-overflow: ellipsis !important;
}
.search-item:hover .search-item-title,
.search-item.focused .search-item-title { color: var(--pt-dropdown-item-hover-color) !important; }
.search-item.focused {
    background: var(--pt-dropdown-item-hover-bg) !important;
    color: var(--pt-dropdown-item-hover-color) !important;
    outline: 2px solid var(--pt-primary) !important;
    outline-offset: -2px !important;
}
.search-no-results { padding: 24px 16px !important; text-align: center !important; color: var(--pt-text-muted) !important; }
.search-no-results i { font-size: 32px !important; color: var(--pt-text-subtle) !important; margin-bottom: 8px !important; }
.search-loading { padding: 16px !important; text-align: center !important; color: var(--pt-text-muted) !important; }

/* ── Header item icons ── */
.top-icon {
    color: var(--pt-topbar-text, rgba(255,255,255,0.9)) !important;
    background: transparent !important;
    border: none !important;
    padding: 8px !important;
    border-radius: var(--pt-btn-radius, 8px) !important;
    transition: all 0.2s ease !important;
    position: relative !important;
}
.top-icon:hover {
    background: var(--pt-topbar-icon-hover-bg, rgba(255,255,255,0.1)) !important;
    color: var(--pt-accent, #3b82f6) !important;
    transform: scale(1.05);
}

/* Notification marker */
.btn-marker .marker {
    position: absolute !important;
    top: 2px !important; right: 2px !important;
    width: 8px !important; height: 8px !important;
    border-radius: 50% !important;
    background: var(--pt-danger, #dc2626) !important;
    border: 2px solid var(--pt-topbar-bg, #334155) !important;
}

/* Notification dropdown size */
.dropdown-menu-lg { min-width: 320px !important; }

.notification-item {
    padding: 12px 16px !important;
    border-bottom: 1px solid var(--pt-card-border) !important;
    transition: all 0.2s ease !important;
}
.notification-item:hover {
    background: var(--pt-dropdown-item-hover-bg) !important;
    transform: translateX(3px);
}
.notification-item:last-child { border-bottom: none !important; }
.notification-item .avatar {
    background: var(--pt-card-header-bg) !important;
    color: var(--pt-body-color) !important;
}

/* header-item & profile */
.header-item {
    color: var(--pt-topbar-text, #ffffff) !important;
    transition: all 0.2s ease !important;
}
.header-item:hover {
    color: var(--pt-accent, #3b82f6) !important;
    transform: scale(1.05);
}
.header-profile-user {
    border: 2px solid rgba(255,255,255,0.2) !important;
    transition: all 0.2s ease !important;
}
.header-profile-user:hover {
    border-color: var(--pt-primary, #3b82f6) !important;
    transform: scale(1.05);
}

/* Sağlık temasında topbar açık → text renkleri koyu olsun */
[data-theme="health"][data-dark="false"] .header-item,
[data-theme="health"]:not([data-dark="true"]) .header-item {
    color: rgba(255,255,255,0.95) !important;
}
[data-theme="health"][data-dark="false"] .top-icon,
[data-theme="health"]:not([data-dark="true"]) .top-icon {
    color: rgba(255,255,255,0.95) !important;
}
</style>
<style>
    /* --- YENİ EKLENEN SIDEBAR DARALTMA STİLLERİ --- */

    /* Transitions for smooth collapse/expand */
    .sidebar-left {
        transition: width 0.3s ease-in-out, transform 0.3s ease-in-out;
    }
    .main-content {
        transition: margin-left 0.3s ease-in-out;
    }
    .sidebar-left .left-menu li > a > span,
    .sidebar-left .left-menu .menu-title,
    .sidebar-left .left-menu .has-arrow::after {
        transition: opacity 0.2s ease-out, width 0.2s ease-out;
    }

    /* Collapsed state styles for DESKTOP */
    @media (min-width: 991.99px) {
        body.sidebar-collapsed .sidebar-left {
            width: 80px; /* Mini sidebar width */
            transform: translateX(0);
        }

        body.sidebar-collapsed .main-content {
            margin-left: 80px; /* Adjust content margin */
        }

        /* Hide text spans, menu titles, and arrows */
        body.sidebar-collapsed .sidebar-left .left-menu li > a > span,
        body.sidebar-collapsed .sidebar-left .ai-assistant-text,
        body.sidebar-collapsed .sidebar-left .left-menu .menu-title,
        body.sidebar-collapsed .sidebar-left .left-menu .has-arrow::after {
            opacity: 0;
            visibility: hidden;
            width: 0;
            white-space: nowrap;
        }

        /* Center the icon */
        body.sidebar-collapsed .sidebar-left .left-menu li > a {
            justify-content: center;
        }
        body.sidebar-collapsed .sidebar-left .left-menu li > a i {
            margin-right: 0;
            font-size: 1.25rem; /* Make icons slightly larger */
        }

        /* Hide all sub-menus when collapsed */
        body.sidebar-collapsed .sidebar-left .sub-menu {
            display: none !important;
        }

         /* Remove hover transform effect when collapsed */
        body.sidebar-collapsed .sidebar-left .left-menu li > a:hover {
            transform: none;
        }
    }
</style>
<header id="page-topbar">
    <div class="navbar-header">

        <!-- Logo -->

        <!-- Start Navbar-Brand -->
        <div class="navbar-logo-box">
            <button type="button" class="sidebar-btn me-2" id="sidebar-btn" title="Menüyü Aç/Kapat">
                <i class="mdi mdi-menu"></i>
            </button>
            <a href="{{ url('/') }}" class="pergel-brand-logo">
                <div class="pergel-logo-emblem">
                    <i class="ri-compass-3-fill"></i>
                </div>
                <div class="pergel-logo-text-box">
                    <span class="pergel-logo-title">
                        PERGEL
                        @if(function_exists('pergel_is_pro') && pergel_is_pro())
                            <span class="badge bg-warning text-dark rounded-pill px-2 py-1 align-middle fw-bold" style="font-size:9.5px; letter-spacing:0.5px; box-shadow: 0 0 10px rgba(245,158,11,0.5);">
                                <i class="ri-vip-crown-2-fill me-1"></i>PRO
                            </span>
                        @else
                            <a href="{{ route('admin.license.upgrade') }}" class="badge bg-secondary bg-opacity-25 text-white-50 text-decoration-none rounded-pill px-2 py-1 align-middle" style="font-size:9px; letter-spacing:0.5px;" title="Topluluk Sürümü — Pro'ya Yükselt">
                                Community
                            </a>
                        @endif
                    </span>
                    <span class="pergel-logo-subtitle">ŞANTİYE & GAYRİMENKUL</span>
                </div>
            </a>
        </div>
        <!-- End navbar brand -->

        <!-- Start menu -->
        <div class="d-flex justify-content-between menu-sm px-3 ms-auto">
            <div class="d-flex align-items-center gap-2">
                <div class="dropdown d-none d-lg-block">
                    <button type="button" class="btn btn-primary btn-sm fs-14 d-inline" data-bs-toggle="dropdown">
                        Uygulamalar
                        <i class="mdi mdi-chevron-down align-middle"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-start dropdown-menu-animated app-dropdown-menu">
                        <a href="{{ route('portfolio.management.index') }}" class="dropdown-item app-menu-item d-flex align-items-center gap-2">
                            <span class="app-menu-icon"><i class="ri-box-3-line"></i></span>
                            <span class="app-menu-label flex-grow-1">Envanter Yöneticisi</span>
                            <span class="badge badge-warning rounded-pill ms-1">20</span>
                        </a>
                        <div class="dropdown-submenu">
                            <a href="{{ route('projects.index') }}" class="dropdown-item app-menu-item d-flex align-items-center gap-2">
                                <span class="app-menu-icon"><i class="ri-git-merge-line"></i></span>
                                <span class="app-menu-label flex-grow-1">Proje Yöneticisi</span>
                                <span class="app-menu-arrow"><i class="mdi mdi-chevron-right align-middle"></i></span>
                            </a>
                            <div class="dropdown-submenu-menu dropdown-submenu-end">
                                <a href="{{ route('projects.index') }}" class="dropdown-item"><i class="dropdown-bullet"></i> <span class="dropdown-content">Tüm Projeler (Şantiyeler)</span></a>
                                <a href="{{ route('projects.create') }}" class="dropdown-item"><i class="dropdown-bullet"></i> <span class="dropdown-content">Yeni Proje Ekle</span></a>
                                <a href="{{ route('site_diaries.index') }}" class="dropdown-item"><i class="dropdown-bullet"></i> <span class="dropdown-content">Saha Günlükleri</span></a>
                                <a href="{{ route('projects.communication.dashboard') }}" class="dropdown-item"><i class="dropdown-bullet"></i> <span class="dropdown-content">Proje İletişimi</span></a>
                            </div>
                        </div>
                        <div class="dropdown-submenu">
                            <a href="{{ route('apps-kanban') }}" class="dropdown-item app-menu-item d-flex align-items-center gap-2">
                                <span class="app-menu-icon"><i class="ri-task-line"></i></span>
                                <span class="app-menu-label flex-grow-1">Görev Yöneticisi</span>
                                <span class="app-menu-arrow"><i class="mdi mdi-chevron-right align-middle"></i></span>
                            </a>
                            <div class="dropdown-submenu-menu dropdown-submenu-end">
                                <a href="{{ route('apps-kanban') }}" class="dropdown-item"><i class="dropdown-bullet"></i> <span class="dropdown-content">Kanban Görev Panosu</span></a>
                                <a href="{{ route('projects.planning') }}" class="dropdown-item"><i class="dropdown-bullet"></i> <span class="dropdown-content">Planlama Görevleri</span></a>
                                <a href="{{ route('index', ['any' => 'field/operations']) }}" class="dropdown-item"><i class="dropdown-bullet"></i> <span class="dropdown-content">Saha Operasyonları</span></a>
                            </div>
                        </div>
                        <a href="{{ route('portfolio.financials.index') }}" class="dropdown-item app-menu-item d-flex align-items-center gap-2">
                            <span class="app-menu-icon"><i class="ri-money-dollar-circle-line"></i></span>
                            <span class="app-menu-label flex-grow-1">Fatura & Finans</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('profile.show') }}" class="dropdown-item app-menu-item d-flex align-items-center gap-2">
                            <span class="app-menu-icon"><i class="ri-user-settings-line"></i></span>
                            <span class="app-menu-label flex-grow-1">Hesabım & Profil</span>
                        </a>
<style>
.app-dropdown-menu {
    min-width: 260px;
    padding: 8px 0;
    background: var(--pt-dropdown-bg, #ffffff) !important;
    border: 1px solid var(--pt-dropdown-border, #cbd5e1) !important;
    box-shadow: var(--pt-dropdown-shadow, 0 10px 25px rgba(15, 23, 42, 0.15)) !important;
    border-radius: var(--pt-card-radius, 10px) !important;
}
.app-menu-item {
    padding: 8px 16px !important;
    border-radius: 8px !important;
    transition: background 0.18s, color 0.18s;
    font-size: 15px;
    font-weight: 500;
    color: var(--pt-dropdown-item-color, #334155) !important;
}
.app-menu-item:hover {
    background: var(--pt-dropdown-item-hover-bg, #F3F4F6) !important;
    color: var(--pt-dropdown-item-hover-color, var(--pt-primary, #2563EB)) !important;
}
.app-menu-icon {
    font-size: 19px;
    color: var(--pt-sidebar-icon-color, #64748b);
    width: 22px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.app-menu-label {
    color: var(--pt-heading-color, #1e293b);
    font-size: 15px;
    font-weight: 500;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.app-menu-arrow {
    color: var(--pt-text-muted, #94A3B8);
    font-size: 18px;
    margin-left: 4px;
}
.badge.badge-warning {
    background: var(--pt-warning, #F59E0B) !important;
    color: #fff !important;
    font-size: 13px;
    font-weight: 600;
    padding: 3px 10px;
    vertical-align: middle;
    border-radius: var(--pt-badge-radius, 6px);
}
.dropdown-divider {
    margin: 8px 0 !important;
    border-top: 1px solid var(--pt-dropdown-border, rgba(0,0,0,0.08)) !important;
}

/* Dropdown Submenu Hover Support */
.dropdown-submenu {
    position: relative;
}
.dropdown-submenu .dropdown-submenu-menu {
    top: 0;
    left: 100%;
    margin-top: -6px;
    display: none;
    position: absolute;
    min-width: 220px;
    background: var(--pt-dropdown-bg, #ffffff) !important;
    border: 1px solid var(--pt-dropdown-border, #cbd5e1) !important;
    border-radius: var(--pt-card-radius, 10px) !important;
    box-shadow: var(--pt-dropdown-shadow, 0 10px 25px rgba(15, 23, 42, 0.15)) !important;
    padding: 6px 0;
    z-index: 1050;
}
.dropdown-submenu:hover .dropdown-submenu-menu {
    display: block;
}
</style>
                    </div>
                </div>

                <div class="dropdown d-none d-lg-block">
                    <button type="button" class="btn btn-primary btn-sm fs-14" data-bs-toggle="dropdown"
                        aria-haspopup="false" aria-expanded="false">
                        Özellikler
                        <i class="mdi mdi-chevron-down align-middle"></i>
                    </button>
                    <div
                        class="dropdown-menu dropdown-menu-start dropdown-menu-lg-widest dropdown-menu-widest dropdown-menu-animated bg-primary-subtle overflow-hidden">
                        <div class="dropdown-row justify-content-center">
                            <div class="p-2 menu-image">
                                <img src="{{ URL::asset('build/images/mega-menu.png') }}" alt="mega-menu image"
                                    class="img-fluid" style="height: 200px;">
                            </div>
                            <div class="dropdown-col">
                                <h2 class="">ŞantiyeConnect AI'ye Hoş Geldiniz!</h2>
                                <p class="text-muted mb-0">
                                    ŞantiyeConnect AI, şantiye operasyonlarınızı veri odaklı hale getirir: otomatik risk tespiti ile gecikme ve maliyet sapmalarını önleyin, sensör ve saha verilerinden öngörüler üreten öngörücü bakım önerileri alın, otomatikleştirilmiş günlük ve haftalık raporlarla paydaş iletişimini hızlandırın. Ayrıca doküman işleme ile sözleşme ve fatura verilerini hızla çıkarıp sınıflandırarak idari yükünüzü azaltır.
                                    <br><strong>Hemen keşfedin:</strong> Proje sağlığı, maliyet tahminleri, süreç optimizasyonu ve kalite kontrol tavsiyeleri tek bir panelde.
                                </p>
                                <div class="mt-3">
                                    <a href="{{ route('ai-module.index') }}" class="btn btn-dark btn-wider">Yapay Zeka Bölümü</a>
                                </div>
                            </div>
                            <div class="dropdown-col border-start border-primary border-opacity-50">
                                <h4 class="dropdown-header">Özellikler</h4>
                                <div class="features-grid">
                                    <a href="{{ route('root') }}" class="feature-box">
                                        <div class="feature-icon"><i class="fas fa-tachometer-alt" style="color:#3b82f6;"></i></div>
                                        <div class="feature-label">Kontrol Paneli</div>
                                    </a>
                                    <a href="{{ route('apps-kanban') }}" class="feature-box">
                                        <div class="feature-icon"><i class="fas fa-tasks" style="color:#10b981;"></i></div>
                                        <div class="feature-label">Yapılacaklar</div>
                                    </a>
                                    <a href="{{ route('help.index') }}" class="feature-box">
                                        <div class="feature-icon"><i class="fas fa-life-ring" style="color:#f59e0b;"></i></div>
                                        <div class="feature-label">Yardım Merkezi</div>
                                    </a>
                                    <a href="{{ route('gallery.index') }}" class="feature-box">
                                        <div class="feature-icon"><i class="fas fa-images" style="color:#ec4899;"></i></div>
                                        <div class="feature-label">Galeri</div>
                                    </a>
                                    <a href="{{ route('apps-kanban') }}" class="feature-box">
                                        <div class="feature-icon"><i class="fas fa-columns" style="color:#8b5cf6;"></i></div>
                                        <div class="feature-label">Scrum Panosu</div>
                                    </a>
                                    <a href="{{ route('projects.documents.all') }}" class="feature-box">
                                        <div class="feature-icon"><i class="fas fa-folder-open" style="color:#06b6d4;"></i></div>
                                        <div class="feature-label">Dokümanlar</div>
                                    </a>
                                    <a href="{{ route('ai-module.risk-analysis') }}" class="feature-box">
                                        <div class="feature-icon"><i class="fas fa-exclamation-triangle" style="color:#ef4444;"></i></div>
                                        <div class="feature-label">Risk Analizi</div>
                                    </a>
                                    <a href="{{ route('ai-module.document-processing') }}" class="feature-box">
                                        <div class="feature-icon"><i class="fas fa-file-invoice" style="color:#6366f1;"></i></div>
                                        <div class="feature-label">Doküman İşleme</div>
                                    </a>
                                    <a href="{{ route('ai-module.compliance-report') }}" class="feature-box">
                                        <div class="feature-icon"><i class="fas fa-shield-alt" style="color:#14b8a6;"></i></div>
                                        <div class="feature-label">Uyumluluk Raporu</div>
                                    </a>
                                </div>
<style>
.features-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 18px 10px;
    padding: 8px 0 8px 0;
    justify-items: center;
    align-items: center;
}
.feature-box {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-decoration: none;
    color: var(--pt-body-color, #475569) !important;
    transition: color 0.18s, background 0.18s, box-shadow 0.18s, transform 0.18s;
    border-radius: var(--pt-card-radius, 10px);
    padding: 6px 4px;
    min-width: 0;
    width: 100%;
}
.feature-box:hover {
    color: var(--pt-primary, #2563eb) !important;
    background: var(--pt-dropdown-item-hover-bg, #f1f5f9) !important;
    box-shadow: 0 2px 8px rgba(var(--pt-primary-rgb, 59,130,246), 0.15);
    transform: translateY(-2px);
}
.feature-icon {
    font-size: 26px;
    margin-bottom: 4px;
    transition: transform 0.18s;
}
.feature-box:hover .feature-icon {
    transform: scale(1.1);
}
.feature-label {
    font-size: 12.5px;
    font-weight: 600;
    text-align: center;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 125px;
    color: var(--pt-heading-color, inherit);
}
@media (max-width: 600px) {
    .features-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 14px 6px;
    }
}
</style>
                            </div>
                            <div class="dropdown-col border-start border-primary border-opacity-50">
                                <h4 class="dropdown-header">Araçlar</h4>
                                <div class="d-flex flex-column gap-1 px-2 py-2">
                                    <a href="{{ route('components.index') }}" class="dropdown-item d-flex align-items-center gap-3 py-2 px-2 rounded-2 custom-tool-link">
                                        <span class="tool-icon d-flex align-items-center justify-content-center rounded-circle me-2" style="width:32px;height:32px;background:#e0f2fe;"><i class="fas fa-cubes" style="font-size:14px;color:#0284c7;"></i></span>
                                        <span class="dropdown-content flex-grow-1" style="font-size:14px;font-weight:600;">Bileşenler</span>
                                    </a>
                                    <a href="{{ route('forms.wizard') }}" class="dropdown-item d-flex align-items-center gap-3 py-2 px-2 rounded-2 custom-tool-link">
                                        <span class="tool-icon d-flex align-items-center justify-content-center rounded-circle me-2" style="width:32px;height:32px;background:#dcfce7;"><i class="fas fa-magic" style="font-size:14px;color:#16a34a;"></i></span>
                                        <span class="dropdown-content flex-grow-1" style="font-size:14px;font-weight:600;">Form Sihirbazı</span>
                                    </a>
                                    <a href="{{ route('help.articles') }}" class="dropdown-item d-flex align-items-center gap-3 py-2 px-2 rounded-2 custom-tool-link">
                                        <span class="tool-icon d-flex align-items-center justify-content-center rounded-circle me-2" style="width:32px;height:32px;background:#fef3c7;"><i class="fas fa-book-open" style="font-size:14px;color:#d97706;"></i></span>
                                        <span class="dropdown-content flex-grow-1" style="font-size:14px;font-weight:600;">Dokümantasyon</span>
                                    </a>
                                    <a href="{{ route('help.faq') }}" class="dropdown-item d-flex align-items-center gap-3 py-2 px-2 rounded-2 custom-tool-link">
                                        <span class="tool-icon d-flex align-items-center justify-content-center rounded-circle me-2" style="width:32px;height:32px;background:#f3e8ff;"><i class="fas fa-lightbulb" style="font-size:14px;color:#9333ea;"></i></span>
                                        <span class="dropdown-content flex-grow-1" style="font-size:14px;font-weight:600;">Bilgi Bankası</span>
                                    </a>
                                    <a href="{{ route('portfolio.management.index') }}" class="dropdown-item d-flex align-items-center gap-3 py-2 px-2 rounded-2 custom-tool-link">
                                        <span class="tool-icon d-flex align-items-center justify-content-center rounded-circle me-2" style="width:32px;height:32px;background:#f1f5f9;"><i class="fas fa-boxes" style="font-size:14px;color:#475569;"></i></span>
                                        <span class="dropdown-content flex-grow-1" style="font-size:14px;font-weight:600;">Inventory Manager</span>
                                    </a>
                                </div>
<style>
.custom-tool-link {
    transition: background 0.18s, color 0.18s, box-shadow 0.18s;
    color: var(--pt-body-color, #334155) !important;
    border-radius: var(--pt-btn-radius, 6px);
}
.custom-tool-link:hover {
    background: var(--pt-dropdown-item-hover-bg, #E0E7EF) !important;
    color: var(--pt-primary, #2563EB) !important;
    box-shadow: 0 2px 8px rgba(var(--pt-primary-rgb, 59,130,246), 0.1);
}
.custom-tool-link .tool-icon {
    transition: background 0.18s, color 0.18s;
}
.custom-tool-link:hover .tool-icon {
    background: var(--pt-primary-subtle, #DBEAFE) !important;
}
.custom-tool-link:hover .dropdown-content {
    color: var(--pt-primary, #2563EB) !important;
}
</style>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2">
                <!--Start App Search-->
                <form class="app-search d-none d-lg-block" id="global-search-form">
                    <div class="position-relative">
                        <input type="text" class="form-control" id="global-search-input" placeholder="Ara..." autocomplete="off">
                        <span class="fab fa-sistrix fs-17 align-middle search-icon"></span>
                        <div class="spinner-border spinner-border-sm search-spinner d-none" role="status" style="position: absolute; right: 40px; top: 50%; transform: translateY(-50%); width: 20px; height: 20px;">
                            <span class="visually-hidden">Aranıyor...</span>
                        </div>
                    </div>
                    <!-- Search Results Dropdown -->
                    <div class="search-results-dropdown" id="search-results" style="display: none;">
                        <div class="search-results-content">
                            <div class="search-category">
                                <div class="search-category-title">Arama Sonuçları</div>
                                <div class="search-items" id="search-items-container">
                                    <!-- Dynamic search results will be inserted here -->
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <!--End App Search-->

                <!-- Start Theme Switcher Toggle -->
                <div class="d-inline-block me-1">
                    <button type="button" class="btn btn-sm top-icon position-relative" id="topbar-theme-switcher-btn" title="Tema Seçici (5 Sektör Teması)">
                        <i class="mdi mdi-palette align-middle fs-18" style="color: var(--pt-primary, #f59e0b);"></i>
                    </button>
                </div>
                <!-- End Theme Switcher Toggle -->

                <!-- Start Light/Dark Mode Toggle -->
                <div class="d-inline-block me-1">
                    <button type="button" class="btn btn-sm top-icon" id="topbar-light-dark-mode" title="Gece / Gündüz Modu">
                        <i class="mdi mdi-moon-waning-crescent align-middle fs-18"></i>
                    </button>
                </div>
                <!-- End Light/Dark Mode Toggle -->

                <!-- Start Focus Music & Radio Toggle -->
                <div class="d-inline-block me-1">
                    <button type="button" class="btn btn-sm top-icon position-relative" id="topbar-radio-toggle-btn" title="Odaklanma Müzikleri & Radyo">
                        <i class="ri-radio-2-line align-middle fs-18 text-warning"></i>
                        <span id="topbar-radio-eq-indicator" class="topbar-eq-bars">
                            <span></span>
                            <span></span>
                            <span></span>
                        </span>
                    </button>
                </div>
                <!-- End Focus Music & Radio Toggle -->

                <!-- Start Notification -->
                <div class="dropdown d-inline-block">
                    <button type="button" class="btn btn-sm top-icon position-relative" id="page-header-notifications-dropdown"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-bell align-middle"></i>
                        @if(isset($notificationStats) && $notificationStats['unread'] > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger notification-bell-badge">
                                {{ $notificationStats['unread'] > 99 ? '99+' : $notificationStats['unread'] }}
                                <span class="visually-hidden">okunmamış bildirim</span>
                            </span>
                        @endif
                    </button>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-md dropdown-menu-end p-0"
                        aria-labelledby="page-header-notifications-dropdown">
                        <div class="p-3 bg-info">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h6 class="text-white m-0"><i class="far fa-bell me-2"></i> Bildirimler </h6>
                                </div>
                                <div class="col-auto">
                                    <span class="badge bg-warning text-dark notification-count-badge">
                                        {{ isset($notificationStats) ? $notificationStats['unread'] : 0 }}{{ isset($notificationStats) && $notificationStats['unread'] > 9 ? '+' : '' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div data-simplebar style="max-height: 230px;">
                            @if(isset($userNotifications) && $userNotifications->count() > 0)
                                @foreach($userNotifications as $notification)
                                <a href="{{ $notification->action_url ?? '#' }}" class="text-reset notification-item" data-notification-id="{{ $notification->id }}">
                                    <div class="d-flex">
                                        <div class="avatar avatar-xs avatar-label-{{ $notification->priority == 'urgent' ? 'danger' : ($notification->priority == 'high' ? 'warning' : 'primary') }} me-3">
                                            <span class="rounded fs-16">
                                                @switch($notification->type)
                                                    @case('task_assigned')
                                                        <i class="mdi mdi-clipboard-check-outline"></i>
                                                        @break
                                                    @case('low_stock')
                                                        <i class="mdi mdi-alert-outline"></i>
                                                        @break
                                                    @case('system_update')
                                                        <i class="mdi mdi-update"></i>
                                                        @break
                                                    @case('task_completed')
                                                        <i class="mdi mdi-check-circle-outline"></i>
                                                        @break
                                                    @case('deadline_approaching')
                                                        <i class="mdi mdi-clock-alert-outline"></i>
                                                        @break
                                                    @case('user_mention')
                                                        <i class="mdi mdi-at"></i>
                                                        @break
                                                    @default
                                                        <i class="mdi mdi-bell-outline"></i>
                                                @endswitch
                                            </span>
                                        </div>
                                        <div class="flex-1">
                                            <h6 class="mb-1">{{ $notification->title }}</h6>
                                            <div class="fs-12 text-muted">
                                                <p class="mb-1">{{ Str::limit($notification->message, 60) }}</p>
                                                <p class="mb-0"><i class="mdi mdi-clock-outline"></i> {{ $notification->created_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                        @if($notification->is_actionable)
                                            <i class="mdi mdi-chevron-right align-middle ms-2"></i>
                                        @endif
                                    </div>
                                </a>
                                @endforeach
                            @else
                                <div class="text-center py-4">
                                    <div class="avatar-lg mx-auto mb-3">
                                        <div class="avatar-title bg-light rounded-circle">
                                            <i class="mdi mdi-bell-outline fa-2x text-muted"></i>
                                        </div>
                                    </div>
                                    <h6 class="text-muted">Henüz bildirim yok</h6>
                                    <p class="text-muted small">Yeni bildirimler burada görünecek</p>
                                </div>
                            @endif
                        </div>
                        <div class="p-2 border-top">
                            @if(isset($userNotifications) && $userNotifications->count() > 0)
                                <div class="d-flex gap-2">
                                    {{-- <a class="btn btn-sm btn-outline-primary flex-1" href="{{ route('notifications.index') }}"> --}}
                                        <i class="mdi mdi-eye me-1"></i>
                                        <span>Tümünü Görüntüle</span>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-primary" onclick="markAllAsRead()">
                                        <i class="mdi mdi-check-all me-1"></i>
                                        Okundu
                                    </button>
                                </div>
                            @else
                                <div class="d-grid">
                                    {{-- <a class="btn btn-sm btn-outline-primary" href="{{ route('notifications.index') }}"> --}}
                                        <i class="mdi mdi-cog me-1"></i>
                                        <span>Bildirim Ayarları</span>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <!-- End Notification -->

                <!-- Start Activities -->
                <div class="d-inline-block activities">
                    <button type="button" class="btn btn-sm top-icon position-relative" data-bs-toggle="offcanvas"
                        data-bs-target="#offcanvas-rightsidabar" title="Aktiviteler">
                        <i class="fas fa-chart-line align-middle"></i>
                        <!-- Activity indicator -->
                        <span class="position-absolute top-0 start-100 translate-middle p-1 bg-success border border-light rounded-circle">
                            <span class="visually-hidden">Neue Aktivitäten</span>
                        </span>
                    </button>
                </div>
                <!-- End Activities -->

                <!-- Start Profile -->
                @auth
                <div class="dropdown d-inline-block">
                    <button type="button" class="btn btn-sm top-icon px-2 py-0 d-flex align-items-center" id="page-header-user-dropdown"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        @php
                            $user = auth()->user();
                            // Try several possible avatar fields (common names in different projects)
                            $candidates = [
                                $user->avatar ?? null,
                                $user->avatar_url ?? null,
                                $user->profile_photo_url ?? null,
                                $user->profile_photo ?? null,
                                $user->photo ?? null,
                                $user->image ?? null,
                                optional($user->profile)->photo ?? null,
                            ];

                            $avatar = null;
                            foreach ($candidates as $c) {
                                if (!empty($c)) { $avatar = $c; break; }
                            }

                            // If avatar looks like a storage path, convert to URL
                            if ($avatar && !\Illuminate\Support\Str::startsWith($avatar, ['http://','https://'])) {
                                try {
                                    $avatar = \Illuminate\Support\Facades\Storage::url($avatar);
                                } catch (\Throwable $e) {
                                    // leave as-is
                                }
                            }

                            // Final fallback
                            $avatar = $avatar ?? \Illuminate\Support\Facades\URL::asset('build/images/users/avatar-6.png');
                        @endphp
                        <img class="rounded avatar-2xs p-0" src="{{ $avatar }}" alt="{{ $user->name ?? 'Kullanıcı' }}">
                    </button>
                    <div
                        class="dropdown-menu dropdown-menu-wide dropdown-menu-end dropdown-menu-animated overflow-hidden py-0">
                        <div class="card border-0">
                            <div class="card-header bg-primary">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="avatar avatar-label-light avatar-circle" style="background:#fff2; width:48px; height:48px; display:flex; align-items:center; justify-content:center;">
                                            <div class="avatar-display" style="font-size:22px;"><i class="fa fa-user-alt"></i></div>
                                        </div>
                                        <div>
                                            <h3 class="rich-list-title text-white mb-0">{{ auth()->user()->name ?? 'Kullanıcı' }}</h3>
                                            <span class="rich-list-subtitle text-white">{{ auth()->user()->email ?? '' }}</span>
                                        </div>
                                    </div>
                                    <span class="badge badge-label-light fs-6" style="background:#fff2; color:#fff; font-weight:600; font-size:18px;">6+</span>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="grid-nav">
                                    <div class="grid-nav-row">
                                        <a href="{{ route('apps.contact') }}" class="grid-nav-item">
                                            <div class="grid-nav-icon"><i class="far fa-address-card"></i></div>
                                            <span class="grid-nav-content">Profil</span>
                                        </a>
                                        <a href="{{ route('messages.index') }}" class="grid-nav-item">
                                            <div class="grid-nav-icon"><i class="far fa-comments"></i></div>
                                            <span class="grid-nav-content">Mesajlar</span>
                                            @if(isset($messageCount) && $messageCount > 0)
                                                <span class="badge bg-danger ms-2">{{ $messageCount > 99 ? '99+' : $messageCount }}</span>
                                            @endif
                                        </a>
                                        <a href="{{ route('activities.index') }}" class="grid-nav-item">
                                            <div class="grid-nav-icon"><i class="far fa-clone"></i></div>
                                            <span class="grid-nav-content">Aktiviteler</span>
                                        </a>
                                    </div>
                                    <div class="grid-nav-row">
                                        <a href="{{ route('tasks.index') }}" class="grid-nav-item">
                                            <div class="grid-nav-icon"><i class="far fa-calendar-check"></i></div>
                                            <span class="grid-nav-content">Görevler</span>
                                            @if(isset($tasksCount) && $tasksCount > 0)
                                                <span class="badge bg-warning ms-2">{{ $tasksCount > 99 ? '99+' : $tasksCount }}</span>
                                            @endif
                                        </a>
                                        <a href="{{ route('notes.index') }}" class="grid-nav-item">
                                            <div class="grid-nav-icon"><i class="far fa-sticky-note"></i></div>
                                            <span class="grid-nav-content">Notlar</span>
                                        </a>
                                        <a href="{{ url('notifications') }}" class="grid-nav-item">
                                            <div class="grid-nav-icon"><i class="far fa-bell"></i></div>
                                            <span class="grid-nav-content">Bildirim</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer p-2 d-flex flex-column gap-2" style="background:transparent; border-top: 1px solid rgba(255,255,255,0.08);">
                                <a href="{{ route('admin.license.index') }}" class="btn btn-sm btn-outline-info w-100 d-flex align-items-center justify-content-between px-3 py-2" style="border-radius: 8px; font-weight: 600;">
                                    <span class="d-flex align-items-center gap-2">
                                        <i class="ri-key-2-line"></i>
                                        <span>Yazılım Lisansı</span>
                                    </span>
                                    @if(function_exists('pergel_is_pro') && pergel_is_pro())
                                        <span class="badge bg-warning text-dark font-monospace" style="font-size: 9px;">PRO</span>
                                    @else
                                        <span class="badge bg-secondary text-white font-monospace" style="font-size: 9px;">FREE</span>
                                    @endif
                                </a>
                                <button type="button" 
                                        onclick="if(window.PergelLock){ window.PergelLock.lockSession(); } else { window.location.href='{{ route('lock-screen.show') }}'; }" 
                                        class="btn btn-sm btn-outline-warning w-100 d-flex align-items-center justify-content-between px-3 py-2"
                                        style="border-radius: 8px; font-weight: 600;">
                                    <span class="d-flex align-items-center gap-2">
                                        <i class="ri-lock-2-line"></i>
                                        <span>Ekranı Kilitle</span>
                                    </span>
                                    <small class="text-muted opacity-75" style="font-size: 10px; font-family: monospace;">Ctrl+Alt+L</small>
                                </button>
                                <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-label-danger w-100 d-flex align-items-center justify-content-center gap-2 py-2" style="border-radius: 8px; font-weight: 600;">
                                        <i class="ri-logout-box-r-line"></i>
                                        <span>Çıkış Yap</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Profile -->
                @else
                <!-- Guest view: show login/register buttons -->
                <div class="d-inline-block">
                    <a href="{{ route('login') }}" class="btn btn-sm btn-outline-light me-2">Giriş Yap</a>
                    @if(Route::has('register'))
                        <a href="{{ route('register') }}" class="btn btn-sm btn-primary">Kayıt Ol</a>
                    @endif
                </div>
                @endauth
            </div>
        </div>
        <!-- End menu -->
    </div>
</header>
<!-- End topbar -->

<!-- Start Focus Music & Radio Offcanvas -->
<div class="offcanvas offcanvas-end rounded-start-4 shadow-lg border-0" tabindex="-1" id="focusRadioOffcanvas" style="width: 380px; background: var(--pt-modal-bg, #0f172a); color: var(--pt-body-color, #ffffff); border-left: 1px solid var(--pt-modal-border, rgba(255,255,255,0.12)) !important; z-index: 1060;">
    <div class="offcanvas-header border-bottom border-secondary border-opacity-25 p-3" style="background: var(--pt-modal-header-bg, rgba(0,0,0,0.1));">
        <div class="d-flex align-items-center gap-2">
            <div class="rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; background: var(--pt-primary-subtle, rgba(245,158,11,0.15)); color: var(--pt-primary, #f59e0b);">
                <i class="ri-radio-2-line fs-5"></i>
            </div>
            <div>
                <h6 class="offcanvas-title fw-bold mb-0" style="color: var(--pt-heading-color, #ffffff);">Odaklanma Müzikleri & Radyo</h6>
                <small class="text-muted" style="font-size:11px;">Kesintisiz Çalışma & Odak Frekansı</small>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Kapat"></button>
    </div>
    <div class="offcanvas-body p-3">
        <!-- Player Card -->
        <div class="card border-0 rounded-4 p-3 mb-3 text-center position-relative overflow-hidden" style="background: var(--pt-card-bg, rgba(255,255,255,0.05)); border: 1px solid var(--pt-card-border, rgba(255,255,255,0.12)) !important; box-shadow: var(--pt-card-shadow, none);">
            <div class="mb-3">
                <span class="badge rounded-pill px-3 py-1 fw-bold mb-2" id="radio-station-genre" style="font-size:10px; background: var(--pt-primary, #f59e0b); color: var(--pt-btn-primary-text, #ffffff);">LOFI CHILL</span>
                <h5 class="fw-extrabold mb-1" id="radio-station-title" style="font-size:1.1rem; color: var(--pt-heading-color, #ffffff);">Deep Work & Lofi Chill</h5>
                <p class="text-muted mb-0" style="font-size:11px;" id="radio-station-artist">Canlı Odak Frekansı - 24/7 Stream</p>
            </div>

            <!-- Controls -->
            <div class="d-flex align-items-center justify-content-center gap-3 my-2">
                <button type="button" class="btn rounded-circle p-0 shadow-lg d-flex align-items-center justify-content-center mx-auto" id="radio-play-btn" style="width:54px; height:54px; background: var(--pt-primary, #f59e0b); color: var(--pt-btn-primary-text, #ffffff); border: none;" title="Oynat / Durdur">
                    <i class="ri-play-fill fs-2 ms-1" id="radio-play-icon"></i>
                </button>
            </div>

            <!-- Volume Slider -->
            <div class="d-flex align-items-center gap-2 mt-3 px-2">
                <i class="ri-volume-down-line text-muted"></i>
                <input type="range" class="form-range" id="radio-volume-slider" min="0" max="1" step="0.05" value="0.7" style="accent-color: var(--pt-primary, #f59e0b);">
                <i class="ri-volume-up-line text-muted"></i>
            </div>
        </div>

        <!-- Station Selector List -->
        <h6 class="text-muted fw-bold mb-2 text-uppercase" style="letter-spacing:1px; font-size:11px;">İstasyon Seçimi:</h6>
        <div class="list-group list-group-flush rounded-3 border-0 overflow-hidden" id="radio-station-list">
            <button type="button" class="list-group-item list-group-item-action border-secondary border-opacity-25 active-station d-flex align-items-center justify-content-between p-3" data-src="https://streams.ilovemusic.de/iloveradio.mp3" data-title="Deep Work & Lofi Chill" data-genre="LOFI CHILL" style="background: var(--pt-card-bg); color: var(--pt-body-color);">
                <div class="d-flex align-items-center gap-3">
                    <i class="ri-headphone-line fs-4" style="color: var(--pt-primary);"></i>
                    <div class="text-start">
                        <strong class="d-block mb-0 fs-6" style="color: var(--pt-heading-color);">Deep Work & Lofi Chill</strong>
                        <small class="text-muted">Sakinleştirici odak frekansı</small>
                    </div>
                </div>
                <i class="ri-volume-up-fill radio-active-icon" style="color: var(--pt-primary);"></i>
            </button>
            <button type="button" class="list-group-item list-group-item-action border-secondary border-opacity-25 d-flex align-items-center justify-content-between p-3" data-src="https://stream.zeno.fm/f3wvbbqmdg8uv" data-title="Saha & İnşaat Klasik Odak" data-genre="KLASİK & AMBİENT" style="background: var(--pt-card-bg); color: var(--pt-body-color);">
                <div class="d-flex align-items-center gap-3">
                    <i class="ri-music-2-line fs-4" style="color: var(--pt-accent);"></i>
                    <div class="text-start">
                        <strong class="d-block mb-0 fs-6" style="color: var(--pt-heading-color);">Saha & İnşaat Klasik Odak</strong>
                        <small class="text-muted">Piyano & Derin Akustik Melodiler</small>
                    </div>
                </div>
            </button>
            <button type="button" class="list-group-item list-group-item-action border-secondary border-opacity-25 d-flex align-items-center justify-content-between p-3" data-src="https://stream.zeno.fm/0r0xa792kwzuv" data-title="Gece Vardiyası Ambient" data-genre="NIGHT AMBIENT" style="background: var(--pt-card-bg); color: var(--pt-body-color);">
                <div class="d-flex align-items-center gap-3">
                    <i class="ri-moon-cloudy-line fs-4" style="color: var(--pt-info, #0891b2);"></i>
                    <div class="text-start">
                        <strong class="d-block mb-0 fs-6" style="color: var(--pt-heading-color);">Gece Vardiyası Ambient</strong>
                        <small class="text-muted">Doğa ve ortam frekansları</small>
                    </div>
                </div>
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Yield to persistent focus-radio-player.blade.php if present on the page
    if (document.getElementById('focus-radio-widget') || document.getElementById('pergel-radio-widget')) {
        return;
    }

    const radioBtn = document.getElementById('topbar-radio-toggle-btn');
    const radioOffcanvasEl = document.getElementById('focusRadioOffcanvas');
    if (!radioBtn || !radioOffcanvasEl) return;

    let bsOffcanvas = null;
    radioBtn.addEventListener('click', function() {
        if (!bsOffcanvas) {
            bsOffcanvas = new bootstrap.Offcanvas(radioOffcanvasEl);
        }
        bsOffcanvas.toggle();
    });

    let audioPlayer = new Audio();
    audioPlayer.volume = 0.7;

    const playBtn = document.getElementById('radio-play-btn');
    const playIcon = document.getElementById('radio-play-icon');
    const volumeSlider = document.getElementById('radio-volume-slider');
    const eqIndicator = document.getElementById('topbar-radio-eq-indicator');
    const stationTitleEl = document.getElementById('radio-station-title');
    const stationGenreEl = document.getElementById('radio-station-genre');
    const stationButtons = document.querySelectorAll('#radio-station-list button');

    let currentStream = 'https://streams.ilovemusic.de/iloveradio.mp3';

    stationButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            stationButtons.forEach(b => {
                b.classList.remove('active-station', 'bg-warning', 'text-dark');
                b.classList.add('bg-dark', 'text-white');
                const icon = b.querySelector('.radio-active-icon');
                if (icon) icon.remove();
            });

            this.classList.add('active-station', 'bg-warning', 'text-dark');
            this.classList.remove('bg-dark', 'text-white');

            currentStream = this.getAttribute('data-src');
            stationTitleEl.textContent = this.getAttribute('data-title');
            stationGenreEl.textContent = this.getAttribute('data-genre');

            audioPlayer.src = currentStream;
            audioPlayer.play().then(() => {
                updatePlayState(true);
            }).catch(e => console.log('Audio autoplay prevented:', e));
        });
    });

    playBtn.addEventListener('click', function() {
        if (audioPlayer.paused) {
            if (!audioPlayer.src) audioPlayer.src = currentStream;
            audioPlayer.play().then(() => {
                updatePlayState(true);
            }).catch(e => console.log('Audio play failed:', e));
        } else {
            audioPlayer.pause();
            updatePlayState(false);
        }
    });

    if (volumeSlider) {
        volumeSlider.addEventListener('input', function() {
            audioPlayer.volume = this.value;
        });
    }

    function updatePlayState(isPlaying) {
        if (isPlaying) {
            playIcon.className = 'ri-pause-fill fs-2 text-dark';
            if (eqIndicator) eqIndicator.classList.add('playing');
        } else {
            playIcon.className = 'ri-play-fill fs-2 text-dark ms-1';
            if (eqIndicator) eqIndicator.classList.remove('playing');
        }
    }
});
</script>
<!-- End Focus Music & Radio Offcanvas -->

<div class="sidebar-overlay" id="sidebar-overlay"></div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const body = document.body;
        const sidebarBtn = document.getElementById('sidebar-btn');
        const sidebarOverlay = document.getElementById('sidebar-overlay');
        const sidebarBtnIcon = sidebarBtn ? sidebarBtn.querySelector('i') : null;

        const toggleMenu = (e) => {
            if (e) e.preventDefault();
            if (window.innerWidth <= 991.98) {
                const mobileDrawer = document.getElementById('pergelMobileDrawer');
                if (mobileDrawer && window.bootstrap && window.bootstrap.Offcanvas) {
                    const bsDrawer = bootstrap.Offcanvas.getInstance(mobileDrawer) || new bootstrap.Offcanvas(mobileDrawer);
                    bsDrawer.toggle();
                } else {
                    body.classList.toggle('sidebar-is-open');
                }
            } else {
                body.classList.toggle('sidebar-collapsed');
            }
            updateIcon();
        };

        const updateIcon = () => {
            if (!sidebarBtnIcon) return;
            // Menünün açık olup olmadığını kontrol et
            const isMobileOpen = body.classList.contains('sidebar-is-open') && window.innerWidth <= 991.98;
            const isDesktopNotCollapsed = !body.classList.contains('sidebar-collapsed') && window.innerWidth > 991.98;

            if (isMobileOpen || isDesktopNotCollapsed) {
                sidebarBtnIcon.classList.remove('mdi-menu');
                sidebarBtnIcon.classList.add('mdi-menu-open');
            } else {
                sidebarBtnIcon.classList.remove('mdi-menu-open');
                sidebarBtnIcon.classList.add('mdi-menu');
            }
        };

        if (sidebarBtn) {
            sidebarBtn.addEventListener('click', toggleMenu);
        }
        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', toggleMenu);
        }

        // Sayfa yüklendiğinde ve ekran boyutu değiştiğinde ikonun doğru ayarlandığından emin ol
        window.addEventListener('resize', updateIcon);
        updateIcon(); // İlk yüklemede çalıştır
    });
</script>

<!-- Global Search JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('global-search-input');
    const searchResults = document.getElementById('search-results');
    const searchItemsContainer = document.getElementById('search-items-container');
    const searchSpinner = document.querySelector('.search-spinner');
    const searchIcon = document.querySelector('.search-icon');

    let searchTimeout;
    let isSearching = false;

    // Sample data for demonstration - replace with actual API calls
    const sampleData = {
        projects: [
            { id: 1, title: 'Website Yenileme Projesi', subtitle: 'Aktif Proje', url: '/projects/1' },
            { id: 2, title: 'Mobil Uygulama Geliştirme', subtitle: 'Planlama Aşamasında', url: '/projects/2' },
            { id: 3, title: 'Veri Tabanı Optimizasyonu', subtitle: 'Tamamlandı', url: '/projects/3' }
        ],
        tasks: [
            { id: 1, title: 'UI Tasarımı Tamamla', subtitle: 'Yüksek Öncelik', url: '/tasks/1' },
            { id: 2, title: 'API Entegrasyonu', subtitle: 'Devam Ediyor', url: '/tasks/2' },
            { id: 3, title: 'Test Senaryoları Yaz', subtitle: 'Beklemede', url: '/tasks/3' }
        ],
        users: [
            { id: 1, title: 'Ahmet Yılmaz', subtitle: 'Proje Yöneticisi', url: '/users/1' },
            { id: 2, title: 'Ayşe Kaya', subtitle: 'Geliştirici', url: '/users/2' },
            { id: 3, title: 'Mehmet Demir', subtitle: 'Tasarımcı', url: '/users/3' }
        ],
        documents: [
            { id: 1, title: 'Proje Belgesi v2.1', subtitle: 'PDF Dokümanı', url: '/documents/1' },
            { id: 2, title: 'Teknik Şartname', subtitle: 'Word Dokümanı', url: '/documents/2' },
            { id: 3, title: 'Kullanıcı Kılavuzu', subtitle: 'PDF Dokümanı', url: '/documents/3' }
        ]
    };

    searchInput.addEventListener('input', function(e) {
        const query = e.target.value.trim();

        if (query.length === 0) {
            hideSearchResults();
            return;
        }

        if (query.length < 2) {
            return;
        }

        // Clear previous timeout
        clearTimeout(searchTimeout);

        // Set new timeout for search
        searchTimeout = setTimeout(() => {
            performSearch(query);
        }, 300);
    });

    searchInput.addEventListener('focus', function() {
        if (searchInput.value.trim().length >= 2) {
            showSearchResults();
        }
    });

    // Hide search results when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.app-search')) {
            hideSearchResults();
        }
    });

    // Prevent form submission
    document.getElementById('global-search-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const query = e.target.value.trim();
        if (query.length >= 2) {
            // Redirect to full search page
            window.location.href = `/search?q=${encodeURIComponent(query)}`;
        }
    });

    function performSearch(query) {
        if (isSearching) return;

        isSearching = true;
        showLoading();

        // Make API call
        fetch(`/api/search?q=${encodeURIComponent(query)}&limit=3`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    displayResults(data.results, query);
                } else {
                    displayResults({ projects: [], tasks: [], users: [], documents: [] }, query);
                }
                isSearching = false;
                hideLoading();
            })
            .catch(error => {
                console.error('Search error:', error);
                // Fallback to local search data
                const results = searchData(query);
                displayResults(results, query);
                isSearching = false;
                hideLoading();
            });
    }

    function searchData(query) {
        const lowerQuery = query.toLowerCase();
        const results = {
            projects: [],
            tasks: [],
            users: [],
            documents: []
        };

        // Search in all categories
        Object.keys(sampleData).forEach(category => {
            sampleData[category].forEach(item => {
                if (item.title.toLowerCase().includes(lowerQuery) ||
                    item.subtitle.toLowerCase().includes(lowerQuery)) {
                    results[category].push(item);
                }
            });
        });

        return results;
    }

    function displayResults(results, query) {
        searchItemsContainer.innerHTML = '';

        let totalResults = 0;
        Object.values(results).forEach(categoryResults => {
            totalResults += categoryResults.length;
        });

        if (totalResults === 0) {
            searchItemsContainer.innerHTML = `
                <div class="search-no-results">
                    <i class="mdi mdi-magnify"></i>
                    <div>
                        <strong>"${query}" için sonuç bulunamadı</strong>
                        <p class="mb-0 mt-1">Farklı anahtar kelimeler deneyin</p>
                    </div>
                </div>
            `;
            showSearchResults();
            return;
        }

        // Display results by category
        const categoryNames = {
            projects: { name: 'Projeler', icon: 'mdi-folder-outline' },
            tasks: { name: 'Görevler', icon: 'mdi-clipboard-check-outline' },
            users: { name: 'Kullanıcılar', icon: 'mdi-account-outline' },
            documents: { name: 'Dokümanlar', icon: 'mdi-file-document-outline' }
        };

        Object.keys(results).forEach(category => {
            if (results[category] && results[category].length > 0) {
                const categoryDiv = document.createElement('div');
                categoryDiv.className = 'search-category';

                categoryDiv.innerHTML = `
                    <div class="search-category-title">
                        <i class="mdi ${categoryNames[category].icon} me-1"></i>
                        ${categoryNames[category].name} (${results[category].length})
                    </div>
                `;

                results[category].forEach(item => {
                    const itemElement = document.createElement('a');
                    itemElement.href = item.url;
                    itemElement.className = 'search-item';
                    itemElement.innerHTML = `
                        <div class="search-item-icon ${category}">
                            <i class="mdi ${categoryNames[category].icon}"></i>
                        </div>
                        <div class="search-item-content">
                            <div class="search-item-title">${highlightQuery(item.title, query)}</div>
                            <div class="search-item-subtitle">${item.subtitle}</div>
                        </div>
                    `;
                    categoryDiv.appendChild(itemElement);
                });

                // Add "see more" link for each category
                const moreLink = document.createElement('a');
                moreLink.href = `/search?q=${encodeURIComponent(query)}&category=${category}`;
                moreLink.className = 'search-item';
                moreLink.innerHTML = `
                    <div class="search-item-icon">
                        <i class="mdi mdi-dots-horizontal"></i>
                    </div>
                    <div class="search-item-content">
                        <div class="search-item-title">Daha fazla ${categoryNames[category].name.toLowerCase()} gör</div>
                        <div class="search-item-subtitle">Tüm sonuçları görüntüle</div>
                    </div>
                `;
                categoryDiv.appendChild(moreLink);

                searchItemsContainer.appendChild(categoryDiv);
            }
        });

        // Add "View all results" link at bottom
        if (totalResults > 0) {
            const allResultsLink = document.createElement('a');
            allResultsLink.href = `/search?q=${encodeURIComponent(query)}`;
            allResultsLink.className = 'search-item';
            allResultsLink.style.borderTop = '1px solid #e5e7eb';
            allResultsLink.style.fontWeight = '600';
            allResultsLink.innerHTML = `
                <div class="search-item-icon">
                    <i class="mdi mdi-arrow-right"></i>
                </div>
                <div class="search-item-content">
                    <div class="search-item-title">Tüm sonuçları görüntüle (${totalResults})</div>
                    <div class="search-item-subtitle">Detaylı arama sayfasına git</div>
                </div>
            `;
            searchItemsContainer.appendChild(allResultsLink);
        }

        showSearchResults();
    }

    function highlightQuery(text, query) {
        const regex = new RegExp(`(${query})`, 'gi');
        return text.replace(regex, '<mark style="background: #FEF3C7; color: #92400E; padding: 1px 2px; border-radius: 2px;">$1</mark>');
    }

    function showLoading() {
        searchSpinner.classList.remove('d-none');
        searchIcon.style.display = 'none';
    }

    function hideLoading() {
        searchSpinner.classList.add('d-none');
        searchIcon.style.display = 'block';
    }

    function showSearchResults() {
        searchResults.style.display = 'block';
    }

    function hideSearchResults() {
        searchResults.style.display = 'none';
    }

    // Keyboard navigation
    searchInput.addEventListener('keydown', function(e) {
        const items = searchResults.querySelectorAll('.search-item');
        let currentFocus = Array.from(items).findIndex(item => item.classList.contains('focused'));

        if (e.key === 'ArrowDown') {
            e.preventDefault();
            if (currentFocus < items.length - 1) {
                if (currentFocus >= 0) items[currentFocus].classList.remove('focused');
                items[currentFocus + 1].classList.add('focused');
            }
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            if (currentFocus > 0) {
                items[currentFocus].classList.remove('focused');
                items[currentFocus - 1].classList.add('focused');
            }
        } else if (e.key === 'Enter') {
            e.preventDefault();
            if (currentFocus >= 0 && items[currentFocus]) {
                items[currentFocus].click();
            } else {
                document.getElementById('global-search-form').dispatchEvent(new Event('submit'));
            }
        }
    });
});

// Mark all notifications as read function
function markAllAsRead() {
    // This would typically make an AJAX call to mark notifications as read
    fetch('/notifications/mark-all-read', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update UI to reflect all notifications as read
            document.querySelectorAll('.notification-item').forEach(item => {
                item.classList.add('read');
            });

            // Update notification badge
            const badge = document.querySelector('.badge.bg-info-subtle');
            if (badge) {
                badge.textContent = '0';
            }

            // Show success message
            showToast('Tüm bildirimler okundu olarak işaretlendi', 'success');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Bir hata oluştu', 'error');
    });
}

// Simple toast notification function
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show position-fixed`;
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 2000; min-width: 300px;';
    toast.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

    document.body.appendChild(toast);

    // Auto remove after 3 seconds
    setTimeout(() => {
        toast.remove();
    }, 3000);
}
</script>

