<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    public function definition(): array
    {
        $start = $this->faker->dateTimeBetween('-1 years', '+1 years')->format('Y-m-d');
        $end = $this->faker->dateTimeBetween($start, '+2 years')->format('Y-m-d');
        $budget = $this->faker->numberBetween(1000000, 50000000); // 1M - 50M arası
        $progress = $this->faker->numberBetween(0, 100);
        $spent = $progress > 0 ? $this->faker->numberBetween($budget * 0.1, $budget * $progress / 100) : 0;

        // Türkiye şehirlerinin koordinatları
        $turkishCities = [
            ['name' => 'İstanbul', 'lat' => 41.0082, 'lng' => 28.9784],
            ['name' => 'Ankara', 'lat' => 39.9334, 'lng' => 32.8597],
            ['name' => 'İzmir', 'lat' => 38.4192, 'lng' => 27.1287],
            ['name' => 'Bursa', 'lat' => 40.1885, 'lng' => 29.0600],
            ['name' => 'Antalya', 'lat' => 36.8969, 'lng' => 30.7133],
            ['name' => 'Adana', 'lat' => 37.0000, 'lng' => 35.3213],
            ['name' => 'Konya', 'lat' => 37.8714, 'lng' => 32.4846],
            ['name' => 'Gaziantep', 'lat' => 37.0662, 'lng' => 37.3833],
            ['name' => 'Şanlıurfa', 'lat' => 37.1591, 'lng' => 38.7969],
            ['name' => 'Kocaeli', 'lat' => 40.8533, 'lng' => 29.8815],
        ];
        $city = $this->faker->randomElement($turkishCities);

        return [
            'name' => $this->faker->company . ' ' . $this->faker->randomElement(['Konaklama', 'Ticaret Merkezi', 'Altyapı', 'Endüstriyel', 'Konut', 'Ofis']) . ' Projesi',
            'description' => $this->faker->paragraphs(2, true),
            'location' => ['lat' => $city['lat'], 'lng' => $city['lng']],
            'status' => $this->faker->randomElement(['planning','bidding','contracted','construction','completed']),
            'budget' => $budget,
            'start_date' => $start,
            'end_date' => $end,
            'progress_percent' => $progress,
            'spent' => $spent,
            'type' => $this->faker->randomElement(['residential', 'commercial', 'infrastructure', 'industrial']),
            'project_manager' => $this->faker->name,
            'region' => $this->faker->randomElement(['Marmara', 'Ege', 'İç Anadolu', 'Akdeniz', 'Karadeniz', 'Doğu Anadolu', 'Güneydoğu Anadolu']),
            'health_status' => $this->faker->randomElement(['healthy', 'risky', 'critical']),
            'project_code' => 'PRJ-' . $this->faker->unique()->numberBetween(1000, 9999),
            'contract_number' => 'CNT-' . $this->faker->unique()->numberBetween(1000, 9999),
            'client_name' => $this->faker->company,
            'contractor_name' => $this->faker->company,
        ];
    }
}
