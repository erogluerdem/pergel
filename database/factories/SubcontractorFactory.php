<?php

namespace Database\Factories;

use App\Models\Subcontractor;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubcontractorFactory extends Factory
{
    protected $model = Subcontractor::class;

    public function definition(): array
    {
        return [
            'company_name' => $this->faker->company(),
            'address' => $this->faker->address(),
            'tax_number' => $this->faker->numerify('###########'),
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->unique()->companyEmail(),
            'contact_person' => $this->faker->name(),
            'expertise_area' => $this->faker->randomElement(['Beton', 'Elektrik', 'Mekanik', 'İzolasyon']),
            'performance_score' => $this->faker->randomFloat(2, 70, 100),
            'safety_score' => $this->faker->randomFloat(2, 70, 100),
            'status' => $this->faker->randomElement(['approved', 'conditional', 'inactive']),
            'active_projects' => $this->faker->numberBetween(0, 5),
            'documents' => [],
        ];
    }
}
