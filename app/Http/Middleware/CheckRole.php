<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!$request->user()) {
            return redirect('login');
        }

        if ($request->user()->hasAnyRole($roles)) {
            return $next($request);
        }

        abort(403, 'Bu işlemi yapma izni yok.');
    }
}
