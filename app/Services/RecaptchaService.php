<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RecaptchaService
{
    protected $secretKey;
    protected $verifyUrl;
    protected $minScore;

    public function __construct()
    {
        $this->secretKey = config('recaptcha.secret_key');
        $this->verifyUrl = config('recaptcha.verify_url');
        $this->minScore = config('recaptcha.min_score', 0.5);
    }

    /**
     * Verify reCAPTCHA token
     *
     * @param string $token
     * @param string|null $ip
     * @return bool
     */
    public function verify(string $token, ?string $ip = null): bool
    {
        if (empty($this->secretKey)) {
            // If secret key is not configured, skip verification (for development)
            Log::warning('reCAPTCHA secret key is not configured. Skipping verification.');
            return true;
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

            if (!$response->successful()) {
                Log::error('reCAPTCHA verification failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return false;
            }

            $data = $response->json();

            if (!isset($data['success']) || $data['success'] !== true) {
                Log::warning('reCAPTCHA verification failed', [
                    'errors' => $data['error-codes'] ?? [],
                ]);
                return false;
            }

            // Check score for reCAPTCHA v3
            if (isset($data['score']) && $data['score'] < $this->minScore) {
                Log::warning('reCAPTCHA score too low', [
                    'score' => $data['score'],
                    'min_score' => $this->minScore,
                ]);
                return false;
            }

            return true;
        } catch (\Exception $e) {
            Log::error('reCAPTCHA verification exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return false;
        }
    }
}
