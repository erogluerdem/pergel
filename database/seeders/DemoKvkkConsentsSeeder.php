<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KvkkConsent;
use App\Models\Customer;
use Carbon\Carbon;

class DemoKvkkConsentsSeeder extends Seeder
{
    public function run(): void
    {
        $customers = Customer::all();
        if ($customers->isEmpty()) {
            $customer1 = Customer::create(['name' => 'Karakaya Gayrimenkul Yatırım A.Ş.', 'email' => 'kurumsal@karakaya.com', 'phone' => '0212 555 0199']);
            $customer2 = Customer::create(['name' => 'Selin Yılmaz', 'email' => 'selin.yilmaz@gmail.com', 'phone' => '0532 444 8877']);
            $customer3 = Customer::create(['name' => 'Marmara Mühendislik Ltd. Şti.', 'email' => 'info@marmaramuhendislik.com', 'phone' => '0216 333 1122']);
            $customers = Customer::all();
        }

        $customerList = $customers->pluck('id')->toArray();

        $demoConsents = [
            [
                'customer_id' => $customerList[0],
                'phone' => '0532 555 1020',
                'email' => 'ahmet.yilmaz@holding.com',
                'sms_code' => '482910',
                'sms_verified_at' => Carbon::now()->subHours(3),
                'email_verified_at' => Carbon::now()->subHours(2),
                'consent_type' => 'both',
                'consent_status' => 'verified',
                'consent_date' => Carbon::now()->subDays(2),
                'expiry_date' => Carbon::now()->addDays(363),
                'verification_attempts' => 1,
                'last_attempt_at' => Carbon::now()->subHours(3),
                'notes' => 'Aydınlatma metni dijital onay formu üzerinden SMS ve Email çift doğrulaması ile onaylandı.',
            ],
            [
                'customer_id' => $customerList[count($customerList) > 1 ? 1 : 0],
                'phone' => '0544 333 4455',
                'email' => 'selin.yilmaz@gmail.com',
                'sms_code' => '910243',
                'sms_verified_at' => Carbon::now()->subDays(1),
                'email_verified_at' => null,
                'consent_type' => 'sms',
                'consent_status' => 'verified',
                'consent_date' => Carbon::now()->subDays(1),
                'expiry_date' => Carbon::now()->addDays(364),
                'verification_attempts' => 1,
                'last_attempt_at' => Carbon::now()->subDays(1),
                'notes' => 'Mobil SMS doğrulama kodu (OTP) ile 6698 sayılı KVKK pazarlama izni sağlandı.',
            ],
            [
                'customer_id' => $customerList[count($customerList) > 2 ? 2 : 0],
                'phone' => '0555 987 6543',
                'email' => 'proje@marmaramuhendislik.com',
                'sms_code' => '125984',
                'sms_verified_at' => null,
                'email_verified_at' => null,
                'consent_type' => 'both',
                'consent_status' => 'pending',
                'consent_date' => Carbon::now()->subMinutes(20),
                'expiry_date' => Carbon::now()->addHours(23),
                'verification_attempts' => 0,
                'last_attempt_at' => null,
                'notes' => 'Web sitesi teklif formu üzerinden iletildi. SMS doğrulama bekleniyor.',
            ],
            [
                'customer_id' => $customerList[0],
                'phone' => '0506 111 2233',
                'email' => 'destek@karakaya.com',
                'sms_code' => '654321',
                'sms_verified_at' => null,
                'email_verified_at' => null,
                'consent_type' => 'email',
                'consent_status' => 'rejected',
                'consent_date' => Carbon::now()->subDays(5),
                'expiry_date' => Carbon::now()->subDays(4),
                'verification_attempts' => 3,
                'last_attempt_at' => Carbon::now()->subDays(5),
                'notes' => 'Kullanıcı açık rıza metnini reddetti. Ticari elektronik ileti gönderimi engellendi.',
            ],
            [
                'customer_id' => $customerList[count($customerList) > 1 ? 1 : 0],
                'phone' => '0533 777 9900',
                'email' => 'bilgi@selinyilmaz.com',
                'sms_code' => '332110',
                'sms_verified_at' => null,
                'email_verified_at' => null,
                'consent_type' => 'sms',
                'consent_status' => 'expired',
                'consent_date' => Carbon::now()->subDays(10),
                'expiry_date' => Carbon::now()->subDays(9),
                'verification_attempts' => 2,
                'last_attempt_at' => Carbon::now()->subDays(10),
                'notes' => 'Doğrulama kodu 24 saat içerisinde girilmediği için zaman aşımına uğradı.',
            ],
        ];

        foreach ($demoConsents as $consent) {
            KvkkConsent::create($consent);
        }
    }
}
