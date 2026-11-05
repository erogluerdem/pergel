<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ModuleManager;
use App\Services\PergelLicenseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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
        ]);
    }

    /**
     * erdemeroglu.com.tr'den indirilen Modül ZIP dosyasını yükler ve kurar
     */
    public function upload(Request $request)
    {
        $request->validate([
            'module_zip' => 'required|file|max:51200', // max 50MB
        ]);

        $file = $request->file('module_zip');
        $ext = strtolower($file->getClientOriginalExtension());

        if ($ext !== 'zip') {
            return back()->with('error', 'Lütfen geçerli bir .zip uzantılı modül paketi yükleyin.');
        }

        $result = $this->moduleManager->installFromZip($file);

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
