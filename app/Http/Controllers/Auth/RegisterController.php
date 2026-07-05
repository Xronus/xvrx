<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Models\User;
use App\Services\CaptchaService;
use App\Services\SRP6Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    protected $srp6Service;

    protected $captchaService;

    public function __construct(SRP6Service $srp6Service, CaptchaService $captchaService)
    {
        $this->srp6Service = $srp6Service;
        $this->captchaService = $captchaService;
    }

    public function showRegistrationForm()
    {
        $settings = SiteSetting::first();

        return view('auth.register', compact('settings'));
    }

    public function register(Request $request)
    {
        $rules = [
            'username' => 'required|string|max:14|regex:/^[a-zA-Z]+$/',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'string', 'min:8', 'confirmed', Password::defaults()],
        ];

        $messages = [
            'username.required' => __('validation.username_required'),
            'username.string' => __('validation.username_string'),
            'username.max' => __('validation.username_max'),
            'username.regex' => __('validation.username_regex'),
            'email.required' => __('validation.email_required'),
            'email.string' => __('validation.email_string'),
            'email.email' => __('validation.email_email'),
            'email.max' => __('validation.email_max'),
            'email.unique' => __('validation.email_unique'),
            'password.required' => __('validation.password_required'),
            'password.string' => __('validation.password_string'),
            'password.min' => __('validation.password_min'),
            'password.confirmed' => __('validation.password_confirmed'),
        ];

        // Add captcha validation only if enabled (google or cloudflare)
        if ($this->captchaService->isEnabled()) {
            $rules['recaptcha_token'] = 'required|string';
            $messages['recaptcha_token.required'] = __('main.captcha_validation_error');
        }

        $request->validate($rules, $messages);

        // Verify captcha token if enabled
        if ($this->captchaService->isEnabled() && ! $this->captchaService->verify($request->recaptcha_token ?? '', $request->ip())) {
            return response()->json([
                'status' => false,
                'message' => __('main.captcha_validation_error'),
            ], 422);
        }

        [$salt, $verifier] = $this->srp6Service->getRegistrationData(
            strtoupper($request->username),
            $request->password
        );

        // 1. Create website user first (transaction on default connection)
        DB::beginTransaction();

        try {
            // Check uniqueness atomically with lock
            $exists = User::where('username', $request->username)->lockForUpdate()->exists();
            if ($exists) {
                DB::rollBack();

                return response()->json([
                    'status' => false, 'type' => 1,
                    'message' => __('validation.username_exists'),
                    'fields' => ['username'],
                ], 422);
            }

            $user = User::create([
                'username' => $request->username,
                'email' => $request->email,
                'salt' => base64_encode($salt),
                'verifier' => base64_encode($verifier),
                'password' => Hash::make($request->password, ['rounds' => 12]),
                'votes' => 0,
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Website user creation error: '.$e->getMessage(), [
                'username' => $request->username,
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'status' => false,
                'message' => __('validation.registration_error'),
            ], 500);
        }

        // 2. Create game account (separate connection — clean up website user on failure)
        try {
            $gameExists = DB::connection('game_auth')->table('account')
                ->where('username', strtoupper($request->username))
                ->exists();

            if ($gameExists) {
                // Rollback: delete website user we just created
                $user->delete();
                Log::warning('Registration: game account already exists, deleted website user', [
                    'username' => $request->username,
                ]);

                return response()->json([
                    'status' => false, 'type' => 1,
                    'message' => __('validation.account_already_exists'),
                    'fields' => ['username'],
                ], 422);
            }

            DB::connection('game_auth')->table('account')->insert([
                'username' => strtoupper($request->username),
                'email' => $request->email,
                'salt' => $salt,
                'verifier' => $verifier,
                'totaltime' => 0,
            ]);
        } catch (\Exception $e) {
            // Clean up orphan: delete website user since game account failed
            $user->delete();
            Log::error('Game account creation failed, website user deleted: '.$e->getMessage(), [
                'username' => $request->username,
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'status' => false,
                'message' => __('validation.game_account_error'),
            ], 500);
        }

        Auth::login($user);

        return response()->json([
            'status' => true,
            'message' => 'Регистрация прошла успешно!',
            'redirect' => route('cabinet'),
        ]);

    }
}
