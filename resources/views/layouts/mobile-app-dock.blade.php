<!-- Pergel Native Mobile Bottom Navigation App Dock -->
<div class="pergel-mobile-dock">
    <a href="{{ route('root') }}" class="mobile-dock-item {{ (request()->is('/') || request()->is('dashboard') || request()->routeIs('root')) ? 'active' : '' }}">
        <i class="ri-home-5-line"></i>
        <span>Ana Sayfa</span>
    </a>

    <a href="{{ route('projects.index') }}" class="mobile-dock-item {{ request()->routeIs('projects.*') ? 'active' : '' }}">
        <i class="ri-building-2-line"></i>
        <span>Projeler</span>
    </a>

    <!-- Center AI Dock Button -->
    <a href="{{ route('ai-module.index') }}" class="mobile-dock-item dock-center-btn {{ request()->routeIs('ai-module.*') ? 'active' : '' }}">
        <div class="dock-icon-circle">
            <i class="ri-robot-line"></i>
        </div>
        <span>Yapay Zeka</span>
    </a>

    <a href="javascript:void(0);" class="mobile-dock-item" id="mobile-dock-radio-btn" onclick="triggerMobileRadio(event)">
        <i class="ri-radio-2-line text-warning"></i>
        <span>Radyo</span>
    </a>

    <a href="javascript:void(0);" class="mobile-dock-item" data-bs-toggle="offcanvas" data-bs-target="#pergelMobileDrawer">
        <i class="ri-menu-4-line"></i>
        <span>Menü</span>
    </a>
</div>

