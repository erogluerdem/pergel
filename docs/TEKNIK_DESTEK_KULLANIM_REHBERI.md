# 📚 Teknik Destek Modülü - Kullanım Rehberi

## 🎯 Hızlı Başlangıç

Pergel03 projesine eklenen **QR Kod Sistemi** ve **SLA Yönetimi** ile müşteri destek taleplerini kolayca yönetebilirsiniz.

---

## 📋 İçindekiler
1. [QR Kod Sistemi](#qr-kod-sistemi)
2. [Destek Taleplerini Yönetme](#destek-taleplerini-yönetme)
3. [SLA Ayarları](#sla-ayarları)
4. [API Kullanımı](#api-kullanımı)
5. [Sık Sorulan Sorular](#ssk)
6. [Örnekler](#örnekler)

---

## 🔲 QR Kod Sistemi

### QR Kod Nedir?
QR (Quick Response) kodlar, müşterilerin dairede yerleştirilen kodları tarayarak **hızlı destek talebi oluşturmasını** sağlayan akıllı sistemdir.

### Kurulumu

#### 1️⃣ QR Kodları Üretme

**Adım 1:** Sidebar'dan **Tesis Yönetimi** → **QR Kod Yönetimi** seçin
```
🏢 Tesis Yönetimi
  ├── Tesis Panosu
  ├── Varlık Yönetimi
  ├── Bakım & Onarım
  ├── Destek Talepleri
  ├── 🔲 QR Kod Yönetimi  ← BURADA
  ├── SLA Ayarları
  └── Raporlar
```

**Adım 2:** "Proje için QR Kodları Oluştur" butonuna tıklayın

**Adım 3:** Projeyi seçin (örn: "Hilton Otel Projesi")
```
┌─────────────────────────────────┐
│ Proje Seçin: [Hilton Otel      ▼] │
│                                  │
│ [Kodları Oluştur]              │
└─────────────────────────────────┘
```

**Adım 4:** Sistem otomatik olarak tüm daireler için QR kod üretir ✨

#### 2️⃣ QR Kodları İndirme

1. QR Kod Yönetimi sayfasında oluşturulan kodları göreceksiniz
2. Bir QR kodun yanındaki **"İndir"** butonuna tıklayın
3. PNG dosyası otomatik olarak indirilir

```
QR Kodlar:
┌──────────────────────────────────────────┐
│ Daire 101    │ Tarama: 5 │ [İndir] [Sil] │
│ Daire 102    │ Tarama: 3 │ [İndir] [Sil] │
│ Daire 103    │ Tarama: 0 │ [İndir] [Sil] │
└──────────────────────────────────────────┘
```

#### 3️⃣ QR Kodları Yerleştirme

1. İndirilen QR kodları yazdırın
2. Her dairenin giriş kapısına veya ön kapısına yapıştırın
3. **Etiket Ekleyin:** "Sorun mu var? QR kodu tara → Hızlı Destek İste"

---

## 🎯 Destek Taleplerini Yönetme

### Müşteri Tarafından: Hızlı Talep Oluşturma

#### Senaryo: Musluk Arızası

1. **QR Kod Taraması**
   - Müşteri: Dairenin kapısında QR kodu tarar
   - Sistem: Otomatik talep formu açılır

2. **Talep Formu Doldurma**
   ```
   ┌─────────────────────────────────────┐
   │ DESTEK TALEBİ                      │
   ├─────────────────────────────────────┤
   │ Başlık: Mutfak muslağı sızan       │
   │                                     │
   │ Açıklama:                          │
   │ Mutfak tezgahındaki muslaktan       │
   │ su akıyor. Acil bakım gerekiyor.   │
   │                                     │
   │ Fotoğraf Ekle: [📷 +]             │
   │                                     │
   │           [GÖNDER]                 │
   └─────────────────────────────────────┘
   ```

3. **Gönderme**
   - "Gönder" butonuna tıkla
   - Sistem otomatik SLA hedefi atanır
   - Müşteri konfirmasyonu alır

### Yönetici Tarafından: Talep Yönetimi

#### Adım 1: Talepları Görüntüle

Sidebar → **Tesis Yönetimi** → **Destek Talepleri**

```
Açık Talepler: 12
┌─────────────────────────────────────────┐
│ Daire 101 | Musluk Arızası             │
│ Durum: 🟠 Bekleme | SLA: 4 Saat        │
│ Kalan: 3 saat 45 dk                    │
│ Oluşturma: 15 dk önce                  │
├─────────────────────────────────────────┤
│ Daire 205 | Işık Açılmıyor             │
│ Durum: 🟡 İşlemde | SLA: 2 Saat        │
│ Kalan: 1 saat 20 dk                    │
│ Atanmış: Ahmet Teknisyen                │
└─────────────────────────────────────────┘
```

#### Adım 2: Talep Detaylarını Aç

Talep satırına tıklayın:

```
┌──────────────────────────────────────────┐
│ TALEP DETAYLARI #105                    │
├──────────────────────────────────────────┤
│ 📍 Konum: Daire 101, Mutfak             │
│ 👤 Müşteri: Ahmet Yılmaz                │
│ 📱 Telefon: 0532 123 45 67              │
│                                          │
│ 🆔 Başlık: Musluk Arızası               │
│ 📝 Açıklama: Mutfak tezgahındaki        │
│    muslaktan su akıyor...                │
│                                          │
│ 📷 Fotoğraf: [Göster]                   │
│ ⏱️ SLA Hedefi: 4 Saat                    │
│ ⏳ Kalan Zaman: 3 saat 45 dk            │
│                                          │
│ 📋 İletişim Geçmişi:                    │
│ 14:30 | Müşteri: Talep oluşturdu        │
│ 14:32 | Sistem: SLA hedefi atandı       │
│ 14:35 | Atama: Ahmet'e atandı           │
│                                          │
│ [Atama] [Durum Değiştir] [Tamamla]    │
└──────────────────────────────────────────┘
```

#### Adım 3: Talep İçin İşlem Yap

**Atama Yapma:**
- "Atama" butonuna tıklayın
- Teknisyeni seçin
- Otomatik bildirim gönderilir

**Durum Güncellemesi:**
- "Durum Değiştir" butonuna tıklayın
- Seçenekler: 🟠 Bekleme → 🟡 İşlemde → 🟢 Tamamlandı

**Tamamlama:**
- "Tamamla" butonuna tıklayın
- Sistem otomatik çözüm süresini kaydeder
- SLA uyumluluğu hesaplanır

---

## ⏱️ SLA Ayarları

### SLA Nedir?
**Service Level Agreement** (Hizmet Seviyesi Anlaşması) - Müşteri taleplerinin ne sürede çözüleceğini tanımlar.

### SLA Tanımlama

#### Adım 1: SLA Ayarları Sayfasına Git

Sidebar → **Tesis Yönetimi** → **SLA Ayarları**

#### Adım 2: Yeni SLA Oluştur

```
┌─────────────────────────────────────────┐
│ YENİ SLA OLUŞTUR                       │
├─────────────────────────────────────────┤
│ Proje: [Hilton Otel              ▼]   │
│                                        │
│ Sorun Kategorisi:                     │
│ [Elektrik Arızası            ]        │
│                                        │
│ Yanıt Süresi (dk): [60     ]          │
│ Çözüm Süresi (dk): [240    ]   (4 saat)
│                                        │
│ Açıklama:                             │
│ [Elektrik arızaları için SLA...]      │
│                                        │
│ [KAYDET]                              │
└─────────────────────────────────────────┘
```

### SLA Seviyeleri Örneği

Tipik bir otel projesi için:

| Kategori | Çözüm Süresi | Uyarılar |
|----------|-------------|---------|
| **Acil (Asansör, Su)** | 1 saat | 45 dk / 1 saat |
| **Yüksek (Elektrik, Isıtma)** | 4 saat | 3 saat / 4 saat |
| **Normal (Işık, Kapı)** | 24 saat | 18 saat / 24 saat |
| **Düşük (Kozmetik)** | 48 saat | 36 saat / 48 saat |

### Uyarılar Nasıl Çalışır?

```
Müşteri Talep Oluşturdu (14:00)
         ↓ (SLA: 4 saat = 240 dk)
   15:00 - İşlemde
   15:00 - Bildirim gönder
         ↓
   17:00 ⚠️ UYARI: %75 (3 saat geçti)
   17:00 - Email: Talep SLA'nın %75'inde
         ↓
   18:00 🔴 KRİTİK: %100 (4 saat geçti)
   18:00 - Email: Talep SLA'yı aştı!
         ↓
   18:30 ✅ TAMAMLANDI
   18:30 - SLA: %94 uyumlu (24 dk fazla)
```

### SLA Dashboard'u Kullanma

**Adım 1:** SLA Ayarları → "Proje Istatistikleri"

```
┌─────────────────────────────────────────┐
│ SLA DASHBOARD - Hilton Otel Projesi    │
├─────────────────────────────────────────┤
│                                         │
│ 📊 Genel İstatistikler:                │
│ • Toplam Talep: 156                    │
│ • Tamamlanan: 145 (93%)                │
│ • SLA Uyumlu: 135 (93%)                │
│ • SLA İhlali: 10 (7%)                  │
│                                         │
│ 🟢 Talepler:                           │
│ • Beklemede: 8                         │
│ • İşlemde: 3                           │
│ • Tamamlandı: 145                      │
│                                         │
│ ⏱️ Ortalama Çözüm Süresi:              │
│ • Elektrik: 3 saat 45 dk              │
│ • Su: 45 dk                            │
│ • Diğer: 18 saat                       │
│                                         │
│ 🔴 KRİTİK UYARILAR: 2                 │
│ [SLA'yı aşan talepleri göster]        │
│                                         │
└─────────────────────────────────────────┘
```

---

## 🔌 API Kullanımı

### Mobil App Entegrasyonu

Mobil uygulamadan QR kod taraması yapılacaksa:

#### 1. QR Taraması

```bash
POST /api/qr/scan/{token}

# Yanıt:
{
  "success": true,
  "data": {
    "unit_id": 101,
    "project_id": 5,
    "qr_label": "Daire 101",
    "scans_count": 45
  }
}
```

#### 2. Hızlı Talep Oluşturma

```bash
POST /api/qr/request/{token}

# Gönder:
{
  "subject": "Musluk Arızası",
  "description": "Mutfak tezgahındaki muslaktan su akıyor",
  "customer_id": 123
}

# Yanıt:
{
  "success": true,
  "data": {
    "id": 456,
    "subject": "Musluk Arızası",
    "status": "pending",
    "sla_target": 14400
  }
}
```

#### 3. QR Kod İstatistikleri

```bash
GET /api/qr/stats/{token}

# Yanıt:
{
  "success": true,
  "data": {
    "qr_token": "abc123...",
    "scans_count": 45,
    "requests_created": 12,
    "last_scanned_at": "2025-10-26T15:30:00Z",
    "is_active": true
  }
}
```

---

## 📱 Mobil Uygulama Örneği (JavaScript/React)

```javascript
// QR Kod Taraması
async function scanQrCode(token) {
  try {
    const response = await fetch(`/api/qr/scan/${token}`, {
      method: 'POST'
    });
    
    const data = await response.json();
    
    if (data.success) {
      console.log('QR Tarandı:', data.data);
      // Talep formunu açabilirsiniz
      openRequestForm(data.data);
    }
  } catch (error) {
    console.error('Tarama başarısız:', error);
  }
}

// Talep Oluşturma
async function createRequest(token, subject, description) {
  try {
    const response = await fetch(`/api/qr/request/${token}`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        subject,
        description,
        customer_id: userId
      })
    });
    
    const data = await response.json();
    
    if (data.success) {
      alert('Talep başarıyla oluşturuldu!');
      console.log('Talep ID:', data.data.id);
    }
  } catch (error) {
    console.error('Talep oluşturma başarısız:', error);
  }
}
```

---

## ❓ Sık Sorulan Sorular

### S: QR kod taraması yapıldığında ne olur?
**C:** Sistem otomatik olarak:
1. Tarama kaydedilir
2. Müşteri destek formuna yönlendirilir
3. Talep oluşturulurken otomatik SLA hedefi atanır

### S: SLA zamanı sıfırlanabilir mi?
**C:** Hayır, SLA zamanı talep oluşturulduğunda başlar ve değiştirilemez. Ancak admin talep statüsünü değiştirebilir.

### S: Kririk uyarılar nasıl alırım?
**C:** Sistem, SLA'yı aşan talepler için otomatik email bildirim gönderir. Ayrıca `/facility/sla/alerts` sayfasında görebilirsiniz.

### S: Müşteri aynı daire için birden çok talep açabilir mi?
**C:** Evet, her talep bağımsız olarak işlenir ve kendi SLA hedefine sahiptir.

### S: QR kod aktif değil ne demek?
**C:** QR kod `is_active` alanında `false` olduğu zaman devre dışıdır. Devre dışı QR kodlar taranmaz.

### S: İletişim geçmişi nerede tutulur?
**C:** Her talepteki `communication_log` JSON alanında tutulur ve otomatik olarak kaydedilir.

---

## 📋 Örnekler

### Örnek 1: Otel Projesi Kurulum

**Amaç:** Hilton Otel Projesi için QR kod sistemi kurmak

**Adımlar:**

1. **QR Kodları Oluştur**
   ```
   Sidebar → Tesis Yönetimi → QR Kod Yönetimi
   → "Hilton Otel" seçin
   → "Kodları Oluştur" tıklayın
   ```

2. **SLA Tanımlamaları Yap**
   ```
   Sidebar → Tesis Yönetimi → SLA Ayarları
   
   SLA 1: Elektrik Arızası = 4 saat
   SLA 2: Su Arızası = 1 saat
   SLA 3: Asansör Arızası = 30 dk
   ```

3. **QR Kodları Yerleştir**
   ```
   101-108. daireye QR kodları yapıştır
   Her birine "Hızlı Destek: QR'yı Tara" etiketi ekle
   ```

4. **Taklif Et**
   ```
   "Müşteriler dairenin kapısında QR'ı tarayarak
    hızlı destek talebi açabilir."
   ```

### Örnek 2: Talep Takibi

**Senaryo:** Müşteri elektrik arızası bildiriyor

**Timeline:**
```
14:00  - Müşteri QR'ı tarar → Talep #201 oluşur
14:00  - SLA: 4 saat (18:00'de hedef)
14:02  - Sistem: Talep oluşturuldu, email gönder
14:10  - Admin: Mühendis Serdar'a atandı
14:15  - Serdar: Durum "İşlemde" ye değiştir
14:20  - Serdar: "Şalter takılmış, açılıyor" mesajı yaz
15:00  - Talep hala açık (SLA: 3 saat kaldı)
17:00  - ⚠️ UYARI: SLA %75'inde
17:00  - Email: Admin'e "Talep SLA'nın 75%'inde"
17:15  - Serdar: "Problem çözüldü" yazar
17:20  - Serdar: Durum "Tamamlandı" yapıyor
17:20  - Sistem: Çözüm süresi = 3 saat 20 dk
17:20  - Sistem: SLA Uyumlu (80 dk erken)
```

---

## 🚀 İpuçları

✅ **En İyi Uygulamalar:**
1. QR kodları hemen her daireye yerleştirin
2. Müşterileri QR kod kullanımı hakkında bilgilendirin
3. SLA'ları gerçekçi olacak şekilde ayarlayın
4. Her gün SLA Dashboard'u kontrol edin
5. Kritik uyarıları önceliklendirebilecek şekilde davranın

⚠️ **Dikkat Edilecekler:**
1. QR kodları zarar görmesine karşı koruyun
2. SLA zamanlarını çok kısa ayılamayan
3. Taleplere zamanında müdahale edin
4. İletişim günlüğünü kullanarak müşteri ile iletişim kurun
5. Aylık raporları analiz edin

---

## 📞 Destek

Sorunuz varsa:
- Email: support@pergel.dev
- Telefon: 0212 XXX XX XX
- Web: www.pergel.dev/help

---

**Son Güncelleme:** 26 Ekim 2025
**Versiyon:** 1.0
