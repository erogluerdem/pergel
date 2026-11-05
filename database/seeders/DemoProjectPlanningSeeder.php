<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\PlanningTask;
use Carbon\Carbon;

class DemoProjectPlanningSeeder extends Seeder
{
    public function run(): void
    {
        $projects = Project::all();

        if ($projects->isEmpty()) {
            $p1 = Project::create([
                'name' => 'Vadi Panorama Evleri & Ticaret Merkezi',
                'code' => 'PRJ-2025-01',
                'description' => 'Karma yaşam projesi, 240 konut ve 30 ticari alan.',
                'status' => 'active',
                'start_date' => Carbon::now()->subMonths(2),
                'end_date' => Carbon::now()->addMonths(12),
            ]);
            $projects = collect([$p1]);
        }

        foreach ($projects as $project) {
            // Delete existing demo tasks if any to seed clean structure
            PlanningTask::where('project_id', $project->id)->delete();

            $baseDate = Carbon::now()->subDays(30);

            // Phase 1: Şantiye Kurulumu & Hafriyat (Completed)
            $t1 = PlanningTask::create([
                'project_id' => $project->id,
                'name' => '1.1 Mobilizasyon ve Şantiye Kurulumu',
                'start_date' => $baseDate->copy(),
                'end_date' => $baseDate->copy()->addDays(10),
                'duration' => 10,
                'progress' => 100,
                'responsible' => 'Ahmet Yılmaz (Saha Şefi)',
                'notes' => 'Konteynerler ve jeneratör alanları kuruldu, çevre çiti çekildi.',
                'budget_wbs' => 'WBS-1.1-MOBIL',
                'critical_path' => true,
                'resources' => [
                    'human' => [['role' => 'Formen', 'count' => 2, 'hours' => 80]],
                    'equipment' => [['name' => 'Jeneratör 150kVA', 'count' => 1]]
                ]
            ]);

            $t2 = PlanningTask::create([
                'project_id' => $project->id,
                'name' => '1.2 Zemin Kazısı ve Hafriyat Atımı',
                'start_date' => $baseDate->copy()->addDays(8),
                'end_date' => $baseDate->copy()->addDays(24),
                'duration' => 16,
                'progress' => 100,
                'responsible' => 'Mehmet Demir (Hafriyat Mühendisi)',
                'notes' => 'Sert kaya kazısı ve kamyon sevkiyatı tamamlandı.',
                'budget_wbs' => 'WBS-1.2-EXCAV',
                'dependencies' => [$t1->id],
                'critical_path' => true,
                'resources' => [
                    'human' => [['role' => 'Operatör', 'count' => 4, 'hours' => 160]],
                    'equipment' => [['name' => 'Ekskavatör CAT 336', 'count' => 2], ['name' => 'Damperli Kamyon', 'count' => 6]]
                ]
            ]);

            // Phase 2: Temel ve Betonarme (In Progress)
            $t3 = PlanningTask::create([
                'project_id' => $project->id,
                'name' => '2.1 Grobeton Dökümü ve Temel İzolasyonu',
                'start_date' => $baseDate->copy()->addDays(25),
                'end_date' => $baseDate->copy()->addDays(32),
                'duration' => 7,
                'progress' => 90,
                'responsible' => 'Serkan Kara (İzolasyon Sorumlusu)',
                'notes' => 'Membran su yalıtımı ve koruma betonu uygulaması.',
                'budget_wbs' => 'WBS-2.1-INSUL',
                'dependencies' => [$t2->id],
                'critical_path' => true,
                'resources' => [
                    'human' => [['role' => 'Yalıtım Ustası', 'count' => 5, 'hours' => 140]],
                    'equipment' => [['name' => 'Beton Pompası 47m', 'count' => 1]]
                ]
            ]);

            $t4 = PlanningTask::create([
                'project_id' => $project->id,
                'name' => '2.2 Radye Temel Demir Örümü ve Betonaj',
                'start_date' => $baseDate->copy()->addDays(33),
                'end_date' => $baseDate->copy()->addDays(48),
                'duration' => 15,
                'progress' => 45,
                'responsible' => 'Mustafa Avcı (Statik Şefi)',
                'notes' => '1200m3 C35 hazır beton döküm organizasyonu yapılıyor.',
                'budget_wbs' => 'WBS-2.2-FOUND',
                'dependencies' => [$t3->id],
                'critical_path' => true,
                'resources' => [
                    'human' => [['role' => 'Demirci Ustası', 'count' => 18, 'hours' => 360]],
                    'equipment' => [['name' => 'Kule Vinç Potain', 'count' => 1], ['name' => 'Transmikser', 'count' => 8]]
                ]
            ]);

            $t5 = PlanningTask::create([
                'project_id' => $project->id,
                'name' => '2.3 Bodrum Kat Perde ve Kolon İmalatları',
                'start_date' => $baseDate->copy()->addDays(49),
                'end_date' => $baseDate->copy()->addDays(65),
                'duration' => 16,
                'progress' => 10,
                'responsible' => 'Mustafa Avcı (Statik Şefi)',
                'notes' => 'Tırmanır kalıp sistemleri şantiyeye indirildi.',
                'budget_wbs' => 'WBS-2.3-BASEMENT',
                'dependencies' => [$t4->id],
                'critical_path' => true,
                'resources' => [
                    'human' => [['role' => 'Kalıpçı Ustası', 'count' => 14, 'hours' => 280]]
                ]
            ]);

            // Phase 3: İnce İşler & Tesisat (Upcoming)
            $t6 = PlanningTask::create([
                'project_id' => $project->id,
                'name' => '3.1 Kat Tesisat Şaftları ve Mekanik Borulama',
                'start_date' => $baseDate->copy()->addDays(66),
                'end_date' => $baseDate->copy()->addDays(85),
                'duration' => 19,
                'progress' => 0,
                'responsible' => 'Engin Şahin (MEP Mühendisi)',
                'notes' => 'Yangın ve temiz su ana hat borulama çalışmaları.',
                'budget_wbs' => 'WBS-3.1-MECH',
                'dependencies' => [$t5->id],
                'critical_path' => false,
                'resources' => [
                    'human' => [['role' => 'Tesisatçı Ustası', 'count' => 8, 'hours' => 200]]
                ]
            ]);

            $t7 = PlanningTask::create([
                'project_id' => $project->id,
                'name' => '3.2 Cephe Giydirme ve Kompozit Panel',
                'start_date' => $baseDate->copy()->addDays(80),
                'end_date' => $baseDate->copy()->addDays(110),
                'duration' => 30,
                'progress' => 0,
                'responsible' => 'Cihan Aslan (Cephe Sorumlusu)',
                'notes' => 'Isı yalıtımlı silikon alüminyum giydirme cephe.',
                'budget_wbs' => 'WBS-3.2-FACADE',
                'dependencies' => [$t5->id],
                'critical_path' => true,
                'resources' => [
                    'human' => [['role' => 'Cephe Montaj Ustası', 'count' => 10, 'hours' => 300]],
                    'equipment' => [['name' => 'Dış Cephe Asansörü', 'count' => 2]]
                ]
            ]);
        }
    }
}
