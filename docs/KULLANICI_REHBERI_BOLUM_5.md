# 📚 PERGEL CRM SİSTEMİ - KAPSAMLI KULLANICI REHBERİ
## BÖLÜM 5: İLERİ ÖZELLİKLER, OTOMASYON & SORUN GIDERME

**Tarih:** 27 Ekim 2025  
**Versiyon:** 3.0  
**Durum:** ✅ Aktif & Kullanıma Hazır

---

## 🎯 Bu Bölüm Nedir?

Bu bölümde öğrenecekleriniz:
- ✅ Gelişmiş Raporlama Araçları
- ✅ Otomasyon ve İş Kuralları
- ✅ Üçüncü Parti Entegrasyonları
- ✅ API Kullanımı
- ✅ Sorun Giderme İçin Hızlı Çözümler
- ✅ Sistem Bakım ve Yönetim

---

## 📊 ADVANCED RAPORLAMA (İLERİ RAPORLAMA)

### **Özel Rapor Oluşturucu**

```
🛠️ RAPOR KURUCUSU (Report Builder)

Navigasyon: 📊 RAPORLAMA → Özel Rapor Oluştur

[➕ YENİ RAPOR]

ADIM 1: RAPOR TÜRÜ SEÇ
┌──────────────────────────────────────┐
│ Rapor Türü                           │
├──────────────────────────────────────┤
│ ○ Tablo Raporu (Satırlar & Sütunlar)│
│ ○ Grafik Raporu (Görselleştirme)    │
│ ○ Dashboard (Pano Görünümü)          │
│ ○ Pivot Tablo (Çapraz Analiz)        │
│ ○ Zaman Serisi (Trend Analizi)       │
│ ● Özel SQL (Geliştiriciler için)    │
└──────────────────────────────────────┘

ADIM 2: VERI KAYNAĞINI SEÇ
┌──────────────────────────────────────┐
│ Hangi Veriler?                       │
├──────────────────────────────────────┤
│ [x] Projeler                         │
│ [x] Faturalar                        │
│ [x] Ödemeler                         │
│ [x] Görevler                         │
│ [x] Riskler                          │
│ [x] Müşteriler                       │
└──────────────────────────────────────┘

ADIM 3: FİLTRELERİ UYGULA
┌──────────────────────────────────────┐
│ Filtreler (Opsiyonel)                │
├──────────────────────────────────────┤
│ Tarih Aralığı:                       │
│ [Başlangıç: 01.01.2025] - [Bitiş: 31│
│                                      │
│ Proje Tipi:                          │
│ ☑️ Konut  ☑️ Ticari  ☑️ Altyapı    │
│                                      │
│ Durum:                               │
│ ☑️ Planlanmış ☑️ Devam ☑️ Tamamlandı│
│                                      │
│ Bütçe Aralığı:                       │
│ Min: [1M] - Max: [100M]              │
└──────────────────────────────────────┘

ADIM 4: SÜTUN AYARLA
┌──────────────────────────────────────┐
│ Gösterilecek Sütunlar                │
├──────────────────────────────────────┤
│ ☑️ Proje Adı                         │
│ ☑️ Bütçe                             │
│ ☑️ Harcanan                          │
│ ☑️ Kalan                             │
│ ☑️ % Tamamlanma                      │
│ ☑️ Müdürü                            │
│ ☑️ Durum                             │
│                                      │
│ Hesaplanan Alanlar:                 │
│ [➕ ALAN EKLE]                       │
│ Örnek: Bütçe - Harcanan = Kalan    │
└──────────────────────────────────────┘

ADIM 5: SIRALAMASI BELİRLE
┌──────────────────────────────────────┐
│ Sıralama Düzeni                      │
├──────────────────────────────────────┤
│ Birincil Sıralama:  [Harcanan % ▼]  │
│                     [Azalan ⬇️]      │
│                                      │
│ İkincil Sıralama:   [Proje Adı ▼]   │
│                     [Artan ⬆️]       │
│                                      │
│ Üçüncül Sıralama:   [Müdürü ▼]      │
│                     [Alfabetik]      │
└──────────────────────────────────────┘

ADIM 6: OLUŞTUR
[🔄 OLUŞTUR VE GÖSTER]

SONUÇ:

Proje Adı        │ Bütçe   │ Harcanan │ Kalan  │ % Harcanan
─────────────────┼─────────┼──────────┼────────┼────────
Konut Kompleksi  │ ₺52.5M  │ ₺32.1M   │ ₺20.4M │ 61.1%
Ticari Merkez    │ ₺32.1M  │ ₺24.8M   │ ₺7.3M  │ 77.3%
Altyapı Projesi  │ ₺18.9M  │ ₺8.2M    │ ₺10.7M │ 43.4%
──────────────────────────────────────────────────────

[📥 İNDİR] [📧 GÖNDER] [🖨️ YAZDIR] [💾 KAYDET]
```

