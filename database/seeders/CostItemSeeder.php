<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CostItem;
use App\Models\Commitment;

class CostItemSeeder extends Seeder
{
    public function run()
    {
        if (CostItem::count() > 0) return;

        $parent = CostItem::create([
            'code' => '160', 'name' => 'Kaba İnşaat İşleri', 'original' => 2000000, 'changes' => 0, 'revised' => 2000000, 'committed' => 1500000, 'actual' => 1200000, 'ttm' => 2100000
        ]);

        $c1 = CostItem::create(['parent_id'=>$parent->id, 'code'=>'160.01', 'name'=>'Beton', 'original'=>800000,'revised'=>800000,'committed'=>600000,'actual'=>480000,'ttm'=>840000]);
        $c2 = CostItem::create(['parent_id'=>$parent->id, 'code'=>'160.02', 'name'=>'Demir', 'original'=>600000,'revised'=>600000,'committed'=>500000,'actual'=>420000,'ttm'=>630000]);

        $parent2 = CostItem::create(['code'=>'170','name'=>'İnce İşler','original'=>1000000,'revised'=>1000000,'committed'=>400000,'actual'=>200000,'ttm'=>1100000]);

        // Commitments sample
        Commitment::create(['cost_item_id'=>$c1->id,'contract_number'=>'C-1001','amount'=>600000,'invoiced'=>480000]);
        Commitment::create(['cost_item_id'=>$c2->id,'contract_number'=>'C-1002','amount'=>500000,'invoiced'=>420000]);
    }
}
