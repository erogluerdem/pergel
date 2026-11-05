<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Unit;
use App\Models\Project;

class DemoUnitsSeeder extends Seeder
{
    public function run()
    {
        $projects = Project::all();
        if ($projects->isEmpty()) {
            return;
        }

        $demoUnits = [
            [
                'project_id' => $projects[0]->id,
                'unit_no' => 'A-101',
                'block' => 'A Blok',
                'floor' => 10,
                'unit_type' => '3+1 Lüks Konut',
                'area' => 145.50,
                'room_count' => 3,
                'bathroom_count' => 2,
                'list_price' => 12500000.00,
                'selling_price' => 11200000.00,
                'max_discount_rate' => 12.00,
                'currency' => 'TRY',
                'status' => 'available',
                'description' => 'Güney-Doğu cepheli, deniz manzaralı, geniş balkonlu 3+1 daire.'
            ],
            [
                'project_id' => $projects[0]->id,
                'unit_no' => 'A-102',
                'block' => 'A Blok',
                'floor' => 10,
                'unit_type' => '2+1 Konut',
                'area' => 95.00,
                'room_count' => 2,
                'bathroom_count' => 1,
                'list_price' => 8900000.00,
                'selling_price' => 8100000.00,
                'max_discount_rate' => 10.00,
                'currency' => 'TRY',
                'status' => 'reserved',
                'description' => 'Peyzaj manzaralı, ebeveyn banyolu, ankastre setli 2+1 daire.'
            ],
            [
                'project_id' => $projects[1]->id ?? $projects[0]->id,
                'unit_no' => 'B-304',
                'block' => 'B Blok',
                'floor' => 3,
                'unit_type' => 'Ticari Ofis',
                'area' => 210.00,
                'room_count' => 4,
                'bathroom_count' => 2,
                'list_price' => 24500000.00,
                'selling_price' => 22000000.00,
                'max_discount_rate' => 15.00,
                'currency' => 'TRY',
                'status' => 'available',
                'description' => 'Plaza katında, açık ofis düzenine uygun, 2 araçlık kapalı otopark tahsisli.'
            ],
            [
                'project_id' => $projects[1]->id ?? $projects[0]->id,
                'unit_no' => 'PH-2501',
                'block' => 'C Kule',
                'floor' => 25,
                'unit_type' => 'Penthouse (4+2)',
                'area' => 320.00,
                'room_count' => 4,
                'bathroom_count' => 3,
                'list_price' => 48000000.00,
                'selling_price' => 44000000.00,
                'max_discount_rate' => 10.00,
                'currency' => 'TRY',
                'status' => 'sold',
                'description' => 'Özel teraslı, panoramik şehir ve boğaz manzaralı, akıllı ev sistemli Penthouse.'
            ],
            [
                'project_id' => $projects[2]->id ?? $projects[0]->id,
                'unit_no' => 'V-08',
                'block' => 'Villa Etabı',
                'floor' => 2,
                'unit_type' => 'Müstakil Villa (5+2)',
                'area' => 450.00,
                'room_count' => 5,
                'bathroom_count' => 4,
                'list_price' => 65000000.00,
                'selling_price' => 59500000.00,
                'max_discount_rate' => 10.00,
                'currency' => 'TRY',
                'status' => 'available',
                'description' => 'Özel yüzme havuzlu, müstakil bahçeli, 4 araçlık kapalı garajlı lüks villa.'
            ],
            [
                'project_id' => $projects[0]->id,
                'unit_no' => 'DK-04',
                'block' => 'Çarşı Blok',
                'floor' => 0,
                'unit_type' => 'Ticari Dükkan / Mağaza',
                'area' => 180.00,
                'room_count' => 2,
                'bathroom_count' => 1,
                'list_price' => 32000000.00,
                'selling_price' => 29500000.00,
                'max_discount_rate' => 8.00,
                'currency' => 'TRY',
                'status' => 'maintenance',
                'description' => 'Ana cadde cepheli, yüksek bacalı, kurumsal kiracıya uygun mağaza alanı.'
            ]
        ];

        foreach ($demoUnits as $unit) {
            Unit::updateOrCreate(
                ['unit_no' => $unit['unit_no'], 'project_id' => $unit['project_id']],
                $unit
            );
        }
    }
}
