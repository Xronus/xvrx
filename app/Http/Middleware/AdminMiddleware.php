<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // auth middleware runs before this, so auth()->user() is guaranteed
        if (! auth()->user()->is_admin) {
            abort(403, __('main.admin_access_denied'));
        }

        return $next($request);
    }
}
