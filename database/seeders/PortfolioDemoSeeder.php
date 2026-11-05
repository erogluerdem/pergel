<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PortfolioDemoSeeder extends Seeder
{
    public function run()
    {
        $path = resource_path('demo/portfolio_demo_projects.json');
        if (!file_exists($path)) return;

        $data = json_decode(file_get_contents($path), true) ?: [];
        foreach ($data as $item) {
            // map only known fields to avoid mass assignment issues
            DB::table('projects')->updateOrInsert(
                ['id' => $item['id']],
                [
                    'name' => $item['name'] ?? null,
                    'budget' => $item['budget'] ?? 0,
                    'spent' => $item['spent'] ?? 0,
                    'start_date' => $item['start_date'] ?? null,
                    'end_date' => $item['end_date'] ?? null,
                    'progress_percent' => $item['progress_percent'] ?? 0,
                    'type' => $item['type'] ?? null,
                    'program_id' => $item['program_id'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
