<?php

namespace Tests\Feature\Item;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Category;

class ItemDetailTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 商品詳細ページに必要な情報が表示されているか
     */
    public function test_item_detail_page_displays_all_information()
    {
        // 1. カテゴリの作成 (nameカラムを使用)
        $category = Category::create(['name' => 'ファッション']);

        // 2. 商品の作成
        $item = Item::factory()->create([
            'name' => '詳細テスト商品',
            'brand' => 'テストブランド',
            'price' => 5000,
            'description' => 'これは商品の詳細説明です。',
            'condition' => '良好',
        ]);

        // カテゴリを紐付け
        $item->categories()->attach($category->id);

        // 3. 詳細ページにアクセス
        $response = $this->get("/item/{$item->id}");

        // 4. 各情報が表示されているか確認
        $response->assertStatus(200);
        $response->assertSee('詳細テスト商品');
        $response->assertSee('テストブランド');
        $response->assertSee('5,000'); // カンマ区切りの可能性
        $response->assertSee('これは商品の詳細説明です。');
        $response->assertSee('良好');
        $response->assertSee('ファッション');
    }

    /**
     * 複数カテゴリがある場合、全て表示されているか
     */
    public function test_item_detail_page_displays_multiple_categories()
    {
        // nameカラムを使用して作成
        $category1 = Category::create(['name' => 'カテゴリ1']);
        $category2 = Category::create(['name' => 'カテゴリ2']);
        
        $item = Item::factory()->create();
        $item->categories()->attach([$category1->id, $category2->id]);

        $response = $this->get("/item/{$item->id}");

        $response->assertSee('カテゴリ1');
        $response->assertSee('カテゴリ2');
    }
}