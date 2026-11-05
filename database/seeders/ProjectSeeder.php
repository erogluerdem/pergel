<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use App\Models\Project;
use Faker\Factory as Faker;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Project::truncate();
        Schema::enableForeignKeyConstraints();
        
        $faker = Faker::create('tr_TR');

        $projectNames = [
            'Bosphorus Life Konakları', 'Zorlu Center Ek Hizmet Binası', 'Pergel Tower İş Merkezi',
            'Vadi İstanbul Premium Rezidans', 'Ege Marina Villaları', 'Ankara Yüksek Hızlı Tren Garı Altyapısı',
            'Antalya Serbest Bölge Lojistik Merkezi', 'İzmir Körfez Geçişi Projesi', 'Kapadokya Butik Otel Kompleksi',
            'Bursa Organize Sanayi Fabrika İnşaatı', 'Gaziantep Şehir Hastanesi Ek Blok', 'Adana Çukurova Kuleleri',
            'Marmaris Luxury Resort & Spa', 'Göktürk Kemer Country Genişletme Projesi', 'Finans Merkezi Merkez Bankası Kulesi',
            'Trabzon Sahil Park ve Yaşam Alanı', 'Kocaeli Teknopark AR-GE Binaları', 'Kayseri Forum AVM Yenileme',
            'Eskişehir Modern Sanatlar Müzesi', 'Çanakkale Boğaz Köprüsü Bağlantı Yolları'
        ];

        $types = ['konut', 'ticari', 'karma', 'altyapi', 'endustriyel'];
        $regions = ['Marmara', 'Ege', 'İç Anadolu', 'Akdeniz', 'Karadeniz'];
        $statuses = ['planned', 'in_progress', 'on_hold', 'completed'];

        for ($i = 0; $i < 20; $i++) {
            $status = $faker->randomElement($statuses);
            
            // Logically tie progress to status
            if ($status === 'completed') {
                $progress = 100;
                $health = 'healthy';
            } elseif ($status === 'planned') {
                $progress = rand(0, 5);
                $health = 'healthy';
            } elseif ($status === 'on_hold') {
                $progress = rand(20, 80);
                $health = $faker->randomElement(['risky', 'critical']);
            } else {
                $progress = rand(10, 95);
                $health = $faker->randomElement(['healthy', 'healthy', 'risky', 'critical']);
            }

            // Budgets from 50M to 2B TRY
            $budget = $faker->numberBetween(50, 2000) * 1000000;
            
            // Spent is tied to progress but can be over/under budget
            $spentFactor = $faker->randomFloat(2, 0.85, 1.25);
            $spent = $budget * ($progress / 100) * $spentFactor; 
            
            // If planned, spent is very low
            if ($status === 'planned') {
                $spent = $budget * 0.01 * $faker->randomFloat(2, 0.5, 2.0);
            }

            $startDate = now()->subMonths(rand(6, 48));
            $endDate = (clone $startDate)->addMonths(rand(12, 60));

            Project::create([
                'name' => $projectNames[$i],
                'description' => $faker->realText(250),
                'location' => json_encode(['lat' => $faker->latitude(36, 42), 'lng' => $faker->longitude(26, 44)]),
                'status' => $status,
                'budget' => $budget,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'progress_percent' => $progress,
                'spent' => $spent,
                'type' => $faker->randomElement($types),
                'project_manager' => $faker->name,
                'region' => $faker->randomElement($regions),
                'health_status' => $health,
                'project_code' => 'PRJ-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'contract_number' => 'CNT-' . $faker->unique()->numberBetween(10000, 99999),
                'client_name' => $faker->company,
                'contractor_name' => 'Pergel İnşaat A.Ş.',
            ]);
        }
    }
}