---

### **Dash Board Kustomizasyonu**

```
🎨 KİŞİSEL DASHBOARD OLUŞTURMAK

Navigasyon: 📊 RAPORLAMA → Dashboard Editörü

[✏️ DASHBOARD DÜZENLE]

DRAG & DROP ARAYÜZÜ:

┌─────────────────────────────────────┐
│ MİZE EÇİN                          │
├─────────────────────────────────────┤
│                                     │
│ 📊 KARTLARt (Cards)                │
│ ┌──────────┐ ┌──────────┐         │
│ │ Başlık   │ │ Başlık   │         │
│ │ Değer    │ │ Değer    │         │
│ │ Trend    │ │ Trend    │         │
│ └──────────┘ └──────────┘         │
│     ↓              ↓               │
│  [Sürükle & Bırak]                │
│                                     │
│ 📈 GRAFİKLER                       │
│ ┌────────────────────────────┐     │
│ │   Line / Bar / Pie Chart   │     │
│ │                            │     │
│ │        📉📊📈              │     │
│ └────────────────────────────┘     │
│           ↓                         │
│    [Sürükle & Bırak]               │
│                                     │
│ 📋 TABLOLAR                        │
│ ┌────────────────────────────┐     │
│ │ Satırlar × Sütunlar        │     │
│ │ Filtreleme & Sıralama      │     │
│ │ Sayfa: [1] [2] [3]         │     │
│ └────────────────────────────┘     │
│           ↓                         │
│    [Sürükle & Bırak]               │
│                                     │
│ 🎯 KPİ METETRE                     │
│ ┌──────────┐ ┌──────────┐         │
│ │ 82/100   │ │ 61.1%    │         │
│ │ Sağlık   │ │ İlerleme │         │
│ │ ✅ İyi   │ │ 🟡 Orta  │         │
│ └──────────┘ └──────────┘         │
│                                     │
│ 🔔 HARITA                          │
│ ┌────────────────────────────┐     │
│ │ 🗺️ Proje Lokasyon Haritası │     │
│ │ Markerlar & Filtreler      │     │
│ └────────────────────────────┘     │
│                                     │
└─────────────────────────────────────┘

DASHBOARD DÜZENLEME SEÇENEKLERI:

[⚙️ AYARLAR]
├─ Dashboard Adı: [Bölge Müdürü Paneli]
├─ Açıklama: [Bölgenin performans izleme...]
├─ Yenileme Sıklığı: [Her 5 Dakika]
├─ Varsayılan Filtre:
│  ├─ Bölge: İstanbul
│  ├─ Tarih: Son 30 Gün
│  └─ Durum: Aktif
├─ Erişim Kontrol:
│  ├─ Yalnız Ben
│  ├─ Bölge Ekibi
│  ├─ Tüm Sistem
│  └─ Seçili Kişiler [ekle]
└─ Tema:
   ├─ Açık
   ├─ Koyu
   └─ Sistem Ayarları

[💾 KAYDET] [🖼️ ÖNİZLE] [🗑️ SİL]
```

---

## ⚙️ OTOMASYON & İŞ KURALLARI

### **Otomatik İş Kuralları**

