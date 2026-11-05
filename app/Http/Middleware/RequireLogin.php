<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RequireLogin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Always allow API routes
        if ($request->is('api/*')) {
            return $next($request);
        }

        // Allow auth routes (login/logout)
        if ($request->routeIs('login.*') || $request->is('login') || $request->is('login/*') || $request->routeIs('logout')) {
            return $next($request);
        }

        // Allow subcontractor login & portal paths (managed by auth:subcontractor / subcontractor.guest)
        if ($request->is('subcontractor/login') || $request->is('subcontractor/login/*') || $request->is('subcontractor/logout')) {
            return $next($request);
        }

        // Allow public external / signed stakeholder routes
        if (
            $request->routeIs('tenders.package.access', 'tenders.package.submit_bid', 'bim.models.file.public', 'digital-contracts.sign', 'digital-contracts.confirm_signature') ||
            $request->is('portfolio/tenders/package-prepare/*/access') ||
            $request->is('portfolio/tenders/package-prepare/*/submit-bid') ||
            $request->is('bim/models/*/file/public') ||
            $request->is('digital-contracts/*/sign/*') ||
            $request->is('digital-contracts/*/confirm-signature/*')
        ) {
            return $next($request);
        }

        // Allow health check
        if ($request->is('up')) {
            return $next($request);
        }

        // Allow debug/dev helpers when app.debug is enabled
        if (config('app.debug')) {
            if ($request->is('__dev/*') || $request->is('debug/*')) {
                return $next($request);
            }
        }

        // Allow some public assets and common static files
        if ($request->is('sw.js') || $request->is('robots.txt') || $request->is('manifest.json') || $request->is('favicon.ico')) {
            return $next($request);
        }

        // If already authenticated via web or subcontractor guard, allow
        if (auth()->check() || auth('subcontractor')->check()) {
            return $next($request);
        }

        // JSON / AJAX should receive 401
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        // Otherwise redirect to login page
        return redirect()->route('login.show');
    }
}
