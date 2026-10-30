<?php

namespace App\Services;

use App\Exceptions\YooKassaApiException;
use App\Models\BalanceTopUp;
use App\Models\User;
use DomainException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class BalanceTopUpService
{
    public function __construct(private readonly YooKassaClient $client)
    {
    }

    public function configured(): bool
    {
        return $this->client->configured();
    }

    public function create(User $user, int $rubles): BalanceTopUp
    {
        if (! $this->configured()) {
            throw new YooKassaApiException('YooKassa credentials are not configured.');
        }

        $topUp = BalanceTopUp::create([
            'public_id' => (string) Str::uuid(),
            'user_id' => $user->id,
            'provider' => 'yookassa',
            'idempotence_key' => (string) Str::uuid(),
            'amount_kopecks' => $rubles * 100,
            'currency' => 'RUB',
            'bonus_amount' => $rubles * (int) config('yookassa.bonus_per_ruble'),
            'status' => BalanceTopUp::STATUS_CREATING,
        ]);

        try {
            $payment = $this->client->createPayment(
                $this->paymentPayload($topUp, $user),
                $topUp->idempotence_key
            );

            return $this->applyProviderPayment($topUp, $payment);
        } catch (Throwable $exception) {
            $topUp->update([
                'status' => BalanceTopUp::STATUS_FAILED,
                'failure_code' => 'creation_error',
            ]);

            Log::error('YooKassa payment creation failed', [
                'top_up_id' => $topUp->public_id,
                'user_id' => $user->id,
                'exception' => $exception::class,
            ]);

            throw $exception;
        }
    }

    public function synchronize(BalanceTopUp $topUp): BalanceTopUp
    {
        if (! $topUp->provider_payment_id) {
            return $topUp;
        }

        return $this->applyProviderPayment(
            $topUp,
            $this->client->getPayment($topUp->provider_payment_id)
        );
    }

    public function handleWebhook(array $notification): void
    {
        if (($notification['type'] ?? null) !== 'notification') {
            throw new DomainException('Unsupported notification type.');
        }

        $event = $notification['event'] ?? null;
        if (! in_array($event, ['payment.succeeded', 'payment.canceled'], true)) {
            return;
        }

        $paymentId = Arr::get($notification, 'object.id');
        $publicId = Arr::get($notification, 'object.metadata.top_up_id');

        if (! is_string($paymentId) || $paymentId === '') {
            throw new DomainException('Payment id is missing.');
        }

        $topUp = BalanceTopUp::query()
            ->where('provider_payment_id', $paymentId)
            ->when(is_string($publicId) && $publicId !== '', fn ($query) => $query->orWhere('public_id', $publicId))
            ->first();

        if (! $topUp) {
            Log::warning('YooKassa notification for unknown payment', [
                'provider_payment_id' => $paymentId,
            ]);

            return;
        }

        // Never trust the notification body for crediting. Fetch the current
        // payment directly from YooKassa using HTTP Basic authentication.
        $this->applyProviderPayment($topUp, $this->client->getPayment($paymentId));
    }

    private function paymentPayload(BalanceTopUp $topUp, User $user): array
    {
        $value = $this->formatAmount($topUp->amount_kopecks);
        $payload = [
            'amount' => [
                'value' => $value,
                'currency' => 'RUB',
            ],
            'capture' => true,
            'confirmation' => [
                'type' => 'redirect',
                'return_url' => route('balance.topup.complete', $topUp),
            ],
            'description' => 'Пополнение баланса XVRX #'.$topUp->id,
            'metadata' => [
                'top_up_id' => $topUp->public_id,
                'user_id' => (string) $user->id,
            ],
        ];

        if (config('yookassa.receipt.enabled')) {
            $payload['receipt'] = [
                'customer' => ['email' => $user->email],
                'items' => [[
                    'description' => 'Пополнение бонусного баланса XVRX',
                    'quantity' => '1.000',
                    'amount' => [
                        'value' => $value,
                        'currency' => 'RUB',
                    ],
                    'vat_code' => (int) config('yookassa.receipt.vat_code'),
                    'payment_subject' => 'service',
                    'payment_mode' => 'full_payment',
                ]],
            ];
        }

        return $payload;
    }

    private function applyProviderPayment(BalanceTopUp $topUp, array $payment): BalanceTopUp
    {
        $this->validateProviderPayment($topUp, $payment);

        $status = (string) ($payment['status'] ?? '');
        $snapshot = [
            'status' => $status,
            'paid' => (bool) ($payment['paid'] ?? false),
            'test' => (bool) ($payment['test'] ?? false),
            'created_at' => $payment['created_at'] ?? null,
            'captured_at' => $payment['captured_at'] ?? null,
            'cancellation_reason' => Arr::get($payment, 'cancellation_details.reason'),
        ];

        if (($payment['test'] ?? false) && ! config('yookassa.allow_test_payments')) {
            throw new DomainException('Test payments are disabled.');
        }

        return DB::transaction(function () use ($topUp, $payment, $status, $snapshot) {
            $lockedTopUp = BalanceTopUp::query()->lockForUpdate()->findOrFail($topUp->id);

            $common = [
                'provider_payment_id' => $payment['id'],
                'status' => $status,
                'is_test' => (bool) ($payment['test'] ?? false),
                'provider_snapshot' => $snapshot,
                'confirmation_url' => Arr::get($payment, 'confirmation.confirmation_url'),
                'failure_code' => Arr::get($payment, 'cancellation_details.reason'),
            ];

            if ($status !== BalanceTopUp::STATUS_SUCCEEDED || ! ($payment['paid'] ?? false)) {
                $lockedTopUp->update($common);

                return $lockedTopUp->fresh();
            }

            if ($lockedTopUp->credited_at) {
                return $lockedTopUp;
            }

            $user = User::query()->lockForUpdate()->findOrFail($lockedTopUp->user_id);
            $user->increment('bonuses', $lockedTopUp->bonus_amount);

            $lockedTopUp->update(array_merge($common, [
                'confirmation_url' => null,
                'failure_code' => null,
                'paid_at' => now(),
                'credited_at' => now(),
            ]));

            Log::info('YooKassa balance top-up credited', [
                'top_up_id' => $lockedTopUp->public_id,
                'user_id' => $lockedTopUp->user_id,
                'bonus_amount' => $lockedTopUp->bonus_amount,
                'is_test' => $lockedTopUp->is_test,
            ]);

            return $lockedTopUp->fresh();
        });
    }

    private function validateProviderPayment(BalanceTopUp $topUp, array $payment): void
    {
        $paymentId = $payment['id'] ?? null;
        $currency = Arr::get($payment, 'amount.currency');
        $value = Arr::get($payment, 'amount.value');
        $metadataTopUpId = Arr::get($payment, 'metadata.top_up_id');
        $metadataUserId = Arr::get($payment, 'metadata.user_id');
        $recipientId = Arr::get($payment, 'recipient.account_id');

        if (! is_string($paymentId) || $paymentId === '') {
            throw new DomainException('Provider payment id is missing.');
        }

        if ($topUp->provider_payment_id && ! hash_equals($topUp->provider_payment_id, $paymentId)) {
            throw new DomainException('Provider payment id mismatch.');
        }

        if ($currency !== $topUp->currency || $value !== $this->formatAmount($topUp->amount_kopecks)) {
            throw new DomainException('Payment amount mismatch.');
        }

        if (! is_string($metadataTopUpId) || ! hash_equals($topUp->public_id, $metadataTopUpId)) {
            throw new DomainException('Payment metadata mismatch.');
        }

        if ((string) $metadataUserId !== (string) $topUp->user_id) {
            throw new DomainException('Payment user mismatch.');
        }

        if ($recipientId !== null && (string) $recipientId !== (string) config('yookassa.shop_id')) {
            throw new DomainException('Payment recipient mismatch.');
        }
    }

    private function formatAmount(int $kopecks): string
    {
        return number_format($kopecks / 100, 2, '.', '');
    }
}
