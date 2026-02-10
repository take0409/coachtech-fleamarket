<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\CommentController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| 1. メール認証関連（未認証ユーザーの誘導先）
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    // 認証誘導画面の表示
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    // 認証リンクの検証
    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect()->route('item.index')->with('message', 'メール認証が完了しました');
    })->middleware(['signed'])->name('verification.verify');

    // 認証メールの再送
    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('status', 'verification-link-sent');
    })->middleware(['throttle:6,1'])->name('verification.send');
});

/*
|--------------------------------------------------------------------------
| 2. 公開ページ（誰でも閲覧可能）
|--------------------------------------------------------------------------
*/
Route::get('/', [ItemController::class, 'index'])->name('item.index');
Route::get('/item/{item_id}', [ItemController::class, 'show'])->name('item.show');

/*
|--------------------------------------------------------------------------
| 3. 要ログイン（メール認証前でも全ての機能が利用可能）
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    
    // マイページ関連
    Route::get('/mypage', [ProfileController::class, 'index'])->name('mypage.index');
    Route::get('/mypage/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/mypage/profile', [ProfileController::class, 'update'])->name('profile.update');
    
    // 商品出品関連
    Route::get('/sell', [ItemController::class, 'create'])->name('item.create');
    Route::post('/sell', [ItemController::class, 'store'])->name('item.store');

    // 商品購入関連（メール認証前でも購入できるよう移動）
    Route::get('/purchase/{item_id}', [PurchaseController::class, 'show'])->name('item.purchase.show');
    Route::post('/purchase/{item_id}', [PurchaseController::class, 'store'])->name('item.purchase.store');
    
    // 配送先変更
    Route::get('/purchase/address/{item_id}', [PurchaseController::class, 'editAddress'])->name('address.edit');
    Route::post('/purchase/address/{item_id}', [PurchaseController::class, 'updateAddress'])->name('address.update');

    // お気に入り・コメント
    Route::post('/item/{item_id}/favorite', [FavoriteController::class, 'store'])->name('favorite.store');
    Route::delete('/item/{item_id}/favorite', [FavoriteController::class, 'destroy'])->name('favorite.destroy');
    Route::post('/item/{item_id}/comment', [CommentController::class, 'store'])->name('comment.store');
});