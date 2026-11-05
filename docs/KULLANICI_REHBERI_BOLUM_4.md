# 📚 PERGEL CRM SİSTEMİ - KAPSAMLI KULLANICI REHBERİ
## BÖLÜM 4: ALAN OPERASYONLARı & CRM YÖNETİMİ

**Tarih:** 27 Ekim 2025  
**Versiyon:** 3.0  
**Durum:** ✅ Aktif & Kullanıma Hazır

---

## 🎯 Bu Bölüm Nedir?

Bu bölümde öğrenecekleriniz:
- ✅ Teknik Destek Modülü (QR Kod, SLA)
- ✅ Müşteri Yönetimi (CRM)
- ✅ İletişim ve İş Takibi
- ✅ Günlük Raporlar
- ✅ Mobil Uygulama Kullanımı
- ✅ Entegrasyonlar

---

## 🔧 TEKNIK DESTEK MODÜLÜ (QR Kod & SLA)

### **QR Kod Sistemi Nedir?**

```
🔲 QR KOD SİSTEMİ

QR Kod = Quick Response Code (Hızlı Yanıt Kodu)

AMACI:
├─ Tesis varlıklarını tanımlama
├─ Hızlı talep açma
├─ Mobil cihazdan okuma
└─ Takiyi otomatikleştirme

FLOW DIYAGRAMI:

Santiye (Müşteri)
│
├─ QR Kod Okur (Telefon Kamerası)
│  └─ Beispiel: 🔲 TESIS-2025-00015
│
├─ "Destek Talebi Aç" Tıklar
│
├─ Hızlı Form Doldurur:
│  ├─ Sorun Tanımı
│  ├─ Fotoğraf Ekler
│  └─ Lokasyon Seçer
│
└─ "Gönder" Tıklar
   │
   └─> PERGEL SİSTEMİ
       │
       ├─ Talebini Kaydeder
       ├─ Email Gönderir
       ├─ SMS Bildirim Gönderir
       └─ Dashboard'da Görünür

ADMIN TARAFINDA:
│
├─ Talep Geldi Bildirimini Alır
├─ Talep Detaylarını Görür
├─ QR Kod Ile Varlığı Bulur
├─ SLA Hedefini Kontrol Eder
├─ Atama Yapıştırır
└─ Müşteriye Bildirim Gönderir
```

---

### **QR Kod Oluşturma**

**Navigasyon:** `🏗️ ALAN OPERASYONLARı → Tesis Yönetimi → QR Kod Yönetimi`

```
📋 QR KOD OLUŞTURMA FORMU

ADIM 1: KALEM TİPİ SEÇ
[Seçim Yapınız]
├─ 🏢 Bina / Yapı
├─ 🔧 Ekipman / Makine
├─ 💧 Su Tesisatı
├─ ⚡ Elektrik Tesisatı
├─ 🔨 Araçlar
└─ 📦 Depo Malları

ADIM 2: KALEM BİLGİSİ
Kalem Adı: [________________]
Örnek: "Asansör #3 - Lobby"

Kalem No: [________________]
Sistem Tarafından Otomatik Atanır

Açıklama: [________________]
İçerik: "Yük Kapasitesi: 2.5 Ton..."

Lokasyon: [________________]
Örnek: "4. Kat, Batı Kolu"

Sorumlu: [________________]
Örnek: "Ahmet Yılmaz"

ADIM 3: KOD AYARLARI
├─ Kod Tipi: Standart (QR Kod)
├─ Boyut: Küçük (50x50 mm)
├─ Barında Bilgi:
│  ├─ ☑️ Kalem Adı
│  ├─ ☑️ Kalem No
│  ├─ ☑️ Lokasyon
│  └─ ☑️ Link (Talep Açma)
└─ Renkler:
   ├─ Ön Plan: Siyah
   └─ Arka Plan: Beyaz

ADIM 4: OLUŞTUR VE YAZDIR
[🔲 OLUŞTUR]
│
└─> ✅ QR Kod Oluşturuldu
    │
    ├─ [🖨️ YAZDIR]
    ├─ [📥 İNDİR] (PNG, PDF)
    └─ [📧 GÖNDER]
```

---

### **QR Kodları İndirme ve Yerleştirme**

