<?php

namespace Tests\Feature\Item;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Item;

class ItemSearchTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 商品名で検索ができるか
     */
    public function test_can_search_items_by_name()
    {
        Item::factory()->create(['name' => '検索ヒット商品']);
        Item::factory()->create(['name' => '対象外']);

        $response = $this->get('/?keyword=ヒット');

        $response->assertStatus(200);
        $response->assertSee('検索ヒット商品');
        $response->assertDontSee('対象外');
    }

    /**
     * ブランド名で検索ができるか
     */
    public function test_can_search_items_by_brand()
    {
        Item::factory()->create(['name' => '商品A', 'brand' => 'ブランドXYZ']);
        Item::factory()->create(['name' => '商品B', 'brand' => 'その他']);

        $response = $this->get('/?keyword=XYZ');

        $response->assertSee('商品A');
        $response->assertDontSee('商品B');
    }
}