<!-- Native Mobile Drawer Offcanvas Navigation -->
<div class="offcanvas offcanvas-start pergel-mobile-drawer" tabindex="-1" id="pergelMobileDrawer" aria-labelledby="pergelMobileDrawerLabel">
    <div class="offcanvas-header">
        <div class="d-flex align-items-center gap-3">
            <div class="avatar-sm rounded-circle bg-primary bg-opacity-20 d-flex align-items-center justify-content-center text-primary fw-bold" style="width: 42px; height: 42px;">
                <i class="ri-user-3-fill fs-5"></i>
            </div>
            <div>
                <h6 class="fw-bold text-white mb-0" id="pergelMobileDrawerLabel">{{ auth()->user()->name ?? 'Pergel Kullanıcısı' }}</h6>
                <small class="text-white-50 extra-small">{{ auth()->user()->email ?? 'Şantiye & Gayrimenkul Portalı' }}</small>
            </div>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Kapat"></button>
    </div>
    <div class="offcanvas-body p-2" data-simplebar>
        <!-- Hızlı Arama Barı -->
        <div class="px-2 mb-3">
            <form action="{{ route('search.index') }}" method="GET" class="position-relative">
                <input type="text" name="q" class="form-control form-control-sm bg-black bg-opacity-40 text-white border-white border-opacity-10 rounded-pill px-3 py-2 ps-4" placeholder="Proje, Hakediş, Fatura Ara...">
                <i class="ri-search-line position-absolute text-white-50" style="left: 12px; top: 50%; transform: translateY(-50%);"></i>
            </form>
        </div>

        <!-- Hızlı Aksiyon Kısayolları -->
        <div class="d-flex gap-2 px-2 mb-3">
            <a href="{{ route('projects.index') }}" class="btn btn-sm btn-primary flex-fill rounded-pill py-2 fw-bold" style="font-size: 11px;">
                <i class="ri-add-line me-1"></i>Yeni Proje
            </a>
            <a href="{{ route('ai-module.index') }}" class="btn btn-sm btn-warning flex-fill rounded-pill py-2 fw-bold text-dark" style="font-size: 11px;">
                <i class="ri-robot-line me-1"></i>AI Asistan
            </a>
        </div>

        <!-- Gezinme Kategorileri -->
        <div class="drawer-section-title">ANA MENÜ</div>
        <a href="{{ route('root') }}" class="drawer-nav-link {{ (request()->is('/') || request()->is('dashboard')) ? 'active' : '' }}">
            <i class="ri-dashboard-3-line"></i>
            <span>Genel Bakış (Dashboard)</span>
        </a>

        <div class="drawer-section-title">PROJE & ŞANTİYE</div>
        <a href="{{ route('projects.index') }}" class="drawer-nav-link {{ request()->routeIs('projects.*') ? 'active' : '' }}">
            <i class="ri-building-4-line"></i>
            <span>Proje Yönetimi</span>
        </a>
        <a href="{{ route('projects.documents.all') }}" class="drawer-nav-link {{ request()->routeIs('projects.documents.*') ? 'active' : '' }}">
            <i class="ri-folder-shared-line"></i>
            <span>Doküman Havuzu</span>
        </a>

        <div class="drawer-section-title">TÜRKİYE İNŞAAT MEVZUATI</div>
        <a href="{{ route('sgk.index') }}" class="drawer-nav-link {{ request()->routeIs('sgk.*') ? 'active' : '' }}">
            <i class="ri-calculator-line"></i>
            <span>SGK Asgari İşçilik</span>
        </a>
        <a href="{{ route('land_owner_contracts.index') }}" class="drawer-nav-link {{ request()->routeIs('land_owner_contracts.*') ? 'active' : '' }}">
            <i class="ri-hand-coin-line"></i>
            <span>Kat Karşılığı Matrisi</span>
        </a>
        <a href="{{ route('concrete_tests.index') }}" class="drawer-nav-link {{ request()->routeIs('concrete_tests.*') ? 'active' : '' }}">
            <i class="ri-flask-line"></i>
            <span>EBİS Beton Kırım</span>
        </a>
        <a href="{{ route('csb_poz.index') }}" class="drawer-nav-link {{ request()->routeIs('csb_poz.*') ? 'active' : '' }}">
            <i class="ri-book-read-line"></i>
            <span>ÇŞB Poz Kütüphanesi</span>
        </a>

        <div class="drawer-section-title">FİNANS & MUHASEBE</div>
        @if(Route::has('vouchers.index'))
        <a href="{{ route('vouchers.index') }}" class="drawer-nav-link">
            <i class="ri-file-list-3-line"></i>
            <span>Hakediş & Makbuzlar</span>
        </a>
        @endif
        @if(Route::has('reports.general.kpi_dashboard'))
        <a href="{{ route('reports.general.kpi_dashboard') }}" class="drawer-nav-link">
            <i class="ri-pie-chart-line"></i>
            <span>Raporlar & Analitik</span>
        </a>
        @endif

        <div class="drawer-section-title">SİSTEM & DESTEK</div>
        <a href="{{ route('admin.system.index') }}" class="drawer-nav-link {{ request()->routeIs('admin.system.*') ? 'active' : '' }}">
            <i class="ri-pulse-line"></i>
            <span>Sistem Durumu & Sağlık</span>
        </a>
        <a href="{{ route('help.index') }}" class="drawer-nav-link {{ request()->routeIs('help.*') ? 'active' : '' }}">
            <i class="ri-question-line"></i>
            <span>Yardım Merkezi</span>
        </a>

        <!-- Logout Form -->
        <div class="mt-4 px-2 mb-3">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-danger w-100 rounded-pill py-2 fw-bold" style="font-size: 12px;">
                    <i class="ri-logout-box-r-line me-1"></i>Çıkış Yap
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function triggerMobileRadio(e) {
    if (e) e.preventDefault();
    // Check if offcanvas is available
    const radioOffcanvasEl = document.getElementById('focusRadioOffcanvas');
    if (radioOffcanvasEl && window.bootstrap && window.bootstrap.Offcanvas) {
        const bsOffcanvas = bootstrap.Offcanvas.getInstance(radioOffcanvasEl) || new bootstrap.Offcanvas(radioOffcanvasEl);
        bsOffcanvas.toggle();
        return;
    }

    // Fallback to floating player widget toggle
    const radioBtn = document.getElementById('topbar-radio-toggle-btn');
    if (radioBtn) {
        radioBtn.click();
    }
}
</script>
