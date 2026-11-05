<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class KnowledgeBaseController extends Controller
{
    /**
     * Display the knowledge base index.
     */
    public function index(): View
    {
        $categories = [
            (object)[
                'id' => 1,
                'name' => 'Genel Bilgiler',
                'description' => 'Sistem hakkında temel bilgiler ve başlangıç rehberi',
                'icon' => 'fas fa-info-circle',
                'color' => 'primary',
                'article_count' => 15,
                'articles' => [
                    (object)['title' => 'Sisteme İlk Giriş', 'views' => 1250, 'helpful' => 95],
                    (object)['title' => 'Temel Navigasyon', 'views' => 980, 'helpful' => 92],
                    (object)['title' => 'Kullanıcı Profili Ayarları', 'views' => 756, 'helpful' => 88]
                ]
            ],
            (object)[
                'id' => 2,
                'name' => 'Proje Yönetimi',
                'description' => 'Proje oluşturma, yönetme ve takip konularında rehberler',
                'icon' => 'fas fa-project-diagram',
                'color' => 'success',
                'article_count' => 23,
                'articles' => [
                    (object)['title' => 'Yeni Proje Nasıl Oluşturulur?', 'views' => 2100, 'helpful' => 97],
                    (object)['title' => 'Proje Timeline Yönetimi', 'views' => 1450, 'helpful' => 94],
                    (object)['title' => 'Milestone Takibi', 'views' => 1200, 'helpful' => 91]
                ]
            ],
            (object)[
                'id' => 3,
                'name' => 'Görev Yönetimi',
                'description' => 'Görev atama, takip ve koordinasyon rehberleri',
                'icon' => 'fas fa-tasks',
                'color' => 'warning',
                'article_count' => 18,
                'articles' => [
                    (object)['title' => 'Görev Atama ve Takip', 'views' => 1800, 'helpful' => 96],
                    (object)['title' => 'Kanban Board Kullanımı', 'views' => 1350, 'helpful' => 93],
                    (object)['title' => 'Scrum Metodolojisi', 'views' => 1100, 'helpful' => 89]
                ]
            ],
            (object)[
                'id' => 4,
                'name' => 'Finansal İşlemler',
                'description' => 'Faturalama, ödeme ve finansal raporlama konuları',
                'icon' => 'fas fa-dollar-sign',
                'color' => 'info',
                'article_count' => 20,
                'articles' => [
                    (object)['title' => 'Fatura Oluşturma Rehberi', 'views' => 1650, 'helpful' => 95],
                    (object)['title' => 'Ödeme Takibi', 'views' => 1200, 'helpful' => 92],
                    (object)['title' => 'Finansal Raporlar', 'views' => 950, 'helpful' => 87]
                ]
            ],
            (object)[
                'id' => 5,
                'name' => 'Teknik Destek',
                'description' => 'Sistem sorunları ve teknik problemlerin çözümleri',
                'icon' => 'fas fa-wrench',
                'color' => 'danger',
                'article_count' => 12,
                'articles' => [
                    (object)['title' => 'Sık Karşılaşılan Hatalar', 'views' => 2500, 'helpful' => 89],
                    (object)['title' => 'Performans Optimizasyonu', 'views' => 850, 'helpful' => 91],
                    (object)['title' => 'Backup ve Geri Yükleme', 'views' => 650, 'helpful' => 94]
                ]
            ]
        ];

        $popularArticles = [
            (object)[
                'title' => 'Yeni Proje Nasıl Oluşturulur?',
                'category' => 'Proje Yönetimi',
                'views' => 2100,
                'helpful' => 97,
                'updated' => '2025-01-10'
            ],
            (object)[
                'title' => 'Sık Karşılaşılan Hatalar',
                'category' => 'Teknik Destek',
                'views' => 2500,
                'helpful' => 89,
                'updated' => '2025-01-12'
            ],
            (object)[
                'title' => 'Görev Atama ve Takip',
                'category' => 'Görev Yönetimi',
                'views' => 1800,
                'helpful' => 96,
                'updated' => '2025-01-08'
            ],
            (object)[
                'title' => 'Fatura Oluşturma Rehberi',
                'category' => 'Finansal İşlemler',
                'views' => 1650,
                'helpful' => 95,
                'updated' => '2025-01-15'
            ]
        ];

        $recentArticles = [
            (object)[
                'title' => 'Yeni Kanban Özellikleri',
                'category' => 'Görev Yönetimi',
                'author' => 'Admin',
                'created' => '2025-01-15'
            ],
            (object)[
                'title' => 'API v2.1 Güncellemeleri',
                'category' => 'Teknik Destek',
                'author' => 'Geliştirici Ekibi',
                'created' => '2025-01-12'
            ],
            (object)[
                'title' => 'Envanter Modülü Kullanımı',
                'category' => 'Genel Bilgiler',
                'author' => 'Destek Ekibi',
                'created' => '2025-01-10'
            ]
        ];

        $stats = [
            'total_articles' => 88,
            'total_views' => 15420,
            'avg_helpful' => 92,
            'categories' => 5
        ];

        return view('knowledge-base.index', compact('categories', 'popularArticles', 'recentArticles', 'stats'));
    }
}