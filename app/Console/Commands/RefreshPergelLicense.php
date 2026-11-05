<?php

namespace App\Console\Commands;

use App\Services\PergelLicenseService;
use Illuminate\Console\Command;

class RefreshPergelLicense extends Command
{
    protected $signature = 'pergel:license-refresh {--force : Sunucuya zorla doğrulama isteği gönder}';

    protected $description = 'PERGEL lisans durumunu uzak API ile senkronize eder';

    public function handle(PergelLicenseService $license): int
    {
        $this->info('PERGEL lisans durumu güncelleniyor...');

        $license->refreshIfStale($this->option('force'));

        $state = $license->state();
        $this->line('Kurulum: '.$state->installation_id);
        $this->line('Durum: '.$license->licenseStatusLabel());
        $this->line('Modül sayısı: '.count($license->modules()));

        if ($state->expires_at) {
            $this->line('Bitiş: '.$state->expires_at->toDateString());
        }

        $this->info('Tamam.');

        return self::SUCCESS;
    }
}
