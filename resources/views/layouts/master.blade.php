<!DOCTYPE html>
<html lang="tr"
      data-server-theme="{{ auth()->check() && !empty(auth()->user()->theme) ? auth()->user()->theme : '' }}"
      data-server-dark="{{ Auth::check() ? (Auth::user()->dark_mode ? 'true' : 'false') : 'false' }}"
      data-theme="{{ Auth::check() && !empty(Auth::user()->theme) ? Auth::user()->theme : 'santiye' }}">

<head>
    <meta charset="utf-8" />
    <title> @yield('title') | Pergel - Şantiye & Gayrimenkul Portalı</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Pergel">
    <meta name="theme-color" content="#0f172a">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content="Pergel - Şantiye ve Gayrimenkul Portalı" name="description" />
    <meta content="Erdem Eroğlu" name="author" />
    {{-- Page specific meta (Open Graph / Twitter) --}}
    @stack('meta')
    <!-- App favicon & Icons -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">

    <!-- include head css -->
    @include('layouts.head-css')
    {{-- Allow pages to push or yield additional styles --}}
    @yield('styles')
    @stack('styles')

    {{-- Tema Sistemi: FOUC önlemek için head'de erken başlat --}}
    <script>
        (function(){
            try {
                var serverTheme = document.documentElement.getAttribute('data-server-theme');
                var serverDark  = document.documentElement.getAttribute('data-server-dark') === 'true';
                var lsTheme = localStorage.getItem('pergel_theme');
                var lsDark  = localStorage.getItem('pergel_dark');
                var theme = (serverTheme && serverTheme !== '') ? serverTheme : (lsTheme || 'santiye');
                var dark  = lsDark !== null ? (lsDark === 'true') : (serverTheme ? serverDark : false);
                var isDarkPalette = (dark === true);
                document.documentElement.setAttribute('data-theme', theme);
                document.documentElement.setAttribute('data-dark', String(dark));
                if (isDarkPalette) { document.documentElement.setAttribute('data-bs-theme', 'dark'); }
                else { document.documentElement.setAttribute('data-bs-theme', 'light'); }
            } catch(e){}
        })();
    </script>
    {{-- Global Layout & Glass Effect — CSS Custom Properties Sistemi --}}
    <style>
        /* ── Body arka plan (her temaya göre değişir) ── */
        [data-theme] body {
            background: var(--pt-body-bg, #0f172a);
            color: var(--pt-body-color, #e2e8f0);
            font-family: var(--pt-font-sans, 'Inter', sans-serif);
        }

        /* ── Page Content — Glass efekti, CSS var bazlı ── */
        [data-theme] .page-content {
            background: var(--pt-page-content-bg, rgba(255,255,255,0.85)) !important;
            backdrop-filter: blur(var(--pt-glass-blur, 8px));
            -webkit-backdrop-filter: blur(var(--pt-glass-blur, 8px));
            border-radius: 20px 0 0 0;
            box-shadow: var(--pt-page-content-shadow, 0 4px 20px rgba(0,0,0,0.05)) !important;
            border: 1px solid var(--pt-page-content-border, rgba(255,255,255,0.8)) !important;
            min-height: calc(100vh - 120px);
            color: var(--pt-body-color) !important;
        }

        /* ── Kartlar ── */
        [data-theme] .card:not([style*="background"]) {
            background: var(--pt-card-bg, rgba(255,255,255,0.9)) !important;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid var(--pt-card-border, rgba(226,232,240,0.6)) !important;
            box-shadow: var(--pt-card-shadow, 0 4px 20px rgba(0,0,0,0.08)) !important;
            border-radius: var(--pt-card-radius, 12px) !important;
            color: var(--pt-body-color) !important;
        }

        [data-theme] .card-header {
            background: var(--pt-card-header-bg, transparent) !important;
            border-bottom: 1px solid var(--pt-card-border) !important;
            color: var(--pt-heading-color) !important;
            font-weight: 600;
            font-family: var(--pt-font-heading, inherit);
        }

        [data-theme] .card-body {
            color: var(--pt-body-color) !important;
        }

        [data-theme] .card-title {
            color: var(--pt-heading-color) !important;
            font-family: var(--pt-font-heading) !important;
            font-weight: var(--pt-font-weight-heading, 700) !important;
        }

        /* ── Başlıklar (Hero ve text-white muaf) ── */
        [data-theme] h1:not(.text-white):not(.text-warning):not(.text-success):not(.text-info):not(.text-danger),
        [data-theme] h2:not(.text-white):not(.text-warning):not(.text-success):not(.text-info):not(.text-danger),
        [data-theme] h3:not(.text-white):not(.text-warning):not(.text-success):not(.text-info):not(.text-danger),
        [data-theme] h4:not(.text-white):not(.text-warning):not(.text-success):not(.text-info):not(.text-danger),
        [data-theme] h5:not(.text-white):not(.text-warning):not(.text-success):not(.text-info):not(.text-danger),
        [data-theme] h6:not(.text-white):not(.text-warning):not(.text-success):not(.text-info):not(.text-danger) {
            color: var(--pt-heading-color) !important;
            font-family: var(--pt-font-heading) !important;
        }

        /* ── HERO BANNER & TEXT-WHITE COLOR PROTECTION ── */
        [data-theme] .text-white,
        [data-theme] .text-white h1, [data-theme] .text-white h2,
        [data-theme] .text-white h3, [data-theme] .text-white h4,
        [data-theme] .text-white p, [data-theme] .text-white span,
        [data-theme] .sales-acc-hero-banner h1, [data-theme] .sales-acc-hero-banner h2,
        [data-theme] .sales-acc-hero-banner h3, [data-theme] .sales-acc-hero-banner h4,
        [data-theme] .dashboard-hero-banner h1, [data-theme] .dashboard-hero-banner h2 {
            color: #ffffff !important;
        }

        [data-theme] .sales-acc-hero-banner p,
        [data-theme] .dashboard-hero-banner p {
            color: #f1f5f9 !important;
        }

        /* ── Alert ── */
        [data-theme] .alert:not(.alert-success):not(.alert-danger):not(.alert-warning):not(.alert-info) {
            background: var(--pt-card-bg) !important;
            backdrop-filter: blur(10px);
            border: 1px solid var(--pt-card-border) !important;
            color: var(--pt-body-color) !important;
            border-radius: var(--pt-card-radius, 8px) !important;
        }

        /* ── Text muted ── */
        [data-theme] .text-muted {
            color: var(--pt-text-muted) !important;
        }

        /* ── Breadcrumb ── */
        [data-theme] .breadcrumb {
            background: var(--pt-card-bg) !important;
            backdrop-filter: blur(8px);
            border-radius: var(--pt-btn-radius, 6px);
        }

        [data-theme] .breadcrumb-item a {
            color: var(--pt-primary) !important;
        }

        [data-theme] .breadcrumb-item.active {
            color: var(--pt-text-muted) !important;
        }

        /* ── Page title kutusu ── */
        [data-theme] .page-title-box {
            background: var(--pt-card-bg) !important;
            backdrop-filter: blur(10px);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid var(--pt-card-border) !important;
            color: var(--pt-heading-color) !important;
        }

        [data-theme] .page-title-box h4 {
            color: var(--pt-heading-color) !important;
            font-family: var(--pt-font-heading) !important;
            font-size: 1.2rem;
            font-weight: var(--pt-font-weight-heading, 700);
            letter-spacing: -0.01em;
        }

        [data-theme] .main-content {
            backdrop-filter: blur(1px);
            position: relative;
            color: var(--pt-body-color) !important;
        }

        [data-theme] .main-content .card-body,
        [data-theme] .main-content .kpi-card .card-body {
            color: var(--pt-body-color) !important;
        }

        /* Light-variant bileşenler */
        [data-theme] .main-content .avatar-light,
        [data-theme] .main-content .avatar-label-light,
        [data-theme] .main-content .badge-label-light,
        [data-theme] .main-content .badge-light,
        [data-theme] .main-content .btn-label-light,
        [data-theme] .main-content .btn-light {
            color: var(--pt-body-color) !important;
            background-color: var(--pt-table-stripe-bg, rgba(248,250,252,1)) !important;
        }

        /* ── Tablo ── */
        [data-theme] .table {
            color: var(--pt-body-color) !important;
            border-color: var(--pt-table-border) !important;
        }

        [data-theme] .table th,
        [data-theme] .table td {
            color: var(--pt-body-color) !important;
            border-color: var(--pt-table-border) !important;
        }

        [data-theme] .table thead th {
            background-color: var(--pt-table-head-bg, var(--pt-table-header-bg, #f8fafc)) !important;
            color: var(--pt-heading-color) !important;
            border-color: var(--pt-table-border) !important;
            font-family: var(--pt-font-heading) !important;
        }

        [data-theme] .table-striped > tbody > tr:nth-of-type(odd) > * {
            background-color: var(--pt-table-stripe-bg) !important;
            color: var(--pt-body-color) !important;
        }

        [data-theme] .table-hover > tbody > tr:hover > * {
            background-color: var(--pt-dropdown-item-hover-bg) !important;
        }

        /* ── Form elemanları ── */
        [data-theme] .form-control,
        [data-theme] .form-select {
            background-color: var(--pt-input-bg) !important;
            border-color: var(--pt-input-border) !important;
            border-radius: var(--pt-input-radius) !important;
            color: var(--pt-input-text) !important;
            font-family: var(--pt-font-sans) !important;
        }

        [data-theme] .form-control:focus,
        [data-theme] .form-select:focus {
            background-color: var(--pt-input-focus-bg, rgba(255,255,255,0.95)) !important;
            border-color: var(--pt-input-focus-border, #3b82f6) !important;
            color: var(--pt-input-focus-text, #111827) !important;
            box-shadow: 0 0 0 3px var(--pt-input-focus-shadow, rgba(59,130,246,0.15)) !important;
        }

        [data-theme] .form-control::placeholder {
            color: var(--pt-input-placeholder) !important;
        }

        [data-theme] .form-control:disabled,
        [data-theme] .form-select:disabled {
            background-color: var(--pt-table-stripe-bg) !important;
            color: var(--pt-text-muted) !important;
        }

        [data-theme] .form-label {
            color: var(--pt-label-color) !important;
            font-weight: 500;
            font-family: var(--pt-font-sans) !important;
        }

        [data-theme] .form-check-input {
            border-color: var(--pt-input-border) !important;
            background-color: var(--pt-input-bg) !important;
        }

        [data-theme] .form-check-input:checked {
            background-color: var(--pt-primary) !important;
            border-color: var(--pt-primary) !important;
        }

        /* Input group */
        [data-theme] .input-group-text {
            background-color: var(--pt-card-header-bg) !important;
            border-color: var(--pt-input-border) !important;
            color: var(--pt-label-color) !important;
        }

        /* ── Modal ── */
        [data-theme] .modal-content {
            background-color: var(--pt-modal-bg) !important;
            border: 1px solid var(--pt-modal-border) !important;
            color: var(--pt-body-color) !important;
            border-radius: var(--pt-card-radius) !important;
            font-family: var(--pt-font-sans) !important;
        }

        [data-theme] .modal-header {
            border-bottom-color: var(--pt-modal-border) !important;
        }

        [data-theme] .modal-footer {
            border-top-color: var(--pt-modal-border) !important;
        }

        [data-theme] .modal-title {
            color: var(--pt-heading-color) !important;
            font-family: var(--pt-font-heading) !important;
        }

        [data-theme] .offcanvas {
            background-color: var(--pt-modal-bg) !important;
            color: var(--pt-body-color) !important;
        }

        /* ── Dropdown (global) ── */
        [data-theme] .dropdown-menu {
            background-color: var(--pt-dropdown-bg) !important;
            border-color: var(--pt-dropdown-border) !important;
            box-shadow: var(--pt-dropdown-shadow) !important;
            border-radius: var(--pt-card-radius) !important;
        }

        [data-theme] .dropdown-item {
            color: var(--pt-dropdown-item-color) !important;
            font-family: var(--pt-font-sans) !important;
        }

        [data-theme] .dropdown-item:hover,
        [data-theme] .dropdown-item:focus {
            background-color: var(--pt-dropdown-item-hover-bg) !important;
            color: var(--pt-dropdown-item-hover-color) !important;
        }

        [data-theme] .dropdown-divider {
            border-top-color: var(--pt-dropdown-border) !important;
        }

        /* ── Pagination ── */
        nav svg, .pagination svg, svg.w-5, svg.h-5, svg.w-6, svg.h-6,
        svg[class*="w-"], svg[class*="h-"] {
            width: 1.25rem !important;
            height: 1.25rem !important;
            max-width: 20px !important;
            max-height: 20px !important;
            display: inline-block !important;
            vertical-align: middle !important;
            flex-shrink: 0 !important;
        }

        .pagination {
            display: flex !important;
            padding-left: 0 !important;
            list-style: none !important;
            border-radius: 0.375rem !important;
            margin-bottom: 0 !important;
            gap: 4px !important;
        }

        .page-link {
            position: relative !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            padding: 0.375rem 0.75rem !important;
            font-size: 0.875rem !important;
            text-decoration: none !important;
            border-radius: var(--pt-btn-radius, 0.375rem) !important;
            transition: color 0.15s ease, background-color 0.15s ease, border-color 0.15s ease !important;
        }

        [data-theme] .page-link {
            color: var(--pt-primary) !important;
            background-color: var(--pt-card-bg) !important;
            border: 1px solid var(--pt-card-border) !important;
        }

        [data-theme] .page-link:hover {
            color: var(--pt-dropdown-item-hover-color) !important;
            background-color: var(--pt-dropdown-item-hover-bg) !important;
            border-color: var(--pt-primary) !important;
        }

        [data-theme] .page-item.active .page-link {
            z-index: 3;
            color: var(--pt-btn-primary-text, #ffffff) !important;
            background-color: var(--pt-primary) !important;
            border-color: var(--pt-primary) !important;
        }

        [data-theme] .page-item.disabled .page-link {
            color: var(--pt-text-subtle) !important;
            pointer-events: none;
            background-color: var(--pt-table-stripe-bg) !important;
            border-color: var(--pt-card-border) !important;
        }

        /* ── Nav Tabs ── */
        [data-theme] .nav-tabs {
            border-bottom-color: var(--pt-card-border) !important;
        }

        [data-theme] .nav-tabs .nav-link {
            color: var(--pt-body-color) !important;
            border-radius: var(--pt-btn-radius, 6px) var(--pt-btn-radius, 6px) 0 0 !important;
        }

        [data-theme] .nav-tabs .nav-link:hover {
            border-color: var(--pt-card-border) !important;
            color: var(--pt-primary) !important;
        }

        [data-theme] .nav-tabs .nav-link.active {
            background-color: var(--pt-card-bg) !important;
            border-color: var(--pt-card-border) !important;
            color: var(--pt-primary) !important;
        }

        /* ── Nav Pills ── */
        [data-theme] .nav-pills .nav-link {
            color: var(--pt-body-color) !important;
            border-radius: var(--pt-badge-radius, 20px) !important;
        }

        [data-theme] .nav-pills .nav-link.active {
            background-color: var(--pt-primary) !important;
            color: var(--pt-btn-primary-text, #fff) !important;
        }

        /* ── List Group ── */
        [data-theme] .list-group-item {
            background-color: var(--pt-card-bg) !important;
            border-color: var(--pt-card-border) !important;
            color: var(--pt-body-color) !important;
        }

        [data-theme] .list-group-item:hover {
            background-color: var(--pt-dropdown-item-hover-bg) !important;
        }

        [data-theme] .list-group-item.active {
            background-color: var(--pt-primary) !important;
            border-color: var(--pt-primary) !important;
            color: var(--pt-btn-primary-text, #ffffff) !important;
        }

        /* ── Badge ── */
        [data-theme] .badge {
            border-radius: var(--pt-badge-radius, 6px) !important;
            font-family: var(--pt-font-sans) !important;
        }

        /* ── Progress Bar ── */
        [data-theme] .progress {
            background-color: var(--pt-table-stripe-bg) !important;
            border-radius: var(--pt-badge-radius, 6px) !important;
        }

        [data-theme] .progress-bar {
            background-color: var(--pt-primary) !important;
        }

        /* ── Body arka plan dekorasyon ── */
        [data-theme] body::before {
            content: '';
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: var(--pt-body-overlay,
                radial-gradient(circle at 20% 80%, rgba(59,130,246,0.04) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(139,92,246,0.03) 0%, transparent 50%)
            );
            pointer-events: none;
            z-index: -1;
        }

        /* ── Mobil optimizasyonlar ── */
        @media (max-width: 768px) {
            .btn { min-height: 44px; font-size: 16px; padding: 12px 16px; }
            .form-control, .form-select { min-height: 44px; font-size: 16px; padding: 12px 16px; }
            .form-check-input { width: 20px; height: 20px; margin-top: 0.25rem; }
            .form-check-label { font-size: 16px; padding-left: 8px; }
            .card { margin-bottom: 20px; }
            .page-title-box { padding: 20px 0; }
            .page-title-box h4 { font-size: 1.25rem; }
            .dropdown-item { padding: 12px 16px; font-size: 16px; }
            .modal-dialog { margin: 10px; }
            .table td, .table th { padding: 12px 8px; font-size: 14px; }
            .img-thumbnail { max-width: 80px; max-height: 80px; }
            input[type="file"] { font-size: 16px; }
            .input-group .btn { min-width: 50px; }
        }

        /* Dokunmatik hover iptal */
        @media (hover: none) and (pointer: coarse) {
            .btn:hover { transform: none; }
            .card:hover { box-shadow: var(--pt-card-shadow); }
        }

        /* Küçük ekranlarda ikon boyutları */
        @media (max-width: 576px) {
            .mdi { font-size: 1.25rem; }
            .avatar-sm .avatar-title { font-size: 1rem; }
        }
    </style>
</head>

<body data-is-home="{{ (request()->is('/') || request()->is('dashboard')) ? 'true' : 'false' }}">
    <!-- Begin page -->
    <div id="layout-wrapper">
        <!-- topbar -->
        @include('layouts.topbar')
        <!-- sidebar components -->
        @include('layouts.sidebar')

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">

            <div class="page-content">
                <div class="container-fluid">

                    <!-- start page title -->
                    @if(!View::hasSection('suppress_page_title'))
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0">@yield('title')</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">@yield('topbar-title')</a>
                                            </li>
                                            <li class="breadcrumb-item active">@yield('title')</li>
                                        </ol>
                                    </div>

                                </div>
                            </div>
                        </div>
                    @endif
                    <!-- end page title -->

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    @yield('content')
                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->

            <!-- footer -->
            @include('layouts.footer')

        </div>
        <!-- end main content-->
    </div>
    <!-- END layout-wrapper -->

    <!-- customizer -->
    @include('layouts.right-sidebar')

    <!-- vendor-scripts -->
    @include('layouts.vendor-scripts')

    {{-- Select2 (used by stock selects) --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    @auth
        <script>
            // server-side page_locked value available to client
            window.SERVER_PAGE_LOCKED = {{ Auth::user()->page_locked ? '1' : '0' }};
        </script>
        @include('layouts.lock-overlay')
    @endauth

    <!-- Accordion JS (load after vendor scripts) -->
    <script src="{{ asset('js/accordion.js') }}"></script>

    <!-- Notification System JS -->
    <script src="{{ asset('js/notifications.js') }}"></script>

    <!-- MetisMenu handles sidebar menu functionality -->

    @yield('scripts')
    @yield('script')

    @stack('scripts')
    <script>
        // Global safe fetch helper (used by notification system)
        window.safeJsonFetch = function(url, options = {}){
            return fetch(url, options).then(res => {
                const ct = res.headers.get('content-type') || '';
                if (!res.ok) throw new Error('HTTP ' + res.status);
                if (!ct.includes('application/json')) throw new Error('Unexpected content-type ' + ct);
                return res.json();
            });
        };
    </script>

    {{-- Persistent Focus Music & Radio Player --}}
    @include('layouts.focus-radio-player')

    {{-- Native Mobile App Bottom Navigation Dock & Drawer --}}
    @include('layouts.mobile-app-dock')
</body>

</html>

