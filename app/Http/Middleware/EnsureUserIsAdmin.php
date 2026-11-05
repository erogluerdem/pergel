<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || !optional(auth()->user())->is_admin) {
            return response()->json(['ok'=>false,'message'=>'Yetkisiz'],403);
        }
        return $next($request);
    }
}
