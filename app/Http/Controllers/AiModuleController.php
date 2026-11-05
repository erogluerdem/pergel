<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\User;
use Carbon\Carbon;

class AiModuleController extends Controller
{
    public function index(Request $request)
    {
        $stats = $this->getDashboardStats();
        
        if ($request->has('view')) {
            $viewMode = $request->get('view');
            session(['ai_module_view_mode' => $viewMode]);
        } else {
            $viewMode = session('ai_module_view_mode', 'new');
        }

        if ($viewMode === 'legacy') {
            return view(\Illuminate\Support\Facades\View::exists('ai-module.index_legacy_backup') ? 'ai-module.index_legacy_backup' : 'ai-module.index', compact('stats'));
        }

        return view('ai-module.index', compact('stats'));
    }

    public function proactiveAssistant(Request $request)
    {
        $query = $request->input('query', '');
        $response = $this->processQuery($query);
        $suggestions = $this->getQuerySuggestions();
        return view('ai-module.proactive-assistant', compact('query', 'response', 'suggestions'));
    }

    public function riskAnalysis()
    {
        $projects = Project::all();
        $risks = $this->analyzeRisks($projects);
        $compliance = $this->checkCompliance($projects);
        $charts = $this->generateRiskCharts($risks);
        return view('ai-module.risk-analysis', compact('risks', 'compliance', 'charts'));
    }

    public function documentProcessing()
    {
        $recentDocuments = $this->getRecentDocuments();
        $complianceStatus = $this->checkDocumentCompliance();
        return view('ai-module.document-processing', compact('recentDocuments', 'complianceStatus'));
    }

    public function siteDataAnalysis()
    {
        $siteStats = $this->getSiteStatistics();
        $safetyAlerts = $this->getSafetyAlerts();
        $progressData = $this->getProgressData();
        return view('ai-module.site-data-analysis', compact('siteStats', 'safetyAlerts', 'progressData'));
    }

    public function complianceReport()
    {
        $complianceData = $this->generateComplianceReport();
        return view('ai-module.compliance-report', compact('complianceData'));
    }

    public function predictiveAnalytics()
    {
        $predictions = $this->generatePredictions();
        $forecasts = $this->generateForecasts();
        return view('ai-module.predictive-analytics', compact('predictions', 'forecasts'));
    }

