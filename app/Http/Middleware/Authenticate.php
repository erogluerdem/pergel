<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    protected function redirectTo($request): ?string
    {
        if ($request->expectsJson()) {
            return null;
        }

        $guards = $this->guards ?? [];

        if (in_array('subcontractor', $guards, true) || $request->routeIs('subcontractor.*')) {
            return route('subcontractor.login');
        }

        return route('login.show');
    }
}
