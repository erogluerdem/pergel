<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\IncidentReport;
use App\Models\Permit;
use App\Models\ToolboxTalk;
use App\Models\Project;
use App\Models\Employee;
use Faker\Factory as Faker;

class IsgRealDataSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('tr_TR');
        $projects = Project::all();
        $employees = Employee::all();
        if ($projects->isEmpty() || $employees->isEmpty()) return;

        foreach ($projects as $project) {
            $numIncidents = rand(5, 25);
            for ($i = 0; $i < $numIncidents; $i++) {
                IncidentReport::create([
                    'type' => $faker->randomElement(['ramak kala', 'hafif', 'ağır', 'guvensiz durum']),
                    'occurred_at' => (string) $faker->dateTimeBetween('-12 months', 'now')->format('Y-m-d'),
                    'status' => $faker->randomElement(['open', 'closed', 'investigation']),
                    'description' => ((string) $project->name) . ' şantiyesinde ' . ((string) $faker->realText(50)),
                ]);
            }

            $numPermits = rand(10, 30);
            $permitTypes = ['Yüksekte Çalışma İzni', 'Ateşli Çalışma İzni', 'Kapalı Alan Çalışma İzni', 'Kaldırma ve Taşıma İzni', 'Elektrik Çalışma İzni', 'Gece Çalışma İzni'];
            for ($i = 0; $i < $numPermits; $i++) {
                Permit::create([
                    'type' => ((string) $faker->randomElement($permitTypes)) . ' (' . ((string) $project->name) . ')',
                    'valid_from' => (string) $faker->dateTimeBetween('-3 months', 'now')->format('Y-m-d'),
                    'valid_to' => (string) $faker->dateTimeBetween('now', '+1 months')->format('Y-m-d'),
                    'status' => $faker->randomElement(['pending', 'active', 'closed']),
                ]);
            }

            $numTalks = rand(15, 50);
            $talkTopics = ['Yüksekte Çalışma Kuralları', 'KKD Kullanımı', 'Acil Durum Tahliye Planı', 'İş Makineleri Güvenliği', 'Elektrik Kazalarından Korunma', 'Ergonomi ve Taşıma Kuralları'];
            for ($i = 0; $i < $numTalks; $i++) {
                ToolboxTalk::create([
                    'topic' => ((string) $faker->randomElement($talkTopics)) . ' - ' . ((string) $project->name),
                    'date' => (string) $faker->dateTimeBetween('-12 months', '+1 months')->format('Y-m-d'),
                    'presenter_id' => $employees->random()->id,
                    'status' => $faker->randomElement(['draft', 'completed', 'scheduled']),
                ]);
            }
        }
    }
}
