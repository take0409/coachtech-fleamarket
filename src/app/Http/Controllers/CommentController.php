<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Http\Requests\CommentRequest; // 作成したFormRequestをインポート
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * コメントの保存処理
     * 第1引数を CommentRequest に変更することで、自動的にバリデーションが走ります。
     */
    public function store(CommentRequest $request, $item_id)
    {
        // $request->validate() の記述は不要になりました。

        // データの保存（カラム名が 'comment' か 'content' かはDBに合わせてください。Request側がcontentならここもcontentにします）
        Comment::create([
            'user_id' => Auth::id(),
            'item_id' => $item_id,
            'content' => $request->content, 
        ]);

        // 元の画面に戻る
        return back()->with('message', 'コメントを投稿しました');
    }
}