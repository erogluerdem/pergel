<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AccountTransactionsSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now()->toDateTimeString();
        // find accounts by name
        $accounts = DB::table('accounts')->whereIn('name', ['Garanti Bankası - Ticari','Merkez Kasa','Akbank - USD Hesabı'])->get()->keyBy('name');
        if(!isset($accounts['Garanti Bankası - Ticari'])) return;

        DB::table('account_transactions')->insert([
            [
                'account_id' => $accounts['Garanti Bankası - Ticari']->id,
                'date' => Carbon::now()->subMonths(6)->toDateString(),
                'description' => 'Açılış bakiyesi',
                'debit' => 1250000,
                'credit' => 0,
                'balance' => 1250000,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'account_id' => $accounts['Garanti Bankası - Ticari']->id,
                'date' => Carbon::now()->subMonths(1)->toDateString(),
                'description' => 'Tahsilat',
                'debit' => 50000,
                'credit' => 0,
                'balance' => 1300000,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'account_id' => $accounts['Merkez Kasa']->id,
                'date' => Carbon::now()->subYear()->toDateString(),
                'description' => 'Kasa açılışı',
                'debit' => 75000,
                'credit' => 0,
                'balance' => 75000,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'account_id' => $accounts['Akbank - USD Hesabı']->id,
                'date' => Carbon::now()->subMonths(3)->toDateString(),
                'description' => 'Açılış',
                'debit' => 50000,
                'credit' => 0,
                'balance' => 50000,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
