# 📚 PERGEL CRM SİSTEMİ - KAPSAMLI KULLANICI REHBERİ
## BÖLÜM 3: FINANSAL YÖNETİM & İLERİ ANALİZ

**Tarih:** 27 Ekim 2025  
**Versiyon:** 3.0  
**Durum:** ✅ Aktif & Kullanıma Hazır

---

## 🎯 Bu Bölüm Nedir?

Bu bölümde öğrenecekleriniz:
- ✅ Fatura ve Ödeme Yönetimi
- ✅ Bütçe Planlama ve Kontrolü
- ✅ Nakit Akışı Tahmin ve Analiz
- ✅ Finansal Risk Analizi
- ✅ Döviz Yönetimi
- ✅ Finansal Raporlar ve İhraç

---

## 💰 FATURA YÖNETİMİ

### **Fatura Türleri**

```
📋 FATURA KATEGORILARI

1️⃣ GELEN FATURALAR (Incoming Invoices)
   ├─ Tedarikçilerden gelen faturalar
   ├─ Müteahhitlerin faturaları
   ├─ Hizmet sağlayıcı faturaları
   └─ Örnekler:
      • Elektrik Tedarikçi: ₺250K
      • İnşaat Malzeme: ₺480K
      • İşçi Ücretleri: ₺1.2M

2️⃣ GIDEN FATURALAR (Outgoing Invoices)
   ├─ Müşteriye gönderilen faturaları
   ├─ Tamamlanan işler için tahsilatlar
   ├─ Dönem sonu hak ediş faturaları
   └─ Örnekler:
      • Yapı Sahibi: ₺2.5M (Ödeme Planı #1)
      • Proje Tamamlama: ₺150K
      • Ek İşler: ₺320K

3️⃣ KALDIRAÇ NOTLARI (Credit Notes)
   ├─ Hatalı veya iptal edilen işler
   ├─ İade işlemleri
   └─ Örnek:
      • Kusurlu Malzeme İadesi: -₺45K
```

---

### **Fatura Oluşturma**

**Navigasyon:** `💰 FİNANSAL → Faturalar`

```
📝 YENİ FATURA FORMU

┌──────────────────────────────────────┐
│  FATURA BİLGİSİ                     │
├──────────────────────────────────────┤
│                                      │
│ 🔢 Fatura Numarası (Otomatik)       │
│    └─ Sistem tarafından atanır      │
│    └─ Format: FV-2025-00001         │
│                                      │
│ 📅 Fatura Tarihi (Zorunlu)         │
│    └─ Bugün: [28.10.2025] [Takvim] │
│                                      │
│ 📅 Vade Tarihi (Zorunlu)           │
│    └─ Örnek: 30.11.2025            │
│    └─ Vade: 33 gün                  │
│                                      │
│ 🏢 Fatura Gönderen                  │
│    ├─ Tedarikçi Adı (Zorunlu)       │
│    ├─ Vergi No: ______________      │
│    ├─ Adres: _______________        │
│    └─ E-posta: ______________       │
│                                      │
│ 🎯 Fatura Alan (Alıcı)             │
│    ├─ Şirket Adı (Zorunlu)          │
│    ├─ Vergi No: ______________      │
│    ├─ Adres: _______________        │
│    └─ E-posta: ______________       │
│                                      │
│ 📌 Proje / Bölüm                    │
│    └─ [Proje Seç] [Bölüm Seç]      │
│    └─ Bu faturayla ilgili proje     │
│                                      │
└──────────────────────────────────────┘
```

---

### **Fatura Kalemleri Ekleme**

