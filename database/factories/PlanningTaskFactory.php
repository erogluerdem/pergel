<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\PlanningTask;

class PlanningTaskFactory extends Factory
{
    protected $model = PlanningTask::class;

    public function definition(): array
    {
        $start = $this->faker->dateTimeBetween('-1 months', '+2 months');
        $end = (clone $start)->modify('+'.rand(1,20).' days');
        return [
            'project_id' => null,
            'name' => $this->faker->sentence(3),
            'start_date' => $start->format('Y-m-d'),
            'end_date' => $end->format('Y-m-d'),
            'duration' => $end->diff($start)->days,
            'progress' => $this->faker->numberBetween(0,100),
            'responsible' => $this->faker->name,
            'notes' => $this->faker->paragraph,
            'dependencies' => rand(0,1) ? [rand(1,10)] : [],
            'resources' => [
                'human' => [
                    ['role' => $this->faker->randomElement(['usta', 'çırak', 'mühendis', 'işçi']), 'count' => rand(1,5), 'hours' => rand(20,40)]
                ],
                'equipment' => rand(0,1) ? [['name' => $this->faker->randomElement(['vinç', 'ekskavatör', 'kamyon']), 'count' => rand(1,3)]] : []
            ],
            'budget_wbs' => rand(0,1) ? 'WBS-' . rand(100,999) : null,
            'documents' => rand(0,1) ? [['type' => 'drawing', 'id' => rand(1000,9999), 'name' => $this->faker->sentence(2)]] : [],
            'critical_path' => false, // will be calculated later
        ];
    }
}
