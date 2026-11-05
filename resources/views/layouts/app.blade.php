<!doctype html>
<html lang="tr">

<head>
    <meta charset="utf-8" />
    <title>@yield('title') | İkon İnşaat Yönetim Sistemi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="İkon İnşaat Yönetim Sistemi - Ekipman Parkı Modülü" name="description" />
    <meta content="İkon" name="author" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <!-- App favicon & Icons -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">

    <!-- PWA Manifest -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">

    <!-- PWA Meta Tags -->
    <meta name="theme-color" content="#2563eb">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="Tesis Yönetim">
    <meta name="mobile-web-app-capable" content="yes">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Material Design Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.2.96/css/materialdesignicons.min.css">

    <!-- Custom CSS -->
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
        }

        .main-content {
            margin-left: 250px;
            padding: 20px;
            min-height: 100vh;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            height: 100vh;
            background: #343a40;
            color: white;
            padding: 20px 0;
            overflow-y: auto;
        }

        .sidebar .nav-link {
            color: rgba(255,255,255,.75);
            padding: 10px 20px;
            display: block;
            text-decoration: none;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background: rgba(255,255,255,.1);
        }

        .topbar {
            height: 60px;
            background: white;
            border-bottom: 1px solid #dee2e6;
            position: fixed;
            top: 0;
            right: 0;
            left: 250px;
            z-index: 1000;
            padding: 0 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .content-wrapper {
            margin-top: 80px;
            padding: 20px;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,.08);
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
        }

        .table th {
            background: #f8f9fa;
            border-top: none;
            font-weight: 600;
        }

        .badge {
            font-size: 0.75em;
        }

        /* Mobil uyumluluk */
        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
            }

            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .topbar {
                left: 0;
            }

            .content-wrapper {
                margin-top: 60px;
            }
        }
    </style>

    @stack('styles')
</head>

<body>
    <!-- Sidebar -->
    <nav class="sidebar">
        <div class="px-3 mb-4">
            <h4 class="text-white mb-4">
                <i class="mdi mdi-office-building me-2"></i>
                İkon İnşaat
            </h4>
        </div>

        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('index') ? 'active' : '' }}" href="{{ route('index') }}">
                    <i class="mdi mdi-view-dashboard me-2"></i>
                    Ana Sayfa
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('resources.equipment.*') ? 'active' : '' }}" href="{{ route('resources.equipment.dashboard') }}">
                    <i class="mdi mdi-truck me-2"></i>
                    Ekipman Parkı
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('field.operations') }}">
                    <i class="mdi mdi-hard-hat me-2"></i>
                    Saha Operasyonları
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('portfolio.management.index') }}">
                    <i class="mdi mdi-briefcase me-2"></i>
                    Portföy Yönetimi
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('cost.budget') }}">
                    <i class="mdi mdi-cash me-2"></i>
                    Maliyet & Bütçe
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('help.*') ? 'active' : '' }}" href="{{ route('help.index') }}">
                    <i class="mdi mdi-help-circle me-2"></i>
                    Yardım
                </a>
            </li>
        </ul>
    </nav>

    <!-- Topbar -->
    <header class="topbar">
        <div>
            <button class="btn btn-outline-secondary d-md-none" onclick="toggleSidebar()">
                <i class="mdi mdi-menu"></i>
            </button>
        </div>

        <div class="d-flex align-items-center">
            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="mdi mdi-account-circle me-1"></i>
                    {{ auth()->user()->name ?? 'Kullanıcı' }}
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#"><i class="mdi mdi-account me-1"></i> Profil</a></li>
                    <li><a class="dropdown-item" href="#"><i class="mdi mdi-cog me-1"></i> Ayarlar</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="{{ route('logout') }}">
                        <i class="mdi mdi-logout me-1"></i> Çıkış
                    </a></li>
                </ul>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <div class="content-wrapper">
            @yield('content')
        </div>
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <!-- Custom Scripts -->
    <script>
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('show');
        }

        // Mobil cihazlarda sidebar'ı otomatik kapat
        if (window.innerWidth <= 768) {
            document.addEventListener('click', function(e) {
                const sidebar = document.querySelector('.sidebar');
                const toggleBtn = e.target.closest('button[onclick="toggleSidebar()"]');

                if (!sidebar.contains(e.target) && !toggleBtn && sidebar.classList.contains('show')) {
                    sidebar.classList.remove('show');
                }
            });
        }
    </script>

    <!-- PWA Service Worker Registration -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/sw.js')
                    .then(function(registration) {
                        console.log('Service Worker registered successfully:', registration.scope);
                    })
                    .catch(function(error) {
                        console.log('Service Worker registration failed:', error);
                    });
            });
        }
    </script>

    @auth
        <script>
            window.SERVER_PAGE_LOCKED = {{ Auth::user()->page_locked ? '1' : '0' }};
        </script>
        @include('layouts.lock-overlay')
    @endauth

    @stack('scripts')
</body>

</html>
