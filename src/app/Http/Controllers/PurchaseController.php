<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\User;

class PurchaseController extends Controller
{
    /**
     * 購入画面を表示
     */
    public function show($item_id)
    {
        $user = Auth::user();
        $item = Item::findOrFail($item_id);

        // 配送先変更画面から戻ってきた際の住所をセッションから取得、なければDBから取得
        $address = session('new_address', [
            'postal_code' => $user->profile->postal_code ?? '',
            'address'     => $user->profile->address ?? '',
            'building'    => $user->profile->building ?? '',
        ]);

        // resources/views/purchase/purchase.blade.php を読み込む
        return view('purchase.purchase', compact('user', 'item', 'address'));
    }

    /**
     * 住所変更画面の表示
     */
    public function editAddress($item_id)
    {
        // 住所変更用ビューを表示
        return view('address_edit', ['item_id' => $item_id]);
    }

    /**
     * 住所の更新（購入確定まで一時的にセッションへ保存）
     */
    public function updateAddress(Request $request, $item_id)
    {
        $request->validate([
            'postal_code' => 'required|string|max:8',
            'address'     => 'required|string|max:255',
        ]);

        // 入力内容をセッションに保存
        session(['new_address' => $request->only(['postal_code', 'address', 'building'])]);

        return redirect()->route('item.purchase.show', ['item_id' => $item_id]);
    }

    /**
     * 購入確定処理（注文の保存とSOLD化）
     */
    public function store(Request $request, $item_id)
    {
        $user = Auth::user();
        $item = Item::findOrFail($item_id);

        $request->validate([
            'payment_method' => 'required',
        ]);

        // 注文データをDBに保存（これで商品がSOLD状態になります）
        $item->orderItems()->create([
            'user_id' => $user->id,
            'payment_method' => $request->payment_method,
            'postal_code' => session('new_address.postal_code', $user->profile->postal_code ?? ''),
            'address' => session('new_address.address', $user->profile->address ?? ''),
            'building' => session('new_address.building', $user->profile->building ?? ''),
        ]);

        // 完了後にセッション住所をクリア
        session()->forget('new_address');

        return redirect()->route('mypage.index')->with('message', '購入が完了しました');
    }
}