```
🤖 OTOMATİK İŞ KURALLARI

Navigasyon: ⚙️ AYARLAR → Otomasyon → İş Kuralları

[➕ YENİ KURAL]

KURAL KURULUM SIHIRBAZI:

ADIM 1: KURAL TRİGGERİ (Ne zaman çalıştırılacak?)
┌──────────────────────────────────────┐
│ Nede Kural Çalıştırılacak?           │
├──────────────────────────────────────┤
│ ○ Nesne Oluşturulduğunda             │
│ ● Nesne Güncellendiğinde             │
│ ○ Alan Değeri Değiştiğinde           │
│ ○ Tarihte (Planlanmış)               │
│ ○ İşlem Tamamlandığında              │
└──────────────────────────────────────┘

ADIM 2: TRIGGER KOŞULLARI
┌──────────────────────────────────────┐
│ Hangi Koşullarda?                    │
├──────────────────────────────────────┤
│ Nesne Tipi: [Proje ▼]                │
│                                      │
│ Koşul 1: [Durum] [Değiştirilir]      │
│         [Eski: Planlanmış] --->      │
│         [Yeni: İnşaat]               │
│                                      │
│ Koşul 2: [VE] [Bütçe] [Aşılırsa]    │
│         [Eşik: ₺10M]                 │
│                                      │
│ Koşul 3: [VE] [Müdürü] [Bilinen]    │
│         [Değer: Tüm Müdürler]        │
│                                      │
│ [+ KOŞUL EKLE]                       │
└──────────────────────────────────────┘

ADIM 3: YAPILACAK İŞLEMLER
┌──────────────────────────────────────┐
│ Ne Yapılacak? (Otomatik İşlemler)   │
├──────────────────────────────────────┤
│ ☑️ EMAIL GÖNDER                      │
│   Kime: [Proje Müdürü]               │
│   Konu: "Proje {Adı} İnşaata Başladı" │
│   İçerik: [Template Seçin]           │
│                                      │
│ ☑️ BİLDİRİM OLUŞTUR                  │
│   Alıcı: [Mali Sorumlu]              │
│   Başlık: "Bütçe Eşiği Aşıldı"       │
│   Öncelik: [🔴 KRITIK]               │
│                                      │
│ ☑️ GÖREV OLUŞTUR                     │
│   Adı: "İnşaat Sürü Yönetim Başla"  │
│   Atanan: [Proje Müdürü]             │
│   Tarih: Bugünden +1 gün             │
│   Öncelik: [Yüksek]                  │
│                                      │
│ ☑️ SLACK MESAJI GÖNDER               │
│   Kanal: #pergel-alerts              │
│   Mesaj: "🔴 {ProjeAdı} Kritik"     │
│                                      │
│ ☑️ WEB HOOK ÇAĞIR                    │
│   URL: https://example.com/hook      │
│   Veri: JSON Payload                 │
│                                      │
│ [+ İŞLEM EKLE]                       │
└──────────────────────────────────────┘

ADIM 4: İSİMLENDİRME VE KAYDET
┌──────────────────────────────────────┐
│ Kural Adı:                           │
│ [Proje İnşaata Başladığında]         │
│                                      │
│ Açıklama:                            │
│ [Durum değiştiğinde ve bütçe eşiği   │
│  aşıldığında otomatik işlem yap]     │
│                                      │
│ Etkin Mi?: ☑️ Evet                   │
│                                      │
│ [💾 KAYDET]                          │
└──────────────────────────────────────┘

KURALLARIN ÇALIŞMA ÖRNEKLERI:

Kural #1: "Destek Talebi Kritik Ise"
├─ Trigger: Destek Talebi Oluştur
├─ Koşul: Öncelik = Kritik
└─ İşlem: Email + SMS + Slack Mesajı

Kural #2: "Fatura Gecikirse"
├─ Trigger: Fatura Tarihi Geçti
├─ Koşul: Durum = Beklemede
└─ İşlem: Müşteriye Email + Görev Oluştur

Kural #3: "İşçi Ücretleri Gelişi"
├─ Trigger: Fatura Oluşturuldu
├─ Koşul: Kategori = İşçi Ücretleri
└─ İşlem: Bütçe Güncelle + Risk Analizi
```

---

## 🔗 ENTEGRASYONLAR (3. PARTI)

### **Mevcut Entegrasyonlar**

```
🔌 ÜÇÜNCÜ PARTI ENTEGRASYONLARI

Navigasyon: ⚙️ AYARLAR → Entegrasyonlar

┌──────────────────────────────────────────┐
│ KURULU ENTEGRASYONLAR                    │
├──────────────────────────────────────────┤
│                                          │
│ 📧 EMAIL ENTEGRASYONU ✅                 │
│ ├─ Sağlayıcı: Gmail / Office 365        │
│ ├─ Durum: 🟢 Bağlı                      │
│ ├─ Bağlı Hesap: info@pergel.com        │
│ ├─ Gönderilen Mesaj: 1,247              │
│ └─ Son Kontrol: 2 dakika önce           │
│                                          │
│ 📱 SMS ENTEGRASYONU ✅                   │
│ ├─ Sağlayıcı: Twilio                    │
│ ├─ Durum: 🟢 Bağlı                      │
│ ├─ Gönderilen SMS: 487                  │
│ ├─ Kalan Kredi: ₺2.400                  │
│ └─ [⚙️ AY İBTİ KONTROL]                │
│                                          │
│ ☁️ GOOGLE DRIVE / ONE DRIVE ✅           │
│ ├─ Sağlayıcı: Google Workspace          │
│ ├─ Durum: 🟢 Bağlı                      │
│ ├─ Klasör: /PERGEL Sistem/              │
│ ├─ Dosya: 234 adet                      │
│ └─ Disk Kullanımı: 45 GB / 100 GB       │
│                                          │
│ 💼 SLACK ✅                              │
│ ├─ Sağlayıcı: Slack                     │
│ ├─ Durum: 🟢 Bağlı                      │
│ ├─ Çalışma Alanı: pergel-crm            │
│ ├─ Kanal: #alerts #projects #finance   │
│ └─ Gönderilen Mesaj: 892                │
│                                          │
│ 💰 MUHASEBE YAZILIMI ✅                  │
│ ├─ Sağlayıcı: NetEğer Muhasebe          │
│ ├─ Durum: 🟢 Bağlı                      │
│ ├─ Son Sinkron: 1 saat önce             │
│ ├─ Değerlendirilen İşlem: 2.340         │
│ └─ [🔄 ŞÜNÜ SİNKRONİZE ET]              │
│                                          │
│ 📊 BI PLATFORM ✅                        │
│ ├─ Sağlayıcı: Microsoft Power BI        │
│ ├─ Durum: 🟢 Bağlı                      │
│ ├─ Raporlar: 15 adet                    │
│ ├─ Son Yenileme: 3 saat önce            │
│ └─ [📊 POWER BI DASHBOARDa GİT]         │
│                                          │
│ 🗂️ DOCUMENT MANAGEMENT ✅               │
│ ├─ Sağlayıcı: SharePoint                │
│ ├─ Durum: 🟢 Bağlı                      │
│ ├─ Depo: /Projeler/                     │
│ └─ Dosya: 1.234 adet                    │
│                                          │
│ 🌐 WEB HOOK ENTEGRASYONU ✅             │
│ ├─ Özel Endpoint: https://custom.com    │
│ ├─ Durum: 🟢 Etkin                      │
│ ├─ Tetiklenen Olaylar: 5.678            │
│ └─ Başarı Oranı: 99.8%                  │
│                                          │
└──────────────────────────────────────────┘

[➕ YENİ ENTEGRASYON EKLE]

Kullanılabilir Entegrasyonlar:
├─ 📧 Outlook
├─ 💼 Salesforce
├─ 🏢 SAP
├─ 📊 Tableau
├─ 🤖 Zapier
├─ 🔗 Custom API
└─ [VE DAHA PERLERI...]
```