```
📥 TOPLU YAZDIRMA

1. Birden Çok QR Kodu Seç:
   ├─ [Seçim Alanı] Tıkla
   ├─ Kodlar listesinden seç
   ├─ Toplu İşlemler:
   │  ├─ [☑️ TÜMSÜNÜ SEÇ]
   │  ├─ [☐ SEÇİMİ KALDIRI]
   │  └─ [🔲 15 KOD SEÇİLDİ]
   └─ [🖨️ TOPLU YAZDIR]

2. Yazdırma Ayarları:
   ├─ Kağıt Tipi: A4
   ├─ Sayfa Başına: 6 x 4 = 24 QR
   ├─ Çerçeve: Kalın (Kolay Kesme)
   └─ Kalite: Yüksek (300 DPI)

3. PDF Dosyası:
   ├─ qr_kodlari_batch_001.pdf (2.4 MB)
   ├─ Toplam: 8 sayfa
   ├─ 192 adet QR Kod
   └─ [🖨️ YAZDIR] [📥 İNDİR]

4. FİZİKİ YERLEŞTIRME:
   ├─ Kesme: Makas veya Kesme Makinesinden
   ├─ Lamina: Su geçirmez laminasyon
   ├─ Yapıştırma: Güçlü yapışkan
   ├─ Lokasyon:
   │  ├─ Asansör: Kontrol Paneli Yanında
   │  ├─ Pompa: Yazılı Plaka Yanında
   │  ├─ Elektrik: Pano Kapağında
   │  └─ Araç: Ön Cam İçinde
   └─ Kontrol: Kameradan Test Okuma
```

---

### **SLA (Hizmet Seviyesi Sözleşmesi) Yönetimi**

```
⏱️ SLA NEDİR?

SLA = Service Level Agreement (Hizmet Seviyesi Anlaşması)

AMACI:
├─ Talep Yanıtlama Zamanını Belirlemek
├─ Çözüm Sürelerini Garantilemek
├─ Müşteri Memnuniyetini Sağlamak
└─ Performansı Ölçmek

ÖRNEK SLA:

Destek Talebi Kategorileri:

1. 🔴 KRİTİK (Critical)
   ├─ Tanım: Tam kesinti, acil durum
   ├─ Örnek: Asansör Takılı (Kişi İçeride)
   ├─ İlk Yanıt: 15 dakika
   ├─ Çözüm Süresi: 1 saat
   ├─ Destek: 7x24 (Haftanın 7 günü)
   └─ Ceza: Her saat ₺1.000

2. 🟠 YÜKSEK (High)
   ├─ Tanım: Ciddi kesinti, işleri durdurdu
   ├─ Örnek: Elektrik Kesintisi
   ├─ İlk Yanıt: 1 saat
   ├─ Çözüm Süresi: 4 saat
   ├─ Destek: Mesai saatleri (08:00-18:00)
   └─ Ceza: Her saat ₺500

3. 🟡 ORTA (Medium)
   ├─ Tanım: Kısmi sorun, etkileme var
   ├─ Örnek: Musluk Akması
   ├─ İlk Yanıt: 4 saat
   ├─ Çözüm Süresi: 24 saat
   ├─ Destek: Mesai saatleri
   └─ Ceza: Günlük ₺250

4. 🟢 DÜŞÜK (Low)
   ├─ Tanım: Küçük sorun, rutine
   ├─ Örnek: Toz Temizliği
   ├─ İlk Yanıt: 24 saat
   ├─ Çözüm Süresi: 5 iş günü
   ├─ Destek: Hafta İçi
   └─ Ceza: Yok
```

---

### **SLA Ayarları Yapılandırma**

**Navigasyon:** `🏗️ ALAN OPERASYONLARı → SLA Ayarları`

