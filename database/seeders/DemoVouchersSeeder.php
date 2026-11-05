<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoVouchersSeeder extends Seeder
{
    public function run()
    {
        // create demo customers if table exists
        if (\Illuminate\Support\Facades\Schema::hasTable('customers')) {
            \DB::table('customers')->insertOrIgnore([
                ['name' => 'Acme A.Ş.', 'balance' => 1000, 'created_at'=>now(),'updated_at'=>now()],
                ['name' => 'Beta Ticaret', 'balance' => 500, 'created_at'=>now(),'updated_at'=>now()],
            ]);
        }

        // create demo invoices if table exists
        if (\Illuminate\Support\Facades\Schema::hasTable('invoices')) {
            \DB::table('invoices')->insertOrIgnore([
                ['no'=>'INV-1001','amount'=>1200,'paid_amount'=>200,'customer_id'=>1,'date'=>now(),'due_date'=>now()->addDays(30),'created_at'=>now(),'updated_at'=>now()],
                ['no'=>'INV-1002','amount'=>800,'paid_amount'=>0,'customer_id'=>1,'date'=>now(),'due_date'=>now()->addDays(10),'created_at'=>now(),'updated_at'=>now()],
            ]);
        }
    }
}
