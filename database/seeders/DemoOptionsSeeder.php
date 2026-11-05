<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Option;
use App\Models\Asset;
use App\Models\Customer;
use App\Models\Opportunity;

class DemoOptionsSeeder extends Seeder
{
    public function run()
    {
        // 1. Ensure Demo Assets exist
        $assetsData = [
            [
                'asset_code' => 'AST-RE-001',
                'name' => 'Mavi Kule Kat: 14 Daire 42',
                'category' => 'building',
                'subcategory' => 'Rezidans Daire',
                'location' => 'İstanbul / Maslak',
                'purchase_price' => 14500000,
                'current_value' => 18200000,
                'status' => 'active'
            ],
            [
                'asset_code' => 'AST-RE-002',
                'name' => 'Panorama Evleri A Blok D:12',
                'category' => 'building',
                'subcategory' => 'Konut Daire (3+1)',
                'location' => 'İzmir / Alsancak',
                'purchase_price' => 9800000,
                'current_value' => 11500000,
                'status' => 'active'
            ],
            [
                'asset_code' => 'AST-RE-003',
                'name' => 'Vadistanbul Plaza Ofis 301',
                'category' => 'building',
                'subcategory' => 'Ticari Ofis',
                'location' => 'İstanbul / Ayazağa',
                'purchase_price' => 24000000,
                'current_value' => 29000000,
                'status' => 'active'
            ],
            [
                'asset_code' => 'AST-RE-004',
                'name' => 'Gökdağ Villaları No: 8',
                'category' => 'building',
                'subcategory' => 'Lüks Villa (5+2)',
                'location' => 'Muğla / Bodrum',
                'purchase_price' => 45000000,
                'current_value' => 52000000,
                'status' => 'active'
            ],
            [
                'asset_code' => 'AST-RE-005',
                'name' => 'Port Residence Daire 88',
                'category' => 'building',
                'subcategory' => 'Penthouse (4+1)',
                'location' => 'Antalya / Lara',
                'purchase_price' => 19500000,
                'current_value' => 22000000,
                'status' => 'active'
            ],
            [
                'asset_code' => 'AST-RE-006',
                'name' => 'Kanyon AVM Mağaza 12',
                'category' => 'building',
                'subcategory' => 'Ticari Dükkan',
                'location' => 'İstanbul / Levent',
                'purchase_price' => 32000000,
                'current_value' => 38000000,
                'status' => 'active'
            ]
        ];

        foreach ($assetsData as $data) {
            Asset::firstOrCreate(['asset_code' => $data['asset_code']], $data);
        }

        $assets = Asset::all();
        $customers = Customer::take(8)->get();
        $opportunities = Opportunity::take(8)->get();

        if ($customers->isEmpty()) {
            return;
        }

        // Clear existing demo options
        Option::truncate();

        $demoOptions = [
            [
                'asset_id' => $assets[0]->id ?? 1,
                'customer_id' => $customers[0]->id,
                'opportunity_id' => $opportunities[0]->id ?? null,
                'option_date' => now()->subDays(3),
                'duration_days' => 14,
                'expiry_date' => now()->addDays(11),
                'status' => 'active',
                'notes' => 'Müşteri 11 gün içerisinde %15 kaparo ödemesi gerçekleştirip satış sözleşmesini imzalayacağını belirtti.'
            ],
            [
                'asset_id' => $assets[1]->id ?? 1,
                'customer_id' => $customers[1]->id,
                'opportunity_id' => $opportunities[1]->id ?? null,
                'option_date' => now()->subDays(13),
                'duration_days' => 14,
                'expiry_date' => now()->addDays(1), // About to expire
                'status' => 'active',
                'notes' => 'Acil opsiyon! Süresinin dolmasına 1 gün kaldı. Müşteri temsilcisi tarafından takip ediliyor.'
            ],
            [
                'asset_id' => $assets[2]->id ?? 1,
                'customer_id' => $customers[2]->id,
                'opportunity_id' => $opportunities[2]->id ?? null,
                'option_date' => now()->subDays(5),
                'duration_days' => 30,
                'expiry_date' => now()->addDays(25),
                'status' => 'active',
                'notes' => 'Ticari alan kiralama ön opsiyonu. Şirket yönetim kurulu onay süreci beklenmektedir.'
            ],
            [
                'asset_id' => $assets[3]->id ?? 1,
                'customer_id' => $customers[3]->id,
                'opportunity_id' => $opportunities[3]->id ?? null,
                'option_date' => now()->subDays(20),
                'duration_days' => 14,
                'expiry_date' => now()->subDays(6),
                'status' => 'expired',
                'notes' => 'Opsiyon süresi dolmuştur. Müşteri süre uzatımı talep etmediği için portföye geri alındı.'
            ],
            [
                'asset_id' => $assets[4]->id ?? 1,
                'customer_id' => $customers[4]->id,
                'opportunity_id' => $opportunities[4]->id ?? null,
                'option_date' => now()->subDays(10),
                'duration_days' => 7,
                'expiry_date' => now()->subDays(3),
                'status' => 'converted_to_project',
                'notes' => 'Opsiyon başarıyla kesinleşti ve projeye dönüştürüldü. Satış sözleşmesi tamamlandı.'
            ],
            [
                'asset_id' => $assets[5]->id ?? 1,
                'customer_id' => $customers[5]->id,
                'opportunity_id' => $opportunities[5]->id ?? null,
                'option_date' => now()->subDays(8),
                'duration_days' => 14,
                'expiry_date' => now()->addDays(6),
                'status' => 'active',
                'notes' => 'Müşteri banka kredi onayını bekliyor. Ekspertiz raporu tamamlandı.'
            ],
            [
                'asset_id' => $assets[0]->id ?? 1,
                'customer_id' => $customers[6]->id ?? $customers[0]->id,
                'opportunity_id' => null,
                'option_date' => now()->subDays(15),
                'duration_days' => 10,
                'expiry_date' => now()->subDays(5),
                'status' => 'cancelled',
                'notes' => 'Müşteri kendi isteğiyle opsiyondan vazgeçti. Kaparo iadesi yapıldı.'
            ],
            [
                'asset_id' => $assets[1]->id ?? 1,
                'customer_id' => $customers[7]->id ?? $customers[1]->id,
                'opportunity_id' => null,
                'option_date' => now()->subDays(2),
                'duration_days' => 21,
                'expiry_date' => now()->addDays(19),
                'status' => 'active',
                'notes' => 'Yabancı yatırımcı opsiyonu. Pasaport ve vekaletname işlemleri tamamlanıyor.'
            ]
        ];

        foreach ($demoOptions as $opt) {
            Option::create($opt);
        }
    }
}