```
⚙️ SLA AYARLARI PANELI

[➕ YENİ SLA TANIMI]

SLA #1: Asansör Bakım
├─ 📋 TEMEL BİLGİLER
│  ├─ SLA Adı: "Asansör Acil Destek"
│  ├─ Açıklama: "Asansör arızaları - Acil"
│  ├─ Etkin Mi?: ☑️ Evet
│  └─ Öncelik: 🔴 Kritik
│
├─ ⏱️ ZAMAN HEDEFLERİ
│  ├─ İlk Yanıt: 15 dakika
│  ├─ Çözüm: 1 saat
│  ├─ İş Saatleri: 24/7 (Tüm saat)
│  └─ Weekend: Kapalı ☐
│
├─ 📞 BİLDİRİM AYARLARI
│  ├─ İlk Yanıt Uyarısı: 10 dakika sonra
│  ├─ Deadline Uyarısı: 50 dakika sonra
│  ├─ Bildirim Kanalları:
│  │  ├─ ☑️ Email
│  │  ├─ ☑️ SMS
│  │  ├─ ☑️ In-App Notification
│  │  └─ ☑️ Push Notification
│  └─ Alıcılar: (Yazı alanı)
│     "ahmet@pergel.com; mehmet@pergel.com"
│
├─ 💰 CEZA / KOMİSYON
│  ├─ İlk Yanıt Aşıldı: ₺1.000/saat
│  ├─ Çözüm Süresi Aşıldı: ₺2.000/saat
│  ├─ Maksimum Ceza/Gün: ₺10.000
│  └─ Toplam Ceza: Otomatik Hesapla
│
└─ [💾 KAYDET] [🗑️ SİL]

[✅ UYGULANMIŞLAR]
├─ SLA #1: Asansör Acil (Aktif)
├─ SLA #2: Elektrik Arızası (Aktif)
└─ SLA #3: Rutin Bakım (Aktif)
```

---

### **Destek Talepleri İzleme**

```
📋 TALEP İZLEME DASHBOARD

Navigasyon: 🏗️ ALAN OPERASYONLARı → Destek Talepleri

┌───────────────────────────────────────────┐
│ TALEP ÖZETI (27.10.2025)                  │
├───────────────────────────────────────────┤
│                                           │
│ 📊 DÖNEM İSTATİSTİKLERİ:                 │
│ ├─ Toplam Talepler: 87                    │
│ ├─ 🔴 Açık / Atanmamış: 12 ⚠️             │
│ ├─ 🟠 Atanmış / Çalışıyor: 23             │
│ ├─ 🟡 Çözülüyor: 18                       │
│ └─ ✅ Kapatılmış: 34 (39%)                 │
│                                           │
│ ⏱️ ORTALAMA SÜRELER:                      │
│ ├─ İlk Yanıt: 42 dakika (SLA: 1 saat) ✅ │
│ ├─ Çözüm: 8 saat (SLA: 4 saat) ⚠️        │
│ └─ Müşteri Memnuniyeti: 4.2/5.0 ⭐      │
│                                           │
│ 🚨 UYARILAR:                              │
│ ├─ 3 talep SLA'yı aşıyor                  │
│ ├─ 1 talep Kritik öncelikte                │
│ └─ 2 talep cevap bekliyor                 │
│                                           │
└───────────────────────────────────────────┘

📑 TALEP TABLOSU

[🔍 FİLTRE]
├─ Durum: [Tüm Durumlar ▼]
├─ Öncelik: [Tüm Öncelikler ▼]
├─ Tarih: [Son 30 Gün ▼]
└─ [🔄 Uygula] [🔄 Sıfırla]

┌─────────────────────────────────────────┐
│ # │ Talep ID │ Konu │ Ön. │ Durum │ SLA│
├─────────────────────────────────────────┤
│1  │ TK-025-1 │ Asa. │🔴  │ Açık │ ⏰ │
│2  │ TK-025-2 │ Elek │🟠  │ Çalş │ ✅ │
│3  │ TK-025-3 │ Su   │🟡  │ Çöz. │ ✅ │
│4  │ TK-025-4 │ Kapı │🟢  │ Kapı │ ✅ │
│   │          │      │    │      │    │
│[◀ Önceki] [1] [2] [3] [Sonraki ▶]     │
└─────────────────────────────────────────┘

TALEP DETAYI (TK-025-1):
├─ 🔴 Asansör Arızası (Kritik)
├─ 📍 4. Kat Asansörü #3
├─ 👤 Talep Veren: Ali Bilir (Sayfa Müdürü)
├─ 📅 Talep Tarihi: 27.10.2025 - 14:30
├─ ⏱️ SLA: 15 dakika (Aşıldı: -5 dakika) ❌
├─ 👨‍🔧 Atanan: (Atanmamış)
├─ 📝 Tanım: "Asansör iniş-çıkış yapmıyor"
├─ 📸 Fotoğraf: 2 adet
└─ ➕ YÖNETİM EYLEMLERİ:
   ├─ [👷 ATA] → Teknisyen Atanması
   ├─ [💬 MESAJ] → Müşteri ile İletişim
   ├─ [📝 NOT] → İç Not Ekle
   └─ [✅ KAPAT] → Talebi Kapatmak
```

