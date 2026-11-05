<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\Subcontractor;
use App\Models\SubcontractorContract;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubcontractorContractFactory extends Factory
{
    protected $model = SubcontractorContract::class;

    public function definition(): array
    {
        return [
            'contract_number' => 'SC-' . $this->faker->unique()->numerify('####'),
            'contract_name' => $this->faker->sentence(3),
            'subcontractor_id' => Subcontractor::factory(),
            'project_id' => Project::factory(),
            'contract_type' => $this->faker->randomElement(['Labour', 'Material', 'Turnkey']),
            'total_amount' => $this->faker->randomFloat(2, 100000, 5000000),
            'paid_amount' => $this->faker->randomFloat(2, 0, 500000),
            'status' => $this->faker->randomElement(['active', 'completed', 'terminated']),
            'start_date' => $this->faker->dateTimeBetween('-6 months', 'now'),
            'end_date' => $this->faker->dateTimeBetween('now', '+6 months'),
            'description' => $this->faker->sentence(),
            'documents' => [],
            'specifications' => [],
            'insurance_expiry_dates' => [],
        ];
    }
}
