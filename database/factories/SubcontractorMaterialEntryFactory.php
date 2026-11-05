<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\SubcontractorContract;
use App\Models\SubcontractorMaterialEntry;
use App\Models\SubcontractorUser;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubcontractorMaterialEntryFactory extends Factory
{
    protected $model = SubcontractorMaterialEntry::class;

    public function definition(): array
    {
        return [
            'subcontractor_contract_id' => SubcontractorContract::factory(),
            'project_id' => Project::factory(),
            'subcontractor_user_id' => SubcontractorUser::factory(),
            'delivery_date' => $this->faker->date(),
            'material_name' => $this->faker->randomElement(['C25 Beton', 'Ø16 Demir', 'Su İzolasyon Membranı']),
            'specification' => $this->faker->sentence(),
            'quantity' => $this->faker->randomFloat(2, 1, 100),
            'unit' => $this->faker->randomElement(['m³', 'ton', 'adet']),
            'unit_price' => $this->faker->randomFloat(2, 100, 5000),
            'status' => 'submitted',
            'attachments' => [],
            'notes' => $this->faker->sentence(),
            'submitted_at' => now(),
            'approved_at' => null,
            'rejected_at' => null,
        ];
    }
}
