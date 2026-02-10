<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * マイページ：ログインユーザー本人だけのデータを抽出し、検索にも対応
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $tab = $request->query('tab', 'sell');
        $keyword = $request->query('keyword');

        $query = Item::query();

        // タブに応じた絞り込み
        if ($tab === 'buy') {
            $query->whereHas('orderItems', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        } elseif ($tab === 'fav') {
            $query->whereHas('favorites', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        } else {
            $query->where('user_id', $user->id);
        }

        // キーワード検索
        if (!empty($keyword)) {
            $query->where('name', 'LIKE', "%{$keyword}%");
        }

        $items = $query->get();

        return view('mypage', compact('user', 'items', 'tab'));
    }

    /**
     * プロフィール編集画面の表示
     */
    public function edit()
    {
        $user = Auth::user();
        return view('profile_edit', compact('user'));
    }

    /**
     * プロフィール情報の更新（バリデーションの完全日本語化）
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // バリデーションの実行
        $request->validate([
            'name' => 'required|string|max:255',
            'img_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'postal_code' => 'nullable|string|max:8',
            'address' => 'required|string|max:255',
        ], [
            // カスタムエラーメッセージの設定
            'required' => ':attributeを入力してください',
            'image' => ':attributeには画像ファイルを指定してください',
            'mimes' => ':attributeには :values 形式の画像をアップロードしてください',
            'max' => ':attributeは :max KB以内のものを指定してください',
        ], [
            // 項目名（属性名）の日本語定義
            'name' => 'ユーザー名',
            'img_url' => 'プロフィール画像',
            'postal_code' => '郵便番号',
            'address' => '住所',
        ]);

        $user->update(['name' => $request->name]);

        $profileData = [
            'postal_code' => $request->postal_code,
            'address' => $request->address,
            'building' => $request->building,
        ];

        if ($request->hasFile('img_url')) {
            $file = $request->file('img_url');
            $path = $file->store('profiles', 'public');
            $profileData['img_url'] = 'storage/' . $path;
        }

        $user->profile()->updateOrCreate(['user_id' => $user->id], $profileData);

        return redirect()->route('mypage.index')->with('message', 'プロフィールを更新しました');
    }
}