<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\SRP6Service;
use App\Services\CaptchaService;
use App\Models\User;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        return back()->with('status', 'Если указанный адрес есть в системе, на него будет отправлена ссылка для восстановления пароля.');
    }

    public function login(Request $request)
    {
        $rules = [
            'username' => 'required|string|max:14',
            'password' => 'required|string|min:8',
        ];

        $messages = [
            'username.required' => 'Введите логин.',
            'username.string' => 'Логин должен быть строкой.',
            'username.max' => 'Логин не должен быть длиннее 14 символов.',
            'password.required' => 'Введите пароль.',
            'password.string' => 'Пароль должен быть строкой.',
            'password.min' => 'Пароль должен быть не короче 8 символов.',
        ];

        // Add captcha validation only if enabled (google or cloudflare)
        if ($this->captchaService->isEnabled()) {
            $rules['recaptcha_token'] = 'required|string';
            $messages['recaptcha_token.required'] = __('main.captcha_validation_error');
        }

        $request->validate($rules, $messages);

        // Verify captcha token if enabled
        if ($this->captchaService->isEnabled() && !$this->captchaService->verify($request->recaptcha_token ?? '', $request->ip())) {
            throw ValidationException::withMessages([
                'username' => [__('main.captcha_validation_error')],
            ]);
        }

        $user = User::where('username', $request->username)->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'username' => ['Неверный логин или пароль'],
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

        if (!$this->srp6Service->verifyLogin(
            strtoupper($request->username),
            $request->password,
            $salt,
            $verifier
        )) {
            throw ValidationException::withMessages([
                'username' => ['Неверный логин или пароль'],
            ]);
        }

        Auth::login($user, $request->boolean('remember'));

        $request->session()->regenerate();

        return response()->json([
            'status' => true,
            'redirect' => route('cabinet')
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