```
📋 FATURA KALEM TABLOSU

[➕ KALEM EKLE]

┌─────────────────────────────────────────────┐
│ # │ Açıklama  │ Miktar │ Birim │ Tutar    │
├─────────────────────────────────────────────┤
│ 1 │ Çimento   │ 1000   │ Ton   │ ₺150.000 │
│ 2 │ Demir     │ 500    │ Ton   │ ₺280.000 │
│ 3 │ İşçilik   │ 1200   │ SM    │ ₺50.000  │
│   │           │        │       │          │
│   │ [➕ EKLE] │        │       │          │
│                                            │
│ Toplam Tutar:              ₺480.000      │
│ KDV (%18):                 ₺86.400        │
│ Brüt Toplam:               ₺566.400       │
└─────────────────────────────────────────────┘
```

---

### **Fatura Durum Yönetimi**

```
🔄 FATURA DURUM İŞLEMLERİ

DURUM AKIŞI:

1. 📝 HAZIRLANMA
   ├─ Yeni fatura oluşturuldu
   ├─ Bilgiler henüz tamamlanmadı
   └─ Eylem: [✏️ DÜZENLE] veya [🗑️ SİL]

2. 📤 GÖNDERME
   ├─ Fatura hazır, eksik bilgi yok
   ├─ Tedarikçiye/Müşteriye gönderildi
   └─ Eylem: [📧 YENİDEN GÖNDER]

3. ✅ ONAYLANMA
   ├─ Alıcı tarafından onaylandı
   ├─ Muhasebede kaydedildi
   └─ Eylem: [💰 ÖDE] veya [📝 NOT EKLE]

4. 💳 ÖDEME
   ├─ Ödeme yapıldı/alındı
   ├─ Bütçeden düşüldü
   └─ Eylem: [🧾 ÖDEMEYİ GÖR]

5. 📊 ARŞİV
   ├─ İşi bittikten sonra arşivlendi
   ├─ İstatistikler güncellendi
   └─ Eylem: [🔍 DETAY GÖR]

KALEM DEĞİŞTİRME:
├─ Beklemede: ✅ Düzenlenebilir
├─ Onaylanmış: ⚠️ Sınırlı değişiklik
├─ Ödenen: ❌ Düzenlenemez
└─ Arşiv: ❌ Görüntülenebilir
```

---

## 💸 ÖDEME YÖNETİMİ

### **Ödeme Yöntemleri**

```
💳 ÖDEME ÇEŞITLERI

1. 🏦 BANKA TRANSFERI (Most Common)
   ├─ Banka Adı: Garanti Bankası
   ├─ Hesap Türü: Ticari Cari
   ├─ IBAN: TR12 0123 4567 8901...
   ├─ Tutar: ₺480.000
   ├─ Referans: "FV-2025-00048"
   └─ ⏱️ Süresi: 1-2 iş günü

2. 💵 NAKİT ÖDEME
   ├─ Tutar: ₺50.000
   ├─ Yer: Proje Sahasında
   ├─ Tarih: 28.10.2025
   ├─ İmza: 2 tanık + Mali Sorumlu
   └─ ⏱️ Süresi: Anında

3. 💳 KREDI KARTI
   ├─ Kart Sahibi: ABC İnşaat A.Ş.
   ├─ Son 4 Hane: 1234
   ├─ Tutar: ₺320.000
   ├─ İşlem Tarihi: 28.10.2025
   └─ ⏱️ Süresi: 3-5 gün (ekstrede görünüş)

4. 📝 ÇEK
   ├─ Çek No: 0000123
   ├─ Banka: Ziraat Bankası
   ├─ Tutar: ₺150.000
   ├─ Son Tarih: 15.11.2025
   └─ ⏱️ Süresi: Vadesinde tahsilat

5. 💰 KREDİ MEKTUBU (L/C)
   ├─ L/C No: LC-2025-001
   ├─ Tutar: ₺2.500.000
   ├─ Geçerlilik: 30.12.2025
   └─ ⏱️ Süresi: Vade sonu

6. 🔄 BÜTÇE TRANSFERI (Internal)
   ├─ Gönderen Proje: Proje A
   ├─ Alan Proje: Proje B
   ├─ Tutar: ₺500.000
   └─ ⏱️ Süresi: Anında
```

---

### **Ödeme İzleme**

