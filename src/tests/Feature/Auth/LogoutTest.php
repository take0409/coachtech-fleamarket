<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    /**
     * ログアウトができるか
     */
    public function test_user_can_logout()
    {
        // 1. 事前にユーザーを作成し、ログイン状態にする
        $user = User::factory()->create();
        $this->actingAs($user);

        // 念のため、現在ログイン状態であることを確認
        $this->assertAuthenticatedAs($user);

        // 2. ログアウト処理を実行する（LaravelのデフォルトはPOSTメソッドで /logout）
        $response = $this->post('/logout');

        // 3. ログアウト状態（Guest）になっているか確認
        $this->assertGuest();

        // 4. トップページ（ / ）へリダイレクトされるか確認
        // ※もしログアウト後の遷移先が違う場合は、ここの '/' を変更してください
        $response->assertRedirect('/');
    }
}