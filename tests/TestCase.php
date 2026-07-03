<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Make a POST request with CSRF token, expecting JSON validation response.
     */
    public function jsonPost(string $uri, array $data = [], array $headers = []): \Illuminate\Testing\TestResponse
    {
        $csrfToken = csrf_token();

        return $this->withHeaders(array_merge([
            'Accept' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
            'X-CSRF-TOKEN' => $csrfToken,
        ], $headers))->withSession(['_token' => $csrfToken])->post($uri, $data);
    }
}
