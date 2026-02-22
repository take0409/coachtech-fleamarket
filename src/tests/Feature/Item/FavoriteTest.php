<?php

namespace Tests\Feature\Item;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Favorite;

class FavoriteTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 商品をお気に入り登録できるか
     */
    public function test_user_can_favorite_item()
    {
        // メール認証済みのユーザーを作成する
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);
        $item = Item::factory()->create();

        $this->actingAs($user);

        // POSTメソッドで登録を実行
        $response = $this->post("/item/{$item->id}/favorite");

        // データベースに保存されているか確認
        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }

    /**
     * 商品のお気に入り解除ができるか
     */
    public function test_user_can_unfavorite_item()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);
        $item = Item::factory()->create();

        // 最初にお気に入り状態を作っておく
        Favorite::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $this->actingAs($user);

        // web.phpの定義に合わせて DELETE メソッドで実行
        $response = $this->delete("/item/{$item->id}/favorite");

        // データベースから消えているか確認
        $this->assertDatabaseMissing('favorites', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }
}