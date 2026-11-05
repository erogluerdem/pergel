<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\StockLocation;

class StockLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locations = [
            [
                'code' => 'MER-DEP-01',
                'name' => 'Merkez Depo',
                'description' => 'Ana merkez depo - Genel malzemeler için',
                'type' => 'warehouse',
                'building' => 'A Blok',
                'floor' => 'Zemin Kat',
                'zone' => 'Kuzey',
                'address' => 'Organize Sanayi Bölgesi 1. Cadde No:15',
                'city' => 'İstanbul',
                'country' => 'Turkey',
                'latitude' => 41.0082,
                'longitude' => 28.9784,
                'capacity' => 50000.00,
                'capacity_unit' => 'TL',
                'temperature_controlled' => 'no',
                'security_level' => 'high',
                'manager_name' => 'Ahmet Yılmaz',
                'manager_email' => 'ahmet.yilmaz@company.com',
                'manager_phone' => '+90 555 123 4567',
                'is_active' => true,
                'working_hours' => [
                    'monday' => ['08:00', '17:00'],
                    'tuesday' => ['08:00', '17:00'],
                    'wednesday' => ['08:00', '17:00'],
                    'thursday' => ['08:00', '17:00'],
                    'friday' => ['08:00', '17:00'],
                    'saturday' => ['09:00', '13:00'],
                    'sunday' => 'closed'
                ],
                'notes' => 'Ana depo lokasyonu. Tüm büyük malzemeler burada saklanır.'
            ],
            [
                'code' => 'SOG-DEP-01',
                'name' => 'Soğuk Hava Deposu',
                'description' => 'Sıcaklık kontrollü özel malzemeler için',
                'type' => 'warehouse',
                'building' => 'C Blok',
                'floor' => 'Bodrum',
                'zone' => 'Güney',
                'address' => 'Organize Sanayi Bölgesi 3. Cadde No:8',
                'city' => 'İstanbul',
                'country' => 'Turkey',
                'latitude' => 41.0085,
                'longitude' => 28.9790,
                'capacity' => 15000.00,
                'capacity_unit' => 'TL',
                'temperature_controlled' => 'cold',
                'security_level' => 'restricted',
                'manager_name' => 'Fatma Kaya',
                'manager_email' => 'fatma.kaya@company.com',
                'manager_phone' => '+90 555 987 6543',
                'is_active' => true,
                'working_hours' => [
                    'monday' => ['08:00', '16:00'],
                    'tuesday' => ['08:00', '16:00'],
                    'wednesday' => ['08:00', '16:00'],
                    'thursday' => ['08:00', '16:00'],
                    'friday' => ['08:00', '16:00'],
                    'saturday' => 'closed',
                    'sunday' => 'closed'
                ],
                'notes' => '+2°C / +8°C arasında tutulan özel malzemeler.'
            ],
            [
                'code' => 'BUR-ODA-01',
                'name' => 'Büro Malzeme Odası',
                'description' => 'Ofis malzemeleri ve kırtasiye',
                'type' => 'room',
                'building' => 'B Blok',
                'floor' => '1. Kat',
                'zone' => 'Doğu',
                'address' => 'Organize Sanayi Bölgesi 2. Cadde No:12',
                'city' => 'İstanbul',
                'country' => 'Turkey',
                'latitude' => 41.0080,
                'longitude' => 28.9780,
                'capacity' => 5000.00,
                'capacity_unit' => 'TL',
                'temperature_controlled' => 'no',
                'security_level' => 'standard',
                'manager_name' => 'Mehmet Demir',
                'manager_email' => 'mehmet.demir@company.com',
                'manager_phone' => '+90 555 111 2233',
                'is_active' => true,
                'working_hours' => [
                    'monday' => ['09:00', '18:00'],
                    'tuesday' => ['09:00', '18:00'],
                    'wednesday' => ['09:00', '18:00'],
                    'thursday' => ['09:00', '18:00'],
                    'friday' => ['09:00', '18:00'],
                    'saturday' => 'closed',
                    'sunday' => 'closed'
                ],
                'notes' => 'Büro malzemeleri, kağıt, kalem, klasör vb.'
            ],
            [
                'code' => 'YED-DEP-01',
                'name' => 'Yedek Parça Deposu',
                'description' => 'Makine ve ekipman yedek parçaları',
                'type' => 'warehouse',
                'building' => 'D Blok',
                'floor' => 'Zemin Kat',
                'zone' => 'Batı',
                'address' => 'Organize Sanayi Bölgesi 4. Cadde No:20',
                'city' => 'İstanbul',
                'country' => 'Turkey',
                'latitude' => 41.0075,
                'longitude' => 28.9775,
                'capacity' => 25000.00,
                'capacity_unit' => 'TL',
                'temperature_controlled' => 'no',
                'security_level' => 'high',
                'manager_name' => 'Zeynep Özkan',
                'manager_email' => 'zeynep.ozkan@company.com',
                'manager_phone' => '+90 555 444 5566',
                'is_active' => true,
                'working_hours' => [
                    'monday' => ['07:00', '16:00'],
                    'tuesday' => ['07:00', '16:00'],
                    'wednesday' => ['07:00', '16:00'],
                    'thursday' => ['07:00', '16:00'],
                    'friday' => ['07:00', '16:00'],
                    'saturday' => ['08:00', '12:00'],
                    'sunday' => 'closed'
                ],
                'notes' => 'Kritik yedek parçalar ve ekipmanlar.'
            ],
            [
                'code' => 'TEH-RAF-A1',
                'name' => 'Tehlikeli Madde Rafı A1',
                'description' => 'Kimyasal ve tehlikeli maddeler',
                'type' => 'shelf',
                'building' => 'E Blok',
                'floor' => 'Zemin Kat',
                'zone' => 'İzole Alan',
                'address' => 'Organize Sanayi Bölgesi 5. Cadde No:25',
                'city' => 'İstanbul',
                'country' => 'Turkey',
                'latitude' => 41.0070,
                'longitude' => 28.9770,
                'capacity' => 3000.00,
                'capacity_unit' => 'TL',
                'temperature_controlled' => 'yes',
                'security_level' => 'restricted',
                'manager_name' => 'Dr. Ali Şahin',
                'manager_email' => 'ali.sahin@company.com',
                'manager_phone' => '+90 555 777 8899',
                'is_active' => true,
                'working_hours' => [
                    'monday' => ['09:00', '17:00'],
                    'tuesday' => ['09:00', '17:00'],
                    'wednesday' => ['09:00', '17:00'],
                    'thursday' => ['09:00', '17:00'],
                    'friday' => ['09:00', '17:00'],
                    'saturday' => 'closed',
                    'sunday' => 'closed'
                ],
                'notes' => 'Sadece yetkili personel erişebilir. MSDS belgeleri gerekli.'
            ]
        ];

        foreach ($locations as $location) {
            StockLocation::create($location);
        }
    }
}
