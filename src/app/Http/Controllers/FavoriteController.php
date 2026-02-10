<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    /**
     * お気に入り登録処理
     */
    public function store($item_id)
    {
        // ログインしていない場合は処理しない（Route側でもガードしますが念のため）
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // firstOrCreateを使うことで、万が一の連打による二重登録（エラー）を完璧に防ぎます
        Favorite::firstOrCreate([
            'user_id' => Auth::id(),
            'item_id' => $item_id,
        ]);

        // 元の商品詳細画面に戻ります
        return back();
    }

    /**
     * お気に入り解除処理
     */
    public function destroy($item_id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // 自分の「いいね」だけを特定して削除します
        Favorite::where('user_id', Auth::id())
            ->where('item_id', $item_id)
            ->delete();

        return back();
    }
}