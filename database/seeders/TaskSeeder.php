<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use Faker\Factory as Faker;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('tr_TR');
        $users = User::all();
        $projects = Project::all();
        if ($projects->isEmpty() || $users->isEmpty()) return;

        $taskCategories = ['Hafriyat', 'Fore Kazık', 'Zemin Etüdü', 'Kalıp Montajı', 'Demir Bağlama', 'Beton Dökümü', 'Elektrik Altyapı', 'Sıhhi Tesisat', 'İnce İşçilik', 'Boya/Badana', 'Peyzaj', 'Dış Cephe', 'Geçici Kabul', 'Kesin Kabul', 'Kalite Kontrol'];

        foreach ($projects as $project) {
            $numTasks = rand(15, 40);
            
            for ($i = 0; $i < $numTasks; $i++) {
                $rate = ($project->progress_percent == 100) ? 100 : rand(0, 100);
                $status = ($rate == 100) ? 'completed' : (($rate > 0) ? 'active' : 'pending');

                Task::create([
                    'title' => ((string) $faker->randomElement($taskCategories)) . ' - Aşama ' . rand(1, 5),
                    'description' => $faker->realText(100),
                    'status' => $status,
                    'priority' => $faker->randomElement(['low', 'medium', 'high', 'urgent']),
                    'completion_rate' => $rate,
                    'assigned_to' => $users->random()->id,
                    'created_by' => $users->random()->id,
                    'due_date' => now()->addDays(rand(-30, 90)),
                ]);
            }
        }
    }
}
