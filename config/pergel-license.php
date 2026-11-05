<?php

return [
    'dev_license' => env('PERGEL_DEV_LICENSE', null),

    'api_base' => env('PERGEL_LICENSE_API', 'https://erdemeroglu.com.tr/api/license/pergel'),

    'cache_ttl_hours' => (int) env('PERGEL_LICENSE_CACHE_HOURS', 24),

    /** Son başarılı doğrulamadan sonra internetsiz çalışma süresi (gün) */
    'offline_grace_days' => (int) env('PERGEL_LICENSE_OFFLINE_GRACE_DAYS', 7),

    'renew_url' => env('PERGEL_LICENSE_RENEW_URL', 'https://erdemeroglu.com.tr/yazilimlar/pergel#pro'),

    /** Topluluk (Community - Ücretsiz) Modülleri */
    'community_modules' => [
        'project',
        'crm_basic',
        'facility_basic',
        'pwa',
        'reports_basic',
        'workspace',
        'help',
    ],

    /** Topluluk (Free) Sürüm Sayısal Limitleri */
    'community_limits' => [
        'max_projects' => (int) env('PERGEL_COMMUNITY_MAX_PROJECTS', 2),
        'max_users' => (int) env('PERGEL_COMMUNITY_MAX_USERS', 2),
        'max_assets' => (int) env('PERGEL_COMMUNITY_MAX_ASSETS', 25),
        'allow_export' => (bool) env('PERGEL_COMMUNITY_ALLOW_EXPORT', false),
    ],

    /** 3'lü Modül Paket Modeli */
    'packages' => [
        'field_subcontractor' => [
            'slug' => 'pergel-field',
            'name' => 'Saha & Taşeron Paketi',
            'badge' => 'Saha & Taşeron',
            'icon' => 'fas fa-hard-hat',
            'price' => 5990,
            'description' => 'Şantiye ve sahada taşeron, iş güvenliği (İSG), günlük defter ve QR arıza yönetimi.',
            'modules' => ['subcontractor', 'qhse', 'qr_sla'],
            'limits' => [
                'max_projects' => 10,
                'max_users' => 10,
                'max_assets' => -1,
                'allow_export' => true,
            ],
            'features' => [
                'Taşeron Portalı & Dış Paydaş Girişi',
                'İş Güvenliği (İSG) & Saha Kalite / Beton Kırım',
                'Mobil Saha Günlüğü & Şantiye Defteri',
                'Kat Planı QR Kodları & SLA Yönetimi',
                'Excel & PDF Dışa Aktarım',
            ],
        ],
        'compliance_finance' => [
            'slug' => 'pergel-compliance',
            'name' => 'Mevzuat & Finans Paketi',
            'badge' => 'Mevzuat & Hakediş',
            'icon' => 'fas fa-coins',
            'price' => 7990,
            'description' => 'ÇŞB pozları, resmi hakedişler, SGK asgari işçilik, Yİ-ÜFE ve ihale yönetimi.',
            'modules' => ['tr_compliance', 'finance', 'tenders'],
            'limits' => [
                'max_projects' => 10,
                'max_users' => 10,
                'max_assets' => -1,
                'allow_export' => true,
            ],
            'features' => [
                'Çevre ve Şehircilik Bakanlığı (ÇŞB) Güncel Pozları',
                'Resmi İnşaat Hakedişleri (Yeşil Defter & Onay Zincirleri)',
                'SGK Asgari İşçilik Hesaplayıcı',
                'TÜİK Yİ-ÜFE Endeksli Resmi Fiyat Farkı Hesabı',
                'İhale Paketi Hazırlama & Teklif Karşılaştırma',
                'Nakit Akışı, Çek/Senet & Bütçe Simülatörü',
            ],
        ],
        'full_enterprise' => [
            'slug' => 'pergel-pro',
            'name' => 'Full Enterprise Paketi',
            'badge' => 'Hepsi Dahil (Pro)',
            'icon' => 'ri-vip-crown-2-fill',
            'price' => 14990,
            'description' => 'Tüm modüller, yapay zeka asistanı, 3D BIM, Executive BI ve sınırsız kullanım.',
            'modules' => [
                'subcontractor', 'finance', 'tr_compliance', 'tenders',
                'qhse', 'qr_sla', 'bim', 'ai', 'bi_reports', 'portfolio', 'erp'
            ],
            'limits' => [
                'max_projects' => -1,
                'max_users' => -1,
                'max_assets' => -1,
                'allow_export' => true,
            ],
            'features' => [
                'Saha & Taşeron Paketinin Tüm Özellikleri',
                'Mevzuat & Finans Paketinin Tüm Özellikleri',
                'BIM 3D Model Görüntüleme & Çakışma Testleri',
                'Yapay Zeka Asistanı & AI Puantaj Analizörü',
                'C-Level Executive Dashboard (BI)',
                'Sınırsız Şantiye, Kullanıcı ve Varlık',
                'Öncelikli 7/24 Teknik Destek & SLA',
            ],
        ],
    ],

    /** Kurumsal (PERGEL Pro - Lisanslı) Modülleri */
    'pro_modules' => [
        'subcontractor',
        'finance',
        'tr_compliance',
        'tenders',
        'qhse',
        'qr_sla',
        'bim',
        'ai',
        'bi_reports',
        'portfolio',
        'erp',
    ],

    /** Modül Bilgileri ve Tanımları (Yükseltme vitrininde kullanılır) */
    'module_meta' => [
        'tr_compliance' => [
            'name' => 'Türkiye İnşaat Mevzuatı & Yasal Uyum',
            'badge' => 'TR Mevzuat',
            'icon' => 'fas fa-flag',
            'description' => 'ÇŞB Poz Kütüphanesi, SGK Asgari İşçilik, Yİ-ÜFE Fiyat Farkı, Kat Karşılığı Matrisi, E-İrsaliye ve Yapı Denetim takibi.',
            'features' => [
                'Çevre ve Şehircilik Bakanlığı güncel birim poz kütüphanesi',
                'SGK Asgari İşçilik ilişiksizlik belgesi hesaplayıcısı',
                'TÜİK Yİ-ÜFE endeksli resmi fiyat farkı hesabı',
                'Kat karşılığı arsa payı ve bağımsız bölüm paylaşım matrisi',
                'E-İrsaliye ve cari E-Mutabakat (BA/BS) uyumu',
            ],
        ],
        'subcontractor' => [
            'name' => 'Taşeron & Yüklenici Portalı',
            'badge' => 'Taşeron Portalı',
            'icon' => 'fas fa-user-friends',
            'description' => 'Dış paydaş girişi, taşeron işçi evrakları, puantaj, avans/hakediş talepleri ve performans puanlaması.',
            'features' => [
                'Taşeronlara özel şifreli giriş portalı',
                'İSG işçi evrakları ve sertifika geçerlilik takibi',
                'Saha malzeme ve servis girişlerinin taşeronca yapılması',
                'Avans ve ara ödeme talep süreçleri',
                'Taşeron performans puanlama ve kesinti yönetimi',
            ],
        ],
        'finance' => [
            'name' => 'Resmi Hakediş & İleri Finans',
            'badge' => 'Finans & Hakediş',
            'icon' => 'fas fa-coins',
            'description' => 'Yeşil defter hakediş onay zincirleri, bütçe simülasyonu, nakit akışı, çek/senet ve döviz kurları.',
            'features' => [
                'Resmi inşaat hakediş raporları ve yeşil defter',
                'Çoklu para birimi ve otomatik TCMB kur entegrasyonu',
                'Kapsamlı nakit akışı projeksiyonu ve bütçe simülatörü',
                'Çek, senet ve teminat mektubu takip sistemi',
                'Esnek ödeme planları ve dijital sözleşmeler',
            ],
        ],
        'tenders' => [
            'name' => 'İhale & Teklif Yönetimi',
            'badge' => 'İhale & Teklif',
            'icon' => 'fas fa-file-signature',
            'description' => 'İhale paketi hazırlama, taşeron teklif toplama ve birim fiyat teklif karşılaştırma matrisleri.',
            'features' => [
                'İhale paketi oluşturma ve teknik şartname bağlama',
                'Taşeronlara otomatik davet ve teklif toplama',
                'Birim fiyatlı teklif karşılaştırma matrisleri',
                'Sözleşme öncesi pazarlık ve revizyon geçmişi',
            ],
        ],
        'qhse' => [
            'name' => 'İş Sağlığı (İSG) & Saha Kalite Kontrol',
            'badge' => 'QHSE / İSG',
            'icon' => 'fas fa-hard-hat',
            'description' => 'Saha denetimleri, beton test kayıtları, ramak kala bildirimleri, şantiye günlüğü ve saha medyası.',
            'features' => [
                'Dijital şantiye günlüğü ve hava durumu kayıtları',
                'Beton döküm ve kırım testi kayıt sertifikaları',
                'İSG denetimleri, ramak kala ve olay raporları',
                'Şantiye görsel medya ve tutanak arşivi',
            ],
        ],
        'qr_sla' => [
            'name' => 'QR Kodlu Destek & SLA Yönetimi',
            'badge' => 'QR & SLA',
            'icon' => 'fas fa-qrcode',
            'description' => 'Kat planı varlıkları için dinamik QR kod üretimi, anlık servis talebi ve SLA ihlal takip mekanizması.',
            'features' => [
                'Kat planı bağımsız bölümlerine dinamik QR etiketleme',
                'Uygulama indirmeden QR okutarak arıza/servis bildirme',
                'Özelleştirilebilir SLA müdahale ve çözüm süreleri',
                'SLA ihlali otomatik eskalasyon ve e-posta uyarıları',
            ],
        ],
        'bim' => [
            'name' => 'BIM 3D Model & Canlı Şantiye',
            'badge' => 'BIM / 3D',
            'icon' => 'fas fa-cube',
            'description' => '3D IFC/BIM model görselleştirme, disiplinler arası çakışma testleri ve canlı şantiye kameraları.',
            'features' => [
                'Tarayıcı üzerinden 3D IFC model inceleme',
                'Mimari/Statik/Mekanik çakışma (clash) testleri',
                'Şantiye canlı kamera ve RTSP yayın entegrasyonu',
            ],
        ],
        'ai' => [
            'name' => 'Yapay Zeka Asistanı & Analitik',
            'badge' => 'AI Asistan',
            'icon' => 'fas fa-robot',
            'description' => 'AI puantaj analizörü, sözleşme risk analizi, proaktif öneriler ve saha veri analitiği.',
            'features' => [
                'Şantiye puantaj ve mesai anomalilerini tespit eden yapay zeka',
                'Arsa sahibi ve taşeron sözleşmeleri yapay zeka risk taraması',
                'Gecikme ve bütçe aşımı öngörü modelleri',
            ],
        ],
        'bi_reports' => [
            'name' => 'Executive Dashboard & İş Zekası (BI)',
            'badge' => 'İş Zekası (BI)',
            'icon' => 'fas fa-chart-line',
            'description' => 'Üst yönetim için konsolide KPI göstergeleri, risk haritası ve dinamik raporlama motoru.',
            'features' => [
                'C-Level Executive özet panosu ve finansal sağlık skoru',
                'Projeler arası görsel risk haritası',
                'Dinamik rapor oluşturucu ve dışa aktarım (PDF/Excel)',
            ],
        ],
        'portfolio' => [
            'name' => 'Gelişmiş Portföy & Fizibilite',
            'badge' => 'Portföy & Program',
            'icon' => 'fas fa-cubes',
            'description' => 'Çoklu proje ve program yönetimi, konsolide finansal göstergeler ve fizibilite analizleri.',
            'features' => [
                'Portföy düzeyinde konsolide finans ve program takibi',
                'Detaylı yatırım fizibilite analizleri ve senaryo karşılaştırma',
            ],
        ],
        'erp' => [
            'name' => 'Şantiye ERP & Telemetri',
            'badge' => 'ERP & IoT',
            'icon' => 'fas fa-sitemap',
            'description' => 'Masraf merkezleri, satın alma siparişleri, araç filosu telemetrisi ve IoT entegrasyonu.',
            'features' => [
                'Şantiye araç ve iş makineleri GPS/telemetri entegrasyonu',
                'Entegre maliyet ve satın alma sipariş yönetimi',
            ],
        ],
    ],

    /** URI önekleri → gerekli modül (en uzun eşleşme öncelikli) */
    'uri_modules' => [
        // TR Mevzuat & Resmi İşlemler
        'csb_poz' => 'tr_compliance',
        'sgk' => 'tr_compliance',
        'land_owner_contracts' => 'tr_compliance',
        'price_differences' => 'tr_compliance',
        'ewaybills' => 'tr_compliance',
        'building_inspections' => 'tr_compliance',
        'vat_returns' => 'tr_compliance',
        'real_estate_valuations' => 'tr_compliance',
        'waste_managements' => 'tr_compliance',
        'e_agreements' => 'tr_compliance',

        // İhale & Teklif
        'portfolio/tenders' => 'tenders',
        'tenders' => 'tenders',

        // Yapay Zeka & Tahminleme
        'ai-module' => 'ai',
        'ai_timesheet_analyzers' => 'ai',
        'ai-timesheet-analyzers' => 'ai',
        'contracts-ai-analysis' => 'ai',

        // Taşeron Portalı & Ekosistem
        'subcontractor' => 'subcontractor',
        'subcontractor_workers' => 'subcontractor',
        'subcontractor_deductions' => 'subcontractor',
        'subcontractor_scores' => 'subcontractor',
        'hr_personnels' => 'subcontractor',
        'admin/subcontractors' => 'subcontractor',

        // Saha Kalite & İSG (QHSE)
        'field/qhse' => 'qhse',
        'concrete_tests' => 'qhse',
        'site_diaries' => 'qhse',
        'site_daily_logs' => 'qhse',

        // QR & SLA
        'facility/qr' => 'qr_sla',
        'facility/sla' => 'qr_sla',

        // BIM & IoT
        'bim' => 'bim',
        'site_cameras' => 'bim',
        'fleet_telemetries' => 'erp',

        // İş Zekası & Raporlar
        'executive_dashboards' => 'bi_reports',
        'executive-dashboards' => 'bi_reports',
        'dynamic_reports' => 'bi_reports',
        'advanced-reports' => 'bi_reports',
        'analytics' => 'bi_reports',

        // Finans, Hakediş & ERP
        'budget_simulators' => 'finance',
        'promissory_notes' => 'finance',
        'promissory-notes-timeline' => 'finance',
        'trade_assets' => 'finance',
        'finance' => 'finance',
        'progress-payments' => 'finance',
        'purchasing' => 'finance',
        'flexible-payments' => 'finance',
        'currency-exchanges' => 'finance',
        'digital-contracts' => 'finance',
        'contracts' => 'finance',
        'cost' => 'finance',
        'cost/erp' => 'erp',

        // Portföy & Fizibilite
        'feasibility' => 'portfolio',
        'portfolio' => 'portfolio',
    ],

    'excluded_uri_prefixes' => [
        'admin/license',
        'login',
        'logout',
        'subcontractor/login',
        'api/',
        'up',
        'debug/',
        '__dev/',
    ],
];
