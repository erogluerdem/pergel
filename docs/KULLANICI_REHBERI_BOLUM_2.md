# 📚 PERGEL CRM SİSTEMİ - KAPSAMLI KULLANICI REHBERİ
## BÖLÜM 2: PROJE YÖNETİMİ DETAYLARI

**Tarih:** 27 Ekim 2025  
**Versiyon:** 3.0  
**Durum:** ✅ Aktif & Kullanıma Hazır

---

## 🎯 Bu Bölüm Nedir?

Bu bölümde öğrenecekleriniz:
- ✅ Yeni Proje Oluşturma
- ✅ Proje Detaylarını Düzenleme
- ✅ Görev ve Kilometre Taşları
- ✅ Proje İzleme ve İlerleme
- ✅ Risk ve Sorun Yönetimi
- ✅ Proje Filtreleme ve Arama

---

## 📋 PROJE OLUŞTURMA

### **ADIM 1: Proje Listesine Gidin**

**Navigasyon:** `📋 PROJELER → Tüm Projeler`

Veya direkt URL'ye gidin: `/projects`

```
┌───────────────────────────────────────────────────┐
│  PROJE LİSTESİ SAYFASI                           │
├───────────────────────────────────────────────────┤
│                                                   │
│  [🔍 Ara]  [🔽 Filtre]  [➕ YENİ PROJE]         │
│                                                   │
│  ┌─────────────────────────────────────────────┐ │
│  │ Proje Adı      │ Durum │ Bütçe   │ İlerleme│ │
│  ├─────────────────────────────────────────────┤ │
│  │ Konut Kompleksi│ İnşaat│ ₺52.5M │ 65%     │ │
│  │ Ticari Merkez  │ Bağlı │ ₺32.1M │ 42%     │ │
│  │ Altyapı Projesi│ Plan. │ ₺18.9M │ 15%     │ │
│  └─────────────────────────────────────────────┘ │
│                                                   │
│  Sayfa: [1] [2] [3] ...                          │
│                                                   │
└───────────────────────────────────────────────────┘
```

---

### **ADIM 2: "YENİ PROJE" Butonuna Tıklayın**

**Kırmızı "➕ YENİ PROJE" butonuna tıklayın**

Sizi Proje Oluşturma Formuna yönlendirecek.

---

### **ADIM 3: Proje Temel Bilgisini Doldurun**

#### **A) Proje Adı** *(Zorunlu)*
```
📝 Proje Adı
└─ Örnek: "Yüksek Katlı Konut Kompleksi - Beşiktaş"
└─ İpucu: Açıklayıcı ve net bir ad kullanın
└─ Karakter Limiti: 200 karakter
```

#### **B) Proje Kodu** *(İsteğe Bağlı)*
```
🔢 Proje Kodu
└─ Örnek: "2025-KK-001"
└─ İpucu: Kodlama sistemi kullanmak daha iyidir
└─ Format: YYYY-XX-NNN (Yıl-Tip-Numara)
```

#### **C) Proje Türü** *(Zorunlu)*
```
🏗️ Proje Türü [Seçim Yapın]
├─ 🏠 Konut (Residential)
│  └─ Kullanım: Apartman, Villa, Konut Sitesi
├─ 🏢 Ticari (Commercial)
│  └─ Kullanım: Ofis, Alışveriş Merkezi, Otel
├─ 🛣️ Altyapı (Infrastructure)
│  └─ Kullanım: Yol, Köprü, Su Tesisi
└─ 🏭 Endüstriyel (Industrial)
   └─ Kullanım: Fabrika, Depo, İmalatane
```

#### **D) Açıklama** *(İsteğe Bağlı)*
```
📄 Açıklama
└─ Örnek: "Beşiktaş'ta inşa edilecek, 250 konuttan 
           oluşan lüks konut kompleksi. 18 kat, 
           yer altı 3 kat."
└─ İpucu: Proje hakkında detaylı bilgi verin
└─ Karakter Limiti: 5000 karakter
```

---

### **ADIM 4: Mali Bilgileri Girin**

#### **E) Bütçe** *(Zorunlu)*
```
💰 Toplam Bütçe (TL)
└─ Örnek: 52500000
└─ İpucu: Virgül veya nokta kullanmayın
└─ Minimum: 1
└─ İşe Yarar: Harcamaları izlemek için gerekli
```