---

## 👥 MÜŞTERI YÖNETİMİ (CRM)

### **Müşteri Veritabanı**

**Navigasyon:** `👥 CRM & İLİŞKİLER → Müşteriler`

```
📋 MÜŞTERI LİSTESİ

[➕ YENİ MÜŞTERI] [🔍 ARA] [📊 RAPOt]

┌──────────────────────────────────────────┐
│ KUL.   │ ŞİRKET ADIDDAN │ TELEFON │İL   │
├──────────────────────────────────────────┤
│001     │ ABC İnşaat A.Ş. │ 2125551 │İST  │
│002     │ XYZ Proje Ltd.  │ 2125552 │İZM  │
│003     │ Best Build      │ 2125553 │BRU  │
│[◀ Önceki] [1] [2] [3] [Sonraki ▶]      │
└──────────────────────────────────────────┘

MÜŞTERI DEBİLİ ÖZETİ:

   Müşteri: ABC İnşaat A.Ş.
   ┌────────────────────────────────┐
   │                                │
   │ 🆔 TEMEL BİLGİLER              │
   │ ├─ Müşteri No: 001             │
   │ ├─ Statüsü: 🟢 Aktif           │
   │ ├─ Kategorisi: Kurumsal        │
   │ ├─ İlişki Başlangıcı: 01.01.20 │
   │ └─ Değerlendirme: ⭐⭐⭐⭐⭐ 5/5 │
   │                                │
   │ 📞 İLETİŞİM BİLGİLERİ         │
   │ ├─ Telefon: 0212 555 11 11     │
   │ ├─ E-posta: info@abcinşaat.com │
   │ ├─ Web: www.abcinşaat.com      │
   │ └─ Adres: Beşiktaş, İstanbul   │
   │                                │
   │ 💼 İŞLETME BİLGİLERİ          │
   │ ├─ Vergi No: 1234567890        │
   │ ├─ MERSIS No: 9876543210       │
   │ ├─ Sektör: İnşaat & Gayrimenkul│
   │ └─ Çalışan Sayısı: 500+        │
   │                                │
   │ 💰 MALİ BİLGİLER               │
   │ ├─ Toplam İşlem: ₺45.2M        │
   │ ├─ Bu Yıl: ₺12.5M              │
   │ ├─ Ortalama Ödeme: 28 gün      │
   │ ├─ Kredi Limiti: ₺5M           │
   │ ├─ Aktif Bakiye: ₺850K         │
   │ └─ Borç Bilgisi: Temiz ✅      │
   │                                │
   │ 📊 PERFORMANs                  │
   │ ├─ Proje Sayısı: 5             │
   │ ├─ Toplam Değer: ₺15.8M        │
   │ ├─ Ortalama Proje: ₺3.16M      │
   │ ├─ Başarı Oranı: 100% ✅       │
   │ └─ Tamamlama Hızı: Zamanında   │
   │                                │
   │ 📌 TİCARİ BİLGİLER            │
   │ ├─ Bölge Müdürü: Mehmet Demir  │
   │ ├─ Muhasebeci: Ayşe Kaya       │
   │ ├─ Teknik Sorumlu: Ali Aytekin │
   │ └─ Son İletişim: 26.10.2025    │
   │                                │
   └────────────────────────────────┘

MÜŞTERI EYLEMLERI:
├─ [📞 ARA]      → Telefon Geçmişi
├─ [📧 E-POSTA]  → Mail Gönder
├─ [📝 DOSYA]    → Dosya Görüntüle
├─ [📊 RAPORt]   → Müşteri Raporu
└─ [✏️ DÜZENLE]  → Bilgiler Değiştir
```

---

### **Müşteri İletişim Takibi**

