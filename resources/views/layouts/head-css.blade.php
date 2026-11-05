@yield('css')
<!-- Bootstrap Css -->
<link href="{{ asset('build/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
<!-- Icons Css -->
<link href="{{ asset('build/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
<!-- Remix Icons -->
<link rel="stylesheet" href="{{ asset('libs/remixicon/remixicon.css') }}">
<!-- Material Design Icons -->
<link rel="stylesheet" href="{{ asset('libs/mdi/css/materialdesignicons.min.css') }}">
<!-- Font Awesome 7 (yerel) -->
<link rel="stylesheet" href="{{ asset('libs/fontawesome/css/all.min.css') }}">
<!-- SweetAlert2 -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<!-- App Css-->
<link href="{{ asset('build/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />

<!-- Accordion CSS for left sidebar -->
<link href="{{ asset('css/accordion.css') }}" rel="stylesheet" type="text/css" />

<!-- Leaflet CSS (harita gösterimi için) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css" crossorigin="anonymous" />

<!-- Page lock CSS -->
<link href="{{ URL::asset('css/page-lock.css') }}" rel="stylesheet" type="text/css" />

<!-- Notification System CSS -->
<link href="{{ URL::asset('css/notifications.css') }}" rel="stylesheet" type="text/css" />

<!-- Visual Enhancements CSS -->
<link href="{{ URL::asset('css/visual-enhancements.css') }}" rel="stylesheet" type="text/css" />

<!-- Native Mobile App Experience CSS -->
<link href="{{ URL::asset('css/mobile-app.css') }}" rel="stylesheet" type="text/css" />

<!-- Eye Comfort (göz konforu) — tema değişkenlerini yumuşatır, en son yüklenir -->
<link href="{{ URL::asset('css/eye-comfort.css') }}?v=20261105a" rel="stylesheet" type="text/css" />
<!-- İkon sistemi & ilüstrasyonlar -->
<link href="{{ URL::asset('css/icon-system.css') }}?v=20261105a" rel="stylesheet" type="text/css" />
<!-- Sidebar & navbar kabuğu -->
<link href="{{ URL::asset('css/shell-theme.css') }}?v=20261105a" rel="stylesheet" type="text/css" />
