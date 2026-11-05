<!doctype html>
<html lang="tr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Taşeron Portalı' }}</title>

    <!-- App favicon & Icons -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/scss/app.scss'])

    @stack('styles')
</head>

<body class="portal-body">
<div class="portal-wrapper">
<nav class="navbar navbar-expand-lg navbar-dark portal-navbar">
    <div class="container-xxl">
        <a class="navbar-brand" href="{{ route('subcontractor.dashboard') }}">
            <span class="d-inline-flex align-items-center justify-content-center rounded-3" style="width:42px;height:42px;background:linear-gradient(140deg, rgba(34,211,238,0.18), rgba(59,130,246,0.35));">
                <i class="fa-solid fa-helmet-safety"></i>
            </span>
            <span>Taşeron Portalı</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#portalNavbar" aria-controls="portalNavbar" aria-expanded="false" aria-label="Menüyü aç">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="portalNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('subcontractor.dashboard') ? 'active' : '' }}" href="{{ route('subcontractor.dashboard') }}">Gösterge Paneli</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('subcontractor.services.*') ? 'active' : '' }}" href="{{ route('subcontractor.services.index') }}">Hizmet Kayıtları</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('subcontractor.materials.*') ? 'active' : '' }}" href="{{ route('subcontractor.materials.index') }}">Malzeme Girişleri</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('subcontractor.documents.*') ? 'active' : '' }}" href="{{ route('subcontractor.documents.index') }}">İSG & Personel</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('subcontractor.advance_requests.*') ? 'active' : '' }}" href="{{ route('subcontractor.advance_requests.index') }}">Avans & Ödeme</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('subcontractor.bids.*') ? 'active' : '' }}" href="{{ route('subcontractor.bids.index') }}">İhale & Teklifler</a>
                </li>
            </ul>
            @auth('subcontractor')
                <div class="d-flex align-items-center text-white gap-3">
                    <div class="text-end small text-white-50">
                        <div class="fw-semibold text-white">{{ auth('subcontractor')->user()->name }}</div>
                        <span>{{ auth('subcontractor')->user()->email }}</span>
                    </div>
                    <form method="POST" action="{{ route('subcontractor.logout') }}">
                        @csrf
                        <button class="btn btn-outline-light btn-sm px-3" type="submit">
                            <i class="fa-solid fa-right-from-bracket me-1"></i>Çıkış Yap
                        </button>
                    </form>
                </div>
            @endauth
        </div>
    </div>
</nav>

<main class="portal-main">
    <div class="container-xxl">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <div class="fw-semibold mb-2">Lütfen aşağıdaki hataları düzeltin:</div>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @yield('content')
    </div>
</main>

<footer class="portal-footer text-center">
    <div class="container-xxl">
        &copy; {{ now()->year }} Pergel · Taşeron Portalı. Daha verimli saha yönetimi için tasarlandı.
    </div>
</footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>

</html>
