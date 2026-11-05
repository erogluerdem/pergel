# 🎨 PERGEL HTML Kullanım Rehberi

## 📌 Hoş Geldiniz!

PERGEL CRM sisteminin HTML, CSS, Blade şablonları ve web geliştirme hakkında kapsamlı kullanım rehberine hoş geldiniz.

## 📂 Dosyalar ve Açıklaması

### HTML Rehberleri (İnteraktif)
| Dosya | Açıklama | Hedef Kitle |
|-------|----------|-----------|
| `index.html` | 📑 **Ana Başlangıç Sayfası** - Tüm rehberlere erişim | Herkes |
| `01-HTML-Yapisi-Temelleri.html` | 🏗️ **Bölüm 1** - HTML yapısı, master layout, grid sistemi | Front-end Dev, Designer |
| `02-CSS-Tema-Sistemi.html` | 🎨 **Bölüm 2** - Renkler, utilities, temalar, tipografi | Designer, Stylist |
| `03-Form-Input-Elemanlari.html` | 📋 **Bölüm 3** - Formlar, input türleri, validasyon | Form Developer |

### Markdown Dokümantasyonu
| Dosya | Açıklama | Format |
|-------|----------|--------|
| `HTML_Rehberi_Detaylı_Dokumantasyon.md` | 📚 Detaylı teknik dokümantasyon | Markdown |
| `README.md` | Bu dosya - Rehberin tanıtımı | Markdown |

## 🚀 Hızlı Başlangıç

### Rehberi Açmak İçin

1. **İnteraktif Rehberi Aç:**
   ```
   Tarayıcıda açın: kullanim_rehberi/index.html
   ```

2. **Bölümleri Oku:**
   - Bölüm 1: HTML Yapısı ve Temelleri
   - Bölüm 2: CSS ve Tema Sistemi
   - Bölüm 3: Form ve Input Elemanları

3. **Markdown Dokümantasyonu:**
   ```
   VS Code'da açın: HTML_Rehberi_Detaylı_Dokumantasyon.md
   ```

## 📖 Rehberin İçeriği

### Bölüm 1: HTML Yapısı ve Temelleri 🏗️

**Konu Başlıkları:**
- ✅ Master Layout Yapısı
- ✅ Bootstrap 5 Grid Sistemi
- ✅ Responsive Breakpoints
- ✅ Bootstrap Bileşenleri (Cards, Buttons, Tables, Modals)
- ✅ Blade Şablonları

**Örnek Kod:**
```php
@extends('layouts.master')

@section('title', 'Projeler')

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <h3>Proje Listesi</h3>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    İstatistikler
                </div>
            </div>
        </div>
    </div>
@endsection
```

**Zaman:** ~1-2 saat

### Bölüm 2: CSS ve Tema Sistemi 🎨

**Konu Başlıkları:**
- ✅ Ana Renk Paleti
- ✅ Bootstrap Utilities (Spacing, Display, Text, Flexbox)
- ✅ Tema Sistemi (Standart, Santiye, Glass Effect)
- ✅ Tipografi Hiyerarşisi
- ✅ Font Awesome İkonları

**Örnek Kod:**
```html
<!-- Utility Sınıfları -->
<div class="d-flex justify-content-center align-items-center p-4 mb-3 bg-light rounded">
    <h2 class="text-primary fw-bold">Ortalaşmış Başlık</h2>
</div>

<!-- Tema Değiştirme -->
<body class="santiye-theme glass-effect">
    ...
</body>
```

**Zaman:** ~1.5-2 saat

### Bölüm 3: Form ve Input Elemanları 📋

**Konu Başlıkları:**
- ✅ Temel Form Yapısı
- ✅ Input Türleri (Text, Email, Number, Date, Select)
- ✅ Checkbox ve Radio Butonları
- ✅ HTML5 ve Bootstrap Validasyonu
- ✅ Laravel Validasyonu
- ✅ İleri Form Bileşenleri

