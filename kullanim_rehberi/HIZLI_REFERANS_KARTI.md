# 🎯 PERGEL HTML - HIZLI REFERANS KARTI

## 📋 En Sık Kullanılan Kod Parçacıkları

### 1. Yeni Sayfa Oluşturma
```php
@extends('layouts.master')

@section('title', 'Sayfa Adı')
@section('topbar-title', 'Modül Adı')

@section('content')
    <div class="row">
        <div class="col-12">
            <!-- İçerik buraya -->
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Stiller */
    </style>
@endpush

@push('scripts')
    <script>
        // Script
    </script>
@endpush
```

---

### 2. Grid Layout (Responsive)
```html
<!-- 2 Sütun -->
<div class="row">
    <div class="col-lg-8">Ana İçerik</div>
    <div class="col-lg-4">Yan Panel</div>
</div>

<!-- 3 Sütun -->
<div class="row">
    <div class="col-md-4">1/3</div>
    <div class="col-md-4">1/3</div>
    <div class="col-md-4">1/3</div>
</div>

<!-- 4 KPI Kartı -->
<div class="row">
    @for($i = 1; $i <= 4; $i++)
        <div class="col-md-6 col-lg-3">
            <div class="card">Card {{ $i }}</div>
        </div>
    @endfor
</div>
```

---

### 3. Kart (Card) Bileşeni
```html
<div class="card">
    <img src="image.jpg" class="card-img-top" alt="...">
    <div class="card-body">
        <h5 class="card-title">Başlık</h5>
        <p class="card-text">Açıklama</p>
    </div>
    <div class="card-footer">
        <a href="#" class="btn btn-primary btn-sm">Düzenle</a>
        <a href="#" class="btn btn-danger btn-sm">Sil</a>
    </div>
</div>
```

---

### 4. Tablo (Table)
```html
<table class="table table-striped table-hover">
    <thead class="table-dark">
        <tr>
            <th>Sütun 1</th>
            <th>Sütun 2</th>
            <th>İşlem</th>
        </tr>
    </thead>
    <tbody>
        @foreach($items as $item)
            <tr>
                <td>{{ $item->name }}</td>
                <td>{{ $item->value }}</td>
                <td>
                    <a href="..." class="btn btn-sm btn-primary">Düzenle</a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
```

---

### 5. Form (Basit)
```php
<form method="POST" action="/save">
    @csrf
    
    <div class="mb-3">
        <label for="name" class="form-label">Ad *</label>
        <input type="text" class="form-control @error('name') is-invalid @enderror"
               id="name" name="name" value="{{ old('name') }}" required>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    
    <div class="mb-3">
        <label for="email" class="form-label">E-Posta *</label>
        <input type="email" class="form-control @error('email') is-invalid @enderror"
               id="email" name="email" value="{{ old('email') }}" required>
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    
    <button type="submit" class="btn btn-primary">Gönder</button>
</form>
```

---

### 6. Select/Dropdown (Select2)
```html
<select class="form-select select2" id="project" name="project_id">
    <option value="">Seçim yapınız...</option>
    @foreach($projects as $project)
        <option value="{{ $project->id }}">{{ $project->name }}</option>
    @endforeach
</select>

<script>
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: 'Seçim yapınız',
            language: 'tr'
        });
    });
</script>
```

---

### 7. Modal Dialog
```html
<!-- Açma Butonu -->
<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal">
    Modal Aç
</button>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modal Başlığı</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                İçerik
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

### 8. Alert (Uyarı Kutuları)
```html
<!-- Başarı -->
<div class="alert alert-success">
    <i class="fas fa-check-circle"></i> İşlem başarılı!
</div>

<!-- Hata -->
<div class="alert alert-danger">
    <i class="fas fa-times-circle"></i> Hata oluştu!
</div>

<!-- Uyarı -->
<div class="alert alert-warning">
    <i class="fas fa-exclamation-triangle"></i> Dikkat!
</div>

<!-- Bilgi -->
<div class="alert alert-info">
    <i class="fas fa-info-circle"></i> Bilgi
</div>
```

---

### 9. Buton Varyasyonları
```html
<!-- Renk Varyasyonları -->
<button class="btn btn-primary">Birincil</button>
<button class="btn btn-secondary">İkincil</button>
<button class="btn btn-success">Başarı</button>
<button class="btn btn-danger">Hata</button>
<button class="btn btn-warning">Uyarı</button>
<button class="btn btn-info">Bilgi</button>

<!-- Boyut Varyasyonları -->
<button class="btn btn-primary btn-sm">Küçük</button>
<button class="btn btn-primary">Normal</button>
<button class="btn btn-primary btn-lg">Büyük</button>

<!-- Stil Varyasyonları -->
<button class="btn btn-primary">Dolu</button>
<button class="btn btn-outline-primary">Çerçeveli</button>
<button class="btn btn-primary disabled">Devre Dışı</button>
```

---

### 10. Badge (Etiketler)
```html
<span class="badge bg-primary">Mavi</span>
<span class="badge bg-success">Yeşil</span>
<span class="badge bg-danger">Kırmızı</span>
<span class="badge bg-warning text-dark">Sarı</span>
<span class="badge bg-secondary">Gri</span>
```

---

### 11. Flex Layout (Hizalama)
```html
<!-- Yatay Ortala -->
<div class="d-flex justify-content-center">
    İçerik
</div>

<!-- Yatay ve Dikey Ortala -->
<div class="d-flex justify-content-center align-items-center" style="height: 200px;">
    İçerik
</div>

<!-- Aralıklı -->
<div class="d-flex justify-content-between">
    <div>Sol</div>
    <div>Sağ</div>
