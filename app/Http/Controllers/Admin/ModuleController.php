<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ModuleManager;
use App\Services\PergelLicenseService;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    public function __construct(
        protected ModuleManager $moduleManager,
        protected PergelLicenseService $licenseService
    ) {}

    /**
     * Modül & Eklenti Yönetim Merkezi
     */
    public function index()
    {
        $installed = $this->moduleManager->getInstalledModules();
        $catalog = $this->moduleManager->getCatalog();
        $licenseState = $this->licenseService->state();

        return view('admin.modules.index', [
            'installed' => $installed,
            'catalog' => $catalog,
            'licenseState' => $licenseState,
            'installationId' => $this->licenseService->installationId(),
            'isLicenseActive' => $this->licenseService->isLicenseActive() || $this->licenseService->proAccessActive(),
        ]);
    }

    /**
     * erdemeroglu.com.tr üzerinden satın alınan Lisans Anahtarı ile
     * modülü doğrular, indirir ve kurar.
     */
    public function installWithLicense(Request $request)
    {
        $data = $request->validate([
            'license_key' => 'required|string|min:10|max:64',
            'module_slug' => 'nullable|string|max:64',
        ]);

        $result = $this->moduleManager->installWithLicenseKey(
            $data['license_key'],
            $data['module_slug'] ?? null
        );

        if (! $result['ok']) {
            return back()->withInput()->with('error', $result['message']);
        }

        return back()->with('success', $result['message']);
    }

    /**
     * erdemeroglu.com.tr'den indirilen Modül ZIP dosyasını yükler ve kurar
     */
    public function upload(Request $request)
    {
        $request->validate([
            'module_zip' => 'required|file|max:51200', // max 50MB
            'license_key' => 'nullable|string|max:64',
        ]);

        $file = $request->file('module_zip');
        $ext = strtolower($file->getClientOriginalExtension());

        if ($ext !== 'zip') {
            return back()->with('error', 'Lütfen geçerli bir .zip uzantılı modül paketi yükleyin.');
        }

        $result = $this->moduleManager->installFromZip($file, $request->input('license_key'));

        if (! $result['ok']) {
            return back()->with('error', $result['message']);
        }

        return back()->with('success', $result['message']);
    }

    /**
     * Kurulu modülü kaldırır
     */
    public function destroy(string $slug)
    {
        $result = $this->moduleManager->uninstall($slug);

        if (! $result['ok']) {
            return back()->with('error', $result['message']);
        }

        return back()->with('success', $result['message']);
    }
}
