<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * コメントの保存処理
     */
    public function store(Request $request, $item_id)
    {
        // 1. バリデーション
        $request->validate([
            'comment' => 'required|max:255',
        ]);

        // 2. データの保存
        Comment::create([
            'user_id' => Auth::id(),
            'item_id' => $item_id,
            'comment' => $request->comment,
        ]);

        // 3. 元の画面に戻る（コメントが即時反映されます）
        return back()->with('message', 'コメントを投稿しました');
    }
}