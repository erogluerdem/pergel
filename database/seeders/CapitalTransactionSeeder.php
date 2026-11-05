<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CapitalTransaction;
use App\Models\Partner;

class CapitalTransactionSeeder extends Seeder
{
    public function run(): void
    {
        $partner1 = Partner::firstOrCreate(['name' => 'Ortak 1'], ['share' => 50]);
        $partner2 = Partner::firstOrCreate(['name' => 'Ortak 2'], ['share' => 50]);

        CapitalTransaction::insert([
            [
                'partner_id' => $partner1->id,
                'amount' => 100000,
                'source' => 'bank',
                'description' => 'Başlangıç sermayesi',
                'transaction_date' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'partner_id' => $partner2->id,
                'amount' => 100000,
                'source' => 'cash',
                'description' => 'Başlangıç sermayesi',
                'transaction_date' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
