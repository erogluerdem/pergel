<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Risk;

class RiskSeeder extends Seeder
{
    public function run(): void
    {
        Risk::factory()->count(10)->create();
    }
}