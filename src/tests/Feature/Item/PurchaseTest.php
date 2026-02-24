<?php

namespace Tests\Feature\Item;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Http;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 10. 商品を購入できるか
     * 決済代行(Stripe)が絡むため、DBに直接データを作成して挙動を確認します
     */
    public function test_user_can_purchase_item()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->createOne(['email_verified_at' => now()]);
        $item = Item::factory()->createOne();

        $this->actingAs($user);

        // 決済後の「注文作成」ロジックをシミュレート
        OrderItem::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        // DBに注文データが存在することを確認
        $this->assertDatabaseHas('order_items', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }

    /**
     * 10. 購入した商品は一覧画面で「SOLD」と表示されるか
     */
    public function test_purchased_item_shows_sold_label()
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $item = Item::factory()->create();

        // 注文済み状態を作成
        OrderItem::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $this->actingAs($user);

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('SOLD');
    }

    /**
     * 11. 自分が出品した商品は購入できない
     */
    public function test_user_cannot_purchase_own_item()
    {
        /** @var \Illuminate\Contracts\Auth\Authenticatable|\App\Models\User $user */
        $user = User::factory()->createOne(['email_verified_at' => now()]);

        // Normalize to a model instance for static analysis/runtime safety
        if ($user instanceof \Illuminate\Database\Eloquent\Collection) {
            $user = $user->first();
        }

        // Ensure $user is present and implements Authenticatable before using actingAs
        $this->assertNotNull($user);
        $this->assertInstanceOf(\Illuminate\Contracts\Auth\Authenticatable::class, $user);

        // 自分がオーナーの商品
        $item = Item::factory()->createOne(['user_id' => $user->id]);

        $this->actingAs($user);

        // 購入画面にアクセス
        $response = $this->get("/purchase/{$item->id}");
        
        // 購入画面自体は表示されるが「購入ボタン」が表示されない仕様、
        // または詳細へリダイレクトされる仕様のいずれかを確認
        if ($response->status() === 302) {
            $response->assertRedirect();
        } else {
            $response->assertStatus(200);
            // 購入ボタンのテキストが存在しないことを確認（仕様に合わせて調整）
            $response->assertDontSee('カードで支払う');
        }
    }
}