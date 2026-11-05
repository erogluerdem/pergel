<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Partner;

class PartnerSeeder extends Seeder
{
    public function run(): void
    {
        Partner::insert([
            ['name' => 'Ortak 1', 'share' => 25],
            ['name' => 'Ortak 2', 'share' => 25],
            ['name' => 'Ortak 3', 'share' => 25],
            ['name' => 'Ortak 4', 'share' => 25],
        ]);
    }
}
