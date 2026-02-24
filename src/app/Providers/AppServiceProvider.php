<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Http\Requests\LoginRequest as FortifyLoginRequest;

class AppServiceProvider extends ServiceProvider
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
        /**
         * Fortifyのログインバリデーションをカスタマイズ
         * ログイン時のエラーメッセージを要件通りの日本語に上書きします
         */
        $this->app->bind(FortifyLoginRequest::class, function ($app) {
            return new class extends FortifyLoginRequest {
                /**
                 * バリデーションルールの定義
                 */
                public function rules(): array
                {
                    return [
                        'email'    => 'required|email',
                        'password' => 'required',
                    ];
                }

                /**
                 * カスタムエラーメッセージの定義
                 */
                public function messages(): array
                {
                    return [
                        'email.required'    => 'メールアドレスを入力してください',
                        'email.email'       => 'メールアドレスの形式で入力してください',
                        'password.required' => 'パスワードを入力してください',
                    ];
                }
            };
        });
    }
}