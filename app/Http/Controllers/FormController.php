<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class FormController extends Controller
{
    /**
     * Display the form wizard.
     */
    public function wizard(): View
    {
        $wizardSteps = [
            (object)[
                'id' => 1,
                'title' => 'Temel Bilgiler',
                'description' => 'Proje veya görev ile ilgili temel bilgileri girin',
                'fields' => [
                    'name' => 'Proje/Görev Adı',
                    'description' => 'Açıklama',
                    'category' => 'Kategori',
                    'priority' => 'Öncelik Seviyesi'
                ]
            ],
            (object)[
                'id' => 2,
                'title' => 'Zaman Planlaması',
                'description' => 'Başlangıç ve bitiş tarihlerini belirleyin',
                'fields' => [
                    'start_date' => 'Başlangıç Tarihi',
                    'end_date' => 'Bitiş Tarihi',
                    'estimated_hours' => 'Tahmini Süre (Saat)',
                    'milestones' => 'Kilometre Taşları'
                ]
            ],
            (object)[
                'id' => 3,
                'title' => 'Atama ve Sorumluluk',
                'description' => 'Görevlileri ve sorumlulukları belirleyin',
                'fields' => [
                    'assigned_to' => 'Atanan Kişi',
                    'team_members' => 'Takım Üyeleri',
                    'supervisor' => 'Denetleyici',
                    'stakeholders' => 'Paydaşlar'
                ]
            ],
            (object)[
                'id' => 4,
                'title' => 'Bütçe ve Kaynaklar',
                'description' => 'Finansal ve kaynak planlaması yapın',
                'fields' => [
                    'budget' => 'Bütçe',
                    'resources' => 'Gerekli Kaynaklar',
                    'equipment' => 'Ekipman İhtiyaçları',
                    'external_services' => 'Dış Hizmetler'
                ]
            ],
            (object)[
                'id' => 5,
                'title' => 'Onay ve Kontrol',
                'description' => 'Bilgileri gözden geçirin ve onaylayın',
                'fields' => [
                    'review' => 'Bilgi Kontrolü',
                    'approval' => 'Onay Süreci',
                    'notifications' => 'Bildirim Ayarları',
                    'documentation' => 'Dokümantasyon'
                ]
            ]
        ];

        $formTemplates = [
            (object)[
                'name' => 'Yeni Proje Oluşturma',
                'description' => 'Kapsamlı proje oluşturma sihirbazı',
                'icon' => 'fas fa-project-diagram',
                'estimated_time' => '10-15 dakika'
            ],
            (object)[
                'name' => 'Görev Atama',
                'description' => 'Detaylı görev atama ve takip formu',
                'icon' => 'fas fa-tasks',
                'estimated_time' => '5-8 dakika'
            ],
            (object)[
                'name' => 'Müşteri Kaydı',
                'description' => 'Yeni müşteri bilgileri kayıt formu',
                'icon' => 'fas fa-user-plus',
                'estimated_time' => '8-12 dakika'
            ],
            (object)[
                'name' => 'Fatura Oluşturma',
                'description' => 'Adım adım fatura hazırlama sihirbazı',
                'icon' => 'fas fa-file-invoice',
                'estimated_time' => '6-10 dakika'
            ]
        ];

        return view('forms.wizard', compact('wizardSteps', 'formTemplates'));
    }
}