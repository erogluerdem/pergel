<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        $this->call([
            RolesAndPermissionsSeeder::class,
            DemoUserSeeder::class,
            CreateAdminUserSeeder::class,
            StandardSeeder::class,
            CostItemSeeder::class,
            PartnerSeeder::class,
            ProjectSeeder::class,
            PropertySeeder::class,
            TaskSeeder::class,
            PlanningTaskSeeder::class,
            FinanceRealDataSeeder::class,
            IsgRealDataSeeder::class,
            ErpSettingsSeeder::class,
        ]);

        Schema::enableForeignKeyConstraints();
    }
}