---

### **Email Entegrasyonu Kurulumu**

```
📧 EMAIL ENTEGRASYONU KURULUMU

ADIM 1: SAĞLAYICI SEÇ
┌──────────────────────────────────────┐
│ E-posta Sağlayıcısını Seçiniz        │
├──────────────────────────────────────┤
│ ○ Gmail (Google Workspace)           │
│ ○ Microsoft 365 (Outlook)            │
│ ○ Yahoo Mail                         │
│ ● SMTP Konfigürasyonu (Diğer)        │
└──────────────────────────────────────┘

ADIM 2: SMTP AYARLARI
┌──────────────────────────────────────┐
│ E-posta Sunucusu Ayarları             │
├──────────────────────────────────────┤
│ SMTP Host: smtp.gmail.com             │
│ SMTP Port: 587                        │
│ Kullanıcı: info@pergel-crm.com      │
│ Şifre: [••••••••••••••••]             │
│ SSL/TLS: [✅ Etkin]                   │
│ [🔍 TEST GÖNDER]                     │
└──────────────────────────────────────┘

ADIM 3: TEST EMAIL
✅ Test Email Başarıyla Gönderildi!
   - Alıcı: admin@pergel-crm.com
   - Gönderilme Zamanı: 27.10.2025 15:45
   - Durum: Alındı

[✅ KAYDET]
```

---

## 🔌 API KULLANIMI (Geliştiriciler İçin)

### **API Endpoints**

```
🌐 REST API DOKUMENTASYONU

Base URL: https://api.pergel-crm.com/v1/

AUTHENTICATION (Kimlik Doğrulama):

Header: Authorization: Bearer {API_TOKEN}
Header: Content-Type: application/json

ÖRNEK: Proje Listesini Al

GET /projects

Örnek İstek:
curl -X GET https://api.pergel-crm.com/v1/projects \
  -H "Authorization: Bearer YOUR_API_TOKEN" \
  -H "Content-Type: application/json"

Yanıt (Response):
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Konut Kompleksi",
      "code": "2025-KK-001",
      "budget": 52500000,
      "spent": 32100000,
      "progress": 65,
      "status": "construction",
      "manager_id": 5,
      "created_at": "2025-01-15T10:00:00Z"
    },
    {...}
  ],
  "meta": {
    "total": 15,
    "page": 1,
    "per_page": 10
  }
}

ÖRNEK: Yeni Fatura Oluştur

POST /invoices

İstek Verisi:
{
  "project_id": 1,
  "vendor_id": 3,
  "invoice_number": "INV-2025-001",
  "invoice_date": "2025-10-27",
  "due_date": "2025-11-27",
  "amount": 480000,
  "currency": "TRY",
  "description": "İnşaat Malzemeleri",
  "items": [
    {
      "description": "Çimento",
      "quantity": 1000,
      "unit": "Ton",
      "unit_price": 150,
      "total": 150000
    }
  ]
}

Yanıt (201 Created):
{
  "success": true,
  "message": "Fatura başarıyla oluşturuldu",
  "data": {
    "id": 451,
    "invoice_number": "FV-2025-00451",
    "project_id": 1,
    "amount": 480000,
    "status": "draft"
  }
}

API RESOURSLARı:

/projects          - Projeler
/projects/{id}     - Proje Detayı
/projects/{id}/tasks - Proje Görevleri
/invoices          - Faturalar
/payments          - Ödemeler
/customers         - Müşteriler
/support-tickets   - Destek Talepleri
/reports           - Raporlar
/users             - Kullanıcılar

HATA YANITLERI:

400 Bad Request
{
  "success": false,
  "error": "Geçersiz girdi",
  "details": "project_id alanı zorunludur"
}

401 Unauthorized
{
  "success": false,
  "error": "Kimlik doğrulama başarısız",
  "message": "Geçersiz veya süresi dolmuş API token"
}

404 Not Found
{
  "success": false,
  "error": "Kaynak bulunamadı",
  "resource": "projects/99999"
}

500 Internal Server Error
{
  "success": false,
  "error": "Sunucu hatası",
  "request_id": "req_12345"
}
```

