<?php

namespace App\Http\Middleware;

use App\Services\PergelLicenseService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPergelLicense
{
    public function __construct(protected PergelLicenseService $license) {}

    public function handle(Request $request, Closure $next): Response
    {
        if ($this->license->isUriExcluded($request->path())) {
            return $next($request);
        }

        if (! auth()->check()) {
            return $next($request);
        }

        $this->license->refreshIfStale();

        $module = $this->license->requiredModuleForUri($request->path());
        if (! $module) {
            return $next($request);
        }

        if ($this->license->hasModule($module)) {
            return $next($request);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Bu modül PERGEL Pro lisansı gerektirir.',
                'module' => $module,
                'module_info' => $this->license->getModuleMeta($module),
                'upgrade_url' => route('admin.license.upgrade', ['module' => $module]),
                'renew_url' => $this->license->renewUrl(),
            ], 403);
        }

        return redirect()
            ->route('admin.license.upgrade', ['module' => $module])
            ->with('info', 'Bu bölüme erişmek için PERGEL Pro lisansı gereklidir.');
    }
}
