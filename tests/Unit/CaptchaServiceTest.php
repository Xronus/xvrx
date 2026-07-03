<?php

namespace Tests\Unit;

use App\Services\CaptchaService;
use App\Services\RecaptchaService;
use App\Services\TurnstileService;
use Tests\TestCase;

class CaptchaServiceTest extends TestCase
{
    private CaptchaService $captchaService;

    protected function setUp(): void
    {
        parent::setUp();

        $recaptcha = $this->createMock(RecaptchaService::class);
        $turnstile = $this->createMock(TurnstileService::class);

        $this->captchaService = new CaptchaService($recaptcha, $turnstile);
    }

    public function test_is_enabled_returns_false_when_config_is_false(): void
    {
        config(['captcha.method' => 'false']);

        $this->assertFalse($this->captchaService->isEnabled());
    }

    public function test_is_enabled_returns_true_when_config_is_google(): void
    {
        config(['captcha.method' => 'google']);

        $this->assertTrue($this->captchaService->isEnabled());
    }

    public function test_is_enabled_returns_true_when_config_is_cloudflare(): void
    {
        config(['captcha.method' => 'cloudflare']);

        $this->assertTrue($this->captchaService->isEnabled());
    }

    public function test_verify_returns_true_when_captcha_is_disabled(): void
    {
        config(['captcha.method' => 'false']);

        $result = $this->captchaService->verify('any-token', '127.0.0.1');

        $this->assertTrue($result);
    }
}
