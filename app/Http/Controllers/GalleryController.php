<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class GalleryController extends Controller
{
    /**
     * Display the gallery index.
     */
    public function index(): View
    {
        $images = [
            (object)[
                'id' => 1,
                'title' => 'Proje A - Başlangıç',
                'description' => 'Proje A\'nın başlangıç aşaması fotoğrafları',
                'url' => '/images/gallery/project-a-start.jpg',
                'thumbnail' => '/images/gallery/thumbs/project-a-start.jpg',
                'category' => 'projeler',
                'date' => '2025-01-15',
                'tags' => ['proje', 'başlangıç', 'inşaat']
            ],
            (object)[
                'id' => 2,
                'title' => 'Takım Toplantısı',
                'description' => 'Aylık değerlendirme toplantısı',
                'url' => '/images/gallery/team-meeting.jpg',
                'thumbnail' => '/images/gallery/thumbs/team-meeting.jpg',
                'category' => 'etkinlikler',
                'date' => '2025-01-10',
                'tags' => ['toplantı', 'takım', 'değerlendirme']
            ],
            (object)[
                'id' => 3,
                'title' => 'Ofis Yenileme',
                'description' => 'Yeni ofis alanlarının düzenlenmesi',
                'url' => '/images/gallery/office-renovation.jpg',
                'thumbnail' => '/images/gallery/thumbs/office-renovation.jpg',
                'category' => 'ofis',
                'date' => '2025-01-08',
                'tags' => ['ofis', 'yenileme', 'tasarım']
            ],
            (object)[
                'id' => 4,
                'title' => 'Proje B - Tamamlanma',
                'description' => 'Proje B\'nin başarılı şekilde tamamlanması',
                'url' => '/images/gallery/project-b-completion.jpg',
                'thumbnail' => '/images/gallery/thumbs/project-b-completion.jpg',
                'category' => 'projeler',
                'date' => '2025-01-05',
                'tags' => ['proje', 'tamamlama', 'başarı']
            ],
            (object)[
                'id' => 5,
                'title' => 'Teknoloji Semineri',
                'description' => 'Yeni teknolojiler hakkında eğitim semineri',
                'url' => '/images/gallery/tech-seminar.jpg',
                'thumbnail' => '/images/gallery/thumbs/tech-seminar.jpg',
                'category' => 'etkinlikler',
                'date' => '2025-01-03',
                'tags' => ['teknoloji', 'eğitim', 'seminer']
            ],
            (object)[
                'id' => 6,
                'title' => 'Yıl Sonu Kutlaması',
                'description' => '2024 yılının başarılarını kutlama etkinliği',
                'url' => '/images/gallery/year-end-celebration.jpg',
                'thumbnail' => '/images/gallery/thumbs/year-end-celebration.jpg',
                'category' => 'etkinlikler',
                'date' => '2024-12-30',
                'tags' => ['kutlama', 'yıl sonu', 'başarı']
            ]
        ];

        $categories = [
            'tumu' => 'Tümü',
            'projeler' => 'Projeler',
            'etkinlikler' => 'Etkinlikler',
            'ofis' => 'Ofis'
        ];

        // Gallery klasörleri
        $folders = [
            [
                'name' => 'Projeler',
                'path' => 'projeler',
                'icon' => 'fa-folder',
                'color' => 'text-primary',
                'count' => 12,
                'item_count' => 12,
                'created_at' => '2025-01-01',
                'modified_date' => '15 Oca 2025'
            ],
            [
                'name' => 'Etkinlikler',
                'path' => 'etkinlikler', 
                'icon' => 'fa-folder',
                'color' => 'text-success',
                'count' => 8,
                'item_count' => 8,
                'created_at' => '2025-01-05',
                'modified_date' => '10 Oca 2025'
            ],
            [
                'name' => 'Ofis',
                'path' => 'ofis',
                'icon' => 'fa-folder',
                'color' => 'text-warning',
                'count' => 5,
                'item_count' => 5,
                'created_at' => '2025-01-10',
                'modified_date' => '12 Oca 2025'
            ],
            [
                'name' => 'Arşiv',
                'path' => 'arsiv',
                'icon' => 'fa-folder',
                'color' => 'text-secondary',
                'count' => 25,
                'item_count' => 25,
                'created_at' => '2024-12-15',
                'modified_date' => '30 Ara 2024'
            ]
        ];

        // Gallery dosyaları (images'dan farklı format)
        $files = [
            [
                'id' => 1,
                'name' => 'project-a-start.jpg',
                'type' => 'image',
                'size' => '2.5 MB',
                'url' => '/images/gallery/project-a-start.jpg',
                'thumbnail' => '/images/gallery/thumbs/project-a-start.jpg',
                'download_url' => '/images/gallery/project-a-start.jpg',
                'created_at' => '2025-01-15',
                'date' => '15 Oca',
                'dimensions' => '1920x1080',
                'icon' => 'fa-image',
                'color' => 'primary',
                'extension' => 'jpg'
            ],
            [
                'id' => 2,
                'name' => 'team-meeting.jpg',
                'type' => 'image',
                'size' => '1.8 MB',
                'url' => '/images/gallery/team-meeting.jpg',
                'thumbnail' => '/images/gallery/thumbs/team-meeting.jpg',
                'download_url' => '/images/gallery/team-meeting.jpg',
                'created_at' => '2025-01-10',
                'date' => '10 Oca',
                'dimensions' => '1280x720',
                'icon' => 'fa-image',
                'color' => 'success',
                'extension' => 'jpg'
            ],
            [  
                'id' => 3,
                'name' => 'office-renovation.jpg',
                'type' => 'image',
                'size' => '3.2 MB',
                'url' => '/images/gallery/office-renovation.jpg',
                'thumbnail' => '/images/gallery/thumbs/office-renovation.jpg',
                'download_url' => '/images/gallery/office-renovation.jpg',
                'created_at' => '2025-01-08',
                'date' => '8 Oca',
                'dimensions' => '2560x1440',
                'icon' => 'fa-image',
                'color' => 'warning',
                'extension' => 'jpg'
            ],
            [
                'id' => 4,
                'name' => 'project-report.pdf',
                'type' => 'document',
                'size' => '1.2 MB',
                'url' => '/documents/project-report.pdf',
                'thumbnail' => '/images/gallery/thumbs/pdf-icon.png',
                'download_url' => '/documents/project-report.pdf',
                'created_at' => '2025-01-05',
                'date' => '5 Oca',
                'dimensions' => null,
                'icon' => 'fa-file-pdf',
                'color' => 'danger',
                'extension' => 'pdf'
            ],
            [
                'id' => 5,
                'name' => 'presentation.pptx',
                'type' => 'document',
                'size' => '5.8 MB',
                'url' => '/documents/presentation.pptx',
                'thumbnail' => '/images/gallery/thumbs/pptx-icon.png',
                'download_url' => '/documents/presentation.pptx',
                'created_at' => '2025-01-03',
                'date' => '3 Oca',
                'dimensions' => null,
                'icon' => 'fa-file-powerpoint',
                'color' => 'info',
                'extension' => 'pptx'
            ]
        ];        // Gallery istatistikleri
        $stats = [
            'total_files' => count($images),
            'images' => count(array_filter($images, function($img) {
                return in_array('proje', $img->tags) || in_array('ofis', $img->tags);
            })),
            'videos' => 0, // Şu anda video yok
            'documents' => count(array_filter($images, function($img) {
                return in_array('toplantı', $img->tags) || in_array('eğitim', $img->tags);
            })),
            'recent_uploads' => count(array_filter($images, function($img) {
                return strtotime($img->date) > strtotime('-7 days');
            })),
            'categories_count' => count($categories) - 1, // 'tumu' hariç
            'storage_used' => '2.5 GB', // Örnek veri
            'storage_total' => '10 GB', // Örnek veri
            'total_size' => '2.5 GB' // View'da kullanılan değer
        ];

        return view('gallery.index', compact('images', 'categories', 'stats', 'folders', 'files'));
    }
}