**Örnek Kod:**
```php
<form method="POST" action="/save">
    @csrf
    
    <div class="mb-3">
        <label for="name" class="form-label">Ad Soyadı *</label>
        <input type="text" class="form-control @error('name') is-invalid @enderror"
               id="name" name="name" required>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    
    <button type="submit" class="btn btn-primary">Gönder</button>
</form>
```

**Zaman:** ~1.5-2 saat

## 🎯 Kimin İçin?

### 👨‍💼 Proje Müdürü
- Rehberi tamamen okumanıza gerek yok
- Index sayfasındaki özeti yeterli

### 👨‍💻 Front-End Developer
- Bölüm 1 ve 2 **ZORUNLU**
- Bölüm 3 detaylı şekilde oku
- Tüm örnekleri pratik yap

### 🎨 Designer/UI Uzmanı
- Bölüm 2 öncelikli
- Bölüm 1 kısaca gözden geçir
- Renk sistemi ve tema bölümünü detaylı oku

### 🔧 Sistem Yöneticisi
- Bölüm 1 genel bakış
- Tema değiştirme bölümü
- Dosya struktur bilgisi

### 📚 Yeni Başlayan
- Sırasıyla Bölüm 1, 2, 3'ü oku
- Her örneği kendim de test et
- Pratik yap ve sorular sor

## 💡 Temel Konseptler

### HTML5
```html
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sayfa Başlığı</title>
</head>
<body>
    <h1>Merhaba Dünya</h1>
</body>
</html>
```

### Bootstrap Grid
```html
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6">Yarısı</div>
        <div class="col-md-6">Diğer Yarısı</div>
    </div>
</div>
```

### Laravel Blade
```php
@if($user->isAdmin())
    <div>Admin Paneli</div>
@endif

@foreach($items as $item)
    <p>{{ $item->name }}</p>
@endforeach
```

## 🛠️ Teknolojiler

| Teknoloji | Versiyon | Kullanım |
|-----------|----------|----------|
| **Bootstrap** | 5.3.x | CSS Framework |
| **Laravel** | 11.31 | Web Framework |
| **Blade** | - | Template Engine |
| **Font Awesome** | 6.x | İkon Kütüphanesi |
| **PHP** | 8.2+ | Server-side |
| **HTML5** | - | Markup |
| **CSS3** | - | Styling |

## 📊 Rehberin İstatistikleri

- **Toplam Dosya:** 6
- **HTML Sayfalar:** 4
- **Markdown Doküman:** 2
- **Kod Örneği:** 100+
- **Resim/Diyagram:** 15+
- **Toplam Kelime:** 15,000+
- **Ortalama Okuma Süresi:** 4-5 saat

## ✅ Öğrenme Yol Haritası

### 1️⃣ Gün 1 (1-2 saat)
- [ ] Index sayfasını oku
- [ ] Bölüm 1'i tamamen oku
- [ ] Grid sistemiyle pratik yap

### 2️⃣ Gün 2 (1.5-2 saat)
- [ ] Bölüm 2'yi oku
- [ ] Renkler ve utilities'i test et
- [ ] Tema değiştirmeyi dene

### 3️⃣ Gün 3 (1.5-2 saat)
- [ ] Bölüm 3'ü oku
- [ ] Formlar oluştur
- [ ] Validasyon ekle

### 4️⃣ Gün 4+ (Pratik)
- [ ] Kendi sayfalarınızı oluşturun
- [ ] Rehberi referans olarak kullanın
- [ ] Sorun yaşandığında troubleshooting bölümüne bakın

## 🔍 Nasıl Kullanılır

### Adım 1: Rehberi Aç
```bash
# Dosya gezgininde aç
kullanim_rehberi/index.html
# veya tarayıcıda açın
```

### Adım 2: Bölümü Seç
- Başlamak için "Bölüm 1"i tıkla
- Sırayla ilerle veya ihtiyacına göre atla

### Adım 3: Öğren ve Pratik Yap
- Tüm kodları oku
- Örnekleri test et
- Kendi projelerinde kullan

