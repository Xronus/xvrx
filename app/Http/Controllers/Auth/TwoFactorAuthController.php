<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\TwoFactorAuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TwoFactorAuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('check.banned');
    }

    /**
     * Show the 2FA setup page with QR code.
     */
    public function showSetup(): View
    {
        $user = auth()->user();

        return view('auth.two-factor.setup', [
            'user' => $user,
            'enabled' => $user->hasTwoFactorEnabled(),
        ]);
    }

    /**
     * Generate a new TOTP secret and return QR code + manual key.
     * AJAX endpoint.
     */
    public function generateSecret(Request $request, TwoFactorAuthService $service): \Illuminate\Http\JsonResponse
    {
        $user = auth()->user();

        if ($user->hasTwoFactorEnabled()) {
            return response()->json([
                'success' => false,
                'message' => __('main.two_factor_already_enabled'),
            ], 422);
        }

        $secret = $service->generateSecret();
        $recoveryCodes = $service->generateRecoveryCodes();

        // Store temporarily in session until confirmed
        session([
            'totp_temp_secret' => $secret,
            'totp_temp_recovery_codes' => $recoveryCodes,
        ]);

        $qrCode = $service->getQRCodeInline(
            config('app.name', 'XVRX'),
            $user->email,
            $secret
        );

        return response()->json([
            'success' => true,
            'qr_code' => $qrCode,
            'secret' => $secret,
        ]);
    }

    /**
     * Confirm 2FA setup by verifying a TOTP code against the temporary secret.
     */
    public function confirmSetup(Request $request, TwoFactorAuthService $service): \Illuminate\Http\JsonResponse
    {
        $tempSecret = session('totp_temp_secret');
        $tempRecoveryCodes = session('totp_temp_recovery_codes');

        if (! $tempSecret || ! $tempRecoveryCodes) {
            return response()->json([
                'success' => false,
                'message' => __('main.two_factor_setup_expired'),
            ], 422);
        }

        $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ]);

        if (! $service->verify($tempSecret, $request->input('code'))) {
            return response()->json([
                'success' => false,
                'message' => __('main.two_factor_setup_error'),
            ], 422);
        }

        // Save to user
        $user = auth()->user();
        $user->forceFill([
            'totp_secret' => base64_encode($tempSecret),
            'totp_confirmed_at' => now(),
            'totp_recovery_codes' => json_encode($service->hashRecoveryCodes($tempRecoveryCodes)),
        ])->save();

        // Clear temp session data
        session()->forget(['totp_temp_secret', 'totp_temp_recovery_codes']);

        return response()->json([
            'success' => true,
            'message' => __('main.two_factor_setup_success'),
            'recovery_codes' => $tempRecoveryCodes,
        ]);
    }

    /**
     * Show the 2FA challenge page (code entry).
     */
    public function showChallenge(): View|RedirectResponse
    {
        $user = auth()->user();

        // If 2FA is not set up, redirect to setup
        if (! $user->is_admin) {
            abort(403);
        }

        if (! $user->hasTwoFactorEnabled()) {
            return redirect()->route('admin.2fa.setup');
        }

        // If already verified and within TTL, go straight to admin
        $verifiedAt = session('admin_2fa_verified_at');
        $ttl = config('2fa.admin_session_ttl', 30);

        if ($verifiedAt && $verifiedAt instanceof \Carbon\Carbon && $verifiedAt->diffInMinutes(now()) < $ttl) {
            return redirect()->intended(route('admin.index'));
        }

        return view('auth.two-factor.challenge');
    }

    /**
     * Verify the TOTP (or recovery) code and grant admin access.
     */
    public function verifyChallenge(Request $request, TwoFactorAuthService $service): RedirectResponse
    {
        $user = auth()->user();

        if (! $user->is_admin || ! $user->hasTwoFactorEnabled()) {
            abort(403);
        }

        $request->validate([
            'code' => ['required', 'string', 'max:255'],
        ]);

        $code = $request->input('code');

        // Try TOTP first
        $secret = $user->getTotpSecret();
        if ($secret && $service->verify($secret, $code)) {
            session(['admin_2fa_verified_at' => now()]);

            return redirect()->intended(route('admin.index'));
        }

        // Try recovery code
        $storedHashes = $user->getRecoveryCodes();
        if (! empty($storedHashes)) {
            $result = $service->verifyAndConsumeRecoveryCode($code, $storedHashes);

            if ($result['valid']) {
                $user->forceFill([
                    'totp_recovery_codes' => json_encode($result['remaining_codes']),
                ])->save();

                session(['admin_2fa_verified_at' => now()]);

                // If no recovery codes left, warn the user
                if (empty($result['remaining_codes'])) {
                    session()->flash('warning', __('main.two_factor_recovery_exhausted'));
                } else {
                    session()->flash('success', __('main.two_factor_recovery_code_used'));
                }

                return redirect()->intended(route('admin.index'));
            }
        }

        return back()->withErrors([
            'code' => __('main.two_factor_invalid_code'),
        ]);
    }

    /**
     * Disable 2FA for the current user (requires valid TOTP code).
     */
    public function disable(Request $request, TwoFactorAuthService $service): RedirectResponse
    {
        $user = auth()->user();

        if (! $user->hasTwoFactorEnabled()) {
            return back()->with('error', __('main.two_factor_not_setup'));
        }

        $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ]);

        $secret = $user->getTotpSecret();
        if (! $secret || ! $service->verify($secret, $request->input('code'))) {
            return back()->withErrors([
                'code' => __('main.two_factor_invalid_code'),
            ]);
        }

        $user->disableTwoFactor();

        // Clear 2FA session
        session()->forget('admin_2fa_verified_at');

        return redirect()->route('admin.2fa.setup')
            ->with('success', __('main.two_factor_disabled'));
    }
}
