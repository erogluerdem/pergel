<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;

class CustomersTableSeeder extends Seeder
{
    public function run()
    {
        $demo = [
            ['name' => 'A İnşaat A.Ş.', 'tax_number' => '1111111111', 'contact_person' => 'Ali Yılmaz', 'phone' => '05551234567', 'email' => 'ali@ainsaat.com', 'city' => 'İstanbul', 'balance' => 1250000, 'overdue_balance' => 250000, 'credit_limit' => 2000000, 'status' => 'active', 'risk_score' => 20],
            ['name' => 'B Gayrimenkul Ltd.', 'tax_number' => '2222222222', 'contact_person' => 'Veli Çelik', 'phone' => '05557654321', 'email' => 'veli@bgayrimenkul.com', 'city' => 'İzmir', 'balance' => 450000, 'overdue_balance' => 50000, 'credit_limit' => 500000, 'status' => 'active', 'risk_score' => 60],
            ['name' => 'C Enerji A.Ş.', 'tax_number' => '3333333333', 'contact_person' => 'Ayşe Demir', 'phone' => '05559876543', 'email' => 'ayse@cenerji.com', 'city' => 'Ankara', 'balance' => 80000, 'overdue_balance' => 0, 'credit_limit' => 200000, 'status' => 'active', 'risk_score' => 10],
            ['name' => 'D Yapı Malzemeleri', 'tax_number' => '4444444444', 'contact_person' => 'Mehmet Kaya', 'phone' => '05551230000', 'email' => 'mehmet@dyapi.com', 'city' => 'Bursa', 'balance' => 250000, 'overdue_balance' => 120000, 'credit_limit' => 150000, 'status' => 'active', 'risk_score' => 90],
        ];

        foreach ($demo as $d) {
            Customer::create($d);
        }
    }
}
