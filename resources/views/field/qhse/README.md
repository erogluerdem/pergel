
Bu klasörde `quality.blade.php` sayfası bulunmaktadır.

Kullanım:
- Uygulamayı çalıştırın: `php artisan serve` (varsa)
- Tarayıcıda: http://127.0.0.1:8000/field/qhse/quality

Hızlı Yenilikler ve Özellikler:
- Denetim şablonlarını arama (başlık ve madde metinlerinde arama)
- Yeni denetim başlatma ve şablon oluşturma/düzenleme
- Denetimler için durum rozeti (Planlandı / Devam Ediyor / Tamamlandı)
- Özet grafiği (Chart.js kullanıyorsa durum dağılımı gösterilir)
- Eksik işler (punchlist) önizlemesi ve punch oluşturma formu (fotoğraf yükleme, not, sorumlu, termin)
- JSON dışa aktarma (templates / inspections / punchlist)

Notlar:
- Bu sürüm halen client-side prototip olarak çalışır ve öncelikle `localStorage` kullanır. Sunucu tarafı API'ler bulunduğunda JS otomatik olarak /api/* endpointlerine bağlanmaya çalışır.
- Chart.js projenizde global olarak yüklü değilse sayfa grafiğini göstermeyebilir; gerekirse CDN ile `chart.min.js` ekleyin.

