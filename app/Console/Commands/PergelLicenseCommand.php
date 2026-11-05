<?php

namespace App\Console\Commands;

use App\Services\PergelLicenseService;
use Illuminate\Console\Command;

class PergelLicenseCommand extends Command
{
    protected $signature = 'pergel:license 
                            {action=status : status | activate | clear | refresh | modules} 
                            {key? : Aktivasyon için lisans anahtarı}';

    protected $description = 'PERGEL lisans durumunu görüntüler, aktifleştirir veya yönetir';

    public function handle(PergelLicenseService $license): int
    {
        $action = $this->argument('action');

        switch ($action) {
            case 'status':
                return $this->showStatus($license);

            case 'activate':
                $key = $this->argument('key') ?? $this->ask('Lütfen PERGEL Pro Lisans Anahtarını girin');
                if (! $key) {
                    $this->error('Lisans anahtarı boş bırakılamaz.');
                    return self::FAILURE;
                }

                $this->info("Lisans anahtarı doğrulanıyor: {$key}...");
                $res = $license->activate($key);

                if ($res['ok'] ?? false) {
                    $this->info('Tebrikler! PERGEL Pro lisansı başarıyla aktifleştirildi.');
                    return $this->showStatus($license);
                }

                $this->error('Aktivasyon başarısız: ' . ($res['message'] ?? 'Bilinmeyen hata'));
                return self::FAILURE;

            case 'clear':
                if ($this->confirm('Mevcut Pro lisans kaldırılıp ücretsiz Topluluk sürümüne dönülsün mü?')) {
                    $license->clearLicense();
                    $this->warn('Lisans kaldırıldı. Topluluk sürümüne geçildi.');
                    return $this->showStatus($license);
                }
                return self::SUCCESS;

            case 'refresh':
                $this->info('Lisans durumu uzak sunucu ile senkronize ediliyor...');
                $license->refreshIfStale(true);
                $this->info('Senkronizasyon tamamlandı.');
                return $this->showStatus($license);

            case 'modules':
                return $this->listModules($license);

            default:
                $this->error("Geçersiz eylem: {$action}. Kullanılabilir: status, activate, clear, refresh, modules");
                return self::FAILURE;
        }
    }

    protected function showStatus(PergelLicenseService $license): int
    {
        $state = $license->state();

        $this->newLine();
        $this->line('<fg=yellow;options=bold>==================================================</>');
        $this->line('<fg=yellow;options=bold>         PERGEL YAZILIM LİSANS DURUMU             </>');
        $this->line('<fg=yellow;options=bold>==================================================</>');

        $tierColor = $license->isPro() ? 'green' : 'cyan';
        $tierName = $license->isPro() ? 'PERGEL PRO (Kurumsal)' : 'Topluluk (Ücretsiz)';

        $this->table(
            ['Parametre', 'Değer'],
            [
                ['Kurulum Kimliği (UUID)', $license->installationId()],
                ['Aktif Sürüm', "<fg={$tierColor};options=bold>{$tierName}</>"],
                ['Statü Etiketi', $license->licenseStatusLabel()],
                ['Lisans Anahtarı', $state->license_key ? substr($state->license_key, 0, 8) . '...' . substr($state->license_key, -4) : '<fg=gray>Tanımlanmamış (Topluluk)</>'],
                ['Bitiş Tarihi', $license->expiresAt() ? $license->expiresAt()->format('d.m.Y H:i') : '<fg=green>Süresiz</>'],
                ['Son Doğrulama', $license->validatedAt() ? $license->validatedAt()->diffForHumans() : '-'],
                ['Çevrimdışı Tolerans', $license->offlineGraceDays() . ' Gün'],
                ['Geliştirici Baypası', env('PERGEL_DEV_LICENSE') === false ? '<fg=red>KAPALI (Topluluk Test)</>' : '<fg=green>AÇIK (Dev Pro)</>'],
                ['Lisanslı Modül Sayısı', count($license->modules()) . ' Modül'],
            ]
        );

        $this->newLine();
        return self::SUCCESS;
    }

    protected function listModules(PergelLicenseService $license): int
    {
        $this->newLine();
        $this->info('PERGEL Modül Listesi ve Yetki Durumu:');

        $rows = [];
        $meta = $license->getModuleMeta();

        foreach (config('pergel-license.community_modules', []) as $mod) {
            $rows[] = [
                $mod,
                $meta[$mod]['name'] ?? ucfirst($mod),
                '<fg=cyan>Topluluk (Free)</>',
                '<fg=green;options=bold>✔ AÇIK</>',
            ];
        }

        foreach (config('pergel-license.pro_modules', []) as $mod) {
            $has = $license->hasModule($mod);
            $status = $has ? '<fg=green;options=bold>✔ LİSANSLI</>' : '<fg=red>✖ KİLİTLİ (Pro)</>';
            $rows[] = [
                $mod,
                $meta[$mod]['name'] ?? ucfirst($mod),
                '<fg=yellow>PERGEL Pro</>',
                $status,
            ];
        }

        $this->table(['Kod', 'Modül Adı', 'Paket', 'Durum'], $rows);
        $this->newLine();

        return self::SUCCESS;
    }
}
