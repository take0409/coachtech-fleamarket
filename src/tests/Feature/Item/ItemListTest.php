<?php

namespace Tests\Feature\Item;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\OrderItem;

class ItemListTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 全商品が取得できるか
     */
    public function test_all_items_are_displayed()
    {
        $item = Item::factory()->create(['name' => 'テスト商品A']);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('テスト商品A');
    }

    /**
     * 購入済み商品は「SOLD」と表示されるか
     * (仕様：ログイン中のユーザーが購入した商品のみSOLD表示)
     */
    public function test_purchased_items_display_sold_label()
    {
        $seller = User::factory()->create();
        $buyer = User::factory()->create();
        
        $item = Item::factory()->create([
            'name' => '売り切れ商品',
            'user_id' => $seller->id,
        ]);

        // 購入データを作成
        OrderItem::create([
            'user_id' => $buyer->id,
            'item_id' => $item->id,
        ]);

        // 【重要】購入者としてログインする（isSoldの仕様に合わせる）
        $this->actingAs($buyer);

        $response = $this->get('/');

        // ビューにある大文字の SOLD を確認
        $response->assertStatus(200);
        $response->assertSee('SOLD');
    }

    /**
     * 自分が出品した商品も表示されるか
     */
    public function test_own_items_are_displayed()
    {
        $me = User::factory()->create();
        $this->actingAs($me);

        $my_item = Item::factory()->create([
            'name' => '自分の商品',
            'user_id' => $me->id,
        ]);

        $response = $this->get('/');

        $response->assertSee('自分の商品');
    }
}