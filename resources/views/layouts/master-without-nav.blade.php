<!DOCTYPE html>
<html lang="tr"
      data-server-theme="{{ Auth::check() ? (Auth::user()->theme ?? 'santiye') : 'santiye' }}"
      data-server-dark="{{ Auth::check() ? (Auth::user()->dark_mode ? 'true' : 'false') : 'false' }}"
      data-theme="{{ Auth::check() ? (Auth::user()->theme ?? 'santiye') : 'santiye' }}">

<head>
    <meta charset="utf-8" />
    <title> @yield('title') | Pergel - Şantiye & Gayrimenkul Portalı</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content="Premium Çok Amaçlı Yönetim ve Panel Şablonu" name="description" />
    <meta content="Erdem Eroğlu" name="author" />
    <!-- App favicon & Icons -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}">

    <!-- include head css -->
    @include('layouts.head-css')

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
                var isDarkPalette = (theme === 'santiye' || theme === 'industrial' || dark === true);
                document.documentElement.setAttribute('data-theme', theme);
                document.documentElement.setAttribute('data-dark', String(dark));
                if (isDarkPalette) { document.documentElement.setAttribute('data-bs-theme', 'dark'); }
                else { document.documentElement.setAttribute('data-bs-theme', 'light'); }
            } catch(e){}
        })();
    </script>
</head>

<body data-is-home="{{ (request()->is('/') || request()->is('dashboard')) ? 'true' : 'false' }}">

    @yield('content')

    <!-- customizer -->
    @include('layouts.right-sidebar')

    <!-- vendor-scripts -->
    @include('layouts.vendor-scripts')

</body>

</html>
