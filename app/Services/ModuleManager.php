<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use ZipArchive;

class ModuleManager
{
    protected string $modulesPath;
    protected ?array $loadedModules = null;

    public function __construct()
    {
        $this->modulesPath = base_path('modules');
    }

    /**
     * Modüllerin bulunduğu kök dizin
     */
    public function path(): string
    {
        if (! File::isDirectory($this->modulesPath)) {
            File::makeDirectory($this->modulesPath, 0755, true);
        }

        return $this->modulesPath;
    }

    /**
     * Yüklü olan tüm modülleri manifest bilgileriyle listeler
     */
    public function getInstalledModules(): array
    {
        if ($this->loadedModules !== null) {
            return $this->loadedModules;
        }

        $modules = [];
        $base = $this->path();

        if (! File::isDirectory($base)) {
            return $this->loadedModules = [];
        }

        $dirs = File::directories($base);
        foreach ($dirs as $dir) {
            $slug = basename($dir);
            $manifestPath = $dir . DIRECTORY_SEPARATOR . 'module.json';

            if (File::exists($manifestPath)) {
                try {
                    $json = json_decode(File::get($manifestPath), true);
                    if (is_array($json)) {
                        $json['slug'] = $json['slug'] ?? $slug;
                        $json['path'] = $dir;
                        $json['installed'] = true;
                        $json['enabled'] = $json['enabled'] ?? true;
                        $modules[$slug] = $json;
                    }
                } catch (\Throwable $e) {
                    Log::warning("PERGEL Module manifest parse error in {$slug}: " . $e->getMessage());
                }
            }
        }

        return $this->loadedModules = $modules;
    }

    /**
     * Belirli bir modülün kurulu olup olmadığını kontrol eder
     */
    public function isInstalled(string $slug): bool
    {
        $installed = $this->getInstalledModules();
        return isset($installed[$slug]);
    }

    /**
     * Belirli bir modülün etkin olup olmadığını kontrol eder
     */
    public function isEnabled(string $slug): bool
    {
        $installed = $this->getInstalledModules();
        return isset($installed[$slug]) && !empty($installed[$slug]['enabled']);
    }

    /**
     * Belirli bir özelliğin/modül anahtarının (örn: 'subcontractor', 'finance', 'bim')
     * yüklü bir modül tarafından sağlanıp sağlanmadığını kontrol eder
     */
    public function providesFeature(string $featureKey): bool
    {
        $installed = $this->getInstalledModules();
        foreach ($installed as $mod) {
            if (empty($mod['enabled'])) {
                continue;
            }
            $provides = $mod['modules'] ?? [];
            if (in_array($featureKey, $provides, true)) {
                return true;
            }
        }

        return false;
    }

