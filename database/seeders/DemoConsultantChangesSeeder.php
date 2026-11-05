<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ConsultantChange;
use App\Models\Customer;
use App\Models\User;
use Carbon\Carbon;

class DemoConsultantChangesSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        if ($users->count() < 2) {
            $user1 = User::firstOrCreate(
                ['email' => 'danisman1@pergel.com'],
                ['name' => 'Ahmet Danışman', 'password' => bcrypt('password'), 'is_admin' => false]
            );
            $user2 = User::firstOrCreate(
                ['email' => 'danisman2@pergel.com'],
                ['name' => 'Elif Portföy Yöneticisi', 'password' => bcrypt('password'), 'is_admin' => false]
            );
            $users = User::all();
        }

        $customers = Customer::all();
        if ($customers->isEmpty()) {
            $customer1 = Customer::create(['name' => 'Yılmaz Holding A.Ş.', 'email' => 'info@yilmazholding.com', 'phone' => '0212 555 1000']);
            $customer2 = Customer::create(['name' => 'Kaya İnşaat ve Mimarlık', 'email' => 'kontakt@kayainsaat.com', 'phone' => '0216 444 2000']);
            $customer3 = Customer::create(['name' => 'Demir Enerji Sistemleri', 'email' => 'talep@demirenerji.com', 'phone' => '0312 333 3000']);
            $customers = Customer::all();
        }

        $userList = $users->pluck('id')->toArray();
        $adminUser = User::where('is_admin', true)->first() ?? $users->first();
        $customerList = $customers->pluck('id')->toArray();

        $demoData = [
            [
                'customer_id' => $customerList[0],
                'old_consultant_id' => $userList[0],
                'new_consultant_id' => $userList[1] ?? $userList[0],
                'change_date' => Carbon::now()->subDays(5),
                'reason' => 'Bölgesel Portföy Yeniden Yapılandırması',
                'notes' => 'Müşteri temsilcisi Marmara bölge genişlemesi sebebiyle Kıdemli Satış Yöneticisine devredildi.',
                'changed_by' => $adminUser->id,
            ],
            [
                'customer_id' => $customerList[count($customerList) > 1 ? 1 : 0],
                'old_consultant_id' => $userList[count($userList) > 1 ? 1 : 0],
                'new_consultant_id' => $userList[0],
                'change_date' => Carbon::now()->subDays(12),
                'reason' => 'Müşteri Talebi & Özel İletişim Tercihi',
                'notes' => 'Müşteri yetkilisinin talebi üzerine kurumsal finansman danışmanı değiştirilmiştir.',
                'changed_by' => $adminUser->id,
            ],
            [
                'customer_id' => $customerList[count($customerList) > 2 ? 2 : 0],
                'old_consultant_id' => $userList[0],
                'new_consultant_id' => $userList[count($userList) > 1 ? 1 : 0],
                'change_date' => Carbon::now()->subDays(25),
                'reason' => 'Eski Danışman Departman Değişikliği',
                'notes' => 'Eski danışman Proje Geliştirme departmanına geçtiği için müşteri portföyü aktarıldı.',
                'changed_by' => $adminUser->id,
            ],
            [
                'customer_id' => $customerList[0],
                'old_consultant_id' => $userList[count($userList) > 1 ? 1 : 0],
                'new_consultant_id' => $userList[0],
                'change_date' => Carbon::now()->subDays(40),
                'reason' => 'Vip Müşteri Segmentasyonu',
                'notes' => 'Holding seviyesindeki alım hacmi nedeniyle VIP müşteri temsilcisine yönlendirildi.',
                'changed_by' => $adminUser->id,
            ],
            [
                'customer_id' => $customerList[count($customerList) > 1 ? 1 : 0],
                'old_consultant_id' => $userList[0],
                'new_consultant_id' => $userList[count($userList) > 1 ? 1 : 0],
                'change_date' => Carbon::now()->subDays(60),
                'reason' => 'Yıllık Rutin Portföy Rotasyonu',
                'notes' => 'Şirket içi rotasyon politikası gereğince temsilci değişimi yapılmıştır.',
                'changed_by' => $adminUser->id,
            ],
        ];

        foreach ($demoData as $data) {
            ConsultantChange::create($data);
        }
    }
}
