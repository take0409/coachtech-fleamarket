<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * ユーザーがこのリクエストを行う権限があるかどうかを判断
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * バリデーションルールの定義
     */
    public function rules(): array
    {
        return [
            'name'     => ['required', 'string', 'max:20'],
            'email'    => ['required', 'string', 'email', 'max:191', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    /**
     * カスタムエラーメッセージの定義
     */
    public function messages(): array
    {
        return [
            'name.required'     => 'お名前を入力してください',
            'name.max'          => 'お名前は20文字以内で入力してください',
            'email.required'    => 'メールアドレスを入力してください',
            'email.email'       => 'メールアドレスはメール形式で入力してください。',
            'email.unique'      => '指定したメールアドレスは既に使用されています。',
            'password.required' => 'パスワードを入力してください',
            'password.min'      => 'パスワードは8文字以上で入力してください',
            'password.confirmed'=> 'パスワードと一致しません',
        ];
    }
}