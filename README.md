# 🏗️ PERGEL — Açık Kaynak Şantiye, Tesis & Proje Yönetim Platformu

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 11" />
  <img src="https://img.shields.io/badge/Sürüm-Topluluk%20(Open--Core)-10B981?style=for-the-badge" alt="Community Edition" />
  <img src="https://img.shields.io/badge/Lisans-MIT-blue?style=for-the-badge" alt="MIT License" />
  <img src="https://img.shields.io/badge/Modüler-Eklenti%20Destekli-F59E0B?style=for-the-badge" alt="Modular Architecture" />
</p>

**PERGEL**, inşaat firmaları, müteahhitler, saha şantiyeleri ve tesis yönetim şirketleri için geliştirilmiş modern, modüler ve **Açık Çekirdek (Open-Core)** kurumsal yönetim platformudur. 

Şantiye ve proje takibi, görevler, Kanban, takvim, CRM, tesis envanteri ve mobil PWA özellikleri **ücretsiz & açık kaynak (Topluluk Sürümü)** olarak sunulmaktadır. Gelişmiş resmi hakedişler, taşeron portalı, ÇŞB pozları, İSG denetimleri ve yapay zeka araçları ise **modüler eklenti paketleri** olarak sisteme entegre edilebilmektedir.

---

## 🌟 Topluluk Sürümü (Ücretsiz & Açık Kaynak) Özellikleri

- **Proje & Şantiye Takibi:** Şantiye oluşturma, proje aşamaları, şantiye fotoğraf galerisi.
- **Görev & İş Yönetimi:** Kanban panosu, Gantt / takvim görünümü, önceliklendirme ve atamalar.
- **Tesis & Varlık Yönetimi:** Envanter kayıtları, arıza bildirimi ve ekipman durumu takibi.
- **Müşteri & CRM:** Müşteri portföyü, toplantı notları, teklif/fırsat takibi ve dahili mesajlaşma.
- **Mobil PWA Desteği:** Sahada çevrimdışı çalışma, akıllı telefon ana ekranına uygulama gibi ekleme.
- **Modül & Eklenti Yükleyici:** [erdemeroglu.com.tr](https://erdemeroglu.com.tr/yazilimlar/pergel) üzerinden satın alınan modül ZIP paketlerini tek tıkla yükleme ve anında aktifleştirme.

---

## 🧩 Modüler Eklenti Paketleri (erdemeroglu.com.tr)

İhtiyacınıza göre PERGEL'i genişletmek için resmi modül paketlerini [erdemeroglu.com.tr/yazilimlar/pergel](https://erdemeroglu.com.tr/yazilimlar/pergel) üzerinden temin edebilir, yönetim panelindeki **Modüller & Eklentiler** (`/admin/modules`) bölümünden ZIP olarak anında yükleyebilirsiniz:

| Modül Paketi | Kapsam & İçerik | Temin Bağlantısı |
| :--- | :--- | :---: |
| **Saha & Taşeron Paketi** (`pergel-field`) | Taşeron dış paydaş portalı, işçi evrakları, İSG / QHSE denetimleri, EBİS beton kırım testleri, mobil şantiye günlüğü ve QR kodlu SLA arıza takibi. | [İncele & İndir](https://erdemeroglu.com.tr/yazilimlar/pergel#field) |
| **Mevzuat & Finans Paketi** (`pergel-compliance`) | Çevre ve Şehircilik Bakanlığı (ÇŞB) güncel poz kütüphanesi, resmi hakedişler (Yeşil Defter), SGK asgari işçilik hesaplayıcı, TÜİK Yİ-ÜFE fiyat farkı hesabı ve ihale yönetimi. | [İncele & İndir](https://erdemeroglu.com.tr/yazilimlar/pergel#compliance) |
| **Full Enterprise Paketi** (`pergel-pro`) | Tüm saha ve mevzuat modüllerine ek olarak; tarayıcıda 3D BIM model görüntüleme, AI puantaj & sözleşme risk analizi, C-Level Executive Dashboard (BI) ve IoT telemetri. | [İncele & İndir](https://erdemeroglu.com.tr/yazilimlar/pergel#pro) |

---

## 🚀 Hızlı Başlangıç & Kurulum

### 1. Sistem Gereksinimleri
* PHP 8.2 veya üzeri (PDO, OpenSSL, Mbstring, Tokenizer, XML, Ctype, JSON, Zip uzantıları aktif)
* Composer 2.x
* Node.js (v18+) & npm
* MySQL / MariaDB / PostgreSQL veya SQLite

### 2. Kurulum Adımları
```bash
# 1. Depoyu klonlayın
git clone https://github.com/erdemeroglu/pergel.git
cd pergel

# 2. PHP ve JS bağımlılıklarını kurun
composer install
npm install

# 3. Çevre değişkenlerini yapılandırın
cp .env.example .env
php artisan key:generate

# 4. Veritabanını oluşturun ve örnek verileri yükleyin
php artisan migrate --seed

# 5. Varlık derlemesini yapın
npm run build

# 6. Geliştirme sunucusunu başlatın
php artisan serve
```

Sisteme tarayıcınızdan `http://localhost:8000` adresinden erişebilirsiniz.

---

## 📦 Modül Yükleme & Yönetim

1. Sol menüden **Platform Yönetimi -> Modüller & Eklentiler** (`/admin/modules`) sayfasına gidin.
2. `erdemeroglu.com.tr` üzerinden indirdiğiniz modül ZIP dosyasını (örneğin `pergel-field.zip`) seçip **«Paketi Yükle ve Aktifleştir»** butonuna tıklayın.
3. Modül otomatik olarak `modules/` dizinine açılacak, veritabanı tabloları güncellenecek ve yeni menüler sol menüde belirecektir.

---

## 🤝 Katkıda Bulunma

PERGEL topluluk çekirdeği açık kaynak kodludur. Hata bildirimleri, özellik önerileri ve Pull Request'ler memnuniyetle karşılanır:

1. Bu depoyu Fork edin.
2. Yeni bir özellik dalı oluşturun (`git checkout -b feature/yeni-ozellik`).
3. Değişikliklerinizi commit edin (`git commit -m 'feat: yeni özellik eklendi'`).
4. Dalınızı gönderin (`git push origin feature/yeni-ozellik`).
5. Bir Pull Request açın.

---

## 📄 Lisans

- **PERGEL Topluluk Çekirdeği:** [MIT Lisansı](LICENSE) kapsamında tamamen ücretsiz ve açık kaynak olarak dağıtılmaktadır.
- **Harici Eklenti Modülleri:** [erdemeroglu.com.tr](https://erdemeroglu.com.tr) ticari lisansı altındadır.
