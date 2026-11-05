<?php

namespace App\Services;

use App\Models\HelpCategory;
use App\Models\HelpArticle;
use App\Models\HelpFaq;
use App\Models\HelpVideoTutorial;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class HelpCenterSyncService
{
    /**
     * Sync knowledge base content from remote central server or mock payload
     */
    public function syncFromRemoteServer(?string $endpointUrl = null): array
    {
        $payload = null;

        if ($endpointUrl && filter_var($endpointUrl, FILTER_VALIDATE_URL)) {
            try {
                $response = Http::timeout(10)->get($endpointUrl);
                if ($response->successful()) {
                    $payload = $response->json();
                }
            } catch (\Exception $e) {
                // Fallback to simulated central update payload
            }
        }

        if (!$payload) {
            $payload = $this->getSimulatedRemotePayload();
        }

        $syncedCategories = 0;
        $syncedArticles = 0;

        // Upsert categories
        if (!empty($payload['categories'])) {
            foreach ($payload['categories'] as $catData) {
                $slug = $catData['slug'] ?? Str::slug($catData['name']);
                HelpCategory::updateOrCreate(
                    ['slug' => $slug],
                    [
                        'name' => $catData['name'],
                        'description' => $catData['description'] ?? null,
                        'icon' => $catData['icon'] ?? 'ri-folder-open-line',
                        'color' => $catData['color'] ?? 'primary',
                        'remote_id' => $catData['remote_id'] ?? null,
                    ]
                );
                $syncedCategories++;
            }
        }

        // Upsert articles
        if (!empty($payload['articles'])) {
            foreach ($payload['articles'] as $artData) {
                $category = HelpCategory::where('slug', $artData['category_slug'] ?? '')->first();
                $slug = $artData['slug'] ?? Str::slug($artData['title']);

                HelpArticle::updateOrCreate(
                    ['slug' => $slug],
                    [
                        'help_category_id' => $category?->id,
                        'title' => $artData['title'],
                        'summary' => $artData['summary'] ?? null,
                        'content' => $artData['content'] ?? null,
                        'views' => $artData['views'] ?? 100,
                        'read_time' => $artData['read_time'] ?? 5,
                        'is_popular' => $artData['is_popular'] ?? false,
                        'tags' => $artData['tags'] ?? [],
                        'author' => $artData['author'] ?? 'Merkez Güncelleme Ekibi',
                        'remote_id' => $artData['remote_id'] ?? null,
                        'last_synced_at' => now(),
                    ]
                );
                $syncedArticles++;
            }
        }

        return [
            'status' => 'success',
            'synced_categories' => $syncedCategories,
            'synced_articles' => $syncedArticles,
            'timestamp' => now()->format('d.m.Y H:i:s'),
        ];
    }

    private function getSimulatedRemotePayload(): array
    {
        return [
            'categories' => [
                [
                    'name' => '1. Yapay Zeka & Genel Bakış',
                    'slug' => 'yapay-zeka-genel-bakis',
                    'description' => 'Yapay Zeka Asistanı, proaktif öneriler, AI risk ve saha veri analitiği, tahminleme modelleri, OCR doküman işleme ve mevzuat uyumu.',
                    'icon' => 'ri-robot-line',
                    'color' => 'indigo',
                    'remote_id' => 'REM-CAT-101',
                ],
                [
                    'name' => '2. Portföy, İhale & Fizibilite',
                    'slug' => 'portfoy-ihale-fizibilite',
                    'description' => 'Portföy yönetimi, proje listeleri, program yönetimi, fizibilite ve senaryo kıyaslama, teklif & ihale paneli.',
                    'icon' => 'ri-pie-chart-line',
                    'color' => 'primary',
                    'remote_id' => 'REM-CAT-102',
                ],
                [
                    'name' => '3. CRM & Satış Yönetimi',
                    'slug' => 'crm-satis-yonetimi',
                    'description' => 'Potansiyel projeler (fırsatlar), satış hedefleri, opsiyon yönetimi, gayrimenkul üniteleri ve esnek ödeme senaryoları.',
                    'icon' => 'ri-shopping-bag-3-line',
                    'color' => 'warning',
                    'remote_id' => 'REM-CAT-103',
                ],
                [
                    'name' => '4. Müşteri Yönetimi & Satış Sonrası',
                    'slug' => 'musteri-yonetimi-satis-sonrasi',
                    'description' => 'Müşteri görüşmeleri, tapu & mesken ruhsat takibi, taksit ve senet vadeleri, takas varlıkları ve KVKK onayları.',
                    'icon' => 'ri-heart-pulse-line',
                    'color' => 'success',
                    'remote_id' => 'REM-CAT-104',
                ],
                [
                    'name' => '5. Proje & Şantiye Yönetimi',
                    'slug' => 'proje-santiye-yonetimi',
                    'description' => 'Proje detayları, GIS canlı harita, doküman kontrolü, iş planlama, proje kapanış, sözleşmeler ve hak talepleri (Claims).',
                    'icon' => 'ri-building-2-line',
                    'color' => 'info',
                    'remote_id' => 'REM-CAT-105',
                ],
                [
                    'name' => '6. Saha Operasyonları, İSG & Kalite',
                    'slug' => 'saha-operasyonlari-isg-kalite',
                    'description' => 'Mobil saha günlüğü, günlük defterler, CCTV kameralar, kalite kontrol, Punch List, İSG olay/kaza kayıtları ve EBİS beton testleri.',
                    'icon' => 'ri-shield-user-line',
                    'color' => 'danger',
                    'remote_id' => 'REM-CAT-106',
                ],
                [
                    'name' => '7. Taşeron & Tedarik Zinciri',
                    'slug' => 'taseron-tedarik-zinciri',
                    'description' => 'Taşeron rehberi, taşeron sözleşmeleri, hakediş onayları, yasal kesintiler, taşeron puanlama, portal, tedarikçi ve satınalma.',
                    'icon' => 'ri-truck-line',
                    'color' => 'teal',
                    'remote_id' => 'REM-CAT-107',
                ],
                [
                    'name' => '8. Hakediş, Finans & Muhasebe',
                    'slug' => 'hakedis-finans-muhasebe',
                    'description' => 'Hakediş listesi ve cetvelleri, cari hesaplar, kasa & banka, mahsup/fiş, POS, çek/senet, e-fatura entegrasyonu ve nakit tahmini.',
                    'icon' => 'ri-calculator-line',
                    'color' => 'indigo',
                    'remote_id' => 'REM-CAT-108',
                ],
                [
                    'name' => '9. Kaynaklar, İK & Tesis Yönetimi',
                    'slug' => 'kaynaklar-ik-tesis-yonetimi',
                    'description' => 'İK özlük ve bordro, satınalma onayları, stok kartları ve ambar hareketleri, tesis varlıkları, ekipman parkı ve telemetri.',
                    'icon' => 'ri-community-line',
                    'color' => 'purple',
                    'remote_id' => 'REM-CAT-109',
                ],
                [
                    'name' => '10. TR İnşaat Mevzuatı & Yasal Uyum',
                    'slug' => 'tr-insaat-mevzuati-yasal-uyum',
                    'description' => 'SGK asgari işçilik, kat karşılığı matrisi, ÇŞB poz kütüphanesi, Yİ-ÜFE fiyat farkı, Yapı Denetim, KDV İade ve MoTAT.',
                    'icon' => 'ri-government-line',
                    'color' => 'secondary',
                    'remote_id' => 'REM-CAT-110',
                ],
                [
                    'name' => '11. İş Zekası, Raporlar & Çalışma Alanı',
                    'slug' => 'is-zekasi-raporlar-calisma-alani',
                    'description' => 'Executive BI dashboards, risk haritası, gelir/maliyet analizleri, danışman performansı, dinamik rapor oluşturucu, görevler ve kanban.',
                    'icon' => 'ri-bar-chart-2-line',
                    'color' => 'dark',
                    'remote_id' => 'REM-CAT-111',
                ],
                [
                    'name' => '12. Platform Yönetimi & Destek',
                    'slug' => 'platform-yonetimi-destek',
                    'description' => 'Kullanıcı yetki ve roller, yazılım lisansı, şirket ayarları, KDV oranları, Excel veri import/export, audit logları ve yardım destek.',
                    'icon' => 'ri-settings-4-line',
                    'color' => 'primary',
                    'remote_id' => 'REM-CAT-112',
                ],
            ],
            'articles' => [
                [
                    'category_slug' => 'yapay-zeka-genel-bakis',
                    'title' => '1. Yapay Zeka Asistanı ve Doğal Dil ile Şantiye Risk Sorgulama',
                    'slug' => 'yapay-zeka-asistani-ve-dogal-dil-ile-santiye-sorgulama',
                    'summary' => 'AI Asistanı, Proaktif Öneriler, Risk Analizi, Saha Veri Analitiği, Tahminleme ve Doküman OCR Kullanım Rehberi.',
                    'content' => "Yapay Zeka Asistanı ve Analitik Modülü (ai-module.*) inşaat projenizdeki puantaj, metraj, bütçe ve hava durumu verilerini doğal dil işleme ile analiz eder.\n\n"
                        . "Kapsamdaki Sayfalar ve Kullanım Adımları:\n"
                        . "1. AI Asistan Paneli (ai-module.index): Doğal dille 'Bu ay bütçesini aşan taşeronlar kimler?' veya 'Geciken pozlar nelerdir?' diye sorun.\n"
                        . "2. Proaktif Asistan & Öneriler (ai-module.proactive-assistant): Sistem kritik yol (CPM) darboğazlarını haftalık otomatik raporlar.\n"
                        . "3. AI Risk Analizi (ai-module.risk-analysis): Maliyet aşımı ve süre uzatımı riski taşıyan imalat kalemlerini % skorlama ile izleyin.\n"
                        . "4. Doküman İşleme OCR (ai-module.document-processing): Fatura ve hakediş evrak görsellerini yükleyerek metrajı otomatik sisteme aktarın.\n"
                        . "5. Mevzuat Uyum Raporu (ai-module.compliance-report): İSG ve SGK mevzuatına uyum skorunu tek tıkla denetleyin.",
                    'views' => 4820,
                    'read_time' => 5,
                    'is_popular' => true,
                    'tags' => ['ai', 'yapay-zeka', 'asistan', 'risk', 'ocr'],
                    'author' => 'AI Sistem Mühendisi',
                    'remote_id' => 'REM-ART-401',
                ],
                [
                    'category_slug' => 'portfoy-ihale-fizibilite',
                    'title' => '2. Portföy Yönetimi, Fizibilite Analizi ve Teklif İhale Paneli',
                    'slug' => 'portfoy-yonetimi-fizibilite-analizi-ve-teklif-ihale-paneli',
                    'summary' => 'Çoklu proje konsolidasyonu, yatırım ROI simülasyonu, senaryo karşılaştırma ve ihale teklif toplama.',
                    'content' => "Portföy & İhale Modülü (portfolio.*, feasibility.*, tenders.*) gayrimenkul yatırımlarının başlangıcından ihale sonucuna kadarki tüm finansal kararları yönetir.\n\n"
                        . "Kullanım Adımları:\n"
                        . "1. Portföy Yönetim Paneli (portfolio.management.index): Tüm projelerinizin konsolide ciro, bütçe ve ilerleme grafiklerini izleyin.\n"
                        . "2. Fizibilite Analizi & Senaryolar (feasibility.index): Arsa bedeli, emsal inşaat alanı (KSK/TAK), yapı sınıfı ve ROI geri dönüş sürelerini hesaplayın.\n"
                        . "3. Senaryo Karşılaştırma (feasibility.compare.get): Farklı konut/ticari alan oranlı mimari senaryoları nakit akışı yönünden kıyaslayın.\n"
                        . "4. Teklif & İhale Paneli (portfolio/tenders/bid-management): İhale paketi hazırlayıp tedarikçi teklif matrislerini kapalı zarf usulü karşılaştırın.",
                    'views' => 3950,
                    'read_time' => 4,
                    'is_popular' => false,
                    'tags' => ['portfoy', 'fizibilite', 'ihale', 'teklif', 'roi'],
                    'author' => 'Yatırım Direktörü',
                    'remote_id' => 'REM-ART-402',
                ],
                [
                    'category_slug' => 'crm-satis-yonetimi',
                    'title' => '3. Fırsatlar Kanban, Satış Hedefleri, Opsiyon & Ödeme Senaryoları',
                    'slug' => 'firsatlar-kanban-satis-hedefleri-opsiyon-ve-odeme-senaryolari',
                    'summary' => 'Müşteri aday yönetimi, temsilci kotaları, bağımsız bölüm opsiyonlaması ve esnek taksit kurgusu.',
                    'content' => "CRM & Satış Modülü (opportunities.*, sales-targets.*, options.*, units.*, flexible-payments.*) gayrimenkul stoklarının satış süreçlerini yönetir.\n\n"
                        . "Kullanım Adımları:\n"
                        . "1. Fırsatlar Listesi & Kanban (opportunities.list): Potansiyel müşterileri sürükle-bırak yöntemiyle ilk görüşmeden satış kapatmaya kadar takip edin.\n"
                        . "2. Satış Hedefleri & Performans (sales-targets.index): Satış temsilcilerinin aylık ciro kotalarını ve prim hakedişlerini görün.\n"
                        . "3. Opsiyon Yönetimi (options.index): Süresi dolmak üzere olan opsiyonlu daireler için otomatik hatırlatmalar alın.\n"
                        . "4. Birim (Ünite) & Gayrimenkul Portföyü (units.index): Daire m², cephe, kat no ve liste fiyatlarını yönetin.\n"
                        . "5. Esnek Ödeme Planları (flexible-payments.index): Müşteriye özel peşinat, balon ödeme ve taksit yapılandırma senaryoları kurgulayın.",
                    'views' => 5120,
                    'read_time' => 5,
                    'is_popular' => true,
                    'tags' => ['crm', 'satis', 'kanban', 'opsiyon', 'odeme-plani'],
                    'author' => 'Satış Direktörü',
                    'remote_id' => 'REM-ART-403',
                ],
                [
                    'category_slug' => 'musteri-yonetimi-satis-sonrasi',
                    'title' => '4. Müşteri İlişkileri, Tapu İşlemleri, Taksit & Senet Vade Takvimi',
                    'slug' => 'musteri-iliskileri-tapu-islemleri-taksit-ve-senet-vade-takvimi',
                    'summary' => 'Görüşme kayıtları, mesken ruhsatı, taksit tahsilatı, senet takibi, takas araçları ve KVKK onayları.',
                    'content' => "Müşteri Yönetimi Modülü (customer_meetings.*, property_documents.*, installments.*, promissory_notes.*) satış sonrası tahsilat ve tapu süreçlerini kapsar.\n\n"
                        . "Kullanım Adımları:\n"
                        . "1. Müşteri Görüşmeleri (customer_meetings.index): Görüşme notlarını ve randevu geçmişini kaydedin.\n"
                        . "2. Tapu & Mesken İşlemleri (property_documents.index): Kat irtifakı, tapu devir evrakları ve mesken ruhsat belgelerini yönetin.\n"
                        . "3. Taksit & Senet Vade Takvimi (promissory_notes.timeline): Vadesi gelen ve geciken senet ödemeleri için otomatik ihtarname süreçlerini çalıştırın.\n"
                        . "4. Takas Varlıkları (trade_assets.index): Konut satışında takasa alınan araç veya gayrimenkul ekspertiz değerlerini izleyin.\n"
                        . "5. KVKK Onayları (kvkk-consents.index): Kişisel verilerin işlenmesi rıza formlarını dijital onaylayın.",
                    'views' => 4430,
                    'read_time' => 4,
                    'is_popular' => false,
                    'tags' => ['musteri', 'tapu', 'taksit', 'senet', 'kvkk'],
                    'author' => 'Müşteri İlişkileri Müdürü',
                    'remote_id' => 'REM-ART-404',
                ],
                [
                    'category_slug' => 'proje-santiye-yonetimi',
                    'title' => '5. Proje Yönetimi, GIS Canlı Harita, Doküman Kontrolü & Claims',
                    'slug' => 'proje-yonetimi-gis-canli-harita-dokuman-kontrolu-ve-claims',
                    'summary' => 'Şantiye detayları, harita uydu katmanı, versiyonlu çizim kontrolü, hak talepleri ve ilave emirler.',
                    'content' => "Proje & Şantiye Modülü (projects.*, contracts.*, change_orders.*, closeout.*) sahadaki mühendislik ve sözleşme süreçlerini yönetir.\n\n"
                        . "Kullanım Adımları:\n"
                        . "1. GIS Canlı Harita (projects.map): Proje poligon sınırlarını haritada uydudan izleyin.\n"
                        . "2. Doküman Kontrolü (projects.documents): Revize mimari, statik ve mekanik çizimlerin versiyonlama (R0, R1, R2) takibini yapın.\n"
                        . "3. Proje Planlama (projects.planning): Gantt şeması iş programı bağıntılarını ve kritik yolu (CPM) inceleyin.\n"
                        . "4. Hak Talepleri Claims (contracts.claims): Zemin sürprizi ve hava koşulları kaynaklı ek süre/maliyet hak taleplerini sözleşmeye bağlayın.\n"
                        . "5. Değişiklik Emirleri (change_orders.index): İşverenden ilave imalat onayları alarak bütçeye işleyin.\n"
                        . "6. Proje Kapanış & Garanti (closeout.index): Geçici kabul tutanakları ve 24 aylık garanti bilet yönetimini yürütün.",
                    'views' => 5890,
                    'read_time' => 6,
                    'is_popular' => true,
                    'tags' => ['proje', 'santiye', 'gis', 'harita', 'claims', 'change-order'],
                    'author' => 'Proje Müdürü',
                    'remote_id' => 'REM-ART-405',
                ],
                [
                    'category_slug' => 'saha-operasyonlari-isg-kalite',
                    'title' => '6. Mobil Saha Günlüğü, CCTV, Punch List, İSG & EBİS Beton Testi',
                    'slug' => 'mobil-saha-gunlugu-cctv-punch-list-isg-ve-ebis-beton-testi',
                    'summary' => 'Ustabaşı puantajı, şantiye defteri, IP kamera yayını, eksik listesi, çalışma izinleri ve beton kırım raporları.',
                    'content' => "Saha, İSG & Kalite Modülü (field.operations, site_diaries.*, field.qhse.*, concrete_tests.*) günlük şantiye imalatlarını ve iş güvenliğini denetler.\n\n"
                        . "Kullanım Adımları:\n"
                        . "1. Mobil Saha Günlüğü (site_diaries.index): Sahadan usta sayıları, çalışılan saatler, hava durumu ve fotoğrafları kaydedin.\n"
                        . "2. Canlı CCTV Kameralar (site_cameras.index): Şantiye IP kameralarını canlı izleyin.\n"
                        . "3. Kalite Kontrol Paneli & Punch List (field.qhse.punchlist): Teslim öncesi eksik/hatalı imalatları taşeronlara atayın.\n"
                        . "4. İş Güvenliği & Çalışma İzinleri (field.qhse.safety.permits.index): Yüksekte çalışma izinleri ve İSG olay/kaza kayıtlarını tutun.\n"
                        . "5. EBİS Beton Kırım Testleri (concrete_tests.index): 7 ve 28 günlük silindir/küp beton basınç dayanım sonuçlarını ÇŞB standartlarında denetleyin.",
                    'views' => 6420,
                    'read_time' => 6,
                    'is_popular' => true,
                    'tags' => ['saha', 'puantaj', 'punchlist', 'isg', 'ebis', 'beton'],
                    'author' => 'Şantiye Şefi',
                    'remote_id' => 'REM-ART-406',
                ],
                [
                    'category_slug' => 'taseron-tedarik-zinciri',
                    'title' => '7. Taşeron Dizini, Hakediş Onayı, Yasal Kesintiler & Satınalma',
                    'slug' => 'taseron-dizini-hakedis-onayi-yasal-kesintiler-ve-satinalma',
                    'summary' => 'Taşeron sözleşmeleri, avans/teminat kesintileri, taşeron puanlama, portal, tedarikçiler ve siparişler.',
                    'content' => "Taşeron & Tedarik Modülü (subcontractor.*, supply.*) alt yüklenici ve satın alma tedarik zincirini yönetir.\n\n"
                        . "Kullanım Adımları:\n"
                        . "1. Taşeron Rehberi & Dizin (subcontractor.index): Taşeron vergi levhası ve SGK borcu yoktur belgelerini doğrulayın.\n"
                        . "2. İlerleme & Hakediş Onayı (subcontractor.progress.approval): Dönemsel imalat metrajlarını onaylayıp avans mahsubu (%10) ve teminat kesintisini (%5) düşürün.\n"
                        . "3. Taşeron Puanlama (subcontractor_scores.index): Taşeronların hız, kalite ve İSG performans puanlarını (1-100) verin.\n"
                        . "4. Taşeron Portalı (subcontractor.dashboard): Taşeronların kendi metraj ve usta puantaj taslaklarını sisteme girmelerini sağlayın.\n"
                        . "5. Tedarikçi & Satınalma Siparişleri (supply.procurement.purchase_orders): Malzeme satınalma taleplerini onaylayıp siparişe dönüştürün.",
                    'views' => 4910,
                    'read_time' => 5,
                    'is_popular' => false,
                    'tags' => ['taseron', 'tedarik', 'hakedis', 'satinalma', 'portal'],
                    'author' => 'Satınalma Müdürü',
                    'remote_id' => 'REM-ART-407',
                ],
                [
                    'category_slug' => 'hakedis-finans-muhasebe',
                    'title' => '8. Taşeron Hakediş Cetveli, Cari Hesaplar & GİB E-Fatura Entegrasyonu',
                    'slug' => 'taseron-hakedis-cetveli-cari-hesaplar-ve-gib-efatura-entegrasyonu',
                    'summary' => 'İmalat metrajları, %20 KDV, tevkifat kodu, kasa/banka, çek/senet ve ETTKN kodlu GİB e-faturası.',
                    'content' => "Hakediş, Finans & Muhasebe Modülü (progress-payments.*, finance.*, currency-exchanges.*) tüm parasal hareketlerin merkezidir.\n\n"
                        . "Kullanım Adımları:\n"
                        . "1. Hakediş Oluşturma (progress-payments.create): İmalat birim fiyatı, ihzarat ve metrajları girerek KDV matrahını denetleyin.\n"
                        . "2. Cari Hesaplar & Kasa/Banka (finance/accounts/customers, finance/cash/accounts): Müşteri/taşeron borç-alacak ekstrelerini ve banka hareketlerini izleyin.\n"
                        . "3. GİB E-Fatura Entegrasyonu (finance/edoc/efatura): VKN doğrulamasının ardından GİB portalına bağlı ETTKN kodlu resmi e-fatura kesin.\n"
                        . "4. Nakit Akış Tahmini (finance.forecast.dashboard): Gelecek 12 aylık devir bakiyesini ve döviz kur riski simülasyonunu inceleyin.",
                    'views' => 7150,
                    'read_time' => 6,
                    'is_popular' => true,
                    'tags' => ['hakedis', 'finans', 'muhasebe', 'efatura', 'gib', 'nakit-akis'],
                    'author' => 'Mali İşler Direktörü',
                    'remote_id' => 'REM-ART-408',
                ],
                [
                    'category_slug' => 'kaynaklar-ik-tesis-yonetimi',
                    'title' => '9. İK Özlük Dosyaları, Stok Kartları, İş Makinesi Telemetri & QR Kod',
                    'slug' => 'ik-ozluk-dosyalari-stok-kartlari-is-makinesi-telemetri-ve-qr-kod',
                    'summary' => 'Personel bordrosu, ambar irsaliye giriş-çıkışları, canlı GPS iş makinesi parkı, QR kod etiketleme.',
                    'content' => "Kaynaklar & Tesis Modülü (resources.hr.*, hr_personnels.*, purchasing.*, facility.*, fleet_telemetries.*) personel, stok ve varlık yönetimini yürütür.\n\n"
                        . "Kullanım Adımları:\n"
                        . "1. İK Özlük & Bordro (resources.hr.employees): Personel sözleşmeleri, zimmetler, masraflar ve aylık bordroları yönetin.\n"
                        . "2. Stok Kartları & Ambar (finance/stock/cards): Malzeme envanter kodlarını açın; Ambar Giriş/Çıkış İrsaliye Fişleri ile zimmetleyin.\n"
                        . "3. Ekipman Parkı & Telemetri (fleet_telemetries.index): İş makinelerinin canlı GPS konumlarını ve çalışma saatlerini haritada takip edin.\n"
                        . "4. QR Kod Yönetimi & Tesis (facility.qr.index): Jeneratör ve HVAC iklimlendirme sistemlerine özel QR kod etiketleri basıp bakım geçmişini okutun.",
                    'views' => 4320,
                    'read_time' => 5,
                    'is_popular' => false,
                    'tags' => ['ik', 'bordro', 'stok', 'ambar', 'gps', 'telemetri', 'qr'],
                    'author' => 'Tesis ve İK Yöneticisi',
                    'remote_id' => 'REM-ART-409',
                ],
                [
                    'category_slug' => 'tr-insaat-mevzuati-yasal-uyum',
                    'title' => '10. SGK Asgari İşçilik, Kat Karşılığı Matrisi, ÇŞB Pozlar & Yİ-ÜFE Fiyat Farkı',
                    'slug' => 'sgk-asgari-iscilik-kat-karsiligi-matrisi-csb-pozlar-ve-fiyat-farki',
                    'summary' => 'SGK ilişiksizlik primi, arsa sahibi sözleşme matrisi, resmi ÇŞB poz fiyatları ve KİK enflasyon fiyat farkı.',
                    'content' => "Türkiye İnşaat Mevzuatı Modülü (sgk.*, land_owner_contracts.*, csb_poz.*, price_differences.*, ewaybills.*, vat_returns.*) yasal mevzuat uyumunu denetler.\n\n"
                        . "Kullanım Adımları:\n"
                        . "1. SGK Asgari İşçilik (sgk.index): Bina maliyet sınıfı uyarınca SGK'ya bildirilmesi gereken minimum prim tutarını ve eksik bildirimi hesaplayın.\n"
                        . "2. Kat Karşılığı Matrisi & AI (land_owner_contracts.index): Arsa sahibi ve müteahhit paylaşım oranlarını daire bazında matrise işleyin.\n"
                        . "3. ÇŞB Poz Kütüphanesi (csb_poz.index): Çevre ve Şehircilik Bakanlığı güncel birim fiyat pozlarını arayın.\n"
                        . "4. Fiyat Farkı Yİ-ÜFE (price_differences.index): KİK Madde 11 uyarınca resmi enflasyon fiyat farkı katsayısını hesaplatın.\n"
                        . "5. MoTAT Şantiye Atık (waste_managements.index): Tehlikeli atık ve hafriyat döküm formlarını yönetin.",
                    'views' => 5670,
                    'read_time' => 5,
                    'is_popular' => true,
                    'tags' => ['sgk', 'asgari-iscilik', 'kat-karsiligi', 'csb', 'poz', 'fiyat-farki'],
                    'author' => 'Mevzuat ve Hukuk Danışmanı',
                    'remote_id' => 'REM-ART-410',
                ],
                [
                    'category_slug' => 'is-zekasi-raporlar-calisma-alani',
                    'title' => '11. Executive BI Dashboard, Risk Haritası, Dinamik Raporlar & Kanban',
                    'slug' => 'executive-bi-dashboard-risk-haritasi-dinamik-raporlar-ve-kanban',
                    'summary' => 'Üst yönetim KPI panoları, risk analizleri, dinamik PDF/Excel rapor mimarı, görevler ve takvim.',
                    'content' => "İş Zekası & Raporlar Modülü (executive_dashboards.*, analytics.*, reports.*, advanced-reports.*, tasks.*) karar destek sistemidir.\n\n"
                        . "Kullanım Adımları:\n"
                        . "1. Executive Dashboard BI (executive_dashboards.index): Şirketinizin toplam portföy değerini, nakit dengesini ve şantiye ilerleme KPI'larını izleyin.\n"
                        . "2. Risk Haritası (analytics.risk-map): Şantiyelerdeki gecikme ve maliyet aşım risklerini coğrafi haritada görselleştirin.\n"
                        . "3. Dinamik Rapor Oluşturucu (dynamic_reports.index): Özel sütun ve filtreler belirleyerek Excel/PDF raporları çıktı alın.\n"
                        . "4. Görev Yönetimi & Kanban (tasks.index, apps-kanban): Şantiye ekibinize iş atayıp tamamlanma sürelerini izleyin.",
                    'views' => 5210,
                    'read_time' => 5,
                    'is_popular' => true,
                    'tags' => ['bi', 'dashboard', 'risk', 'rapor', 'kanban', 'is-zekasi'],
                    'author' => 'İş Zekası Mimarı',
                    'remote_id' => 'REM-ART-411',
                ],
                [
                    'category_slug' => 'platform-yonetimi-destek',
                    'title' => '12. Kullanıcı Roller, Şirket Ayarları, Excel Import/Export & Audit Log',
                    'slug' => 'kullanici-roller-sirket-ayarlari-excel-import-export-ve-audit-log',
                    'summary' => 'Erişim yetki matrisi, lisans yönetimi, toplu Excel aktarımları, işlem zaman damgası denetimi.',
                    'content' => "Platform Yönetimi Modülü (admin.*, activity-logs.*, data-import-export.*, help.*) sistem konfigürasyonu ve güvenliği sağlar.\n\n"
                        . "Kullanım Adımları:\n"
                        . "1. Kullanıcılar & Roller (admin.users.index): Şantiye şefleri, muhasebe ve satış temsilcileri için modül bazlı erişim yetkileri tanımlayın.\n"
                        . "2. Veri İçe/Dışa Aktarma (data-import-export.index): Excel dosyaları ile toplu stok kartı, cari liste ve metraj yüklemesi yapın.\n"
                        . "3. Aktivite Günlüğü & Audit Trail (activity-logs.index): Sistemde yapılan tüm veri silme ve güncelleme işlemlerini zaman damgasıyla denetleyin.\n"
                        . "4. Canlı Destek & Bilet (help.support): İhtiyaç duyduğunuz her an teknik ekibe doğrudan destek bileti açın.",
                    'views' => 4180,
                    'read_time' => 4,
                    'is_popular' => false,
                    'tags' => ['admin', 'roller', 'lisans', 'import', 'export', 'audit', 'destek'],
                    'author' => 'Sistem Yöneticisi',
                    'remote_id' => 'REM-ART-412',
                ],
            ],
        ];
    }
}
