<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Program;
use App\Models\Project;

class SampleProgramSeeder extends Seeder
{
    public function run()
    {
        $program = Program::create([
            'name' => 'Güney Ege Otel Yatırımları',
            'manager' => 'Ali Yılmaz',
            'strategic_objective' => 'Bölgedeki yatak kapasitesini 5 yılda 2.000 artırmak',
            'status' => 'active',
            'total_budget' => 1200000000,
            'spent_to_date' => 350000000,
            'description' => 'Örnek program (seed)'
        ]);

        $project = Project::first();
        if ($project) {
            $project->program_id = $program->id;
            $project->save();
            $this->command?->info('Linked project '.$project->id.' to program '.$program->id);
        } else {
            $this->command?->info('No projects found to link.');
        }
    }
}
