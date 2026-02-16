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
    // a/b. メール認証誘導画面の表示（未認証時に自動リダイレクトされる先）
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    // c. メール認証処理（メール内のリンク押下時）
    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        // 認証完了後はプロフィール設定画面へ遷移させる（指示要件4-d）
        return redirect()->route('profile.edit')->with('message', 'メール認証が完了しました。プロフィールを設定してください。');
    })->middleware(['signed'])->name('verification.verify');

    // 認証メール再送ボタン用
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
    
    // d. プロフィール設定・マイページ
    Route::get('/mypage', [ProfileController::class, 'index'])->name('mypage.index');
    Route::get('/mypage/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/mypage/profile', [ProfileController::class, 'update'])->name('profile.update');
    
    // 出品・購入・お気に入り（認証後のみ可能に制限）
    Route::get('/sell', [ItemController::class, 'create'])->name('item.create');
    Route::post('/sell', [ItemController::class, 'store'])->name('item.store');
    Route::get('/purchase/{item_id}', [PurchaseController::class, 'show'])->name('item.purchase.show');
    Route::post('/purchase/{item_id}', [PurchaseController::class, 'store'])->name('item.purchase.store');
    
    Route::post('/item/{item_id}/favorite', [FavoriteController::class, 'store'])->name('favorite.store');
    Route::delete('/item/{item_id}/favorite', [FavoriteController::class, 'destroy'])->name('favorite.destroy');
    Route::post('/item/{item_id}/comment', [CommentController::class, 'store'])->name('comment.store');
});