<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfSubcontractorAuthenticated
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard('subcontractor')->check()) {
            return redirect()->route('subcontractor.dashboard');
        }

        return $next($request);
    }
}