#### **F) Para Birimi** *(Varsayılan: TL)*
```
💵 Para Birimi [Seçim Yapın]
├─ 🇹🇷 Türk Lirası (TL)
├─ 💵 Amerikan Doları (USD)
├─ 💶 Euro (EUR)
├─ 🇬🇧 İngiliz Sterlini (GBP)
└─ 🇦🇪 Diğerleri...
```

#### **G) Harcanan Toplam** *(İsteğe Bağlı - Otomatik)*
```
📊 Harcanan Toplam (TL)
└─ Sistem otomatik olarak hesaplar
└─ Gelen Faturalar + Ödemeler toplamı
└─ Manuel giriş: [✏️ Düzelt] butonuna tıklayın
```

---

### **ADIM 5: Lokasyon Bilgisini Girin**

#### **H) Şehir / İl** *(Zorunlu)*
```
🏙️ Lokasyon - Şehir
├─ Türkiye'deki tüm şehirler listelenir
├─ Örnek: İstanbul
└─ Sistem: Dinamik liste - yazmaya başlayın
```

#### **İ) Belirtmek** *(Zorunlu)*
```
🗺️ Belirtmek / Bölge
├─ Örnek: Beşiktaş
├─ İpucu: Mahalle, ilçe veya bölge ad kullanın
└─ Karakter Limiti: 100 karakter
```

#### **J) Harita Koordinatları** *(İsteğe Bağlı)*
```
📍 Enlem (Latitude)
├─ Örnek: 41.0534
└─ Örnek Format: XX.XXXX

📍 Boylam (Longitude)
├─ Örnek: 29.0042
└─ Örnek Format: XX.XXXX

💡 İpucu: Google Haritalar → Sağ Tıkla → 
          Koordinatları kopyala
```

---

### **ADIM 6: Tarih Bilgisini Girin**

#### **K) Başlangıç Tarihi** *(Zorunlu)*
```
📅 Proje Başlangıç Tarihi
├─ Örnek: 15.01.2025
├─ Format: GG.AA.YYYY
└─ Takvim: Tıklayarak seçin
```

#### **L) Bitiş Tarihi (Planlanan)** *(Zorunlu)*
```
📅 Planlanan Bitiş Tarihi
├─ Örnek: 30.12.2026
├─ Format: GG.AA.YYYY
└─ İpucu: Başlangıçtan sonra bir tarih seçin
```

#### **M) Tahmini Süre** *(Otomatik Hesaplanır)*
```
⏱️ Tahmini Süre
├─ Sistem hesaplar: Bitiş - Başlangıç
├─ Örnek: 380 gün (12 ay 14 gün)
└─ Bilgi: Sadece okunabilir
```

---

### **ADIM 7: Sorumlu Kişileri Atayın**

#### **N) Proje Müdürü** *(Zorunlu)*
```
👨‍💼 Proje Müdürü [Seçim Yapın]
├─ Sistem: Kullanıcı listesi açılır
├─ Örnek: Ahmet Yılmaz
├─ İpucu: Yazmaya başlayarak arayabilirsiniz
└─ Sorumluluk: Proje genel yönetimi
```

#### **O) Teknik Müdürü / Şantiye Şefi** *(İsteğe Bağlı)*
```
👷‍♂️ Teknik Müdürü [Seçim Yapın]
├─ Sistem: Kullanıcı listesi açılır
├─ Örnek: Mehmet Demir
└─ Sorumluluk: Alan operasyonları
```

#### **P) Mali Sorumlu** *(İsteğe Bağlı)*
```
💰 Mali Sorumlu [Seçim Yapın]
├─ Sistem: Kullanıcı listesi açılır
├─ Örnek: Ayşe Kaya
└─ Sorumluluk: Bütçe ve finansman
```

---

### **ADIM 8: Ek Bilgiler**

#### **Q) Müşteri** *(İsteğe Bağlı)*
```
🤝 Müşteri / İnşaat Sahibi [Seçim Yapın]
├─ Sistem: Müşteri veritabanından seçin
├─ Örnek: ABC İnşaat A.Ş.
└─ İpucu: Yeni müşteri eklemek için [➕] tıklayın
```

