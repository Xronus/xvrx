<?php

namespace Tests\Feature\Admin;

use App\Models\ShopItemType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShopItemTypeTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create();
        $this->admin->forceFill([
            'is_admin' => true,
            'totp_secret' => base64_encode('test-secret'),
            'totp_confirmed_at' => now(),
        ])->save();

        $this->actingAs($this->admin)
            ->withSession(['admin_2fa_verified_at' => now()]);
    }

    public function test_create_form_uses_supported_type_options(): void
    {
        $response = $this->get(route('admin.shop-item-types.create'));

        $response->assertOk();
        $response->assertSee('name="name_ru"', false);
        $response->assertSee('value="items"', false);
        $response->assertSee('value="money"', false);
        $response->assertSee('Предметы');
        $response->assertSee('Золото');
    }

    public function test_supported_type_can_be_created(): void
    {
        $response = $this->post(route('admin.shop-item-types.store'), [
            'name_ru' => ShopItemType::ITEMS,
        ]);

        $response->assertRedirect(route('admin.shop-item-types.index'));
        $this->assertDatabaseHas('shop_item_types', ['name_ru' => ShopItemType::ITEMS]);
    }

    public function test_custom_type_is_rejected_with_clear_message(): void
    {
        $response = $this->from(route('admin.shop-item-types.create'))
            ->post(route('admin.shop-item-types.store'), [
                'name_ru' => 'Маунты',
            ]);

        $response->assertRedirect(route('admin.shop-item-types.create'));
        $response->assertSessionHasErrors([
            'name_ru' => __('main.shop_item_type_invalid'),
        ]);
        $this->assertDatabaseCount('shop_item_types', 0);
    }

    public function test_legacy_type_value_is_selected_as_its_canonical_option(): void
    {
        $type = ShopItemType::create(['name_ru' => 'золото']);

        $response = $this->get(route('admin.shop-item-types.edit', $type));

        $response->assertOk();
        $response->assertSee('value="money" selected', false);
    }
}
