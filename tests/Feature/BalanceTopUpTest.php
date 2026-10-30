<?php

namespace Tests\Feature;

use App\Models\BalanceTopUp;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Tests\TestCase;

class BalanceTopUpTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config()->set('yookassa.shop_id', 'test-shop-id');
        config()->set('yookassa.secret_key', 'test-secret-key');
        config()->set('yookassa.bonus_per_ruble', 2);
        config()->set('yookassa.min_amount', 10);
        config()->set('yookassa.max_amount', 100000);
        config()->set('yookassa.allow_test_payments', true);
    }

    public function test_user_can_start_redirect_payment(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);

        Http::fake(function ($request) {
            return Http::response([
                'id' => 'payment-1',
                'status' => 'pending',
                'paid' => false,
                'test' => true,
                'amount' => ['value' => '500.00', 'currency' => 'RUB'],
                'recipient' => ['account_id' => 'test-shop-id'],
                'confirmation' => [
                    'type' => 'redirect',
                    'confirmation_url' => 'https://yookassa.example/confirm/payment-1',
                ],
                'metadata' => $request['metadata'],
            ]);
        });

        $response = $this->actingAs($user)->post(route('balance.topup.store'), [
            'amount' => 500,
        ]);

        $topUp = BalanceTopUp::firstOrFail();
        $response->assertRedirect('https://yookassa.example/confirm/payment-1');
        $this->assertSame(50000, $topUp->amount_kopecks);
        $this->assertSame(1000, $topUp->bonus_amount);
        $this->assertSame(BalanceTopUp::STATUS_PENDING, $topUp->status);

        Http::assertSent(function ($request) use ($topUp, $user) {
            return $request->url() === 'https://api.yookassa.ru/v3/payments'
                && $request->hasHeader('Idempotence-Key', $topUp->idempotence_key)
                && $request['capture'] === true
                && $request['metadata']['top_up_id'] === $topUp->public_id
                && $request['metadata']['user_id'] === (string) $user->id;
        });
    }

    public function test_configured_top_up_page_shows_payment_form(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);

        $this->actingAs($user)
            ->get(route('balance.topup'))
            ->assertOk()
            ->assertSee('id="topup-form"', false)
            ->assertSee(__('main.top_up_continue'));
    }

    public function test_connection_failure_shows_provider_unavailable_message(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);

        Http::fake(Http::failedConnection('SSL connection timeout'));

        $response = $this->actingAs($user)
            ->from(route('balance.topup'))
            ->post(route('balance.topup.store'), ['amount' => 500]);

        $response->assertRedirect(route('balance.topup'))
            ->assertSessionHas('error', __('main.top_up_provider_unavailable'));

        $this->assertSame(BalanceTopUp::STATUS_FAILED, BalanceTopUp::firstOrFail()->status);
    }

    public function test_verified_webhook_credits_balance_only_once(): void
    {
        $user = User::factory()->create([
            'bonuses' => 100,
            'email_verified_at' => now(),
        ]);
        $topUp = $this->pendingTopUp($user, 50000, 500);
        $providerPayment = $this->succeededPayment($topUp);

        Http::fake([
            'https://api.yookassa.ru/v3/payments/payment-1' => Http::response($providerPayment),
        ]);

        $notification = [
            'type' => 'notification',
            'event' => 'payment.succeeded',
            'object' => $providerPayment,
        ];

        $this->postJson(route('payments.yookassa.webhook'), $notification)->assertOk();
        $this->postJson(route('payments.yookassa.webhook'), $notification)->assertOk();

        $this->assertSame(600, $user->fresh()->bonuses);
        $this->assertNotNull($topUp->fresh()->credited_at);
        $this->assertSame(BalanceTopUp::STATUS_SUCCEEDED, $topUp->fresh()->status);
    }

    public function test_webhook_rejects_amount_mismatch(): void
    {
        $user = User::factory()->create(['bonuses' => 100]);
        $topUp = $this->pendingTopUp($user, 50000, 500);
        $providerPayment = $this->succeededPayment($topUp);
        $providerPayment['amount']['value'] = '1.00';

        Http::fake([
            'https://api.yookassa.ru/v3/payments/payment-1' => Http::response($providerPayment),
        ]);

        $this->postJson(route('payments.yookassa.webhook'), [
            'type' => 'notification',
            'event' => 'payment.succeeded',
            'object' => $providerPayment,
        ])->assertStatus(422);

        $this->assertSame(100, $user->fresh()->bonuses);
        $this->assertNull($topUp->fresh()->credited_at);
    }

    public function test_test_payment_is_rejected_when_disabled(): void
    {
        config()->set('yookassa.allow_test_payments', false);

        $user = User::factory()->create(['bonuses' => 100]);
        $topUp = $this->pendingTopUp($user, 50000, 500);
        $providerPayment = $this->succeededPayment($topUp);

        Http::fake([
            'https://api.yookassa.ru/v3/payments/payment-1' => Http::response($providerPayment),
        ]);

        $this->postJson(route('payments.yookassa.webhook'), [
            'type' => 'notification',
            'event' => 'payment.succeeded',
            'object' => $providerPayment,
        ])->assertStatus(422);

        $this->assertSame(100, $user->fresh()->bonuses);
    }

    private function pendingTopUp(User $user, int $kopecks, int $bonuses): BalanceTopUp
    {
        return BalanceTopUp::create([
            'public_id' => (string) Str::uuid(),
            'user_id' => $user->id,
            'provider' => 'yookassa',
            'provider_payment_id' => 'payment-1',
            'idempotence_key' => (string) Str::uuid(),
            'amount_kopecks' => $kopecks,
            'currency' => 'RUB',
            'bonus_amount' => $bonuses,
            'status' => BalanceTopUp::STATUS_PENDING,
            'is_test' => true,
        ]);
    }

    private function succeededPayment(BalanceTopUp $topUp): array
    {
        return [
            'id' => 'payment-1',
            'status' => 'succeeded',
            'paid' => true,
            'test' => true,
            'amount' => [
                'value' => number_format($topUp->amount_kopecks / 100, 2, '.', ''),
                'currency' => 'RUB',
            ],
            'recipient' => ['account_id' => 'test-shop-id'],
            'metadata' => [
                'top_up_id' => $topUp->public_id,
                'user_id' => (string) $topUp->user_id,
            ],
            'created_at' => now()->toIso8601String(),
            'captured_at' => now()->toIso8601String(),
        ];
    }
}
