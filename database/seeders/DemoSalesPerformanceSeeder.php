<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SalesTarget;
use App\Models\Opportunity;
use App\Models\User;
use Carbon\Carbon;

class DemoSalesPerformanceSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Get or create sample sales consultants / users
        $users = User::limit(5)->get();

        if ($users->isEmpty()) {
            $user1 = User::create([
                'name' => 'Mehmet Yılmaz',
                'email' => 'mehmet.yilmaz@pergel.com',
                'password' => bcrypt('password'),
            ]);
            $user2 = User::create([
                'name' => 'Ayşe Kaya',
                'email' => 'ayse.kaya@pergel.com',
                'password' => bcrypt('password'),
            ]);
            $user3 = User::create([
                'name' => 'Caner Demir',
                'email' => 'caner.demir@pergel.com',
                'password' => bcrypt('password'),
            ]);
            $users = collect([$user1, $user2, $user3]);
        }

        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth()->toDateString();
        $endOfMonth = $now->copy()->endOfMonth()->toDateString();

        // Target configurations per user
        $targetsData = [
            [
                'target_amount' => 15000000.00,
                'target_type' => 'revenue',
                'description' => '2026 Q3 Konut & Ticari Satış Hedefi',
                'status' => 'active',
                'won_value' => 16500000.00,
                'won_count' => 8,
            ],
            [
                'target_amount' => 12000000.00,
                'target_type' => 'revenue',
                'description' => 'Marina Rezidans Blok Satış Portföyü',
                'status' => 'active',
                'won_value' => 9800000.00,
                'won_count' => 5,
            ],
            [
                'target_amount' => 8500000.00,
                'target_type' => 'revenue',
                'description' => 'Villa Portföy Satış Operasyonu',
                'status' => 'active',
                'won_value' => 4200000.00,
                'won_count' => 2,
            ],
            [
                'target_amount' => 20000000.00,
                'target_type' => 'revenue',
                'description' => 'Kurumsal Ofis Kiralama & Satış Hedefi',
                'status' => 'active',
                'won_value' => 21000000.00,
                'won_count' => 12,
            ]
        ];

        foreach ($users as $index => $u) {
            $tConfig = $targetsData[$index % count($targetsData)];

            // Create or update Sales Target
            $target = SalesTarget::updateOrCreate(
                [
                    'user_id' => $u->id,
                    'period_start' => $startOfMonth,
                ],
                [
                    'period_end' => $endOfMonth,
                    'target_type' => $tConfig['target_type'],
                    'target_amount' => $tConfig['target_amount'],
                    'description' => $tConfig['description'],
                    'status' => $tConfig['status'],
                ]
            );

            // Create Won Opportunities for user so calculatePerformance gets realistic numbers
            if (class_exists(Opportunity::class)) {
                // Ensure at least one won opportunity for this user
                Opportunity::create([
                    'title' => 'Villa Satış Sözleşmesi - ' . $u->name,
                    'owner_id' => $u->id,
                    'stage' => 'won',
                    'estimated_value_kdv' => $tConfig['won_value'],
                    'updated_at' => $now,
                    'created_at' => $now,
                ]);
            }
        }
    }
}
