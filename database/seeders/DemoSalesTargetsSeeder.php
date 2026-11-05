<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SalesTarget;
use App\Models\User;

class DemoSalesTargetsSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();
        if ($users->isEmpty()) {
            return;
        }

        $adminId = $users->firstWhere('email', 'admin@test.com')?->id ?? $users->first()->id;
        $demoId = $users->firstWhere('email', 'demo@test.com')?->id ?? $users->skip(1)->first()?->id ?? $adminId;
        $erdemId = $users->firstWhere('email', 'erdemeroglu@hotmail.com')?->id ?? $users->last()->id;

        $targets = [
            [
                'user_id' => $adminId,
                'period_start' => '2026-01-01',
                'period_end' => '2026-03-31',
                'target_type' => 'revenue',
                'target_amount' => 120000000.00,
                'description' => '2026 Yılı 1. Çeyrek Kurumsal Gayrimenkul ve Proje Satış Ciro Hedefi (120 Milyon ₺)',
                'status' => 'active',
            ],
            [
                'user_id' => $adminId,
                'period_start' => '2026-04-01',
                'period_end' => '2026-06-30',
                'target_type' => 'revenue',
                'target_amount' => 150000000.00,
                'description' => '2026 Yılı 2. Çeyrek Konut ve Ticari Bağımsız Bölüm Satış Ciro Hedefi (150 Milyon ₺)',
                'status' => 'active',
            ],
            [
                'user_id' => $demoId,
                'period_start' => '2026-01-01',
                'period_end' => '2026-03-31',
                'target_type' => 'unit',
                'target_amount' => 15.00,
                'description' => '1. Çeyrek Villa ve Rezidans Bağımsız Bölüm Satış Adedi Hedefi (15 Adet)',
                'status' => 'active',
            ],
            [
                'user_id' => $demoId,
                'period_start' => '2026-07-01',
                'period_end' => '2026-09-30',
                'target_type' => 'revenue',
                'target_amount' => 85000000.00,
                'description' => '3. Çeyrek Yaz Dönemi Lüks Konut Portföyü Ciro Hedefi (85 Milyon ₺)',
                'status' => 'active',
            ],
            [
                'user_id' => $erdemId,
                'period_start' => '2026-01-01',
                'period_end' => '2026-12-31',
                'target_type' => 'revenue',
                'target_amount' => 250000000.00,
                'description' => '2026 Yıllık Genel Bölge Satış Ciro Hedefi (250 Milyon ₺)',
                'status' => 'active',
            ],
            [
                'user_id' => $erdemId,
                'period_start' => '2026-01-01',
                'period_end' => '2026-12-31',
                'target_type' => 'unit',
                'target_amount' => 30.00,
                'description' => '2026 Yıllık Toplam Sözleşmeli Bağımsız Bölüm Satış Adedi (30 Adet)',
                'status' => 'active',
            ],
            [
                'user_id' => $adminId,
                'period_start' => '2025-10-01',
                'period_end' => '2025-12-31',
                'target_type' => 'revenue',
                'target_amount' => 90000000.00,
                'description' => '2025 Yılı Son Çeyrek Kapanış Satış Hedefi (90 Milyon ₺)',
                'status' => 'completed',
            ]
        ];

        foreach ($targets as $targetData) {
            SalesTarget::create($targetData);
        }
    }
}
