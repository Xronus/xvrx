<?php

namespace Tests\Feature\Auth;

use App\Models\SiteSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;
    use WithoutMiddleware;

    protected function setUp(): void
    {
        parent::setUp();

        SiteSetting::firstOrCreate(['id' => 1], [
            'title' => 'Test Server',
            'description' => 'Test Description',
        ]);
    }

    public function test_login_page_is_accessible(): void
    {
        $response = $this->get('/cp');
        $response->assertStatus(200);
    }

    public function test_login_validation_requires_username(): void
    {
        $response = $this->post('/cp', ['password' => 'password123']);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['username']);
    }

    public function test_login_validation_requires_password(): void
    {
        $response = $this->post('/cp', ['username' => 'testuser']);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['password']);
    }

    public function test_login_fails_with_invalid_credentials(): void
    {
        $response = $this->post('/cp', [
            'username' => 'nonexistent',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['username']);
    }

    public function test_forgot_password_page_is_accessible(): void
    {
        $this->markTestSkipped('View requires full DB setup with language_settings table.');
    }

    public function test_forgot_password_requires_email(): void
    {
        $response = $this->post('/cp/forgot-password', []);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['email']);
    }

    public function test_forgot_password_accepts_any_email(): void
    {
        $response = $this->post('/cp/forgot-password', [
            'email' => 'nonexistent@example.com',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('status');
    }

    public function test_forgot_password_uses_configured_rate_limit(): void
    {
        SiteSetting::first()->update(['mail_password_reset_rate_limit' => 1]);

        $this->post('/cp/forgot-password', [
            'email' => 'limited@example.com',
        ])->assertSessionHas('status');

        $this->post('/cp/forgot-password', [
            'email' => 'limited@example.com',
        ])->assertSessionHasErrors(['email']);
    }
}