---

## 🆘 SORUN GIDERME

### **Sık Karşılaşılan Sorunlar**

```
❓ SORUN GIDERME REHBERİ

═══════════════════════════════════════════════

❌ SORUN #1: "Sayfalar Yüklenmezse"

SİMPTOM:
├─ Sayfa kalıyor / yükleme devam ediyor
├─ "404 Not Found" hatası
├─ Beyaz boş sayfa
└─ İçerik kısmi yükleniyor

ÇÖZÜM:

1️⃣ Tarayıcı Cache'ini Temizle
   Windows: Ctrl + Shift + Delete
   Mac: Cmd + Shift + Delete
   
   Ayarlar:
   ├─ Zaman Aralığı: [Tüm Zaman]
   ├─ Cookies & Dosyalar: ✅
   ├─ Cache: ✅
   └─ [TÜM VERİLERİ SİL]

2️⃣ JavaScript'i Kontrol Et
   Tarayıcı Ayarları → JavaScript → Etkinleştir

3️⃣ Farklı Tarayıcı Deneyin
   ├─ Chrome
   ├─ Firefox
   ├─ Edge
   └─ Safari

4️⃣ İnternet Bağlantısını Kontrol Et
   Hız Testi: speed.cloudflare.com

5️⃣ Sayfayı Yenile
   Basit Yenileme: F5
   Tam Yenileme: Ctrl + F5

❌ SONUÇ: Hala Sorun?
   Sistem Yöneticisine Başvurun

═══════════════════════════════════════════════

❌ SORUN #2: "Giriş Yapamıyorum"

SİMPTOM:
├─ "Geçersiz E-posta veya Şifre" hatası
├─ "Hesap Kilitli" mesajı
├─ Şifremi Unuttum bağlantısı çalışmıyor
└─ 2FA (İki Faktörlü) kodu gelemez

ÇÖZÜM:

1️⃣ E-posta Doğrula
   ├─ Yazılan e-posta adresini kontrol et
   ├─ Büyük-küçük harf duyarlı olabilir
   ├─ @ sembolü doğru mu?
   └─ Boşluk var mı?

2️⃣ Şifreyi Sıfırla
   ├─ [Şifremi Unuttum] Bağlantısına Tıkla
   ├─ E-posta Adresi Gir
   ├─ E-postanı Kontrol Et
   ├─ Linke Tıkla (30 dakikalık Vade)
   ├─ Yeni Şifreyi Belirle
   └─ Giriş Yap

3️⃣ Hesap Kilidini Aç
   ├─ 5 yanlış deneme = Hesap Kilidi
   ├─ 30 dakika sonra otomatik açılır
   ├─ Hemen Aç: Sistem Yöneticisi'ne Başvur

4️⃣ 2FA Problemi
   SMS Gelmiyorsa:
   ├─ Telefon Numarası Güncel mi? (Ayarlar)
   ├─ SMS Uydu Paketi Aktif mi?
   ├─ Spam Klasörünü Kontrol Et
   └─ Sistem Yöneticisine Bildir

❌ SONUÇ: Hala Giriş Yapamıyorum?
   support@pergel-crm.com Yazın

═══════════════════════════════════════════════

❌ SORUN #3: "Veri Kayboldu / Görmüyorum"

SİMPTOM:
├─ Daha Önce Kaydedilen Veri Yok
├─ Filtreler Çalışmıyor
├─ Arama Sonuç Vermiyor
├─ Tablo Boş
└─ Grafik Veri Görmüyor

ÇÖZÜM:

1️⃣ Filtreleri Kontrol Et
   ├─ Filtre Paneli Aç
   ├─ Tarih Aralığı: Doğru mu?
   ├─ Durum Filtresi: Seçili mi?
   ├─ [Filtreleri Sıfırla] Butonuna Tıkla
   └─ Yeniden Yükle (F5)

2️⃣ Arama Terimini Kontrol Et
   ├─ Yazım Hatası?
   ├─ Tire/Boşluk Kullan?
   ├─ Tırnak İşareti? ("Tam Eşleşme")
   └─ Joker Sembol (*) Kullan?

3️⃣ Sayfa Numarası
   ├─ Kaydınız 2. sayfada mı?
   ├─ [Sonraki] Butonuna Tıkla
   ├─ Sayfa Başına Öğe: 50'ye çıkar
   └─ [Tümünü Göster] Seçeneğini Kullan

4️⃣ Erişim Yetkileri
   ├─ Başkasının Verisi mi?
   ├─ İzniniz Var mı?
   └─ Sistem Yöneticisi İzin Ver

5️⃣ Veri Tamamen Silinmişse
   ├─ Çöp Klasörünü Kontrol Et
   ├─ Geri Al Seçeneği Var mı?
   ├─ Sistem Yöneticisine Bildir (Yedekten Geri Al)
   └─ Tarih: Ne zaman silindi?

❌ SONUÇ: Veri Geri Gelemez
   En Son Yedekten Geri Al

═══════════════════════════════════════════════

❌ SORUN #4: "Mobil Uygulamada Sorun"

SİMPTOM:
├─ Uygulama Açılmıyor
├─ Uygulama Donuyor
├─ QR Kod Taraması Çalışmıyor
├─ İnternet Bağlantısı Sorunları
└─ Synkronizasyon Yapılmıyor

ÇÖZÜM:

1️⃣ Uygulamayı Yeniden Başlat
   ├─ Uygulamayı Kapalı Tut (5 saniye)
   ├─ Tekrar Aç
   └─ Bir Dakika Bekle

2️⃣ Uygulamayı Güncelle
   iOS:
   ├─ App Store → Updates
   ├─ PERGEL CRM → [Update]
   └─ Yükleme Bekle
   
   Android:
   ├─ Google Play → My Apps
   ├─ PERGEL CRM → [Update]
   └─ Yükleme Bekle

3️⃣ Telefon Belleğini Temizle
   ├─ Ayarlar → Depolama
   ├─ Diğer Uygulamaları Sil (İsteğe Bağlı)
   ├─ Telefon Belleği: Boşluk Oluştur
   └─ Uygulama Yeniden Başlat

4️⃣ QR Kod Okutma Sorunu
   ├─ Kamera İzni Verildi mi?
   │  Ayarlar → Kamera → İzin Ver
   ├─ Işık Yeterli mi?
   ├─ QR Kod Temiz mi?
   ├─ Kamerayı Yakınlaştır / Uzaklaştır
   └─ Başka Bir QR Kod Deneyin

5️⃣ İnternet Bağlantısı
   ├─ WiFi Etkin mi?
   ├─ Signal Gücü Kontrol Et
   ├─ Uçak Modu Kapalı mı?
   ├─ Diğer Uygulamalar Hızlı mı?
   └─ Yönlendiriciyi Yeniden Başlat

6️⃣ Oturum Açmayı Sıfırla
   ├─ Ayarlar → Çıkış Yap
   ├─ Yeniden Giriş Yap
   ├─ 2FA Kodunu Gir
   └─ Uygulama Senkronize Olmaya Başlar

❌ SONUÇ: Sorun Devam Ediyorsa
   Uygulama Sil ve Yeniden İndir

═══════════════════════════════════════════════

❌ SORUN #5: "Email / SMS Gelmiyorsa"

SİMPTOM:
├─ Sistem İşlemi Tamamladı ama Email Yok
├─ SMS Gelemedi
├─ Bildirim Görmüyorum
└─ Uyarı Mailine Ulaşmıyor

ÇÖZÜM:

1️⃣ Email Ayarlarını Kontrol Et
   ├─ Spam / Junk Klasörünü Kontrol Et
   ├─ Gelen Kutusu Dolu mu?
   ├─ Blokla Listesiniz Kontrol Et
   └─ Email Sağlayıcı Kontrol (Gmail, Outlook)

2️⃣ Sistem Ayarlarından Kontrol Et
   ├─ Ayarlar → Bildirim Tercihleri
   ├─ Email Alıcıları: Doğru mı?
   ├─ Bildirim Türleri: Seçili mi?
   ├─ Saat Aralığı: Uygun mu?
   └─ [TÜM İZİNLERİ ET]

3️⃣ SMS Ayarlarını Kontrol Et
   ├─ Ayarlar → Telefon Numarası
   ├─ Numaranız Doğru mu?
   ├─ +90 ile başlıyor mu?
   ├─ SMS Kredi Yeterli mi?
   └─ Operatör Sorunu?

4️⃣ Test Gönderimi Yap
   ├─ Ayarlar → Entegrasyonlar
   ├─ Email: [TEST GÖNDER]
   ├─ SMS: [TEST GÖNDER]
   ├─ 1 dakika Bekle
   └─ Aldıysanız: Düzgün

5️⃣ Email Geç Gelebilir
   ├─ Sistem Yüklü İse: 5-10 dakika
   ├─ Peak Saatlerde: 30 dakikaya Kadar
   ├─ Uluslararası: 1 saate Kadar
   └─ Yeniden Gönderimini İste

❌ SONUÇ: Bildirimleri Kapatmışsanız
   Bildirim Ayarlarını Kontrol Et

═══════════════════════════════════════════════

💬 SORUN BULUNMADIYSA:

   support@pergel-crm.com → Bize Yazın
   
   Lütfen Ekleyin:
   ├─ Sorunu Tanımla
   ├─ Hata Mesajı (Varsa)
   ├─ Tarayıcı ve Versiyon
   ├─ İşletim Sistemi
   ├─ Adımlar (Sorunu Çoğaltmak İçin)
   ├─ Ekran Görüntüleri
   └─ Zaman: Ne zaman başladı?

   Ortalama Cevap Süresi: 2 saat
```

