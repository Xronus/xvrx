<?php

namespace App\Services;

use App\Exceptions\YooKassaApiException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class YooKassaClient
{
    public function configured(): bool
    {
        return filled(config('yookassa.shop_id')) && filled(config('yookassa.secret_key'));
    }

    public function createPayment(array $payload, string $idempotenceKey): array
    {
        $response = $this->request()
            ->withHeader('Idempotence-Key', $idempotenceKey)
            ->post('/payments', $payload);

        return $this->decode($response->status(), $response->json());
    }

    public function getPayment(string $paymentId): array
    {
        $response = $this->request()->get('/payments/'.rawurlencode($paymentId));

        return $this->decode($response->status(), $response->json());
    }

    private function request(): PendingRequest
    {
        if (! $this->configured()) {
            throw new YooKassaApiException('YooKassa credentials are not configured.');
        }

        return Http::baseUrl(rtrim((string) config('yookassa.api_url'), '/'))
            ->withBasicAuth((string) config('yookassa.shop_id'), (string) config('yookassa.secret_key'))
            ->acceptJson()
            ->asJson()
            ->connectTimeout(5)
            ->timeout(15);
    }

    private function decode(int $status, mixed $body): array
    {
        if ($status < 200 || $status >= 300 || ! is_array($body)) {
            $code = is_array($body) ? ($body['code'] ?? 'unknown_error') : 'invalid_response';

            throw new YooKassaApiException('YooKassa API error: '.$code, $status);
        }

        return $body;
    }
}
