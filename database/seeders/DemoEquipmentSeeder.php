<?php

namespace Database\Seeders;

use App\Models\Equipment;
use App\Models\Project;
use Illuminate\Database\Seeder;

class DemoEquipmentSeeder extends Seeder
{
    public function run(): void
    {
        $project = Project::first();

        // Clear existing demo equipment if re-seeding
        Equipment::query()->delete();

        $equipments = [
            [
                'name' => 'CAT 336 Paletli Ağır Ekskavatör',
                'type' => 'Ağır İş Makinesi',
                'plate_number' => '34 EKS 884',
                'serial_number' => 'CAT-336-99412',
                'status' => 'Çalışıyor',
                'current_project_id' => $project?->id,
                'operator' => 'Ahmet Yılmaz (Sertifikalı Operatör)',
                'next_maintenance_date' => now()->addDays(12),
                'brand' => 'Caterpillar',
                'model' => '336 Next Gen',
                'year' => 2023,
                'latitude' => 41.008500,
                'longitude' => 28.978200,
                'working_hours' => 2450,
                'fuel_level' => 85,
                'ownership' => 'Sahip',
                'purchase_date' => now()->subMonths(14),
                'purchase_cost' => 14500000.00,
                'notes' => 'A-Blok temel kazısı ve zemin ıslahı çalışmasında aktif görevde.',
            ],
            [
                'name' => 'Volvo L180H Lastikli Yükleyici (Loder)',
                'type' => 'Yükleyici & Loder',
                'plate_number' => '34 LDR 552',
                'serial_number' => 'VLV-180-4421',
                'status' => 'Çalışıyor',
                'current_project_id' => $project?->id,
                'operator' => 'Mustafa Demir (Operatör)',
                'next_maintenance_date' => now()->subDays(2), // Overdue!
                'brand' => 'Volvo',
                'model' => 'L180H',
                'year' => 2022,
                'latitude' => 41.009800,
                'longitude' => 28.979500,
                'working_hours' => 3890,
                'fuel_level' => 35, // Low fuel!
                'ownership' => 'Sahip',
                'purchase_date' => now()->subMonths(26),
                'purchase_cost' => 11200000.00,
                'notes' => 'Mıcır ve hafriyat yükleme operasyonunda. Periyodik yağ bakımı gecikmiş!',
            ],
            [
                'name' => 'Liebherr 120 Ton Kule Vinç',
                'type' => 'Kule Vinç & Kaldırma',
                'plate_number' => 'VNC-120-01',
                'serial_number' => 'LBH-120-7721',
                'status' => 'Çalışıyor',
                'current_project_id' => $project?->id,
                'operator' => 'Hasan Kara (Vinç Operatörü)',
                'next_maintenance_date' => now()->addDays(5),
                'brand' => 'Liebherr',
                'model' => '280 EC-H 12',
                'year' => 2021,
                'latitude' => 41.007500,
                'longitude' => 28.977100,
                'working_hours' => 4120,
                'fuel_level' => 100, // Electric
                'ownership' => 'Kiralık',
                'purchase_date' => now()->subMonths(30),
                'purchase_cost' => 0.00,
                'notes' => 'Kule vinç rüzgar anemometre sensörleri ve halat sapan muayenesi güncel.',
            ],
            [
                'name' => 'Mercedes-Benz Actros 3345 Damperli Kamyon',
                'type' => 'Hafriyat Kamyonu',
                'plate_number' => '34 KMY 991',
                'serial_number' => 'MB-3345-1029',
                'status' => 'Bakımda',
                'current_project_id' => $project?->id,
                'operator' => 'Kemal Şahin',
                'next_maintenance_date' => now()->subDays(1),
                'brand' => 'Mercedes-Benz',
                'model' => 'Actros 3345 K',
                'year' => 2020,
                'latitude' => 41.006200,
                'longitude' => 28.975400,
                'working_hours' => 5600,
                'fuel_level' => 60,
                'ownership' => 'Sahip',
                'purchase_date' => now()->subMonths(36),
                'purchase_cost' => 4800000.00,
                'notes' => 'Şantiye servisinde fren balata ve makas bakımı yapılıyor.',
            ],
        ];

        foreach ($equipments as $e) {
            Equipment::create($e);
        }
    }
}