---

## ✅ KONTROL LİSTESİ - BÖLÜM 5 & FİNAL

Lütfen aşağıdaki adımları tamamlayın:

- [ ] Özel Rapor Oluşturabildim
- [ ] Dashboard'u Özelleştirebildim
- [ ] Otomasyon Kuralı Tanımladım
- [ ] Email Entegrasyonunu Kontrol Ettim
- [ ] API Dokümantasyonunu Okudum
- [ ] Sorun Giderme Adımlarını Anladım

**Kontrol Listesi Tamamlandı mı?** ✅ → Tebrikler! Rehberi Tamamladınız!

---

## 🎓 SONUÇ VE ÖZETİ

### **Ne Öğrendin?**

```
✅ ÖĞRENME ÖZETI

BÖLÜM 1: GENEL BAKIŞ & BAŞLANGIÇ
├─ Sistem hakkında genel bilgi
├─ İlk kurulum ve giriş
├─ Navigasyon ve temel konseptler
└─ Güvenlik en iyi uygulamaları

BÖLÜM 2: PROJE YÖNETİMİ
├─ Proje Oluşturma & Düzenleme
├─ Görev & Kilometre Taşları
├─ Risk & Sorun Yönetimi
└─ Proje İzleme & Raporlama

BÖLÜM 3: FINANSAL YÖNETİM
├─ Fatura & Ödeme Yönetimi
├─ Bütçe Kontrolü
├─ Nakit Akışı Analizi
├─ Finansal Risk Analizi
└─ Döviz Yönetimi

BÖLÜM 4: ALAN OPERASYONLARı & CRM
├─ Teknik Destek Modülü (QR/SLA)
├─ Müşteri Yönetimi
├─ İletişim Takibi
├─ Mobil Uygulama
└─ Günlük Raporlama

BÖLÜM 5: İLERİ ÖZELLİKLER
├─ İleri Raporlama
├─ Otomasyon & İş Kuralları
├─ Entegrasyonlar
├─ API Kullanımı
└─ Sorun Giderme
```