```
📊 ÖDEME DÜŞÜRÜLECEKLER LİSTESİ

Navigasyon: 💰 FİNANSAL → Ödemeler

[🔍 FİLTRE] [📊 VİZUAL] [📥 İNDİR]

┌──────────────────────────────────────────────┐
│ ÖDEME TABLOSU                                │
├──────────────────────────────────────────────┤
│                                              │
│ Tarih    │ Kimden    │ Tutar   │ Durum     │
├──────────────────────────────────────────────┤
│ 28.10    │ FV-001    │ ₺480K   │ ✅ Ödandi  │
│ 29.10    │ FV-002    │ ₺250K   │ ⏳ Bekleme │
│ 30.10    │ FV-003    │ ₺320K   │ ⏳ Bekleme │
│ 31.10    │ FV-004    │ ₺150K   │ ⚠️ Gecikme │
│                                              │
│ Toplam Ödenecek: ₺1.2M                      │
│ Toplam Ödenen: ₺480K (40%)                  │
│ Beklemede: ₺570K (47.5%)                    │
│ Gecikmiş: ₺150K (12.5%)                     │
│                                              │
└──────────────────────────────────────────────┘

⏰ ÖDEME TAHMİNİ (Takvim Görünümü)

           EKIM 2025
     Pzt Salı Çarş Perş Cuma
     27  28   29   30   31
             • ₺480K ✅
                  • ₺250K ⏳
                       • ₺320K ⏳
                            • ₺150K ⚠️

KASIM 2025
     Pzt Salı Çarş Perş Cuma
      3   4    5    6    7
     • ₺200K (Planlanmış)
```

---

## 📊 BÜTÇE YÖNETİMİ

### **Bütçe Yapısı**

```
💰 BÜTÇE HİYERARŞİSİ

PROJE BÜTÇESI
│
├─ 📊 BÜTÇE KALEMLER
│  │
│  ├─ 🔨 İnşaat İşleri
│  │  ├─ Temeller: ₺5.2M
│  │  ├─ Ayakkabı: ₺4.8M
│  │  ├─ Saçaklar: ₺3.1M
│  │  └─ Dönermenler: ₺2.4M
│  │
│  ├─ 🏗️ Yapısal İşler
│  │  ├─ Perde Duvarlar: ₺8.3M
│  │  ├─ Taşıyıcı Kolonlar: ₺6.9M
│  │  └─ Kalas Yapılar: ₺4.1M
│  │
│  ├─ 💧 MEP (Mekanik, Elektrik, Tesisatı)
│  │  ├─ Elektrik Sistemi: ₺4.2M
│  │  ├─ Su Tesisatı: ₺2.8M
│  │  ├─ Isıtma Sistemi: ₺3.1M
│  │  └─ Klima: ₺2.5M
│  │
│  ├─ 🎨 İç Mekan Tasarımı
│  │  ├─ Badana ve Boyama: ₺1.8M
│  │  ├─ Zemin Kaplamaları: ₺3.2M
│  │  ├─ Kabin ve Kapılar: ₺2.4M
│  │  └─ Mobilya: ₺1.6M
│  │
│  └─ 👥 İnsan Kaynakları
│     ├─ Yönetim Ekibi: ₺2.1M
│     ├─ Teknik Ekip: ₺3.4M
│     ├─ İşçiler: ₺4.8M
│     └─ Diğer Personel: ₺1.2M
│
├─ 📈 TOPLAM BÜTÇE: ₺52.5M
├─ 💸 HARCANAN: ₺32.1M (61%)
├─ 🔓 KALAN: ₺20.4M (39%)
└─ ⚠️ RISK: Planlanan kalmayan
```

---

### **Bütçe Kontrolü Matrisi**

