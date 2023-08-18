<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminAccessMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if ($user && ($user->status == 1 || $user->status == 2)) {
            return $next($request);
        }

        abort(403, 'Unauthorized');
    }
}
