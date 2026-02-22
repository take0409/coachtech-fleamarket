<?php

namespace Tests\Feature\Profile;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Profile; // Profileモデルがある前提です

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    /**
     * プロフィール設定画面に初期値が表示されているか
     */
    public function test_profile_page_displays_initial_values()
    {
        $user = User::factory()->create(['email_verified_at' => now(), 'name' => 'テスト太郎']);
        
        // ユーザーに紐づくプロフィールを作成（テーブル名やカラム名は一般的によくある形式にしています）
        // もしProfileモデルがない、またはUserテーブルに直接持っている場合は修正が必要です
        $user->profile()->create([
            'postal_code' => '885-0000',
            'address'     => '宮崎県都城市',
            'building'    => 'テックマンション',
            'img_url'     => 'img/default-user.jpg',
        ]);

        $this->actingAs($user);

        // プロフィール編集画面へアクセス
        $response = $this->get('/mypage/profile');

        $response->assertStatus(200);
        $response->assertSee('テスト太郎');
        $response->assertSee('885-0000');
        $response->assertSee('宮崎県都城市');
        $response->assertSee('テックマンション');
    }
}