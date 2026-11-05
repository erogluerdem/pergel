<?php

namespace Database\Seeders;

use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\Vendor;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoPurchasingQuotationSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::first();
        
        // Ensure vendors exist
        $vendor1 = Vendor::firstOrCreate(
            ['name' => 'Aksu Beton Sanayi A.Ş.'],
            [
                'tax_number' => '1234567890',
                'specialty' => 'Hazır Beton & Mıcır',
                'status' => 'active',
                'performance_score' => 95,
                'contact_person' => 'Mehmet Aksu',
                'phone' => '0532 111 2233',
                'email' => 'satis@aksubeton.com',
                'address' => 'Güneşli OSB No:45 İstanbul',
                'city' => 'İstanbul',
                'country' => 'Türkiye',
            ]
        );

        $vendor2 = Vendor::firstOrCreate(
            ['name' => 'Karakaya Çelik & Demir Ltd.'],
            [
                'tax_number' => '9876543210',
                'specialty' => 'Nervürlü Demir & Profil',
                'status' => 'active',
                'performance_score' => 92,
                'contact_person' => 'Ali Karakaya',
                'phone' => '0533 222 3344',
                'email' => 'teklif@karakayacelik.com',
                'address' => 'Gebze Sanayi Sitesi Kocaeli',
                'city' => 'Kocaeli',
                'country' => 'Türkiye',
            ]
        );

        $vendor3 = Vendor::firstOrCreate(
            ['name' => 'Özkan MEP Tesisat & Elektrik A.Ş.'],
            [
                'tax_number' => '5554443322',
                'specialty' => 'Mekanik & Elektrik',
                'status' => 'active',
                'performance_score' => 88,
                'contact_person' => 'Hasan Özkan',
                'phone' => '0535 444 5566',
                'email' => 'info@ozkanmep.com',
                'address' => 'İkitelli OSB No:12 İstanbul',
                'city' => 'İstanbul',
                'country' => 'Türkiye',
            ]
        );

        $quotesData = [
            [
                'vendor_id' => $vendor1->id,
                'quote_number' => 'QT-2026-000101',
                'quote_date' => now()->subDays(3),
                'valid_until' => now()->addDays(20),
                'total_amount' => 1250000.00,
                'currency' => 'TRY',
                'status' => 'pending',
                'notes' => 'C35/40 hazır beton ve pompa teslimi birim fiyat teklifi. Vadesi 45 gün.',
                'created_by' => $admin?->id,
                'items' => [
                    [
                        'description' => 'C35/40 Katkılı Slump 18 Hazır Beton',
                        'quantity' => 450,
                        'unit_price' => 2200.00,
                        'total_price' => 990000.00,
                        'delivery_days' => 2,
                    ],
                    [
                        'description' => 'Beton Pompası Saha Kurulum & Hizmet Bedeli',
                        'quantity' => 10,
                        'unit_price' => 26000.00,
                        'total_price' => 260000.00,
                        'delivery_days' => 1,
                    ],
                ]
            ],
            [
                'vendor_id' => $vendor2->id,
                'quote_number' => 'QT-2026-000102',
                'quote_date' => now()->subDays(5),
                'valid_until' => now()->addDays(15),
                'total_amount' => 2850000.00,
                'currency' => 'TRY',
                'status' => 'approved',
                'notes' => 'S420 Nervürlü donatı demiri ton fiyatı teklifi. Şantiye indirme dahil.',
                'created_by' => $admin?->id,
                'items' => [
                    [
                        'description' => 'Ø12 - Ø25 S420 Nervürlü İnşaat Demiri (Ton)',
                        'quantity' => 120,
                        'unit_price' => 21500.00,
                        'total_price' => 2580000.00,
                        'delivery_days' => 3,
                    ],
                    [
                        'description' => 'Ø8 - Ø10 Etriye Demiri (Ton)',
                        'quantity' => 12,
                        'unit_price' => 22500.00,
                        'total_price' => 270000.00,
                        'delivery_days' => 3,
                    ],
                ]
            ],
            [
                'vendor_id' => $vendor3->id,
                'quote_number' => 'QT-2026-000103',
                'quote_date' => now()->subDays(8),
                'valid_until' => now()->addDays(10),
                'total_amount' => 840000.00,
                'currency' => 'TRY',
                'status' => 'rejected',
                'notes' => 'A-Blok mekanik havalandırma kanalları ve izolasyon malzemeleri birim fiyat teklifi.',
                'created_by' => $admin?->id,
                'items' => [
                    [
                        'description' => 'Galvaniz Sac Havalandırma Kanalı (m²)',
                        'quantity' => 850,
                        'unit_price' => 750.00,
                        'total_price' => 637500.00,
                        'delivery_days' => 5,
                    ],
                    [
                        'description' => 'Kauçuk Köpüğü Kanal İzolasyonu 19mm (m²)',
                        'quantity' => 850,
                        'unit_price' => 238.23,
                        'total_price' => 202500.00,
                        'delivery_days' => 5,
                    ],
                ]
            ],
        ];

        foreach ($quotesData as $qData) {
            $items = $qData['items'];
            unset($qData['items']);

            $quotation = Quotation::create($qData);
            foreach ($items as $item) {
                $item['quotation_id'] = $quotation->id;
                QuotationItem::create($item);
            }
        }
    }
}
