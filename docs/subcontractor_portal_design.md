# Taşeron Portalı Tasarım Özeti

## Amaç
Ana platformdan ayrı oturum açma ve yetkilendirme mekanizması ile taşeronların; bağlı oldukları şantiyelerde (projelerde) sundukları hizmetleri ve teslim ettikleri malzemeleri günlük olarak raporlayabilecekleri, durumlarını takip edip yönetebilecekleri hafif bir portal sağlamak.

## Temel Kullanıcı Hikâyeleri
- Taşeron kullanıcısı özel giriş ekranından e-posta ve şifre ile giriş yapar.
- Kullanıcı sadece ilişkilendirildiği sözleşme/projeleri görür.
- Dashboard’da aktif projeler, son hizmet ve malzeme girişleri, bekleyen onaylar gibi özet kartlar görüntülenir.
- Kullanıcı yeni hizmet raporu oluşturur; tarih, sözleşme, hizmet türü, miktar/adam-saat ve açıklama girer.
- Kullanıcı şantiyeye sevk ettiği malzemeler için teslim kayıtları oluşturur; teslim tarihi, malzeme adı, miktar, birim ve belge ekler.
- Kullanıcı önceki kayıtlarını tablo halinde görüntüler, filtreler ve düzenleyebilir.
- Kullanıcı oturumu kapatabilir, parolasını değiştirebilir.

## Veri Modeli
- `subcontractor_users`
  - `id`, `subcontractor_id`, `name`, `email` (unique), `password`, `phone`, `role`, `status`, `last_login_at`, `remember_token`, timestamps.
- `subcontractor_user_contract`
  - Pivot tablo; `subcontractor_user_id`, `subcontractor_contract_id` (benzersiz kombinasyon).
- `subcontractor_service_entries`
  - `id`, `subcontractor_contract_id`, `project_id`, `subcontractor_user_id`, `entry_date`, `service_type`, `work_description`, `quantity`, `unit`, `crew_size`, `worked_hours`, `status` (`draft|submitted|approved|rejected`), `attachments` (json), `notes`, timestamps.
- `subcontractor_material_entries`
  - `id`, `subcontractor_contract_id`, `project_id`, `subcontractor_user_id`, `delivery_date`, `material_name`, `specification`, `quantity`, `unit`, `unit_price`, `status`, `attachments` (json), `notes`, timestamps.

Her iki kayıt tablosu da daha sonra ana sistemdeki onay/ödeme akışlarına bağlanabilecek şekilde genişletilebilir.

## Kimlik Doğrulama
- Yeni guard: `subcontractor` (session driver) ve provider `subcontractor_users`.
- `SubcontractorUser` modeli `Authenticatable` soyundan gelecek.
- Portal rotaları `auth:subcontractor` ile korunacak, `guest:subcontractor` ile giriş ekranı kontrol edilecek.
- Laravel’in varsayılan `Authenticate` middleware’i için alt sınıf oluşturularak taşeron oturumlarında yönlendirme `/subcontractor/login` adresine yapılacak.

## Rotalar
```
GET  /subcontractor/login
POST /subcontractor/login
POST /subcontractor/logout
GET  /subcontractor/dashboard
GET  /subcontractor/services
GET  /subcontractor/services/create
POST /subcontractor/services
GET  /subcontractor/services/{service}/edit
PUT  /subcontractor/services/{service}
GET  /subcontractor/materials
GET  /subcontractor/materials/create
POST /subcontractor/materials
GET  /subcontractor/materials/{material}/edit
PUT  /subcontractor/materials/{material}
```
Gerektiğinde SOAP/JSON raporlaması için `/subcontractor/services/export` vb. uç noktalar eklenebilir.

## Controller Katmanı
- `Portal\AuthController`: giriş/çıkış akışları, parola güncelleme.
- `Portal\DashboardController`: özet verileri hesaplar.
- `Portal\ServiceEntryController`: CRUD & yetkilendirme.
- `Portal\MaterialEntryController`: CRUD & yetkilendirme.
- Form istekleri için `FormRequest` sınıfları: `StoreServiceEntryRequest`, `UpdateServiceEntryRequest`, benzeri malzeme girişleri için.

## Görünümler
`resources/views/subcontractor-portal` altında hafif Blade bileşenleri:
- `layouts/app.blade.php`: minimal bir layout, Tailwind CSS veya mevcut tema bileşenlerinden sadeleştirilmiş varyant.
- `auth/login.blade.php`
- `dashboard/index.blade.php`
- `services/index.blade.php`, `services/create.blade.php`, `services/edit.blade.php`
- `materials/index.blade.php`, `materials/create.blade.php`, `materials/edit.blade.php`

## Yetkilendirme ve Güvenlik
- Her işlemde `subcontractor_user_id` ile `subcontractor_contract_id` eşleşmesi doğrulanacak.
- Erişim kontrolü için policy veya controller içi gate kullanılacak; başlangıç için `authorizeContractAccess` yardımcı metodu yeterli.
- Dosya yüklemeleri `storage/app/public/subcontractor/...` altında saklanacak, maksimum 10MB, izin verilen MIME tipleri: pdf, jpg, png, docx.
- Audit trail için kayıtlar `submitted_at`, `approved_at` gibi alanlar eklenebilir (gelecek iterasyonlar için not bırakıldı).

## Entegrasyon Notları
- Ana platform admin kullanıcıları yeni taşeron kullanıcılarını oluşturup şifre tanımlayabilecek; bu iterasyonda CLI seed veya tinker üzerinden oluşturulabilir, sonraki sprintte UI eklenebilir.
- Dashboard metrikleri ana sistemdeki `ProgressClaim` ve `PaymentInstruction` tabloları ile entegre edilebilir; şimdilik portal girişlerinden basit istatistikler hesaplanacak.

## Açık Sorular
- Taşeron kayıtlarının onay akışı (kim onaylıyor?) — şimdilik statü alanı manuel güncellenebilir.
- Malzeme girişleri için birim maliyet zorunlu mu? Şimdilik opsiyonel bırakıldı.
- Çok faktörlü doğrulama ihtiyacı var mı? İlk sürümde desteklenmiyor.

```
Sürüm: v0.1 (03.10.2025)
Hazırlayan: GitHub Copilot (AI Yardımcısı)
```
