<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Program;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Risk>
 */
class RiskFactory extends Factory
{
    public function definition(): array
    {
        $isPortfolioLevel = $this->faker->boolean(30); // %30 portföy genelinde

        return [
            'program_id' => $isPortfolioLevel ? null : Program::factory(),
            'project_id' => $isPortfolioLevel ? null : null, // Şimdilik null, sonra project ile ilişkilendir
            'title' => $this->faker->randomElement([
                'Çelik fiyatlarındaki artış',
                'Nitelikli personel bulma zorluğu',
                'Tedarik zinciri kesintileri',
                'Maliyet artışları',
                'Zaman çizelgesi gecikmeleri',
                'Kalite standartları düşüşü',
                'Düzenleyici değişiklikler',
                'Hava koşulları etkisi',
                'Alt yüklenici sorunları',
                'Finansman gecikmeleri'
            ]),
            'impact' => $this->faker->randomElement(['high', 'medium', 'low']),
            'likelihood' => $this->faker->randomElement(['high', 'medium', 'low']),
            'status' => $this->faker->randomElement(['open', 'mitigated', 'closed']),
            'mitigation' => $this->faker->sentence(),
        ];
    }
}