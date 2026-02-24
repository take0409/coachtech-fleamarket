<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use App\Http\Requests\ExposeRequest; // 作成したバリデーションクラスをインポート
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    /**
     * 商品一覧を表示（おすすめ・マイリスト切り替え ＆ 検索対応）
     */
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');
        $tab = $request->query('tab', 'all');
        $user = Auth::user();

        $query = Item::with(['orderItems', 'favorites']);

        // マイリストタブの時、自分がお気に入りした商品のみ表示
        if ($tab === 'fav' && $user) {
            $query->whereHas('favorites', function($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        // キーワード検索（商品名 または ブランド名）
        if (!empty($keyword)) {
            $query->where(function($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('brand', 'like', "%{$keyword}%");
            });
        }

        $items = $query->get();
        return view('item_index', compact('items', 'tab'));
    }

    public function show($item_id)
    {
        $item = Item::with(['categories', 'favorites', 'comments.user.profile'])->findOrFail($item_id);
        $user = Auth::user();
        return view('item_detail', compact('item', 'user'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('item_sell', compact('categories'));
    }

    /**
     * 商品出品（ExposeRequestを使用してバリデーションを適用）
     */
    public function store(ExposeRequest $request)
    {
        // $request->validate() の記述は不要になりました。
        // ExposeRequest が自動でバリデーションを行い、失敗時はエラーメッセージと共に元の画面に戻ります。

        $imgUrl = 'img/default.jpg'; 
        if ($request->hasFile('img_url')) {
            $file = $request->file('img_url');
            // storage/app/public/items に保存
            $path = $file->store('items', 'public');
            // DBには storage/items/... として保存
            $imgUrl = 'storage/' . $path;
        }

        $item = Item::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'condition' => $request->condition,
            'brand' => $request->brand,
            'img_url' => $imgUrl,
        ]);

        // カテゴリの紐付け（複数対応）
        $item->categories()->sync($request->categories);

        return redirect()->route('item.index')->with('message', '出品が完了しました');
    }
}