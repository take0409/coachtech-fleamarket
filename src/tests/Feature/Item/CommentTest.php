<?php

namespace Tests\Feature\Item;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Comment;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    /**
     * ログイン済みのユーザーはコメントを送信できる
     */
    public function test_authenticated_user_can_send_comment()
    {
        // メール認証済みのユーザーを作成
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);
        $item = Item::factory()->create();

        $this->actingAs($user);

        // コメント送信の実行
        $response = $this->post("/item/{$item->id}/comment", [
            'comment' => 'これはテストコメントです。',
        ]);

        // データベースにコメントが保存されているか確認
        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'comment' => 'これはテストコメントです。',
        ]);
    }

    /**
     * ログインしていないユーザーはコメントを送信できない
     */
    public function test_guest_cannot_send_comment()
    {
        $item = Item::factory()->create();

        // ログインせずにコメントを送信
        $response = $this->post("/item/{$item->id}/comment", [
            'comment' => 'ゲストのコメント',
        ]);

        // ログイン画面へリダイレクトされることを確認
        $response->assertRedirect('/login');
        
        // データベースに保存されていないことを確認
        $this->assertDatabaseMissing('comments', [
            'comment' => 'ゲストのコメント',
        ]);
    }
}