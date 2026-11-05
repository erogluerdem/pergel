<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AccountsTableSeeder extends Seeder
{
    public function run()
    {
        // Use insert for deterministic IDs
        $now = Carbon::now()->toDateTimeString();
        DB::table('accounts')->insert([
            [
                'name' => 'Garanti Bankası - Ticari',
                'type' => 'bank',
                'bank_name' => 'Garanti',
                'branch' => 'Niğde Şb.',
                'account_no' => '1234567890',
                'iban' => 'TR00 0000 0000 0000 0000 00',
                'currency' => 'TRY',
                'balance' => 1250000.00,
                'responsible' => null,
                'opening_date' => Carbon::now()->subMonths(6)->toDateString(),
                'active' => true,
                'created_by' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Merkez Kasa',
                'type' => 'cashbox',
                'bank_name' => null,
                'branch' => null,
                'account_no' => null,
                'iban' => null,
                'currency' => 'TRY',
                'balance' => 75000.00,
                'responsible' => 'Muhasebe',
                'opening_date' => Carbon::now()->subYears(1)->toDateString(),
                'active' => true,
                'created_by' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Akbank - USD Hesabı',
                'type' => 'bank',
                'bank_name' => 'Akbank',
                'branch' => 'Merkez Şb.',
                'account_no' => '9876543210',
                'iban' => 'TR11 1111 1111 1111 1111 11',
                'currency' => 'USD',
                'balance' => 50000.00,
                'responsible' => null,
                'opening_date' => Carbon::now()->subMonths(3)->toDateString(),
                'active' => true,
                'created_by' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
