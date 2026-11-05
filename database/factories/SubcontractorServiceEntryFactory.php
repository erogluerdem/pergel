<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\SubcontractorContract;
use App\Models\SubcontractorServiceEntry;
use App\Models\SubcontractorUser;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubcontractorServiceEntryFactory extends Factory
{
    protected $model = SubcontractorServiceEntry::class;

    public function definition(): array
    {
        return [
            'subcontractor_contract_id' => SubcontractorContract::factory(),
            'project_id' => Project::factory(),
            'subcontractor_user_id' => SubcontractorUser::factory(),
            'entry_date' => $this->faker->date(),
            'service_type' => $this->faker->randomElement(['Beton dökümü', 'Kalıp montajı', 'Demir bağlama']),
            'work_description' => $this->faker->sentence(),
            'quantity' => $this->faker->randomFloat(2, 1, 200),
            'unit' => $this->faker->randomElement(['m³', 'm²', 'adam/saat']),
            'worked_hours' => $this->faker->randomFloat(2, 0, 12),
            'crew_size' => $this->faker->numberBetween(1, 20),
            'status' => 'submitted',
            'attachments' => [],
            'notes' => $this->faker->sentence(),
            'submitted_at' => now(),
            'approved_at' => null,
            'rejected_at' => null,
        ];
    }
}