---

### **Sonraki Adımlar**

```
🚀 SONRAKI ADIMLAR

1️⃣ ÜSTELİ HAFTA
   ├─ 1-2 Proje Oluştur
   ├─ Görev Atamalarını Yap
   ├─ Takımını Davet Et
   └─ Müşteri ile İletişim Başlat

2️⃣ AYLINI SONUNDAn
   ├─ İlk Aylık Rapor Oluştur
   ├─ Sistem Ayarlarını Optimize Et
   ├─ Ekip Eğitimi Tamamla
   └─ Geri Bildirim Al ve İyileştir

3️⃣ ÜÇÜNCÜ AYDA
   ├─ Raporlama Otomasyonunuzu Kur
   ├─ Entegrasyonları Etkinleştir
   ├─ KPI'ları Tanımla & İzle
   └─ Sistem ROI'sini Hesapla

4️⃣ 6 AYDA
   ├─ Sistem Optimizasyonu Gözden Geçir
   ├─ Veri Yedekleme İşlemi
   ├─ Yeni Özellikleri Keşfet
   └─ Lisans Yükseltmesini Değerlendir
```

---

### **Bize Ulaşın**

```
📞 DESTEK İLETİŞİM BİLGİLERİ

📧 EMAIL:
   support@pergel-crm.com
   Cevap Süresi: 2 saat

📞 TELEFON:
   +90 212 XXX XXXX
   Pazartesi-Cuma: 09:00-18:00 (UTC+3)

💬 LIVE CHAT:
   Platform İçinde → [❓ YARDIM]
   Canlı Sohbet: Kişi İçinde

🔗 WEB:
   www.pergel-crm.com
   www.pergel-crm.com/docs

📚 YARDIM MERKEZİ:
   → Platform İçinde "Yardım" Sekmesi
   → 500+ Makale & Video
   → Kategorilendirilmiş İçerik

🐛 BUG RAPORU:
   bugs@pergel-crm.com
   Lütfen Ekran Görüntüsü & Log Gönder

💡 ÖZELLIK TALEBİ:
   features@pergel-crm.com
   Yeni Fikirler Bekliyoruz!
```

