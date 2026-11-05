@yield('css')
<!-- Bootstrap Css -->
<link href="{{ asset('build/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
<!-- Icons Css -->
<link href="{{ asset('build/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
<!-- Remix Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css">
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<!-- Material Design Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css">
<!-- Font Awesome (use v5 to match local/font assets) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">
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
