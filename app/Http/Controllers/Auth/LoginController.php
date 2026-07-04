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
        $settings = SiteSetting::first();

        return view('auth.login', compact('settings'));
    }

    public function showForgotPasswordForm()
    {
        $settings = SiteSetting::first();

        return view('auth.forgot-password', compact('settings'));
    }

    public function sendPasswordResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
        ]);

        $user = User::where('email', $request->email)->first();
        $settings = SiteSetting::first();
        $rateLimit = max(1, min(60, (int) ($settings?->mail_password_reset_rate_limit ?: 3)));
        $rateLimitKey = 'password-reset:'.Str::lower($request->email).'|'.$request->ip();

        if (RateLimiter::tooManyAttempts($rateLimitKey, $rateLimit)) {
            return back()->withErrors([
                'email' => __('main.password_reset_rate_limit', [
                    'seconds' => RateLimiter::availableIn($rateLimitKey),
                ]),
            ])->withInput();
        }

        RateLimiter::hit($rateLimitKey, 60);

        if ($user) {
            try {
                if ($settings && $settings->mail_password_reset_enabled === false) {
                    Log::warning('Password reset email skipped because mail is disabled in admin settings', [
                        'email' => $user->email,
                        'username' => $user->username,
                    ]);

                    return back()->with('status', __('main.forgot_password_sent'));
                }

                $token = bin2hex(random_bytes(32));

                DB::table('password_reset_tokens')->updateOrInsert(
                    ['email' => $user->email],
                    [
                        'token' => $token,
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
            } catch (\Exception $e) {
                Log::error('Failed to send password reset email: '.$e->getMessage(), [
                    'email' => $request->email,
                    'trace' => $e->getTraceAsString(),
                ]);
            }
        }

        return back()->with('status', __('main.forgot_password_sent'));
    }

    public function showResetForm(Request $request)
    {
        if (! $request->has('token') || ! $request->has('email')) {
            return redirect()->route('password.request')
                ->withErrors(['email' => __('main.invalid_reset_token')]);
        }

        $settings = SiteSetting::first();

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

        if (! $record || $record->token !== $request->token) {
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

        $user = User::where('username', $request->username)->first();

        if (! $user) {
            throw ValidationException::withMessages([
                'username' => [__('validation.invalid_credentials')],
            ]);
        }

        $saltRaw = $user->getRawOriginal('salt');
        $verifierRaw = $user->getRawOriginal('verifier');

        if (is_resource($saltRaw)) {
            $salt = stream_get_contents($saltRaw);
        } elseif (is_string($saltRaw)) {
            $decoded = base64_decode($saltRaw, true);
            $salt = $decoded !== false ? $decoded : $saltRaw;
        } else {
            $salt = $saltRaw;
        }

        if (is_resource($verifierRaw)) {
            $verifier = stream_get_contents($verifierRaw);
        } elseif (is_string($verifierRaw)) {
            $decoded = base64_decode($verifierRaw, true);
            $verifier = $decoded !== false ? $decoded : $verifierRaw;
        } else {
            $verifier = $verifierRaw;
        }

        if (! $this->srp6Service->verifyLogin(
            strtoupper($request->username),
            $request->password,
            $salt,
            $verifier
        )) {
            throw ValidationException::withMessages([
                'username' => [__('validation.invalid_credentials')],
            ]);
        }

        Auth::login($user, $request->boolean('remember'));

        $request->session()->regenerate();

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
}
