<?php

namespace Database\Factories;

use App\Models\Subcontractor;
use App\Models\SubcontractorUser;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class SubcontractorUserFactory extends Factory
{
    protected $model = SubcontractorUser::class;

    public function definition(): array
    {
        return [
            'subcontractor_id' => Subcontractor::factory(),
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'password' => 'password',
            'role' => $this->faker->randomElement(['manager', 'site-lead', 'member']),
            'status' => 'active',
            'last_login_at' => null,
            'remember_token' => Str::random(10),
        ];
    }

    public function inactive(): self
    {
        return $this->state(fn () => ['status' => 'inactive']);
    }
}
