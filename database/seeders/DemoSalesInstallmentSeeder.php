<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Invoice;
use App\Models\Customer;
use Carbon\Carbon;

class DemoSalesInstallmentSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ensure sample customers exist
        $cust1 = Customer::firstOrCreate(['email' => 'yapi.holding@demo.com'], [
            'name' => 'Avrupa Yapı Holding A.Ş.',
            'phone' => '0212 555 1122',
            'address' => 'Maslak No:45 Sarıyer/İstanbul'
        ]);

        $cust2 = Customer::firstOrCreate(['email' => 'temel.muhendislik@demo.com'], [
            'name' => 'Temel Mühendislik Ltd. Şti.',
            'phone' => '0216 444 3344',
            'address' => 'Atataşehir Bulvarı No:12 Kadıköy/İstanbul'
        ]);

        $cust3 = Customer::firstOrCreate(['email' => 'deniz.mimarlik@demo.com'], [
            'name' => 'Deniz Mimarlık & Tasarım',
            'phone' => '0212 333 9988',
            'address' => 'Levent Cd. No:88 Beşiktaş/İstanbul'
        ]);

        // 2. Create realistic sales invoices using valid enum statuses: ('open', 'partially_paid', 'paid', 'cancelled')
        $sampleInvoices = [
            [
                'no' => 'SAT-2026-0081',
                'customer_id' => $cust1->id,
                'date' => Carbon::now()->subDays(15)->toDateString(),
                'due_date' => Carbon::now()->addDays(15)->toDateString(),
                'amount' => 4500000.00,
                'paid_amount' => 3000000.00,
                'subtotal' => 3750000.00,
                'tax_amount' => 750000.00,
                'status' => 'partially_paid',
                'notes' => 'A-Blok Kat:12 Daire 48 Satış Sözleşmesi 1. ve 2. Taksit Tahsilatı'
            ],
            [
                'no' => 'SAT-2026-0082',
                'customer_id' => $cust2->id,
                'date' => Carbon::now()->subDays(40)->toDateString(),
                'due_date' => Carbon::now()->subDays(10)->toDateString(), // Overdue
                'amount' => 2800000.00,
                'paid_amount' => 1000000.00,
                'subtotal' => 2333333.33,
                'tax_amount' => 466666.67,
                'status' => 'partially_paid',
                'notes' => 'B-Blok Dubleks Daire Taksitli Satış — Gecikmiş Bakiye İkazı Gönderildi'
            ],
            [
                'no' => 'SAT-2026-0083',
                'customer_id' => $cust3->id,
                'date' => Carbon::now()->subDays(5)->toDateString(),
                'due_date' => Carbon::now()->addDays(25)->toDateString(),
                'amount' => 6200000.00,
                'paid_amount' => 6200000.00,
                'subtotal' => 5166666.67,
                'tax_amount' => 1033333.33,
                'status' => 'paid',
                'notes' => 'C-Blok Ticari Mağaza Alanı Peşin Satış Sözleşmesi'
            ],
            [
                'no' => 'SAT-2026-0084',
                'customer_id' => $cust1->id,
                'date' => Carbon::now()->subDays(60)->toDateString(),
                'due_date' => Carbon::now()->subDays(20)->toDateString(), // Overdue
                'amount' => 1950000.00,
                'paid_amount' => 0.00,
                'subtotal' => 1625000.00,
                'tax_amount' => 325000.00,
                'status' => 'open',
                'notes' => 'Aura Loft Ofis Katı Satış Sözleşmesi'
            ]
        ];

        foreach ($sampleInvoices as $data) {
            Invoice::updateOrCreate(
                ['no' => $data['no']],
                $data
            );
        }
    }
}
