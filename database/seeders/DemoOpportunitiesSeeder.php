<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Opportunity;
use App\Models\User;
use Carbon\Carbon;

class DemoOpportunitiesSeeder extends Seeder
{
    public function run()
    {
        $user = User::first();
        $userId = $user ? $user->id : null;

        $opportunities = [
            [
                'title' => 'Gümüşler Lüks Villa Kompleksi',
                'description' => 'Niğde Gümüşler bölgesinde 24 villadan oluşan lüks konut projesi için yatırım grubu ile görüşmeler yapılıyor.',
                'customer' => 'Gümüşler Yatırım A.Ş.',
                'estimated_value' => 45000000.00,
                'kdv_percent' => 20,
                'kdv_amount' => 9000000.00,
                'estimated_value_kdv' => 54000000.00,
                'stage' => 'proposal',
                'owner_id' => $userId,
                'source' => 'Referans',
                'probability' => 75,
                'expected_close_date' => Carbon::now()->addDays(15)->format('Y-m-d'),
                'next_action_date' => Carbon::now()->addDays(3)->format('Y-m-d'),
                'last_contacted_at' => Carbon::now()->subDays(2)->format('Y-m-d H:i:s'),
                'activity_log' => 'Teklif dosyası ve mimari konsept sunumu hazırlandı, toplantı tarihi alındı.'
            ],
            [
                'title' => 'Merkez Avm & İş Merkezi Restorasyonu',
                'description' => 'Şehir merkezindeki iş merkezinin statik güçlendirme ve cephe yenileme ihale teklifi.',
                'customer' => 'Kule Gayrimenkul Geliştirme',
                'estimated_value' => 28500000.00,
                'kdv_percent' => 20,
                'kdv_amount' => 5700000.00,
                'estimated_value_kdv' => 34200000.00,
                'stage' => 'negotiation',
                'owner_id' => $userId,
                'source' => 'İhale',
                'probability' => 85,
                'expected_close_date' => Carbon::now()->addDays(10)->format('Y-m-d'),
                'next_action_date' => Carbon::now()->addDays(2)->format('Y-m-d'),
                'last_contacted_at' => Carbon::now()->subDays(1)->format('Y-m-d H:i:s'),
                'activity_log' => 'Fiyat revizyonu talep edildi, %5 iskonto ile nihai sözleşme taslağı iletildi.'
            ],
            [
                'title' => 'Organize Sanayi Lojistik Depo İnşaatı',
                'description' => '15.000 m² kapalı alana sahip çelik konstrüksiyon lojistik depo ve antrepo inşaatı projesi.',
                'customer' => 'Verim Lojistik Grubu',
                'estimated_value' => 62000000.00,
                'kdv_percent' => 20,
                'kdv_amount' => 12400000.00,
                'estimated_value_kdv' => 74400000.00,
                'stage' => 'qualification',
                'owner_id' => $userId,
                'source' => 'Doğrudan Temas',
                'probability' => 50,
                'expected_close_date' => Carbon::now()->addDays(35)->format('Y-m-d'),
                'next_action_date' => Carbon::now()->addDays(7)->format('Y-m-d'),
                'last_contacted_at' => Carbon::now()->subDays(4)->format('Y-m-d H:i:s'),
                'activity_log' => 'Zemin etüdü ve keşif metraj çalışmaları devam ediyor.'
            ],
            [
                'title' => 'Kıyı Rezidans ve Otel Projesi Kaba İnşaatı',
                'description' => 'Sahil şeridinde 5 yıldızlı otel ve rezidans bloğunun betonarme ve kaba inşaat işleri.',
                'customer' => 'Mavi Su Turizm ve İnşaat',
                'estimated_value' => 85000000.00,
                'kdv_percent' => 20,
                'kdv_amount' => 17000000.00,
                'estimated_value_kdv' => 102000000.00,
                'stage' => 'won',
                'owner_id' => $userId,
                'source' => 'Web Sitesi',
                'probability' => 100,
                'expected_close_date' => Carbon::now()->subDays(5)->format('Y-m-d'),
                'next_action_date' => Carbon::now()->addDays(1)->format('Y-m-d'),
                'last_contacted_at' => Carbon::now()->subDays(1)->format('Y-m-d H:i:s'),
                'activity_log' => 'Sözleşme imzalandı! Şantiye kurulumu ve mobilizasyon başlatıldı.'
            ],
            [
                'title' => 'Yeşilçam Konutları 2. Etap İnce İşler',
                'description' => '120 dairelik konut projesinin alçı, boya, seramik ve parke kaplama işleri paket ihalesi.',
                'customer' => 'Yeşilçam Yapı Kooperatifi',
                'estimated_value' => 18200000.00,
                'kdv_percent' => 20,
                'kdv_amount' => 3640000.00,
                'estimated_value_kdv' => 21840000.00,
                'stage' => 'lead',
                'owner_id' => $userId,
                'source' => 'Referans',
                'probability' => 30,
                'expected_close_date' => Carbon::now()->addDays(60)->format('Y-m-d'),
                'next_action_date' => Carbon::now()->addDays(10)->format('Y-m-d'),
                'last_contacted_at' => Carbon::now()->subDays(6)->format('Y-m-d H:i:s'),
                'activity_log' => 'İlk telefon görüşmesi yapıldı. Şartname talep edildi.'
            ],
            [
                'title' => 'Akıllı Kampüs Öğrenci Yurdu İnşaatı',
                'description' => '500 yatak kapasiteli özel öğrenci yurdu bina inşaatı ve çevre düzenlemesi projesi.',
                'customer' => 'Çınar Eğitim Vakfı',
                'estimated_value' => 37000000.00,
                'kdv_percent' => 20,
                'kdv_amount' => 7400000.00,
                'estimated_value_kdv' => 44400000.00,
                'stage' => 'proposal',
                'owner_id' => $userId,
                'source' => 'Fuar',
                'probability' => 65,
                'expected_close_date' => Carbon::now()->addDays(20)->format('Y-m-d'),
                'next_action_date' => Carbon::now()->addDays(4)->format('Y-m-d'),
                'last_contacted_at' => Carbon::now()->subDays(3)->format('Y-m-d H:i:s'),
                'activity_log' => 'Teklif teslim edildi, ihale komisyonu değerlendirmesi bekleniyor.'
            ],
            [
                'title' => 'Güneş Enerjisi Santral (GES) Saha Altyapısı',
                'description' => '5 MWp GES projesi için saha düzleme, çit, tel örgü ve kule temelleri altyapı işleri.',
                'customer' => 'Solarex Enerji A.Ş.',
                'estimated_value' => 12500000.00,
                'kdv_percent' => 20,
                'kdv_amount' => 2500000.00,
                'estimated_value_kdv' => 15000000.00,
                'stage' => 'won',
                'owner_id' => $userId,
                'source' => 'Doğrudan Temas',
                'probability' => 100,
                'expected_close_date' => Carbon::now()->subDays(12)->format('Y-m-d'),
                'next_action_date' => Carbon::now()->addDays(2)->format('Y-m-d'),
                'last_contacted_at' => Carbon::now()->subDays(2)->format('Y-m-d H:i:s'),
                'activity_log' => 'Avans ödemesi alındı, hafriyat makineleri sahaya sevk edildi.'
            ],
            [
                'title' => 'Hastane Ek Hizmet Binası Güçlendirme',
                'description' => 'Özel Medikal Park hastanesinin acil servis ek binası deprem güçlendirme projesi.',
                'customer' => 'Şifa Medikal Sağlık Hizmetleri',
                'estimated_value' => 9800000.00,
                'kdv_percent' => 20,
                'kdv_amount' => 1960000.00,
                'estimated_value_kdv' => 11760000.00,
                'stage' => 'lost',
                'owner_id' => $userId,
                'source' => 'İhale',
                'probability' => 0,
                'expected_close_date' => Carbon::now()->subDays(20)->format('Y-m-d'),
                'next_action_date' => null,
                'last_contacted_at' => Carbon::now()->subDays(15)->format('Y-m-d H:i:s'),
                'activity_log' => 'İhale başka bir yüklenici firmaya daha düşük fiyat teklifi sebebiyle verildi.'
            ],
            [
                'title' => 'Vadipark Premium İş Merkezi Elektrik/Mekanik Paket',
                'description' => 'A+ ofis bloğunun iklimlendirme, yangın söndürme ve zayıf akım altyapı işleri.',
                'customer' => 'Vadi Gayrimenkul Yatırım',
                'estimated_value' => 22000000.00,
                'kdv_percent' => 20,
                'kdv_amount' => 4400000.00,
                'estimated_value_kdv' => 26400000.00,
                'stage' => 'negotiation',
                'owner_id' => $userId,
                'source' => 'Referans',
                'probability' => 80,
                'expected_close_date' => Carbon::now()->addDays(8)->format('Y-m-d'),
                'next_action_date' => Carbon::now()->addDays(1)->format('Y-m-d'),
                'last_contacted_at' => Carbon::now()->subDays(1)->format('Y-m-d H:i:s'),
                'activity_log' => 'Ödeme planı ve teminat mektubu koşulları müzakere ediliyor.'
            ]
        ];

        foreach ($opportunities as $oppData) {
            Opportunity::updateOrCreate(
                ['title' => $oppData['title']],
                $oppData
            );
        }
    }
}

