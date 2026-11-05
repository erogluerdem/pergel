<?php

namespace Database\Seeders;

use App\Models\StockCard;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoStockMovementSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::first();
        $cards = StockCard::all();

        if ($cards->isEmpty()) {
            return;
        }

        $c1 = $cards->where('code', 'STK-00101')->first() ?: $cards[0];
        $c2 = $cards->where('code', 'STK-00102')->first() ?: $cards[0];
        $c3 = $cards->where('code', 'STK-00103')->first() ?: $cards[0];
        $c4 = $cards->where('code', 'STK-00104')->first() ?: $cards[0];

        $movements = [
            [
                'stock_card_id' => $c1->id,
                'type' => 'in',
                'quantity' => 500.00,
                'unit_price' => 185.00,
                'total_amount' => 92500.00,
                'reference_no' => 'STK-IN-2026-001',
                'description' => 'Akçansa Çimento irsaliye No: 458912 ile A-Blok ambarına mal girişi yapıldı.',
                'created_by' => $admin?->id,
                'created_at' => now()->subDays(5),
            ],
            [
                'stock_card_id' => $c2->id,
                'type' => 'out',
                'quantity' => 12.00,
                'unit_price' => 21800.00,
                'total_amount' => 261600.00,
                'reference_no' => 'STK-OUT-2026-002',
                'description' => 'Temel donatı kalfası Mehmet Usta ekibine B-Blok temel imalatı için zimmetli demir çıkışı.',
                'created_by' => $admin?->id,
                'created_at' => now()->subDays(3),
            ],
            [
                'stock_card_id' => $c3->id,
                'type' => 'transfer',
                'quantity' => 45.00,
                'unit_price' => 240.00,
                'total_amount' => 10800.00,
                'reference_no' => 'STK-TR-2026-003',
                'description' => 'Merkez ambardan 2. Etap Şantiye MEP deposuna PVC boru transferi.',
                'created_by' => $admin?->id,
                'created_at' => now()->subDays(2),
            ],
            [
                'stock_card_id' => $c4->id,
                'type' => 'in',
                'quantity' => 600.00,
                'unit_price' => 320.00,
                'total_amount' => 192000.00,
                'reference_no' => 'STK-IN-2026-004',
                'description' => 'İzocam A.Ş. sevk irsaliyesi ile A-Blok dış cephe mantolama taşyünü girişi.',
                'created_by' => $admin?->id,
                'created_at' => now()->subDays(1),
            ],
        ];

        foreach ($movements as $m) {
            StockMovement::create($m);
        }
    }
}