#### **R) Kontratör / Yüklenici** *(İsteğe Bağlı)*
```
🏗️ Kontratör [Seçim Yapın]
├─ Sistem: Yüklenici listesinden seçin
└─ Örnek: XYZ İnşaat Müteahhitliği
```

#### **S) Durum** *(Zorunlu - Varsayılan: Planlama)*
```
🔄 Proje Durumu [Seçim Yapın]
├─ 📋 Planlama (Planning)
│  └─ Yeni oluşturulan projeler
├─ 🏷️ İhale (Bidding)
│  └─ İhale aşamasında
├─ ✍️ Sözleşmeli (Contracted)
│  └─ Sözleşme imzalandı
├─ 🏗️ İnşaat (Construction)
│  └─ Aktif inşaat
└─ ✅ Tamamlandı (Completed)
   └─ Proje bitmiş
```

---

### **ADIM 9: KAYDET**

**[💾 KAYDET] Butonuna tıklayın**

```
✅ Beklenen Sonuç:
├─ Sayfa yeşil kontrol işareti gösterecek
├─ "Proje başarıyla oluşturuldu" mesajı
└─ Sizi Proje Detay Sayfasına yönlendirecek
```

---

## 📝 PROJE DETAY SAYFASI

### **Sayfanın Yapısı**

```
┌─────────────────────────────────────────────────┐
│ PROJE: Yüksek Katlı Konut Kompleksi           │
├─────────────────────────────────────────────────┤
│                                                 │
│ [← GERİ]  [✏️ DÜZENLE]  [⋮ DAHA FAZLA]        │
│                                                 │
│ 📌 GENEL BİLGİLER                              │
│ ├─ Durum: 🏗️ İnşaat (% 65)                    │
│ ├─ Müdür: Ahmet Yılmaz                        │
│ ├─ Başlangıç: 15.01.2025                      │
│ └─ Bitiş: 30.12.2026                          │
│                                                 │
│ 💰 BÜTÇE BİLGİLERİ                             │
│ ├─ Toplam: ₺52.5M                             │
│ ├─ Harcanan: ₺32.1M (61%)                     │
│ └─ Kalan: ₺20.4M (39%)                        │
│                                                 │
│ 🗺️ LOKASYON                                    │
│ ├─ Şehir: İstanbul, Beşiktaş                  │
│ └─ Harita: [🗺️ Haritada Göster]               │
│                                                 │
│ 📊 SEKMELER                                     │
│ ├─ 📈 İzleme ve İlerleme                      │
│ ├─ ✅ Görevler ve Kilometre Taşları           │
│ ├─ 📋 Sözleşmeler ve Belgeler                 │
│ ├─ 💬 İletişim                                │
│ ├─ ⚠️ Riskler ve Sorunlar                     │
│ ├─ 📝 Faturalar ve Ödemeler                   │
│ └─ 📊 Raporlar ve Analizler                   │
│                                                 │
└─────────────────────────────────────────────────┘
```

---

### **SEKMELER - Detaylı İnceleme**

#### **1️⃣ İzleme ve İlerleme (Monitoring & Progress)**

```
📈 İlerleme Durumu
├─ Genel İlerleme: ████████░ 65%
├─ Planlanan İlerleme: ███████░░ 70%
├─ Fark: ⚠️ 5% GERİ
└─ Durum: 🟡 Riskli

⏱️ ZAMAN ANALİZİ
├─ Başlangıç: 15.01.2025
├─ Bugün: 27.10.2025 (9 ay 12 gün oldu)
├─ Bitiş: 30.12.2026 (1 yıl 3 ay kaldı)
├─ Bütçe Süresi: 24 ay
└─ Kullanılan Zaman: 40%

💰 BÜTÇE ANALİZİ
├─ Toplam Bütçe: ₺52.5M
├─ Harcanan: ₺32.1M (61%)
├─ Kalan: ₺20.4M (39%)
├─ Aylık Harcama: ₺3.5M ~
└─ Trend: 📈 Artış

🟢 SAĞLIK SKORU: 82/100
├─ Zaman Uygunluğu: 80/100
├─ Bütçe Uygunluğu: 85/100
├─ Kalite Skoru: 81/100
└─ Risk Seviyesi: 🟡 Orta
```

---

