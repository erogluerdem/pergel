<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CashBankTransaction;
use App\Models\CapitalTransaction;

class CashBankTransactionSeeder extends Seeder
{
    public function run(): void
    {
        $capTxs = CapitalTransaction::take(2)->pluck('id')->values();

        CashBankTransaction::insert([
            [
                'type' => 'bank',
                'amount' => 100000,
                'description' => 'Ortak 1 banka yatırımı',
                'transaction_date' => now(),
                'direction' => 'in',
                'capital_transaction_id' => $capTxs[0] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'cash',
                'amount' => 100000,
                'description' => 'Ortak 2 nakit yatırımı',
                'transaction_date' => now(),
                'direction' => 'in',
                'capital_transaction_id' => $capTxs[1] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
