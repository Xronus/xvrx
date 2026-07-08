<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResetMail;
use App\Models\SiteSetting;
use App\Models\User;
use App\Services\CaptchaService;
use App\Services\SRP6Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    protected $srp6Service;

    protected $captchaService;

    public function __construct(SRP6Service $srp6Service, CaptchaService $captchaService)
    {
        $this->srp6Service = $srp6Service;
        $this->captchaService = $captchaService;
    }

    public function showLoginForm()
    {
        $settings = site_settings();

        return view('auth.login', compact('settings'));
    }

    public function showForgotPasswordForm()
    {
        $settings = site_settings();

        return view('auth.forgot-password', compact('settings'));
    }

    public function sendPasswordResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
        ]);

        $user = User::where('email', $request->email)->first();

        // Return the same "sent" message for non-existent emails to prevent enumeration
        if (! $user) {
            return back()->with('status', __('main.forgot_password_sent'));
        }

        $settings = site_settings();

        // Check if mail is enabled BEFORE counting rate limiter attempts
        if ($settings && $settings->mail_password_reset_enabled === false) {
            Log::info('Password reset requested but mail is disabled in admin settings', [
                'email' => $user->email,
                'username' => $user->username,
            ]);

            return back()->with('status', __('main.forgot_password_sent'));
        }

        $rateLimit = max(1, min(60, (int) ($settings?->mail_password_reset_rate_limit ?: 3)));
        $rateLimitKey = 'password-reset:'.Str::lower($request->email).'|'.$request->ip();

        if (RateLimiter::tooManyAttempts($rateLimitKey, $rateLimit)) {
            return back()->withErrors([
                'email' => __('main.password_reset_rate_limit', [
                    'seconds' => RateLimiter::availableIn($rateLimitKey),
                ]),
            ])->withInput();
        }

        // Use configured rate limit window (decay = seconds per attempt)
        RateLimiter::hit($rateLimitKey, max(60, $rateLimit * 60));

        try {
            $token = bin2hex(random_bytes(32));

            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $user->email],
                [
                    'token' => hash('sha256', $token),
                    'created_at' => now(),
                ]
            );

            $resetUrl = route('password.reset', [
                'token' => $token,
                'email' => $user->email,
            ]);

            Mail::to($user->email)->send(new PasswordResetMail(
                resetUrl: $resetUrl,
                subjectLine: $settings?->mail_reset_subject,
                bodyText: $settings?->mail_reset_body,
                fromName: $settings?->mail_from_name,
            ));

            Log::info('Password reset email sent', [
                'email' => $user->email,
                'username' => $user->username,
            ]);

            return back()->with('status', __('main.forgot_password_sent'));
        } catch (\Exception $e) {
            Log::error('Failed to send password reset email: '.$e->getMessage(), [
                'email' => $request->email,
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->withErrors([
                'email' => __('main.password_reset_mail_error'),
            ])->withInput();
        }
    }

    public function showResetForm(Request $request)
    {
        if (! $request->has('token') || ! $request->has('email')) {
            return redirect()->route('password.request')
                ->withErrors(['email' => __('main.invalid_reset_token')]);
        }

        $settings = site_settings();

        return view('auth.reset-password', [
            'token' => $request->token,
            'email' => $request->email,
            'settings' => $settings,
        ]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'email' => 'required|email|exists:users,email',
            'password' => ['required', 'string', 'min:8', 'confirmed', Password::defaults()],
        ], [
            'password.required' => __('validation.password_required'),
            'password.min' => __('validation.password_min'),
            'password.confirmed' => __('validation.password_confirmed'),
        ]);

        $record = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (! $record || $record->token !== hash('sha256', $request->token)) {
            return back()->withErrors(['email' => __('main.invalid_reset_token')]);
        }

        if (now()->diffInMinutes($record->created_at) > 60) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();

            return back()->withErrors(['email' => __('main.invalid_reset_token')]);
        }

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return back()->withErrors(['email' => __('main.invalid_reset_token')]);
        }

        try {
            [$salt, $verifier] = $this->srp6Service->getRegistrationData(
                strtoupper($user->username),
                $request->password
            );

            DB::beginTransaction();

            DB::connection('game_auth')
                ->table('account')
                ->where('username', strtoupper($user->username))
                ->update([
                    'salt' => $salt,
                    'verifier' => $verifier,
                ]);

            DB::table('users')
                ->where('id', $user->id)
                ->update([
                    'salt' => base64_encode($salt),
                    'verifier' => base64_encode($verifier),
                    'password' => Hash::make($request->password, ['rounds' => 12]),
                    'updated_at' => now(),
                ]);

            DB::table('password_reset_tokens')->where('email', $request->email)->delete();

            DB::commit();

            Log::info('Password reset successful', [
                'username' => $user->username,
                'email' => $user->email,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Password reset error: '.$e->getMessage(), [
                'email' => $request->email,
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->withErrors([
                'email' => __('validation.password_reset_error'),
            ]);
        }

        return redirect()->route('login')->with('status', __('main.password_reset_success'));
    }

    public function login(Request $request)
    {
        $rules = [
            'username' => 'required|string|max:14',
            'password' => 'required|string|min:8',
        ];

        $messages = [
            'username.required' => __('validation.username_required'),
            'username.string' => __('validation.username_string'),
            'username.max' => __('validation.username_max'),
            'password.required' => __('validation.password_required'),
            'password.string' => __('validation.password_string'),
            'password.min' => __('validation.password_min'),
        ];

        // Add captcha validation only if enabled (google or cloudflare)
        if ($this->captchaService->isEnabled()) {
            $rules['recaptcha_token'] = 'required|string';
            $messages['recaptcha_token.required'] = __('main.captcha_validation_error');
        }

        $request->validate($rules, $messages);

        // Verify captcha token if enabled
        if ($this->captchaService->isEnabled() && ! $this->captchaService->verify($request->recaptcha_token ?? '', $request->ip())) {
            throw ValidationException::withMessages([
                'username' => [__('main.captcha_validation_error')],
            ]);
        }

        $user = User::whereRaw('LOWER(username) = ?', [strtolower($request->username)])->first();

        if (! $user) {
            throw ValidationException::withMessages([
                'username' => [__('validation.invalid_credentials')],
            ]);
        }

        // Check if user is banned on the website
        if ($user->isBanned()) {
            $reason = $user->ban_reason
                ? __('main.banned_by_admin').': '.$user->ban_reason
                : __('main.banned_by_admin');

            throw ValidationException::withMessages([
                'username' => [$reason],
            ]);
        }

        // User model accessors handle salt/verifier decoding
        if (! $this->srp6Service->verifyLogin(
            strtoupper($request->username),
            $request->password,
            $user->salt,
            $user->verifier
        )) {
            throw ValidationException::withMessages([
                'username' => [__('validation.invalid_credentials')],
            ]);
        }

        Auth::login($user, $request->boolean('remember'));

        $request->session()->regenerate();

        // Check for active game ban and store in session for cabinet warning
        $this->checkGameBan($request, $user);

        return response()->json([
            'status' => true,
            'redirect' => route('cabinet'),
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    /**
     * Check if the user has an active game ban and store it in session.
     */
    private function checkGameBan(Request $request, $user): void
    {
        try {
            // Get account ID from game auth DB
            $accountId = DB::connection('game_auth')
                ->table('account')
                ->where('username', strtoupper($user->username))
                ->value('id');

            if (! $accountId) {
                $request->session()->forget('game_ban');

                return;
            }

            // Check for active ban
            // TrinityCore: account_banned.id = account ID (PK)
            $gameBan = DB::connection('game_auth')
                ->table('account_banned')
                ->where('id', $accountId)
                ->where('active', 1)
                ->first();

            if ($gameBan) {
                $request->session()->put('game_ban', [
                    'reason' => $gameBan->banreason ?? __('main.game_banned_default'),
                    'bandate' => $gameBan->bandate,
                    'unbandate' => $gameBan->unbandate,
                    'bannedby' => $gameBan->bannedby ?? '',
                ]);
            } else {
                $request->session()->forget('game_ban');
            }
        } catch (\Exception $e) {
            Log::error('Failed to check game ban: '.$e->getMessage());
            $request->session()->forget('game_ban');
        }
    }
}
