vouchers_import seçenekleri

1) `vouchers_import.sql` (idempotent, önerilen)
- Canlı veritabanlarında denemek için uygundur; mevcut tabloları silmez.
- `ALTER TABLE ... ADD COLUMN IF NOT EXISTS` gibi ifadeler MySQL sürümünüzde desteklenmezse hata verebilir. Hata alırsanız bana bildirin; uygun düzeltmeyi yapacağım.

2) `vouchers_import_drop.sql` (temiz kurulum, dikkat)
- Bu versiyon `DROP TABLE IF EXISTS` kullanır ve tabloları sıfırlar. Test veritabanları için hızlı, güvenli bir seçenek.
- Production (canlı) veritabanında kesinlikle çalıştırmayın, aksi halde mevcut tablolarınız ve verileriniz silinir.

Hangi dosyayı kullanmalısınız?
- Eğer test/veri üzerinde çalışıyorsanız: `vouchers_import_drop.sql` hızlı ve temiz.
- Eğer canlı veritabanında sadece ekleme/tenzime ihtiyaç varsa: `vouchers_import.sql`.

Yardım
- Hangi MySQL/MariaDB sürümünü kullandığınızı belirtin; varsa uyumluluk sorunlarını düzeltip tekrar gönderirim.
- Import sırasında çıkan hataları paylaşın; hatayı düzeltilmiş SQL ile geri vereceğim.
