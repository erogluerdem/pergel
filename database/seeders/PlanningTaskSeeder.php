<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\PlanningTask;
use Carbon\Carbon;

class PlanningTaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get first project or create Gümüşler Villa Projesi if not exists
        $project = Project::first();
        if (!$project) {
            $project = Project::create([
                'name' => 'Gümüşler Villa Projesi',
                'description' => 'Niğde Gümüşler bölgesinde lüks villa inşaatı',
                'status' => 'planning',
                'start_date' => Carbon::now()->subMonths(1),
                'end_date' => Carbon::now()->addMonths(12),
                'budget' => 15000000,
                'spent' => 0,
                'progress_percent' => 0,
            ]);
        }

        // Clear existing tasks for this project
        PlanningTask::where('project_id', $project->id)->delete();

        // Create sample tasks
        $tasks = [
            [
                'name' => 'Proje Başlangıç ve İzinler',
                'start_date' => Carbon::now()->subMonths(2),
                'end_date' => Carbon::now()->subMonths(2)->addWeeks(2),
                'duration' => 14,
                'progress' => 100,
                'responsible' => 'Proje Müdürü',
                'notes' => 'Belediye izinleri ve proje onayları',
                'resources' => [
                    ['role' => 'Proje Müdürü', 'count' => 1, 'hours' => 80],
                    ['role' => 'Hukuk Müşaviri', 'count' => 1, 'hours' => 40],
                ],
                'critical_path' => true,
            ],
            [
                'name' => 'Saha Hazırlığı ve Kazı İşleri',
                'start_date' => Carbon::now()->subMonths(2)->addWeeks(2),
                'end_date' => Carbon::now()->subMonths(1),
                'duration' => 21,
                'progress' => 100,
                'responsible' => 'Saha Şefi',
                'notes' => 'Temel kazısı ve saha düzenlemesi',
                'resources' => [
                    ['role' => 'Saha Şefi', 'count' => 1, 'hours' => 160],
                    ['role' => 'İşçi', 'count' => 8, 'hours' => 1280],
                    ['role' => 'Makinist', 'count' => 2, 'hours' => 320],
                ],
                'critical_path' => true,
            ],
            [
                'name' => 'Temel ve Zemin Güçlendirme',
                'start_date' => Carbon::now()->subMonths(1),
                'end_date' => Carbon::now()->subWeeks(2),
                'duration' => 14,
                'progress' => 90,
                'responsible' => 'İnşaat Mühendisi',
                'notes' => 'Betonarme temel atılması',
                'resources' => [
                    ['role' => 'İnşaat Mühendisi', 'count' => 1, 'hours' => 112],
                    ['role' => 'Betoncu', 'count' => 6, 'hours' => 672],
                    ['role' => 'Demir İşçisi', 'count' => 4, 'hours' => 448],
                ],
                'critical_path' => true,
            ],
            [
                'name' => 'Zemin Kat Kolon ve Kiriş İmalatı',
                'start_date' => Carbon::now()->subWeeks(2),
                'end_date' => Carbon::now()->addWeeks(2),
                'duration' => 28,
                'progress' => 45,
                'responsible' => 'Yapı Mühendisi',
                'notes' => 'Zemin kat taşıyıcı sistem',
                'resources' => [
                    ['role' => 'Yapı Mühendisi', 'count' => 1, 'hours' => 224],
                    ['role' => 'Betoncu', 'count' => 8, 'hours' => 1792],
                    ['role' => 'Demir İşçisi', 'count' => 6, 'hours' => 1344],
                ],
                'critical_path' => true,
            ],
            [
                'name' => '1. Kat Yapı İnşaatı',
                'start_date' => Carbon::now()->addWeeks(1),
                'end_date' => Carbon::now()->addWeeks(6),
                'duration' => 35,
                'progress' => 0,
                'responsible' => 'Yapı Mühendisi',
                'notes' => 'Birinci kat kolon, kiriş ve döşeme',
                'resources' => [
                    ['role' => 'Yapı Mühendisi', 'count' => 1, 'hours' => 280],
                    ['role' => 'Betoncu', 'count' => 8, 'hours' => 2240],
                    ['role' => 'Demir İşçisi', 'count' => 6, 'hours' => 1680],
                ],
                'critical_path' => true,
            ],
            [
                'name' => 'Elektrik Alt Yapı İşleri',
                'start_date' => Carbon::now()->addWeeks(3),
                'end_date' => Carbon::now()->addWeeks(8),
                'duration' => 35,
                'progress' => 0,
                'responsible' => 'Elektrik Mühendisi',
                'notes' => 'Elektrik tesisatı altyapı döşemesi',
                'resources' => [
                    ['role' => 'Elektrik Mühendisi', 'count' => 1, 'hours' => 140],
                    ['role' => 'Elektrikçi', 'count' => 4, 'hours' => 560],
                ],
                'dependencies' => [], // Will be set after task creation
            ],
            [
                'name' => 'Sıhhi Tesisat Alt Yapı',
                'start_date' => Carbon::now()->addWeeks(3),
                'end_date' => Carbon::now()->addWeeks(7),
                'duration' => 28,
                'progress' => 0,
                'responsible' => 'Makine Mühendisi',
                'notes' => 'Su ve kanalizasyon tesisatı',
                'resources' => [
                    ['role' => 'Makine Mühendisi', 'count' => 1, 'hours' => 112],
                    ['role' => 'Tesisatçı', 'count' => 3, 'hours' => 336],
                ],
            ],
            [
                'name' => '2. Kat Yapı İnşaatı',
                'start_date' => Carbon::now()->addWeeks(5),
                'end_date' => Carbon::now()->addWeeks(10),
                'duration' => 35,
                'progress' => 0,
                'responsible' => 'Yapı Mühendisi',
                'notes' => 'İkinci kat kolon, kiriş ve döşeme',
                'resources' => [
                    ['role' => 'Yapı Mühendisi', 'count' => 1, 'hours' => 280],
                    ['role' => 'Betoncu', 'count' => 8, 'hours' => 2240],
                    ['role' => 'Demir İşçisi', 'count' => 6, 'hours' => 1680],
                ],
                'critical_path' => true,
            ],
            [
                'name' => 'Çatı ve Üst Yapı İşleri',
                'start_date' => Carbon::now()->addWeeks(9),
                'end_date' => Carbon::now()->addWeeks(13),
                'duration' => 28,
                'progress' => 0,
                'responsible' => 'Yapı Mühendisi',
                'notes' => 'Çatı konstrüksiyonu ve örtü sistemi',
                'resources' => [
                    ['role' => 'Yapı Mühendisi', 'count' => 1, 'hours' => 224],
                    ['role' => 'Çelik İşçisi', 'count' => 4, 'hours' => 896],
                    ['role' => 'Çatı İşçisi', 'count' => 3, 'hours' => 672],
                ],
                'critical_path' => true,
            ],
            [
                'name' => 'Dış Cephe ve Yalıtım İşleri',
                'start_date' => Carbon::now()->addWeeks(11),
                'end_date' => Carbon::now()->addWeeks(16),
                'duration' => 35,
                'progress' => 0,
                'responsible' => 'Mimari Müdürü',
                'notes' => 'Dış cephe kaplama ve ısı yalıtımı',
                'resources' => [
                    ['role' => 'Mimar', 'count' => 1, 'hours' => 140],
                    ['role' => 'Cephe İşçisi', 'count' => 6, 'hours' => 840],
                    ['role' => 'Yalıtımcı', 'count' => 4, 'hours' => 560],
                ],
            ],
            [
                'name' => 'İç Mimari ve Son İşler',
                'start_date' => Carbon::now()->addWeeks(15),
                'end_date' => Carbon::now()->addWeeks(20),
                'duration' => 35,
                'progress' => 0,
                'responsible' => 'İç Mimar',
                'notes' => 'İç mekan düzenleme ve finishing işleri',
                'resources' => [
                    ['role' => 'İç Mimar', 'count' => 1, 'hours' => 140],
                    ['role' => 'Boyacı', 'count' => 4, 'hours' => 560],
                    ['role' => 'Döşemeci', 'count' => 3, 'hours' => 420],
                    ['role' => 'Marangoz', 'count' => 2, 'hours' => 280],
                ],
            ],
            [
                'name' => 'Peyzaj ve Çevre Düzenlemesi',
                'start_date' => Carbon::now()->addWeeks(18),
                'end_date' => Carbon::now()->addWeeks(22),
                'duration' => 28,
                'progress' => 0,
                'responsible' => 'Peyzaj Mimarı',
                'notes' => 'Bahçe düzenlemesi ve çevre peyzajı',
                'resources' => [
                    ['role' => 'Peyzaj Mimarı', 'count' => 1, 'hours' => 112],
                    ['role' => 'Bahçıvan', 'count' => 3, 'hours' => 336],
                    ['role' => 'İşçi', 'count' => 4, 'hours' => 448],
                ],
            ],
        ];

        $createdTasks = [];

        // Create tasks
        foreach ($tasks as $taskData) {
            $task = PlanningTask::create([
                'project_id' => $project->id,
                'name' => $taskData['name'],
                'start_date' => $taskData['start_date'],
                'end_date' => $taskData['end_date'],
                'duration' => $taskData['duration'],
                'progress' => $taskData['progress'],
                'responsible' => $taskData['responsible'],
                'notes' => $taskData['notes'],
                'resources' => $taskData['resources'],
                'critical_path' => $taskData['critical_path'] ?? false,
            ]);

            $createdTasks[] = $task;
        }

        // Set up dependencies
        if (count($createdTasks) >= 8) {
            // 1st task depends on nothing
            // 2nd task depends on 1st
            $createdTasks[1]->dependencies = [$createdTasks[0]->id];
            $createdTasks[1]->save();

            // 3rd task depends on 2nd
            $createdTasks[2]->dependencies = [$createdTasks[1]->id];
            $createdTasks[2]->save();

            // 4th task depends on 3rd
            $createdTasks[3]->dependencies = [$createdTasks[2]->id];
            $createdTasks[3]->save();

            // 5th task depends on 4th
            $createdTasks[4]->dependencies = [$createdTasks[3]->id];
            $createdTasks[4]->save();

            // Electrical work depends on 1st floor structure
            if (isset($createdTasks[5])) {
                $createdTasks[5]->dependencies = [$createdTasks[3]->id];
                $createdTasks[5]->save();
            }

            // Plumbing work depends on 1st floor structure
            if (isset($createdTasks[6])) {
                $createdTasks[6]->dependencies = [$createdTasks[3]->id];
                $createdTasks[6]->save();
            }

            // 2nd floor depends on 1st floor
            if (isset($createdTasks[7])) {
                $createdTasks[7]->dependencies = [$createdTasks[4]->id];
                $createdTasks[7]->save();
            }

            // Roof depends on 2nd floor
            if (isset($createdTasks[8])) {
                $createdTasks[8]->dependencies = [$createdTasks[7]->id];
                $createdTasks[8]->save();
            }

            // Facade depends on roof
            if (isset($createdTasks[9])) {
                $createdTasks[9]->dependencies = [$createdTasks[8]->id];
                $createdTasks[9]->save();
            }

            // Interior depends on facade
            if (isset($createdTasks[10])) {
                $createdTasks[10]->dependencies = [$createdTasks[9]->id];
                $createdTasks[10]->save();
            }

            // Landscaping depends on interior
            if (isset($createdTasks[11])) {
                $createdTasks[11]->dependencies = [$createdTasks[10]->id];
                $createdTasks[11]->save();
            }
        }

        $this->command?->info("Created " . count($createdTasks) . " planning tasks for project: {$project->name}");
    }
}
