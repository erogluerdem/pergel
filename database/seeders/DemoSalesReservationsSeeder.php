<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SalesReservation;
use App\Models\Customer;
use App\Models\Unit;
use App\Models\User;
use Carbon\Carbon;

class DemoSalesReservationsSeeder extends Seeder
{
    public function run()
    {
        $customers = Customer::limit(10)->get();
        $units = Unit::limit(10)->get();
        $user = User::first();

        if ($customers->isEmpty() || $units->isEmpty()) {
            return;
        }

        $demoReservations = [
            [
                'option_no' => 'OPS-2026-001',
                'customer_id' => $customers[0]->id,
                'unit_id' => $units[0]->id,
                'created_by' => $user->id,
                'option_date' => Carbon::now()->subDays(5)->format('Y-m-d'),
                'expiry_date' => Carbon::now()->addDays(10)->format('Y-m-d'),
                'option_price' => 14500000.00,
                'discount_amount' => 300000.00,
                'status' => 'confirmed',
                'notes' => 'Gümüşler Lüks Villa A-1 blok için %2 özel peşin ödeme indirimi uygulanarak opsiyon kesinleştirildi.',
            ],
            [
                'option_no' => 'OPS-2026-002',
                'customer_id' => $customers[1]->id,
                'unit_id' => $units[1]->id,
                'created_by' => $user->id,
                'option_date' => Carbon::now()->subDays(2)->format('Y-m-d'),
                'expiry_date' => Carbon::now()->addDays(12)->format('Y-m-d'),
                'option_price' => 8750000.00,
                'discount_amount' => 150000.00,
                'status' => 'pending',
                'notes' => 'Merkez AVM Restorasyon projesi dükkan no: 14 için ön kaparo opsiyon rezervasyonu.',
            ],
            [
                'option_no' => 'OPS-2026-003',
                'customer_id' => $customers[2]->id,
                'unit_id' => $units[2]->id,
                'created_by' => $user->id,
                'option_date' => Carbon::now()->subDays(15)->format('Y-m-d'),
                'expiry_date' => Carbon::now()->subDays(1)->format('Y-m-d'),
                'option_price' => 22000000.00,
                'discount_amount' => 500000.00,
                'status' => 'expired',
                'notes' => 'Kıyı Rezidans Penthouse katı 14 günlük opsiyon süresi dolmuştur, müşteri uzatma talep etmedi.',
            ],
            [
                'option_no' => 'OPS-2026-004',
                'customer_id' => $customers[3]->id,
                'unit_id' => $units[3]->id,
                'created_by' => $user->id,
                'option_date' => Carbon::now()->subDays(1)->format('Y-m-d'),
                'expiry_date' => Carbon::now()->addDays(14)->format('Y-m-d'),
                'option_price' => 6200000.00,
                'discount_amount' => 0.00,
                'status' => 'pending',
                'notes' => 'Yeşilova Konutları 3+1 Daire C-12 için kaparo yatırıldı, kredi onayı bekleniyor.',
            ],
            [
                'option_no' => 'OPS-2026-005',
                'customer_id' => $customers[4]->id,
                'unit_id' => $units[4]->id,
                'created_by' => $user->id,
                'option_date' => Carbon::now()->subDays(8)->format('Y-m-d'),
                'expiry_date' => Carbon::now()->addDays(6)->format('Y-m-d'),
                'option_price' => 18500000.00,
                'discount_amount' => 450000.00,
                'status' => 'confirmed',
                'notes' => 'Organize Sanayi Lojistik Depo B-2 için resmi ön protokol imzalandı, sözleşme hazırlığı sürüyor.',
            ],
            [
                'option_no' => 'OPS-2026-006',
                'customer_id' => $customers[5]->id,
                'unit_id' => $units[5]->id,
                'created_by' => $user->id,
                'option_date' => Carbon::now()->subDays(20)->format('Y-m-d'),
                'expiry_date' => Carbon::now()->subDays(5)->format('Y-m-d'),
                'option_price' => 9800000.00,
                'discount_amount' => 200000.00,
                'status' => 'cancelled',
                'notes' => 'Müşteri vazgeçme talebinde bulundu, kaparo iade şartlarına göre işlem iptal edildi.',
            ],
            [
                'option_no' => 'OPS-2026-007',
                'customer_id' => $customers[6]->id,
                'unit_id' => $units[6]->id,
                'created_by' => $user->id,
                'option_date' => Carbon::now()->subDays(3)->format('Y-m-d'),
                'expiry_date' => Carbon::now()->addDays(11)->format('Y-m-d'),
                'option_price' => 31000000.00,
                'discount_amount' => 1000000.00,
                'status' => 'confirmed',
                'notes' => 'Vadi Manzara Konakları Ikiz Villa V-2 için kurumsal rezervasyon opsiyonu.',
            ],
            [
                'option_no' => 'OPS-2026-008',
                'customer_id' => $customers[7]->id,
                'unit_id' => $units[7]->id,
                'created_by' => $user->id,
                'option_date' => Carbon::now()->subDays(4)->format('Y-m-d'),
                'expiry_date' => Carbon::now()->addDays(9)->format('Y-m-d'),
                'option_price' => 7400000.00,
                'discount_amount' => 100000.00,
                'status' => 'pending',
                'notes' => 'Şehir Merkez Ofis Plaza Kat: 4 Bağımsız bölüm opsiyon talebi.',
            ]
        ];

        foreach ($demoReservations as $resData) {
            SalesReservation::create($resData);
        }
    }
}
