<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class DocumentationController extends Controller
{
    /**
     * Display the documentation index.
     */
    public function index(): View
    {
        $sections = [
            (object)[
                'id' => 1,
                'title' => 'Sistem Genel Bakış',
                'description' => 'Sistemimizdeki temel modüller ve işleyiş hakkında genel bilgiler',
                'icon' => 'fas fa-info-circle',
                'articles' => [
                    'Sisteme Giriş ve İlk Adımlar',
                    'Arayüz Kullanımı',
                    'Temel Navigasyon',
                    'Kullanıcı Rolleri ve Yetkileri'
                ]
            ],
            (object)[
                'id' => 2,
                'title' => 'Proje Yönetimi',
                'description' => 'Proje oluşturma, yönetme ve takip etme rehberi',
                'icon' => 'fas fa-project-diagram',
                'articles' => [
                    'Yeni Proje Oluşturma',
                    'Proje Ayarları ve Konfigürasyon',
                    'Milestone ve Timeline Yönetimi',
                    'Proje Raporlama',
                    'Takım Koordinasyonu'
                ]
            ],
            (object)[
                'id' => 3,
                'title' => 'Görev ve İş Akışı',
                'description' => 'Görev atama, takip ve iş akışı yönetimi',
                'icon' => 'fas fa-tasks',
                'articles' => [
                    'Görev Oluşturma ve Atama',
                    'Kanban Board Kullanımı',
                    'Scrum Metodolojisi',
                    'İş Akışı Otomasyonu',
                    'Performans Takibi'
                ]
            ],
            (object)[
                'id' => 4,
                'title' => 'Finansal Yönetim',
                'description' => 'Faturalama, ödeme ve finansal raporlama',
                'icon' => 'fas fa-dollar-sign',
                'articles' => [
                    'Fatura Oluşturma ve Gönderme',
                    'Ödeme Takibi',
                    'Finansal Raporlar',
                    'Bütçe Planlama',
                    'KDV ve Vergi İşlemleri'
                ]
            ],
            (object)[
                'id' => 5,
                'title' => 'Envanter Yönetimi',
                'description' => 'Stok takibi, malzeme yönetimi ve lojistik',
                'icon' => 'fas fa-boxes',
                'articles' => [
                    'Envanter Kayıt İşlemleri',
                    'Stok Takip ve Kontrol',
                    'Tedarikçi Yönetimi',
                    'Lojistik Planlama'
                ]
            ],
            (object)[
                'id' => 6,
                'title' => 'API ve Entegrasyon',
                'description' => 'Sistem entegrasyonları ve API kullanımı',
                'icon' => 'fas fa-plug',
                'articles' => [
                    'API Dokümantasyonu',
                    'Webhook Yapılandırması',
                    'Üçüncü Parti Entegrasyonlar',
                    'Veri İçe/Dışa Aktarma'
                ]
            ]
        ];

        $quickLinks = [
            (object)[
                'title' => 'API Referansı',
                'url' => '/api/documentation',
                'icon' => 'fas fa-code'
            ],
            (object)[
                'title' => 'Video Eğitimler',
                'url' => '/help/videos',
                'icon' => 'fas fa-video'
            ],
            (object)[
                'title' => 'SSS',
                'url' => '/help/faq',
                'icon' => 'fas fa-question-circle'
            ],
            (object)[
                'title' => 'Destek Talebi',
                'url' => '/support/ticket',
                'icon' => 'fas fa-headset'
            ]
        ];

        $recentUpdates = [
            (object)[
                'title' => 'Yeni Kanban Board Özellikleri',
                'date' => '2025-01-15',
                'type' => 'feature'
            ],
            (object)[
                'title' => 'API v2.1 Güncellemesi',
                'date' => '2025-01-10',
                'type' => 'update'
            ],
            (object)[
                'title' => 'Fatura Modülü İyileştirmeleri',
                'date' => '2025-01-05',
                'type' => 'improvement'
            ]
        ];

        return view('documentation.index', compact('sections', 'quickLinks', 'recentUpdates'));
    }
}