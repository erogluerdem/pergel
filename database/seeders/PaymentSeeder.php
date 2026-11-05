<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    public function run()
    {
        $p = \App\Models\Payment::create(['no'=>'HK-021-003','contractor'=>'A İnşaat','contract'=>'Kaba İnşaat Sözleşmesi','date'=>'2025-09-25','requested'=>450000,'status'=>'Saha Onayında']);
        \App\Models\PaymentItem::create(['payment_id'=>$p->id,'pos'=>'160.01.001','description'=>'C30 Beton','unit'=>'m³','contract_qty'=>1200,'prev_qty'=>450,'this_qty'=>250,'total_qty'=>700,'unit_price'=>2000,'this_amount'=>500000]);
        \App\Models\PaymentItem::create(['payment_id'=>$p->id,'pos'=>'160.02.005','description'=>'Nervürlü Demir','unit'=>'ton','contract_qty'=>85,'prev_qty'=>30,'this_qty'=>15,'total_qty'=>45,'unit_price'=>25000,'this_amount'=>375000]);
    }
}
