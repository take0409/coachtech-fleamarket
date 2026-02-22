<?php

namespace Tests\Feature\Item;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\OrderItem;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 商品を購入できるか
     */
    public function test_user_can_purchase_item()
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $item = Item::factory()->create();

        $this->actingAs($user);

        // 購入処理を実行
        $response = $this->post("/purchase/{$item->id}", [
            'payment_method' => 'card',
        ]);

        $this->assertDatabaseHas('order_items', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }

    /**
     * 購入した商品は一覧画面で「SOLD」と表示されるか
     */
    public function test_purchased_item_shows_sold_label()
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $item = Item::factory()->create(['name' => 'テスト売却商品']);

        // 1. 購入者としてログイン
        $this->actingAs($user);

        // 2. 購入処理を実行
        $this->post("/purchase/{$item->id}", [
            'payment_method' => 'card',
        ]);

        // 3. そのままのログイン状態でトップページへ
        $response = $this->get('/');
        
        // 4. 購入した本人であれば「SOLD」が見えるはず
        $response->assertStatus(200);
        $response->assertSee('SOLD');
    }
}