</div>

<!-- Dikey Layout -->
<div class="d-flex flex-column">
    <div>Üst</div>
    <div>Orta</div>
    <div>Alt</div>
</div>
```

---

### 12. Padding ve Margin Utilities
```html
<!-- Padding -->
<div class="p-0">0</div>
<div class="p-1">0.25rem</div>
<div class="p-2">0.5rem</div>
<div class="p-3">1rem</div>
<div class="p-4">1.5rem</div>
<div class="p-5">3rem</div>

<!-- Margin -->
<div class="m-3 mb-0">Margin 3 tüm taraflar, alt 0</div>

<!-- Responsive -->
<div class="p-2 p-md-3 p-lg-5">
    Mobilde p-2, tablet'te p-3, masaüstünde p-5
</div>
```

---

### 13. Display Utilities
```html
<!-- Gizle/Göster -->
<div class="d-none">Gizli (display: none)</div>
<div class="d-none d-md-block">Mobilde gizli, tablet ve üstünde görünür</div>
<div class="d-block d-lg-none">Masaüstünde gizli, altında görünür</div>

<!-- Display Türleri -->
<div class="d-flex">Flex layout</div>
<div class="d-grid">Grid layout</div>
<div class="d-inline">Inline</div>
<div class="d-block">Block</div>
```

---

### 14. Text Utilities
```html
<!-- Hizalama -->
<p class="text-start">Sol</p>
<p class="text-center">Orta</p>
<p class="text-end">Sağ</p>

<!-- Stili -->
<p class="text-uppercase">BÜYÜK HARFLERİ</p>
<p class="text-lowercase">küçük harfleri</p>
<p class="text-capitalize">Başlık Harfleri</p>

<!-- Ağırlık -->
<p class="fw-light">Hafif</p>
<p class="fw-normal">Normal</p>
<p class="fw-bold">Kalın</p>
<p class="fw-bolder">Daha Kalın</p>

<!-- Renk -->
<p class="text-primary">Mavi</p>
<p class="text-danger">Kırmızı</p>
<p class="text-muted">Soluk</p>
```

---

### 15. Validasyon Stilleri
```html
<!-- Başarılı -->
<input type="text" class="form-control is-valid" value="Geçerli">
<div class="valid-feedback">Mükemmel!</div>

<!-- Başarısız -->
<input type="text" class="form-control is-invalid" value="">
<div class="invalid-feedback">Hata mesajı</div>
```

---

### 16. Blade Direktifleri (Sık Kullanılan)
```php
<!-- Yield (Master'dan veri al) -->
@yield('content')
@yield('title', 'Varsayılan')

<!-- Section (Master'a veri gönder) -->
@section('content')
    İçerik
@endsection

<!-- Include (Dosya dahil et) -->
@include('header')
@include('components.sidebar', ['data' => $data])

<!-- Loop -->
@foreach($items as $item)
    {{ $item->name }}
@endforeach

<!-- Koşul -->
@if($user->isAdmin())
    Admin
@elseif($user->isEditor())
    Editor
@else
    User
@endif

<!-- Auth Check -->
@auth
    Giriş yapılmış
@else
    Giriş yapılmamış
@endauth

<!-- Error Check -->
@error('field')
    {{ $message }}
@enderror
```

---

## 📊 RENK KODLARI

```
Primary (Mavi):     #667eea
Secondary (Mor):    #764ba2
Success (Yeşil):    #28a745
Danger (Kırmızı):   #dc3545
Warning (Sarı):     #ffc107
Info (Açık Mavi):   #17a2b8
Dark (Siyah):       #343a40
Light (Açık Gri):   #f8f9fa
```

---

## 📐 BREAKPOINTS (Responsive)

```
Mobil:       < 576px  (.col- / -xs)
Tablet:      ≥ 576px  (.col-sm-)
Tablet+:     ≥ 768px  (.col-md-)
Masaüstü:    ≥ 992px  (.col-lg-)
Geniş:       ≥ 1200px (.col-xl-)
Çok Geniş:   ≥ 1400px (.col-xxl-)
```

---

## ⌨️ KLAVIŞ KISAYOLLARI

```
VS Code:
Ctrl + B          = Sidebar aç/kapat
Ctrl + P          = Dosya aç
Ctrl + /          = Satırı comment'le
Ctrl + Shift + F  = Tüm dosyalarda ara
Ctrl + `          = Terminal aç

Tarayıcı:
F12               = Developer Tools
Ctrl + Shift + C  = Element seç
Ctrl + P          = Yazdır (PDF kaydet)
Ctrl + L          = URL'ye fokus
```

---

## 🔗 FAYDALI BAĞLANTILAR

```
Bootstrap:     https://getbootstrap.com/
Laravel Blade: https://laravel.com/docs/blade
Font Awesome:  https://fontawesome.com/
MDN:           https://developer.mozilla.org/
CSS Tricks:    https://css-tricks.com/
```

---

## ✅ KONTROL LİSTESİ (Yeni Sayfa Oluştururken)

- [ ] Master extend ettim
- [ ] Title section'ı tanımladım
- [ ] Content section'ı yazdum
- [ ] Bootstrap grid kullandım
- [ ] Responsive tasarım kontrol ettim
- [ ] Form varsa @csrf ekledim
- [ ] Validasyon sınıfları ekledim
- [ ] CSS varsa @push('styles') kullandım
- [ ] JavaScript varsa @push('scripts') kullandım
- [ ] Mobile cihazda test ettim

---

**Son Güncelleme:** 27.10.2025  
**Sürüm:** 1.0  
**Durum:** ✅ Aktif