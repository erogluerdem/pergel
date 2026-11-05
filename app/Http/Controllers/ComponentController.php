<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class ComponentController extends Controller
{
    /**
     * Display the components index.
     */
    public function index(): View
    {
        $components = [
            [
                'category' => 'Form Bileşenleri',
                'items' => [
                    (object)[
                        'name' => 'Gelişmiş Tarih Seçici',
                        'description' => 'Türkçe dil desteği ile tarih ve saat seçimi',
                        'preview' => 'date-picker-preview.png',
                        'usage' => 'forms, scheduling'
                    ],
                    (object)[
                        'name' => 'Çoklu Seçim Dropdown',
                        'description' => 'Arama ve filtreleme özellikli dropdown',
                        'preview' => 'multi-select-preview.png',
                        'usage' => 'forms, filtering'
                    ],
                    (object)[
                        'name' => 'Dosya Yükleme',
                        'description' => 'Sürükle-bırak özellikli dosya yükleme',
                        'preview' => 'file-upload-preview.png',
                        'usage' => 'documents, media'
                    ]
                ]
            ],
            [
                'category' => 'Veri Görselleştirme',
                'items' => [
                    (object)[
                        'name' => 'Pasta Grafik',
                        'description' => 'Animasyonlu ve etkileşimli pasta grafikleri',
                        'preview' => 'pie-chart-preview.png',
                        'usage' => 'analytics, reports'
                    ],
                    (object)[
                        'name' => 'Çubuk Grafik',
                        'description' => 'Karşılaştırmalı veri analizi için çubuk grafikler',
                        'preview' => 'bar-chart-preview.png',
                        'usage' => 'analytics, comparison'
                    ],
                    (object)[
                        'name' => 'Zaman Çizelgesi',
                        'description' => 'Proje timeline ve milestone görüntüleme',
                        'preview' => 'timeline-preview.png',
                        'usage' => 'projects, planning'
                    ]
                ]
            ],
            [
                'category' => 'Navigasyon',
                'items' => [
                    (object)[
                        'name' => 'Breadcrumb',
                        'description' => 'Hiyerarşik navigasyon bileşeni',
                        'preview' => 'breadcrumb-preview.png',
                        'usage' => 'navigation, structure'
                    ],
                    (object)[
                        'name' => 'Sidebar Menü',
                        'description' => 'Daraltılabilir yan menü bileşeni',
                        'preview' => 'sidebar-preview.png',
                        'usage' => 'navigation, layout'
                    ],
                    (object)[
                        'name' => 'Tab Navigasyon',
                        'description' => 'Sekme tabanlı içerik organizasyonu',
                        'preview' => 'tabs-preview.png',
                        'usage' => 'content, organization'
                    ]
                ]
            ],
            [
                'category' => 'Bildirimler',
                'items' => [
                    (object)[
                        'name' => 'Toast Bildirimleri',
                        'description' => 'Otomatik kapanan bildirim mesajları',
                        'preview' => 'toast-preview.png',
                        'usage' => 'feedback, alerts'
                    ],
                    (object)[
                        'name' => 'Modal Pencereler',
                        'description' => 'Özelleştirilebilir popup pencereler',
                        'preview' => 'modal-preview.png',
                        'usage' => 'dialogs, forms'
                    ],
                    (object)[
                        'name' => 'İlerleme Çubukları',
                        'description' => 'Görev ve proje ilerleme göstergeleri',
                        'preview' => 'progress-preview.png',
                        'usage' => 'progress, loading'
                    ]
                ]
            ]
        ];

        return view('components.index', compact('components'));
    }
}