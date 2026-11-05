<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentPlan;

class DemoFlexiblePaymentsSeeder extends Seeder
{
    public function run()
    {
        $plans = [
            [
                'name' => 'Peşin & Tek Çekim Ödeme Şablonu',
                'description' => 'Satış esnasında %100 peşin ödemelerde geçerli, brüt satış tutarından %15 özel peşin indirimi uygulanan şablon.',
                'installment_count' => 1,
                'interval_days' => 30,
                'down_payment_percentage' => 100.00,
                'is_template' => true,
                'notes' => 'Sözleşme tarihinde tutarın tamamı tahsil edilir. Tapu devri hemen gerçekleşir.'
            ],
            [
                'name' => '12 Aylık Standart Eşit Taksitli Plan',
                'description' => '%25 peşinat ve 12 eşit taksitle ödeme imkanı sunan standart konut ödeme şablonu.',
                'installment_count' => 12,
                'interval_days' => 30,
                'down_payment_percentage' => 25.00,
                'is_template' => true,
                'notes' => 'Her ayın 15\'inde aylık taksit ödemeleri gerçekleşir. Sıfır faiz avantajlı.'
            ],
            [
                'name' => '24 Ay Vadeli Orta Vadeli Finansman Şablonu',
                'description' => '%30 peşinat ve 24 aylık esnek vade takvimiyle kurumsal satışlarda tercih edilen plan.',
                'installment_count' => 24,
                'interval_days' => 30,
                'down_payment_percentage' => 30.00,
                'is_template' => true,
                'notes' => '6. ve 12. aylarda opsiyonel %10 ara ödeme balon seçeneği mevcuttur.'
            ],
            [
                'name' => '36 Ay Uzun Vadeli Proje Lansman Şablonu',
                'description' => '%35 peşinat ödemesi sonrasında 36 ay taksit imkanı tanıyan geniş vadeli lansman şablonu.',
                'installment_count' => 36,
                'interval_days' => 30,
                'down_payment_percentage' => 35.00,
                'is_template' => true,
                'notes' => 'Teslim tarihine kadar taksit imkanı sağlar.'
            ],
            [
                'name' => '6 Aylık Üçer Aylık Ara Ödemeli Özel Plan',
                'description' => '%20 peşinat ile başlayıp 60 günde bir (2 ayda bir) taksit ödemeli yatırımcı odaklı plan.',
                'installment_count' => 6,
                'interval_days' => 60,
                'down_payment_percentage' => 20.00,
                'is_template' => true,
                'notes' => 'Özellikle ticari gayrimenkul ve dükkan satışlarında nakit akışını kolaylaştırır.'
            ],
            [
                'name' => 'Çeyrek Dönemlik Yatırımcı Şablonu (4 Taksit)',
                'description' => '%40 peşinat sonrası her 90 günde bir eşit 4 çeyrek ödeme vadeli şablon.',
                'installment_count' => 4,
                'interval_days' => 90,
                'down_payment_percentage' => 40.00,
                'is_template' => true,
                'notes' => 'Yabancı yatırımcı ve fon alımlarında kullanılan çeyrek dönem ödeme modeli.'
            ]
        ];

        foreach ($plans as $plan) {
            PaymentPlan::updateOrCreate(
                ['name' => $plan['name']],
                $plan
            );
        }
    }
}