    /**
     * erdemeroglu.com.tr üzerindeki resmi modül kataloğu
     */
    public function getCatalog(): array
    {
        $installed = $this->getInstalledModules();

        $catalog = [
            'pergel-field' => [
                'slug' => 'pergel-field',
                'name' => 'Saha & Taşeron Paketi',
                'badge' => 'Saha & Taşeron',
                'icon' => 'fas fa-hard-hat',
                'price' => 5990,
                'price_formatted' => '5.990 ₺',
                'description' => 'Şantiye ve sahada taşeron portalı, iş güvenliği (İSG), günlük defter, EBİS beton testleri ve QR arıza yönetimi.',
                'features' => [
                    'Taşeron Portalı & Dış Paydaş Girişi',
                    'İş Güvenliği (İSG) & Saha Kalite / Beton Kırım',
                    'Mobil Saha Günlüğü & Şantiye Defteri',
                    'Kat Planı QR Kodları & SLA Servis Yönetimi',
                    'Excel & PDF Dışa Aktarım',
                ],
                'buy_url' => 'https://erdemeroglu.com.tr/panel/hizmetler?yazilim=pergel-field',
                'showcase_url' => 'https://erdemeroglu.com.tr/yazilimlar/pergel#pro',
                'modules' => ['subcontractor', 'qhse', 'qr_sla'],
                'installed' => isset($installed['pergel-field']),
                'enabled' => isset($installed['pergel-field']) && !empty($installed['pergel-field']['enabled']),
            ],
            'pergel-compliance' => [
                'slug' => 'pergel-compliance',
                'name' => 'Mevzuat & Finans Paketi',
                'badge' => 'Mevzuat & Hakediş',
                'icon' => 'fas fa-coins',
                'price' => 7990,
                'price_formatted' => '7.990 ₺',
                'description' => 'ÇŞB pozları, resmi hakedişler (Yeşil Defter), SGK asgari işçilik, TÜİK Yİ-ÜFE ve ihale yönetimi.',
                'features' => [
                    'Çevre ve Şehircilik Bakanlığı (ÇŞB) Güncel Pozları',
                    'Resmi İnşaat Hakedişleri (Yeşil Defter & Onay Zincirleri)',
                    'SGK Asgari İşçilik Hesaplayıcı',
                    'TÜİK Yİ-ÜFE Endeksli Resmi Fiyat Farkı Hesabı',
                    'İhale Paketi Hazırlama & Teklif Karşılaştırma',
                    'Nakit Akışı, Çek/Senet & Bütçe Simülatörü',
                ],
                'buy_url' => 'https://erdemeroglu.com.tr/panel/hizmetler?yazilim=pergel-compliance',
                'showcase_url' => 'https://erdemeroglu.com.tr/yazilimlar/pergel#pro',
                'modules' => ['tr_compliance', 'finance', 'tenders'],
                'installed' => isset($installed['pergel-compliance']),
                'enabled' => isset($installed['pergel-compliance']) && !empty($installed['pergel-compliance']['enabled']),
            ],
            'pergel-pro' => [
                'slug' => 'pergel-pro',
                'name' => 'Full Enterprise Paketi',
                'badge' => 'Hepsi Dahil (Pro)',
                'icon' => 'ri-vip-crown-2-fill',
                'price' => 14990,
                'price_formatted' => '14.990 ₺',
                'description' => 'Tüm modüller, yapay zeka asistanı, 3D BIM modelleme, Executive BI ve sınırsız kullanım.',
                'features' => [
                    'Saha & Taşeron Paketinin Tüm Özellikleri',
                    'Mevzuat & Finans Paketinin Tüm Özellikleri',
                    'BIM 3D Model Görüntüleme & Çakışma Testleri',
                    'Yapay Zeka Asistanı & AI Puantaj Analizörü',
                    'C-Level Executive Dashboard (BI)',
                    'Sınırsız Şantiye, Kullanıcı ve Varlık',
                ],
                'buy_url' => 'https://erdemeroglu.com.tr/panel/hizmetler?yazilim=pergel-pro',
                'showcase_url' => 'https://erdemeroglu.com.tr/yazilimlar/pergel#pro',
                'modules' => [
                    'subcontractor', 'finance', 'tr_compliance', 'tenders',
                    'qhse', 'qr_sla', 'bim', 'ai', 'bi_reports', 'portfolio', 'erp'
                ],
                'installed' => isset($installed['pergel-pro']),
                'enabled' => isset($installed['pergel-pro']) && !empty($installed['pergel-pro']['enabled']),
            ],
        ];

        return $catalog;
    }

    /**
     * erdemeroglu.com.tr üzerinden alınan Lisans Anahtarı ile modülü doğrular,
     * uzaktan (.zip) veya Git paketinden (packages/{slug}) indirip kurar ve aktifleştirir.
     */
    public function installWithLicenseKey(string $licenseKey, ?string $requestedSlug = null): array
    {
        $licenseKey = strtoupper(trim($licenseKey));
        if (! str_starts_with($licenseKey, 'PERGEL-')) {
            return ['ok' => false, 'message' => 'Geçersiz PERGEL lisans anahtarı formatı (Örn: PERGEL-XXXX-XXXX-XXXX-XXXX).'];
        }

        /** @var PergelLicenseService $licenseService */
        $licenseService = app(PergelLicenseService::class);

        $response = $licenseService->post('download-module', [
            'licenseKey' => $licenseKey,
            'installationId' => $licenseService->installationId(),
            'moduleSlug' => $requestedSlug,
            'customerName' => config('app.name'),
        ]);

        // Eğer uzak sunucuda download-module ucu henüz yoksa standart activate ucuna düş
        if (! ($response['ok'] ?? false) && str_contains((string) ($response['message'] ?? ''), '404')) {
            $response = $licenseService->post('activate', [
                'licenseKey' => $licenseKey,
                'installationId' => $licenseService->installationId(),
                'customerName' => config('app.name'),
            ]);
        }

        if (! ($response['ok'] ?? false)) {
            return [
                'ok' => false,
                'message' => $response['message'] ?? 'Lisans anahtarı erdemeroglu.com.tr üzerinde doğrulanamadı.',
            ];
        }

        $licenseService->applyPayload($response, $licenseKey);

        return $this->syncPackageFromPayload($response, $licenseKey, $requestedSlug);
    }