#### **2️⃣ Görevler ve Kilometre Taşları (Tasks & Milestones)**

```
✅ GÖREVLER LİSTESİ

[📊 İstatistik]
├─ Toplam: 45 görev
├─ Tamamlanan: 29 (%64)
├─ Devam Eden: 12 (%27)
├─ Planlanmış: 4 (%9)
└─ Geçikmiş: 2 ⚠️

[🔍 Filtrele]
├─ [Durum]  [Atanan]  [Tarih]
└─ [🔄 Sıfırla]

[📋 Görevler Tablosu]
┌─────────────────────────────────────────┐
│ Görev Adı          │ Atanan  │ Durum   │
├─────────────────────────────────────────┤
│ Temeller Kazısı    │ Mehmet  │ ✅ Tammam│
│ Depo Yönetimi      │ Ali     │ 🟡 %75  │
│ İnsan Kaynakları   │ Fatma   │ 🔵 Bekleme│
└─────────────────────────────────────────┘

🎯 KİLOMETRE TAŞLARI

[📍 Önemli Noktalar]
├─ Zemin Katı Tamamlandı: ✅ 15.03.2025
├─ İş Emri Alındı: ✅ 01.04.2025
├─ Şantiye Açılışı: 🟡 Devam (% 90)
└─ Ön Muayene: 🔵 Planlandı (15.11.2025)
```

---

#### **3️⃣ Sözleşmeler ve Belgeler**

```
📑 SÖZLEŞMELER

[➕ YENİ SÖZLEŞME]

📋 SÖZLEŞMELERİ LİSTESİ
├─ Ana Sözleşme (ABC İnşaat) - ₺45.2M
├─ Elektrik Altsözleşmesi - ₺4.8M
└─ İnşaat Müteahhidi - ₺2.5M

📄 BELGELER

[➕ BELGE YÜKLE]

📂 YÜKLENMİŞ BELGELER
├─ 📄 İmarPlnı_2025.pdf (2.3 MB)
├─ 📷 Şantiye_Fotoları.zip (15.2 MB)
├─ 📊 Detaylı_Proje.xlsx (3.1 MB)
└─ 📋 İnşaat_İzni.docx (1.2 MB)

[🔍 DOSYA ARAMASI]
└─ İsim, Tür, Tarih ile ara
```

---

#### **4️⃣ İletişim (Communication)**

```
💬 PROJE İLETİŞİM

[➕ YORUM YAZ]

📧 MESAJ THREADLARI
├─ [📌 Sabitlenmiş] Bütçe Güncellemesi (5 yorum)
├─ [🔴 Acil] Gece Vardiyası Problemi (12 yorum)
├─ [💼] Muhasebe Birimi İletişimi (8 yorum)
└─ [📝] Günlük Raporlar (89 yorum)

👥 TAKIP ÜYELERİ
├─ Ahmet Yılmaz (Proje Müdürü) 👁️
├─ Mehmet Demir (Teknik Müdürü) 👁️
├─ Ayşe Kaya (Mali Sorumlu) 👁️
└─ [➕ ÜYELER EKLE]
```

---

#### **5️⃣ Riskler ve Sorunlar**

```
⚠️ RİSKLER

📊 RİSK ÖZETI
├─ Toplam Riskler: 18
├─ 🔴 Kritik: 2
├─ 🟠 Yüksek: 5
├─ 🟡 Orta: 8
└─ 🟢 Düşük: 3

🔴 KRİTİK RİSKLER
├─ Döviz Dalgalanması (Etki: 95/100, Olasılık: 85%)
│  └─ Eylem: Döviz Riskini Hedge Etme
├─ İnşaat Malzeme Fiyatı Artışı (87/100, 75%)
│  └─ Eylem: Uzun Vadeli Sözleşmeler

❌ SORUNLAR

📋 SORUN LİSTESİ
├─ [🔴 Acil] Elektrik Tesisatı Gecikmesi
│  └─ Açıklama: Tedarikçi kesintisi nedeni ile...
│  └─ Sorumlu: Mehmet Demir
│  └─ Tarih: 25.10.2025
│  └─ Durum: Çözülüyor (% 60)
│
└─ [🟡 Orta] Şantiye Güvenlik Problemi
   └─ Açıklama: Gece nöbetçisi eksikliği...
   └─ Sorumlu: Ahmet Yılmaz
   └─ Tarih: 20.10.2025
   └─ Durum: Çözüldü ✅
```