```
💬 MÜŞTERİ İLETİŞİM LOG'U

Müşteri: ABC İnşaat A.Ş.
Proje: Yüksek Katlı Konut Kompleksi

[➕ İLETİŞİM KAYDI EKLE]

┌────────────────────────────────────┐
│ TARİH │ TİP │ KONU │ NOT │ SONUÇ   │
├────────────────────────────────────┤
│27 Ekim│ ☎️  │ Ödeme│Nett │ Kabul   │
│26 Ekim│ 📧  │ Rapp│ Send│ Görmedi │
│25 Ekim│ 💬  │ Plan│ Oké │ Onaylı  │
│25 Ekim│ 🔔  │ Uyar│ SLA │ Cevap   │
│24 Ekim│ ☎️  │ Fatu│ Soru│ Yanıtl. │
│       │     │     │     │         │
└────────────────────────────────────┘

SON İLETİŞİM DETAYI:

27 Ekim 2025 - 14:30 (Telefon Araması)

Arayan: Mehmet Demir (Bölge Müdürü)
Arama Süresi: 12 dakika
Konu: "Ödeme Planı Konusunda Müzakere"

Not: "Müşteri 3. ödemeyi 5 gün ertelemek 
      istiyor. Nakit akış problemi var 
      diyor. İndirim teklifine olumlu 
      baktı. Sonraki hafta teyit yapacak."

Sonuç: ✅ Kabul - İndirim: %2, Tarih: 05.11

TAKIP EDİLECEK:
├─ 📅 Takvime Bildirim Ekle (05.11.2025)
├─ 📧 Resmi Email Gönder
├─ 📞 Günün Sonunda Teyit Ara
└─ 📊 CRM'de İşlem Kaydet
```

---

### **Fırsat & Teklif Yönetimi**

```
🎯 FIRSATLAR (Opportunities)

Navigasyon: 👥 CRM & İLİŞKİLER → Fırsatlar

FıRSAT TÜRLERI:
├─ 🆕 YENİ PROjE İLE İLGİLİ FıRSAT
├─ 💼 ÜÜ BÖLÜM İLE İLGİLİ FıRSAT
├─ 🔄 EK İŞ / DESTEK HİZMETİ
└─ 🏢 MÜŞTERI GENİŞLEME

FıRSAT TABLOSU:

[➕ YENİ FıRSAT]

┌───────────────────────────────────────┐
│ FIRSATIN │ MÜŞTERI │ DEĞER │ TARAF │ % │
├───────────────────────────────────────┤
│001       │ ABC     │ ₺8M   │ Tik. │ 60│
│002       │ XYZ     │ ₺5.2M │ Tal. │ 35│
│003       │ Best    │ ₺3.1M │ Redd │ 10│
│          │         │       │      │   │
└───────────────────────────────────────┘

FıRSAT DETAYI (FRS-001):

Proje: Yeni Ofis Kompleksi
Müşteri: ABC İnşaat A.Ş.
Tahmini Değer: ₺8M
Olasılık: 60% (Orta-Yüksek)
Beklenen Kapatma: 30.11.2025

Durum: 🟡 Müzakere Aşaması
├─ 📊 Teklif Gönderildi: 20.10.2025
├─ 💬 Müşteri Yorumu: "İyiye benziyor"
├─ 💰 Beklenmeyen Rekabeti: ₺7.8M
└─ ⏱️ Karar Tarihi: 30.10.2025 (3 gün)

ÖNERİLER:
├─ Fiyat Revizyonunu İndir (-₺200K)
├─ Müşteri Ziyareti Yap
├─ Referans Proje Gönder
└─ Teknik Spec'i Uyarla

TEKLIF YÖNETIMI:

📋 TEKLIF (QUOTE) FORMU

[➕ YENİ TEKLIF]

Teklif No: QUOTE-2025-001 (Otomatik)
Müşteri: [Seçim Yapınız]
Fırsat: [Seçim Yapınız]
Teklif Tarihi: 27.10.2025
Geçerlilik Tarihi: 30.11.2025 (Vade: 34 gün)

KALEMLER:

Sıra │ Açıklama │ Miktar │ Birim Fiyatı │ Toplam
──────────────────────────────────────────
1    │ A+ Desen │ 1200   │ ₺4.500      │ ₺5.4M
2    │ Kurulum  │ 1      │ ₺1.2M       │ ₺1.2M
3    │ Test     │ 1      │ ₺400K       │ ₺400K
──────────────────────────────────────────
                 Toplam (Brüt): ₺7M
                 KDV %18: ₺1.26M
                 Grand Total: ₺8.26M

KOŞULLAR:
├─ Ödeme Planı: 3 Taksit (Başlangıç, Orta, Bitiş)
├─ Teslim: 120 gün sonra
├─ Garanti: 2 yıl
└─ Geçerlilik: 34 gün

EYLEMLER:
├─ [📧 GÖNDER]   → Müşteriye E-posta
├─ [📄 PDF]      → PDF Dosyası İndir
├─ [✅ ONAYLA]   → Teklifi Onayla
└─ [❌ REDDET]   → Teklifi Reddet
```

