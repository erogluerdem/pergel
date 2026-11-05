Değişiklik özeti - Tüm Projeler sayfası (all-projects)

Yapılanlar:
- Grid görünümü eklendi (kart bazlı layout).
- Liste/Grid toggle butonları eklendi.
- Kartlarda thumbnail gösterimi eklendi; veritabanında `projects.attachments` alanı varsa ilk attachment thumbnail olarak kullanılır (storage altında `storage/<attachment>` yolunu kullanır).
- Kartlarda hover overlay ile proje adı, kısa açıklama, KPI'lar eklendi: ilerleme çubuğu, sağlık ikonu, kısa finans gösterimi (spent / budget).
- Önizleme modalı eklendi (Bootstrap modal), karttaki "Önizle" butonuyla açılır.
- Client-side CSV export: "Rapor İndir" butonu görünürdeki (filter uygulanmış) projeleri CSV'e çevirir.
- Arama (search) hem tablo hem grid görünümü için çalışır.

Nasıl test edilir:
1. `php artisan serve` ile sunucuyu çalıştırın.
2. http://127.0.0.1:8000/portfolio/all-projects adresini açın.
3. Liste ve Kart butonları ile görünüm değiştirin; gridde kartların yukarıdan gelen görselleri veya placeholder görselle gösterildiğini kontrol edin.
4. Bir kartın "Önizle" butonuna tıklayın: modal açılmalı ve proje bilgilerini göstermelidir.
5. "Rapor İndir" butonuna tıklayın: filtreli (görünen) projeler CSV olarak indirilecektir.

Notlar / Varsayımlar:
- `projects.attachments` alanı JSON (dizi) olarak saklanıyor; öğeler string (storage path) veya associative array olabilir. Kod hem string hem array formatlarını ele alır (views içinde).
- Eğer proje görseli yoksa `public/images/placeholder-issue.png` gösterilir. İsterseniz kendi placeholder görselinizi `public/images/default-project.png` olarak ekleyin ve blade dosyasını buna göre güncelleyin.
- Bootstrap JS sayfada yüklü olmalıdır (modal çalışması için).

İyileştirme önerileri:
- Sunucu tarafı export (CSV/XLSX/PDF) eklemek.
- Kartlara küçük aksiyon menüleri (favorite, quick assign) eklemek.
- Proje thumbnail'lerini upload ve otomatik kırpma/thumbnail pipeline'ı kurmak.
