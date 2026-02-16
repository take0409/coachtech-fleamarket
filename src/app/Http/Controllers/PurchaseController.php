<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class PurchaseController extends Controller
{
    public function show($item_id)
    {
        $user = Auth::user();
        $item = Item::findOrFail($item_id);

        $address = session('new_address', [
            'postal_code' => $user->profile->postal_code ?? '',
            'address'     => $user->profile->address ?? '',
            'building'    => $user->profile->building ?? '',
        ]);

        return view('purchase.purchase', compact('user', 'item', 'address'));
    }

    public function editAddress($item_id)
    {
        return view('address_edit', ['item_id' => $item_id]);
    }

    public function updateAddress(Request $request, $item_id)
    {
        $request->validate([
            'postal_code' => 'required|string|max:8',
            'address'     => 'required|string|max:255',
        ]);

        session(['new_address' => $request->only(['postal_code', 'address', 'building'])]);

        return redirect()->route('item.purchase.show', ['item_id' => $item_id]);
    }

    /**
     * 購入確定処理（Stripe決済画面へ接続：FN023）
     */
    public function store(Request $request, $item_id)
    {
        $user = Auth::user();
        $item = Item::findOrFail($item_id);

        $request->validate([
            'payment_method' => 'required',
        ]);

        // Stripe秘密鍵の設定
        Stripe::setApiKey(config('services.stripe.secret'));

        // 支払い方法の判定（カードかコンビニか）
        $method = ($request->payment_method === 'コンビニ払い') ? 'konbini' : 'card';

        // Stripeチェックアウトセッションの作成
        $session = Session::create([
            'payment_method_types' => [$method],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => ['name' => $item->name],
                    'unit_amount' => $item->price,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('mypage.index', ['success' => 1]),
            'cancel_url' => route('item.purchase.show', ['item_id' => $item->id]),
        ]);

        // 注文データをDBに保存（Stripe遷移直前に保存してSOLD化）
        $item->orderItems()->create([
            'user_id' => $user->id,
            'payment_method' => $request->payment_method,
            'postal_code' => session('new_address.postal_code', $user->profile->postal_code ?? ''),
            'address' => session('new_address.address', $user->profile->address ?? ''),
            'building' => session('new_address.building', $user->profile->building ?? ''),
        ]);

        session()->forget('new_address');

        // Stripeの決済ページへリダイレクト
        return redirect($session->url, 303);
    }
}