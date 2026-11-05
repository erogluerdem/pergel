<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CapitalTransaction;

class CapitalTransactionSeeder extends Seeder
{
    public function run(): void
    {
        CapitalTransaction::insert([
            [
                'partner_id' => 1,
                'amount' => 100000,
                'source' => 'bank',
                'description' => 'Başlangıç sermayesi',
                'transaction_date' => now(),
            ],
            [
                'partner_id' => 2,
                'amount' => 100000,
                'source' => 'cash',
                'description' => 'Başlangıç sermayesi',
                'transaction_date' => now(),
            ],
        ]);
    }
}
