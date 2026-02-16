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
| 1. 公開ページ（未ログインでも閲覧可能）
|--------------------------------------------------------------------------
*/
Route::get('/', [ItemController::class, 'index'])->name('item.index');
Route::get('/item/{item_id}', [ItemController::class, 'show'])->name('item.show');

/*
|--------------------------------------------------------------------------
| 2. メール認証関連（Laravel Fortify & 指示要件）
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    // メール認証誘導画面
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    // メール認証処理
    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect()->route('profile.edit')->with('message', 'メール認証が完了しました。プロフィールを設定してください。');
    })->middleware(['signed'])->name('verification.verify');

    // 認証メール再送
    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('status', 'verification-link-sent');
    })->middleware(['throttle:6,1'])->name('verification.send');
});

/*
|--------------------------------------------------------------------------
| 3. 要メール認証（認証完了まで利用不可の機能：FN012要件）
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    
    // マイページ関連
    Route::get('/mypage', [ProfileController::class, 'index'])->name('mypage.index');
    Route::get('/mypage/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/mypage/profile', [ProfileController::class, 'update'])->name('profile.update');
    
    // 出品関連
    Route::get('/sell', [ItemController::class, 'create'])->name('item.create');
    Route::post('/sell', [ItemController::class, 'store'])->name('item.store');

    // 購入関連（Stripe決済含む）
    Route::get('/purchase/{item_id}', [PurchaseController::class, 'show'])->name('item.purchase.show');
    Route::post('/purchase/{item_id}', [PurchaseController::class, 'store'])->name('item.purchase.store');
    
    // 配送先変更（ここがエラーの原因でした：ルート名を追加）
    Route::get('/purchase/address/{item_id}', [PurchaseController::class, 'editAddress'])->name('address.edit');
    Route::post('/purchase/address/{item_id}', [PurchaseController::class, 'updateAddress'])->name('address.update');

    // お気に入り・コメント
    Route::post('/item/{item_id}/favorite', [FavoriteController::class, 'store'])->name('favorite.store');
    Route::delete('/item/{item_id}/favorite', [FavoriteController::class, 'destroy'])->name('favorite.destroy');
    Route::post('/item/{item_id}/comment', [CommentController::class, 'store'])->name('comment.store');
});