<?php

namespace Tests\Feature\Item;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Favorite;

class MyListTest extends TestCase
{
    use RefreshDatabase;

    /**
     * いいね！した商品が表示されるか
     */
    public function test_favorited_items_are_displayed_in_mylist()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create(['name' => 'お気に入り商品']);

        Favorite::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $this->actingAs($user);
        $response = $this->get('/?tab=fav');

        $response->assertStatus(200);
        $response->assertSee('お気に入り商品');
    }

    /**
     * いいね！していない商品は表示されないか
     */
    public function test_not_favorited_items_are_not_displayed_in_mylist()
    {
        $user = User::factory()->create();
        Item::factory()->create(['name' => '普通の商品']);

        $this->actingAs($user);
        $response = $this->get('/?tab=fav');

        $response->assertDontSee('普通の商品');
    }

    /**
     * 未ログイン状態でマイリストにアクセスした場合、全商品が表示される（現在のコントローラーの仕様）
     */
    public function test_guest_sees_all_items_in_mylist_tab()
    {
        Item::factory()->create(['name' => '誰かの商品']);

        // 未ログイン状態でマイリストタブへ
        $response = $this->get('/?tab=fav');

        // コントローラーの if($tab === 'fav' && $user) を通らないため、表示されるのが正解
        $response->assertSee('誰かの商品');
    }
}