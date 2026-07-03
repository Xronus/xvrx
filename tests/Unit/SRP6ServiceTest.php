<?php

namespace Tests\Unit;

use App\Services\SRP6Service;
use Tests\TestCase;

class SRP6ServiceTest extends TestCase
{
    private SRP6Service $srp6;

    protected function setUp(): void
    {
        parent::setUp();
        $this->srp6 = new SRP6Service;
    }

    public function test_get_registration_data_returns_salt_and_verifier(): void
    {
        [$salt, $verifier] = $this->srp6->getRegistrationData('TESTUSER', 'testpassword');

        $this->assertNotEmpty($salt);
        $this->assertNotEmpty($verifier);
        $this->assertEquals(32, strlen($salt));
    }

    public function test_calculate_verifier_is_deterministic(): void
    {
        $salt = random_bytes(32);

        $v1 = $this->srp6->calculateVerifier('TESTUSER', 'testpassword', $salt);
        $v2 = $this->srp6->calculateVerifier('TESTUSER', 'testpassword', $salt);

        $this->assertEquals($v1, $v2);
    }

    public function test_verify_login_succeeds_with_correct_credentials(): void
    {
        [$salt, $verifier] = $this->srp6->getRegistrationData('PLAYER1', 'secret123');

        $result = $this->srp6->verifyLogin('PLAYER1', 'secret123', $salt, $verifier);

        $this->assertTrue($result);
    }

    public function test_verify_login_fails_with_wrong_password(): void
    {
        [$salt, $verifier] = $this->srp6->getRegistrationData('PLAYER1', 'secret123');

        $result = $this->srp6->verifyLogin('PLAYER1', 'wrongpassword', $salt, $verifier);

        $this->assertFalse($result);
    }

    public function test_verify_login_fails_with_wrong_username(): void
    {
        [$salt, $verifier] = $this->srp6->getRegistrationData('PLAYER1', 'secret123');

        $result = $this->srp6->verifyLogin('OTHERPLAYER', 'secret123', $salt, $verifier);

        $this->assertFalse($result);
    }

    public function test_username_is_case_insensitive(): void
    {
        [$salt, $verifier] = $this->srp6->getRegistrationData('PLAYER1', 'secret123');

        $result = $this->srp6->verifyLogin('player1', 'secret123', $salt, $verifier);

        $this->assertTrue($result);
    }

    public function test_different_users_have_different_verifiers(): void
    {
        [$salt1, $verifier1] = $this->srp6->getRegistrationData('USER_A', 'samepass');
        [$salt2, $verifier2] = $this->srp6->getRegistrationData('USER_B', 'samepass');

        $this->assertNotEquals($salt1, $salt2);
        $this->assertNotEquals($verifier1, $verifier2);
    }
}
