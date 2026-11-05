<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\HelpCenterSyncService;

class SyncHelpCenterCommand extends Command
{
    protected $signature = 'help:sync-remote {--url= : Merkez sunucu API adresi}';
    protected $description = 'Uzak merkez sunucudan yeni yardım rehberlerini ve SQL konu başlıklarını günceller';

    public function handle(HelpCenterSyncService $syncService): int
    {
        $this->info('Uzak merkez sunucudan yardım rehberleri senkronize ediliyor...');
        $url = $this->option('url');

        $result = $syncService->syncFromRemoteServer($url);

        $this->info("✔ Senkronizasyon Başarılı! [{$result['timestamp']}]");
        $this->line("• Güncellenen Kategori Sayısı: {$result['synced_categories']}");
        $this->line("• Güncellenen Makale Sayısı: {$result['synced_articles']}");

        return Command::SUCCESS;
    }
}
