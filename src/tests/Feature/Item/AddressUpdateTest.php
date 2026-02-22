<?php

namespace Tests\Feature\Item;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;

class AddressUpdateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 送付先住所変更画面にて住所変更ができるか
     */
    public function test_user_can_update_shipping_address()
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $item = Item::factory()->create();

        $this->actingAs($user);

        // 1. 新しい住所データを送信（web.phpのaddress.updateに対応）
        $response = $this->post("/purchase/address/{$item->id}", [
            'postal_code' => '123-4567',
            'address'     => '宮崎県都城市1-2-3',
            'building'    => 'テックビル101',
        ]);

        // 2. 購入画面へリダイレクトされるか確認
        $response->assertRedirect("/purchase/{$item->id}");

        // 3. 購入画面を再度開き、新しい住所が表示されているか確認
        $response = $this->get("/purchase/{$item->id}");
        $response->assertSee('123-4567');
        $response->assertSee('宮崎県都城市1-2-3');
        $response->assertSee('テックビル101');
    }
}