```
📊 BÜTÇE DURUM MATRISI

Kalemi Seçin    │ Bütçe  │ Harcanan │ % │ Durum
────────────────┼────────┼──────────┼───┼──────────
Temeller        │ ₺5.2M  │ ₺4.8M    │92 │ 🟢 OK
Ayakkabı        │ ₺4.8M  │ ₺4.5M    │94 │ 🟡 Dikkat
MEP Elektrik    │ ₺4.2M  │ ₺5.1M    │121│ 🔴 AŞIM!
Badana/Boyama   │ ₺1.8M  │ ₺0.9M    │50 │ 🟢 OK
İnsan Kaynakları│ ₺11.5M │ ₺6.8M    │59 │ 🟢 OK
────────────────┴────────┴──────────┴───┴──────────
TOPLAM          │ ₺52.5M │ ₺32.1M   │61 │ 🟡 Kontrol

Varyans Analizi (Variance Analysis)
├─ Olay İyi Varyans (Favorable)
│  └─ Bütçe Tasarrufu: Harcanan < Bütçe
│  └─ Örnek: Temeller (₺400K tasarruf)
│
├─ Olumsuz Varyans (Unfavorable)
│  └─ Bütçe Aşımı: Harcanan > Bütçe
│  └─ Örnek: MEP Elektrik (₺900K aşım)
│
└─ Yönetim Eylemi
   ├─ Fazla Kalemden Transfer
   ├─ Proje Kapsamını Düşür
   ├─ Ek Bütçe Iste
   └─ Risk Azaltma Önlemleri Al
```

---

## 🔮 NAKIT AKIŞI TAHMİNİ & ANALİZ

### **Nakit Akışı Nedir?**

```
💰 NAKİT AKIŞI KAVRAMSAL ÖRNEK

AYLAR      │ Ocak   │ Şubat  │ Mart   │ Nisan
───────────┼────────┼────────┼────────┼────────
GELİŞLER   │        │        │        │
├─ Satış   │ ₺2.5M  │ ₺3.2M  │ ₺2.8M  │ ₺3.5M
├─ Ödeme   │ ₺2.0M  │ ₺2.8M  │ ₺2.6M  │ ₺3.2M
└─ Toplam  │ ₺2.0M  │ ₺2.8M  │ ₺2.6M  │ ₺3.2M

ÇIKIŞLAR   │        │        │        │
├─ Ödeme   │ ₺1.8M  │ ₺2.1M  │ ₺2.4M  │ ₺2.2M
├─ Masraf  │ ₺0.6M  │ ₺0.8M  │ ₺0.7M  │ ₺0.9M
└─ Toplam  │ ₺2.4M  │ ₺2.9M  │ ₺3.1M  │ ₺3.1M

NET AKIŞ   │ -₺0.4M │ -₺0.1M │ -₺0.5M │ +₺0.1M
           │ (Açık) │ (Açık) │ (Açık) │ (Fazla)

BIRIKINTI  │        │        │        │
├─ Başla   │ ₺5.0M  │ ₺4.6M  │ ₺4.5M  │ ₺4.0M
├─ Net     │ -₺0.4M │ -₺0.1M │ -₺0.5M │ +₺0.1M
└─ Sonu    │ ₺4.6M  │ ₺4.5M  │ ₺4.0M  │ ₺4.1M
```

---

### **Tahmini Nakit Akışı Raporu**

