<?php

namespace Tests\Feature\Item;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\User;
use App\Models\Category;

class ItemSellTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 商品出品画面にて必要な情報を入力し、出品ができるか
     */
    public function test_user_can_sell_item()
    {
        // ストレージを偽装
        Storage::fake('public');

        $user = User::factory()->create(['email_verified_at' => now()]);
        $category = Category::create(['name' => 'テストカテゴリ']);

        $this->actingAs($user);

        // 【修正】image()関数を使わず、ダミーのファイルを作成してアップロード
        // これにより、GDライブラリがなくてもエラーを回避できます
        $dummyImage = UploadedFile::fake()->create('test_item.jpg', 100, 'image/jpeg');

        // 出品リクエストの送信
        $response = $this->post('/sell', [
            'name'        => '出品テスト商品',
            'description' => 'テスト用の商品説明文です。',
            'price'       => 3000,
            'condition'   => '良好',
            'categories'  => [$category->id],
            'img_url'     => $dummyImage,
        ]);

        // 1. リダイレクトの確認
        $response->assertRedirect('/');

        // 2. データベースの確認
        $this->assertDatabaseHas('items', [
            'name'  => '出品テスト商品',
            'price' => 3000,
        ]);

        // 3. 一覧画面の確認
        $response = $this->get('/');
        $response->assertSee('出品テスト商品');
    }
}