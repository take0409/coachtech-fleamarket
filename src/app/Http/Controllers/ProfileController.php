<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\User;
use App\Models\Profile;

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
            // エラー箇所修正：order_items -> orderItems
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
     * プロフィール情報の更新（ProfileRequestを使用してバリデーションを適用）
     */
    public function update(ProfileRequest $request)
    {
        $user = User::findOrFail(Auth::id());

        // ユーザー名の更新
        $user->name = $request->name;
        $user->save();

        // 基本データの準備
        $profileData = [
            'post_code' => $request->post_code,
            'address'   => $request->address,
            'building'  => $request->building,
        ];

        // プロフィール画像の保存処理
        if ($request->hasFile('img_url')) {
            $file = $request->file('img_url');
            $path = $file->store('profiles', 'public');
            $profileData['img_url'] = 'storage/' . $path;
        }

        // プロフィールの更新または作成
        Profile::updateOrCreate(['user_id' => $user->id], $profileData);

        return redirect()->route('mypage.index')->with('message', 'プロフィールを更新しました');
    }
}