```
📈 AYLYSAK AKIŞ TAHMİNİ (6 AY)

Navigasyon: 💰 FİNANSAL → Nakit Akış Tahmini → Dashboard

┌──────────────────────────────────────────────────────────┐
│ NAKIT AKIŞI TAHMİNİ DASHBOARD                           │
├──────────────────────────────────────────────────────────┤
│                                                          │
│ 📊 ÖZETİ:                                              │
│ ├─ Toplam Beklenen Gelir: ₺18.2M                       │
│ ├─ Toplam Beklenen Gider: ₺16.5M                       │
│ ├─ Net Pozisyon: +₺1.7M                                │
│ └─ Güven Seviyesi: 87% ✅                              │
│                                                          │
│ 📉 AYLIK GRAFİK:                                        │
│                                                          │
│    5M │                                                 │
│    4M │ ┌─┐                                            │
│    3M │ │ │     ┌─┐                                    │
│    2M │ │ │     │ │     ┌─┐                            │
│    1M │ │ │     │ │     │ │     ┌─┐                    │
│    0M │─┼─┴─┬───┼─┴─┬───┼─┴─┬───┼─┴─┬───               │
│   -1M │ │   │   │   │   │   │   │   │                  │
│        │ Oca Şub Mar Nis May Haz                        │
│        │ Sarı: Gelir  Mavi: Gider                      │
│        │                                                │
│ 🎯 ÖNEMLİ NOKTALAr:                                    │
│ ├─ Şubat: Minimum Nakit (-₺0.1M) ⚠️                   │
│ ├─ Mayıs: Maksimum Nakit (+₺0.8M) 🟢                  │
│ └─ Sonbahar: İyileşme Trendi 📈                        │
│                                                          │
│ 💡 ÖNERİLER:                                           │
│ ├─ Şubat için ek nakit rezervi sağla                   │
│ ├─ Ödeme Planını mayıs ve sonrasına çek                │
│ └─ Tedarikçilerle uzun vadeli anlaşma yap             │
│                                                          │
└──────────────────────────────────────────────────────────┘

📋 DETAYLI AYLYK TABLO:

Ay         Gelir   Gider  Net     Birikim  Durumu
───────────────────────────────────────────────────
Ocak       ₺2.5M   ₺2.4M  +100K   ₺5.1M    ✅
Şubat      ₺3.2M   ₺2.9M  +300K   ₺5.4M    ✅
Mart       ₺2.8M   ₺3.1M  -300K   ₺5.1M    🟡
Nisan      ₺3.5M   ₺3.1M  +400K   ₺5.5M    ✅
Mayıs      ₺3.2M   ₺2.8M  +400K   ₺5.9M    ✅
Haziran    ₺3.0M   ₺2.2M  +800K   ₺6.7M    ✅
───────────────────────────────────────────────────
TOPLAM     ₺18.2M  ₺16.5M +1.7M   (Son)    ✅
```

---

## ⚠️ FINANSAL RİSK ANALİZİ

### **Risk Türleri**

```
⚠️ FİNANSAL RİSK ÇEŞITLERI

1. 💳 ÖDEME RİSKİ (Payment Risk)
   ├─ Müşteri / Tedarikçi ödeme yapmayabilir
   ├─ Ödeme geç kalabilir
   ├─ Kısmi ödeme yapılabilir
   └─ RİSK SKORU: Müşteri Bonotu × Tarihçe
      • Yüksek Risk: ₺1M+ önemli müşteri
      • Düşük Risk: Sürekli ödeyen müşteri

2. 💱 DÖVIZ RİSKİ (Exchange Rate Risk)
   ├─ Döviz Kuru Değişimi
   ├─ Yabancı Para Borcu/Alacağı
   ├─ İthalatçı İnşaatçılar için Kritik
   └─ RİSK SKORU: Döviz Volatilitesi
      • Örnek: ₺50M USD cinsinden proje
      • +10% USD → ₺5M Zarar
      • -10% USD → ₺5M Kâr

3. 🏦 NAKİT RİSKİ (Liquidity Risk)
   ├─ Nakit Eksikliği
   ├─ Ödemeleri Zamanında Yapamama
   ├─ Borçlanmaya Mecbur Kalma
   └─ RİSK SKORU: Birikim Seviyeleri
      • Kritik: Birikinti < 3 ay gideri
      • Yüksek: Birikinti < 6 ay gideri
      • Normal: Birikinti > 6 ay gideri

4. 💰 BAJETİZLEME RİSKİ (Budgeting Risk)
   ├─ Bütçe Hata / Eksik Öngörü
   ├─ Niyet Kapsamı Değişimi
   ├─ Malzeme Fiyat Artışı
   └─ RİSK SKORU: Varyans %
      • Düşük Risk: 0-5% Varyans
      • Orta Risk: 5-10% Varyans
      • Yüksek Risk: 10%+ Varyans

5. 🌍 PAZAR RİSKİ (Market Risk)
   ├─ Fiyat Dalgalanmaları
   ├─ Talep Değişimleri
   ├─ Rekabet Artışı
   └─ RİSK SKORU: Sektör Analizi
      • Düşük: Stabil sektör & müşteri
      • Yüksek: Volatile sektör & yeni müşteri
```

