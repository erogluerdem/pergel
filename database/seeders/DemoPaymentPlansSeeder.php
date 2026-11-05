<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentPlan;
use App\Models\Invoice;
use App\Models\Customer;
use App\Models\Installment;
use Carbon\Carbon;

class DemoPaymentPlansSeeder extends Seeder
{
    public function run()
    {
        // Truncate existing payment plans & installments
        Installment::query()->forceDelete();
        PaymentPlan::query()->forceDelete();

        $customer = Customer::first();
        if (!$customer) {
            $customer = Customer::create([
                'name' => 'Akdeniz Yapı & İnşaat A.Ş.',
                'email' => 'muhasebe@akdenizyapi.com.tr',
                'phone' => '+90 212 555 9000',
                'company' => 'Akdeniz Yapı A.Ş.',
                'address' => 'Büyükdere Cad. No:142 Levent / İstanbul',
                'tax_office' => 'Zincirlikuyu',
                'tax_number' => '1029384756',
            ]);
        }

        // Demo Invoices
        $invoicesData = [
            [
                'no' => 'FTR-2025-00101',
                'customer_id' => $customer->id,
                'amount' => 1200000.00,
                'paid_amount' => 400000.00,
                'subtotal' => 1000000.00,
                'tax_amount' => 200000.00,
                'status' => 'partially_paid',
                'date' => Carbon::now()->subMonths(3)->toDateString(),
                'due_date' => Carbon::now()->addMonths(9)->toDateString(),
                'notes' => 'Lotus Towers Daire 14 Satış Vadelendirme Faturası',
            ],
            [
                'no' => 'FTR-2025-00102',
                'customer_id' => $customer->id,
                'amount' => 450000.00,
                'paid_amount' => 450000.00,
                'subtotal' => 375000.00,
                'tax_amount' => 75000.00,
                'status' => 'paid',
                'date' => Carbon::now()->subMonths(6)->toDateString(),
                'due_date' => Carbon::now()->subMonths(1)->toDateString(),
                'notes' => 'Ofis Katı İklimlendirme Tesisat Hakediş Faturası',
            ],
            [
                'no' => 'FTR-2025-00103',
                'customer_id' => $customer->id,
                'amount' => 840000.00,
                'paid_amount' => 0.00,
                'subtotal' => 700000.00,
                'tax_amount' => 140000.00,
                'status' => 'open',
                'date' => Carbon::now()->subDays(10)->toDateString(),
                'due_date' => Carbon::now()->addMonths(12)->toDateString(),
                'notes' => 'Marina Konutları Blok B Daire 8 Peşinatsız Taksitlendirme',
            ],
            [
                'no' => 'FTR-2025-00104',
                'customer_id' => $customer->id,
                'amount' => 320000.00,
                'paid_amount' => 160000.00,
                'subtotal' => 266666.67,
                'tax_amount' => 53333.33,
                'status' => 'partially_paid',
                'date' => Carbon::now()->subMonths(2)->toDateString(),
                'due_date' => Carbon::now()->addMonths(4)->toDateString(),
                'notes' => 'Özel Mimari Konsept ve İç Dekora İlişkin Taksit Sözleşmesi',
            ],
        ];

        $invoices = [];
        foreach ($invoicesData as $invData) {
            $inv = Invoice::firstOrCreate(['no' => $invData['no']], $invData);
            $invoices[] = $inv;
        }

        // Demo Payment Plans
        $plansData = [
            [
                'invoice_id' => $invoices[0]->id,
                'plan_no' => 'PLN-2025-001',
                'name' => '12 Ay Eşit Taksitli Konut Satış Ödeme Planı',
                'description' => 'Lotus Towers projesi A Blok 14 No numaralı konut satışı 12 taksitli ödeme takvimi.',
                'total_amount' => 1200000.00,
                'installment_count' => 12,
                'start_date' => Carbon::now()->subMonths(3)->startOfMonth()->toDateString(),
                'payment_interval_days' => 30,
                'interval_days' => 30,
                'down_payment_percentage' => 20.00,
                'is_template' => false,
                'status' => 'active',
                'notes' => 'Taksit ödemeleri her ayın 5. günü Garanti BBVA TR92... hesabına yapılacaktır.',
            ],
            [
                'invoice_id' => $invoices[1]->id,
                'plan_no' => 'PLN-2025-002',
                'name' => '6 Ay Tamamlanan Tesisat Hizmet Planı',
                'description' => 'İklimlendirme ve havalandırma santrali projesi 6 taksitli ödeme planı.',
                'total_amount' => 450000.00,
                'installment_count' => 6,
                'start_date' => Carbon::now()->subMonths(6)->startOfMonth()->toDateString(),
                'payment_interval_days' => 30,
                'interval_days' => 30,
                'down_payment_percentage' => 10.00,
                'is_template' => false,
                'status' => 'completed',
                'notes' => 'Tüm taksitler zamanında eksiksiz ödenerek plan başarıyla tamamlanmıştır.',
            ],
            [
                'invoice_id' => $invoices[2]->id,
                'plan_no' => 'PLN-2025-003',
                'name' => '24 Ay Esnek Ödemeli Ticari Alan Taksit Planı',
                'description' => 'Marina Villaları B Blok 8 No mağaza alanına ait 24 aylık esnek taksit takvimi.',
                'total_amount' => 840000.00,
                'installment_count' => 24,
                'start_date' => Carbon::now()->addDays(5)->toDateString(),
                'payment_interval_days' => 30,
                'interval_days' => 30,
                'down_payment_percentage' => 0.00,
                'is_template' => true,
                'status' => 'active',
                'notes' => 'İlk 3 taksit ertelemeli kampanya kapsamında düzenlenmiştir.',
            ],
            [
                'invoice_id' => $invoices[3]->id,
                'plan_no' => 'PLN-2025-004',
                'name' => '4 Sezonluk Mimari Tasarım Ödeme Takvimi',
                'description' => 'İç mimarlık uygulama ve mobilya imalat sözleşmesi 4 taksit ödeme planı.',
                'total_amount' => 320000.00,
                'installment_count' => 4,
                'start_date' => Carbon::now()->subMonths(2)->startOfMonth()->toDateString(),
                'payment_interval_days' => 60,
                'interval_days' => 60,
                'down_payment_percentage' => 25.00,
                'is_template' => false,
                'status' => 'active',
                'notes' => '2 taksit ödenmiş olup kalan 2 taksit tarihi yaklaşıyor.',
            ],
        ];

        foreach ($plansData as $pData) {
            $plan = PaymentPlan::create($pData);

            // Generate Installment Records for each Payment Plan
            $instCount = $plan->installment_count;
            $amountPerInst = $plan->total_amount / $instCount;
            $startDate = Carbon::parse($plan->start_date);

            for ($i = 1; $i <= $instCount; $i++) {
                $dueDate = (clone $startDate)->addDays(($i - 1) * $plan->payment_interval_days);
                
                $status = 'pending';
                $paidDate = null;
                $paidAmount = 0.00;

                if ($plan->status === 'completed') {
                    $status = 'paid';
                    $paidDate = (clone $dueDate)->subDays(rand(1, 4))->toDateString();
                    $paidAmount = $amountPerInst;
                } elseif ($plan->status === 'active') {
                    if ($dueDate->isPast()) {
                        $status = 'paid';
                        $paidDate = $dueDate->toDateString();
                        $paidAmount = $amountPerInst;
                    } elseif ($i === ceil($instCount / 3)) {
                        $status = 'partial';
                        $paidDate = Carbon::now()->subDays(2)->toDateString();
                        $paidAmount = round($amountPerInst / 2, 2);
                    }
                }

                Installment::create([
                    'payment_plan_id' => $plan->id,
                    'invoice_id' => $plan->invoice_id,
                    'installment_number' => $i,
                    'amount' => $amountPerInst,
                    'due_date' => $dueDate->toDateString(),
                    'paid_date' => $paidDate,
                    'paid_amount' => $paidAmount,
                    'status' => $status,
                    'notes' => "{$i}. Taksit ödemesi (" . number_format($amountPerInst, 2) . " ₺)",
                ]);
            }
        }
    }
}
