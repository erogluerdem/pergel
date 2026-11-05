<?php

namespace App\Http\Controllers;

use App\Models\HelpCategory;
use App\Models\HelpArticle;
use App\Models\HelpFaq;
use App\Models\HelpVideoTutorial;
use App\Services\HelpCenterSyncService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HelpController extends Controller
{
    protected HelpCenterSyncService $syncService;

    public function __construct(HelpCenterSyncService $syncService)
    {
        $this->syncService = $syncService;
    }

    /**
     * Display the help center index powered by SQL Database.
     */
    public function index(): View
    {
        $categories = HelpCategory::withCount('articles')
            ->orderBy('sort_order')
            ->get();

        $popularArticles = HelpArticle::with('category')
            ->orderByDesc('views')
            ->take(5)
            ->get()
            ->map(function($art) {
                return (object)[
                    'id' => $art->id,
                    'title' => $art->title,
                    'category' => $art->category->name ?? 'Genel',
                    'category_color' => $art->category->color ?? 'primary',
                    'summary' => $art->summary,
                    'views' => $art->views,
                    'read_time' => $art->read_time,
                ];
            });

        $lastSyncedArticle = HelpArticle::latest('last_synced_at')->first();
        $recentUpdates = [
            (object)[
                'title' => 'SQL Veritabanı & Canlı Merkez Senkronizasyonu Devrede',
                'description' => 'Tüm yardım konuları SQL veritabanından çekilmekte olup merkez sunucudan anlık güncellenebilir.',
                'date' => $lastSyncedArticle?->last_synced_at ? $lastSyncedArticle->last_synced_at->format('d.m.Y H:i') : now()->format('d.m.Y')
            ],
            (object)[
                'title' => 'Uçtan Uca 10 Aşamalı Kullanım Kılavuzu Yayında',
                'description' => 'Şantiye kurulumundan geçici kabule tüm süreçler için adım adım rehber eklendi.',
                'date' => '13 Ağustos 2026'
            ],
            (object)[
                'title' => 'Leaflet.js Canlı Harita Entegrasyonu Rehberi Eklendi',
                'description' => 'Tesis ve iş makinesi parkında anlık enlem/boylam GPS harita takibi rehberi entegre edildi.',
                'date' => '12 Ağustos 2026'
            ]
        ];

        $stats = (object)[
            'total_articles' => HelpArticle::count(),
            'total_categories' => HelpCategory::count(),
            'solved_tickets' => 312,
            'avg_response_time' => 1.8
        ];

        return view('help.index', compact('categories', 'popularArticles', 'recentUpdates', 'stats'));
    }

    /**
     * Web Button Action: Trigger SQL DB Sync from Central Remote Server
     */
    public function syncRemote(Request $request)
    {
        $result = $this->syncService->syncFromRemoteServer();

        return back()->with('success', "Merkez sunucudan SQL veritabanı senkronize edildi! [{$result['synced_categories']} yeni/güncel kategori, {$result['synced_articles']} makale güncellendi]");
    }

    /**
     * Display the comprehensive step-by-step help guide.
     */
    public function guide(): View
    {
        $guideSteps = [
            (object)[
                'step' => 1,
                'title' => '1. Yapay Zeka & Genel Bakış',
                'category' => 'Yapay Zeka & Analitik',
                'icon' => 'ri-robot-line',
                'color' => 'indigo',
                'url' => route('ai-module.index'),
                'description' => 'Yapay zeka asistanı, proaktif risk önerileri, saha veri analitiği, tahminleme modelleri, doküman OCR işleme, mevzuat uyumu ve genel arama.',
                'sub_modules' => [
                    ['name' => 'AI Asistan Paneli', 'url' => route('ai-module.index')],
                    ['name' => 'Proaktif Asistan & Öneriler', 'url' => route('ai-module.proactive-assistant')],
                    ['name' => 'AI Risk Analizi', 'url' => route('ai-module.risk-analysis')],
                    ['name' => 'Saha Veri Analizi', 'url' => route('ai-module.site-data-analysis')],
                    ['name' => 'Tahminleme & Analitik', 'url' => route('ai-module.predictive-analytics')],
                    ['name' => 'Doküman İşleme (OCR)', 'url' => route('ai-module.document-processing')],
                    ['name' => 'Mevzuat Uyum Raporu', 'url' => route('ai-module.compliance-report')],
                    ['name' => 'Genel Bakış (Dashboard)', 'url' => route('root')],
                    ['name' => 'Genel Arama Engine', 'url' => route('search.index')],
                ],
                'details' => [
                    'Yapay Zeka Asistanı ile projelerinizdeki bütçe sapmaları ve kritik yol gecikme risklerini anlık doğal dille sorgulayın.',
                    'AI Risk Analizi modülünde maliyet aşımı riski taşıyan poz ve taşeron hakedişlerini önceden tespit edin.',
                    'Doküman İşleme ile hakediş ve ihale evraklarını taratarak metraj verisini otomatik sisteme aktarın.'
                ],
                'pro_tip' => 'AI Asistanı projenize özel haftalık proaktif uyarılar üreterek kritik yol gecikmelerini önler.',
                'warning' => 'AI tahminlemelerinin doğruluğu şantiyeden girilen puantaj ve hakediş verilerinin güncelliğine bağlıdır.'
            ],
            (object)[
                'step' => 2,
                'title' => '2. Portföy, İhale & Fizibilite',
                'category' => 'Portföy & İhale',
                'icon' => 'ri-pie-chart-line',
                'color' => 'primary',
                'url' => route('portfolio.management.index'),
                'description' => 'Gayrimenkul ve inşaat portföyleri, fizibilite analizleri, senaryo kıyaslama ve ihale teklif toplama süreçleri.',
                'sub_modules' => [
                    ['name' => 'Portföy Yönetim Paneli', 'url' => route('portfolio.management.index')],
                    ['name' => 'Tüm Projeler Liste', 'url' => route('projects.index')],
                    ['name' => 'Program Yönetimi', 'url' => route('portfolio.program-management.index')],
                    ['name' => 'Portföy Finansal Özet', 'url' => route('portfolio.financials.index')],
                    ['name' => 'Fizibilite Analizi', 'url' => route('feasibility.index')],
                    ['name' => 'Senaryo Karşılaştırma', 'url' => route('feasibility.compare.get')],
                    ['name' => 'Teklif & İhale Paneli', 'url' => route('index', ['any' => 'portfolio/tenders/bid-management'])],
                    ['name' => 'İhale Paketi Hazırlama', 'url' => route('tenders.package.prepare')],
                ],
                'details' => [
                    'Portföy Yönetim ekranından tüm projelerin finansal sağlık ve ilerleme durumlarını konsolide izleyin.',
                    'Fizibilite Analizi modülünde arsa maliyeti, inşaat maliyet katsayıları ve ROI sürelerini simüle edin.',
                    'Teklif & İhale modülünde tedarikçi teklif matrislerini kıyaslayarak en uygun ihale paketini oluşturun.'
                ],
                'pro_tip' => 'İhale teklif matrisinde nakliye ve pompa bedellerinin dahil olup olmadığını kontrol ederek net kıyaslama yapın.',
                'warning' => 'Fizibilite onaylanmadan projeye bağlı harcama ve ihale bütçesi açılamaz.'
            ],
            (object)[
                'step' => 3,
                'title' => '3. CRM & Satış Yönetimi',
                'category' => 'Satış & CRM',
                'icon' => 'ri-shopping-bag-3-line',
                'color' => 'warning',
                'url' => route('opportunities.list'),
                'description' => 'Potansiyel proje fırsatları, satış hedefleri, opsiyon takibi, gayrimenkul üniteleri, esnek ödeme planları ve dijital sözleşmeler.',
                'sub_modules' => [
                    ['name' => 'Fırsatlar Listesi & Kanban', 'url' => route('opportunities.list')],
                    ['name' => 'Satış Hedefleri & Dashboard', 'url' => route('sales-targets.index')],
                    ['name' => 'Satış Performansı', 'url' => route('sales_performance.index')],
                    ['name' => 'Satış & Rezervasyon', 'url' => route('sales-reservations.index')],
                    ['name' => 'Opsiyon Yönetimi', 'url' => route('options.index')],
                    ['name' => 'Birim (Ünite) Yönetimi', 'url' => route('units.index')],
                    ['name' => 'Gayrimenkul Portföyü', 'url' => route('operations.properties.index')],
                    ['name' => 'Esnek Ödeme Planları & Senaryo', 'url' => route('flexible-payments.index')],
                    ['name' => 'Dijital Sözleşmeler', 'url' => route('digital-contracts.index')],
                ],
                'details' => [
                    'Fırsatlar Kanban Panosu üzerinde potansiyel müşterileri sürükle-bırak yöntemiyle aşamalandırın.',
                    'Opsiyon Yönetimi ile süresi dolmak üzere olan daire/dükkan rezervasyonları için otomatik uyarılar alın.',
                    'Esnek Ödeme Planı Senaryo Oluşturucu ile müşteriye özel peşinat, taksit ve balon ödeme planları kurgulayın.'
                ],
                'pro_tip' => 'Opsiyon süresi dolmadan müşteriyle dijital satış sözleşmesini bağlayarak stoku kilitli tutun.',
                'warning' => 'Süresi dolan opsiyonlar onay verilmezse sistem tarafından otomatik olarak satışa açılır.'
            ],
            (object)[
                'step' => 4,
                'title' => '4. Müşteri Yönetimi & Satış Sonrası',
                'category' => 'Müşteri İlişkileri',
                'icon' => 'ri-heart-pulse-line',
                'color' => 'success',
                'url' => route('customer_meetings.index'),
                'description' => 'Müşteri görüşmeleri, tapu & mesken ruhsat işlemleri, taksit ve senet takvimi, takas varlıkları ve KVKK onayları.',
                'sub_modules' => [
                    ['name' => 'Müşteri Görüşmeleri', 'url' => route('customer_meetings.index')],
                    ['name' => 'Tapu & Mesken İşlemleri', 'url' => route('property_documents.index')],
                    ['name' => 'Ödeme Planları', 'url' => route('payment-plans.index')],
                    ['name' => 'Taksit Takibi & Durum Raporu', 'url' => route('installments.index')],
                    ['name' => 'Senetli Satış Takibi & Vade Takvimi', 'url' => route('promissory_notes.index')],
                    ['name' => 'Takas Varlıkları', 'url' => route('trade_assets.index')],
                    ['name' => 'Danışman Değişimleri', 'url' => route('consultant-changes.index')],
                    ['name' => 'KVKK Onayları', 'url' => route('kvkk-consents.index')],
                ],
                'details' => [
                    'Müşteri Görüşmeleri sayfasından tüm satış temsilcilerinin iletişim geçmişini kaydedin.',
                    'Taksit ve Senet Takvimi ekranından geciken ödemeler için otomatik SMS/E-posta ihtarname süreçlerini çalıştırın.',
                    'Takas Varlıkları modülünde konut alımında alınan araç veya gayrimenkul ekspertiz değerlerini izleyin.'
                ],
                'pro_tip' => 'Erken senet ödemelerinde iskonto oranını taksit cetvelinden otomatik hesaplatın.',
                'warning' => 'KVKK onayı alınmayan müşterilere pazarlama iletişimi gönderilmesi yasal engel teşkil eder.'
            ],
            (object)[
                'step' => 5,
                'title' => '5. Proje & Şantiye Yönetimi',
                'category' => 'Proje & Şantiye',
                'icon' => 'ri-building-2-line',
                'color' => 'info',
                'url' => route('projects.index'),
                'description' => 'Şantiye proje yönetimi, GIS canlı harita, doküman kontrolü, iş programı planlama, iletişim, proje kapanış, sözleşmeler ve hak talepleri (Claims).',
                'sub_modules' => [
                    ['name' => 'Tüm Projeler (Şantiyeler)', 'url' => route('projects.index')],
                    ['name' => 'GIS Canlı Harita', 'url' => route('projects.map')],
                    ['name' => 'Doküman Kontrolü', 'url' => route('index', ['any' => 'projects/documents'])],
                    ['name' => 'Proje Planlama', 'url' => route('index', ['any' => 'projects/planning'])],
                    ['name' => 'Proje İletişim Panosu', 'url' => route('projects.communication.dashboard')],
                    ['name' => 'Proje Kapanış & Garanti', 'url' => route('closeout.index')],
                    ['name' => 'Müteahhit Sözleşmeleri', 'url' => route('index', ['any' => 'contracts/management'])],
                    ['name' => 'Hak Talepleri (Claims)', 'url' => route('contracts.claims')],
                    ['name' => 'Sözleşme Kütüphanesi', 'url' => route('contracts.library.index')],
                    ['name' => 'Değişiklik Emirleri (Change Orders)', 'url' => route('change_orders.index')],
                ],
                'details' => [
                    'GIS Canlı Harita üzerinden projelerinizin şantiye sınırlarını ve saha lokasyonlarını haritada izleyin.',
                    'Doküman Kontrolü modülü ile revize mimari ve statik çizimlerin versiyon takibini yapın.',
                    'Hak Talepleri (Claims) ekranından zemin ve gecikme kaynaklı ilave maliyet taleplerini sözleşmeye bağlayın.'
                ],
                'pro_tip' => 'Değişiklik Emirlerini (Change Order) işverenden onay almadan imalata geçirmeyin.',
                'warning' => 'Proje kapanışında geçici kabul tutanağı onaylanmadan teminat mektupları serbest bırakılamaz.'
            ],
            (object)[
                'step' => 6,
                'title' => '6. Saha Operasyonları, İSG & Kalite',
                'category' => 'Saha, İSG & QHSE',
                'icon' => 'ri-shield-user-line',
                'color' => 'danger',
                'url' => route('field.operations'),
                'description' => 'Mobil saha günlükleri, şantiye defterleri, canlı CCTV kameralar, kalite kontrol, saha eksik listesi (Punchlist), İSG kaza/olay kayıtları ve EBİS beton testleri.',
                'sub_modules' => [
                    ['name' => 'Saha Operasyonları', 'url' => route('index', ['any' => 'field/operations'])],
                    ['name' => 'Mobil Saha Günlüğü', 'url' => route('site_diaries.index')],
                    ['name' => 'Şantiye Günlük Defteri', 'url' => route('site_daily_logs.index')],
                    ['name' => 'Canlı CCTV Kameralar', 'url' => route('site_cameras.index')],
                    ['name' => 'Kalite Kontrol Paneli', 'url' => route('index', ['any' => 'field/qhse/quality'])],
                    ['name' => 'Saha Eksik Listesi (Punch List)', 'url' => route('field.qhse.punchlist')],
                    ['name' => 'İş Güvenliği (İSG) Genel', 'url' => route('index', ['any' => 'field/qhse/safety'])],
                    ['name' => 'İSG Olay & Kaza Kayıtları', 'url' => route('field.qhse.safety.incidents.index')],
                    ['name' => 'Çalışma İzinleri (Permits)', 'url' => route('field.qhse.safety.permits.index')],
                    ['name' => 'İSG Bilgilendirme (Toolbox)', 'url' => route('field.qhse.safety.toolbox.index')],
                    ['name' => 'Taşeron İSG Kapısı & İşçiler', 'url' => route('subcontractor_workers.index')],
                    ['name' => 'EBİS Beton Kırım Testleri', 'url' => route('concrete_tests.index')],
                ],
                'details' => [
                    'Mobil Saha Günlüğü ile şantiyeden doğrudan usta sayısı, kullanılan malzeme ve hava durumunu fotoğraflarla kaydedin.',
                    'EBİS Beton Kırım Testleri sayfasından 7 ve 28 günlük basınç dayanımı sonuçlarını ÇŞB standartlarında denetleyin.',
                    'Punch List (Saha Eksik Listesi) ile teslim öncesi hatalı imalatları taşeronlara atayın.'
                ],
                'pro_tip' => 'Yüksekte çalışma öncesi Çalışma İzni (Permit to Work) almayan ekiplerin sahaya girişini durdurun.',
                'warning' => 'EBİS beton testi olumsuz çıkan dökümlerde karot testi istenmesi yasal zorunluluktur.'
            ],
            (object)[
                'step' => 7,
                'title' => '7. Taşeron & Tedarik Zinciri',
                'category' => 'Taşeron & Tedarik',
                'icon' => 'ri-truck-line',
                'color' => 'teal',
                'url' => route('subcontractor.index'),
                'description' => 'Taşeron firma rehberi, taşeron sözleşmeleri, hakediş onayları, yasal kesintiler, performans puanlama, taşeron portalı, tedarikçiler ve satınalma siparişleri.',
                'sub_modules' => [
                    ['name' => 'Taşeron Rehberi & Dizin', 'url' => route('subcontractor.index')],
                    ['name' => 'Taşeron Sözleşmeleri', 'url' => route('subcontractor.contracts.index')],
                    ['name' => 'İlerleme & Hakediş Onayı', 'url' => route('subcontractor.progress.approval')],
                    ['name' => 'Taşeron Yasal Kesintiler', 'url' => route('subcontractor_deductions.index')],
                    ['name' => 'Taşeron Puanlama (Scoring)', 'url' => route('subcontractor_scores.index')],
                    ['name' => 'Taşeron Portalı', 'url' => route('subcontractor.dashboard')],
                    ['name' => 'Tedarikçi Yönetimi (Vendors)', 'url' => route('supply.vendors')],
                    ['name' => 'Satınalma Talepleri (Requisitions)', 'url' => route('supply.procurement.requisitions')],
                    ['name' => 'Satınalma Siparişleri & Faturaları', 'url' => route('supply.procurement.purchase_orders')],
                ],
                'details' => [
                    'Taşeron Rehberi üzerinden taşeron firmanın SGK borcu yoktur yazısını ve vergi levhasını doğrulayın.',
                    'Taşeron Hakediş Onayı ekranından metraj kontrolü yapıp stopaj ve ceza kesintilerini düşerek hakedişi onaylayın.',
                    'Taşeron Portalı üzerinden taşeronların kendi metraj ve işçi puantaj taslaklarını girmelerini sağlayın.'
                ],
                'pro_tip' => 'Taşeron puanlama sisteminde 70 puanın altında kalan firmaları yeni ihalelere davet etmeyin.',
                'warning' => 'SGK borcu yoktur belgesi sunmayan taşeronlara hakediş ödemesi yapılması müteselsil sorumluluk doğurur.'
            ],
            (object)[
                'step' => 8,
                'title' => '8. Hakediş, Finans & Muhasebe',
                'category' => 'Hakediş & Finans',
                'icon' => 'ri-calculator-line',
                'color' => 'indigo',
                'url' => route('progress-payments.index'),
                'description' => 'Hakediş cetvelleri, cari hesaplar, kasa/banka, mahsup fişleri, POS, çek/senetler, genel giderler, e-fatura mühürleme ve nakit akış tahminleri.',
                'sub_modules' => [
                    ['name' => 'Hakediş Listesi', 'url' => route('progress-payments.index')],
                    ['name' => 'Yeni Hakediş Oluştur', 'url' => route('progress-payments.create')],
                    ['name' => 'Hakediş Raporları', 'url' => route('progress-payments.reports')],
                    ['name' => 'Finans Panosu', 'url' => route('index', ['any' => 'finance/dashboard'])],
                    ['name' => 'Cari Hesaplar (Müşteri & Tedarikçi)', 'url' => route('index', ['any' => 'finance/accounts/customers'])],
                    ['name' => 'Kasa & Banka Hesapları', 'url' => route('index', ['any' => 'finance/cash/accounts'])],
                    ['name' => 'Mahsup & Fiş İşlemleri', 'url' => route('finance.accounts.vouchers.list')],
                    ['name' => 'POS İşlemleri', 'url' => route('index', ['any' => 'finance/cash/pos'])],
                    ['name' => 'Çek & Senetler', 'url' => route('index', ['any' => 'finance/cheques/customers'])],
                    ['name' => 'Genel Giderler & Kategoriler', 'url' => route('index', ['any' => 'finance/expenses/general'])],
                    ['name' => 'Satış Faturaları', 'url' => route('finance.invoices.index')],
                    ['name' => 'Şirket Ortakları', 'url' => route('partners.index')],
                    ['name' => 'Nakit Akış Tahmini', 'url' => route('finance.forecast.dashboard')],
                    ['name' => 'Finansal Risk Yönetimi', 'url' => route('finance.risks.dashboard')],
                    ['name' => 'Finansal Trendler & Analiz', 'url' => route('finance.trends.dashboard')],
                    ['name' => 'Döviz İşlemleri & Kurlar', 'url' => route('currency-exchanges.index')],
                    ['name' => 'E-Dönüşüm (E-Fatura/E-İrsaliye)', 'url' => route('index', ['any' => 'finance/edoc/efatura'])],
                ],
                'details' => [
                    'Hakediş Oluşturma ekranından imalat birim fiyatlarını ve metrajları girip %20 KDV matrahını hesaplatın.',
                    'E-Dönüşüm modülü üzerinden GİB portalı ile anlık entegre e-fatura ve e-irsaliye mühürleyin.',
                    'Nakit Akış Tahmini ekranından gelecek 12 aylık gelir/gider dengesini ve fon ihtiyacını öngörün.'
                ],
                'pro_tip' => 'KDV tevkifatlı faturalarda (Örn: 4/10 Yapım İşleri) tevkifat kodunu faturaya ekleyin.',
                'warning' => 'Resmileşen GİB E-Faturaları ETTKN kodu aldıktan sonra doğrudan silinemez, iptal süreci gerekir.'
            ],
            (object)[
                'step' => 9,
                'title' => '9. Kaynaklar, İK & Tesis Yönetimi',
                'category' => 'İK, Stok & Tesis',
                'icon' => 'ri-community-line',
                'color' => 'purple',
                'url' => route('resources.hr.dashboard'),
                'description' => 'Personel özlük dosyaları, iş gücü planlama, satınalma onayları, stok kartları ve ambar hareketleri, tesis varlıkları, ekipman parkı ve telemetri.',
                'sub_modules' => [
                    ['name' => 'İK Panosu & Personel Listesi', 'url' => route('resources.hr.dashboard')],
                    ['name' => 'İş Gücü Planlaması', 'url' => route('resources.hr.workforce_planning')],
                    ['name' => 'Sertifikalar & Özlük Dosyaları', 'url' => route('resources.hr.certificates')],
                    ['name' => 'Personel Masrafları & Avans & Bordro', 'url' => route('index', ['any' => 'finance/hr/expenses'])],
                    ['name' => 'Teklif & Satınalma', 'url' => route('purchasing.quotations.index')],
                    ['name' => 'Satınalma Onay Süreçleri & Bütçeler', 'url' => route('purchasing.approvals.pending')],
                    ['name' => 'Stok Kartları & Hareketleri', 'url' => route('index', ['any' => 'finance/stock/cards'])],
                    ['name' => 'Tesis Panosu & Varlıkları', 'url' => route('facility.index')],
                    ['name' => 'Ekipman Parkı & Telemetri', 'url' => route('resources.equipment.dashboard')],
                    ['name' => 'Duran Varlıklar & Amortisman', 'url' => route('index', ['any' => 'finance/assets/list'])],
                    ['name' => 'Bakım & Onarım', 'url' => route('facility.maintenance')],
                    ['name' => 'QR Kod Yönetimi', 'url' => route('facility.qr.index')],
                    ['name' => 'Hizmet Seviyesi (SLA)', 'url' => route('facility.sla.index')],
                ],
                'details' => [
                    'Stok Kartları sayfasından çimento ve demir ambar giriş-çıkış irsaliye fişlerini düzenleyin.',
                    'Ekipman Parkı & Telemetri sayfasından iş makinelerinin GPS konumunu ve çalışma saatlerini takip edin.',
                    'QR Kod Yönetimi ile tesis ekipmanlarına özel karekod etiketleri üreterek bakım geçmişini okutun.'
                ],
                'pro_tip' => 'Ambar stok seviyesi kritik miktar altına düştüğünde otomatik satınalma talebi tetikleyin.',
                'warning' => 'Zimmet imzası alınmadan depodan çıkarılan varlıklarda ambar sorumlusu mesuldür.'
            ],
            (object)[
                'step' => 10,
                'title' => '10. TR İnşaat Mevzuatı & Yasal Uyum',
                'category' => 'TR Mevzuat & Uyum',
                'icon' => 'ri-government-line',
                'color' => 'secondary',
                'url' => route('sgk.index'),
                'description' => 'SGK asgari işçilik oranları, kat karşılığı inşaat sözleşmeleri matrisi, ÇŞB birim fiyat poz kütüphanesi, Yİ-ÜFE fiyat farkı, Yapı Denetim, KDV iade ve MoTAT.',
                'sub_modules' => [
                    ['name' => 'SGK Asgari İşçilik', 'url' => route('sgk.index')],
                    ['name' => 'Kat Karşılığı Matrisi & AI Analiz', 'url' => route('land_owner_contracts.index')],
                    ['name' => 'ÇŞB Poz Kütüphanesi', 'url' => route('csb_poz.index')],
                    ['name' => 'Fiyat Farkı (Yİ-ÜFE)', 'url' => route('price_differences.index')],
                    ['name' => 'E-İrsaliye Entegrasyonu', 'url' => route('ewaybills.index')],
                    ['name' => 'Yapı Denetim Takibi', 'url' => route('building_inspections.index')],
                    ['name' => 'KDV İade (YMM)', 'url' => route('vat_returns.index')],
                    ['name' => 'SPK Gayrimenkul Değerleme', 'url' => route('real_estate_valuations.index')],
                    ['name' => 'Bütçe ve Kur Simülatörü', 'url' => route('budget_simulators.index')],
                    ['name' => 'AI Puantaj Analizörü', 'url' => route('ai_timesheet_analyzers.index')],
                    ['name' => 'Şantiye Atık (MoTAT)', 'url' => route('waste_managements.index')],
                    ['name' => 'Cari E-Mutabakat (BA/BS)', 'url' => route('e_agreements.index')],
                ],
                'details' => [
                    'SGK Asgari İşçilik modülünde bina maliyet katsayısına göre bildirilmesi gereken minimum işçilik tutarını hesaplayın.',
                    'Fiyat Farkı (Yİ-ÜFE) modülü ile resmi Kamu İhale Kanunu Madde 11 uyarınca enflasyon fiyat farkı katsayısını hesaplatın.',
                    'ÇŞB Poz Kütüphanesi üzerinden Çevre ve Şehircilik Bakanlığı poz tanımlarını ve güncel birim fiyatlarını arayın.'
                ],
                'pro_tip' => 'SGK ilişiksizlik belgesi başvurusu öncesi asgari işçilik bildirim farkını otomatik denetleyin.',
                'warning' => 'MoTAT şantiye tehlikeli atık formları güncellenmeden hafriyat döküm izni alınamaz.'
            ],
            (object)[
                'step' => 11,
                'title' => '11. İş Zekası, Raporlar & Çalışma Alanı',
                'category' => 'İş Zekası & Raporlar',
                'icon' => 'ri-bar-chart-2-line',
                'color' => 'dark',
                'url' => route('executive_dashboards.index'),
                'description' => 'Executive BI üst yönetim panoları, risk haritası, proje maliyet/gelir analitikleri, danışman performansı, dinamik rapor oluşturucu, görevler, kanban ve takvim.',
                'sub_modules' => [
                    ['name' => 'Executive Dashboard (BI)', 'url' => route('executive_dashboards.index')],
                    ['name' => 'Analitik Panosu & Risk Haritası', 'url' => route('analytics.index')],
                    ['name' => 'Proje Analizi & Gelir/Maliyet', 'url' => route('reports.projects.financial_analysis')],
                    ['name' => 'Satış & Muhasebe Raporları', 'url' => route('reports.sales.accounting_summary')],
                    ['name' => 'KPI & Genel Raporlar', 'url' => route('reports.general.kpi_dashboard')],
                    ['name' => 'Gelişmiş Raporlar & Danışman Performansı', 'url' => route('advanced-reports.index')],
                    ['name' => 'Dinamik Rapor Oluşturucu', 'url' => route('dynamic_reports.index')],
                    ['name' => 'Görev Yönetimi & Kanban', 'url' => route('tasks.index')],
                    ['name' => 'Çalışma Notları', 'url' => route('notes.index')],
                    ['name' => 'Etkinlik Takvimi', 'url' => route('calendar.index')],
                    ['name' => 'İç İletişim / Mesajlar', 'url' => route('messages.index')],
                    ['name' => 'Fotoğraf & Galeri', 'url' => route('gallery.index')],
                ],
                'details' => [
                    'Executive Dashboard üzerinden şirketinizin toplam portföy değerini, nakit dengesini ve şantiye ilerleme KPI değerlerini anlık takip edin.',
                    'Dinamik Rapor Oluşturucu ile özel sütun ve filtreler belirleyerek Excel/PDF raporları çıktı alın.',
                    'Görev Yönetimi ve Kanban panoları ile şantiye ekibinize iş atayıp tamamlanma sürelerini izleyin.'
                ],
                'pro_tip' => 'Üst yönetim için haftalık otomatik e-posta KPI rapor gönderimini zamanlayın.',
                'warning' => 'Raporlardaki maliyet verileri kesinleşmiş hakediş ve onaylı faturaları baz alır.'
            ],
            (object)[
                'step' => 12,
                'title' => '12. Platform Yönetimi & Destek',
                'category' => 'Sistem & Destek',
                'icon' => 'ri-settings-4-line',
                'color' => 'primary',
                'url' => route('admin.users.index'),
                'description' => 'Sistem kullanıcı yetkileri, rol tanımları, yazılım lisansı, şirket parametreleri, KDV oranları, Excel veri aktarımı, denetim izleri (Audit Trail) ve yardım merkezi.',
                'sub_modules' => [
                    ['name' => 'Kullanıcılar & Roller', 'url' => route('admin.users.index')],
                    ['name' => 'Yazılım Lisansı', 'url' => route('admin.license.index')],
                    ['name' => 'Şirket Ayarları', 'url' => route('index', ['any' => 'admin/standards'])],
                    ['name' => 'KDV Oranları', 'url' => route('admin.kdv.index')],
                    ['name' => 'Veri İçe/Dışa Aktar', 'url' => route('data-import-export.index')],
                    ['name' => 'Aktivite Günlüğü & Denetim İzi', 'url' => route('activity-logs.index')],
                    ['name' => 'Sistem Kayıtları', 'url' => route('admin.system.index')],
                    ['name' => 'Yardım Merkezi', 'url' => route('help.index')],
                    ['name' => 'Kılavuzlar & Dokümanlar', 'url' => route('help.guide')],
                    ['name' => 'Video Eğitimler', 'url' => route('help.videos')],
                    ['name' => 'Sıkça Sorulan Sorular (SSS)', 'url' => route('help.faq')],
                    ['name' => 'Destek Talebi / İletişim', 'url' => route('help.support')],
                ],
                'details' => [
                    'Kullanıcılar & Roller sayfasından şantiye şefleri, muhasebe ve satış temsilcileri için modül bazlı erişim yetkileri tanımlayın.',
                    'Veri İçe/Dışa Aktarma ekranından Excel dosyaları ile toplu stok kartı, cari liste ve metraj yüklemesi yapın.',
                    'Aktivite Günlüğü & Audit Trail modülü ile sistemde yapılan tüm veri silme ve güncelleme işlemlerini zaman damgasıyla denetleyin.'
                ],
                'pro_tip' => 'Kullanıcı rollerini tanımlarken "Sadece Okuma" yetkisi vererek hassas finansal verilerin korunmasını sağlayın.',
                'warning' => 'Sistem yönetici (Admin) şifresini iki faktörlü doğrulama (2FA) ile korumanız önerilir.'
            ],
        ];

        return view('help.guide', compact('guideSteps'));
    }

    /**
     * Display video tutorials from SQL Database.
     */
    public function videos(): View
    {
        $videos = HelpVideoTutorial::all();
        
        $videoCategories = $videos->groupBy('category_name')->map(function($vList, $catName) {
            return (object)[
                'name' => $catName,
                'videos' => $vList
            ];
        })->values();

        return view('help.videos', compact('videoCategories'));
    }

    /**
     * Display frequently asked questions from SQL Database.
     */
    public function faq(): View
    {
        $faqs = HelpFaq::orderBy('sort_order')->get();

        if ($faqs->isEmpty()) {
            $fallbackFaqs = collect([
                (object)['category_name' => '1. Yapay Zeka & Genel Bakış', 'question' => 'Yapay Zeka Asistanı şantiye gecikmelerini nasıl tahmin eder?', 'answer' => 'AI Asistanı, şantiyeden günlük girilen usta puantajları, beton döküm raporları ve hava durumu verilerini geçmiş iş programı bağıntıları (CPM) ile kıyaslayarak kritik yol gecikme risklerini hesaplar.'],
                (object)['category_name' => '2. Portföy, İhale & Fizibilite', 'question' => 'Fizibilite analizinde ROI ve kar marjı nasıl simüle edilir?', 'answer' => 'Fizibilite modülünde arsa bedeli, emsal inşaat alanı (KSK/TAK), yapı yaklaşık maliyet sınıfı ve hedeflenen satış birim m² fiyatları girilerek net nakit akışı ve yatırım geri dönüş süresi (ROI) otomatik simüle edilir.'],
                (object)['category_name' => '3. CRM & Satış Yönetimi', 'question' => 'Süresi dolan opsiyonlu gayrimenkul ünitelerine ne olur?', 'answer' => 'Opsiyon Yönetiminde belirlenen opsiyon süresi dolduğunda satış temsilcisine SMS/E-posta uyarısı gider. Onay verilmezse bağımsız bölüm otomatik olarak genel satışa açılır.'],
                (object)['category_name' => '4. Müşteri Yönetimi & Satış Sonrası', 'question' => 'Geciken senet ödemeleri için ihtarname nasıl oluşturulur?', 'answer' => 'Senet Vade Takvimi sayfasında vadesi geçen senetler kırmızı ile vurgulanır. Tek tıkla yasal ihtarname şablonu üretilebilir ve müşteriye otomatik bildirim gönderilir.'],
                (object)['category_name' => '5. Proje & Şantiye Yönetimi', 'question' => 'Hak Talepleri (Claims) ve Değişiklik Emirleri (Change Orders) nasıl işlenir?', 'answer' => 'Saha koşullarından kaynaklanan süre veya maliyet artışları Hak Talepleri modülünde kaydedilir. İşveren onayı alındığında Değişiklik Emri olarak proje bütçesine eklenir.'],
                (object)['category_name' => '6. Saha Operasyonları, İSG & Kalite', 'question' => 'EBİS beton testi basınç dayanımı olumsuz çıkarsa ne yapılmalıdır?', 'answer' => '7 veya 28 günlük beton kırım testi ÇŞB dayanım sınırının altında kalırsa ilgili döküm lokasyonu sistemde kırmızı alarma geçer ve karot testi sürecini başlatır.'],
                (object)['category_name' => '7. Taşeron & Tedarik Zinciri', 'question' => 'Taşeron hakedişinde avans ve teminat kesintisi nasıl uygulanır?', 'answer' => 'Taşeron hakediş onayında ihzarat ve imalat matrahı üzerinden %10 Avans Mahsubu ve %5 Teminat Kesintisi otomatik olarak net ödenecek tutardan düşülür.'],
                (object)['category_name' => '8. Hakediş, Finans & Muhasebe', 'question' => 'Resmi GİB E-Faturası ETTKN kodu aldıktan sonra nasıl iptal edilir?', 'answer' => 'ETTKN kodu alan e-faturalar GİB portalı üzerinde onaylanmış sayılır. İptal işlemleri GİB Temel Fatura İptal Portalı veya KEP üzerinden 8 gün içinde yapılmalıdır.'],
                (object)['category_name' => '9. Kaynaklar, İK & Tesis Yönetimi', 'question' => 'İş makinelerinin canlı GPS konumları nasıl izlenir?', 'answer' => 'Ekipman Parkı & Telemetri sayfasında Leaflet.js haritası üzerinde tüm araçlar canlı pin olarak görünür. Üzerine tıklandığında anlık çalışma saati ve depodaki yakıt miktarını verir.'],
                (object)['category_name' => '10. TR İnşaat Mevzuatı & Yasal Uyum', 'question' => 'SGK Asgari İşçilik ilişiksizlik belgesi bildirimi nasıl hesaplanır?', 'answer' => 'Bina toplam m² alanı ve ÇŞB Yapı Yaklaşık Maliyet grubu seçildiğinde, Kurum tarafından istenen minimum SGK prim matrahı ve şantiyeden bildirilen prim farkı otomatik raporlanır.'],
                (object)['category_name' => '11. İş Zekası, Raporlar & Çalışma Alanı', 'question' => 'Dinamik Rapor Oluşturucu ile özel Excel çıktıları nasıl alınır?', 'answer' => 'Dinamik Rapor sayfasında tablo sütunlarını (Örn: Proje, Taşeron, KDV Matrahı, Ödeme Tarihi) seçerek filtre uygulayabilir ve tek tıkla Excel/PDF indirebilirsiniz.'],
                (object)['category_name' => '12. Platform Yönetimi & Destek', 'question' => 'Kullanıcı yetkileri modül bazlı nasıl sınırlandırılır?', 'answer' => 'Kullanıcılar & Roller sayfasından her personel için role özel okuma, yazma, silme veya finansal verileri gizleme yetkisi tanımlanabilir.'],
            ]);

            $faqCategories = $fallbackFaqs->groupBy('category_name')->map(function($fList, $catName) {
                return (object)[
                    'name' => $catName,
                    'faqs' => $fList
                ];
            })->values();
        } else {
            $faqCategories = $faqs->groupBy('category_name')->map(function($fList, $catName) {
                return (object)[
                    'name' => $catName,
                    'faqs' => $fList
                ];
            })->values();
        }

        return view('help.faq', compact('faqCategories'));
    }

    /**
     * Display contact/support form.
     */
    public function contact(): View
    {
        $supportCategories = [
            'hakedis' => 'Hakediş & Muhasebe Destek',
            'saha' => 'Saha & Operasyon Destek',
            'efatura' => 'GİB E-Fatura Entegrasyonu',
            'satinalma' => 'Satın Alma & Stok Kartları',
            'is_makinesi' => 'İş Makinesi GPS & Tesis Yönetimi',
            'bug' => 'Sistem Hata Bildirimi'
        ];

        return view('help.contact', compact('supportCategories'));
    }

    /**
     * Alias for support page (GET /help/support).
     */
    public function support(): View
    {
        return $this->contact();
    }

    /**
     * Store support ticket request.
     */
    public function storeSupport(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'subject' => 'required|string',
            'priority' => 'required|string',
            'message' => 'required|string',
        ]);

        return back()->with('success', 'Destek biletiniz başarıyla oluşturuldu. Teknik ekibimiz en kısa sürede sizinle iletişime geçecektir.');
    }

    /**
     * Display a specific help article from SQL DB.
     */
    public function article($id): View
    {
        $article = HelpArticle::with('category')->findOrFail($id);
        $article->increment('views');

        return view('help.article', compact('article'));
    }

    /**
     * Display articles by category from SQL DB.
     */
    public function category($slug): View
    {
        $category = HelpCategory::where('slug', $slug)->firstOrFail();
        $articles = HelpArticle::where('help_category_id', $category->id)->latest()->get();
        $categoryName = $category->name;

        return view('help.category', compact('articles', 'categoryName', 'slug'));
    }

    /**
     * Search help articles from SQL DB.
     */
    public function search(Request $request): View
    {
        $query = $request->get('q', '');

        $searchResults = HelpArticle::with('category')
            ->where('title', 'like', "%{$query}%")
            ->orWhere('summary', 'like', "%{$query}%")
            ->orWhere('content', 'like', "%{$query}%")
            ->get();

        $totalResults = count($searchResults);

        $popularSearches = [
            'hakedis hesaplama',
            'efatura gib',
            'saha puantaji',
            'is makinesi gps',
            'nakit akis tahmini',
            'stok karti'
        ];

        return view('help.search', compact('query', 'searchResults', 'totalResults', 'popularSearches'));
    }

    /**
     * Display all help articles from SQL DB.
     */
    public function articles(): View
    {
        $articles = HelpArticle::with('category')->latest()->get();
        $categories = HelpCategory::withCount('articles')->pluck('articles_count', 'name')->toArray();

        return view('help.articles', compact('articles', 'categories'));
    }
}
