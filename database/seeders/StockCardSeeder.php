<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StockCardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stockCards = [
            [
                'code' => 'STK001',
                'name' => 'Çimento Portland PC-32.5',
                'description' => 'Yapı malzemesi çimento, 50kg torba',
                'category' => 'Yapı Malzemeleri',
                'unit' => 'torba',
                'min_stock' => 100,
                'max_stock' => 500,
                'current_stock' => 250,
                'unit_price' => 45.50,
                'total_value' => 11375.00,
                'location' => 'Depo A',
                'supplier' => 'Akçansa Çimento',
                'status' => 'active',
                'barcode' => '8694567890123'
            ],
            [
                'code' => 'STK002',
                'name' => 'Demir Profil 14mm',
                'description' => 'Nervürlü inşaat demiri, 12m boy',
                'category' => 'Demir Malzemeler',
                'unit' => 'adet',
                'min_stock' => 50,
                'max_stock' => 200,
                'current_stock' => 30,
                'unit_price' => 85.75,
                'total_value' => 2572.50,
                'location' => 'Depo B',
                'supplier' => 'Ereğli Demir Çelik',
                'status' => 'active',
                'barcode' => '8694567890124'
            ],
            [
                'code' => 'STK003',
                'name' => 'Tuğla Kırmızı 19x9x6',
                'description' => 'Standart yapı tuğlası',
                'category' => 'Yapı Malzemeleri',
                'unit' => 'adet',
                'min_stock' => 1000,
                'max_stock' => 5000,
                'current_stock' => 3500,
                'unit_price' => 2.25,
                'total_value' => 7875.00,
                'location' => 'Açık Alan 1',
                'supplier' => 'Kırşehir Tuğla',
                'status' => 'active',
                'barcode' => '8694567890125'
            ],
            [
                'code' => 'STK004',
                'name' => 'Boya Astar Beyaz',
                'description' => 'Su bazlı astar boya, 15L teneke',
                'category' => 'Boyalar',
                'unit' => 'teneke',
                'min_stock' => 20,
                'max_stock' => 100,
                'current_stock' => 15,
                'unit_price' => 125.00,
                'total_value' => 1875.00,
                'location' => 'Depo C',
                'supplier' => 'Marshall Boya',
                'status' => 'active',
                'barcode' => '8694567890126'
            ],
            [
                'code' => 'STK005',
                'name' => 'Elektrik Kablosu 2.5mm',
                'description' => 'NYA kablo, 100m makara',
                'category' => 'Elektrik Malzemeleri',
                'unit' => 'makara',
                'min_stock' => 10,
                'max_stock' => 50,
                'current_stock' => 25,
                'unit_price' => 180.00,
                'total_value' => 4500.00,
                'location' => 'Depo D',
                'supplier' => 'Nexans Kablo',
                'status' => 'active',
                'barcode' => '8694567890127'
            ],
            [
                'code' => 'STK006',
                'name' => 'Seramik Karo 30x30',
                'description' => 'Banyo seramiği, beyaz renk',
                'category' => 'Seramik',
                'unit' => 'm²',
                'min_stock' => 100,
                'max_stock' => 300,
                'current_stock' => 180,
                'unit_price' => 35.50,
                'total_value' => 6390.00,
                'location' => 'Depo E',
                'supplier' => 'Kaleseramik',
                'status' => 'active',
                'barcode' => '8694567890128'
            ],
            [
                'code' => 'STK007',
                'name' => 'Çivi 10cm',
                'description' => 'Galvanizli çivi, 1kg paket',
                'category' => 'Hırdavat',
                'unit' => 'kg',
                'min_stock' => 50,
                'max_stock' => 200,
                'current_stock' => 8,
                'unit_price' => 12.50,
                'total_value' => 100.00,
                'location' => 'Depo F',
                'supplier' => 'Hırdavat Market',
                'status' => 'active',
                'barcode' => '8694567890129'
            ],
            [
                'code' => 'STK008',
                'name' => 'PVC Boru 110mm',
                'description' => 'Atık su borusu, 6m boy',
                'category' => 'Tesisat',
                'unit' => 'adet',
                'min_stock' => 30,
                'max_stock' => 150,
                'current_stock' => 75,
                'unit_price' => 55.00,
                'total_value' => 4125.00,
                'location' => 'Depo G',
                'supplier' => 'Pimsa Plastik',
                'status' => 'active',
                'barcode' => '8694567890130'
            ]
        ];

        foreach ($stockCards as $card) {
            \App\Models\StockCard::create($card);
        }

        // Stok hareketleri ekle
        $stockCards = \App\Models\StockCard::all();
        foreach ($stockCards as $card) {
            // Giriş hareketi
            \App\Models\StockMovement::create([
                'stock_card_id' => $card->id,
                'type' => 'in',
                'quantity' => $card->current_stock,
                'unit_price' => $card->unit_price,
                'total_amount' => $card->current_stock * $card->unit_price,
                'reference_no' => 'INIT-' . $card->code,
                'description' => 'Başlangıç stoku',
                'created_by' => null
            ]);
        }
    }
}
