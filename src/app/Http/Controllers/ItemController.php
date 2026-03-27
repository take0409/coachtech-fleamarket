<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use App\Http\Requests\ItemRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    /**
     * 商品一覧を表示
     */
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');
        $tab = $request->query('tab', 'all');
        $user = Auth::user();

        // orderItemsを読み込んでSOLD判定をスムーズにする
        $query = Item::with(['orderItems', 'favorites']);

        // 【元に戻したポイント】自分が出品した商品も「おすすめ」に表示されるようにしました

        // マイリストタブの時、自分がお気に入りした商品のみ表示
        if ($tab === 'fav' && $user) {
            $query->whereHas('favorites', function($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        // キーワード検索
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

    public function store(ItemRequest $request)
    {
        $imgUrl = 'img/default.jpg'; 
        if ($request->hasFile('img_url')) {
            $file = $request->file('img_url');
            $path = $file->store('items', 'public');
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

        $item->categories()->sync($request->categories);

        return redirect()->route('item.index')->with('message', '出品が完了しました');
    }
}
