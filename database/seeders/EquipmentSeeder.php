<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EquipmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Equipment::create([
            'name' => 'Kule Vinç KV-01',
            'type' => 'Kule Vinç',
            'plate_number' => null,
            'serial_number' => null,
            'status' => 'Çalışıyor',
            'current_project_id' => null,
            'operator' => 'Ali Vural',
            'next_maintenance_date' => '2025-10-15',
            'brand' => 'Liebherr',
            'model' => '150 EC-B',
            'year' => 2020,
            'latitude' => 39.9208,
            'longitude' => 32.8541,
            'working_hours' => 1250,
            'fuel_level' => 75,
            'ownership' => 'Sahip',
            'purchase_date' => '2020-03-15',
            'purchase_cost' => 2500000.00,
            'notes' => 'Yeni kuleden sonra en verimli vinç'
        ]);

        \App\Models\Equipment::create([
            'name' => 'Ekskavatör EX-03',
            'type' => 'Paletli Ekskavatör',
            'plate_number' => '34 ABC 123',
            'serial_number' => 'LEX-2020-003',
            'status' => 'Bakımda',
            'current_project_id' => null,
            'operator' => null,
            'next_maintenance_date' => null,
            'brand' => 'Caterpillar',
            'model' => '320D',
            'year' => 2019,
            'latitude' => null,
            'longitude' => null,
            'working_hours' => 3200,
            'fuel_level' => null,
            'ownership' => 'Kiralık',
            'purchase_date' => null,
            'purchase_cost' => null,
            'notes' => 'Kiralık makine - aylık bakım sözleşmesi var'
        ]);

        \App\Models\Equipment::create([
            'name' => 'Kamyon KM-05',
            'type' => 'Kamyon',
            'plate_number' => '34 DEF 456',
            'serial_number' => 'TRK-2018-005',
            'status' => 'Boşta',
            'current_project_id' => null,
            'operator' => null,
            'next_maintenance_date' => '2025-11-01',
            'brand' => 'Mercedes',
            'model' => 'Actros 1845',
            'year' => 2018,
            'latitude' => 39.9334,
            'longitude' => 32.8597,
            'working_hours' => 4500,
            'fuel_level' => 90,
            'ownership' => 'Sahip',
            'purchase_date' => '2018-06-20',
            'purchase_cost' => 1200000.00,
            'notes' => 'Şantiye taşımacılığı için optimize edilmiş'
        ]);
    }
}
