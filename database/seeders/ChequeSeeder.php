<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ChequeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = now();

        $demo = [
            [
                'cheque_number' => 'CK2025001',
                'bank_name' => 'Türkiye İş Bankası',
                'branch' => 'Kadıköy',
                'account_number' => 'TR1234567890',
                'amount' => 125000.00,
                'issue_date' => $now->subMonths(2)->toDateString(),
                'due_date' => $now->addWeeks(2)->toDateString(),
                'status' => 'pending',
                'notes' => 'Demo çek 1',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'cheque_number' => 'CK2025002',
                'bank_name' => 'Garanti BBVA',
                'branch' => 'Ataşehir',
                'account_number' => 'TR0987654321',
                'amount' => 85000.00,
                'issue_date' => $now->subMonths(1)->toDateString(),
                'due_date' => $now->addDays(10)->toDateString(),
                'status' => 'pending',
                'notes' => 'Demo çek 2',
                'created_at' => $now,
                'updated_at' => $now
            ],
        ];

        DB::table('cheques')->insert($demo);
    }
}
