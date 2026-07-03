<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        $isAdmin = $user->is_admin;

        if (is_string($isAdmin)) {
            $isAdmin = $isAdmin === '1' || $isAdmin === 'true';
        } elseif (is_int($isAdmin)) {
            $isAdmin = $isAdmin === 1;
        } elseif (is_bool($isAdmin)) {
            $isAdmin = $isAdmin === true;
        } else {
            $isAdmin = false;
        }

        if (! $isAdmin) {
            abort(403, 'Доступ запрещен. Требуются права администратора.');
        }

        return $next($request);
    }
}
