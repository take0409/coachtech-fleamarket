<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Contracts\RegisterResponse; // 追加

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 1. 会員登録画面の指定
        Fortify::registerView(function () {
            return view('auth.register');
        });

        // 2. ログイン画面の指定
        Fortify::loginView(function () {
            return view('auth.login');
        });

        // 3. 新規登録の処理を紐付け
        Fortify::createUsersUsing(CreateNewUser::class);

        // --- 追加：登録後の移動先を「メール認証誘導画面」に強制する ---
        $this->app->instance(RegisterResponse::class, new class implements RegisterResponse {
            public function toResponse($request) {
                return redirect()->route('verification.notice');
            }
        });

        // レートリミット設定
        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(10)->by($request->email.$request->ip());
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });
    }
}