---

#### **6️⃣ Faturalar ve Ödemeler**

```
💸 FINANSAL HAREKET

[➕ YENİ FATURA]

📊 FATURA ÖZETI
├─ Toplam Gelen Faturalar: ₺32.1M
├─ Ödenen: ₺28.5M (89%)
├─ Beklemede: ₺3.2M (10%)
└─ Reddedilen: ₺400K (1%)

💰 ÖDEME TABLOSU
├─ Toplam Ödemeler: ₺28.5M
├─ Bu Ay: ₺2.4M
├─ Sonraki Ay: ₺3.1M (Planlanmış)
└─ Gecikmiş Ödemeler: ₺150K ⚠️

📋 SON FATURALAR
├─ Elektrik Tedarikçi - ₺250K (Beklemede)
├─ İnşaat Malzeme - ₺480K (Ödendi)
└─ İşçi Ücretleri - ₺1.2M (Ödendi)
```

---

#### **7️⃣ Raporlar ve Analizler**

```
📊 RAPORLAMA

[📈 RAPORLAR]
├─ Haftalık İlerleme Raporu
├─ Aylık Finansal Raporu
├─ Üç Aylık Performans Raporu
└─ Yıllık Stratejik Raporu

[📉 ANALİZLER]
├─ Bütçe vs Gerçekleşme
├─ Zaman Varyasyon Analizi
├─ Risk Trend Analizi
└─ KPI Karşılaştırması

[📥 İNDİR]
├─ [PDF] Tüm Raporları Indir
├─ [EXCEL] Finansal Verileri Indir
└─ [CSV] Ham Verileri Indir
```

---

## 🎯 GÖREV OLUŞTURMA & YÖNETİMİ

### **ADIM 1: Görev Ekle**

**Proje Detay Sayfasından:**
1. **"✅ Görevler ve Kilometre Taşları"** sekmesine gidin
2. **"[➕ YENİ GÖREV]"** butonuna tıklayın

```
📝 YENİ GÖREV FORMU
├─ Görev Adı (Zorunlu)
│  └─ Örnek: "Temeller Kazı Işı"
├─ Açıklama (İsteğe Bağlı)
│  └─ Detaylı açıklama yazın
├─ Atanan Kişi (Zorunlu)
│  └─ Sorumlu çalışanı seçin
├─ Başlangıç Tarihi (Zorunlu)
│  └─ Görev başlangıç tarihi
├─ Bitiş Tarihi (Zorunlu)
│  └─ Görev bitiş tarihi
├─ Durum (Varsayılan: Planlandı)
│  ├─ 📋 Planlandı
│  ├─ 🔵 Başlangıç
│  ├─ 🟡 Devam Ediyor
│  ├─ ✅ Tamamlandı
│  └─ ❌ İptal Edildi
├─ Öncelik (Varsayılan: Normal)
│  ├─ 🟢 Düşük
│  ├─ 🟡 Normal
│  ├─ 🟠 Yüksek
│  └─ 🔴 Acil
└─ Dosya Eki (İsteğe Bağlı)
   └─ İlgili belgeleri yükle
```

---

### **ADIM 2: Görev Detayları**

**Görev tıklanınca açılan view:**

```
✅ Temeller Kazı Işı

📌 DETAYLAR
├─ Durum: 🟡 Devam Ediyor (% 75)
├─ Atanan: Mehmet Demir
├─ Başlangıç: 15.02.2025
├─ Bitiş: 28.02.2025
├─ Kalan: 3 gün
└─ Gecikme: -0 gün (Zamanında)

📊 İLERLEME
├─ Planlanan: 14 gün
├─ Kullanılan: 10.5 gün (75%)
├─ Grafik: ███████░░ 75%
└─ Yapılan: 10.5 / 14 gün

👥 TRİB ÜYELERİ
├─ Mehmet Demir (Lider)
├─ Ali Aytekin
├─ Fatma Öztürk
└─ [➕ ÜYELER EKLE]

💬 YORUMLAR & İLETİŞİM
├─ [Yorum Yaz...]
├─ Mehmet Demir (2 saat önce): "İlerleme yüksek..."
└─ Ahmet Yılmaz (1 gün önce): "Harika ilerleme..."

📎 DOSYALAR
├─ Kazı_Planı.pdf (1.2 MB)
├─ Günlük_Rapor.xlsx (500 KB)
└─ [➕ DOSYA YÜKLE]
```

