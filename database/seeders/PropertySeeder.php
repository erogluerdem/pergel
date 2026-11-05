<?php

namespace Database\Seeders;

use App\Models\Unit;
use App\Models\Project;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class PropertySeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('tr_TR');
        $residentialProjects = Project::whereIn('type', ['residential', 'commercial'])->get();
        if ($residentialProjects->isEmpty()) return;

        $types = ['Villa', 'Daire', 'Dükkan', 'Ofis', 'Stüdyo'];
        $statuses = ['available', 'sold', 'reserved', 'rented'];

        foreach ($residentialProjects as $project) {
            $numUnits = rand(30, 100);
            for ($i = 1; $i <= $numUnits; $i++) {
                $price = rand(5, 50) * 1000000;
                Unit::create([
                    'unit_no' => 'PRJ' . $project->id . '-' . $faker->randomElement(['A', 'B', 'C', 'D']) . '-' . $i,
                    'block' => $faker->randomElement(['A', 'B', 'C', 'D']),
                    'floor' => rand(1, 30),
                    'description' => $faker->realText(50),
                    'project_id' => $project->id,
                    'unit_type' => $faker->randomElement($types),
                    'status' => $faker->randomElement($statuses),
                    'list_price' => $price,
                    'selling_price' => $price * $faker->randomFloat(2, 0.8, 1.0),
                    'max_discount_rate' => rand(5, 20),
                    'currency' => 'TRY',
                    'area' => rand(60, 450),
                    'room_count' => rand(1, 6),
                    'bathroom_count' => rand(1, 4),
                ]);
            }
        }
    }
}
