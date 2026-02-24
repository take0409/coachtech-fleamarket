<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest; // 修正：作成したProfileRequestをインポート
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
            $query->whereHas('order_items', function ($q) use ($user) {
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
        // Ensure we have an Eloquent User model instance so ->save() is available
        $user = User::findOrFail(Auth::id());

        // バリデーションは ProfileRequest が自動で行うため、
        // ここに記述されていた $request->validate([...]) は削除しました。

        // Assign and save the name to ensure compatibility with different Auth user types
        $user->name = $request->name;
        $user->save();

        $profileData = [
            'post_code' => $request->post_code, // Requestに合わせてpostal_codeからpost_codeに変更
            'address' => $request->address,
            'building' => $request->building,
        ];
        if ($request->hasFile('img_url')) {
            $file = $request->file('img_url');
            $path = $file->store('profiles', 'public');
            $profileData['img_url'] = 'storage/' . $path;
        }

        Profile::updateOrCreate(['user_id' => $user->id], $profileData);

        return redirect()->route('mypage.index')->with('message', 'プロフィールを更新しました');
    }
}