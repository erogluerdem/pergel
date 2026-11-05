<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\InventoryItem;
use App\Models\User;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();
        $userId = $user ? $user->id : 1;
        
        $items = [
            [
                'name' => 'İnşaat Çimento',
                'sku' => 'CEM-001',
                'description' => 'Portland çimentosu - 50kg torbalar',
                'category' => 'İnşaat Malzemeleri',
                'price' => 45.50,
                'quantity' => 120,
                'min_quantity' => 20,
                'unit' => 'torba',
                'supplier' => 'Akçansa',
                'location' => 'Depo A-1',
                'status' => 'active',
                'created_by' => $userId,
            ],
            [
                'name' => 'Demir İnşaat Çubuğu 12mm',
                'sku' => 'DMR-012',
                'description' => '12mm çapında nervürlü inşaat demiri',
                'category' => 'Demir Çelik',
                'price' => 8.75,
                'quantity' => 250,
                'min_quantity' => 50,
                'unit' => 'adet',
                'supplier' => 'Ereğli Demir Çelik',
                'location' => 'Açık Alan B',
                'status' => 'active',
                'created_by' => $userId,
            ],
            [
                'name' => 'Kablolu Matkap',
                'sku' => 'MAT-001',
                'description' => 'Bosch Professional 13mm kablolu matkap',
                'category' => 'El Aletleri',
                'price' => 285.00,
                'quantity' => 8,
                'min_quantity' => 2,
                'unit' => 'adet',
                'supplier' => 'Bosch',
                'location' => 'Alet Deposu',
                'status' => 'active',
                'created_by' => $userId,
            ],
            [
                'name' => 'Güvenlik Kaskı',
                'sku' => 'GVN-001',
                'description' => 'Beyaz güvenlik kaskı - CE onaylı',
                'category' => 'Güvenlik Ekipmanları',
                'price' => 25.00,
                'quantity' => 45,
                'min_quantity' => 10,
                'unit' => 'adet',
                'supplier' => '3M',
                'location' => 'Güvenlik Deposu',
                'status' => 'active',
                'created_by' => $userId,
            ],
            [
                'name' => 'Elektrik Kablosu 2.5mm',
                'sku' => 'ELK-025',
                'description' => '2.5mm² NYA elektrik kablosu - 100m makara',
                'category' => 'Elektrik Malzemeleri',
                'price' => 125.00,
                'quantity' => 15,
                'min_quantity' => 5,
                'unit' => 'makara',
                'supplier' => 'Türk Kablo',
                'location' => 'Elektrik Deposu',
                'status' => 'active',
                'created_by' => $userId,
            ],
            [
                'name' => 'Su Borusu PVC 50mm',
                'sku' => 'BRU-050',
                'description' => 'PVC su borusu 50mm çap - 6 metre',
                'category' => 'Tesisat Malzemeleri',
                'price' => 18.50,
                'quantity' => 30,
                'min_quantity' => 10,
                'unit' => 'adet',
                'supplier' => 'Pimapen',
                'location' => 'Tesisat Deposu',
                'status' => 'active',
                'created_by' => $userId,
            ],
            [
                'name' => 'Beton Karıştırıcı',
                'sku' => 'BET-001',
                'description' => '150L beton karıştırıcı - elektrikli',
                'category' => 'Makineler',
                'price' => 1250.00,
                'quantity' => 3,
                'min_quantity' => 1,
                'unit' => 'adet',
                'supplier' => 'Imer',
                'location' => 'Makine Parkı',
                'status' => 'active',
                'created_by' => $userId,
            ],
            [
                'name' => 'İzolasyon Malzemesi',
                'sku' => 'IZO-001',
                'description' => 'EPS dış cephe izolasyon levhası 5cm',
                'category' => 'İzolasyon',
                'price' => 12.75,
                'quantity' => 200,
                'min_quantity' => 50,
                'unit' => 'm2',
                'supplier' => 'İzocam',
                'location' => 'İzolasyon Deposu',
                'status' => 'active',
                'created_by' => $userId,
            ],
        ];
        
        foreach ($items as $item) {
            InventoryItem::create($item);
        }
    }
}
