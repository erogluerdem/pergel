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
                'buy_url' => 'https://erdemeroglu.com.tr/yazilimlar/pergel#field',
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
                'buy_url' => 'https://erdemeroglu.com.tr/yazilimlar/pergel#compliance',
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
                'buy_url' => 'https://erdemeroglu.com.tr/yazilimlar/pergel#pro',
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
     * Yüklenen ZIP dosyasını açar, doğrular ve modules/{slug} içine kurar
     */
    public function installFromZip(UploadedFile $file): array
    {
        if (! class_exists('ZipArchive')) {
            return ['ok' => false, 'message' => 'PHP ZipArchive uzantısı sunucuda aktif değil.'];
        }

        $zip = new ZipArchive();
        $res = $zip->open($file->getRealPath());

        if ($res !== true) {
            return ['ok' => false, 'message' => 'Geçersiz ZIP arşivi veya dosya açılamadı.'];
        }

        // Güvenlik & Manifest Kontrolü
        $hasManifest = false;
        $manifestContent = null;
        $manifestFolderPrefix = '';

        for ($i = 0; $i < $zip->numFiles; $i++) {
            $filename = $zip->getNameIndex($i);

            // Path Traversal saldırılarını engelle
            if (str_contains($filename, '..') || str_starts_with($filename, '/') || str_starts_with($filename, '\\')) {
                $zip->close();
                return ['ok' => false, 'message' => 'Güvenlik uyarısı: ZIP içerisinde geçersiz dosya yolu tespit edildi.'];
            }

            if (basename($filename) === 'module.json') {
                $hasManifest = true;
                $manifestContent = $zip->getFromIndex($i);
                $manifestFolderPrefix = dirname($filename);
                if ($manifestFolderPrefix === '.' || $manifestFolderPrefix === '') {
                    $manifestFolderPrefix = '';
                } else {
                    $manifestFolderPrefix = rtrim($manifestFolderPrefix, '/\\') . '/';
                }
                break;
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
        $targetDir = $this->path() . DIRECTORY_SEPARATOR . $slug;
        if (! File::isDirectory($targetDir)) {
            return ['ok' => false, 'message' => 'Modül bulunamadı.'];
        }

        File::deleteDirectory($targetDir);
        $this->loadedModules = null;
        $this->clearCache();

        return ['ok' => true, 'message' => "{$slug} modülü başarıyla kaldırıldı."];
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
