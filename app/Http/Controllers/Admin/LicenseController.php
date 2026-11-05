<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\PergelLicenseService;
use Illuminate\Http\Request;

class LicenseController extends Controller
{
    public function index(PergelLicenseService $license)
    {
        $license->refreshIfStale();

        return view('admin.license.index', [
            'installationId' => $license->installationId(),
            'tier' => $license->tier(),
            'modules' => $license->modules(),
            'expiresAt' => $license->expiresAt(),
            'validatedAt' => $license->validatedAt(),
            'offlineGraceDays' => $license->offlineGraceDays(),
            'offlineGraceEndsAt' => $license->offlineGraceEndsAt(),
            'statusLabel' => $license->licenseStatusLabel(),
            'renewUrl' => $license->renewUrl(),
            'isPro' => $license->isPro(),
            'hasStoredKey' => (bool) $license->state()->license_key,
            'communityModules' => config('pergel-license.community_modules'),
            'proModules' => config('pergel-license.pro_modules'),
            'moduleMeta' => $license->getModuleMeta(),
        ]);
    }

    public function upgrade(Request $request, PergelLicenseService $license)
    {
        $requestedModule = $request->query('module');
        $moduleMeta = $requestedModule ? $license->getModuleMeta($requestedModule) : null;
        $allModulesMeta = $license->getModuleMeta();

        return view('admin.license.upgrade', [
            'requestedModule' => $requestedModule,
            'moduleMeta' => $moduleMeta,
            'allModulesMeta' => $allModulesMeta,
            'installationId' => $license->installationId(),
            'tier' => $license->tier(),
            'isPro' => $license->isPro(),
            'renewUrl' => $license->renewUrl(),
            'communityModules' => config('pergel-license.community_modules'),
            'proModules' => config('pergel-license.pro_modules'),
        ]);
    }

    public function modules(PergelLicenseService $license)
    {
        $license->refreshIfStale();

        return view('admin.license.modules', [
            'installationId' => $license->installationId(),
            'tier' => $license->tier(),
            'modules' => $license->modules(),
            'expiresAt' => $license->expiresAt(),
            'isPro' => $license->isPro(),
            'packages' => config('pergel-license.packages', []),
            'moduleMeta' => $license->getModuleMeta(),
            'communityModules' => config('pergel-license.community_modules', []),
            'proModules' => config('pergel-license.pro_modules', []),
        ]);
    }

    public function activate(Request $request, PergelLicenseService $license)
    {
        $data = $request->validate([
            'license_key' => 'required|string|min:10|max:64',
        ]);

        $result = $license->activate($data['license_key']);

        if ($result['ok'] ?? false) {
            $pkgName = $result['packageName'] ?? 'Paket';
            return redirect()
                ->route('admin.license.modules')
                ->with('success', "{$pkgName} başarıyla etkinleştirildi! İlgili tüm modüller kullanıma açıldı.");
        }

        return back()
            ->withInput()
            ->with('error', $result['message'] ?? 'Lisans aktivasyonu başarısız.');
    }

    public function refresh(PergelLicenseService $license)
    {
        $license->refreshIfStale(true);

        return redirect()
            ->route('admin.license.index')
            ->with('success', 'Lisans durumu güncellendi.');
    }

    public function clear(PergelLicenseService $license)
    {
        $license->clearLicense();

        return redirect()
            ->route('admin.license.index')
            ->with('success', 'Pro lisans kaldırıldı; topluluk sürümüne dönüldü.');
    }
}