### Adım 4: Referans Olarak Kullan
- Uygulamakta zorlandığında rehberi aç
- Arama yaparak bulduğun bölüme git
- Kod örneğini kopyala ve uyarla

## ❓ Sık Sorulan Sorular (FAQ)

**S: Rehberi çevrimdışı olarak okuyabilir miyim?**
A: Evet! HTML dosyalarını indirip tarayıcıda açabilirsiniz. İnternet bağlantısına ihtiyaç yok.

**S: Hangi tarayıcıda açmalıyım?**
A: Chrome, Firefox, Safari, Edge - tüm modern tarayıcılarda sorunsuz çalışır.

**S: Rehberi yazıcıdan çıkabilir miyim?**
A: Evet! HTML dosyalarını "Yazdır" (Ctrl+P) özelliğiyle PDF olarak kaydetebilirsiniz.

**S: Rehberi güncelleme planınız var mı?**
A: Evet! Her 3 ayda bir gözden geçirilir ve güncellenir.

**S: Üçüncü taraf kütüphaneler (Select2, DataTables) için rehber var mı?**
A: Bölüm 3 ve Markdown dokümantasyonda Select2 ve DataTables örnekleri vardır.

## 📞 Destek ve Geri Bildirim

### Sorularınız Varsa
- 📧 Email: support@pergel.io
- 💬 Slack: #html-rehberi
- 📞 Tel: +90 (XXX) XXX XXXX

### Geri Bildirim
Rehberi iyileştirmek için lütfen geri bildiriminizi gönderin:
- 🐛 Hata bulma
- 💡 Yeni konu önerisi
- 📝 İyileştirme önerisi
- ❓ Anlaşılamayan kısımlar

## 📜 Sürüm Tarihi

| Versiyon | Tarih | Değişiklikler |
|----------|-------|---------------|
| 1.0 | 27.10.2025 | İlk Yayın |
| 1.1 | İlerde | Ek örnekler ve video bağlantıları |
| 2.0 | İlerde | Etkileşimli rehber |

## 📚 Ek Kaynaklar

### Resmi Belgeler
- [Bootstrap 5 Docs](https://getbootstrap.com/docs/5.0/)
- [Laravel Blade Docs](https://laravel.com/docs/11.x/blade)
- [MDN Web Docs](https://developer.mozilla.org/)

### Öğrenme Platformları
- [Codecademy](https://www.codecademy.com/)
- [freeCodeCamp](https://www.freecodecamp.org/)
- [W3Schools](https://www.w3schools.com/)

### Araçlar
- [VS Code](https://code.visualstudio.com/)
- [Chrome DevTools](https://developer.chrome.com/docs/devtools/)
- [Can I Use](https://caniuse.com/)

## 🎯 Başarı Kriterleri

Rehberi başarıyla tamamladığınızda şunları yapabilmelisiniz:

✅ HTML5 sayfa yapısı oluşturabilme  
✅ Bootstrap grid sistemi kullanabilme  
✅ Responsive tasarım yapabilme  
✅ Blade şablonları yazabilme  
✅ CSS utilities efektif kullanabilme  
✅ Form tasarlayabilme  
✅ Validasyon ekleyebilme  
✅ Temel PERGEL sayfaları oluşturabilme  

## 🏆 Bitirme Projesi (Opsiyonel)

Rehberi bitirdikten sonra bir proje yapın:

```
Görev: PERGEL'de yeni bir "Gözlemler" modülü sayfası oluşturun

Gereksinimler:
- Master layout'u extend et
- Grid sistem kullan (responsive)
- Tüm bölümlerin konseptlerini uygula
- Form ekle ve validasyon yap
- En az 3 farklı bileşen kullan
- Tüm Bootstrap utilities'i kullan
```

---

**Son Güncelleme:** 27.10.2025  
**Hazırlayan:** PERGEL Geliştirme Ekibi  
**Durum:** ✅ Tamamlandı ve Yayında

Rehberi okumaya başlamak için **index.html** dosyasını açınız!
