<!DOCTYPE html>
<html lang="tr" data-theme="santiye" data-dark="false">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('code') — @yield('title') | Pergel</title>
    <link href="{{ asset('build/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('build/css/app.min.css') }}" rel="stylesheet">
    <link href="{{ asset('libs/remixicon/remixicon.css') }}" rel="stylesheet">
    <link href="{{ asset('css/eye-comfort.css') }}?v=6" rel="stylesheet">
    <link href="{{ asset('css/icon-system.css') }}?v=3" rel="stylesheet">
    <script>
        (function(){ try {
            var t = localStorage.getItem('pergel_theme') || 'santiye';
            var d = localStorage.getItem('pergel_dark') === 'true';
            var h = document.documentElement;
            h.setAttribute('data-theme', t); h.setAttribute('data-dark', String(d));
            h.setAttribute('data-bs-theme', d ? 'dark' : 'light');
        } catch(e){} })();
    </script>
    <style>
        body { min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 24px; }
        .err-card { max-width: 520px; width: 100%; text-align: center; padding: 2.5rem 2rem 2rem; }
        .err-code { font-size: .8rem; letter-spacing: .14em; font-weight: 700; color: var(--pt-primary); text-transform: uppercase; }
        .err-card h1 { font-size: 1.6rem; margin: .35rem 0 .6rem; }
        .err-card p { color: var(--pt-text-muted); margin-bottom: 1.5rem; }
    </style>
</head>
<body>
    <main class="card err-card">
        <x-illustration :name="trim($__env->yieldContent('illustration', 'error'))" :width="240" />
        <div class="err-code mt-3">Hata @yield('code')</div>
        <h1>@yield('title')</h1>
        <p>@yield('message')</p>
        <div class="d-flex gap-2 justify-content-center flex-wrap">
            <a href="{{ url('/') }}" class="btn btn-primary"><i class="ri-home-4-line"></i> Ana sayfa</a>
            <a href="javascript:history.back()" class="btn btn-outline-secondary"><i class="ri-arrow-left-line"></i> Geri dön</a>
        </div>
    </main>
</body>
</html>
