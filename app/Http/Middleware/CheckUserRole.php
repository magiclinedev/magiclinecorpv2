<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Illuminate\Support\Facades\Gate;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // $user = $request->user();

        // if (Gate::allows('admin-access', $user) || Gate::allows('viewer-access', $user)) {
        //     return $next($request);
        // }

        // abort(403, 'Unauthorized');
        return $next($request);
    }
}
