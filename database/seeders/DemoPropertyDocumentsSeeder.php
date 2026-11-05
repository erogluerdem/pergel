<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PropertyDocument;
use App\Models\Project;
use App\Models\Asset;
use Carbon\Carbon;

class DemoPropertyDocumentsSeeder extends Seeder
{
    public function run()
    {
        // Truncate or clean existing property documents
        PropertyDocument::query()->forceDelete();

        $project = Project::first();
        $asset = Asset::first();

        $projectId = $project ? $project->id : null;
        $assetId = $asset ? $asset->id : null;

        $documents = [
            [
                'project_id' => $projectId,
                'asset_id' => $assetId,
                'document_type' => 'tapu',
                'title' => 'Kat Mülkiyeti Müstakil Tapu Senedi — Blok A Daire 14',
                'description' => 'Kadıköy Tapu Müdürlüğü onaylı 1/1 hisseli Kat Mülkiyeti Tapu Senedi aslı dijital kopyası.',
                'document_number' => 'TP-2025/89412',
                'issue_date' => Carbon::now()->subMonths(6)->toDateString(),
                'expiry_date' => null,
                'status' => 'processed',
                'issued_by' => 'Kadıköy Tapu Müdürlüğü',
                'document_cost' => 18500.00,
                'file_name' => 'kat_mulkiyeti_tapu_a14.pdf',
                'file_path' => 'demo/tapu_a14.pdf',
                'is_critical' => true,
                'requires_renewal' => false,
            ],
            [
                'project_id' => $projectId,
                'asset_id' => $assetId,
                'document_type' => 'mesken',
                'title' => 'Yapı Kullanma İzin Belgesi (İskan / Mesken Ruhsatı)',
                'description' => 'Belediye İmar Müdürlüğü onaylı yapı genel kullanım iskan belgesi ve bağımsız bölüm onayları.',
                'document_number' => 'MSK-2024/00412',
                'issue_date' => Carbon::now()->subMonths(14)->toDateString(),
                'expiry_date' => null,
                'status' => 'processed',
                'issued_by' => 'İstanbul Büyükşehir Belediyesi İmar Dairesi',
                'document_cost' => 45000.00,
                'file_name' => 'iskan_mesken_ruhsati_2024.pdf',
                'file_path' => 'demo/iskan_mesken_ruhsati.pdf',
                'is_critical' => true,
                'requires_renewal' => false,
            ],
            [
                'project_id' => $projectId,
                'asset_id' => $assetId,
                'document_type' => 'imar',
                'title' => 'Güncel İmar Durum Belgesi & Çap Raporu (1/1000)',
                'description' => 'Parsel emsal oranları (KAKS: 2.07, TAKS: 0.35) ve Hmax yükseklik sınırı raporu.',
                'document_number' => 'IMR-2025/11029',
                'issue_date' => Carbon::now()->subMonths(2)->toDateString(),
                'expiry_date' => Carbon::now()->addMonths(10)->toDateString(),
                'status' => 'received',
                'issued_by' => 'Beşiktaş Belediyesi Şehir Planlama Müd.',
                'document_cost' => 6200.00,
                'file_name' => 'imar_durum_cap_2025.pdf',
                'file_path' => 'demo/imar_durum_cap.pdf',
                'is_critical' => true,
                'requires_renewal' => true,
            ],
            [
                'project_id' => $projectId,
                'asset_id' => $assetId,
                'document_type' => 'yapı_ruhsat',
                'title' => 'Mimari Proje Yapı İnşaat Ruhsatı',
                'description' => 'Çevre ve Şehircilik Bakanlığı normlarına uygun 12.500 m² toplam inşaat alanı ruhsatı.',
                'document_number' => 'RHS-2023/9910',
                'issue_date' => Carbon::now()->subYears(2)->toDateString(),
                'expiry_date' => Carbon::now()->addMonths(1)->toDateString(),
                'status' => 'received',
                'issued_by' => 'Şişli Belediyesi İmar ve Şehircilik Müd.',
                'document_cost' => 128000.00,
                'file_name' => 'yapi_insaat_ruhsati.pdf',
                'file_path' => 'demo/yapi_insaat_ruhsati.pdf',
                'is_critical' => true,
                'requires_renewal' => true,
            ],
            [
                'project_id' => $projectId,
                'asset_id' => $assetId,
                'document_type' => 'elektrik',
                'title' => 'Trafo & Elektrik Tesisat Uygunluk Raporu (TEDAŞ/BEDAŞ)',
                'description' => 'Yüksek gerilim trafo binası ve dikey şaft ana tablo periyodik topraklama muayene raporu.',
                'document_number' => 'ELK-2024/7741',
                'issue_date' => Carbon::now()->subMonths(11)->toDateString(),
                'expiry_date' => Carbon::now()->subDays(5)->toDateString(), // Expired
                'status' => 'expired',
                'issued_by' => 'BEDAŞ Elektrik Dağıtım A.Ş.',
                'document_cost' => 9500.00,
                'file_name' => 'elektrik_topraklama_raporu.pdf',
                'file_path' => 'demo/elektrik_topraklama_raporu.pdf',
                'is_critical' => true,
                'requires_renewal' => true,
            ],
            [
                'project_id' => $projectId,
                'asset_id' => $assetId,
                'document_type' => 'doğalgaz',
                'title' => 'İGDAŞ Merkezi Isıtma Kaskad Tesisat Onay Projesi',
                'description' => '2x 450 kW kaskad kombi sistemi gaz açım ve güvenlik bacası uygunluk onay belgesi.',
                'document_number' => 'IGD-2025/3301',
                'issue_date' => Carbon::now()->subMonths(4)->toDateString(),
                'expiry_date' => Carbon::now()->addMonths(8)->toDateString(),
                'status' => 'processed',
                'issued_by' => 'İGDAŞ Proje Onay Dairesi',
                'document_cost' => 14200.00,
                'file_name' => 'igdas_kaskad_onay_projesi.pdf',
                'file_path' => 'demo/igdas_kaskad_onay_projesi.pdf',
                'is_critical' => false,
                'requires_renewal' => false,
            ],
            [
                'project_id' => $projectId,
                'asset_id' => $assetId,
                'document_type' => 'su',
                'title' => 'İSKİ Şebeke Bağlantı & Hidrofor Başvuru Evrakları',
                'description' => 'Temiz su ve atık su kanal şebekesi deşarj izin belgesi aslı.',
                'document_number' => 'ISK-2025/00912',
                'issue_date' => Carbon::now()->subDays(15)->toDateString(),
                'expiry_date' => Carbon::now()->addYears(2)->toDateString(),
                'status' => 'pending',
                'issued_by' => 'İSKİ Genel Müdürlüğü',
                'document_cost' => 8400.00,
                'file_name' => 'iski_sebeke_baglanti.pdf',
                'file_path' => 'demo/iski_sebeke_baglanti.pdf',
                'is_critical' => false,
                'requires_renewal' => true,
            ],
        ];

        foreach ($documents as $doc) {
            PropertyDocument::create($doc);
        }
    }
}
