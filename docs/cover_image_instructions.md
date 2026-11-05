Cover image integration - kullanım ve test notları

Yapılan değişiklikler:
- `projects` tablosuna `cover_image` (nullable string) sütunu eklendi (migration: `2025_09_30_000000_add_cover_image_to_projects.php`).
- `app/Models/Project.php` modelinin `fillable` alanına `cover_image` eklendi.
- `resources/views/portfolio/project-form.blade.php` formuna `cover_image` file input eklendi.
- `app/Http/Controllers/ProjectController.php` içinde `store` ve `update` metodlarına cover image upload ve eski cover silme mantığı eklendi. Coverlar `storage/app/public/projects/covers` altına kaydedilir.

Çalıştırma / test adımları:
1. Migration çalıştır:

```powershell
php artisan migrate
```

2. Storage link oluştur (eğer yapılmadıysa):

```powershell
php artisan storage:link
```

3. Sunucuyu başlat:

```powershell
php artisan serve --host=127.0.0.1 --port=8000
```

4. Tarayıcıda proje formuna (yeni proje oluşturma veya düzenleme) gidin: `http://127.0.0.1:8000/projects/create` veya düzenleme URL'si.
   - "Kapak Görseli" alanından bir resim seçin ve kaydedin.
   - Kaydettikten sonra proje detay sayfasında veya grid listede kapak görseli gösterilmelidir.

Ek notlar:
- Dosya boyutu kısıtlaması controller'da `attachments.*` için 5MB olarak tanımlıdır; `cover_image` için de aynı limiti uygulamak isterseniz validasyon kuralı eklenebilir.
- Mevcut `attachments` alanı JSON array olduğu için eski sistemde zaten görseller `attachments[0]` olarak kullanılıyordu; `cover_image` öncelikli olarak kullanılır (controller/apiShow/show metodlarında da buna göre tercih ediliyor).
- Eğer S3 veya farklı disk kullanmak isterseniz, `store('projects/covers', 'public')` yerine disk adını değiştirin ve `asset(Storage::url(...))` kullanımına geçin.
