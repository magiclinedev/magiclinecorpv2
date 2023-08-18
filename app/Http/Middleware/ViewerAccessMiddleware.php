<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ViewerAccessMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if ($user && $user->status == 3) {
            return $next($request);
        }

        abort(403, 'Unauthorized');
    }
}
