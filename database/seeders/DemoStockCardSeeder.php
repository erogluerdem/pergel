<?php

namespace Database\Seeders;

use App\Models\StockCard;
use Illuminate\Database\Seeder;

class DemoStockCardSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'code' => 'STK-00101',
                'name' => 'CEM I 42.5R Portland Çimento (50 kg Torba)',
                'description' => 'A-Blok betonarme ve sıva imalatları için yüksek dayanımlı torbalı çimento.',
                'category' => 'Inşaat Malzemesi',
                'unit' => 'Torba',
                'min_stock' => 100.00,
                'max_stock' => 1000.00,
                'current_stock' => 650.00,
                'unit_price' => 185.00,
                'total_value' => 120250.00,
                'location' => 'Şantiye A Deposu - Raf 04',
                'supplier' => 'Akçansa Çimento A.Ş.',
                'status' => 'active',
                'barcode' => '8690001001012',
                'notes' => 'Nemden uzak tutulmalıdır, paletli muhafaza edilmektedir.',
            ],
            [
                'code' => 'STK-00102',
                'name' => 'S420 Nervürlü İnşaat Demiri Ø16mm',
                'description' => 'Temel ve kolon donatı imalatlarında kullanılan TS 708 normlu nervürlü çubuk demir.',
                'category' => 'Demir & Çelik',
                'unit' => 'Ton',
                'min_stock' => 15.00,
                'max_stock' => 100.00,
                'current_stock' => 8.00, // Critical stock!
                'unit_price' => 21800.00,
                'total_value' => 174400.00,
                'location' => 'Açık Stok Sahası - Bölüm B',
                'supplier' => 'Karakaya Çelik Ltd.',
                'status' => 'active',
                'barcode' => '8690001001029',
                'notes' => 'Stok kritik seviyenin altında! Acil sipariş verilmelidir.',
            ],
            [
                'code' => 'STK-00103',
                'name' => 'PVC Atık Su Borusu Ø110mm x 3m',
                'description' => 'Bina içi pis su ve yağmur suyu tesisatı borusu.',
                'category' => 'Tesisat & Boru',
                'unit' => 'Boy',
                'min_stock' => 50.00,
                'max_stock' => 300.00,
                'current_stock' => 180.00,
                'unit_price' => 240.00,
                'total_value' => 43200.00,
                'location' => 'MEP Deposu - Raf 12',
                'supplier' => 'Fırat Plastik A.Ş.',
                'status' => 'active',
                'barcode' => '8690001001036',
                'notes' => 'Conta ve ek parçaları ile temin edilmiştir.',
            ],
            [
                'code' => 'STK-00104',
                'name' => 'Taşyünü Mantolama Levhası 5cm (150 kg/m³)',
                'description' => 'Dış cephe ısı ve ses yalıtımı için A1 sınıfı yanmaz taşyünü levha.',
                'category' => 'Yalıtım & İzolasyon',
                'unit' => 'm²',
                'min_stock' => 200.00,
                'max_stock' => 2000.00,
                'current_stock' => 1100.00,
                'unit_price' => 320.00,
                'total_value' => 352000.00,
                'location' => 'B-Blok Zemin Depo',
                'supplier' => 'Izocam A.Ş.',
                'status' => 'active',
                'barcode' => '8690001001043',
                'notes' => 'Dış cephe taşeronuna zimmetli teslim edilmektedir.',
            ],
            [
                'code' => 'STK-00105',
                'name' => 'NYM 3x2.5mm² Antigron Elektrik Kablosu',
                'description' => 'Sıva üstü ve sıva altı aydınlatma ve priz linyeleri için bakır iletkenli kablo.',
                'category' => 'Elektrik & Aydınlatma',
                'unit' => 'Metre',
                'min_stock' => 500.00,
                'max_stock' => 5000.00,
                'current_stock' => 3200.00,
                'unit_price' => 42.50,
                'total_value' => 136000.00,
                'location' => 'Elektrik Atölyesi',
                'supplier' => 'Hes Kablo A.Ş.',
                'status' => 'active',
                'barcode' => '8690001001050',
                'notes' => '100m kangallar halinde makaralarda saklanmaktadır.',
            ],
        ];

        foreach ($items as $item) {
            StockCard::create($item);
        }
    }
}
