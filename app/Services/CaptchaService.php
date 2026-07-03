<?php

namespace App\Services;

class CaptchaService
{
    protected RecaptchaService $recaptchaService;

    protected TurnstileService $turnstileService;

    public function __construct(RecaptchaService $recaptchaService, TurnstileService $turnstileService)
    {
        $this->recaptchaService = $recaptchaService;
        $this->turnstileService = $turnstileService;
    }

    /**
     * Check if captcha is enabled
     */
    public function isEnabled(): bool
    {
        $method = config('captcha.method');

        return $method === 'google' || $method === 'cloudflare';
    }

    /**
     * Verify captcha token (delegates to Google or Cloudflare based on config)
     */
    public function verify(string $token, ?string $ip = null): bool
    {
        $method = config('captcha.method');

        if ($method === 'false' || $method === false || $method === '') {
            return true;
        }

        if ($method === 'cloudflare') {
            return $this->turnstileService->verify($token, $ip);
        }

        if ($method === 'google') {
            return $this->recaptchaService->verify($token, $ip);
        }

        return true;
    }
}