---

### **Risk Analiz Raporu**

```
📊 PROJE RİSK ANALIZ RAPORU

Navigasyon: 💰 FİNANSAL → Risk Analizi → Dashboard

┌────────────────────────────────────────────┐
│ PROJE: Yüksek Katlı Konut Kompleksi       │
│ TARİH: 27.10.2025                         │
├────────────────────────────────────────────┤
│                                            │
│ 🎯 GENEL RİSK SEVİYESİ: 🟠 YÜKSEK       │
│    RİSK SKORU: 72/100                     │
│                                            │
│ 🔴 KRİTİK RİSKLER (Risk Score > 80)    │
│ ├─ Döviz Dalgalanması (85/100)           │
│ │  ├─ Etki: 95 (Çok Yüksek)              │
│ │  ├─ Olasılık: 85 (Çok Yüksek)          │
│ │  ├─ Tehdit: ₺5.2M Kaybı Riski          │
│ │  └─ Azaltma: FX Forward Sözleşmesi     │
│ │                                         │
│ └─ Malzeme Fiyat Artışı (82/100)         │
│    ├─ Etki: 90 (Çok Yüksek)              │
│    ├─ Olasılık: 75 (Yüksek)              │
│    ├─ Tehdit: ₺3.8M Kaybı Riski          │
│    └─ Azaltma: Uzun Vadeli Sözleşme     │
│                                            │
│ 🟠 YÜKSEK RİSKLER (Risk Score 60-79)   │
│ ├─ Ödeme Gecikmeleri (68/100)             │
│ ├─ İnsan Kaynakları Sorunu (65/100)       │
│ └─ Tedarik Zinciri Kesintisi (62/100)    │
│                                            │
│ 🟡 ORTA RİSKLER (Risk Score 40-59)     │
│ ├─ Proje Gecikmeleri (55/100)             │
│ └─ Kalite Sorunları (48/100)              │
│                                            │
│ 🟢 DÜŞÜK RİSKLER (Risk Score 0-39)     │
│ ├─ Yasal Uyum (25/100)                    │
│ └─ Çevre Sorunları (32/100)               │
│                                            │
│ 📈 TAVSIYE EDLEN İŞLEMLER:               │
│ ├─ ✅ FX Forward Sözleşmesi Yap           │
│ ├─ ✅ Malzeme Tedarikçileriyle Anlaş     │
│ ├─ ✅ Nakit Rezervi Artır                │
│ └─ ✅ Sigorta Kapsamı Gözden Geçir       │
│                                            │
└────────────────────────────────────────────┘
```

---

## 💵 DÖVIZ YÖNETİMİ

### **Döviz Kurları**

