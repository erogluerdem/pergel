<?php

namespace Database\Seeders;

use App\Models\GeneralExpense;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class GumuslerVillaExpenseSeeder extends Seeder
{
    public function run(): void
    {
        $expenseDate = Carbon::create(2025, 10, 1);
        $projectCode = 'PRJ-2025-GUMUSLER';
        $currency = 'TRY';

        $expenses = [
            [
                'code' => '251 001',
                'title' => 'Gümüşler Villa Projesi (8 Adet)',
                'description' => '8 adet villa için genel başlangıç bütçesi',
                'category' => 'construction',
                'unit' => 'AD',
                'quantity' => 8,
                'amount' => 400000.00,
            ],
            [
                'code' => '251 001 02',
                'title' => 'Çimento Alımları',
                'description' => 'Şantiye temel çalışmaları için ilk parti çimento',
                'category' => 'materials',
                'unit' => 'KG',
                'quantity' => 2100,
                'amount' => 6288.33,
            ],
            [
                'code' => '251 001 03',
                'title' => 'Kalıp İşçiliği',
                'description' => 'Temel ve kolon kalıpları için saha ekibi',
                'category' => 'labor',
                'unit' => 'AD',
                'quantity' => 1,
                'amount' => 30000.00,
            ],
            [
                'code' => '251 001 04',
                'title' => 'Demir İşçiliği',
                'description' => 'Donatı bağlama ve yerleştirme hizmeti',
                'category' => 'labor',
                'unit' => 'AD',
                'quantity' => 1,
                'amount' => 50000.00,
            ],
            [
                'code' => '251 001 05',
                'title' => 'Elektrik/Tesisat Giderleri',
                'description' => 'Geçici şantiye elektriği ve tesisat kurulumu',
                'category' => 'construction',
                'unit' => 'AD',
                'quantity' => 1,
                'amount' => 60000.00,
            ],
            [
                'code' => '251 001 06',
                'title' => 'Kereste Alımları',
                'description' => 'Kalıp ve iskele için kereste tedariki',
                'category' => 'materials',
                'unit' => 'M3',
                'quantity' => 58016,
                'amount' => 498025.00,
            ],
            [
                'code' => '251 001 07',
                'title' => 'Beton Alımları',
                'description' => 'Temel ve döşeme beton dökümleri',
                'category' => 'materials',
                'unit' => 'M3',
                'quantity' => 356.5,
                'amount' => 881275.00,
            ],
            [
                'code' => '251 001 08',
                'title' => 'Demir Alımları',
                'description' => 'Çap 12-32 donatı demiri tedariki',
                'category' => 'materials',
                'unit' => 'KG',
                'quantity' => 281613,
                'amount' => 7393777.49,
            ],
            [
                'code' => '251 001 09',
                'title' => 'Su Malzemeleri Alımları',
                'description' => 'Temiz ve pis su tesisatı malzemeleri',
                'category' => 'materials',
                'unit' => 'AD',
                'quantity' => 543,
                'amount' => 40169.00,
            ],
            [
                'code' => '251 001 10',
                'title' => 'Teçhizat Malzeme Alımı',
                'description' => 'Şantiye el aletleri ve ekipmanları',
                'category' => 'equipment',
                'unit' => 'AD',
                'quantity' => 53,
                'amount' => 6036.00,
            ],
            [
                'code' => '251 001 11',
                'title' => 'Akaryakıt Giderleri',
                'description' => 'İş makineleri için yakıt',
                'category' => 'fuel',
                'unit' => 'LT',
                'quantity' => 4,
                'amount' => 6434.09,
            ],
            [
                'code' => '251 001 12',
                'title' => 'Resmi Belgeler ve Harçlar',
                'description' => 'Ruhsat, belediye izinleri ve diğer resmi harçlar',
                'category' => 'fees',
                'unit' => 'KG',
                'quantity' => 2,
                'amount' => 160000.00,
            ],
            [
                'code' => '251 001 13',
                'title' => 'Yemek Giderleri',
                'description' => 'Şantiye personeli yemek hizmeti',
                'category' => 'catering',
                'unit' => 'AD',
                'quantity' => 1,
                'amount' => 1475.00,
            ],
            [
                'code' => '251 001 14',
                'title' => 'Nakliye Giderleri',
                'description' => 'Malzeme ve ekipman taşımaları',
                'category' => 'logistics',
                'unit' => 'AD',
                'quantity' => 21,
                'amount' => 26500.00,
            ],
            [
                'code' => '251 001 15',
                'title' => 'Bims-Biriket Giderleri',
                'description' => 'Duvar örümü için bims ve briket alımı',
                'category' => 'materials',
                'unit' => 'AD',
                'quantity' => 3087,
                'amount' => 968400.00,
            ],
            [
                'code' => '251 001 16',
                'title' => 'Kolon-Kiriş Giderleri',
                'description' => 'Taşıyıcı sistem beton ve donatı maliyeti',
                'category' => 'construction',
                'unit' => 'M3',
                'quantity' => 10497,
                'amount' => 37860.00,
            ],
            [
                'code' => '251 001 17',
                'title' => 'Çivi-Tel-Kalıp Yağ Giderleri',
                'description' => 'Kalıp sarf malzemeleri ve yardımcı ürünler',
                'category' => 'materials',
                'unit' => 'KG',
                'quantity' => 2553,
                'amount' => 40214.66,
            ],
            [
                'code' => '251 001 18',
                'title' => 'İş Yeri Masrafları',
                'description' => 'Şantiye ofis ve genel işletme giderleri',
                'category' => 'overhead',
                'unit' => 'AD',
                'quantity' => 1,
                'amount' => 585.00,
            ],
            [
                'code' => '251 001 19',
                'title' => 'Forklift-Vinç',
                'description' => 'Forklift ve vinç kiralama bedelleri',
                'category' => 'equipment',
                'unit' => 'KG',
                'quantity' => 1,
                'amount' => 3700.00,
            ],
        ];

        foreach ($expenses as $item) {
            GeneralExpense::updateOrCreate(
                [
                    'project_code' => $projectCode,
                    'title' => $item['title'],
                    'expense_date' => $expenseDate,
                ],
                [
                    'description' => $item['description'] ?? null,
                    'category' => $item['category'],
                    'subcategory' => $item['subcategory'] ?? null,
                    'amount' => $item['amount'],
                    'currency' => $currency,
                    'expense_date' => $expenseDate,
                    'status' => $item['status'] ?? 'pending',
                    'vat_rate' => $item['vat_rate'] ?? 0,
                    'vat_amount' => $item['vat_amount'] ?? 0,
                    'total_amount' => $item['total_amount'] ?? $item['amount'],
                    'department' => $item['department'] ?? 'Şantiye',
                    'project_code' => $projectCode,
                    'tags' => $item['tags'] ?? ['gumusler', 'villa', 'phase-1'],
                    'custom_fields' => [
                        'code' => $item['code'],
                        'unit' => $item['unit'],
                        'quantity' => $item['quantity'],
                    ],
                ]
            );
        }
    }
}
