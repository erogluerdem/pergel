<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CustomerMeeting;
use App\Models\Customer;
use App\Models\User;

class DemoCustomerMeetingsSeeder extends Seeder
{
    public function run()
    {
        $customers = Customer::take(10)->get();
        if ($customers->isEmpty()) {
            return;
        }

        $user = User::first();

        $meetings = [
            [
                'customer_id' => $customers[0]->id,
                'user_id' => $user?->id,
                'subject' => 'Mavi Kule Penthouse Sunumu & Ön Protokol Görüşmesi',
                'notes' => 'Müşteri projenin 14. katındaki 3+1 daire ile ilgilendi. Peşinat ve 12 taksit seçeneği sunuldu. Kat planları ve manzara simülasyonu incelendi.',
                'meeting_date' => now()->subDays(2)->setHour(14)->setMinute(30),
                'location' => 'Vadistanbul Satış Ofisi',
                'outcome' => 'converted_to_sale',
                'potential_sale_amount' => 12500000.00,
                'next_followup' => 'Kapora ödemesi ve e-sözleşme imza randevusu',
                'next_followup_date' => now()->addDays(2),
                'meeting_type' => 'visit',
            ],
            [
                'customer_id' => $customers[1]->id ?? $customers[0]->id,
                'user_id' => $user?->id,
                'subject' => 'Plaza Ofis 301 Kurumsal Kiralama Görüşmesi',
                'notes' => 'Şirket genel müdürü Mehmet Bey ile 210 m² ofis alanının 2 yıllık kiralama şartları detaylandırıldı. Metrekare başına fiyat revizesi talep edildi.',
                'meeting_date' => now()->subDays(5)->setHour(11)->setMinute(0),
                'location' => 'Müşteri Genel Merkezi (Levent)',
                'outcome' => 'interested',
                'potential_sale_amount' => 24500000.00,
                'next_followup' => 'Revize teklif mektubunun gönderilmesi',
                'next_followup_date' => now()->addDays(1),
                'meeting_type' => 'visit',
            ],
            [
                'customer_id' => $customers[2]->id ?? $customers[0]->id,
                'user_id' => $user?->id,
                'subject' => 'Gökdağ Villaları Lüks Rezidans Telefon Görüşmesi',
                'notes' => 'İç mimari seçenekler ve özel havuz detayları hakkında bilgi verildi. Katalog WhatsApp ve e-posta üzerinden iletildi.',
                'meeting_date' => now()->subDays(1)->setHour(16)->setMinute(15),
                'location' => 'Telefon Görüşmesi',
                'outcome' => 'pending',
                'potential_sale_amount' => 38000000.00,
                'next_followup' => 'Hafta sonu örnek villa ziyareti organize edilecek',
                'next_followup_date' => now()->addDays(3),
                'meeting_type' => 'call',
            ],
            [
                'customer_id' => $customers[3]->id ?? $customers[0]->id,
                'user_id' => $user?->id,
                'subject' => 'Çarşı Blok Ticari Ünite Değerlendirme Zoom Toplantısı',
                'notes' => 'Yatırımcı ekibiyle ticari alanın amortisman süresi ve kira garantisi modeli sunuldu. Yatırımcı kurul onayı bekliyor.',
                'meeting_date' => now()->subDays(3)->setHour(10)->setMinute(0),
                'location' => 'Microsoft Teams / Online',
                'outcome' => 'interested',
                'potential_sale_amount' => 18000000.00,
                'next_followup' => 'Finansal fizibilite raporunun paylaşımı',
                'next_followup_date' => now()->addDays(4),
                'meeting_type' => 'online',
            ],
            [
                'customer_id' => $customers[4]->id ?? $customers[0]->id,
                'user_id' => $user?->id,
                'subject' => 'Port Residence 2+1 Fiyat & Ödeme Planı Bilgilendirmesi',
                'notes' => 'Müşteriye taksitli esnek ödeme planları gönderildi. Bütçenin üzerinde kaldığı için teklif şu an beklemede.',
                'meeting_date' => now()->subDays(7)->setHour(15)->setMinute(45),
                'location' => 'E-Posta & SMS',
                'outcome' => 'not_interested',
                'potential_sale_amount' => 8500000.00,
                'next_followup' => 'Yeni kampanya döneminde tekrar ileşitime geçilecek',
                'next_followup_date' => now()->addDays(30),
                'meeting_type' => 'email',
            ],
            [
                'customer_id' => $customers[5]->id ?? $customers[0]->id,
                'user_id' => $user?->id,
                'subject' => 'Marina Projesi VIP Müşteri Yüz Yüze Görüşme',
                'notes' => 'Yatırımcı müşteri ile marina projesindeki 4 adet 2+1 daire paketi görüşüldü. Toplu alım indirimi talep edildi.',
                'meeting_date' => now()->subDays(4)->setHour(13)->setMinute(0),
                'location' => 'Pergel VIP Lounge',
                'outcome' => 'converted_to_sale',
                'potential_sale_amount' => 52000000.00,
                'next_followup' => 'Ön satış sözleşmesi hazırlanması',
                'next_followup_date' => now()->addDays(1),
                'meeting_type' => 'visit',
            ],
            [
                'customer_id' => $customers[6]->id ?? $customers[0]->id,
                'user_id' => $user?->id,
                'subject' => 'Kule Blok Kat 8 Ofis Kiralama Telefon Görüşmesi',
                'notes' => 'Müşteri metrekare ve otopark hakları hakkında bilgi aldı. Şirket ortakları ile görüşüp geri dönecek.',
                'meeting_date' => now()->subHours(4),
                'location' => 'Telefon Görüşmesi',
                'outcome' => 'pending',
                'potential_sale_amount' => 14000000.00,
                'next_followup' => '2 gün sonra takip araması yapılacak',
                'next_followup_date' => now()->addDays(2),
                'meeting_type' => 'call',
            ]
        ];

        foreach ($meetings as $data) {
            CustomerMeeting::create($data);
        }
    }
}
