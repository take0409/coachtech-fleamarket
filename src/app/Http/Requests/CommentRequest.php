<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
{
    /**
     * ユーザーがこのリクエストを行う権限があるか
     */
    public function authorize()
    {
        return true; 
    }

    /**
     * バリデーションルール
     */
    public function rules()
    {
        return [
            // HTML側のinputタグのname属性に合わせて 'content' としています
            'content' => 'required|max:255',
        ];
    }

    /**
     * エラーメッセージのカスタマイズ
     */
    public function messages()
    {
        return [
            'content.required' => '商品コメントを入力してください',
            'content.max' => '商品コメントは255文字以内で入力してください',
        ];
    }
}