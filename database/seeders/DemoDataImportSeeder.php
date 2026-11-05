<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DataImport;
use App\Models\User;

class DemoDataImportSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::first() ?? User::factory()->create([
            'name' => 'Sistem Yöneticisi',
            'email' => 'admin@pergel.com',
            'is_admin' => true,
        ]);

        $imports = [
            [
                'user_id' => $admin->id,
                'module' => 'invoices',
                'status' => 'completed',
                'file_path' => 'imports/santiye_hakedis_faturasi_2026.xlsx',
                'file_name' => 'santiye_hakedis_faturasi_2026.xlsx',
                'total_rows' => 145,
                'imported_rows' => 145,
                'skipped_rows' => 0,
                'error_rows' => 0,
                'error_messages' => [],
                'created_at' => now()->subHours(3),
            ],
            [
                'user_id' => $admin->id,
                'module' => 'projects',
                'status' => 'completed',
                'file_path' => 'imports/yeni_santiye_projeleri.csv',
                'file_name' => 'yeni_santiye_projeleri.csv',
                'total_rows' => 28,
                'imported_rows' => 28,
                'skipped_rows' => 0,
                'error_rows' => 0,
                'error_messages' => [],
                'created_at' => now()->subDay(),
            ],
            [
                'user_id' => $admin->id,
                'module' => 'contacts',
                'status' => 'completed',
                'file_path' => 'imports/tasaron_tedarikci_listesi.xlsx',
                'file_name' => 'tasaron_tedarikci_listesi.xlsx',
                'total_rows' => 84,
                'imported_rows' => 82,
                'skipped_rows' => 2,
                'error_rows' => 0,
                'error_messages' => [],
                'created_at' => now()->subDays(2),
            ],
            [
                'user_id' => $admin->id,
                'module' => 'tasks',
                'status' => 'failed',
                'file_path' => 'imports/saha_is_emiri_listesi.csv',
                'file_name' => 'saha_is_emiri_listesi.csv',
                'total_rows' => 50,
                'imported_rows' => 12,
                'skipped_rows' => 0,
                'error_rows' => 38,
                'error_messages' => [
                    ['row' => 13, 'message' => 'Geçersiz son teslim tarihi formatı'],
                    ['row' => 14, 'message' => 'Zorunlu sorumlu mühendis alanı boş'],
                ],
                'created_at' => now()->subDays(4),
            ]
        ];

        foreach ($imports as $imp) {
            DataImport::create($imp);
        }
    }
}