```
💱 DÖVIZ KURU TABLOSU

Navigasyon: 💰 FİNANSAL → Döviz Yönetimi

┌──────────────────────────────────────────┐
│ DÖVIZ KURLAR (27.10.2025 itibaren)     │
├──────────────────────────────────────────┤
│                                          │
│ 💵 USD / TL                             │
│    Satış: 32.45 ₺                       │
│    Alış: 32.15 ₺                        │
│    Fark: 0.30 ₺ (0.93%)                 │
│    Trend: 📈 +0.5 ₺ (5 gün içinde)     │
│                                          │
│ 💶 EUR / TL                             │
│    Satış: 34.82 ₺                       │
│    Alış: 34.52 ₺                        │
│    Fark: 0.30 ₺ (0.87%)                 │
│    Trend: 📉 -0.2 ₺ (5 gün içinde)     │
│                                          │
│ 🇬🇧 GBP / TL                            │
│    Satış: 40.15 ₺                       │
│    Alış: 40.85 ₺                        │
│    Fark: 0.30 ₺ (0.74%)                 │
│    Trend: 📈 +0.8 ₺ (5 gün içinde)     │
│                                          │
└──────────────────────────────────────────┘

📊 DÖVIZ PORTFÖYÜ

Proje: Konut Kompleksi

Para Birimi │ Tutar      │ Kur  │ TL Değeri │ Durum
────────────┼────────────┼──────┼───────────┼──────
USD         │ $1.000.000 │ 32.45│ ₺32.45M   │ 🟡 Riski
EUR         │ €250.000   │ 34.82│ ₺8.705M   │ 🟢 OK
GBP         │ £100.000   │ 40.15│ ₺4.015M   │ 🟡 Riski
────────────┴────────────┴──────┴───────────┴──────
TOPLAM                                ₺45.17M

DÖVIZ RİSK ANALIZI
├─ USD Risking: +$100K harcama = +₺3.245M
├─ EUR Risking: Diğerleri ile dengelenmiş
└─ Hedge Stratejisi: Forward Sözleşme önerilir
```

---

### **FX Forward Sözleşmesi**

```
📋 FX FORWARD SÖZLEŞME (Döviz Vadeli Sözleşme)

Nedir?
├─ Gelecekte belirli bir tarihte
├─ Belirli bir kürle
├─ Döviz alma/satma taahhüdü
└─ Kur dalgalanması riskinden koruma

Örnek:
├─ Bugün: USD/TL = 32.45
├─ İhtiyaç: 6 ay sonra $1.000.000
├─ Riskil: Kür 35'e çıkarsa = ₺2.55M zarar
├─ Çözüm: Forward Sözleşme
│  └─ Bugün 6 aylık kuru: 33.00 ₺
│  └─ 6 ay sonra: $1M = ₺33.00M (sabit)
│  └─ Korunmuş: Artık riski yok

Avantajlar:
├─ ✅ Kur riskinden koruma
├─ ✅ Maliyeti önceden bilme
├─ ✅ Planlama kolaylığı
└─ ✅ Fiyat rakabeti (sabit maliyet)

Dezavantajlar:
├─ ❌ Kür aşağı giderse fayda kaybı
├─ ❌ Taahhüt yapması gerekli
├─ ❌ Banka komisyonu
└─ ❌ İşlem masrafları
```

---

## 📊 FINANSAL RAPORLAR & İHRAÇ

### **Raporlar Türleri**

```
📈 FINANSAL RAPORLAMA

Navigasyon: 💰 FİNANSAL → Raporlar

[📋 RAPOR TÜRÜ SEÇ]

1. 📄 HAFTALIK İLERLEME RAPORU
   ├─ İçerik:
   │  ├─ Haftalık İlerleme %
   │  ├─ Bütçe Harcaması
   │  ├─ Önemlı Olaylar
   │  └─ Sonraki Haftanın Planı
   ├─ Sıklık: Her Cuma
   ├─ Format: PDF, Excel
   └─ Alıcı: Proje Yönetimi, Müşteri

2. 📄 AYLIK MALİ RAPORU
   ├─ İçerik:
   │  ├─ Aylık Harcama Özeti
   │  ├─ Bütçe Varyans Analizi
   │  ├─ Nakit Akışı Analizi
   │  ├─ Ödeme Durum Tablosu
   │  └─ Sonraki Ay Tahmini
   ├─ Sıklık: Ayın 5'inde
   ├─ Format: PDF, Excel, Power Point
   └─ Alıcı: Mali Müdür, CEO, Müşteri

3. 📄 ÜÇ AYLIK PERFORMANS RAPORU
   ├─ İçerik:
   │  ├─ Dönem İlerleme ve Analiz
   │  ├─ Bütçe Performansı
   │  ├─ Risk Durumu
   │  ├─ Sorun ve Çözümleri
   │  └─ İleriye Dönük Tahminler
   ├─ Sıklık: 4 defa/yıl
   ├─ Format: Power Point (Sunum)
   └─ Alıcı: İdari Kurulu, Hissedarlar

4. 📄 YILLIK STRATEJİK RAPORU
   ├─ İçerik:
   │  ├─ Yıllık Performans Özeti
   │  ├─ Stratejik Analizler
   │  ├─ Kıyaslamalı Veriler
   │  ├─ Öğrenilen Dersler
   │  └─ Sonraki Yıl Stratejisi
   ├─ Sıklık: Yılda 1 defa
   ├─ Format: Detaylı Rapor
   └─ Alıcı: Tüm Paydaşlar

5. 📄 İLERİ ANALİZ RAPORLARI
   ├─ Nakit Akışı Tahmini Raporu
   ├─ Finansal Risk Analiz Raporu
   ├─ Trend Analiz Raporu
   ├─ Döviz Riski Raporu
   ├─ Müşteri Bonotu Raporu
   └─ Portfolio Karşılaştırması Raporu
```

