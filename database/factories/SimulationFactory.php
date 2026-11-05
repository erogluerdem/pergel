<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Simulation;
use App\Models\Project;

class SimulationFactory extends Factory
{
    protected $model = Simulation::class;

    public function definition()
    {
        $project = Project::inRandomOrder()->first();
        return [
            'name'=>$this->faker->sentence(3),
            'type'=>$this->faker->randomElement(['4D','5D']),
            'project_id'=>$project->id ?? null,
            'status'=>'done',
            'config'=>['models'=>[]],
            'result'=>null,
            'created_by'=>1
        ];
    }
}
