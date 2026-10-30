<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    public function createApplication()
    {
        $app = parent::createApplication();
        $connection = $app['config']->get('database.default');
        $database = $app['config']->get("database.connections.{$connection}.database");

        if (! $app->environment('testing') || $connection !== 'sqlite' || $database !== ':memory:') {
            throw new \RuntimeException(
                'Unsafe test database configuration. Tests require APP_ENV=testing and SQLite :memory:.'
            );
        }

        return $app;
    }

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