---

## 📱 MOBİL UYGULAMA KULLANIMI

### **Mobil App Özellikleri**

```
📱 PERGEL CRM MOBİL UYGULAMASI

⬇️ KURULUM

iOS:
├─ App Store'dan "PERGEL CRM" arayın
├─ "PERGEL CRM" uygulamasına tıklayın
└─ [⬇️ İNDİR] [☁️ YÜKLEMEİ] - Kurulum başlar

Android:
├─ Google Play'den "PERGEL CRM" arayın
├─ Resmi PERGEL CRM uygulamasına tıklayın
└─ [⬇️ İNDİR] [☁️ KURAL] - Kurulum başlar

⚙️ KURULUMDAN SONRA:

1. Uygulamayı Açın
2. E-posta ile Giriş Yapın
3. Şifre Girin
4. İki Faktörlü Doğrulama (Varsa)
5. Dashboard'ı Göreceksiniz

📋 MOBİL DASHBOARD

HOME EKRANI:
┌────────────────────────────────┐
│ ☰ PERGEL CRM     🔔 1     👤   │
├────────────────────────────────┤
│ Hoş Geldiniz, Mehmet Demir     │
│                                │
│ HIZLI ERIŞIM:                  │
│ ├─ 🏗️ [Projeler]              │
│ ├─ 👥 [Müşteriler]             │
│ ├─ 📞 [Destek Talepleri]       │
│ └─ 💰 [Faturalar]              │
│                                │
│ ⚠️ ÖNEMLİ BİLDİRİMLER:         │
│ ├─ 🔴 Asansör Arızası (Kritik) │
│ ├─ 🟠 Elektrik Sorunu          │
│ └─ 🟡 Raporlandı               │
│                                │
│ 📊 BENİM VERILERIM:           │
│ ├─ Açık Görevler: 8            │
│ ├─ Gecikmiş: 2 ⚠️              │
│ └─ Bu Hafta Yapılan: 15        │
│                                │
└────────────────────────────────┘

QR KOD OKUMA:

Destek Talebi Açmak İçin:

1. [🔲 QR OKUT] Butonuna Tıkla
2. Kamera Aç ve QR Kodunu Tara
3. Sistem Otomatik Talep Formu Açar
4. Formu Doldur:
   ├─ Sorun Tanımı: [_____________]
   ├─ Aciliyet: [Seç]
   ├─ Fotoğraf: [📸 Çek] [+] [+]
   └─ [GÖNDER]
5. ✅ Talep Kaydedildi
   └─ ID: TK-2025-002345
   └─ Bildirim: SMS & Email

MOBİL PROJE TAKİBİ:

[Projeler] → Proje Seç
├─ 📊 İlerleme: ████████░ 65%
├─ 📅 Tarih: Başlangıç: 15.01 | Bitiş: 30.12
├─ 💰 Bütçe: ₺52.5M | Harcanan: ₺32.1M (61%)
├─ ⚠️ Risk: 72/100 (Yüksek)
├─ 👥 Ekip: 15 Üye
├─ ✅ Görevler: 45 (29 Tamamlanan)
└─ [🔔 BİLDİRİMLER] [📞 İLETİŞİM]

MOBİL CHAT:

💬 Takım Sohbeti
├─ Ahmet (10 dakika önce):
│  "Temeller tamamlandı! ✅"
├─ Mehmet (5 dakika önce):
│  "Fotoları gönder"
├─ Ali (Şimdi yazıyor...):
│  "Okey bekle"
└─ [MESAJ TİP ET...]

OFFLİNE ÇALIŞMA:

📡 Çevrimdışı Mod
├─ İnternet Yok ↓ Otomatik Açılır
├─ Yapılabilenler:
│  ├─ ✅ Yerel Verileri Görüntüle
│  ├─ ✅ Fotoğraf Çek & Kaydet
│  ├─ ✅ Not Yazıp Kaydet
│  ├─ ✅ Ses Kayıt Yap
│  └─ ✅ Form Doldur (Gönderme: İnternet Gelince)
└─ Internet Gelince: Otomatik Senkronizasyon
```

