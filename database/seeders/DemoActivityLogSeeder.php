<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ActivityLog;
use App\Models\User;

class DemoActivityLogSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::first() ?? User::factory()->create([
            'name' => 'Sistem Yöneticisi',
            'email' => 'admin@pergel.com',
            'is_admin' => true,
        ]);

        $activities = [
            [
                'user_id' => $admin->id,
                'action' => 'create',
                'model_type' => 'App\\Models\\Invoice',
                'model_id' => 101,
                'old_values' => null,
                'new_values' => ['invoice_number' => 'FAT-2026-089', 'amount' => 450000.00, 'customer' => 'Kavaklı İnşaat A.Ş.'],
                'ip_address' => '192.168.1.45',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
                'status' => 'success',
                'description' => 'Şantiye beton teslimatı için 450.000 TL tutarında yeni e-fatura taslağı oluşturuldu.',
                'created_at' => now()->subMinutes(15),
            ],
            [
                'user_id' => $admin->id,
                'action' => 'update',
                'model_type' => 'App\\Models\\Project',
                'model_id' => 4,
                'old_values' => ['status' => 'planning', 'budget' => 15000000.00],
                'new_values' => ['status' => 'in_progress', 'budget' => 18500000.00],
                'ip_address' => '192.168.1.45',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
                'status' => 'success',
                'description' => 'Marmara Konutları projesi durumu Planlama aşamasından Devam Ediyor durumuna güncellendi.',
                'created_at' => now()->subHours(2),
            ],
            [
                'user_id' => $admin->id,
                'action' => 'approve',
                'model_type' => 'App\\Models\\ProgressPayment',
                'model_id' => 12,
                'old_values' => ['status' => 'pending_approval'],
                'new_values' => ['status' => 'approved', 'approved_by' => $admin->name],
                'ip_address' => '192.168.1.45',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
                'status' => 'success',
                'description' => '12 Nolu Taşeron Hakediş Raporu teknik incelemenin ardından başarıyla onaylandı.',
                'created_at' => now()->subHours(5),
            ],
            [
                'user_id' => $admin->id,
                'action' => 'export',
                'model_type' => 'App\\Models\\DataImport',
                'model_id' => null,
                'old_values' => null,
                'new_values' => ['module' => 'invoices', 'format' => 'xlsx'],
                'ip_address' => '192.168.1.45',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
                'status' => 'success',
                'description' => 'Son 6 ayın tüm hakediş ve satış fatura verileri Excel formatında indirildi.',
                'created_at' => now()->subDay(),
            ],
            [
                'user_id' => $admin->id,
                'action' => 'delete',
                'model_type' => 'App\\Models\\User',
                'model_id' => 99,
                'old_values' => ['name' => 'Deneme Kullanıcı', 'email' => 'test@pergel.com'],
                'new_values' => null,
                'ip_address' => '192.168.1.45',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
                'status' => 'warning',
                'description' => 'Pasif test kullanıcı hesabı sistemden kalıcı olarak silindi.',
                'created_at' => now()->subDays(2),
            ],
        ];

        foreach ($activities as $act) {
            ActivityLog::create($act);
        }
    }
}
