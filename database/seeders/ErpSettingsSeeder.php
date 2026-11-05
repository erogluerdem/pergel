<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\ErpLog;

class ErpSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'api_url', 'value' => 'https://erp.pergel.local/api/v1'],
            ['key' => 'username', 'value' => 'pergel_entegrasyon'],
            ['key' => 'cron', 'value' => 'daily'],
        ];

        foreach ($settings as $setting) {
            DB::table('erp_settings')->updateOrInsert(['key' => $setting['key']], $setting);
        }

        $mappings = [
            ['platform_code' => 'projects', 'erp_account' => 'WBS'],
            ['platform_code' => 'invoices', 'erp_account' => 'FI-AR'],
        ];

        foreach ($mappings as $mapping) {
            DB::table('erp_mappings')->updateOrInsert(['platform_code' => $mapping['platform_code']], $mapping);
        }

        if (class_exists(\App\Models\ErpLog::class)) {
            $logs = [
                ['action' => 'Fatura Senkronizasyonu', 'status' => 'success', 'details' => '15 fatura aktarıldı.', 'created_at' => now()->subHours(1)],
            ];
            foreach ($logs as $log) {
                ErpLog::firstOrCreate(['action' => $log['action']], $log);
            }
        }
    }
}