---

## 📊 RAPORLAMA ÖZÜR ÖZELLIKLERI

### **Günlük Rapor Oluşturma**

```
📝 GÜNLÜK RAPOR (Daily Report)

Navigasyon: 🏗️ ALAN OPERASYONLARı → Günlük Raporlar

[➕ YENİ GÜNLÜK RAPOR]

RAPOR TARİHİ: 27.10.2025 (Bugün)
PROJE: [Proje Seçiniz]
RAPORLAYAN: Mehmet Demir

HAVA KOŞULLARI:
├─ ☀️ Hava: Açık
├─ 🌡️ Sıcaklık: 22°C
├─ 💨 Rüzgar: 10 km/h
└─ 🌧️ Yağış: Yok

GÜNÜN ÖZETI:
[Bugün neler yapıldı?]
├─ Temeller: 90% tamamlandı
├─ MEP Resimleri: Başlandı
├─ Yangın Söndürme: Kuruldu
└─ Sistem Testleri: Başlangıç

SAAT KURULU:
Time     Aktivite                    Kişi Sayısı
08:00    Sabah Toplantısı           20
09:00    Temeller Hazırlığı         35
12:00    Öğle Molası                -
13:00    MEP Resim Kurulumu         25
17:00    İşçi Çıkışı                -
─────────────────────────────────────────────
Toplam Saat: 6 saat | Ortalama İşçi: 28

EKIPMAN KULLANIMI:
├─ Vincin: 8 saat
├─ Ekskavatörü: 6 saat
├─ Kompresörü: 4 saat
└─ Welding Cihazları: 3 saat

YAŞANAN SORUNLAR:
├─ 🔴 Vinç Arızası (Çözüldü)
│  └─ Elektrik Bağlantısı gevşek
├─ 🟡 Malzeme Gecikmesi
│  └─ Tedarikçi 2 gün gecikmişler
└─ 🟢 Kaza Yok

UYARLAR & UYARILAR:
├─ ⚠️ Güvenlik Ekipmani Eksikliği (3 kişi)
├─ ⚠️ Elektrik Hattı Taraması Gerekiyor
└─ ⚠️ Vinç İnspeksiyonu (Aylık)

FOTOĞRAF & BELGELER:
[📸 FOTOĞRAF YÜKLE]
├─ morning_site_photo.jpg
├─ foundation_progress.jpg
├─ equipment_photo.jpg
└─ [➕ DAHA FAZLA]

YAPILACAKLAR (Yarın):
├─ ✓ Temeller Bitiş Evi
├─ ✓ MEP Tesisatı (Başlangıç)
├─ ✓ İş Güvenliği Toplantısı
└─ ✓ Müşteri Ziyareti

SORUMLU: Ahmet Yılmaz (Proje Müdürü)
├─ Onay: [ONAYLANACAK]
└─ [💾 KAYDET] [📧 GÖNDER] [🖨️ YAZDIR]
```

---

## ✅ KONTROL LİSTESİ - BÖLÜM 4

Lütfen aşağıdaki adımları tamamlayın:

- [ ] QR Kod Sistemi Hakkında Bilgi Aldım
- [ ] En Az Bir QR Kod Oluşturdum
- [ ] SLA Ayarlarını Gördüm ve Anladım
- [ ] Destek Talebi Açabiliyorum
- [ ] Müşteri Kaydı Oluşturabildim
- [ ] Müşteri Detaylarını Güncelledim
- [ ] İletişim Geçmişi Kaydettim
- [ ] Mobil Uygulamayı Indirdim ve Kullandım
- [ ] Günlük Rapor Oluşturabildim

**Kontrol Listesi Tamamlandı mı?** ✅ → Sonraki bölüme geçebilirsiniz!

---

## 🎯 SONRAKI BÖLÜM

**BÖLÜM 5: İLERİ ÖZELLİKLER & İNTEGRASYONLAR**
- Raporlama Araçları
- Otomasyon ve İş Kuralları
- Üçüncü Parti Entegrasyonları
- API Kullanımı
- Sorun Giderme

👉 **Devamı için:** `KULLANICI_REHBERI_BOLUM_5.md`

---

**Sürüm:** 3.0  
**Tarih:** 27 Ekim 2025  
**Durum:** ✅ Onaylı ve Yayınlandı