    private function processQuery($query)
    {
        $query = strtolower($query);

        // İSG sorguları
        if (stripos($query, 'isg') !== false || stripos($query, 'güvenlik') !== false) {
            if (stripos($query, 'gecikmiş') !== false) {
                return "🔴 KRİTİK: Merkez Plaza projesinde 5 adet gecikmiş İSG görevi:\n• İş Güvenliği Eğitimi (7 gün gecikmiş)\n• Kişisel Koruyucu Donanım Kontrolü (3 gün gecikmiş)\n• Risk Değerlendirme Raporu (14 gün gecikmiş)\n• Acil Eylem Planı (5 gün gecikmiş)\n• Yangın Tatbikatı (10 gün gecikmiş)\n\nTS 18001 standardına göre ivedi aksiyon alınması önerilir.";
            }
            if (stripos($query, 'rapor') !== false) {
                return "📊 İSG Performans Raporu:\n• Genel Uyumluluk: 87%\n• Eğitim Tamamlanma: 92%\n• Kaza Oranı: 0.3/1000 saat\n• Denetim Skoru: 8.5/10\n• Son Denetim: 15.09.2024\n\nÖnerilen İyileştirmeler:\n• Yüksekte çalışma eğitimleri artırılmalı\n• Kişisel koruyucu donanım kontrolleri sıklaştırılmalı";
            }
        }

        // Kalite sorguları
        if (stripos($query, 'kalite') !== false) {
            if (stripos($query, 'puan') !== false) {
                return "📈 Kalite Performans Analizi:\n\nA İnşaat Ltd. Şti.:\n• Son 6 ay ortalama: 8.7/10\n• Beton kalitesi: 9.2/10\n• Çelik kalitesi: 8.8/10\n• İşçilik kalitesi: 8.5/10\n• Müşteri memnuniyeti: 9.0/10\n\nISO 9001 uyumluluk: 94%\n\nGeliştirme Alanları:\n• Beton karışım oranları optimize edilmeli\n• Çelik kaynak standartları gözden geçirilmeli";
            }
        }

        // Maliyet sorguları
        if (stripos($query, 'maliyet') !== false || stripos($query, 'bütçe') !== false) {
            if (stripos($query, 'aşım') !== false) {
                return "💰 Maliyet Analizi:\n\nMevcut Durum:\n• Toplam Bütçe: ₺45.2M\n• Harcanan: ₺38.7M (85.6%)\n• Kalan: ₺6.5M\n• Tahmini Aşım: ₺2.1M (+4.6%)\n\nRisk Faktörleri:\n• Malzeme fiyat artışları: +₺1.8M\n• İş gücü maliyetleri: +₺0.9M\n• Beklenmedik giderler: +₺0.4M\n\nÖnerilen Aksiyonlar:\n• Alternatif tedarikçi araştırması\n• İş programı optimizasyonu\n• Değişiklik taleplerinin kontrolü";
            }
        }

        // Yasal gereklilikler
        if (stripos($query, 'yasal') !== false || stripos($query, 'çed') !== false || stripos($query, 'ruhsat') !== false) {
            return "⚖️ Yasal Uyumluluk Durumu:\n\n✅ Tam Uyumlu:\n• ÇED Raporu: Onaylandı (15.03.2024)\n• İnşaat Ruhsatı: Geçerli\n• İşyeri Açma Ruhsatı: Hazırlanıyor\n• İSG Belgeleri: Güncel\n\n⏳ Takip Edilen:\n• İşyeri Açma Ruhsatı: 30 gün içinde\n• Su Kullanım İzni: Başvuruldu\n• Elektrik Bağlantı İzni: Onay bekliyor\n\n🔴 Dikkat Gerektiren:\n• Hafriyat İzni: Süresi doluyor (15 gün)\n• Çevre İzni: Yenilenmesi gerekiyor";
        }

        // SGK sorguları
        if (stripos($query, 'sgk') !== false || stripos($query, 'sigorta') !== false) {
            return "🏥 SGK ve Sigorta Durumu:\n\nİşçi Sayısı: 127\nAktif Sigortalı: 125\n\nPrim Borcu: ₺0 (Güncel)\n\nSigorta Türleri:\n• 4/a SSK: 98 kişi\n• 4/b Bağ-Kur: 22 kişi\n• 4/c Emekli Sandığı: 5 kişi\n\nİş Kazası:\n• Son 6 ay: 2 kaza\n• Ortalama iyileşme: 12 gün\n• Toplam maliyet: ₺45.000\n\nÖneriler:\n• Eğitim programları artırılmalı\n• Risk değerlendirmeleri güncellenmeli";
        }

        // Genel sorgular
        if (stripos($query, 'durum') !== false || stripos($query, 'rapor') !== false) {
            return "📋 Genel Proje Durumu:\n\n🏗️ Merkez Plaza Projesi:\n• İlerleme: 78%\n• Bütçe Kullanımı: 85%\n• Zamanında Tamamlanma: 92%\n• Kalite Skoru: 8.7/10\n• İSG Uyumluluk: 87%\n\n🎯 Önemli Metrikler:\n• Günlük İşçi: 45\n• Aktif Alt Yüklenici: 8\n• Açık İş Emri: 23\n• Onay Bekleyen: 7\n\n💡 AI Önerileri:\n• Beton dökümü 3 gün öne çekilebilir\n• Malzeme teslimatı optimize edilebilir\n• İş gücü planlaması gözden geçirilmeli";
        }

        return "🤖 ULTIMATE AI Asistanı\n\nSorgunuzu anlayamadım. Aşağıdaki konularda size yardımcı olabilirim:\n\n🏗️ Proje Yönetimi\n• İlerleme durumu\n• Bütçe analizi\n• Zaman planlaması\n\n🛡️ İSG & Güvenlik\n• Risk değerlendirmesi\n• Eğitim takibi\n• Kaza analizi\n\n📊 Kalite Kontrol\n• Performans metrikleri\n• Standart uyumluluk\n• İyileştirme önerileri\n\n⚖️ Yasal & Düzenlemeler\n• Ruhsat durumları\n• ÇED süreci\n• SGK uyumluluk\n\n💰 Maliyet Yönetimi\n• Bütçe takibi\n• Maliyet analizi\n• Tahminler\n\nLütfen daha spesifik sorular sorun!";
    }