---

### **Rapor İndirme & Dışa Aktarma**

```
📥 RAPOR İNDİRME

Adım 1: Rapor Türünü Seçin
├─ Navigasyon: 💰 FİNANSAL → Raporlar
└─ [📋 RAPOR TÜRÜ SEÇ]

Adım 2: Tarih Aralığını Belirle
├─ Başlangıç Tarihi: [01.01.2025]
├─ Bitiş Tarihi: [27.10.2025]
└─ [🔄 GÜncEL] [🔄 SON 30 GÜN] [📅 ÖZELLEŞTİR]

Adım 3: Format Seç
├─ 📄 PDF (Yazdırmaya hazır)
├─ 📊 Excel (Analiz için uygun)
├─ 🎯 Power Point (Sunum için)
├─ 📋 CSV (Data analizi için)
└─ 🌐 HTML (Web görünümü)

Adım 4: İNDİR ve GÖNDER Seçenekleri
├─ [📥 İNDİR] → Bilgisayara indir
├─ [📧 GÖNDER] → Seçili kişilere mail gönder
├─ [☁️ BULUT] → Google Drive/OneDrive'a kaydet
└─ [🖨️ YAZDIR] → Doğrudan yazıcıya gönder

ÖRNEK: HAFTALIK RAPORUN PDF'İ
┌────────────────────────────────┐
│ haftalik_rapor_27_10_2025.pdf  │
│ (2.3 MB)                        │
│                                 │
│ [📥 İNDİR]  [📧 GÖNDER]        │
│ [☁️ BULUT]  [🖨️ YAZDIR]        │
└────────────────────────────────┘
```

---

## ✅ KONTROL LİSTESİ - BÖLÜM 3

Lütfen aşağıdaki adımları tamamlayın:

- [ ] Fatura Oluşturabildim
- [ ] Fatura Durumunu Güncelledim
- [ ] En Az Bir Ödeme İşlemi Yapıştım
- [ ] Bütçe Yapısını ve Kontrolünü Anladım
- [ ] Nakit Akışı Tahmin Raporunu Gördüm
- [ ] Risk Analizi Raporunu Okudum
- [ ] Döviz Kurlarını Kontrol Ettim
- [ ] En Az Bir Finansal Rapor İndirdim

**Kontrol Listesi Tamamlandı mı?** ✅ → Sonraki bölüme geçebilirsiniz!

---

## 🎯 SONRAKI BÖLÜM

**BÖLÜM 4: ALAN OPERASYONLARı & CRM**
- Teknik Destek Modülü
- Müşteri Yönetimi
- İletişim Takibi
- Raporlama
- Mobil Kullanım

👉 **Devamı için:** `KULLANICI_REHBERI_BOLUM_4.md`

---

**Sürüm:** 3.0  
**Tarih:** 27 Ekim 2025  
**Durum:** ✅ Onaylı ve Yayınlandı
