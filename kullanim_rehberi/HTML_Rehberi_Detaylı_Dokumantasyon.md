# 📚 PERGEL HTML Kullanım Rehberi - Detaylı Dokümantasyon

## İçindekiler

1. [Giriş](#giriş)
2. [Proje Yapısı](#proje-yapısı)
3. [HTML Temelleri](#html-temelleri)
4. [CSS ve Temasıyla](#css-ve-temasıyla)
5. [Form Bileşenleri](#form-bileşenleri)
6. [Best Practices](#best-practices)
7. [Sık Karşılaşılan Sorunlar](#sık-karşılaşılan-sorunlar)

---

## Giriş

### PERGEL HTML Sistemi Nedir?

PERGEL, Laravel 11.31 tabanlı modern bir kurum yönetim sistemidir. HTML yapısı Bootstrap 5, CSS utilities ve Laravel Blade şablonlarıyla oluşturulmuştur.

### Temel Teknolojiler

| Teknoloji | Versiyon | Açıklama |
|-----------|----------|----------|
| **Laravel** | 11.31 | Web framework'ü |
| **Bootstrap** | 5.x | CSS framework'ü |
| **Blade** | - | Template engine'i |
| **Font Awesome** | 6.x | İkon kütüphanesi |
| **PHP** | 8.2+ | Server-side dil |

### Rehberin Hedefi

Bu rehber aşağıdaki konuları kapsamaktadır:
- ✅ HTML5 ve Blade şablonları
- ✅ Bootstrap 5 grid sistemi
- ✅ CSS utilities ve tema sistemi
- ✅ Form tasarımı ve validasyonu
- ✅ Responsive tasarım
- ✅ Best practices ve patterns

---

## Proje Yapısı

### Klasör Düzeni

```
pergel03/
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   │   ├── master.blade.php      (Ana şablon)
│   │   │   ├── topbar.blade.php      (Üst bar)
│   │   │   ├── sidebar.blade.php     (Sol menü)
│   │   │   ├── head-css.blade.php    (CSS dosyaları)
│   │   │   └── vendor-scripts.blade.php (JS dosyaları)
│   │   ├── projects/                 (Proje modülü)
│   │   ├── finance/                  (Finans modülü)
│   │   ├── crm/                      (CRM modülü)
│   │   ├── field/                    (Alan operasyonları)
│   │   └── components/               (Reusable bileşenler)
│   └── css/
│       ├── app.css                   (Genel stiller)
│       └── themes/                   (Tema dosyaları)
├── public/
│   ├── css/                          (Derlenmiş CSS)
│   ├── js/                           (JavaScript dosyaları)
│   └── images/                       (Resim dosyaları)
└── app/
    ├── Http/
    │   ├── Controllers/              (80+ Controller)
    │   └── Middleware/               (Middleware'ler)
    └── Models/                       (200+ Model)
```

### Ana Dosyalar

#### master.blade.php
Tüm sayfaların temeli olan ana şablon dosyası.

```php
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>@yield('title') | Pergel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- CSS Dosyaları -->
    @include('layouts.head-css')
    @stack('styles')
</head>

<body>
    <div id="layout-wrapper">
        <!-- Üst Bar -->
        @include('layouts.topbar')
        
        <!-- Sol Menü -->
        @include('layouts.sidebar')
        
        <!-- Ana İçerik -->
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    <!-- Sayfa Başlığı -->
                    @if(!View::hasSection('suppress_page_title'))
                        <div class="page-title-box">
                            <h4>@yield('title')</h4>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">@yield('topbar-title')</li>
                                <li class="breadcrumb-item active">@yield('title')</li>
                            </ol>
                        </div>
                    @endif
                    
                    <!-- İçerik -->
                    @yield('content')
                </div>
            </div>
        </div>
        
        <!-- Alt Bilgi -->
        @include('layouts.footer')
    </div>

    <!-- Vendor Scripts -->
    @include('layouts.vendor-scripts')
    @stack('scripts')
</body>
</html>
```

---

## HTML Temelleri

### 1. Yeni Sayfa Oluşturma

```php
@extends('layouts.master')

@section('title', 'Sayfanın Başlığı')
@section('topbar-title', 'Modül Adı')

@section('content')
    <div class="row">
        <div class="col-12">
            <!-- İçerik Buraya Gelir -->
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Sayfa Spesifik Stiller */
    </style>
@endpush

@push('scripts')
    <script>
        // Sayfa Spesifik Script
    </script>
@endpush
```

### 2. Bootstrap Grid Sistemi

#### 12 Sütunlu Sistem
```html
<!-- İki Sütun -->
<div class="row">
    <div class="col-lg-8">
        <h3>Ana İçerik (8 sütun)</h3>
    </div>
    <div class="col-lg-4">
        <h3>Yan Panel (4 sütun)</h3>
    </div>
</div>

<!-- Üç Eşit Sütun -->
<div class="row">
    <div class="col-md-4">
        <div class="card">Card 1</div>
    </div>
    <div class="col-md-4">
        <div class="card">Card 2</div>
    </div>
    <div class="col-md-4">
        <div class="card">Card 3</div>
    </div>
</div>

<!-- Dört Sütun Dashboard -->
<div class="row">
    @for($i = 1; $i <= 4; $i++)
        <div class="col-md-6 col-lg-3">
            <div class="card">
                <div class="card-body">
                    <h5>KPI {{ $i }}</h5>
                    <p class="text-primary h3">1,234</p>
                </div>
            </div>
        </div>
    @endfor
</div>
```

#### Responsive Breakpoints
```html
<!-- Mobilde tam genişlik, tablete 6 sütun, masaüstünde 4 sütun -->
<div class="col-12 col-md-6 col-lg-4">
    İçerik
</div>

<!-- Başka bir örnek -->
<div class="col-sm-6 col-md-4 col-lg-3 col-xl-2">
    İçerik
</div>
```

### 3. Sık Kullanılan Bileşenler

#### Kartlar
```html
<div class="card">
    <img src="image.jpg" class="card-img-top" alt="...">
    <div class="card-body">
        <h5 class="card-title">Kart Başlığı</h5>
        <p class="card-text">Kart açıklaması...</p>
        
        <div class="card-footer">
            <a href="#" class="btn btn-primary">Detaylar</a>
            <a href="#" class="btn btn-secondary">İptal</a>
        </div>
    </div>
</div>
```

#### Tablolar
```html
<table class="table table-striped table-hover">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Ad</th>
            <th>Durum</th>
            <th>İşlemler</th>
        </tr>
    </thead>
    <tbody>
        @foreach($items as $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td>{{ $item->name }}</td>
                <td>
                    @if($item->status == 'active')
                        <span class="badge bg-success">Aktif</span>
                    @else
                        <span class="badge bg-secondary">Pasif</span>
                    @endif
                </td>
                <td>
                    <a href="..." class="btn btn-sm btn-primary">Düzenle</a>
                    <a href="..." class="btn btn-sm btn-danger">Sil</a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
```

#### Modals
```html
<!-- Modal Açma Butonu -->
<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalExample">
    Modal Aç
</button>

<!-- Modal Yapısı -->
<div class="modal fade" id="modalExample" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modal Başlığı</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Modal içeriği...</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
                <button type="button" class="btn btn-primary">Kaydet</button>
            </div>
        </div>
    </div>
</div>
```

---

## CSS ve Temasıyla

### 1. Renk Sistemi

```css
/* Ana Renkler */
--primary: #667eea;      /* Mavi */
--secondary: #764ba2;    /* Mor */
--success: #28a745;      /* Yeşil */
--danger: #dc3545;       /* Kırmızı */
--warning: #ffc107;      /* Sarı */
--info: #17a2b8;         /* Açık Mavi */
--dark: #343a40;         /* Siyah */
--light: #f8f9fa;        /* Açık Gri */
```

### 2. Utility Sınıfları

```html
<!-- Margin ve Padding -->
<div class="m-0 m-md-3 m-lg-5">
    Margin: mobilde 0, tablet'te 3, masaüstünde 5
</div>

<div class="p-2 px-4 py-3">
    Padding: 2 (all), 4 (left-right), 3 (top-bottom)
</div>

<!-- Display -->
<div class="d-none d-md-block">
    Mobilde gizli, tablet ve üstünde görünür
</div>

<!-- Flexbox -->
<div class="d-flex justify-content-center align-items-center">
    Yatay ve dikey olarak ortaya hizalanmış
</div>

<!-- Text -->
<h1 class="text-center text-uppercase fw-bold">
    Ortada, BÜYÜK HARFLERİ, Kalın
</h1>

<!-- Border -->
<div class="border border-primary rounded p-3">
    Blue sınırlı ve köşeleri yumuşatılmış
</div>
```

### 3. Tema Değiştirme

```html
<!-- Standart Tema (Varsayılan) -->
<body class="theme-standard">
    ...
</body>

<!-- Santiye Tema -->
<body class="santiye-theme">
    ...
</body>

<!-- Glass Effect ile Modern Görünüm -->
<body class="santiye-theme glass-effect">
    ...
</body>
```

---

## Form Bileşenleri

### 1. Temel Form Yapısı

```php
<form method="POST" action="/api/save">
    @csrf
    
    <div class="mb-3">
        <label for="name" class="form-label">Ad Soyadı <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('name') is-invalid @enderror"
               id="name" name="name" value="{{ old('name') }}" 
               placeholder="Adınızı giriniz" required>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    
    <div class="mb-3">
        <label for="email" class="form-label">E-Posta <span class="text-danger">*</span></label>
        <input type="email" class="form-control @error('email') is-invalid @enderror"
               id="email" name="email" value="{{ old('email') }}" 
               placeholder="email@örnek.com" required>
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    
    <div class="mb-3">
        <button type="submit" class="btn btn-primary">Gönder</button>
        <button type="reset" class="btn btn-secondary">Temizle</button>
    </div>
</form>
```

### 2. İnput Türleri

```html
<!-- Metin -->
<input type="text" class="form-control" placeholder="Metin giriniz">

<!-- E-Posta -->
<input type="email" class="form-control" placeholder="email@örnek.com">

<!-- Şifre -->
<input type="password" class="form-control" placeholder="Şifre giriniz">

<!-- Sayı -->
<input type="number" class="form-control" min="0" max="100" step="1">

<!-- Tarih -->
<input type="date" class="form-control">

<!-- Saat -->
<input type="time" class="form-control">

<!-- Renk -->
<input type="color" class="form-control">

<!-- Dosya -->
<input type="file" class="form-control">
```

### 3. Select ve Dropdown'lar

```html
<!-- Select2 Kullanımı (PERGEL'de mevcut) -->
<select class="form-select select2" id="project">
    <option value="">Proje Seçiniz...</option>
    @foreach($projects as $project)
        <option value="{{ $project->id }}">{{ $project->name }}</option>
    @endforeach
</select>

<script>
    $(document).ready(function() {
        $('#project').select2({
            placeholder: 'Proje seçiniz',
            allowClear: true,
            language: 'tr'
        });
    });
</script>
```

### 4. Checkbox ve Radio

```html
<!-- Checkbox -->
<div class="form-check">
    <input class="form-check-input" type="checkbox" id="agree" name="agree">
    <label class="form-check-label" for="agree">
        Şartları kabul ediyorum
    </label>
</div>

<!-- Radio Butonları -->
<div class="form-check">
    <input class="form-check-input" type="radio" name="status" id="active" value="active">
    <label class="form-check-label" for="active">Aktif</label>
</div>
<div class="form-check">
    <input class="form-check-input" type="radio" name="status" id="inactive" value="inactive">
    <label class="form-check-label" for="inactive">Pasif</label>
</div>

<!-- Toggle Switch -->
<div class="form-check form-switch">
    <input class="form-check-input" type="checkbox" id="notifications" role="switch">
    <label class="form-check-label" for="notifications">
        Bildirimleri Aç/Kapat
    </label>
</div>
```

### 5. Form Validasyonu

```html
<!-- HTML5 Validasyonu -->
<input type="text" required minlength="3" maxlength="50">

<!-- Pattern ile Validasyon -->
<input type="text" pattern="[A-Za-z]+" title="Sadece harfler">

<!-- Bootstrap Validasyon Stilleri -->
<input type="text" class="form-control is-invalid">
<div class="invalid-feedback">Bu alan gereklidir</div>

<!-- Başarılı Validasyon -->
<input type="text" class="form-control is-valid" value="Geçerli değer">
<div class="valid-feedback">Mükemmel!</div>
```

### 6. Laravel Form Validasyonu

```php
// Controller'da
public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|min:3|max:50',
        'email' => 'required|email|unique:users',
        'age' => 'required|integer|min:18|max:120',
        'terms' => 'accepted'
    ]);
    
    // Kaydet
}

// View'de
@if ($errors->any())
    <div class="alert alert-danger">
        <h4>Hata!</h4>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
```

---

## Best Practices

### 1. HTML Yazarken

```html
<!-- ✅ İYİ: Semantik HTML -->
<nav class="navbar">
    <ul class="nav">
        <li><a href="/">Home</a></li>
    </ul>
</nav>

<!-- ❌ KÖTÜ: Yapısız HTML -->
<div class="navbar">
    <div onclick="navigate('/')">Home</div>
</div>
```

### 2. Blade Şablonlarında

```php
<!-- ✅ İYİ: Koşul kontrolü -->
@if($user->isAdmin())
    <div>Admin Paneli</div>
@elseif($user->isEditor())
    <div>Editör Paneli</div>
@else
    <div>Kullanıcı Paneli</div>
@endif

<!-- ❌ KÖTÜ: Karmaşık logik template'de -->
@if(($user->role == 'admin' && $user->status == 'active') || ...)
    ...
@endif
```

### 3. CSS Kullanırken

```html
<!-- ✅ İYİ: Bootstrap utilities -->
<div class="p-3 mb-4 bg-light rounded">
    İçerik
</div>

<!-- ❌ KÖTÜ: Inline stiller -->
<div style="padding: 1rem; margin-bottom: 1.5rem; background-color: #f0f0f0; border-radius: 0.25rem;">
    İçerik
</div>
```

### 4. Form Güvenliği

```php
<!-- ✅ İYİ: CSRF token'ı ekle -->
<form method="POST">
    @csrf
    <input type="text" name="content">
    <button type="submit">Gönder</button>
</form>

<!-- ✅ İYİ: Input'ları escape et -->
<p>{{ $user->name }}</p>  <!-- HTML escape -->
<p>{!! $user->bio !!}</p>  <!-- HTML olarak göster -->

<!-- ❌ KÖTÜ: CSRF token'ı olmadan -->
<form method="POST">
    <!-- Token yok! -->
</form>
```

### 5. Responsive Tasarım

```html
<!-- ✅ İYİ: Responsive class'ları kullan -->
<div class="col-md-6 col-lg-4">
    İçerik
</div>

<!-- ✅ İYİ: Responsive utilities -->
<div class="d-none d-md-block">
    Tablet ve üstünde görünür
</div>

<!-- ❌ KÖTÜ: Sabit genişlik -->
<div style="width: 800px;">
    Mobilde kayıyor!
</div>
```

---

## Sık Karşılaşılan Sorunlar

### Problem 1: Modal Açılmıyor

**Çözüm:**
```html
<!-- Eksik: data-bs-toggle ve data-bs-target -->
<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalId">
    Aç
</button>

<!-- Modal var mı? -->
<div class="modal fade" id="modalId">
    ...
</div>
```

### Problem 2: Select2 Çalışmıyor

**Çözüm:**
```javascript
// jQuery yüklü mü?
// Select2 kütüphanesi yüklü mü?
// Doğru selector mi?

$(document).ready(function() {
    $('.select2').select2();
});
```

### Problem 3: Form Validasyonu Çalışmıyor

**Çözüm:**
```php
// Laravel validasyonu
$request->validate([
    'email' => 'required|email'
]);

// HTML5 validasyonu
<input type="email" required>

// Bootstrap validation CSS sınıfı
<input class="form-control @error('email') is-invalid @enderror">
```

### Problem 4: Responsive Tasarım Mobilde Çalışmıyor

**Çözüm:**
```html
<!-- Meta tag'i ekle -->
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- Breakpoint'leri kontrol et -->
<div class="col-12 col-md-6 col-lg-4">
    <!-- Mobilde tam, tablet'te yarısı, masaüstünde 1/3 -->
</div>
```

### Problem 5: Blade Şablonları Derlenmyor

**Çözüm:**
```bash
# Cache'i temizle
php artisan cache:clear
php artisan view:clear

# Recompile et
php artisan view:cache

# Config'i temizle
php artisan config:clear
```

---

## Yararlı Kaynaklar

- 📖 [Bootstrap 5 Belgeleri](https://getbootstrap.com/docs/5.0/)
- 📖 [Laravel Blade Belgeleri](https://laravel.com/docs/11.x/blade)
- 🎨 [Font Awesome İkonları](https://fontawesome.com/icons)
- 🔍 [MDN Web Docs](https://developer.mozilla.org/)
- 📱 [Can I Use - Browser Support](https://caniuse.com/)

---

## Sürüm Bilgisi

- **Rehber Versiyon:** 1.0
- **Son Güncelleme:** 27.10.2025
- **PERGEL Versiyon:** 1.0
- **Bootstrap Versiyon:** 5.3.x
- **Laravel Versiyon:** 11.31

---

## İletişim ve Destek

Sorularınız veya önerileriniz için lütfen sistem yöneticisiyle iletişime geçin.

**Belge Hazırlayan:** PERGEL Geliştirme Ekibi  
**Son Düzenleyen:** HTML Rehberi Ekibi  
**Durum:** ✅ Tamamlandı