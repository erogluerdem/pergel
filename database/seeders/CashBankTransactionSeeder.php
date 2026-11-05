<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CashBankTransaction;

class CashBankTransactionSeeder extends Seeder
{
    public function run(): void
    {
        CashBankTransaction::insert([
            [
                'type' => 'bank',
                'amount' => 100000,
                'description' => 'Ortak 1 banka yatırımı',
                'transaction_date' => now(),
                'direction' => 'in',
                'capital_transaction_id' => 1,
            ],
            [
                'type' => 'cash',
                'amount' => 100000,
                'description' => 'Ortak 2 nakit yatırımı',
                'transaction_date' => now(),
                'direction' => 'in',
                'capital_transaction_id' => 2,
            ],
        ]);
    }
}
