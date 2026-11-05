<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\GeneralExpense;
use Carbon\Carbon;

class GeneralExpenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $expenses = [
            [
                'title' => 'Elektrik Faturası - Ekim 2025',
                'description' => 'Ofis elektrik faturası',
                'category' => 'utilities',
                'amount' => 3450.75,
                'expense_date' => Carbon::now()->subDays(5),
                'vendor_name' => 'BEDAŞ',
                'invoice_number' => 'ELK-2025-10-001',
                'status' => 'paid',
                'vat_rate' => 18,
                'vat_amount' => 621.14,
                'total_amount' => 4071.89,
                'department' => 'Genel'
            ],
            [
                'title' => 'Office 365 Lisansları',
                'description' => 'Aylık Office 365 Business Premium lisansları (50 kullanıcı)',
                'category' => 'software',
                'amount' => 2850.00,
                'expense_date' => Carbon::now()->subDays(10),
                'vendor_name' => 'Microsoft Türkiye',
                'invoice_number' => 'MS-2025-09-442',
                'status' => 'approved',
                'vat_rate' => 18,
                'vat_amount' => 513.00,
                'total_amount' => 3363.00,
                'department' => 'IT',
                'is_recurring' => true,
                'recurring_frequency' => 'monthly'
            ],
            [
                'title' => 'Pazarlama Kampanyası - Google Ads',
                'description' => 'Eylül ayı Google Ads reklam harcaması',
                'category' => 'marketing',
                'amount' => 5650.00,
                'expense_date' => Carbon::now()->subDays(15),
                'vendor_name' => 'Google Ireland Limited',
                'status' => 'paid',
                'vat_rate' => 0,
                'vat_amount' => 0,
                'total_amount' => 5650.00,
                'department' => 'Pazarlama',
                'tags' => ['dijital', 'reklam', 'google']
            ],
            [
                'title' => 'Ofis Malzemeleri',
                'description' => 'Kırtasiye, temizlik malzemeleri ve ofis gereçleri',
                'category' => 'supplies',
                'amount' => 1275.50,
                'expense_date' => Carbon::now()->subDays(3),
                'vendor_name' => 'Ofis Dünyası',
                'invoice_number' => 'OD-2025-3421',
                'status' => 'pending',
                'vat_rate' => 18,
                'vat_amount' => 229.59,
                'total_amount' => 1505.09,
                'department' => 'Genel'
            ],
            [
                'title' => 'Hukuki Danışmanlık',
                'description' => 'Sözleşme inceleme ve hukuki danışmanlık hizmeti',
                'category' => 'legal',
                'amount' => 4500.00,
                'expense_date' => Carbon::now()->subDays(7),
                'vendor_name' => 'Yılmaz & Ortakları Hukuk Bürosu',
                'invoice_number' => 'HUK-2025-156',
                'status' => 'approved',
                'vat_rate' => 18,
                'vat_amount' => 810.00,
                'total_amount' => 5310.00,
                'department' => 'Hukuk'
            ],
            [
                'title' => 'Sunucu Bakım Hizmeti',
                'description' => 'Aylık sunucu bakım ve destek hizmeti',
                'category' => 'maintenance',
                'amount' => 2200.00,
                'expense_date' => Carbon::now()->subDays(12),
                'vendor_name' => 'TechCare Bilişim',
                'invoice_number' => 'TC-2025-891',
                'status' => 'paid',
                'vat_rate' => 18,
                'vat_amount' => 396.00,
                'total_amount' => 2596.00,
                'department' => 'IT',
                'is_recurring' => true,
                'recurring_frequency' => 'monthly'
            ],
            [
                'title' => 'İş Seyahati - İstanbul',
                'description' => 'Müşteri ziyareti için İstanbul seyahati (uçak, otel, diğer)',
                'category' => 'travel',
                'amount' => 3200.00,
                'expense_date' => Carbon::now()->subDays(20),
                'vendor_name' => 'Çeşitli',
                'status' => 'paid',
                'vat_rate' => 18,
                'vat_amount' => 576.00,
                'total_amount' => 3776.00,
                'department' => 'Satış',
                'tags' => ['seyahat', 'müşteri']
            ],
            [
                'title' => 'Ofis Klima Bakımı',
                'description' => 'Tüm ofis klimalarının periyodik bakımı',
                'category' => 'maintenance',
                'amount' => 850.00,
                'expense_date' => Carbon::now()->subDays(2),
                'vendor_name' => 'Klima Servis Plus',
                'invoice_number' => 'KSP-2025-334',
                'status' => 'pending',
                'vat_rate' => 18,
                'vat_amount' => 153.00,
                'total_amount' => 1003.00,
                'department' => 'Genel'
            ],
            [
                'title' => 'Zoom Pro Lisansı',
                'description' => 'Yıllık Zoom Pro toplantı lisansı',
                'category' => 'software',
                'amount' => 1680.00,
                'expense_date' => Carbon::now()->subDays(30),
                'vendor_name' => 'Zoom Video Communications',
                'status' => 'paid',
                'vat_rate' => 18,
                'vat_amount' => 302.40,
                'total_amount' => 1982.40,
                'department' => 'IT'
            ],
            [
                'title' => 'Sosyal Medya Yönetim Aracı',
                'description' => 'Hootsuite Professional aylık abonelik',
                'category' => 'marketing',
                'amount' => 450.00,
                'expense_date' => Carbon::now()->subDays(8),
                'vendor_name' => 'Hootsuite Inc.',
                'status' => 'approved',
                'vat_rate' => 18,
                'vat_amount' => 81.00,
                'total_amount' => 531.00,
                'department' => 'Pazarlama',
                'is_recurring' => true,
                'recurring_frequency' => 'monthly'
            ]
        ];

        foreach ($expenses as $expense) {
            GeneralExpense::create($expense);
        }
    }
}
