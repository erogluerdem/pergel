vouchers_import.sql — nasıl kullanılır

Bu klasörde phpMyAdmin veya MySQL/MariaDB üzerine hızlıca import edebileceğiniz SQL script'i bulunur.

İçerik:
- users tablosuna `is_admin` kolonu eklenir (varsa)
- `vouchers` tablosu (soft-delete uyumlu)
- `voucher_lines` tablosu (voucher_id FK ile)
- Basit `customers` ve `invoices` tabloları (örnek verilerle)
- Örnek veri: 2 müşteri, 2 fatura, ve 3 fiş + satırları

Kullanım (phpMyAdmin):
1) PHPMyAdmin'i açın.
2) Sol menüden hedef veritabanınızı seçin.
3) Üst menüden "Import" seçeneğine tıklayın.
4) "File to import" kısmına bu `vouchers_import.sql` dosyasını seçin.
5) Go / Yükle butonuna basın.

Kullanım (komut satırı - Windows PowerShell):
Aşağıdaki komutu çalıştırmadan önce veritabanı adınızı, kullanıcı adınızı ve şifrenizi değiştirin:

# PowerShell örneği
# mysql -u root -p database_name < path\to\vouchers_import.sql
# (Windows PowerShell'de tek satır: mysql -u root -p; sonra şifre girin; daha sonra "USE database_name; SOURCE C:/ikon/database/sql/vouchers_import.sql;")

Notlar:
- Script idempotent olacak şekilde yazılmaya çalışıldı (IF NOT EXISTS, INSERT ... ON DUPLICATE KEY UPDATE). Ancak bazı eski MySQL sürümlerinde `IF NOT EXISTS` ile FOREIGN KEY oluşturma farklılık gösterebilir.
- Mevcut `users`, `customers` veya `invoices` tablolarınız varsa, script var olan yapıya göre kısıtlı değişiklikler yapacaktır (ör. users tablosuna yeni kolon ekler). Yine de canlı veritabanınızda çalıştırmadan önce script'i bir test veritabanında denemenizi öneririm.
- Eğer repo içindeki Laravel uygulamanız `vouchers` tablosunu farklı bir şema ile oluşturduysa, migration ile senkronizasyon için migration dosyalarını kullanmanız daha güvenlidir.

Yardım isterseniz, script'i sizin için veritabanında çalıştırıp doğrulamayı uzaktan yapamam; ama komutları çalıştırmanız için adım adım yardımcı olabilirim.
