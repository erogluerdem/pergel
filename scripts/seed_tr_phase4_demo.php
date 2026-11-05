<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\SiteCamera;
use App\Models\SubcontractorScore;
use App\Models\HrPersonnel;
use App\Models\DynamicReport;

echo "Seeding Phase 4 Demo Data...\n";

// 1. SiteCamera
SiteCamera::truncate();
SiteCamera::create([
    'camera_name' => 'Kule Vinç-1 Tepe Kamerası',
    'location' => 'A Blok Şantiye Sahası',
    'stream_url' => 'rtsp://demo-cam-1.local/stream',
    'status' => 'online'
]);
SiteCamera::create([
    'camera_name' => 'Ana Giriş Nizamiyesi',
    'location' => 'Güvenlik Kapısı',
    'stream_url' => 'rtsp://demo-cam-2.local/stream',
    'status' => 'online'
]);
SiteCamera::create([
    'camera_name' => 'B Blok Döküm Alanı',
    'location' => 'B Blok Arka',
    'stream_url' => 'rtsp://demo-cam-3.local/stream',
    'status' => 'offline'
]);

// 2. SubcontractorScore
SubcontractorScore::truncate();
SubcontractorScore::create([
    'subcontractor_name' => 'Yıldızlar Kalıp İnşaat',
    'ai_reliability_score' => 95,
    'safety_score' => 88,
    'speed_score' => 92,
    'total_score' => 92,
    'grade' => 'A+',
    'system_comment' => 'Güvenilir taşeron, planlamaya sadık kalıyor. İSG ihlali yok.'
]);
SubcontractorScore::create([
    'subcontractor_name' => 'Akıncı Elektrik',
    'ai_reliability_score' => 65,
    'safety_score' => 70,
    'speed_score' => 85,
    'total_score' => 73,
    'grade' => 'C',
    'system_comment' => 'Hakediş beyanlarında tutarsızlık var. İSG tarafında baret takmama uyarıları aldı.'
]);

// 3. HrPersonnel
HrPersonnel::truncate();
HrPersonnel::create([
    'full_name' => 'Mehmet Yılmaz',
    'position' => 'Proje Müdürü',
    'hire_date' => '2020-05-15',
    'remaining_leaves' => 14,
    'salary' => 125000.00,
    'status' => 'aktif'
]);
HrPersonnel::create([
    'full_name' => 'Ayşe Demir',
    'position' => 'Şantiye Şefi',
    'hire_date' => '2022-08-10',
    'remaining_leaves' => 4,
    'salary' => 85000.00,
    'status' => 'aktif'
]);
HrPersonnel::create([
    'full_name' => 'Kemal Öztürk',
    'position' => 'İSG Uzmanı',
    'hire_date' => '2023-01-20',
    'remaining_leaves' => 21,
    'salary' => 75000.00,
    'status' => 'aktif'
]);

// 4. DynamicReport
DynamicReport::truncate();
DynamicReport::create([
    'report_title' => '2026 Temmuz Ayı Konsolide Hakediş Raporu',
    'report_type' => 'Finans & Hakediş',
    'generated_by' => 'Sistem (Otomatik)',
    'file_format' => 'PDF',
    'file_url' => '/downloads/reports/temmuz_hakedis.pdf'
]);
DynamicReport::create([
    'report_title' => 'Taşeron Risk ve Skorlama Analizi',
    'report_type' => 'Yönetim Özeti',
    'generated_by' => 'Mehmet Yılmaz',
    'file_format' => 'EXCEL',
    'file_url' => '/downloads/reports/taseron_risk.xlsx'
]);
DynamicReport::create([
    'report_title' => 'MoTAT Çevre Atık Beyannamesi (Q2)',
    'report_type' => 'Çevre Yönetimi',
    'generated_by' => 'Sistem (Otomatik)',
    'file_format' => 'PDF',
    'file_url' => '/downloads/reports/motat_q2.pdf'
]);

echo "Done.\n";
