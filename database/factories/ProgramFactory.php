<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Program>
 */
class ProgramFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->company . ' Programı',
            'description' => $this->faker->paragraph(),
            'manager' => $this->faker->name,
            'strategic_objective' => $this->faker->sentence(),
            'status' => $this->faker->randomElement(['active', 'completed', 'paused']),
            'total_budget' => $this->faker->numberBetween(10000000, 100000000), // 10M - 100M
            'spent_to_date' => $this->faker->numberBetween(0, 50000000),
            'start_date' => $this->faker->dateTimeBetween('-2 years', 'now'),
            'end_date' => $this->faker->dateTimeBetween('now', '+2 years'),
        ];
    }
}