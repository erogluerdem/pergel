<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HelpCategory;
use App\Models\HelpArticle;
use App\Models\HelpFaq;
use App\Models\HelpVideoTutorial;

class HelpKnowledgeBaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Categories for all PERGEL ERP modules
        $categoriesData = [
            [
                'name' => '1. Şantiye & Saha Operasyonları',
                'slug' => 'santiye-saha-operasyonlari',
                'description' => 'Günlük şantiye raporları, puantaj, iş makinesi kullanım saatleri ve hava durumu takibi',
                'icon' => 'ri-building-2-line',
                'color' => 'success',
                'sort_order' => 1,
                'remote_id' => 'REM-CAT-101',
            ],
            [
                'name' => '2. Hakediş & Taşeron Ödemeleri',
                'slug' => 'hakedis-taseron-odemeleri',
                'description' => 'Ara ve kesin hakediş cetvelleri, %20 KDV tevkifatı, kesinti ve onay mekanizmaları',
                'icon' => 'ri-money-dollar-circle-line',
                'color' => 'primary',
                'sort_order' => 2,
                'remote_id' => 'REM-CAT-102',
            ],
            [
                'name' => '3. Finans, E-Fatura & Nakit Akışı',
                'slug' => 'finans-efatura-nakit-akisi',
                'description' => 'GİB canlı e-fatura entegrasyonu, nakit akış tahmini ve döviz alım/satım kurları',
                'icon' => 'ri-bank-card-line',
                'color' => 'info',
                'sort_order' => 3,
                'remote_id' => 'REM-CAT-103',
            ],
            [
                'name' => '4. Satın Alma, İhale & Stok Kartları',
                'slug' => 'satin-alma-ihale-stok-kartlari',
                'description' => 'Tedarikçi teklif toplama tablosu, malzeme stok kartları ve ambar giriş/çıkış fişleri',
                'icon' => 'ri-shopping-cart-2-line',
                'color' => 'warning',
                'sort_order' => 4,
                'remote_id' => 'REM-CAT-104',
            ],
            [
                'name' => '5. Tesis & Ağır Makine Filosu GPS',
                'slug' => 'tesis-agir-makine-filosu-gps',
                'description' => 'Harita tabanlı GPS ağır ekipman takibi, bakım iş emirleri ve tesis varlık envanteri',
                'icon' => 'ri-truck-line',
                'color' => 'danger',
                'sort_order' => 5,
                'remote_id' => 'REM-CAT-105',
            ],
            [
                'name' => '6. İnsan Kaynakları, Bordro & Avans',
                'slug' => 'insan-kaynaklari-bordro-avans',
                'description' => 'Şantiye personel kartları, seyahat/ozalit harcamaları, taksitli maaş avansları ve bordro tahakkuku',
                'icon' => 'ri-team-line',
                'color' => 'secondary',
                'sort_order' => 6,
                'remote_id' => 'REM-CAT-106',
            ],
            [
                'name' => '7. Gayrimenkul Satış & Finansal Raporlar',
                'slug' => 'gayrimenkul-satis-finansal-raporlar',
                'description' => 'Konut taksit muhasebesi, danışman performans analizi, kar/zarar ve üst yönetim KPI dashboard',
                'icon' => 'ri-store-2-line',
                'color' => 'teal',
                'sort_order' => 7,
                'remote_id' => 'REM-CAT-107',
            ],
            [
                'name' => '8. Sistem Yönetimi, Audit & Kalite',
                'slug' => 'sistem-yonetimi-audit-kalite',
                'description' => 'Kullanıcı yetkilendirme, veri aktarımı, ISO standartları, sistem sağlığı ve audit logları',
                'icon' => 'ri-shield-keyhole-line',
                'color' => 'dark',
                'sort_order' => 8,
                'remote_id' => 'REM-CAT-108',
            ],
            [
                'name' => '9. Proje Kapanış & Geçici Kabul',
                'slug' => 'proje-kapanis-gecici-kabul',
                'description' => 'Şantiye teslim dosyası, eksik/kusurlu imalat kontrol listeleri ve garanti arıza yönetimi',
                'icon' => 'ri-flag-2-line',
                'color' => 'indigo',
                'sort_order' => 9,
                'remote_id' => 'REM-CAT-109',
            ],
        ];

        $categoriesMap = [];
        foreach ($categoriesData as $cData) {
            $cat = HelpCategory::updateOrCreate(['slug' => $cData['slug']], $cData);
            $categoriesMap[$cat->slug] = $cat->id;
        }

        // 2. 28 Comprehensive Articles Covering ALL PERGEL ERP Modules
        $articles = [
            // 1. Saha Operasyonları
            [
                'category_slug' => 'santiye-saha-operasyonlari',
                'title' => 'Günlük Şantiye Raporu ve Saha Puantaj Girişi Senaryosu',
                'slug' => 'gunluk-santiye-raporu-ve-puantaj-girisi',
                'summary' => 'Ustabaşı ve iş makinelerinin günlük çalışma saatlerini kaydetme, hava durumu ekleme ve saha fotoğraf galerisi.',
                'content' => "Şantiye operasyonlarının dijitalleşmesinde en kritik aşama günlük saha raporunun eksiksiz doldurulmasıdır.\n\n"
                    . "Kullanım Senaryosu ve Adımlar:\n"
                    . "1. Saha Operasyonları modülüne giriş yapın ve 'Günlük Rapor Oluştur' butonuna tıklayın.\n"
                    . "2. Vardiya Hava Durumu alanına o günkü sıcaklık (°C) ve şantiye koşullarını (Güneşli, Yağmurlu vb.) ekleyin.\n"
                    . "3. Taşeron İş Gücü Tablosu alanında kalıpçı, demirci ve betoncu ustalarının kişi sayısı ve mesai saatlerini girin.\n"
                    . "4. Ağır Makine Parkı bölümünde ekskavatör ve kule vincin o günkü Çalışma Saatlerini (ÇS) ve tüketilen motorin litresini işleyin.\n"
                    . "5. Şantiye Fotoğrafları alanına dökülen beton veya kurulan iskelenin fotoğraflarını yükleyip 'Taslağı Onayla' butonuna basın.",
                'views' => 5420,
                'read_time' => 5,
                'is_popular' => true,
                'tags' => ['saha', 'puantaj', 'şantiye', 'hava durumu', 'ekipman'],
                'author' => 'Kıdemli Saha Şefi',
                'remote_id' => 'REM-ART-201',
            ],

            // 2. Hakediş Yönetimi
            [
                'category_slug' => 'hakedis-taseron-odemeleri',
                'title' => 'Taşeron Hakediş Cetveli ve %20 KDV Hesaplaması Rehberi',
                'slug' => 'taseron-hakedis-cetveli-ve-kdv-hesabi',
                'summary' => 'Hakediş tutarının hesaplanması, %20 KDV tevkifatı eklenmesi ve teknik onay mekanizması adımları.',
                'content' => "PERGEL ERP hakediş modülünde imalat miktarlarını birim fiyatlarla çarparak metraj ve ihzarat tutarlarını hesaplayabilirsiniz.\n\n"
                    . "Adım Adım Kullanım Senaryosu:\n"
                    . "1. Hakediş Yönetimi sekmesine gidin ve '+ Yeni Hakediş Ekle' butonuna basın.\n"
                    . "2. Taşeron firma ve sözleşme kalemlerini seçerek dönemsel imalat miktarını girin.\n"
                    . "3. Sistem Ara Toplam Matrah üzerinden otomatik %20 KDV tutarını hesaplar.\n"
                    . "4. Varsa Avans Mahsubu (%10) ve Teminat Kesintisi (%5) otomatik olarak net ödenecek tutardan düşülür.\n"
                    . "5. Saha mühendisi incelemesinin ardından 'Onaya Gönder' ve Genel Müdür onayı ile hakedişi kesinleştirin.",
                'views' => 7820,
                'read_time' => 6,
                'is_popular' => true,
                'tags' => ['hakediş', 'kdv', 'taşeron', 'onay', 'metraj'],
                'author' => 'Kıdemli Metraj & Hakediş Mühendisi',
                'remote_id' => 'REM-ART-202',
            ],

            // 3. E-Fatura & GİB
            [
                'category_slug' => 'finans-efatura-nakit-akisi',
                'title' => 'Resmi GİB E-Fatura Kesme ve ETTKN Kod Doğrulaması',
                'slug' => 'resmi-gib-e-fatura-kesme-ve-ettkn-dogrulama',
                'summary' => 'GİB canlı entegrasyonu üzerinden taslak e-fatura oluşturma, VKN doğrulama ve ETTKN kodlu resmi fatura indirme.',
                'content' => "GİB E-Fatura portalımız Gelir İdaresi Başkanlığı servisleri ile canlı senkronize çalışır.\n\n"
                    . "Adım Adım E-Fatura Düzenleme:\n"
                    . "1. E-Fatura sayfasına giderek 'Yeni E-Fatura Oluştur' butonunu tıklayın.\n"
                    . "2. Müşteri Vergi Kimlik Numarasını (VKN) girdiğinizde sistem unvan ve adres bilgilerini otomatik doğrular.\n"
                    . "3. Fatura kalemlerini ekleyip %20 KDV matrahını kontrol edin.\n"
                    . "4. 'GİB'e Gönder' butonuna bastığınızda benzersiz UUID formatında ETTKN kodu otomatik üretilir ve e-fatura mühürlenir.\n"
                    . "5. Üretilen e-faturayı UBL XML veya PDF formatında tek tıkla indirebilirsiniz.",
                'views' => 6950,
                'read_time' => 6,
                'is_popular' => true,
                'tags' => ['efatura', 'gib', 'ettkn', 'vkn', 'kdv'],
                'author' => 'E-Fatura Entegrasyon Uzmanı',
                'remote_id' => 'REM-ART-203',
            ],

            // 4. Nakit Akış Tahmini
            [
                'category_slug' => 'finans-efatura-nakit-akisi',
                'title' => 'Nakit Akış Tahmini & 12 Aylık Projeksiyon Analizi',
                'slug' => 'nakit-akis-tahmini-12-aylik-projeksiyon',
                'summary' => 'Planlanan tahsilat ve ödeme giriş/çıkışlarının güven skorları (%75-%95) ile analiz edilmesi.',
                'content' => "İnşaat projelerinde nakit sıkışıklığı yaşamamak için Nakit Akış Tahmini modülü kullanılır.\n\n"
                    . "Kullanım Adımları:\n"
                    . "1. Nakit Akış Tahmini dashboard ekranını açın.\n"
                    . "2. 6, 12 veya 24 Aylık Projeksiyon periyodunu seçin.\n"
                    . "3. Planlanan Tahsilatlar (Giriş) ve Planlanan Ödemeler (Çıkış) barlarını inceleyin.\n"
                    . "4. Güven Skorunu (%75 - %95) kontrol ederek gerçekleşen vs planlanan akış sapmasını değerlendirin.",
                'views' => 5110,
                'read_time' => 5,
                'is_popular' => false,
                'tags' => ['nakit akış', 'projeksiyon', 'finans', 'bütçe'],
                'author' => 'CFO / Finans Direktörü',
                'remote_id' => 'REM-ART-204',
            ],

            // 5. Döviz İşlemleri
            [
                'category_slug' => 'finans-efatura-nakit-akisi',
                'title' => 'Döviz Alım/Satım & Kur Çevrim Banka Komisyon Takibi',
                'slug' => 'doviz-alim-satim-ve-kur-cevrim-yonetimi',
                'summary' => 'TRY, USD, EUR ve GBP döviz kurları arası alım/satım işlemleri ve banka makas takibi.',
                'content' => "Yabancı para birimi ile malzeme alımlarında kur riskini yönetmek için kullanılır.\n\n"
                    . "Kullanım Adımları:\n"
                    . "1. Döviz Alım/Satım sayfasına gidin.\n"
                    . "2. Çevrim Yönünü (Örn: USD -> TRY veya EUR -> TRY) seçin.\n"
                    . "3. Banka işlem kurunu ve tahsil edilen komisyon tutarını kaydedin.\n"
                    . "4. İşlemi onaylayarak döviz bakiyenizi anlık güncelleyin.",
                'views' => 3890,
                'read_time' => 4,
                'is_popular' => false,
                'tags' => ['döviz', 'usd', 'eur', 'kur', 'banka'],
                'author' => 'Hazine Uzmanı',
                'remote_id' => 'REM-ART-205',
            ],

            // 6. Satın Alma Teklifleri
            [
                'category_slug' => 'satin-alma-ihale-stok-kartlari',
                'title' => 'Tedarikçi Teklif Toplama Tablosu ve Fiyat Karşılaştırma',
                'slug' => 'tedarikci-teklif-toplama-tablosu-ve-karsilastirma',
                'summary' => 'Farklı tedarikçilerden alınan çimento ve demir tekliflerinin fiyat matrisinde kıyaslanması.',
                'content' => "Satın alma modülü en uygun fiyat ve vade şartlarıyla malzeme tedarik etmenizi sağlar.\n\n"
                    . "Adım Adım Teklif Karşılaştırma:\n"
                    . "1. Satın Alma Teklifleri sayfasına gidin ve '+ Yeni Teklif İhalesi Aç' butonuna tıklayın.\n"
                    . "2. İstenen malzeme miktarını (Örn: 50 Ton NERVÜRLÜ İNŞAAT DEMİRİ ST-52) belirtin.\n"
                    . "3. 3 farklı tedarikçiden gelen birim fiyat, nakliye dahil ücret ve ödeme vadesini matrise işleyin.\n"
                    . "4. En düşük fiyatı veren tedarikçiyi seçerek teknik onay sürecine gönderin.",
                'views' => 4720,
                'read_time' => 5,
                'is_popular' => true,
                'tags' => ['satın alma', 'teklif', 'ihale', 'tedarikçi', 'demir'],
                'author' => 'Satın Alma Müdürü',
                'remote_id' => 'REM-ART-206',
            ],

            // 7. Satın Alma Onayları
            [
                'category_slug' => 'satin-alma-ihale-stok-kartlari',
                'title' => 'Satın Alma Bekleyen Onay Talepleri ve Yetki Hiyerarşisi',
                'slug' => 'satinalma-bekleyen-onay-talepleri-ve-hiyerarsi',
                'summary' => 'Belirlenen bütçe limitlerini aşan tedarik taleplerinin üst yönetim onay akışı.',
                'content' => "Şantiye harcamalarında yetki sınırlarını korumak için onay mekanizması işletilir.\n\n"
                    . "Onay Akışı:\n"
                    . "1. Onay Bekleyen Talepler sayfasına girin.\n"
                    . "2. Talep edilen malzeme detayını ve tedarikçi teklif özetini inceleyin.\n"
                    . "3. Bütçe uygunluğuna göre 'Onayla', 'Reddet' veya 'Revize İste' butonuna basın.",
                'views' => 3240,
                'read_time' => 4,
                'is_popular' => false,
                'tags' => ['onay', 'talep', 'satın alma', 'yetki'],
                'author' => 'Operasyon Direktörü',
                'remote_id' => 'REM-ART-207',
            ],

            // 8. Stok Kartları
            [
                'category_slug' => 'satin-alma-ihale-stok-kartlari',
                'title' => 'Şantiye Malzeme Stok Kartları ve Kritik Seviye Uyarısı',
                'slug' => 'santiye-malzeme-stok-kartlari-ve-kritik-seviye',
                'summary' => 'Çimento C30/37, inşaat demiri ve PVC boru envanter kartlarının tanımlanması.',
                'content' => "Stok kartları tüm şantiye envanterinin kimlik kartıdır.\n\n"
                    . "Kullanım Adımları:\n"
                    . "1. Stok Kartları sayfasına girin.\n"
                    . "2. Malzeme Adı, Stok Kodu, Birimi (Ton, m3, Metre) ve Minimum Stok Miktarını girin.\n"
                    . "3. Kritik seviyenin altına düşüldüğünde sistem otomatik satın alma uyarısı üretir.",
                'views' => 4110,
                'read_time' => 4,
                'is_popular' => false,
                'tags' => ['stok', 'kart', 'envanter', 'çimento'],
                'author' => 'Stok Kontrol Müdürü',
                'remote_id' => 'REM-ART-208',
            ],

            // 9. Stok Hareketleri
            [
                'category_slug' => 'satin-alma-ihale-stok-kartlari',
                'title' => 'Ambar Giriş/Çıkış Fişleri ve Şantiye Zimmet Transferi',
                'slug' => 'santiye-ambar-giris-cikis-fisleri-ve-zimmet-transferi',
                'summary' => 'Çimento, PVC boru ve kablo stoklarının ambar hareketleri ile canlı takibi.',
                'content' => "Ambar fişleri şantiyedeki malzeme kayıplarını engellemenin en etkili yoludur.\n\n"
                    . "Stok Hareket Kaydı:\n"
                    . "1. Stok Hareketleri sayfasına girin ve 'Ambar Giriş Fişi (İrsaliye)' oluşturun.\n"
                    . "2. Malzemenin Stok Kartını seçip teslim alınan miktarı yazın.\n"
                    . "3. Sahaya zimmetli malzeme verirken 'Ambar Çıkış Fişi' keserek sorumlu ustabaşının imzasını alın.\n"
                    . "4. Kritik stok seviyesinin altına düşen malzemeler için sistem otomatik bildirim gönderir.",
                'views' => 3950,
                'read_time' => 4,
                'is_popular' => false,
                'tags' => ['stok', 'ambar', 'irsaliye', 'çimento', 'envanter'],
                'author' => 'Ambar Şefi',
                'remote_id' => 'REM-ART-209',
            ],

            // 10. Tesis Yönetimi
            [
                'category_slug' => 'tesis-agir-makine-filosu-gps',
                'title' => 'Tesis Bina İşletme ve Bakım İş Emirleri Yönetimi',
                'slug' => 'tesis-bina-isletme-ve-bakim-is-emirleri',
                'summary' => 'HVAC iklimlendirme santralleri, jeneratör ve asansörlerin periyodik bakımları.',
                'content' => "Tesis yönetimi bina işletme ömrünü uzatan planlı bakımları kapsar.\n\n"
                    . "İş Emri Süreci:\n"
                    . "1. Tesis Yönetimi sayfasına gidin ve Ekipman Listesini açın.\n"
                    . "2. Bakım Zamanı Gelen jeneratör veya asansörü seçerek 'İş Emri Oluştur' butonuna basın.\n"
                    . "3. Bakım teknisyenini ve değiştirilecek filtre/yağ malzemelerini iş emrine tanımlayın.",
                'views' => 3650,
                'read_time' => 4,
                'is_popular' => false,
                'tags' => ['tesis', 'bakım', 'jeneratör', 'asansör'],
                'author' => 'Tesis İşletme Müdürü',
                'remote_id' => 'REM-ART-210',
            ],

            // 11. GPS İş Makinesi Parkı
            [
                'category_slug' => 'tesis-agir-makine-filosu-gps',
                'title' => 'Harita Tabanlı GPS Ağır Ekipman Konum ve Canlı Takip',
                'slug' => 'harita-tabanli-gps-agir-ekipman-konum-takibi',
                'summary' => 'Leaflet.js canlı haritası üzerinde vinç, loder ve ekskavatörlerin anlık enlem/boylam takibi.',
                'content' => "İş makinesi parkı modülü Leaflet.js canlı harita entegrasyonu ile donatılmıştır.\n\n"
                    . "Harita Takip Senaryosu:\n"
                    . "1. İş Makinesi Parkı harita görünümünü açın.\n"
                    . "2. Harita üzerindeki özel pin işaretçilerine tıklayarak makinenin anlık GPS Koordinatlarını görün.\n"
                    . "3. Açılır harita bilgi kartında (Popup) Operatör Adı, Çalışma Saati ve Yakıt Depo Seviyesini inceleyin.\n"
                    . "4. Tek tıkla ilgili varlığın detay sayfasına geçerek periyodik bakım iş emirlerini görün.",
                'views' => 4340,
                'read_time' => 4,
                'is_popular' => true,
                'tags' => ['gps', 'harita', 'leaflet', 'ekipman', 'vinç'],
                'author' => 'Filo Yöneticisi',
                'remote_id' => 'REM-ART-211',
            ],

            // 12. Demirbaş Envanteri
            [
                'category_slug' => 'tesis-agir-makine-filosu-gps',
                'title' => 'Demirbaş & Sabit Kıymet Amortisman ve Zimmet Takibi',
                'slug' => 'demirbas-sabit-kiymet-amortisman-ve-zimmet-takibi',
                'summary' => 'Şantiye konteynerleri, topoğrafik ölçüm cihazları ve dizüstü bilgisayar zimmetleri.',
                'content' => "Demirbaş modülü sabit kıymetlerin faydalı ömrünü ve zimmetli personelini izler.\n\n"
                    . "Kullanım Adımları:\n"
                    . "1. Demirbaş Listesi sayfasına gidin.\n"
                    . "2. Varlık Barkodunu girin ve Amortisman Oranını tanımlayın.\n"
                    . "3. 'Zimmetle' butonuna basarak cihazı şantiye mühendisine veya topoğrafa atayın.",
                'views' => 3110,
                'read_time' => 4,
                'is_popular' => false,
                'tags' => ['demirbaş', 'zimmet', 'amortisman', 'sabit kıymet'],
                'author' => 'Demirbaş Sorumlusu',
                'remote_id' => 'REM-ART-212',
            ],

            // 13. İK & Şantiye Kadrosu
            [
                'category_slug' => 'insan-kaynaklari-bordro-avans',
                'title' => 'İnşaat Şantiye Personeli Kartları ve ISG Sertifika Takibi',
                'slug' => 'insaat-santiye-personeli-kartlari-ve-isg-sertifika',
                'summary' => 'İş güvenliği (İSG) yüksekte çalışma sertifikaları ve personel şantiye atamaları.',
                'content' => "İnşaat sektöründe İSG sertifikası olmayan personelin sahaya girişi engellenmelidir.\n\n"
                    . "İK İş Akışı:\n"
                    . "1. Personel Listesi sayfasına girin.\n"
                    . "2. Çalışan profiline Yüksekte Çalışma ve Mesleki Yeterlilik Sertifikasını yükleyin.\n"
                    . "3. Sertifika geçerlilik süresi dolmadan 15 gün önce sistem otomatik İSG uyarısı gönderir.",
                'views' => 3810,
                'read_time' => 4,
                'is_popular' => false,
                'tags' => ['ik', 'personel', 'isg', 'sertifika'],
                'author' => 'İK & İSG Yöneticisi',
                'remote_id' => 'REM-ART-213',
            ],

            // 14. Personel Harcamaları
            [
                'category_slug' => 'insan-kaynaklari-bordro-avans',
                'title' => 'Personel Harcırah & Ozalit Seyahat Masraf Beyanı',
                'slug' => 'personel-harcirah-ve-ozalit-masraf-beyani',
                'summary' => 'Şantiye mühendislerinin ozalit, yemek ve ulaşım masraf fişlerinin onaylanması.',
                'content' => "Personel harcamalarının hızlı ve şeffaf bir şekilde ödenmesini sağlar.\n\n"
                    . "Masraf Beyan Adımları:\n"
                    . "1. Masraf Beyanları sayfasına gidin ve '+ Yeni Fiş Ekle' butonuna basın.\n"
                    . "2. Fiş Kategorisini (Ozalit, Seyahat, Yemek) ve tutarı (%20 KDV dahil) girin.\n"
                    . "3. Fiş fotoğrafını yükleyip proje yöneticisi onayına gönderin.",
                'views' => 3420,
                'read_time' => 4,
                'is_popular' => false,
                'tags' => ['masraf', 'harcırah', 'ozalit', 'fiş'],
                'author' => 'Muhasebe Şefi',
                'remote_id' => 'REM-ART-214',
            ],

            // 15. Personel Avansları
            [
                'category_slug' => 'insan-kaynaklari-bordro-avans',
                'title' => 'Maaş Avansı & Şantiye İntikal Taksitli Mahsuplaşma',
                'slug' => 'maas-avansi-ve-santiye-intikal-taksitli-mahsuplasma',
                'summary' => 'Çalışanlara verilen maaş avanslarının aylık bordrodan otomatik kesintisi.',
                'content' => "Avans modülü personel borç ve mahsuplaşmalarını hatasız yönetir.\n\n"
                    . "Avans İşlemi:\n"
                    . "1. Avans Portalı sayfasına gidin.\n"
                    . "2. Talep edilen avans tutarını ve Taksit Sayısını (Örn: 3 Eşit Taksit) yazın.\n"
                    . "3. Onaylanan avans tutarı aylık bordrolara otomatik kesinti kalemi olarak eklenir.",
                'views' => 3290,
                'read_time' => 4,
                'is_popular' => false,
                'tags' => ['avans', 'mahsuplaşma', 'taksit', 'bordro'],
                'author' => 'Bordro Uzmanı',
                'remote_id' => 'REM-ART-215',
            ],

            // 16. Personel Bordro
            [
                'category_slug' => 'insan-kaynaklari-bordro-avans',
                'title' => 'Aylık Personel Bordrosu & SGK Maaş Tahakkuku',
                'slug' => 'aylik-personel-bordrosu-ve-sgk-maas-tahakkuku',
                'summary' => 'Puantaj verilerinden otomatik net maaş, fazla mesai ve SGK primi hesaplama.',
                'content' => "Bordro modülü saha puantaj verileriyle entegre çalışır.\n\n"
                    . "Bordro Tahakkuku:\n"
                    . "1. Bordro sayfasına gidin ve İlgili Ayı seçin.\n"
                    . "2. Puantaj verilerini aktararak net maaş, mesai ücreti ve avans kesintilerini hesaplatın.\n"
                    . "3. Banka maaş ödeme listesini Excel/TXT formatında dışa aktarın.",
                'views' => 4150,
                'read_time' => 5,
                'is_popular' => false,
                'tags' => ['bordro', 'maaş', 'sgk', 'puantaj'],
                'author' => 'Bordro Müdürü',
                'remote_id' => 'REM-ART-216',
            ],

            // 17. Satış Performansı
            [
                'category_slug' => 'gayrimenkul-satis-finansal-raporlar',
                'title' => 'Satış Temsilcisi Dönem Ciro Hedefleri ve Performansı',
                'slug' => 'satis-temsilcisi-donem-ciro-hedefleri-ve-performans',
                'summary' => 'Gayrimenkul konut satış temsilcilerinin kazanılan sözleşme hedefleri.',
                'content' => "Satış ekiplerinin motivasyonunu ve gerçekleşen cirolarını izlemek için kullanılır.\n\n"
                    . "Analiz Adımları:\n"
                    . "1. Satış Performansı sayfasına gidin.\n"
                    . "2. Temsilci bazında Dönem Ciro Hedefini ve Kazanılan Sözleşmeleri görün.\n"
                    . "3. Hedef gerçekleşme yüzdesini (%80 - %120) performans grafiğinde inceleyin.",
                'views' => 3890,
                'read_time' => 4,
                'is_popular' => false,
                'tags' => ['satış', 'performans', 'ciro', 'hedef'],
                'author' => 'Satış Direktörü',
                'remote_id' => 'REM-ART-217',
            ],

            // 18. Gayrimenkul Taksit Muhasebesi
            [
                'category_slug' => 'gayrimenkul-satis-finansal-raporlar',
                'title' => 'Konut Satış Taksit Muhasebesi & Müşteri Bakiyesi',
                'slug' => 'konut-satis-taksit-muhasebesi-ve-musteri-bakiyesi',
                'summary' => 'Satılan dairelerin taksit ödeme planı, senet vadeleri ve tahsilat durumu.',
                'content' => "Konut projelerinde müşteri taksitlerinin zamanında tahsil edilmesini sağlar.\n\n"
                    . "Taksit Takibi:\n"
                    . "1. Satış Taksit Muhasebe sayfasına girin.\n"
                    . "2. Daire No ve Müşteri Adını aratın.\n"
                    . "3. Vadesi geçen taksitleri, yapılan ödemeleri ve kalan anapara bakiyesini inceleyin.",
                'views' => 4550,
                'read_time' => 5,
                'is_popular' => true,
                'tags' => ['taksit', 'konut', 'gayrimenkul', 'tahsilat'],
                'author' => 'Finansal Muhasebe Müdürü',
                'remote_id' => 'REM-ART-218',
            ],

            // 19. Proje Finansal Analiz
            [
                'category_slug' => 'gayrimenkul-satis-finansal-raporlar',
                'title' => 'Proje Kar/Zarar Analizi & Bütçe Maliyet Kıyaslaması',
                'slug' => 'proje-kar-zarar-analizi-ve-butce-maliyet-kiyaslama',
                'summary' => 'Planlanan inşaat bütçesi ile gerçekleşen hakediş maliyetlerinin karşılaştırılması.',
                'content' => "Proje kar marjını korumak için finansal analiz raporları kullanılır.\n\n"
                    . "Rapor İnceleme:\n"
                    . "1. Proje Finansal Analiz sayfasına gidin.\n"
                    . "2. Toplam İmalat Maliyeti vs Toplam Satış Geliri kartlarını inceleyin.\n"
                    . "3. Bütçe aşımı olan kalemleri tespit ederek önleyici maliyet tedbiri alın.",
                'views' => 4910,
                'read_time' => 5,
                'is_popular' => true,
                'tags' => ['kar zarar', 'bütçe', 'maliyet', 'analiz'],
                'author' => 'Proje Kontrol Yöneticisi',
                'remote_id' => 'REM-ART-219',
            ],

            // 20. Üst Yönetim KPI Dashboard
            [
                'category_slug' => 'gayrimenkul-satis-finansal-raporlar',
                'title' => 'Genel Yönetim Üst Düzey KPI Dashboard & Konsolide Ciro',
                'slug' => 'genel-yonetim-ust-duzey-kpi-dashboard',
                'summary' => 'Şirket sahipleri ve Genel Müdür için tüm projelerin özet performans göstergeleri.',
                'content' => "Üst yönetimin tek bir ekrandan şirket sağlığını izlemesini sağlar.\n\n"
                    . "Dashboard Metrikleri:\n"
                    . "1. Genel Yönetim KPI Dashboard ekranını açın.\n"
                    . "2. Toplam Şantiye Sayısı, Aktif Taşeron Adedi, Konsolide Ciro ve Likidite Oranını görün.\n"
                    . "3. Grafikler üzerinden projeler arası performans kıyaslaması yapın.",
                'views' => 5890,
                'read_time' => 5,
                'is_popular' => true,
                'tags' => ['kpi', 'dashboard', 'yönetim', 'ciro'],
                'author' => 'Genel Müdür',
                'remote_id' => 'REM-ART-220',
            ],

            // 21. Gelişmiş Raporlar Hub
            [
                'category_slug' => 'gayrimenkul-satis-finansal-raporlar',
                'title' => 'Gelişmiş İşletme Raporları Hub ve Çok Boyutlu Raporlama',
                'slug' => 'gelismis-isletme-raporlari-hub-ve-cok-boyutlu',
                'summary' => 'Sezonluk satış dinamikleri, ödeme analizi ve çapraz sorgu raporları.',
                'content' => "Gelişmiş raporlama modülü derinlemesine veri analizi olanağı sunar.\n\n"
                    . "Kullanım Adımları:\n"
                    . "1. Gelişmiş Raporlar Hub sayfasına gidin.\n"
                    . "2. Sezonluk Analiz, Ödeme Analizi veya Çok Boyutlu Sorgulama kartını seçin.\n"
                    . "3. Özel filtreleme kriterlerini uygulayarak raporu Excel formatında indirin.",
                'views' => 3780,
                'read_time' => 4,
                'is_popular' => false,
                'tags' => ['rapor', 'hub', 'sezonluk', 'analiz'],
                'author' => 'İş Analisti',
                'remote_id' => 'REM-ART-221',
            ],

            // 22. Kullanıcı Yönetimi
            [
                'category_slug' => 'sistem-yonetimi-audit-kalite',
                'title' => 'Kullanıcı Yönetimi, Rol Rol Yetkilendirme ve İzin Matrisi',
                'slug' => 'kullanici-yonetimi-rol-yetkilendirme-ve-izin-matrisi',
                'summary' => 'Saha mühendisi, muhasebeci ve şantiye şefi rol yetkilerinin yapılandırılması.',
                'content' => "Kullanıcı rolleri sistem güvenliğinin temelini oluşturur.\n\n"
                    . "Yetkilendirme Adımları:\n"
                    . "1. Kullanıcı Yönetimi sayfasına girin.\n"
                    . "2. '+ Yeni Kullanıcı Ekle' butonuna basın veya mevcut kullanıcıyı seçin.\n"
                    . "3. Kullanıcıya 'Saha Mühendisi' veya 'Finans Yöneticisi' rolünü atayın.\n"
                    . "4. İzin matrisinden modül okuma/yazma/silme yetkilerini özelleştirin.",
                'views' => 4210,
                'read_time' => 5,
                'is_popular' => false,
                'tags' => ['kullanıcı', 'rol', 'yetki', 'güvenlik'],
                'author' => 'Sistem Yöneticisi (Admin)',
                'remote_id' => 'REM-ART-222',
            ],

            // 23. Yazılım Lisansı
            [
                'category_slug' => 'sistem-yonetimi-audit-kalite',
                'title' => 'Yazılım Lisansı & Pro Modül Etkinleştirme Yönetimi',
                'slug' => 'yazilim-lisansi-ve-pro-modul-etkinlestirme',
                'summary' => 'PERGEL ERP yazılım lisans anahtarı ve aktif modül kullanım süreleri.',
                'content' => "Yazılım lisansı sistemin aktif modül kapasitesini belirler.\n\n"
                    . "Lisans Kontrolü:\n"
                    . "1. Lisans Yönetimi sayfasına gidin.\n"
                    . "2. Lisans Son Kullanma Tarihini ve Maksimum Kullanıcı Limitini inceleyin.\n"
                    . "3. Yeni pro modül aktifleştirmek için Lisans Anahtarını girip 'Etkinleştir'e basın.",
                'views' => 3120,
                'read_time' => 3,
                'is_popular' => false,
                'tags' => ['lisans', 'pro', 'modül', 'etkinleştirme'],
                'author' => 'Teknik Lisans Ekibi',
                'remote_id' => 'REM-ART-223',
            ],

            // 24. Kalite Standartları
            [
                'category_slug' => 'sistem-yonetimi-audit-kalite',
                'title' => 'TS EN 206 Beton Basınç Dayanım ve Kalite Deneyi Şartnamesi',
                'slug' => 'ts-en-206-beton-kalite-deneyi',
                'summary' => 'Şantiyeye giren C30/37 beton mikser numunelerinin 7 ve 28 günlük silindir kıran test takibi.',
                'content' => "Beton ve demir numunelerinin şartnameye uygunluğu test edilmelidir.\n\n"
                    . "Kalite Test Takibi:\n"
                    . "1. Kalite Standartları modülünden TS EN 206 standardını seçin.\n"
                    . "2. Laboratuvar 7 ve 28 günlük silindir kıran test sonuçlarını girin.\n"
                    . "3. Basınç dayanımı şartname değerini karşıladığında 'Uyum Sertifikası' üretin.",
                'views' => 3890,
                'read_time' => 5,
                'is_popular' => false,
                'tags' => ['kalite', 'beton', 'ts en 206', 'laboratuvar'],
                'author' => 'Kalite Kontrol Müdürü',
                'remote_id' => 'REM-ART-224',
            ],

            // 25. Veri İçe/Dışa Aktarma
            [
                'category_slug' => 'sistem-yonetimi-audit-kalite',
                'title' => 'Veri İçe & Dışa Aktarma (Excel/CSV Import & Export)',
                'slug' => 'veri-ice-ve-disa-aktarma-excel-csv-import-export',
                'summary' => 'Toplu metraj, stok ve hakediş verilerinin Excel şablonları ile yüklenmesi.',
                'content' => "Büyük veri setlerini tek tek girmek yerine toplu aktarım imkanı sağlar.\n\n"
                    . "Aktarım Adımları:\n"
                    . "1. Veri İçe/Dışa Aktarma sayfasına gidin.\n"
                    . "2. İçe aktarılacak veri tipini (Stok Kartları, Personel Listesi) seçip Örnek Excel Şablonunu indirin.\n"
                    . "3. Doldurduğunuz Excel dosyasını yükleyin ve 'Önizleme & Onayla' butonuna basın.",
                'views' => 4670,
                'read_time' => 4,
                'is_popular' => true,
                'tags' => ['excel', 'csv', 'import', 'export', 'veri'],
                'author' => 'Veritabanı Yöneticisi',
                'remote_id' => 'REM-ART-225',
            ],

            // 26. Sistem Aktivite Günlüğü
            [
                'category_slug' => 'sistem-yonetimi-audit-kalite',
                'title' => 'Sistem Aktivite Günlüğü ve Audit Trail Güvenlik İzleme',
                'slug' => 'sistem-aktivite-gunlugu-guvenlik-izleme',
                'summary' => 'Kullanıcıların sistemdeki veri değişikliklerini, silme işlemlerini ve IP izlerini denetleme.',
                'content' => "Aktivite Günlüğü modülü güvenlik ve uyumluluk standartları için tüm kritik işlemleri kaydeder.\n\n"
                    . "Denetim Adımları:\n"
                    . "1. Aktivite Günlüğü sayfasına gidin.\n"
                    . "2. Kullanıcı bazlı arama yapın veya İşlem Tipi (Oluşturma, Güncelleme, Silme) filtresini uygulayın.\n"
                    . "3. İşlemin eski vs yeni değer farkını (Diff view) inceleyin.\n"
                    . "4. İşlemin yapıldığı IP Adresi ve Kullanıcı Cihazını (User Agent) raporlayın.",
                'views' => 3190,
                'read_time' => 5,
                'is_popular' => false,
                'tags' => ['güvenlik', 'audit', 'log', 'ip', 'izin'],
                'author' => 'Sistem Güvenlik Direktörü',
                'remote_id' => 'REM-ART-226',
            ],

            // 27. Sistem Sağlığı
            [
                'category_slug' => 'sistem-yonetimi-audit-kalite',
                'title' => 'Sistem Sağlığı, Sunucu Performansı ve Veritabanı Ölçümleri',
                'slug' => 'sistem-sagligi-sunucu-performansi-ve-veritabani',
                'summary' => 'PHP çalışma zamanı, bellek kullanımı, DB boyutu ve 24 saatlik hata takibi.',
                'content' => "Sunucunun kesintisiz çalışması ve yüksek performansı için kullanılır.\n\n"
                    . "Sunucu Kontrolü:\n"
                    . "1. Sistem Sağlığı sayfasına gidin.\n"
                    . "2. CPU Yükü, RAM Kullanımı, Veritabanı Boyutu ve Son 24 Saat Hata Sayısını inceleyin.\n"
                    . "3. 'Önbelleği Temizle' veya 'Veritabanı Optimize Et' butonları ile sistemi hızlandırın.",
                'views' => 2980,
                'read_time' => 4,
                'is_popular' => false,
                'tags' => ['sistem', 'sunucu', 'performans', 'veritabanı'],
                'author' => 'DevOps Mühendisi',
                'remote_id' => 'REM-ART-227',
            ],

            // 28. Proje Kapanış
            [
                'category_slug' => 'proje-kapanis-gecici-kabul',
                'title' => 'Geçici Kabul Eksik/Kusurlu İmalat Kontrol Listesi Yönetimi',
                'slug' => 'gecici-kabul-eksik-kusurlu-imalat-kontrol-listesi',
                'summary' => 'Şantiye kapanış öncesi eksik listelerinin hazırlanması ve garanti arıza talepleri.',
                'content' => "Proje Kapanış modülü şantiyenin sorunsuz bir şekilde idareye veya mal sahibine teslimini sağlar.\n\n"
                    . "Kapanış Adımları:\n"
                    . "1. Proje Kapanış sekmesinden '+ Yeni Kapanış Dosyası' oluşturun.\n"
                    . "2. Kontrol Listesi bölümüne eksik boya, kırık fayans veya eksik armatür kalemlerini ekleyin.\n"
                    . "3. Her kusurlu kalem için sorumluluk atayın ve tamamlanma durumunu (%0 - %100) güncelleyin.\n"
                    . "4. Teslim sonrası oluşan arızalar için Garanti Bilet Formu doldurarak teknik servise yönlendirin.",
                'views' => 3120,
                'read_time' => 5,
                'is_popular' => false,
                'tags' => ['kapanış', 'geçici kabul', 'garanti', 'teslimat'],
                'author' => 'Kabul Komisyonu Başkanı',
                'remote_id' => 'REM-ART-228',
            ],
        ];

        foreach ($articles as $artData) {
            $catId = $categoriesMap[$artData['category_slug']] ?? null;
            HelpArticle::updateOrCreate(['slug' => $artData['slug']], [
                'help_category_id' => $catId,
                'title' => $artData['title'],
                'summary' => $artData['summary'],
                'content' => $artData['content'],
                'views' => $artData['views'],
                'read_time' => $artData['read_time'],
                'is_popular' => $artData['is_popular'],
                'tags' => $artData['tags'],
                'author' => $artData['author'],
                'remote_id' => $artData['remote_id'],
                'last_synced_at' => now(),
            ]);
        }

        // 3. Comprehensive FAQs for ALL areas
        $faqs = [
            [
                'category_name' => '1. Şantiye & Saha',
                'question' => 'Günlük puantajlarda mesai saatleri nasıl hesaplanır?',
                'answer' => 'Saha günlüğünde normal çalışma süresi (8 saat) dışındaki fazla mesai saatleri 1.5 katsayısıyla otomatik çarpılarak işçi maaş tahakkukuna aktarılır.',
                'sort_order' => 1,
                'remote_id' => 'REM-FAQ-101',
            ],
            [
                'category_name' => '2. Hakediş Yönetimi',
                'question' => 'Taşeron hakedişinde %20 KDV oranı ve tevkifat nasıl hesaplanır?',
                'answer' => 'PERGEL ERP hakediş modülünde dönemsel metraj miktarları girildikten sonra ara toplam matrah belirlenir. Sistem otomatik %20 KDV tutarını hesaplar. Gerekli durumlarda KDV tevkifat oranını (Örn: 4/10 veya 9/10) manuel olarak seçebilirsiniz.',
                'sort_order' => 2,
                'remote_id' => 'REM-FAQ-102',
            ],
            [
                'category_name' => '3. Finans & E-Fatura',
                'question' => 'GİB Canlı Bağlantı Rozeti (Pulse Indicator) ne anlama gelir?',
                'answer' => 'E-Fatura sayfasının sağ üstünde yer alan canlı yeşil pulse sinyali, Gelir İdaresi Başkanlığı (GİB) entegratör servislerinin online ve sorunsuz çalıştığını gösterir.',
                'sort_order' => 3,
                'remote_id' => 'REM-FAQ-103',
            ],
            [
                'category_name' => '3. Finans & E-Fatura',
                'question' => 'Resmi E-Fatura ETTKN numarası nasıl üretilir?',
                'answer' => 'Faturanız GİB servisine iletildiği anda Gelir İdaresi tarafından benzersiz UUID formatında ETTKN (Elektronik Teminat ve Takip Kod Numarası) atanır ve faturalarınız resmi olarak mühürlenir.',
                'sort_order' => 4,
                'remote_id' => 'REM-FAQ-104',
            ],
            [
                'category_name' => '4. Satın Alma & Stok',
                'question' => 'Stok kartlarında kritik stok uyarısı nasıl çalışır?',
                'answer' => 'Malzeme stok kartına tanımlanan min_stock miktarının altına düşüldüğünde sistem şantiye ambar şefine ve satın alma departmanına otomatik e-posta ve sistem içi bildirim gönderir.',
                'sort_order' => 5,
                'remote_id' => 'REM-FAQ-105',
            ],
            [
                'category_name' => '5. Tesis & GPS Ekipman',
                'question' => 'İş makinelerinin haritadaki konumları nasıl güncellenir?',
                'answer' => 'İş makinelerindeki GPS IoT cihazları dakikada bir enlem/boylam koordinatlarını PERGEL API servisine iletir ve Leaflet.js canlı haritada makinenin konumu otomatik güncellenir.',
                'sort_order' => 6,
                'remote_id' => 'REM-FAQ-106',
            ],
            [
                'category_name' => '6. İK & Avans',
                'question' => 'Maaş avansları taksitli olarak bordrodan nasıl düşülür?',
                'answer' => 'Avans onaylanırken belirlenen taksit sayısı kadar eşit tutar, her ay otomatik olarak çalışanın bordro kesinti kalemine yansıtılır.',
                'sort_order' => 7,
                'remote_id' => 'REM-FAQ-107',
            ],
            [
                'category_name' => '7. Satış & Raporlar',
                'question' => 'Konut satış taksit ödemeleri geciktiğinde ne olur?',
                'answer' => 'Vadesi geçen taksitler Satış Taksit Muhasebesi sayfasında kırmızı uyarı rozeti ile gösterilir ve müşteriye otomatik hatırlatma SMS/e-postası gönderilir.',
                'sort_order' => 8,
                'remote_id' => 'REM-FAQ-108',
            ],
            [
                'category_name' => '8. Sistem & Audit',
                'question' => 'Silinen bir veriyi kimin sildiğini nereden görebilirim?',
                'answer' => 'Sistem Aktivite Günlüğü (Activity Logs) modülünden silme işlemini yapan kullanıcının adını, tarihini ve IP adresini görebilirsiniz.',
                'sort_order' => 9,
                'remote_id' => 'REM-FAQ-109',
            ],
            [
                'category_name' => '9. Proje Kapanış',
                'question' => 'Geçici kabul sonrası garanti süresi ne zaman başlar?',
                'answer' => 'Geçici kabul tutanağı taraflarca imzalandığı gün itibarıyla sistemde 12 veya 24 aylık garanti takip sayacı otomatik olarak işlemeye başlar.',
                'sort_order' => 10,
                'remote_id' => 'REM-FAQ-110',
            ],
        ];

        foreach ($faqs as $faqData) {
            HelpFaq::updateOrCreate(['question' => $faqData['question']], $faqData);
        }

        // 4. Video Tutorials for key areas
        $videos = [
            [
                'category_name' => '1. Saha Operasyonları',
                'title' => 'Günlük Saha Günlüğü ve Puantaj Girişi',
                'description' => 'Şantiye şefleri ve topoğraflar için günlük çalışma saatleri, fazla mesai ve hava durumu kaydı.',
                'duration' => '04:15',
                'video_url' => 'https://pergel.com/tutorials/field-operations.mp4',
                'remote_id' => 'REM-VID-201',
            ],
            [
                'category_name' => '2. Hakediş & Finans',
                'title' => 'Taşeron Hakediş Hesabı ve KDV Tevkifatı',
                'description' => 'İmalat miktarlarının girilmesi, ihzarat düşülmesi ve onay aşamaları.',
                'duration' => '06:45',
                'video_url' => 'https://pergel.com/tutorials/hakedis-calculation.mp4',
                'remote_id' => 'REM-VID-202',
            ],
            [
                'category_name' => '3. E-Fatura & GİB',
                'title' => 'Resmi GİB E-Fatura Kesme ve XML İndirme',
                'description' => 'Fatura taslağı oluşturma, VKN doğrulaması ve GİB portalına gönderme.',
                'duration' => '03:50',
                'video_url' => 'https://pergel.com/tutorials/efatura-gib.mp4',
                'remote_id' => 'REM-VID-203',
            ],
            [
                'category_name' => '4. Satın Alma & Stok',
                'title' => 'Tedarikçi Teklif Toplama ve Karşılaştırma Matrisi',
                'description' => '3 farklı tedarikçiden fiyat teklifi toplama ve en uygun teklifi onaylama.',
                'duration' => '04:50',
                'video_url' => 'https://pergel.com/tutorials/purchasing-quotation.mp4',
                'remote_id' => 'REM-VID-204',
            ],
            [
                'category_name' => '5. Tesis & GPS',
                'title' => 'İş Makinesi Canlı GPS Harita Takibi',
                'description' => 'Ekskavatör, kule vinç ve damperli kamyonların haritada canlı takibi ve yakıt kontrolü.',
                'duration' => '05:30',
                'video_url' => 'https://pergel.com/tutorials/gps-tracking.mp4',
                'remote_id' => 'REM-VID-205',
            ],
            [
                'category_name' => '7. Satış & Raporlar',
                'title' => 'Üst Yönetim KPI Dashboard ve Proje Kar/Zarar Analizi',
                'description' => 'Genel Müdür ve şirket ortakları için konsolide finansal durum göstergeleri.',
                'duration' => '05:10',
                'video_url' => 'https://pergel.com/tutorials/kpi-dashboard.mp4',
                'remote_id' => 'REM-VID-206',
            ],
        ];

        foreach ($videos as $vidData) {
            HelpVideoTutorial::updateOrCreate(['title' => $vidData['title']], $vidData);
        }
    }
}