---

## 🚩 RİSK YÖNETİMİ

### **Risk Türleri ve Örnekleri**

```
⚠️ RİSK TÜRLERİ

🔴 FİNANSAL RİSKLER
├─ Döviz Dalgalanması
├─ Fiyat Artışları
├─ Ödeme Problemleri
└─ Nakit Akışı Sorunları

🔴 PROJE RİSKLERİ
├─ Gecikme Riski
├─ Kaliite Sorunu
├─ Çalışan Eksikliği
└─ Ekipman Sorunu

🔴 OPERASYON RİSKLERİ
├─ İş Güvenliği Problemi
├─ Çevre Sorunları
├─ Tedarik Zinciri Kırılması
└─ Yasal Uyum Sorunu

🔴 PAZARLAMA RİSKLERİ
├─ Müşteri Memnuniyeti
├─ Rekabet
├─ Pazar Değişimi
└─ Talep Azalması
```

---

### **Risk Puanlamması**

```
📊 RİSK SKORU HESAPLAMA

Risk Skoru = Etki × Olasılık / 100

Etki Puanı (0-100):
├─ 0-25   → Önemsiz (Yeşil)
├─ 26-50  → Düşük (Yeşil)
├─ 51-75  → Orta (Sarı)
├─ 76-90  → Yüksek (Turuncu)
└─ 91-100 → Kritik (Kırmızı)

Olasılık Puanı (0-100):
├─ 0-25   → Çok Düşük
├─ 26-50  → Düşük
├─ 51-75  → Orta
├─ 76-90  → Yüksek
└─ 91-100 → Çok Yüksek

Örnek: Döviz Dalgalanması
├─ Etki: 95/100 (Çok yüksek)
├─ Olasılık: 85/100 (Yüksek)
├─ Risk Skoru = 95 × 85 / 100 = 80.75
└─ Seviye: 🔴 KRITIK
```

---

### **Risk Azaltma Stratejileri**

```
🛡️ RİSK AZALTMA YÖNTEMLERİ

1. KAÇINMA (Avoidance)
   ├─ Risk oluşturacak işlemi yapma
   └─ Örnek: Risky tedarikçiden sipariş verme

2. HAFIFLETME (Mitigation)
   ├─ Risk olasılığını veya etkisini azalt
   └─ Örnek: Döviz riskini Hedge etme

3. TRANSFER (Transfer)
   ├─ Riski başkasına aktar
   └─ Örnek: Sigorta ile koruma

4. KABUL (Acceptance)
   ├─ Riski kabul et ve plan yap
   └─ Örnek: Yedek bütçe ayırma
```

---

## ✅ KONTROL LİSTESİ - BÖLÜM 2

Lütfen aşağıdaki adımları tamamlayın:

- [ ] Yeni Proje Oluşturdum
- [ ] Proje Temel Bilgilerini Doldurdum
- [ ] Proje Detay Sayfasının Tüm Sekmelerini Gördüm
- [ ] En Az Bir Görev Oluşturdum
- [ ] Görev İlerleme Durumunu Güncelledim
- [ ] Risk Tanımını Anladım
- [ ] Proje Filtreleme Özelliğini Kullandım
- [ ] Proje Arama Yapabiliyorum

**Kontrol Listesi Tamamlandı mı?** ✅ → Sonraki bölüme geçebilirsiniz!

---

## 🎯 SONRAKI BÖLÜM

**BÖLÜM 3: FINANSAL YÖNETİM & RAPORLAMA**
- Fatura ve Ödeme Yönetimi
- Bütçe Kontrolü
- Nakit Akışı Analizi
- Finansal Risk Analizi
- Raporlar ve İhraç

👉 **Devamı için:** `KULLANICI_REHBERI_BOLUM_3.md`

---

**Sürüm:** 3.0  
**Tarih:** 27 Ekim 2025  
**Durum:** ✅ Onaylı ve Yayınlandı