    /**
     * Doğrulanmış lisans yanıtına göre ilgili modül paketini modules/{slug} dizinine kurar
     */
    public function syncPackageFromPayload(array $payload, string $licenseKey, ?string $requestedSlug = null): array
    {
        $rawSlug = $requestedSlug ?: ($payload['moduleSlug'] ?? ($payload['package'] ?? 'pergel-pro'));
        $targetSlug = preg_replace('/[^a-zA-Z0-9_\-]/', '', (string) $rawSlug);
        if (! in_array($targetSlug, ['pergel-field', 'pergel-compliance', 'pergel-pro'], true)) {
            $targetSlug = 'pergel-pro';
        }

        $targetDir = $this->path() . DIRECTORY_SEPARATOR . $targetSlug;
        $installedFromZip = false;

        // 1. Uzaktan gelen zipBase64 varsa aç
        if (! empty($payload['zipBase64']) && class_exists('ZipArchive')) {
            $binary = base64_decode((string) $payload['zipBase64'], true);
            if ($binary !== false && strlen($binary) > 50) {
                $tmpPath = storage_path('app/tmp_module_' . $targetSlug . '_' . uniqid() . '.zip');
                file_put_contents($tmpPath, $binary);
                $extractResult = $this->extractZipArchivePath($tmpPath);
                @unlink($tmpPath);
                if ($extractResult['ok']) {
                    $installedFromZip = true;
                }
            }
        }

        // 2. Git deposundaki packages/{slug} dizininden kur (Open-Core yerel paket yedeği)
        if (! $installedFromZip) {
            $gitPkgDir = base_path('packages' . DIRECTORY_SEPARATOR . $targetSlug);
            if (File::isDirectory($gitPkgDir)) {
                if (! File::isDirectory($targetDir)) {
                    File::makeDirectory($targetDir, 0755, true);
                }
                File::copyDirectory($gitPkgDir, $targetDir);
            }
        }

        if (File::isDirectory($targetDir)) {
            File::put(
                $targetDir . DIRECTORY_SEPARATOR . 'activation.json',
                json_encode([
                    'license_key' => $licenseKey,
                    'installation_id' => app(PergelLicenseService::class)->installationId(),
                    'package' => $payload['package'] ?? $targetSlug,
                    'expires_at' => $payload['expiresAt'] ?? null,
                    'activated_at' => now()->toIso8601String(),
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
            );
        }

        $this->loadedModules = null;

        // Otomatik Migration Çalıştırma
        $migrationsDir = $targetDir . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'migrations';
        if (File::isDirectory($migrationsDir)) {
            try {
                Artisan::call('migrate', ['--path' => 'modules/' . $targetSlug . '/database/migrations', '--force' => true]);
            } catch (\Throwable $e) {
                Log::warning("Modül migration çalıştırma uyarısı ({$targetSlug}): " . $e->getMessage());
            }
        }

        $this->clearCache();

        $pkgName = $payload['packageName'] ?? $targetSlug;

        return [
            'ok' => true,
            'message' => "{$pkgName} lisansı erdemeroglu.com.tr üzerinden doğrulandı, modül paketi indirildi ve etkinleştirildi!",
            'slug' => $targetSlug,
        ];
    }

    /**
     * Yüklenen ZIP dosyasını açar, lisansı doğrular ve modules/{slug} içine kurar
     */
    public function installFromZip(UploadedFile $file, ?string $licenseKey = null): array
    {
        /** @var PergelLicenseService $licenseService */
        $licenseService = app(PergelLicenseService::class);

        if ($licenseKey !== null && trim($licenseKey) !== '') {
            $act = $licenseService->activate($licenseKey);
            if (! ($act['ok'] ?? false)) {
                return [
                    'ok' => false,
                    'message' => $act['message'] ?? 'Girilen lisans anahtarı doğrulanamadı.',
                ];
            }
        } elseif (! $licenseService->isLicenseActive() && ! $licenseService->proAccessActive()) {
            return [
                'ok' => false,
                'message' => 'Modül paketini yüklemek için önce erdemeroglu.com.tr üzerinden satın aldığınız PERGEL lisans anahtarını girmeniz gerekmektedir.',
            ];
        }

        return $this->extractZipArchivePath($file->getRealPath());
    }

    /**
     * Disk üzerindeki ZIP dosyasını güvenli şekilde doğrular ve modules/{slug} içine açar
     */
    protected function extractZipArchivePath(string $zipFilePath): array
    {
        if (! class_exists('ZipArchive')) {
            return ['ok' => false, 'message' => 'PHP ZipArchive uzantısı sunucuda aktif değil.'];
        }

        $zip = new ZipArchive();
        $res = $zip->open($zipFilePath);

        if ($res !== true) {
            return ['ok' => false, 'message' => 'Geçersiz ZIP arşivi veya dosya açılamadı.'];
        }

        // Güvenlik & Manifest Kontrolü
        $hasManifest = false;
        $manifestContent = null;
        $manifestFolderPrefix = '';

        for ($i = 0; $i < $zip->numFiles; $i++) {
            $filename = $zip->getNameIndex($i);

            // Path Traversal saldırılarını tüm girdiler için engelle (Zip-Slip koruması)
            if (
                str_contains($filename, '..') ||
                str_starts_with($filename, '/') ||
                str_starts_with($filename, '\\') ||
                str_contains($filename, ':')
            ) {
                $zip->close();
                return ['ok' => false, 'message' => 'Güvenlik uyarısı: ZIP içerisinde geçersiz dosya yolu tespit edildi.'];
            }

            if (!$hasManifest && basename($filename) === 'module.json') {
                $hasManifest = true;
                $manifestContent = $zip->getFromIndex($i);
                $manifestFolderPrefix = dirname($filename);
                if ($manifestFolderPrefix === '.' || $manifestFolderPrefix === '') {
                    $manifestFolderPrefix = '';
                } else {
                    $manifestFolderPrefix = rtrim($manifestFolderPrefix, '/\\') . '/';
                }
            }
        }

        if (! $hasManifest || ! $manifestContent) {
            $zip->close();
            return ['ok' => false, 'message' => 'Modül paketinde module.json manifest dosyası bulunamadı.'];
        }

        $manifest = json_decode($manifestContent, true);
        if (! is_array($manifest) || empty($manifest['slug'])) {
            $zip->close();
            return ['ok' => false, 'message' => 'module.json dosyası geçersiz veya "slug" anahtarı eksik.'];
        }

        $targetSlug = preg_replace('/[^a-zA-Z0-9_\-]/', '', $manifest['slug']);
        $targetDir = $this->path() . DIRECTORY_SEPARATOR . $targetSlug;

        // Hedef dizini hazırla
        if (! File::isDirectory($targetDir)) {
            File::makeDirectory($targetDir, 0755, true);
        }

        // Dosyaları aç
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $entryName = $zip->getNameIndex($i);

            // Klasör prefixini sıyır
            if ($manifestFolderPrefix && str_starts_with($entryName, $manifestFolderPrefix)) {
                $relative = substr($entryName, strlen($manifestFolderPrefix));
            } else {
                $relative = $entryName;
            }

            if (! $relative || $relative === '/') {
                continue;
            }

            $destPath = $targetDir . DIRECTORY_SEPARATOR . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $relative);

            if (str_ends_with($entryName, '/') || str_ends_with($entryName, '\\')) {
                if (! File::isDirectory($destPath)) {
                    File::makeDirectory($destPath, 0755, true);
                }
            } else {
                $parent = dirname($destPath);
                if (! File::isDirectory($parent)) {
                    File::makeDirectory($parent, 0755, true);
                }
                file_put_contents($destPath, $zip->getFromIndex($i));
            }
        }

        $zip->close();
        $this->loadedModules = null;

        // Otomatik Migration Çalıştırma
        $migrationsDir = $targetDir . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'migrations';
        if (File::isDirectory($migrationsDir)) {
            try {
                Artisan::call('migrate', ['--path' => 'modules/' . $targetSlug . '/database/migrations', '--force' => true]);
            } catch (\Throwable $e) {
                Log::warning("Modül migration çalıştırma uyarısı ({$targetSlug}): " . $e->getMessage());
            }
        }

        // Cache temizle
        $this->clearCache();

        return [
            'ok' => true,
            'message' => "{$manifest['name']} modülü başarıyla yüklendi ve aktifleştirildi.",
            'module' => $manifest,
        ];
    }

    /**
     * Modülü pasife alır veya siler
     */
    public function uninstall(string $slug): array
    {
        $cleanSlug = preg_replace('/[^a-zA-Z0-9_\-]/', '', $slug);
        if (! $cleanSlug || $cleanSlug !== $slug) {
            return ['ok' => false, 'message' => 'Geçersiz modül tanımlayıcısı.'];
        }

        $targetDir = $this->path() . DIRECTORY_SEPARATOR . $cleanSlug;
        if (! File::isDirectory($targetDir)) {
            return ['ok' => false, 'message' => 'Modül bulunamadı.'];
        }

        File::deleteDirectory($targetDir);
        $this->loadedModules = null;
        $this->clearCache();

        return ['ok' => true, 'message' => "{$cleanSlug} modülü başarıyla kaldırıldı."];
    }

    /**
     * Belleği ve rotaları tazele
     */
    public function clearCache(): void
    {
        try {
            Artisan::call('route:clear');
            Artisan::call('view:clear');
            Artisan::call('cache:clear');
        } catch (\Throwable $e) {
            // CLI/Web ortamında sessiz geç
        }
    }
}
