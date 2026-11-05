<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ChangeOrder;
use Illuminate\Support\Facades\DB;

class ChangeOrderSeeder extends Seeder
{
    public function run()
    {
        // If table empty, insert demo records
        if (DB::table('change_orders')->count() == 0) {
            ChangeOrder::create([
                'no' => 'ET-001',
                'subject' => 'Asma Tavan Tipi Değişikliği',
                'reason' => 'İşveren Talebi',
                'status' => 'Onaylandı',
                'cost' => 150000,
                'approved_at' => now()->subDays(2),
            ]);

            ChangeOrder::create([
                'no' => 'ET-002',
                'subject' => 'Zemin Etüdü Farkı',
                'reason' => 'Öngörülemeyen Durum',
                'status' => 'İncelemede',
                'cost' => 450000,
            ]);
        }
    }
}
