<?php

namespace App\Services;

use App\Models\PergelLicenseState;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PergelLicenseService
{
    protected ?PergelLicenseState $state = null;

    public function state(): PergelLicenseState
    {
        if ($this->state) {
            return $this->state;
        }

        $this->state = PergelLicenseState::query()->first();

        if (! $this->state) {
            $this->state = PergelLicenseState::create([
                'installation_id' => (string) Str::uuid(),
                'tier' => 'community',
                'modules' => config('pergel-license.community_modules'),
            ]);
        }

        return $this->state;
    }

    public function installationId(): string
    {
        return $this->state()->installation_id;
    }

    public function tier(): string
    {
        if ($this->proAccessActive()) {
            return 'pro';
        }

        if ($this->isLicenseActive()) {
            return $this->state()->tier;
        }

        return 'community';
    }

    public function isPro(): bool
    {
        return $this->proAccessActive() || $this->tier() === 'pro';
    }

    public function isLicenseActive(): bool
    {
        $state = $this->state();

        if (! $state->license_key) {
            return false;
        }

        if ($state->expires_at && $state->expires_at->isPast()) {
            return false;
        }

        if (! $state->validated_at) {
            return false;
        }

        return $state->validated_at->gte(now()->subDays($this->offlineGraceDays()));
    }

    public function modules(): array
    {
        if ($this->proAccessActive()) {
            $modules = $this->state()->modules;

            return is_array($modules) && count($modules) > 0
                ? array_unique(array_merge($modules, config('pergel-license.pro_modules', [])))
                : array_merge(
                    config('pergel-license.community_modules', []),
                    config('pergel-license.pro_modules', [])
                );
        }

        if ($this->isLicenseActive()) {
            $modules = $this->state()->modules;
            if (is_array($modules) && count($modules) > 0) {
                return array_values(array_unique(array_merge(config('pergel-license.community_modules', []), $modules)));
            }
        }

        return config('pergel-license.community_modules', []);
    }

    public function hasModule(string $module): bool
    {
        // Topluluk modülleri her zaman ücretsiz ve açıktır
        if (in_array($module, config('pergel-license.community_modules', []), true)) {
            return true;
        }

        // Kurulu harici modül kontrolü (ModuleManager)
        try {
            if (app(\App\Services\ModuleManager::class)->providesFeature($module)) {
                return true;
            }
        } catch (\Throwable $e) {
        }

        // Pro erişimi aktifse (Geliştirici modu veya aktif Pro lisans)
        if ($this->proAccessActive()) {
            return true;
        }

        // Aktif paket lisansı kontrolü
        if ($this->isLicenseActive() && in_array($module, $this->modules(), true)) {
            return true;
        }

        return false;
    }

    public function getModuleMeta(?string $module = null): ?array
    {
        $all = config('pergel-license.module_meta', []);
        if ($module === null) {
            return $all;
        }

        return $all[$module] ?? [
            'name' => strtoupper($module) . ' Modülü',
            'badge' => strtoupper($module),
            'icon' => 'fas fa-cube',
            'description' => 'Bu özellik PERGEL Pro kurumsal lisansı gerektirir.',
            'features' => [
                'Gelişmiş kurumsal işlevler',
                'Yetkilendirme ve detaylı raporlama',
                'Sektörel standartlara uyum',
            ],
        ];
    }

    public function expiresAt(): ?\Carbon\Carbon
    {
        return $this->state()->expires_at;
    }

    public function validatedAt(): ?\Carbon\Carbon
    {
        return $this->state()->validated_at;
    }

    public function offlineGraceDays(): int
    {
        return max(1, (int) config('pergel-license.offline_grace_days', 7));
    }

    public function offlineGraceEndsAt(): ?\Carbon\Carbon
    {
        $validated = $this->validatedAt();
        if (! $validated || ! $this->state()->license_key) {
            return null;
        }

        return $validated->copy()->addDays($this->offlineGraceDays());
    }

    public function proAccessActive(): bool
    {
        $state = $this->state();

        // .env içinde PERGEL_DEV_LICENSE açıkça false yapılmışsa test için Community moduna düş
        $devFlag = env('PERGEL_DEV_LICENSE');
        if ($devFlag !== null && filter_var($devFlag, FILTER_VALIDATE_BOOLEAN) === false) {
            // Dev bypass kapalı; gerçek lisans doğrulamasına bak
        } elseif (config('app.env') === 'local' || env('APP_ENV') === 'local' || filter_var($devFlag ?? true, FILTER_VALIDATE_BOOLEAN)) {
            return true;
        }

        if ($state->tier !== 'pro' || ! $state->license_key) {
            return false;
        }

        if ($state->expires_at && $state->expires_at->isPast()) {
            return false;
        }

        if (! $state->validated_at) {
            return false;
        }

        return $state->validated_at->gte(now()->subDays($this->offlineGraceDays()));
    }

    public function licenseStatusLabel(): string
    {
        if ($this->proAccessActive()) {
            if ($this->state()->tier === 'pro' && $this->state()->license_key) {
                return 'PERGEL Pro (Kurumsal Lisans)';
            }

            return 'PERGEL Pro (Geliştirme Ortamı Lisanslı - Sınırsız Modül)';
        }

        return 'Topluluk Sürümü (Ücretsiz)';
    }

    public function renewUrl(): string
    {
        return $this->state()->renew_url ?: config('pergel-license.renew_url');
    }

    public function refreshIfStale(bool $force = false): void
    {
        $state = $this->state();
        $ttlHours = max(1, (int) config('pergel-license.cache_ttl_hours', 24));

        if (! $force && $state->validated_at && $state->validated_at->gt(now()->subHours($ttlHours))) {
            return;
        }

        if ($state->license_key) {
            $this->validateRemote($state->license_key);

            return;
        }

        $this->syncCommunity();
    }

    public function activate(string $licenseKey): array
    {
        $licenseKey = strtoupper(trim($licenseKey));
        $response = $this->post('activate', [
            'licenseKey' => $licenseKey,
            'installationId' => $this->installationId(),
            'customerName' => config('app.name'),
        ]);

        if (! ($response['ok'] ?? false)) {
            return $response;
        }

        $this->applyPayload($response, $licenseKey);

        return $response;
    }

    public function clearLicense(): void
    {
        $state = $this->state();
        $state->update([
            'license_key' => null,
            'tier' => 'community',
            'modules' => config('pergel-license.community_modules'),
            'expires_at' => null,
            'customer' => null,
            'validated_at' => now(),
            'renew_url' => config('pergel-license.renew_url'),
        ]);
        $this->state = $state->fresh();
        Cache::forget('pergel.license.modules');
    }

    protected function downgradeExpiredKeepKey(): void
    {
        $state = $this->state();
        $state->update([
            'tier' => 'community',
            'modules' => config('pergel-license.community_modules'),
            'validated_at' => now(),
        ]);
        $this->state = $state->fresh();
        Cache::forget('pergel.license.modules');
    }

    protected function validateRemote(string $licenseKey): array
    {
        $response = $this->post('validate', [
            'licenseKey' => $licenseKey,
            'installationId' => $this->installationId(),
        ]);

        if ($response['ok'] ?? false) {
            $this->applyPayload($response, $licenseKey);

            return $response;
        }

        $message = (string) ($response['message'] ?? '');

        if ($this->isNetworkError($message)) {
            return $response;
        }

        if (str_contains($message, 'süresi dolmuş') || str_contains($message, 'dolmuş')) {
            $this->downgradeExpiredKeepKey();

            return $response;
        }

        $this->clearLicense();

        return $response;
    }

    protected function isNetworkError(string $message): bool
    {
        return str_contains($message, 'ulaşılamadı')
            || str_contains($message, 'sunucusu hatası')
            || str_contains($message, 'Boş yanıt');
    }

    protected function syncCommunity(): void
    {
        $response = $this->post('status', [
            'installationId' => $this->installationId(),
        ]);

        if ($response['ok'] ?? false) {
            $this->applyPayload($response, null);
        }
    }

    protected function applyPayload(array $payload, ?string $licenseKey): void
    {
        $state = $this->state();
        $state->update([
            'license_key' => $licenseKey,
            'tier' => $payload['tier'] ?? 'community',
            'modules' => $payload['modules'] ?? config('pergel-license.community_modules'),
            'expires_at' => ! empty($payload['expiresAt']) ? $payload['expiresAt'] : null,
            'customer' => $payload['customer'] ?? null,
            'validated_at' => now(),
            'renew_url' => $payload['renewUrl'] ?? config('pergel-license.renew_url'),
        ]);
        $this->state = $state->fresh();
        Cache::put('pergel.license.modules', $this->modules(), now()->addHours(6));
    }

    protected function post(string $action, array $body): array
    {
        $base = rtrim((string) config('pergel-license.api_base'), '/');
        $url = $base.'/'.$action;

        try {
            $response = Http::timeout(15)
                ->acceptJson()
                ->asJson()
                ->post($url, $body);

            if ($response->successful()) {
                return $response->json() ?? ['ok' => false, 'message' => 'Boş yanıt'];
            }

            return $response->json() ?? ['ok' => false, 'message' => 'Lisans sunucusu hatası ('.$response->status().')'];
        } catch (\Throwable $e) {
            return ['ok' => false, 'message' => 'Lisans sunucusuna ulaşılamadı: '.$e->getMessage()];
        }
    }

    public function requiredModuleForUri(string $uri): ?string
    {
        $path = trim($uri, '/');
        $map = config('pergel-license.uri_modules', []);
        uksort($map, fn ($a, $b) => strlen($b) <=> strlen($a));

        foreach ($map as $prefix => $module) {
            if ($path === $prefix || str_starts_with($path, $prefix)) {
                return $module;
            }
        }

        return null;
    }

    public function isUriExcluded(string $uri): bool
    {
        $path = trim($uri, '/');
        foreach (config('pergel-license.excluded_uri_prefixes', []) as $prefix) {
            $prefix = trim($prefix, '/');
            if ($path === $prefix || str_starts_with($path, $prefix)) {
                return true;
            }
        }

        return false;
    }
}
