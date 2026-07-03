<?php

namespace App\Services;

class SRP6Service
{
    private $g;

    private $N;

    public function __construct()
    {
        $this->g = gmp_init(7);
        $this->N = gmp_init('894B645E89E1535BBDAD5B8B290650530801B18EBFBF5E8FAB3C82872A3E9BB7', 16);
    }

    public function calculateVerifier(string $username, string $password, string $salt): string
    {
        $h1 = sha1(strtoupper($username.':'.$password), true);
        $h2 = sha1($salt.$h1, true);
        $h2 = gmp_import($h2, 1, GMP_LSW_FIRST);
        $verifier = gmp_powm($this->g, $h2, $this->N);
        $verifier = gmp_export($verifier, 1, GMP_LSW_FIRST);

        return $verifier;
    }

    public function getRegistrationData(string $username, string $password): array
    {
        $salt = random_bytes(32);
        $verifier = $this->calculateVerifier($username, $password, $salt);

        return [$salt, $verifier];
    }

    public function verifyLogin(string $username, string $password, string $salt, string $verifier): bool
    {
        $checkVerifier = $this->calculateVerifier($username, $password, $salt);

        return hash_equals($verifier, $checkVerifier);
    }
}
