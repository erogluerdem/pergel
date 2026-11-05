<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Program;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Milestone>
 */
class MilestoneFactory extends Factory
{
    public function definition(): array
    {
        return [
            'program_id' => Program::factory(),
            'title' => $this->faker->randomElement([
                'Kaba inşaat başlangıcı',
                'Kaba inşaat bitişi',
                'İç mekan çalışmaları',
                'Elektrik tesisatı',
                'Mekanik tesisat',
                'İskan başvurusu',
                'İş teslimi',
                'Son kontroller',
                'Garanti dönemi başlangıcı',
                'Proje kapanışı'
            ]),
            'due_date' => $this->faker->dateTimeBetween('now', '+6 months'),
            'status' => $this->faker->randomElement(['pending', 'completed', 'overdue']),
            'description' => $this->faker->sentence(),
        ];
    }
}