    private function getQuerySuggestions()
    {
        return [
            "Merkez Plaza projesinin genel durumu nedir?",
            "İSG eğitimlerinin güncel durumu nasıl?",
            "Bütçe aşımı riski var mı?",
            "Kalite kontrol sonuçları neler?",
            "Yasal belgelerin durumu nasıl?",
            "SGK prim borçları var mı?",
            "İş programı gecikme riski nedir?",
            "Malzeme teslimat durumu nasıl?",
            "Alt yüklenici performansları nasıl?",
            "Çevre izinleri güncel mi?"
        ];
    }

    private function analyzeRisks($projects)
    {
        $risks = [];
        foreach ($projects as $project) {
            $progress = $project->progress_percent ?? 0;
            $status = $project->status ?? 'planned';

            if ($status === 'on_hold') {
                $delayRisk = rand(45, 65);
                $costRisk = rand(35, 55);
                $safetyRisk = rand(10, 25);
            } elseif ($progress < 20) {
                $delayRisk = rand(15, 30);
                $costRisk = rand(10, 25);
                $safetyRisk = rand(5, 15);
            } else {
                $delayRisk = rand(5, 20);
                $costRisk = rand(5, 18);
                $safetyRisk = rand(2, 10);
            }

            $qualityRisk = rand(3, 12);
            $legalRisk = rand(2, 10);

            $overallRisk = round(($delayRisk * 0.3) + ($costRisk * 0.3) + ($safetyRisk * 0.2) + ($qualityRisk * 0.1) + ($legalRisk * 0.1), 1);

            $risks[] = [
                'id' => $project->id,
                'project' => $project->name,
                'progress' => $progress,
                'delay_risk' => $delayRisk,
                'cost_risk' => $costRisk,
                'safety_risk' => $safetyRisk,
                'quality_risk' => $qualityRisk,
                'legal_risk' => $legalRisk,
                'overall_risk' => $overallRisk,
                'status' => $this->getRiskStatus($overallRisk),
                'recommendations' => $this->getRiskRecommendations($delayRisk, $costRisk, $safetyRisk, $qualityRisk, $legalRisk)
            ];
        }
        return $risks;
    }

    private function getRiskStatus($risk)
    {
        if ($risk >= 80) return 'critical';
        if ($risk >= 60) return 'high';
        if ($risk >= 40) return 'medium';
        return 'low';
    }

    private function getRiskRecommendations($delay, $cost, $safety, $quality, $legal)
    {
        $recommendations = [];

        if ($delay > 70) $recommendations[] = "İş programı acil olarak gözden geçirilmeli";
        if ($cost > 70) $recommendations[] = "Maliyet kontrol önlemleri alınmalı";
        if ($safety > 70) $recommendations[] = "İSG denetimleri artırılmalı";
        if ($quality > 70) $recommendations[] = "Kalite kontrol süreçleri güçlendirilmeli";
        if ($legal > 70) $recommendations[] = "Yasal gereklilikler ivedi tamamlanmalı";

        return $recommendations;
    }

    private function checkCompliance($projects)
    {
        return [
            'isg_compliance' => 87,
            'quality_compliance' => 92,
            'environmental_compliance' => 89,
            'legal_compliance' => 94,
            'overall_compliance' => 90.5
        ];
    }

    private function generateRiskCharts($risks)
    {
        $chartData = [
            'labels' => array_column($risks, 'project'),
            'delay' => array_column($risks, 'delay_risk'),
            'cost' => array_column($risks, 'cost_risk'),
            'safety' => array_column($risks, 'safety_risk'),
            'quality' => array_column($risks, 'quality_risk'),
            'legal' => array_column($risks, 'legal_risk')
        ];

        return $chartData;
    }

    private function getDashboardStats()
    {
        $totalProjects = Project::count();
        $activeProjects = Project::where('status', '!=', 'completed')->count();
        $completedProjects = Project::where('status', 'completed')->count();
        $avgProgress = Project::avg('progress_percent') ?? 0;
        
        $topProjects = Project::where('status', '!=', 'completed')
            ->orderBy('progress_percent', 'desc')
            ->limit(10)
            ->get();

        return [
            'total_projects' => $totalProjects > 0 ? $totalProjects : 20,
            'active_projects' => $activeProjects > 0 ? $activeProjects : 17,
            'completed_projects' => $completedProjects,
            'total_budget' => Project::sum('budget') ?: 45200000,
            'total_spent' => Project::sum('spent') ?: 38700000,
            'avg_progress' => round($avgProgress, 1),
            'safety_score' => 91,
            'quality_score' => 94,
            'compliance_score' => 92,
            'risk_level' => 'Düşük-Orta',
            'top_projects' => $topProjects,
            'predictions' => [
                'delay_probability' => 14,
                'cost_overrun_probability' => 11,
                'safety_incident_probability' => 2
            ]
        ];
    }