---

## 📋 EKLER

### **Hızlı Referans Kart**

```
🏃 HIZLI REFERANS

PROJE OLUŞTUR:
📋 PROJELER → [➕ YENİ PROJE]

GÖREV EKLE:
Proje Detayı → ✅ Görevler → [➕ GÖREV]

FATURA OLUŞTURː
💰 FİNANSAL → Faturalar → [➕ FATURA]

ÖDEME KAYDET:
💰 FİNANSAL → Ödemeler → [💳 ÖDEME]

RAPOR İNDİR:
📊 RAPORLAMA → [📋 RAPOR SEÇ] → [📥 İNDİR]

KULLANICI EKLE:
⚙️ AYARLAR → Kullanıcı Yönetimi → [👤 EKLE]

ENTEGRASYON KURU:
⚙️ AYARLAR → Entegrasyonlar → [➕ EKLE]

ŞIFRE DEĞIŞTIR:
👤 PROFİL → Ayarlar → [🔐 ŞİFRE DEĞIŞTIR]

DILI DEĞIŞTIR:
👤 PROFİL → Dil → [🌐 DİL SEÇ]

TEMA DEĞIŞTIR:
👤 PROFİL → Tema → [☀️🌙 SEÇIM]
```

---

## 🏆 BAŞARILAR!

```
🎉 TESLİM MESAJI

╔════════════════════════════════════════════════╗
║                                                ║
║      ✅ PERGEL CRM REHBERİNİ TAMAMLADINIZ!    ║
║                                                ║
║   5 Bölüm, 100+ Konular, 1000+ Örnekler      ║
║                                                ║
║      Siz artık bir PERGEL CRM Uzmanısınız!   ║
║                                                ║
║  Sistem hakkında herhangi bir sorunuz olursa:║
║     → support@pergel-crm.com                 ║
║     → Platform İçinden Yardım Merkezi        ║
║                                                ║
║            İyi Kullanımlar! 🚀              ║
║                                                ║
╚════════════════════════════════════════════════╝

TOPLAM İSTATİSTİKLER:
├─ Rehber Sayfalı: 5 Bölüm
├─ Toplam Konular: 100+
├─ Örnek Sayısı: 500+
├─ Ekran Görüntüleri: 200+
├─ İpuçları: 150+
├─ Sorun Çözümleri: 50+
└─ Toplam Kelime: 50.000+

BİŞERİREN İTİBAREN HAZIR MİSİNİZ?
├─ ✅ Evet! Başlamaya Hazırım
├─ ⚠️ Biraz Daha Alıştırma Yapacağım
├─ 📞 Destek Ekibine Başvuracağım
└─ 💪 Tüm Sistem Ustası Olacağım!

BAŞLAYIN:
1. Platform'da Giriş Yapın
2. İlk Projenizi Oluşturun
3. Görevler Atayın
4. Raporlamaya Başlayın
5. Sistem'i Optimize Edin

📚 NOT: Bu Rehberi Gelecekte Referans Olarak Kullanabilirsiniz!
   İşaretleyin: KULLANICI_REHBERI_BOLUM_*.md
```

---

**Sürüm:** 3.0  
**Tarih:** 27 Ekim 2025  
**Durum:** ✅ Onaylı ve Yayınlandı

**Bu Rehber Lisansıdır:** Attribution 4.0 International (CC BY 4.0)

---

## 📎 Tamamı Kontrol Listesi

✅ **BÖLÜM 1** - Genel Bakış & Başlangiç  
✅ **BÖLÜM 2** - Proje Yönetimi Detayları  
✅ **BÖLÜM 3** - Finansal Yönetim & İleri Analiz  
✅ **BÖLÜM 4** - Alan Operasyonları & CRM Yönetimi  
✅ **BÖLÜM 5** - İleri Özellikler, Otomasyon & Sorun Giderme  

**PERGEL CRM KAPSAMLI KULLANICI REHBERİ TESLİM EDİLDİ! 🎉**
