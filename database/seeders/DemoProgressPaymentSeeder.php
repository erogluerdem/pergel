<?php

namespace Database\Seeders;

use App\Models\Contract;
use App\Models\ProgressPayment;
use App\Models\ProgressPaymentDetail;
use App\Models\Project;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoProgressPaymentSeeder extends Seeder
{
    public function run(): void
    {
        $projects = Project::all();
        if ($projects->isEmpty()) {
            return;
        }

        $contracts = Contract::all();

        $hakedisTemplates = [
            [
                'payment_number' => 'HKD-2026-001',
                'description' => 'Kaba İnşaat & Betonarme 1. Dönem Hakediş Cetveli',
                'amount' => 4850000.00,
                'percentage' => 25.00,
                'status' => ProgressPayment::STATUS_PAID,
                'payment_date' => now()->subMonths(3),
                'due_date' => now()->subMonths(3)->addDays(15),
                'paid_at' => now()->subMonths(3)->addDays(10),
                'details' => [
                    ['description' => 'Bodrum Kat C40 Beton Dökümü ve Kalıp İmalatları', 'quantity' => 1250, 'unit_price' => 2400.00, 'amount' => 3000000.00],
                    ['description' => 'Radye Temel ve Perde Donatı Çeliği Montajı', 'quantity' => 85, 'unit_price' => 20000.00, 'amount' => 1700000.00],
                    ['description' => 'Grobeton & Yalıtım Koruma İmalatı', 'quantity' => 300, 'unit_price' => 500.00, 'amount' => 150000.00],
                ]
            ],
            [
                'payment_number' => 'HKD-2026-002',
                'description' => 'Kaba İnşaat & Perde Kolon 2. Dönem Hakediş Cetveli',
                'amount' => 6200000.00,
                'percentage' => 55.00,
                'status' => ProgressPayment::STATUS_APPROVED,
                'payment_date' => now()->subMonths(1),
                'due_date' => now()->subMonths(1)->addDays(15),
                'paid_at' => null,
                'details' => [
                    ['description' => '1. ve 2. Normal Kat Kolon & Perde Beton İmalatı', 'quantity' => 1800, 'unit_price' => 2500.00, 'amount' => 4500000.00],
                    ['description' => 'Kat Döşemeleri İskele ve Kalıp Kurulumu', 'quantity' => 3400, 'unit_price' => 500.00, 'amount' => 1700000.00],
                ]
            ],
            [
                'payment_number' => 'HKD-2026-003',
                'description' => 'Elektrik & Tesisat Altyapı Hakediş Cetveli #3',
                'amount' => 2150000.00,
                'percentage' => 40.00,
                'status' => ProgressPayment::STATUS_SUBMITTED,
                'payment_date' => now()->subWeeks(2),
                'due_date' => now()->addDays(10),
                'paid_at' => null,
                'details' => [
                    ['description' => 'Ana Dağıtım Panoları ve Busbar Hatları Kurulumu', 'quantity' => 4, 'unit_price' => 350000.00, 'amount' => 1400000.00],
                    ['description' => 'Kablo Tavası Montajı ve Şaft Besleme Hatları', 'quantity' => 1500, 'unit_price' => 500.00, 'amount' => 750000.00],
                ]
            ],
            [
                'payment_number' => 'HKD-2026-004',
                'description' => 'Mekanik İklimlendirme & Chiller Hakediş Cetveli #4',
                'amount' => 3800000.00,
                'percentage' => 70.00,
                'status' => ProgressPayment::STATUS_PENDING,
                'payment_date' => now()->subDays(5),
                'due_date' => now()->addDays(20),
                'paid_at' => null,
                'details' => [
                    ['description' => 'VRF Dış Ünite ve Çatı Chiller Grubu Montajı', 'quantity' => 6, 'unit_price' => 450000.00, 'amount' => 2700000.00],
                    ['description' => 'Galvaniz Hava Kanalı İzolasyonu ve Testleri', 'quantity' => 2200, 'unit_price' => 500.00, 'amount' => 1100000.00],
                ]
            ],
        ];

        foreach ($projects as $index => $project) {
            $contract = $contracts->where('project_id', $project->id)->first() ?: $contracts->first();

            foreach ($hakedisTemplates as $tIndex => $tmpl) {
                $paymentNo = 'HKD-' . $project->id . '-' . sprintf('%03d', $tIndex + 1);

                if (ProgressPayment::where('payment_number', $paymentNo)->exists()) {
                    continue;
                }

                DB::transaction(function () use ($project, $contract, $tmpl, $paymentNo) {
                    $payment = ProgressPayment::create([
                        'project_id' => $project->id,
                        'contract_id' => $contract ? $contract->id : null,
                        'user_id' => 1,
                        'payment_number' => $paymentNo,
                        'payment_date' => $tmpl['payment_date'],
                        'due_date' => $tmpl['due_date'],
                        'amount' => $tmpl['amount'],
                        'percentage' => $tmpl['percentage'],
                        'description' => $tmpl['description'],
                        'status' => $tmpl['status'],
                        'approved_by' => $tmpl['status'] === 'approved' || $tmpl['status'] === 'paid' ? 1 : null,
                        'approved_at' => $tmpl['status'] === 'approved' || $tmpl['status'] === 'paid' ? now()->subWeeks(1) : null,
                        'paid_at' => $tmpl['paid_at'],
                        'notes' => 'Hakediş hakediş heyeti tarafından saha imalat kontratlarına göre incelenmiştir.',
                    ]);

                    foreach ($tmpl['details'] as $detail) {
                        ProgressPaymentDetail::create([
                            'progress_payment_id' => $payment->id,
                            'description' => $detail['description'],
                            'quantity' => $detail['quantity'],
                            'unit_price' => $detail['unit_price'],
                            'amount' => $detail['amount'],
                        ]);
                    }
                });
            }
        }
    }
}