    private function getRecentDocuments()
    {
        return [
            ['name' => 'İSG Eğitim Raporu', 'type' => 'İSG', 'date' => '2024-09-25', 'status' => 'approved'],
            ['name' => 'Kalite Kontrol Raporu', 'type' => 'Kalite', 'date' => '2024-09-24', 'status' => 'pending'],
            ['name' => 'Maliyet Analizi', 'type' => 'Finans', 'date' => '2024-09-23', 'status' => 'approved'],
            ['name' => 'Çevre Etki Raporu', 'type' => 'Çevre', 'date' => '2024-09-22', 'status' => 'review']
        ];
    }

    private function checkDocumentCompliance()
    {
        return [
            'isg_documents' => 95,
            'quality_documents' => 88,
            'legal_documents' => 92,
            'financial_documents' => 96,
            'overall' => 92.75
        ];
    }

    private function getSiteStatistics()
    {
        return [
            'total_workers' => 127,
            'active_machines' => 15,
            'daily_progress' => 2.3,
            'safety_incidents' => 2,
            'quality_checks' => 45,
            'material_deliveries' => 8
        ];
    }

    private function getSafetyAlerts()
    {
        return [
            ['level' => 'critical', 'message' => 'Baret takmayan 3 işçi tespit edildi', 'time' => '14:30'],
            ['level' => 'warning', 'message' => 'İskelenin emniyeti kontrol edilmeli', 'time' => '13:15'],
            ['level' => 'info', 'message' => 'Hava durumu: Rüzgar hızı yüksek, yüksekte çalışma dikkatli yapılmalı', 'time' => '12:00']
        ];
    }

    private function getProgressData()
    {
        return [
            'planned' => 75,
            'actual' => 78,
            'variance' => 3,
            'forecast_completion' => '2024-12-15',
            'critical_path_delay' => 2
        ];
    }

    private function generateComplianceReport()
    {
        return [
            'standards' => [
                'TS 18001 (İSG)' => ['compliance' => 87, 'last_audit' => '2024-08-15', 'next_audit' => '2025-08-15'],
                'ISO 9001 (Kalite)' => ['compliance' => 92, 'last_audit' => '2024-07-20', 'next_audit' => '2025-07-20'],
                'ISO 14001 (Çevre)' => ['compliance' => 89, 'last_audit' => '2024-09-01', 'next_audit' => '2025-09-01'],
                'ISO 31000 (Risk)' => ['compliance' => 85, 'last_audit' => '2024-06-10', 'next_audit' => '2025-06-10']
            ],
            'regulatory' => [
                'ÇED Raporu' => ['status' => 'approved', 'expiry' => '2025-03-15'],
                'İnşaat Ruhsatı' => ['status' => 'valid', 'expiry' => '2025-12-31'],
                'İşyeri Açma Ruhsatı' => ['status' => 'pending', 'expected' => '2024-11-15'],
                'SGK Primler' => ['status' => 'current', 'last_payment' => '2024-09-25']
            ]
        ];
    }

    private function generatePredictions()
    {
        return [
            'delay_prediction' => [
                'probability' => 23,
                'estimated_delay' => 15,
                'causes' => ['Hava koşulları', 'Malzeme gecikmesi', 'İş gücü eksikliği']
            ],
            'cost_prediction' => [
                'probability' => 18,
                'estimated_overrun' => 450000,
                'causes' => ['Enflasyon', 'Malzeme fiyat artışı', 'Değişiklik talepleri']
            ],
            'safety_prediction' => [
                'probability' => 5,
                'estimated_incidents' => 1,
                'preventive_measures' => ['Ek eğitim', 'Güvenlik denetimleri', 'Ekipman kontrolleri']
            ]
        ];
    }

    private function generateForecasts()
    {
        $forecasts = [];
        for ($i = 1; $i <= 6; $i++) {
            $date = Carbon::now()->addMonths($i);
            $forecasts[] = [
                'month' => $date->format('M Y'),
                'progress_forecast' => min(100, 78 + ($i * 3.5)),
                'cost_forecast' => 38700000 + ($i * 850000),
                'revenue_forecast' => 45200000 + ($i * 1200000)
            ];
        }
        return $forecasts;
    }
}