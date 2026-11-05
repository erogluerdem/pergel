<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StockCard;
use App\Models\StockMovement;
use Carbon\Carbon;

class StockDataSeeder extends Seeder
{
    public function run()
    {
        // Mevcut stock card'ları güncelle
        $stockCards = StockCard::all();
        
        foreach ($stockCards as $card) {
            // Random stok seviyeleri ata
            $minStock = rand(10, 50);
            $maxStock = $minStock + rand(50, 200);
            $currentStock = rand($minStock - 20, $maxStock + 20); // Bazen kritik, bazen fazla olsun
            $unitPrice = rand(10, 500) / 10; // 1-50 TL arası
            
            $card->update([
                'min_stock' => $minStock,
                'max_stock' => $maxStock,
                'current_stock' => max(0, $currentStock), // Negatif olmasın
                'unit_price' => $unitPrice,
                'total_value' => max(0, $currentStock) * $unitPrice,
                'category' => $this->getRandomCategory(),
                'unit' => $this->getRandomUnit(),
                'location' => $this->getRandomLocation(),
                'supplier' => $this->getRandomSupplier(),
                'status' => 'active'
            ]);
            
            // Son 3 ay için random hareketler oluştur
            for ($i = 0; $i < rand(5, 15); $i++) {
                $type = ['in', 'out', 'adjustment'][rand(0, 2)];
                $quantity = rand(1, 50);
                $date = Carbon::now()->subDays(rand(1, 90));
                
                StockMovement::create([
                    'stock_card_id' => $card->id,
                    'type' => $type,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice + rand(-10, 10) / 10,
                    'total_amount' => $quantity * $unitPrice,
                    'reference_no' => 'REF-' . strtoupper(uniqid()),
                    'description' => $this->getRandomDescription($type),
                    'created_by' => 4,
                    'created_at' => $date,
                    'updated_at' => $date
                ]);
            }
        }
    }
    
    private function getRandomCategory()
    {
        $categories = [
            'Elektronik',
            'Ofis Malzemeleri',
            'Temizlik Ürünleri',
            'Gıda',
            'Tekstil',
            'Makine Parçaları',
            'Kırtasiye',
            'Kimyasal Ürünler'
        ];
        
        return $categories[array_rand($categories)];
    }
    
    private function getRandomUnit()
    {
        $units = ['Adet', 'Kg', 'Lt', 'M', 'M2', 'M3', 'Paket', 'Kutu'];
        return $units[array_rand($units)];
    }
    
    private function getRandomLocation()
    {
        $locations = [
            'Depo A',
            'Depo B',
            'Raf 1-A',
            'Raf 1-B',
            'Raf 2-A',
            'Soğuk Depo',
            'Ofis Deposu',
            'Dış Depo'
        ];
        
        return $locations[array_rand($locations)];
    }
    
    private function getRandomSupplier()
    {
        $suppliers = [
            'ABC Tedarik Ltd.',
            'XYZ Sanayi A.Ş.',
            'Kalite Ürünler Ltd.',
            'Hızlı Tedarik A.Ş.',
            'Global Tedarikçi Ltd.',
            'Yerel Tedarik Şti.',
            'Premium Ürünler A.Ş.',
            'Ekonomik Tedarik Ltd.'
        ];
        
        return $suppliers[array_rand($suppliers)];
    }
    
    private function getRandomDescription($type)
    {
        $descriptions = [
            'in' => [
                'Satın alma',
                'Transfer girişi',
                'İade girişi',
                'Üretim girişi',
                'Sayım fazlası'
            ],
            'out' => [
                'Satış çıkışı',
                'Transfer çıkışı',
                'Fire/kayıp',
                'Üretimde kullanım',
                'Sayım eksiği'
            ],
            'adjustment' => [
                'Sayım düzeltmesi',
                'Fiyat düzeltmesi',
                'Sistem düzeltmesi',
                'Manuel düzeltme'
            ]
        ];
        
        return $descriptions[$type][array_rand($descriptions[$type])];
    }
}