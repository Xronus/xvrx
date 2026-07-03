<?php

namespace Tests\Feature\Auth;

use App\Models\SiteSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class RegistrationTest extends TestCase
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

    public function test_registration_page_is_accessible(): void
    {
        $response = $this->get('/cp/register');
        $response->assertStatus(200);
    }

    public function test_registration_validation_requires_username(): void
    {
        $response = $this->post('/cp/register', [
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['username']);
    }

    public function test_registration_validation_requires_valid_email(): void
    {
        $response = $this->post('/cp/register', [
            'username' => 'testuser',
            'email' => 'not-an-email',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['email']);
    }

    public function test_registration_validation_password_confirmation(): void
    {
        $response = $this->post('/cp/register', [
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'different',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['password']);
    }

    public function test_registration_username_only_latin_letters(): void
    {
        $response = $this->post('/cp/register', [
            'username' => 'testuser123',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['username']);
    }

    public function test_registration_username_max_14_chars(): void
    {
        $response = $this->post('/cp/register', [
            'username' => 'verylongusername',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['username']);
    }

    public function test_registration_password_min_8_chars(): void
    {
        $response = $this->post('/cp/register', [
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => 'short',
            'password_confirmation' => 'short',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['password']);
    }
}
