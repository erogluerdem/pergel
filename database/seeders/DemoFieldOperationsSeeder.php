<?php

namespace Database\Seeders;

use App\Models\DailyFieldReport;
use App\Models\DailyFieldReportEquipment;
use App\Models\DailyFieldReportLabour;
use App\Models\Equipment;
use App\Models\FieldMedia;
use App\Models\Project;
use App\Models\Vendor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoFieldOperationsSeeder extends Seeder
{
    public function run(): void
    {
        $projects = Project::all();
        if ($projects->isEmpty()) {
            return;
        }

        $vendors = Vendor::all();

        $weatherOptions = [
            ['temperature' => 24, 'description' => 'Açık & Güneşli', 'humidity' => 45, 'wind_speed' => 12, 'icon' => '01d'],
            ['temperature' => 18, 'description' => 'Parçalı Bulutlu', 'humidity' => 60, 'wind_speed' => 15, 'icon' => '02d'],
            ['temperature' => 15, 'description' => 'Hafif Yağmurlu', 'humidity' => 80, 'wind_speed' => 20, 'icon' => '10d'],
            ['temperature' => 28, 'description' => 'Sıcak & Rüzgarsız', 'humidity' => 35, 'wind_speed' => 8, 'icon' => '01d'],
        ];

        $trades = ['Kalıpçı Ekibi', 'Demirci Ekibi', 'Beton döküm ekibi', 'Mekanik Tesisatçı', 'Elektrik Teknisyeni', 'İskele & İzolasyon', 'Sıva & Boya Ustaları'];

        $equipmentsList = [
            ['equipment_type' => 'Kule Vinç #1', 'identifier' => 'KV-01', 'working_hours' => 8, 'idle_hours' => 1],
            ['equipment_type' => 'Paletli Ekskavatör', 'identifier' => 'EX-220', 'working_hours' => 7, 'idle_hours' => 2],
            ['equipment_type' => 'Beton Pompası (47m)', 'identifier' => 'BP-47', 'working_hours' => 6, 'idle_hours' => 0],
            ['equipment_type' => 'Jeneratör (350 kVA)', 'identifier' => 'GEN-350', 'working_hours' => 10, 'idle_hours' => 0],
            ['equipment_type' => 'Bekoloder Kepçe', 'identifier' => 'JCB-3CX', 'working_hours' => 8, 'idle_hours' => 1],
        ];

        $notesList = [
            'A-Blok 4. kat döşeme betonu (C40/50 - 320 m³) sorunsuz döküldü. Şantiye İSG denetimi yapıldı.',
            'B-Blok bodrum yalıtım koruma perdesi tamamlandı. Yağış nedeniyle saat 16:00 sonrası dış cephe çalışması durduruldu.',
            'Şantiyeye 2 tır donatı demiri (32 ton) teslim alındı. Kalite kontrol numuneleri çekildi.',
            'Kule vinç periyodik bakımı gerçekleştirildi. Elektrik altyapı kanal kazısı devam ediyor.',
            'MEP şaft geçişleri mimari onaylı projesine göre kontrol edildi. Taşeron puantajı onaylandı.',
        ];

        foreach ($projects as $project) {
            // Seed reports for past 7 days for each project
            for ($i = 0; $i < 7; $i++) {
                $reportDate = now()->subDays($i)->toDateString();

                if (DailyFieldReport::where('project_id', $project->id)->where('report_date', $reportDate)->exists()) {
                    continue;
                }

                DB::transaction(function () use ($project, $reportDate, $weatherOptions, $trades, $equipmentsList, $notesList, $vendors, $i) {
                    $weather = $weatherOptions[array_rand($weatherOptions)];
                    $note = $notesList[$i % count($notesList)];
                    $status = $i === 0 ? 'draft' : ($i % 3 === 0 ? 'approved' : 'submitted');

                    $report = DailyFieldReport::create([
                        'project_id' => $project->id,
                        'report_date' => $reportDate,
                        'author_id' => 1,
                        'notes' => $note,
                        'weather' => $weather,
                        'status' => $status,
                    ]);

                    // Seed 3-5 labours
                    $selectedTrades = array_rand($trades, 3);
                    foreach ($selectedTrades as $tIdx) {
                        DailyFieldReportLabour::create([
                            'report_id' => $report->id,
                            'subcontractor' => $vendors->isNotEmpty() ? $vendors->random()->name : 'Aksu İnşaat Ltd.',
                            'trade' => $trades[$tIdx],
                            'count' => rand(6, 18),
                            'hours' => rand(8, 10),
                        ]);
                    }

                    // Seed 2-3 equipments
                    $selectedEq = array_rand($equipmentsList, 2);
                    foreach ($selectedEq as $eIdx) {
                        $eq = $equipmentsList[$eIdx];
                        DailyFieldReportEquipment::create([
                            'report_id' => $report->id,
                            'equipment_type' => $eq['equipment_type'],
                            'identifier' => $eq['identifier'],
                            'working_hours' => $eq['working_hours'],
                            'idle_hours' => $eq['idle_hours'],
                        ]);
                    }

                    // Seed media
                    FieldMedia::create([
                        'project_id' => $project->id,
                        'report_id' => $report->id,
                        'uploader_id' => 1,
                        'original_name' => "saha_fotograf_" . ($i + 1) . ".jpg",
                        'path' => "field-media/saha_sample_" . ($i % 3 + 1) . ".jpg",
                        'mime' => 'image/jpeg',
                        'size' => rand(1500000, 3500000),
                        'gps_lat' => 39.9208 + (rand(-10, 10) / 1000),
                        'gps_lon' => 32.8541 + (rand(-10, 10) / 1000),
                    ]);
                });
            }
        }
    }
}
