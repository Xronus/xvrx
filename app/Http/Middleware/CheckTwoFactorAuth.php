<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTwoFactorAuth
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // Non-admin users should never reach this middleware
        // (the 'admin' middleware runs before and aborts 403)
        if (! $user || ! $user->is_admin) {
            abort(403);
        }

        // If 2FA is not set up, redirect to setup
        if (! $user->hasTwoFactorEnabled()) {
            return redirect()->guest(route('admin.2fa.setup'));
        }

        // Check session for valid 2FA verification
        $verifiedAt = session('admin_2fa_verified_at');
        $ttl = config('2fa.admin_session_ttl', 30);

        if ($verifiedAt && $verifiedAt instanceof \Carbon\Carbon && $verifiedAt->diffInMinutes(now()) < $ttl) {
            // Refresh the timestamp on each access (sliding window)
            session(['admin_2fa_verified_at' => now()]);

            return $next($request);
        }

        // Need to verify 2FA
        return redirect()->guest(route('admin.2fa.challenge'));
    }
}
