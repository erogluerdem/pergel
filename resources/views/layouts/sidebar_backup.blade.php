{{--
    SantiyeConnect Sidebar - Premium Glassmorphism Design
    Koyu lacivert + cam efekti ile premium menu tasarim
--}}
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
:root {
    --sidebar-bg: linear-gradient(160deg, #0d1b2a 0%, #1a2744 40%, #0f1e38 100%);
    --accent-blue: #4f9ef8;
    --accent-purple: #7c3aed;
    --text-bright: #e2e8f0;
    --text-normal: #b0bec5;
    --text-dim: #78909c;
    --t-fast: all 0.18s cubic-bezier(0.4,0,0.2,1);
    --t-med: all 0.28s cubic-bezier(0.4,0,0.2,1);
}
.sidebar-left { display: block !important; visibility: visible !important; left: 0; }
.sidebar-left .vertical-menu {
    background: var(--sidebar-bg) !important;
    border-right: 1px solid rgba(99,179,237,0.12);
    box-shadow: 0 25px 50px rgba(0,0,0,0.5);
    height: 100vh;
    overflow-y: auto; overflow-x: hidden;
    position: relative;
    padding-top: 4px;
    font-family: 'Inter', sans-serif;
    display: block !important; visibility: visible !important; opacity: 1 !important;
    transform: translateX(0) !important;
    scrollbar-width: thin;
    scrollbar-color: rgba(79,158,248,0.2) transparent;
}
.sidebar-left .vertical-menu::before {
    content: ''; position: absolute; top: 0; left: 0; right: 0; height: 220px;
    background: radial-gradient(ellipse at 50% 0%, rgba(79,158,248,0.07) 0%, transparent 70%);
    pointer-events: none; z-index: 0;
}
.sidebar-left .vertical-menu::-webkit-scrollbar { width: 3px; }
.sidebar-left .vertical-menu::-webkit-scrollbar-track { background: transparent; }
.sidebar-left .vertical-menu::-webkit-scrollbar-thumb { background: rgba(79,158,248,0.2); border-radius: 10px; }
@media (max-width: 991.98px) {
    .sidebar-left { position: fixed; top: 64px; left: 0; bottom: 0; width: 250px; z-index: 1050; }
    .sidebar-left .vertical-menu { height: calc(100vh - 64px); }
    .main-content { margin-left: 250px; }
}
.sidebar-left .navbar-brand-box {
    background: rgba(7,12,28,0.9) !important;
    border-bottom: 1px solid rgba(79,158,248,0.15) !important;
    box-shadow: 0 4px 24px rgba(0,0,0,0.3); position: relative;
}
.sidebar-left .navbar-brand-box::after {
    content: ''; position: absolute; bottom: 0; left: 15%; right: 15%;
    height: 1px; background: linear-gradient(90deg, transparent, rgba(79,158,248,0.5), transparent);
}
.main-content { margin-left: 255px; }
@media (max-width: 991.98px) { .main-content { margin-left: 0 !important; } }
.sidebar-left .left-menu .menu-title {
    color: rgba(79,158,248,0.8); font-size: 9.5px; font-weight: 700;
    letter-spacing: 1.4px; text-transform: uppercase; padding: 18px 16px 6px;
    display: flex; align-items: center; gap: 8px; font-family: 'Inter', sans-serif; list-style: none;
}
.sidebar-left .left-menu .menu-title::after {
    content: ''; flex: 1; height: 1px; background: linear-gradient(90deg, rgba(79,158,248,0.25), transparent);
}
.ai-assistant-text {
    font-weight: 700; font-size: 0.875rem; font-family: 'Inter', sans-serif;
    display: inline-block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 148px;
    background: linear-gradient(90deg, #4f9ef8, #a78bfa, #4f9ef8); background-size: 200% auto;
    -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
    animation: sb-shimmer 3s linear infinite;
}
@keyframes sb-shimmer { 0% { background-position: 0% center; } 100% { background-position: 200% center; } }
.left-menu, .left-menu .sub-menu { list-style: none; padding: 0; margin: 0; }
.left-menu li { position: relative; }
.left-menu .sub-menu li { display: block; }
.left-menu .mm-show { display: block !important; }
.left-menu .mm-active > .sub-menu, .left-menu li.open > .sub-menu { display: block; }
.left-menu a > span { white-space: normal; word-break: break-word; line-height: 1.45; font-family: 'Inter', sans-serif; }
.sidebar-left .left-menu > li > a {
    color: var(--text-normal) !important; font-weight: 500;
    display: flex !important; align-items: center;
    padding: 8px 12px 8px 10px; margin: 2px 8px; border-radius: 9px;
    transition: var(--t-fast); font-size: 0.83rem; line-height: 1.45;
    text-decoration: none !important; border: 1px solid transparent;
    position: relative; overflow: hidden; font-family: 'Inter', sans-serif;
}
.sidebar-left .left-menu > li > a:hover {
    color: #ffffff !important; background: rgba(79,158,248,0.10) !important;
    border-color: rgba(79,158,248,0.22); transform: translateX(3px);
}
.sidebar-left .left-menu > li > a.active {
    color: #ffffff !important; background: rgba(79,158,248,0.18) !important;
    border-color: rgba(79,158,248,0.5) !important;
    box-shadow: 0 4px 20px rgba(79,158,248,0.15), inset 0 1px 0 rgba(255,255,255,0.08);
}
.sidebar-left .left-menu > li > a.active::before {
    content: ''; position: absolute; left: 0; top: 18%; bottom: 18%; width: 3px;
    background: linear-gradient(180deg, #4f9ef8, #7c3aed);
    border-radius: 0 3px 3px 0; box-shadow: 0 0 10px rgba(79,158,248,0.3);
}
.sidebar-left .left-menu > li > a > i {
    width: 22px; text-align: center; flex-shrink: 0; margin-right: 10px;
    font-size: 0.85rem; color: var(--text-dim); transition: var(--t-fast); line-height: 1;
    display: inline-block; background: none !important; border: none !important;
    box-shadow: none !important; border-radius: 0 !important; height: auto !important;
}
.sidebar-left .left-menu > li > a:hover > i { color: #4f9ef8; }
.sidebar-left .left-menu > li > a.active > i { color: #4f9ef8; text-shadow: 0 0 10px rgba(79,158,248,0.5); }
.left-menu .mm-active > a, .left-menu li.open > a { color: var(--text-bright) !important; }
.left-menu .has-arrow::after {
    content: "\f078"; font-family: 'Font Awesome 5 Free'; font-weight: 900;
    display: inline-block; margin-left: auto; font-size: 0.62rem; color: var(--text-dim);
    padding: 3px 5px; border-radius: 4px;
    background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.07);
    transition: var(--t-med); flex-shrink: 0; line-height: 1.4;
}
.left-menu .has-arrow:hover::after { color: #4f9ef8; background: rgba(79,158,248,0.1); border-color: rgba(79,158,248,0.25); }
.left-menu .mm-active > a.has-arrow::after, .left-menu li.open > a.has-arrow::after {
    transform: rotate(-180deg); color: #4f9ef8; background: rgba(79,158,248,0.15); border-color: rgba(79,158,248,0.35);
}
.left-menu .sub-menu { padding: 2px 0 4px; }
.sidebar-left .left-menu .sub-menu li > a {
    display: flex !important; align-items: center;
    color: var(--text-dim) !important; font-size: 0.8rem; font-weight: 400;
    padding: 6px 10px 6px 12px; margin: 1px 8px 1px 24px;
    text-decoration: none !important; transition: var(--t-fast); line-height: 1.4;
    font-family: 'Inter', sans-serif; border-left: 2px solid rgba(79,158,248,0.14);
    border-radius: 0 7px 7px 0; background: none;
}
.sidebar-left .left-menu .sub-menu li > a:hover {
    color: #e2e8f0 !important; background: rgba(79,158,248,0.07) !important;
    border-left-color: rgba(79,158,248,0.5) !important; transform: translateX(2px);
}
.sidebar-left .left-menu .sub-menu li > a.active {
    color: #4f9ef8 !important; background: rgba(79,158,248,0.1) !important;
    border-left-color: #4f9ef8 !important; font-weight: 500;
}
.sidebar-left .left-menu .sub-menu li > a > i {
    width: 16px; text-align: center; flex-shrink: 0; margin-right: 8px;
    font-size: 0.7rem; opacity: 0.7; color: inherit; transition: var(--t-fast);
    display: inline-block; background: none !important; border: none !important;
    box-shadow: none !important; border-radius: 0 !important; height: auto !important;
}
.sidebar-left .left-menu .sub-menu li > a:hover > i,
.sidebar-left .left-menu .sub-menu li > a.active > i { opacity: 1; color: #4f9ef8; }
.left-menu .sub-menu .sub-menu { margin-left: 10px; border-left: 1px dashed rgba(79,158,248,0.15); }
.sidebar-left .left-menu .sub-menu .sub-menu li > a {
    font-size: 0.77rem !important; padding: 5px 10px 5px 10px !important;
    margin: 1px 6px 1px 14px !important; border-left: none !important;
    border-radius: 6px !important; color: #607d8b !important;
}
.sidebar-left .left-menu .sub-menu .sub-menu li > a:hover { color: #e2e8f0 !important; background: rgba(79,158,248,0.06) !important; }
.sidebar-left .left-menu .sub-menu .sub-menu li > a.active { color: #4f9ef8 !important; background: rgba(79,158,248,0.08) !important; font-weight: 500; }
.sidebar-left .left-menu .sub-menu .sub-menu li > a > i { font-size: 0.65rem !important; width: 13px; margin-right: 6px; opacity: 0.6; }
.left-menu .sub-menu .sub-menu .sub-menu { margin-left: 8px; border-left-color: rgba(79,158,248,0.08); }
.sidebar-left .left-menu .sub-menu .sub-menu .sub-menu li > a { font-size: 0.74rem !important; padding: 4px 8px !important; margin: 1px 4px 1px 10px !important; }
#side-menu > li:first-child > a {
    background: linear-gradient(135deg, rgba(79,158,248,0.1), rgba(124,58,237,0.07)) !important;
    border: 1px solid rgba(79,158,248,0.22) !important;
    box-shadow: 0 4px 18px rgba(79,158,248,0.08), inset 0 1px 0 rgba(255,255,255,0.05) !important;
    margin-bottom: 2px;
}
#side-menu > li:first-child > a:hover { background: linear-gradient(135deg, rgba(79,158,248,0.16), rgba(124,58,237,0.1)) !important; border-color: rgba(79,158,248,0.38) !important; }
#side-menu > li:first-child > a > i { color: #7c9fd4 !important; text-shadow: 0 0 8px rgba(79,158,248,0.4); }
</style>
<!-- ========== Left Sidebar Start ========== -->
<div class="sidebar-left">
    <div data-simplebar class="h-100">
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="left-menu list-unstyled" id="side-menu">

                <li>
                    <a href="{{ route('ai-module.index') }}" class="{{ request()->routeIs('ai-module.*') ? 'active' : '' }}">
                        <i class="fas fa-robot"></i>
                        <span class="ai-assistant-text">Yapay Zeka Asistanı</span>
                        @unless(pergel_has_module('ai'))<span class="badge bg-warning text-dark ms-1" style="font-size:9px">Pro</span>@endunless
                    </a>
                </li>

                <li class="menu-title">PLATFORM MODÜLLERİ</li>

                {{-- Portföy & Fırsat --}}
                @if(pergel_has_module('portfolio'))
                <li>
                    <a href="javascript: void(0);" class="has-arrow {{ request()->routeIs('portfolio.*') ? 'active' : '' }}">
                        <i class="fas fa-chart-line"></i>
                        <span>Portföy & Fırsat</span>
                    </a>
                    <ul class="sub-menu">
                        <li>
                            <a href="javascript: void(0);" class="has-arrow {{ request()->routeIs('portfolio.management.*') ? 'active' : '' }}">
                                <i class="fas fa-folder-open"></i>
                                <span>Portföy Yönetimi</span>
                            </a>
                            <ul class="sub-menu">
                                <li><a href="{{ route('portfolio.management.index') }}" class="{{ request()->routeIs('portfolio.management.index') ? 'active' : '' }}"><i class="fas fa-folder"></i> <span>Portföy Yönetimi</span></a></li>
                                <li><a href="{{ route('index', ['any' => 'portfolio/all-projects']) }}" class="{{ request()->is('portfolio/all-projects') ? 'active' : '' }}"><i class="fas fa-stream"></i> <span>Tüm Projeler</span></a></li>
                                <li><a href="{{ route('index', ['any' => 'portfolio/program-management']) }}" class="{{ request()->is('portfolio/program-management') ? 'active' : '' }}"><i class="fas fa-tasks"></i> <span>Program Yönetimi</span></a></li>
                                <li><a href="{{ route('portfolio.financials.index') }}" class="{{ request()->is('portfolio/financials') ? 'active' : '' }}"><i class="fas fa-dollar-sign"></i> <span>Portföy Finansalları</span></a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="javascript: void(0);" class="has-arrow {{ request()->routeIs('portfolio.tenders.*') ? 'active' : '' }}">
                                <i class="fas fa-gavel"></i>
                                <span>İhale Yönetimi</span>
                            </a>
                            <ul class="sub-menu">
                                <li><a href="{{ route('opportunities.index') }}" class="{{ request()->is('opportunities*') ? 'active' : '' }}"><i class="fas fa-lightbulb"></i> <span>Potansiyel Projeler</span></a></li>
                                <li><a href="{{ route('index', ['any' => 'portfolio/tenders/feasibility-capital']) }}" class="{{ request()->is('portfolio/tenders/feasibility-capital') ? 'active' : '' }}"><i class="fas fa-chart-pie"></i> <span>Fizibilite Planlama</span></a></li>
                                <li><a href="{{ route('index', ['any' => 'portfolio/tenders/package-prepare']) }}" class="{{ request()->is('portfolio/tenders/package-prepare') ? 'active' : '' }}"><i class="fas fa-box-open"></i> <span>İhale Paketi Hazırla</span></a></li>
                                <li><a href="{{ route('index', ['any' => 'portfolio/tenders/bid-management']) }}" class="{{ request()->is('portfolio/tenders/bid-management') ? 'active' : '' }}"><i class="fas fa-file-signature"></i> <span>Teklif Yönetimi</span></a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                @endif

                {{-- CRM Müşteri İlişkileri Yönetimi --}}
                <li>
                    <a href="javascript: void(0);" class="has-arrow {{ request()->routeIs('opportunities.*', 'sales-targets.*', 'kvkk-consents.*', 'options.*', 'customer_meetings.*', 'property_documents.*', 'sales_performance.*') ? 'active' : '' }}">
                        <i class="fas fa-users"></i>
                        <span>CRM</span>
                    </a>
                    <ul class="sub-menu">
                        <li>
                            <a href="javascript: void(0);" class="has-arrow {{ request()->routeIs('opportunities.*', 'sales-targets.*', 'kvkk-consents.*', 'options.*', 'sales-reservations.*', 'units.*', 'payment-plans.*', 'consultant-changes.*') ? 'active' : '' }}">
                                <i class="fas fa-briefcase"></i>
                                <span>Satış Yönetimi</span>
                            </a>
                            <ul class="sub-menu">
                                <li><a href="{{ route('opportunities.index') }}" class="{{ request()->routeIs('opportunities.*') ? 'active' : '' }}"><i class="fas fa-lightbulb"></i> <span>Potansiyel Projeler</span></a></li>
                                <li><a href="{{ route('sales-targets.index') }}" class="{{ request()->routeIs('sales-targets.*') ? 'active' : '' }}"><i class="fas fa-bullseye"></i> <span>Satış Hedefleri</span></a></li>
                                <li><a href="{{ route('kvkk-consents.index') }}" class="{{ request()->routeIs('kvkk-consents.*') ? 'active' : '' }}"><i class="fas fa-shield-alt"></i> <span>KVKK Onayları</span></a></li>
                                <li><a href="{{ route('options.index') }}" class="{{ request()->routeIs('options.*') ? 'active' : '' }}"><i class="fas fa-bookmark"></i> <span>Opsiyonlar</span></a></li>
                                <li><a href="{{ route('sales-reservations.index') }}" class="{{ request()->routeIs('sales-reservations.*') ? 'active' : '' }}"><i class="fas fa-file-contract"></i> <span>Satış Opsiyonları</span></a></li>
                                <li><a href="{{ route('units.index') }}" class="{{ request()->routeIs('units.*') ? 'active' : '' }}"><i class="fas fa-building"></i> <span>Birim Yönetimi</span></a></li>
                            </ul>
                        </li>
                        
                        <li>
                            <a href="javascript: void(0);" class="has-arrow {{ request()->routeIs('customer_meetings.*', 'property_documents.*', 'payment-plans.*', 'consultant-changes.*', 'installments.*') ? 'active' : '' }}">
                                <i class="fas fa-handshake"></i>
                                <span>Müşteri Takibi</span>
                            </a>
                            <ul class="sub-menu">
                                <li><a href="{{ route('customer_meetings.index') }}" class="{{ request()->routeIs('customer_meetings.*') ? 'active' : '' }}"><i class="fas fa-comments"></i> <span>Görüşmeler</span></a></li>
                                <li><a href="{{ route('property_documents.index') }}" class="{{ request()->routeIs('property_documents.*') ? 'active' : '' }}"><i class="fas fa-file-contract"></i> <span>Tapu & Mesken</span></a></li>
                                <li><a href="{{ route('payment-plans.index') }}" class="{{ request()->routeIs('payment-plans.*') ? 'active' : '' }}"><i class="fas fa-credit-card"></i> <span>Ödeme Planları</span></a></li>
                                <li><a href="{{ route('installments.index') }}" class="{{ request()->routeIs('installments.*') ? 'active' : '' }}"><i class="fas fa-receipt"></i> <span>Taksit Takibi</span></a></li>
                                <li><a href="{{ route('installments.payment_status_report') }}" class="{{ request()->routeIs('installments.payment_status_report') ? 'active' : '' }}"><i class="fas fa-chart-pie"></i> <span>Ödeme Durumu</span></a></li>
                                <li><a href="{{ route('consultant-changes.index') }}" class="{{ request()->routeIs('consultant-changes.*') ? 'active' : '' }}"><i class="fas fa-exchange-alt"></i> <span>Danışman Değişiklikleri</span></a></li>
                            </ul>
                        </li>
                        
                        <li><a href="{{ route('sales_performance.index') }}" class="{{ request()->routeIs('sales_performance.*') ? 'active' : '' }}"><i class="fas fa-chart-line"></i> <span>Performans Analizi</span></a></li>
                        
                        <li>
                            <a href="javascript: void(0);" class="has-arrow {{ request()->routeIs('reports.sales.*', 'reports.projects.*', 'reports.general.*') ? 'active' : '' }}">
                                <i class="fas fa-file-pdf"></i>
                                <span>Raporlar</span>
                            </a>
                            <ul class="sub-menu">
                                <li>
                                    <a href="javascript: void(0);" class="has-arrow {{ request()->routeIs('reports.sales.*') ? 'active' : '' }}">
                                        <i class="fas fa-receipt"></i>
                                        <span>Satış Raporları</span>
                                    </a>
                                    <ul class="sub-menu">
                                        <li><a href="{{ route('reports.sales.monthly_installment') }}" class="{{ request()->routeIs('reports.sales.monthly_installment') ? 'active' : '' }}"><i class="fas fa-calendar"></i> <span>Aylık Taksitler</span></a></li>
                                        <li><a href="{{ route('reports.sales.payment_timeline') }}" class="{{ request()->routeIs('reports.sales.payment_timeline') ? 'active' : '' }}"><i class="fas fa-clock"></i> <span>Ödeme Takvimi</span></a></li>
                                        <li><a href="{{ route('reports.sales.balance_detail') }}" class="{{ request()->routeIs('reports.sales.balance_detail') ? 'active' : '' }}"><i class="fas fa-balance-scale"></i> <span>Bakiye Detayı</span></a></li>
                                        <li><a href="{{ route('reports.sales.accounting_summary') }}" class="{{ request()->routeIs('reports.sales.accounting_summary') ? 'active' : '' }}"><i class="fas fa-book"></i> <span>Muhasebe Özeti</span></a></li>
                                    </ul>
                                </li>
                                
                                <li>
                                    <a href="javascript: void(0);" class="has-arrow {{ request()->routeIs('reports.projects.*') ? 'active' : '' }}">
                                        <i class="fas fa-chart-bar"></i>
                                        <span>Proje Analizi</span>
                                    </a>
                                    <ul class="sub-menu">
                                        <li><a href="{{ route('reports.projects.financial_analysis') }}" class="{{ request()->routeIs('reports.projects.financial_analysis') ? 'active' : '' }}"><i class="fas fa-chart-line"></i> <span>Finansal Analiz</span></a></li>
                                        <li><a href="{{ route('reports.projects.stock_sales_balance') }}" class="{{ request()->routeIs('reports.projects.stock_sales_balance') ? 'active' : '' }}"><i class="fas fa-boxes"></i> <span>Stok-Satış</span></a></li>
                                        <li><a href="{{ route('reports.projects.revenue_analysis') }}" class="{{ request()->routeIs('reports.projects.revenue_analysis') ? 'active' : '' }}"><i class="fas fa-coins"></i> <span>Gelir Analizi</span></a></li>
                                        <li><a href="{{ route('reports.projects.discount_profit') }}" class="{{ request()->routeIs('reports.projects.discount_profit') ? 'active' : '' }}"><i class="fas fa-percent"></i> <span>İndirim & Kar</span></a></li>
                                        <li><a href="{{ route('reports.projects.cost_comparison') }}" class="{{ request()->routeIs('reports.projects.cost_comparison') ? 'active' : '' }}"><i class="fas fa-arrows-left-right"></i> <span>Maliyet Karş.</span></a></li>
                                    </ul>
                                </li>
                                
                                <li>
                                    <a href="javascript: void(0);" class="has-arrow {{ request()->routeIs('reports.general.*') ? 'active' : '' }}">
                                        <i class="fas fa-tachometer-alt"></i>
                                        <span>Genel Raporlar</span>
                                    </a>
                                    <ul class="sub-menu">
                                        <li><a href="{{ route('reports.general.kpi_dashboard') }}" class="{{ request()->routeIs('reports.general.kpi_dashboard') ? 'active' : '' }}"><i class="fas fa-tachometer-alt"></i> <span>KPI Dashboard</span></a></li>
                                        <li><a href="{{ route('reports.general.performance_graphs') }}" class="{{ request()->routeIs('reports.general.performance_graphs') ? 'active' : '' }}"><i class="fas fa-chart-bar"></i> <span>Grafikleri</span></a></li>
                                        <li><a href="{{ route('reports.general.sales_analysis') }}" class="{{ request()->routeIs('reports.general.sales_analysis') ? 'active' : '' }}"><i class="fas fa-chart-line"></i> <span>Satış Analizi</span></a></li>
                                        <li><a href="{{ route('reports.general.dynamic_query') }}" class="{{ request()->routeIs('reports.general.dynamic_query') ? 'active' : '' }}"><i class="fas fa-filter"></i> <span>Dinamik Sorgu</span></a></li>
                                        <li><a href="{{ route('reports.general.available_portfolio') }}" class="{{ request()->routeIs('reports.general.available_portfolio') ? 'active' : '' }}"><i class="fas fa-building"></i> <span>Açık Portföy</span></a></li>
                                    </ul>
                                </li>
                            </ul>
                        </li>

                        {{-- PHASE 3: GELIŞMIŞ RAPORLAR --}}
                        <li><a href="{{ route('advanced-reports.index') }}" class="{{ request()->routeIs('advanced-reports.*') ? 'active' : '' }}"><i class="fas fa-chart-area"></i> <span>Gelişmiş Raporlar</span></a></li>

                        {{-- PHASE 3: AKTİVİTE GÜNLÜĞÜ --}}
                        <li><a href="{{ route('activity-logs.index') }}" class="{{ request()->routeIs('activity-logs.*') ? 'active' : '' }}"><i class="fas fa-history"></i> <span>Aktivite Günlüğü</span></a></li>

                        {{-- PHASE 4: VERİ İÇE/DIŞA AKTARIMI --}}
                        <li><a href="{{ route('data-import-export.index') }}" class="{{ request()->routeIs('data-import-export.*') ? 'active' : '' }}"><i class="fas fa-exchange-alt"></i> <span>Veri İçe/Dışa Aktar</span></a></li>
                    </ul>
                </li>

                {{-- PHASE 3: SATIŞ YÖNETİMİ (Hedefler & Opsiyonlar) --}}
                <li>
                    <a href="javascript: void(0);" class="has-arrow {{ request()->routeIs('sales-targets.*', 'sales-options.*') ? 'active' : '' }}">
                        <i class="fas fa-bullseye"></i>
                        <span>Satış Yönetimi</span>
                    </a>
                    <ul class="sub-menu">
                        <li><a href="{{ route('sales-targets.index') }}" class="{{ request()->routeIs('sales-targets.*') ? 'active' : '' }}"><i class="fas fa-bullseye"></i> <span>Satış Hedefleri</span></a></li>
                        <li><a href="{{ route('sales-options.index') }}" class="{{ request()->routeIs('sales-options.*') ? 'active' : '' }}"><i class="fas fa-star"></i> <span>Satış Opsiyonları</span></a></li>
                    </ul>
                </li>

                {{-- PHASE 3: ÖDEME YÖNETİMİ (Esnek Planlar) --}}
                <li><a href="{{ route('flexible-payments.index') }}" class="{{ request()->routeIs('flexible-payments.*') ? 'active' : '' }}"><i class="fas fa-calculator"></i> <span>Esnek Ödeme Planları</span></a></li>

                {{-- PHASE 3: DÖVIZ İŞLEMLERİ --}}
                <li><a href="{{ route('currency-exchanges.index') }}" class="{{ request()->routeIs('currency-exchanges.*') ? 'active' : '' }}"><i class="fas fa-money-bill-wave"></i> <span>Döviz İşlemleri</span></a></li>

                {{-- PHASE 3: DİJİTAL SÖZLEŞMELER --}}
                <li><a href="{{ route('digital-contracts.index') }}" class="{{ request()->routeIs('digital-contracts.*') ? 'active' : '' }}"><i class="fas fa-file-signature"></i> <span>Dijital Sözleşmeler</span></a></li>
                <li>
                    <a href="javascript: void(0);" class="has-arrow {{ request()->routeIs('projects.*') ? 'active' : '' }}">
                        <i class="fas fa-project-diagram"></i>
                        <span>Proje Yönetimi</span>
                    </a>
                    <ul class="sub-menu">
                        <li><a href="{{ route('index', ['any' => 'projects/documents']) }}" class="{{ request()->routeIs('projects.documents') ? 'active' : '' }}"><i class="fas fa-file-alt"></i> <span>Doküman Kontrolü</span></a></li>
                        <li><a href="{{ route('index', ['any' => 'projects/planning']) }}" class="{{ request()->routeIs('projects.planning') ? 'active' : '' }}"><i class="fas fa-calendar-alt"></i> <span>Planlama</span></a></li>
                        <li><a href="{{ route('projects.communication.dashboard') }}" class="{{ request()->routeIs('projects.communication.*') ? 'active' : '' }}"><i class="fas fa-comments"></i> <span>İletişim</span></a></li>
                    </ul>
                </li>

                {{-- BIM & Dijital İkiz --}}
                @if(pergel_has_module('bim'))
                <li>
                    <a href="javascript: void(0);" class="has-arrow {{ request()->routeIs('bim.*') ? 'active' : '' }}">
                        <i class="fas fa-cube"></i>
                        <span>BIM & Dijital İkiz</span>
                    </a>
                    <ul class="sub-menu">
                        <li><a href="{{ route('index', ['any' => 'bim/models']) }}" class="{{ request()->routeIs('bim.models') ? 'active' : '' }}"><i class="fas fa-cubes"></i> <span>Model Yönetimi</span></a></li>
                        <li><a href="{{ route('index', ['any' => 'bim/simulations']) }}" class="{{ request()->routeIs('bim.simulations') ? 'active' : '' }}"><i class="fas fa-cogs"></i> <span>Simülasyonlar</span></a></li>
                    </ul>
                </li>
                @endif

                {{-- Finans ve Muhasebe --}}
                @if(pergel_has_module('finance'))
                <li>
                    <a href="javascript: void(0);" class="has-arrow {{ request()->routeIs('finance.*') ? 'active' : '' }}">
                        <i class="fas fa-coins"></i>
                        <span>Finans ve Muhasebe</span>
                    </a>
                    <ul class="sub-menu">
                        <li><a href="{{ route('index', ['any' => 'finance/dashboard']) }}" class="{{ request()->routeIs('finance.dashboard') ? 'active' : '' }}"><i class="fas fa-tachometer-alt"></i> <span>Finans Panosu</span></a></li>

                        <li>
                            <a href="javascript: void(0);" class="has-arrow {{ request()->routeIs('finance.accounts.*') ? 'active' : '' }}">
                                <i class="fas fa-user-tie"></i>
                                <span>Cari Hesaplar</span>
                            </a>
                            <ul class="sub-menu">
                                <li><a href="{{ route('index', ['any' => 'finance/accounts/customers']) }}" class="{{ request()->routeIs('finance.accounts.customers') ? 'active' : '' }}"><i class="fas fa-user-friends"></i> <span>Müşteri Hesapları</span></a></li>
                                <li><a href="{{ route('index', ['any' => 'finance/accounts/suppliers']) }}" class="{{ request()->routeIs('finance.accounts.suppliers') ? 'active' : '' }}"><i class="fas fa-people-carry"></i> <span>Tedarikçi Hesapları</span></a></li>
                                <li><a href="{{ route('index', ['any' => 'finance/accounts/vouchers']) }}" class="{{ request()->routeIs('finance.accounts.vouchers') ? 'active' : '' }}"><i class="fas fa-file-invoice"></i> <span>Cari Fişler</span></a></li>
                                <li><a href="{{ route('index', ['any' => 'finance/accounts/reports']) }}" class="{{ request()->routeIs('finance.accounts.reports') ? 'active' : '' }}"><i class="fas fa-chart-bar"></i> <span>Cari Raporlar</span></a></li>
                            </ul>
                        </li>

                        <li>
                            <a href="javascript: void(0);" class="has-arrow {{ request()->routeIs('finance.cash.*') ? 'active' : '' }}">
                                <i class="fas fa-university"></i>
                                <span>Kasa & Banka</span>
                            </a>
                            <ul class="sub-menu">
                                <li><a href="{{ route('index', ['any' => 'finance/cash/accounts']) }}" class="{{ request()->routeIs('finance.cash.accounts') ? 'active' : '' }}"><i class="fas fa-wallet"></i> <span>Hesaplar</span></a></li>
                                <li><a href="{{ route('index', ['any' => 'finance/cash/movements']) }}" class="{{ request()->routeIs('finance.cash.movements') ? 'active' : '' }}"><i class="fas fa-exchange-alt"></i> <span>Hesap Hareketleri</span></a></li>
                                <li><a href="{{ route('index', ['any' => 'finance/cash/transfers']) }}" class="{{ request()->routeIs('finance.cash.transfers') ? 'active' : '' }}"><i class="fas fa-paper-plane"></i> <span>Para Transferleri</span></a></li>
                                <li><a href="{{ route('index', ['any' => 'finance/cash/mutabakat']) }}" class="{{ request()->routeIs('finance.cash.mutabakat') ? 'active' : '' }}"><i class="fas fa-check-double"></i> <span>Banka Mutabakatı</span></a></li>
                                <li><a href="{{ route('index', ['any' => 'finance/cash/pos']) }}" class="{{ request()->routeIs('finance.cash.pos') ? 'active' : '' }}"><i class="fas fa-credit-card"></i> <span>POS İşlemleri</span></a></li>
                                <li><a href="{{ route('index', ['any' => 'finance/cash/currency']) }}" class="{{ request()->routeIs('finance.cash.currency') ? 'active' : '' }}"><i class="fas fa-lira-sign"></i> <span>Döviz İşlemleri</span></a></li>
                                <li><a href="{{ route('partners.index') }}" class="{{ request()->routeIs('partners.*') ? 'active' : '' }}"><i class="fas fa-handshake"></i> <span>Ortaklar</span></a></li>
                            </ul>
                        </li>

                        <li>
                            <a href="javascript: void(0);" class="has-arrow {{ request()->routeIs('finance.cheques.*') ? 'active' : '' }}">
                                <i class="fas fa-money-check-alt"></i>
                                <span>Çek & Senet</span>
                            </a>
                            <ul class="sub-menu">
                                <li><a href="{{ route('index', ['any' => 'finance/cheques/customers']) }}" class="{{ request()->routeIs('finance.cheques.customers') ? 'active' : '' }}"><i class="fas fa-arrow-down"></i> <span>Müşteri Çek/Senetleri</span></a></li>
                                <li><a href="{{ route('index', ['any' => 'finance/cheques/own']) }}" class="{{ request()->routeIs('finance.cheques.own') ? 'active' : '' }}"><i class="fas fa-arrow-up"></i> <span>Kendi Çek/Senetlerimiz</span></a></li>
                            </ul>
                        </li>

                        <li>
                            <a href="javascript: void(0);" class="has-arrow {{ request()->routeIs('finance.stock.*') ? 'active' : '' }}">
                                <i class="fas fa-boxes"></i>
                                <span>Stok Yönetimi</span>
                            </a>
                            <ul class="sub-menu">
                                <li><a href="{{ route('index', ['any' => 'finance/stock/cards']) }}" class="{{ request()->routeIs('finance.stock.cards') ? 'active' : '' }}"><i class="fas fa-box"></i> <span>Stok Kartları</span></a></li>
                                <li><a href="{{ route('index', ['any' => 'finance/stock/locations']) }}" class="{{ request()->routeIs('finance.stock.locations') ? 'active' : '' }}"><i class="fas fa-warehouse"></i> <span>Depo ve Lokasyonlar</span></a></li>
                                <li><a href="{{ route('index', ['any' => 'finance/stock/movements']) }}" class="{{ request()->routeIs('finance.stock.movements') ? 'active' : '' }}"><i class="fas fa-dolly-flatbed"></i> <span>Stok Hareketleri</span></a></li>
                                <li><a href="{{ route('index', ['any' => 'finance/stock/reports']) }}" class="{{ request()->routeIs('finance.stock.reports') ? 'active' : '' }}"><i class="fas fa-clipboard-list"></i> <span>Stok Raporları</span></a></li>
                            </ul>
                        </li>

                        <li>
                            <a href="javascript: void(0);" class="has-arrow {{ request()->routeIs('finance.hr.*') ? 'active' : '' }}">
                                <i class="fas fa-file-invoice-dollar"></i>
                                <span>Personel ve Bordro</span>
                            </a>
                            <ul class="sub-menu">
                                <li><a href="{{ route('index', ['any' => 'finance/hr/expenses']) }}" class="{{ request()->routeIs('finance.hr.expenses') ? 'active' : '' }}"><i class="fas fa-receipt"></i> <span>Personel Masrafları</span></a></li>
                                <li><a href="{{ route('index', ['any' => 'finance/hr/advances']) }}" class="{{ request()->routeIs('finance.hr.advances') ? 'active' : '' }}"><i class="fas fa-hand-holding-usd"></i> <span>Avans ve Borç Takibi</span></a></li>
                                <li><a href="{{ route('index', ['any' => 'finance/hr/payroll']) }}" class="{{ request()->routeIs('finance.hr.payroll') ? 'active' : '' }}"><i class="fas fa-file-alt"></i> <span>Bordro Tahakkukları</span></a></li>
                            </ul>
                        </li>

                        <li>
                            <a href="javascript: void(0);" class="has-arrow {{ request()->routeIs('finance.expenses.*') ? 'active' : '' }}">
                                <i class="fas fa-cash-register"></i>
                                <span>Gider Yönetimi</span>
                            </a>
                            <ul class="sub-menu">
                                <li><a href="{{ route('index', ['any' => 'finance/expenses/general']) }}" class="{{ request()->routeIs('finance.expenses.general') ? 'active' : '' }}"><i class="fas fa-money-bill"></i> <span>Genel Giderler</span></a></li>
                            </ul>
                        </li>

                        <li>
                            <a href="javascript: void(0);" class="has-arrow {{ request()->routeIs('finance.assets.*') ? 'active' : '' }}">
                                <i class="fas fa-city"></i>
                                <span>Duran Varlıklar</span>
                            </a>
                            <ul class="sub-menu">
                                <li><a href="{{ route('index', ['any' => 'finance/assets/list']) }}" class="{{ request()->routeIs('finance.assets.list') ? 'active' : '' }}"><i class="fas fa-list-alt"></i> <span>Varlık Listesi</span></a></li>
                                <li><a href="{{ route('index', ['any' => 'finance/assets/amortization']) }}" class="{{ request()->routeIs('finance.assets.amortization') ? 'active' : '' }}"><i class="fas fa-sort-amount-down"></i> <span>Amortisman İşlemleri</span></a></li>
                            </ul>
                        </li>

                        <li>
                            <a href="javascript: void(0);" class="has-arrow {{ request()->routeIs('finance.budget.*') ? 'active' : '' }}">
                                <i class="fas fa-piggy-bank"></i>
                                <span>Finansman & Bütçe</span>
                            </a>
                            <ul class="sub-menu">
                                <li><a href="{{ route('index', ['any' => 'finance/budget/company']) }}" class="{{ request()->routeIs('finance.budget.company') ? 'active' : '' }}"><i class="fas fa-building"></i> <span>Şirket Bütçesi</span></a></li>
                                <li><a href="{{ route('index', ['any' => 'finance/budget/loans']) }}" class="{{ request()->routeIs('finance.budget.loans') ? 'active' : '' }}"><i class="fas fa-landmark"></i> <span>Krediler ve Taksitler</span></a></li>
                                <li><a href="{{ route('index', ['any' => 'finance/budget/guarantees']) }}" class="{{ request()->routeIs('finance.budget.guarantees') ? 'active' : '' }}"><i class="fas fa-shield-alt"></i> <span>Teminatlar ve Sigortalar</span></a></li>
                            </ul>
                        </li>

                        <li>
                            <a href="javascript: void(0);" class="has-arrow {{ request()->routeIs('finance.edoc.*') ? 'active' : '' }}">
                                <i class="fas fa-sync-alt"></i>
                                <span>E-Dönüşüm</span>
                            </a>
                            <ul class="sub-menu">
                                <li><a href="{{ route('index', ['any' => 'finance/edoc/efatura']) }}" class="{{ request()->routeIs('finance.edoc.efatura') ? 'active' : '' }}"><i class="fas fa-file-import"></i> <span>E-Fatura</span></a></li>
                                <li><a href="{{ route('index', ['any' => 'finance/edoc/earshiv']) }}" class="{{ request()->routeIs('finance.edoc.earshiv') ? 'active' : '' }}"><i class="fas fa-archive"></i> <span>E-Arşiv</span></a></li>
                                <li><a href="{{ route('index', ['any' => 'finance/edoc/eirsaliye']) }}" class="{{ request()->routeIs('finance.edoc.eirsaliye') ? 'active' : '' }}"><i class="fas fa-truck-loading"></i> <span>E-İrsaliye</span></a></li>
                            </ul>
                        </li>

                        <!-- Fatura Yönetimi -->
                        <li>
                            <a href="javascript: void(0);" class="has-arrow {{ request()->routeIs('finance.invoices.*') ? 'active' : '' }}">
                                <i class="fas fa-file-invoice"></i>
                                <span>Fatura Yönetimi</span>
                            </a>
                            <ul class="sub-menu">
                                <li><a href="{{ route('supply.procurement.purchase_orders') }}" class="{{ request()->is('supply/procurement/purchase-orders') ? 'active' : '' }}"><i class="fas fa-shopping-basket"></i> <span>Satınalma Faturaları</span></a></li>
                                <li><a href="{{ route('finance.invoices.index') }}" class="{{ request()->routeIs('finance.invoices.index') ? 'active' : '' }}"><i class="fas fa-dollar-sign"></i> <span>Satış Faturaları</span></a></li>
                            </ul>
                        </li>

                        <!-- PHASE 5: Hakediş Yönetimi Sistemi -->
                        <li>
                            <a href="javascript: void(0);" class="has-arrow {{ request()->routeIs('progress-payments.*') ? 'active' : '' }}">
                                <i class="fas fa-file-invoice-dollar"></i>
                                <span>Hakediş Yönetimi</span>
                            </a>
                            <ul class="sub-menu">
                                <li><a href="{{ route('progress-payments.index') }}" class="{{ request()->routeIs('progress-payments.index') ? 'active' : '' }}"><i class="fas fa-list"></i> <span>Hakediş Listesi</span></a></li>
                                <li><a href="{{ route('progress-payments.create') }}" class="{{ request()->routeIs('progress-payments.create') ? 'active' : '' }}"><i class="fas fa-plus-circle"></i> <span>Yeni Hakediş</span></a></li>
                                <li><a href="{{ route('progress-payments.reports') }}" class="{{ request()->routeIs('progress-payments.reports') ? 'active' : '' }}"><i class="fas fa-chart-bar"></i> <span>Hakediş Raporları</span></a></li>
                            </ul>
                        </li>

                        <!-- Taşınan: Maliyet alt menüleri -->
                        <li><a href="{{ route('index', ['any' => 'cost/budget']) }}" class="{{ request()->routeIs('cost.budget') ? 'active' : '' }}"><i class="fas fa-search-dollar"></i> <span>Bütçe Kontrolü</span></a></li>
                        <li><a href="{{ route('index', ['any' => 'cost/payments']) }}" class="{{ request()->routeIs('cost.payments') ? 'active' : '' }}"><i class="fas fa-calculator"></i> <span>Hakedişler</span></a></li>
                        <li><a href="{{ route('index', ['any' => 'cost/erp']) }}" class="{{ request()->routeIs('cost.erp') ? 'active' : '' }}"><i class="fas fa-network-wired"></i> <span>ERP Entegrasyonu</span></a></li>

                        <!-- İleri Finans Özellikleri -->
                        <li>
                            <a href="javascript: void(0);" class="has-arrow {{ request()->routeIs('finance.forecast.*', 'finance.risks.*', 'finance.trends.*', 'finance.currency.*') ? 'active' : '' }}">
                                <i class="fas fa-chart-line"></i>
                                <span>İleri Finans Analizi</span>
                            </a>
                            <ul class="sub-menu">
                                <li><a href="{{ route('finance.forecast.dashboard') }}" class="{{ request()->routeIs('finance.forecast.*') ? 'active' : '' }}"><i class="fas fa-chart-area"></i> <span>Nakit Akış Tahmini</span></a></li>
                                <li><a href="{{ route('finance.risks.dashboard') }}" class="{{ request()->routeIs('finance.risks.*') ? 'active' : '' }}"><i class="fas fa-exclamation-triangle"></i> <span>Finansal Risk Analizi</span></a></li>
                                <li><a href="{{ route('finance.trends.dashboard') }}" class="{{ request()->routeIs('finance.trends.*') ? 'active' : '' }}"><i class="fas fa-chart-bar"></i> <span>Trend Analizi</span></a></li>
                                <li><a href="{{ route('finance.currency.index') }}" class="{{ request()->routeIs('finance.currency.*') ? 'active' : '' }}"><i class="fas fa-money-bill-wave"></i> <span>Döviz Yönetimi</span></a></li>
                            </ul>
                        </li>

                    </ul>
                </li>
                @endif

                {{-- Maliyet & Finans --}} <!-- removed: submenus moved under Finans ve Muhasebe -->

                {{-- Sözleşme Yönetimi --}}
                <li>
                    <a href="javascript: void(0);" class="has-arrow {{ request()->routeIs('contracts.*') ? 'active' : '' }}">
                        <i class="fas fa-file-signature"></i>
                        <span>Sözleşme Yönetimi</span>
                    </a>
                    <ul class="sub-menu">
                        <li><a href="{{ route('index', ['any' => 'contracts/management']) }}" class="{{ request()->routeIs('contracts.management') ? 'active' : '' }}"><i class="fas fa-file-contract"></i> <span>Sözleşmeler</span></a></li>
                        <li><a href="{{ route('index', ['any' => 'contracts/claims']) }}" class="{{ request()->routeIs('contracts.claims') ? 'active' : '' }}"><i class="fas fa-balance-scale"></i> <span>Hak Talepleri</span></a></li>
                    </ul>
                </li>

                {{-- Taşeron Yönetimi --}}
                @if(pergel_has_module('finance'))
                <li>
                    <a href="javascript: void(0);" class="has-arrow {{ request()->routeIs('purchasing.*') ? 'active' : '' }}">
                        <i class="fas fa-shopping-cart"></i>
                        <span>Satın Alma Yönetimi</span>
                    </a>
                    <ul class="sub-menu">
                        <li><a href="{{ route('purchasing.budgets.index') }}" class="{{ request()->routeIs('purchasing.budgets.*') ? 'active' : '' }}"><i class="fas fa-piggy-bank"></i> <span>Bütçe Yönetimi</span></a></li>
                        <li><a href="{{ route('purchasing.quotations.index') }}" class="{{ request()->routeIs('purchasing.quotations.*') ? 'active' : '' }}"><i class="fas fa-file-alt"></i> <span>Teklif Yönetimi</span></a></li>
                        <li><a href="{{ route('purchasing.approvals.pending') }}" class="{{ request()->routeIs('purchasing.approvals.*') ? 'active' : '' }}"><i class="fas fa-check-circle"></i> <span>Onay Süreci</span></a></li>
                        <li><a href="{{ route('purchasing.approvals.dashboard') }}" class="{{ request()->routeIs('purchasing.approvals.dashboard') ? 'active' : '' }}"><i class="fas fa-chart-line"></i> <span>Onay Paneli</span></a></li>
                    </ul>
                </li>
                @endif

                @if(pergel_has_module('subcontractor'))
                <li>
                    <a href="javascript: void(0);" class="has-arrow {{ request()->routeIs('subcontractor.*') ? 'active' : '' }}">
                        <i class="fas fa-briefcase"></i>
                        <span>Taşeron Yönetimi</span>
                    </a>
                    <ul class="sub-menu">
                        <li><a href="{{ route('index', ['any' => 'subcontractor/companies']) }}" class="{{ request()->routeIs('subcontractor.companies') ? 'active' : '' }}"><i class="fas fa-industry"></i> <span>Taşeron Firmalar</span></a></li>
                        <li><a href="{{ route('index', ['any' => 'subcontractor/contracts']) }}" class="{{ request()->routeIs('subcontractor.contracts') ? 'active' : '' }}"><i class="fas fa-handshake"></i> <span>Taşeron Sözleşmeleri</span></a></li>
                        <li>
                            <a href="javascript: void(0);" class="has-arrow {{ request()->routeIs('subcontractor.progress.*') ? 'active' : '' }}">
                                <i class="fas fa-file-signature"></i> <span>Hakediş Yönetimi</span>
                            </a>
                            <ul class="sub-menu">
                                <li><a href="{{ route('index', ['any' => 'subcontractor/progress/new']) }}" class="{{ request()->routeIs('subcontractor.progress.new') ? 'active' : '' }}"><i class="fas fa-plus-circle"></i> <span>Yeni Hakediş Talebi</span></a></li>
                                <li><a href="{{ route('index', ['any' => 'subcontractor/progress/approval']) }}" class="{{ request()->routeIs('subcontractor.progress.approval') ? 'active' : '' }}"><i class="fas fa-check-circle"></i> <span>Hakediş Onay Süreci</span></a></li>
                                <li><a href="{{ route('index', ['any' => 'subcontractor/progress/payment']) }}" class="{{ request()->routeIs('subcontractor.progress.payment') ? 'active' : '' }}"><i class="fas fa-credit-card"></i> <span>Ödeme Talimatları</span></a></li>
                            </ul>
                        </li>
                        <li><a href="{{ route('index', ['any' => 'subcontractor/payments']) }}" class="{{ request()->routeIs('subcontractor.payments') ? 'active' : '' }}"><i class="fas fa-money-bill-wave"></i> <span>Taşeron Ödemeleri</span></a></li>
                    </ul>
                </li>
                @endif

                {{-- Şantiye Yönetimi --}}
                <li>
                    <a href="javascript: void(0);" class="has-arrow {{ request()->routeIs('field.*') ? 'active' : '' }}">
                        <i class="fas fa-hard-hat"></i>
                        <span>Şantiye Yönetimi</span>
                    </a>
                    <ul class="sub-menu">
                        <li><a href="{{ route('index', ['any' => 'field/operations']) }}"><i class="fas fa-people-carry"></i> <span>Saha Operasyonları</span></a></li>
                        @if(pergel_has_module('qhse'))
                        <li>
                            <a href="javascript: void(0);" class="has-arrow {{ request()->routeIs('field.qhse.*') ? 'active' : '' }}"><i class="fas fa-shield-alt"></i> <span>Kalite & İSG (QHSE)</span></a>
                            <ul class="sub-menu">
                                <li><a href="{{ route('index', ['any' => 'field/qhse/quality']) }}" class="{{ request()->routeIs('field.qhse.quality') ? 'active' : '' }}"><i class="fas fa-award"></i> <span>Kalite Kontrol</span></a></li>
                                <li><a href="{{ route('index', ['any' => 'field/qhse/punchlist']) }}" class="{{ request()->routeIs('field.qhse.punchlist') ? 'active' : '' }}"><i class="fas fa-list-ul"></i> <span>Eksik İşler</span></a></li>
                                <li><a href="{{ route('index', ['any' => 'field/qhse/safety']) }}" class="{{ request()->routeIs('field.qhse.safety') ? 'active' : '' }}"><i class="fas fa-user-shield"></i> <span>İş Güvenliği (İSG)</span></a></li>
                            </ul>
                        </li>
                        @endif
                    </ul>
                </li>

                {{-- Kaynak Yönetimi --}}
                 <li>
                    <a href="javascript: void(0);" class="has-arrow {{ request()->routeIs('resources.*') ? 'active' : '' }}">
                        <i class="fas fa-cogs"></i>
                        <span>Kaynak Yönetimi</span>
                    </a>
                    <ul class="sub-menu">
                        <li><a href="{{ route('resources.equipment.dashboard') }}" class="{{ request()->routeIs('resources.equipment.*') ? 'active' : '' }}"><i class="fas fa-truck-monster"></i> <span>Ekipman Parkı</span></a></li>
                    </ul>
                </li>

                {{-- İnsan Kaynakları --}}
                <li>
                    <a href="javascript: void(0);" class="has-arrow {{ request()->routeIs('resources.hr.*') ? 'active' : '' }}">
                        <i class="fas fa-address-card"></i>
                        <span>İnsan Kaynakları</span>
                    </a>
                    <ul class="sub-menu">
                        <li><a href="{{ route('resources.hr.dashboard') }}" class="{{ request()->routeIs('resources.hr.dashboard') ? 'active' : '' }}"><i class="fas fa-tachometer-alt"></i> <span>İK Panosu</span></a></li>
                        <li><a href="{{ route('resources.hr.workforce-planning') }}" class="{{ request()->routeIs('resources.hr.workforce-planning') ? 'active' : '' }}"><i class="fas fa-calendar-check"></i> <span>İş Gücü Planlama</span></a></li>
                        <li><a href="{{ route('resources.hr.employees') }}" class="{{ request()->routeIs('resources.hr.employees') ? 'active' : '' }}"><i class="fas fa-users"></i> <span>Personel Listesi</span></a></li>
                        <li><a href="{{ route('resources.hr.certificates') }}" class="{{ request()->routeIs('resources.hr.certificates') ? 'active' : '' }}"><i class="fas fa-certificate"></i> <span>Sertifika Takibi</span></a></li>
                    </ul>
                </li>

                {{-- Diğer Ana Menüler --}}
                <li><a href="{{ route('index', ['any' => 'closeout']) }}" class="{{ request()->routeIs('closeout.*') ? 'active' : '' }}"><i class="fas fa-flag-checkered"></i><span>Proje Kapanış</span></a></li>
                
                {{-- Tesis Yönetimi & Teknik Destek --}}
                <li>
                    <a href="javascript: void(0);" class="has-arrow {{ request()->routeIs('facility.*') ? 'active' : '' }}">
                        <i class="fas fa-building"></i>
                        <span>Tesis Yönetimi</span>
                    </a>
                    <ul class="sub-menu">
                        <li><a href="{{ route('facility.index') }}" class="{{ request()->routeIs('facility.index') ? 'active' : '' }}"><i class="fas fa-tachometer-alt"></i> <span>Tesis Panosu</span></a></li>
                        <li><a href="{{ route('facility.assets') }}" class="{{ request()->routeIs('facility.assets*') ? 'active' : '' }}"><i class="fas fa-cube"></i> <span>Varlık Yönetimi</span></a></li>
                        <li><a href="{{ route('facility.maintenance') }}" class="{{ request()->routeIs('facility.maintenance') ? 'active' : '' }}"><i class="fas fa-wrench"></i> <span>Bakım & Onarım</span></a></li>
                        <li><a href="{{ route('facility.requests') }}" class="{{ request()->routeIs('facility.requests') ? 'active' : '' }}"><i class="fas fa-headset"></i> <span>Destek Talepleri</span></a></li>
                        @if(pergel_has_module('qr_sla'))
                        <li><a href="{{ route('facility.qr.index') }}" class="{{ request()->routeIs('facility.qr.*') ? 'active' : '' }}"><i class="fas fa-qrcode"></i> <span>QR Kod Yönetimi</span></a></li>
                        <li><a href="{{ route('facility.sla.index') }}" class="{{ request()->routeIs('facility.sla.*') ? 'active' : '' }}"><i class="fas fa-clock"></i> <span>SLA Ayarları</span></a></li>
                        @endif
                        <li><a href="{{ route('facility.reports') }}" class="{{ request()->routeIs('facility.reports') ? 'active' : '' }}"><i class="fas fa-chart-bar"></i> <span>Raporlar</span></a></li>
                    </ul>
                </li>
                
                <li><a href="{{ route('index', ['any' => 'analytics']) }}" class="{{ request()->routeIs('analytics.*') ? 'active' : '' }}"><i class="fas fa-chart-pie"></i><span>Analitik & Raporlar</span></a></li>

                <li class="menu-title">SİSTEM YÖNETİMİ</li>

                {{-- Yardım & Destek --}}
                <li>
                    <a href="javascript: void(0);" class="has-arrow {{ request()->routeIs('help.*') ? 'active' : '' }}">
                        <i class="fas fa-life-ring"></i>
                        <span>Yardım & Destek</span>
                    </a>
                    <ul class="sub-menu">
                        <li>
                            @if(Route::has('help.index'))
                                <a href="{{ route('help.index') }}" class="{{ request()->routeIs('help.index') ? 'active' : '' }}"><i class="fas fa-question-circle"></i> <span>Yardım Ana Sayfası</span></a>
                            @else
                                <a href="{{ route('index', ['any' => 'help']) }}" class="{{ request()->is('help') ? 'active' : '' }}"><i class="fas fa-question-circle"></i> <span>Yardım Ana Sayfası</span></a>
                            @endif
                        </li>

                        <li>
                            @if(Route::has('help.guide'))
                                <a href="{{ route('help.guide') }}" class="{{ request()->routeIs('help.guide') ? 'active' : '' }}"><i class="fas fa-book"></i> <span>Kullanım Kılavuzu</span></a>
                            @else
                                <a href="{{ route('index', ['any' => 'help/guide']) }}" class="{{ request()->is('help/guide') ? 'active' : '' }}"><i class="fas fa-book"></i> <span>Kullanım Kılavuzu</span></a>
                            @endif
                        </li>

                        <li>
                            @if(Route::has('help.videos'))
                                <a href="{{ route('help.videos') }}" class="{{ request()->routeIs('help.videos') ? 'active' : '' }}"><i class="fas fa-video"></i> <span>Video Eğitimleri</span></a>
                            @else
                                <a href="{{ route('index', ['any' => 'help/videos']) }}" class="{{ request()->is('help/videos') ? 'active' : '' }}"><i class="fas fa-video"></i> <span>Video Eğitimleri</span></a>
                            @endif
                        </li>

                        <li>
                            @if(Route::has('help.faq'))
                                <a href="{{ route('help.faq') }}" class="{{ request()->routeIs('help.faq') ? 'active' : '' }}"><i class="fas fa-question"></i> <span>Sık Sorulan Sorular</span></a>
                            @else
                                <a href="{{ route('index', ['any' => 'help/faq']) }}" class="{{ request()->is('help/faq') ? 'active' : '' }}"><i class="fas fa-question"></i> <span>Sık Sorulan Sorular</span></a>
                            @endif
                        </li>

                        <li>
                            @if(Route::has('help.articles'))
                                <a href="{{ route('help.articles') }}" class="{{ request()->routeIs('help.articles') ? 'active' : '' }}"><i class="fas fa-newspaper"></i> <span>Yardım Makaleleri</span></a>
                            @else
                                <a href="{{ route('index', ['any' => 'help/articles']) }}" class="{{ request()->is('help/articles') ? 'active' : '' }}"><i class="fas fa-newspaper"></i> <span>Yardım Makaleleri</span></a>
                            @endif
                        </li>

                        <li>
                            @if(Route::has('help.contact'))
                                <a href="{{ route('help.contact') }}" class="{{ request()->routeIs('help.contact') ? 'active' : '' }}"><i class="fas fa-envelope"></i> <span>İletişim</span></a>
                            @else
                                <a href="{{ route('index', ['any' => 'help/contact']) }}" class="{{ request()->is('help/contact') ? 'active' : '' }}"><i class="fas fa-envelope"></i> <span>İletişim</span></a>
                            @endif
                        </li>
                    </ul>
                </li>

                {{-- Platform Yönetimi --}}
                <li>
                    <a href="javascript: void(0);" class="has-arrow {{ request()->routeIs('admin.*') ? 'active' : '' }}">
                        <i class="fas fa-users-cog"></i>
                        <span>Platform Yönetimi</span>
                    </a>
                    <ul class="sub-menu">
                        <li><a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}"><i class="fas fa-user-shield"></i> <span>Kullanıcılar & Roller</span></a></li>
                        <li><a href="{{ route('admin.license.index') }}" class="{{ request()->routeIs('admin.license.*') ? 'active' : '' }}"><i class="fas fa-key"></i> <span>Yazılım Lisansı</span></a></li>
                        <li><a href="{{ route('index', ['any' => 'admin/standards']) }}" class="{{ request()->routeIs('admin.standards') ? 'active' : '' }}"><i class="fas fa-cogs"></i> <span>Şirket Ayarları</span></a></li>
                        <li><a href="{{ route('admin.system.index') }}" class="{{ request()->routeIs('admin.system.*') ? 'active' : '' }}"><i class="fas fa-history"></i> <span>Sistem Kayıtları</span></a></li>
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
    // Handle menu clicks manually
    document.querySelectorAll('#side-menu .has-arrow').forEach(function(arrow) {
        arrow.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            var parentLi = this.parentElement;
            var submenu = parentLi.querySelector(':scope > .sub-menu');
            var isOpen = parentLi.classList.contains('mm-active') || parentLi.classList.contains('open');

            console.log('Menu clicked:', this.textContent.trim(), 'Currently open:', isOpen);

            if (!isOpen) {
                // Close all siblings at same level
                var siblings = parentLi.parentElement.children;
                for (var i = 0; i < siblings.length; i++) {
                    if (siblings[i] !== parentLi) {
                        siblings[i].classList.remove('mm-active', 'mm-show', 'open');
                        var siblingArrow = siblings[i].querySelector(':scope > .has-arrow');
                        if (siblingArrow) {
                            siblingArrow.setAttribute('aria-expanded', 'false');
                        }
                    }
                }

                // Open this menu
                parentLi.classList.add('mm-active', 'mm-show', 'open');
                this.setAttribute('aria-expanded', 'true');
                console.log('Menu opened:', this.textContent.trim());
            } else {
                // Close this menu and all children
                parentLi.classList.remove('mm-active', 'mm-show', 'open');
                this.setAttribute('aria-expanded', 'false');

                // Close all nested submenus
                var nestedMenus = parentLi.querySelectorAll('.mm-active, .mm-show, .open');
                nestedMenus.forEach(function(nested) {
                    nested.classList.remove('mm-active', 'mm-show', 'open');
                    var nestedArrow = nested.querySelector(':scope > .has-arrow');
                    if (nestedArrow) {
                        nestedArrow.setAttribute('aria-expanded', 'false');
                    }
                });
                console.log('Menu closed:', this.textContent.trim());
            }
        });
    });

    console.log('Manual menu handler initialized for', document.querySelectorAll('#side-menu .has-arrow').length, 'menu items');
});
</script>


