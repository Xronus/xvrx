<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TurnstileService
{
    protected $secretKey;

    protected $verifyUrl;

    public function __construct()
    {
        $this->secretKey = config('turnstile.secret_key');
        $this->verifyUrl = config('turnstile.verify_url');
    }

    /**
     * Verify Cloudflare Turnstile token
     */
    public function verify(string $token, ?string $ip = null): bool
    {
        if (empty($this->secretKey)) {
            Log::error('Turnstile secret key is not configured. CAPTCHA verification cannot proceed.');

            return false;
        }

        if (empty($token)) {
            return false;
        }

        try {
            $response = Http::asForm()->post($this->verifyUrl, [
                'secret' => $this->secretKey,
                'response' => $token,
                'remoteip' => $ip ?? request()->ip(),
            ]);

            if (! $response->successful()) {
                Log::error('Turnstile verification failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return false;
            }

            $data = $response->json();

            if (! isset($data['success']) || $data['success'] !== true) {
                Log::warning('Turnstile verification failed', [
                    'error-codes' => $data['error-codes'] ?? [],
                ]);

                return false;
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Turnstile verification exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return false;
        }
    }
}
