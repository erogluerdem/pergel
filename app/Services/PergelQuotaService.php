<?php

namespace App\Services;

use App\Models\Asset;
use App\Models\Project;
use App\Models\User;

class PergelQuotaService
{
    public function __construct(protected PergelLicenseService $license) {}

    public function currentLimits(): array
    {
        if ($this->license->isPro()) {
            return [
                'max_projects' => -1,
                'max_users' => -1,
                'max_assets' => -1,
                'allow_export' => true,
            ];
        }

        if ($this->license->isLicenseActive()) {
            $tier = $this->license->tier();
            $packages = config('pergel-license.packages', []);

            foreach ($packages as $key => $pkg) {
                if ($key === $tier || ($pkg['slug'] ?? '') === $tier || str_contains($tier, $key)) {
                    return $pkg['limits'] ?? [
                        'max_projects' => 10,
                        'max_users' => 10,
                        'max_assets' => -1,
                        'allow_export' => true,
                    ];
                }
            }
        }

        return config('pergel-license.community_limits', [
            'max_projects' => 2,
            'max_users' => 2,
            'max_assets' => 25,
            'allow_export' => false,
        ]);
    }

    public function maxProjects(): int
    {
        return (int) ($this->currentLimits()['max_projects'] ?? 2);
    }

    public function maxUsers(): int
    {
        return (int) ($this->currentLimits()['max_users'] ?? 2);
    }

    public function maxAssets(): int
    {
        return (int) ($this->currentLimits()['max_assets'] ?? 25);
    }

    public function canCreateProject(): bool
    {
        $max = $this->maxProjects();
        if ($max === -1) {
            return true;
        }

        return Project::count() < $max;
    }

    public function canCreateUser(): bool
    {
        $max = $this->maxUsers();
        if ($max === -1) {
            return true;
        }

        return User::count() < $max;
    }

    public function canCreateAsset(): bool
    {
        $max = $this->maxAssets();
        if ($max === -1) {
            return true;
        }

        try {
            return Asset::count() < $max;
        } catch (\Throwable $e) {
            return true;
        }
    }

    public function canExport(): bool
    {
        if ($this->license->isPro()) {
            return true;
        }

        return (bool) config('pergel-license.community_limits.allow_export', false);
    }

    public function getUsageSummary(): array
    {
        $isPro = $this->license->isPro();

        $projectCount = Project::count();
        $maxProjects = $this->maxProjects();

        $userCount = User::count();
        $maxUsers = $this->maxUsers();

        try {
            $assetCount = Asset::count();
        } catch (\Throwable $e) {
            $assetCount = 0;
        }
        $maxAssets = $this->maxAssets();

        return [
            'is_pro' => $isPro,
            'tier' => $this->license->tier(),
            'projects' => [
                'current' => $projectCount,
                'max' => $maxProjects,
                'limit' => $maxProjects,
                'percentage' => $maxProjects > 0 ? min(100, (int) round(($projectCount / $maxProjects) * 100)) : 0,
                'percent' => $maxProjects > 0 ? min(100, (int) round(($projectCount / $maxProjects) * 100)) : 0,
                'is_limit_reached' => $maxProjects > 0 && $projectCount >= $maxProjects,
                'can_create' => $this->canCreateProject(),
            ],
            'users' => [
                'current' => $userCount,
                'max' => $maxUsers,
                'limit' => $maxUsers,
                'percentage' => $maxUsers > 0 ? min(100, (int) round(($userCount / $maxUsers) * 100)) : 0,
                'percent' => $maxUsers > 0 ? min(100, (int) round(($userCount / $maxUsers) * 100)) : 0,
                'is_limit_reached' => $maxUsers > 0 && $userCount >= $maxUsers,
                'can_create' => $this->canCreateUser(),
            ],
            'assets' => [
                'current' => $assetCount,
                'max' => $maxAssets,
                'limit' => $maxAssets,
                'percentage' => $maxAssets > 0 ? min(100, (int) round(($assetCount / $maxAssets) * 100)) : 0,
                'percent' => $maxAssets > 0 ? min(100, (int) round(($assetCount / $maxAssets) * 100)) : 0,
                'is_limit_reached' => $maxAssets > 0 && $assetCount >= $maxAssets,
                'can_create' => $this->canCreateAsset(),
            ],
            'export' => [
                'allowed' => $this->canExport(),
            ],
        ];
    }
}
