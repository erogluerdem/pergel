<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Simulation;

class SimulationSeeder extends Seeder
{
    public function run()
    {
        Simulation::factory()->count(2)->create();
    }
}
