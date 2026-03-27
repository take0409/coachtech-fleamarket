<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'description' => 'required|string|max:255',
            'condition' => 'required|string',
            'img_url' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'categories' => 'required|array',
            'categories.*' => 'integer|exists:categories,id',
            'brand' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => '商品名を入力してください。',
            'description.required' => '商品の説明を入力してください。',
            'condition.required' => '商品の状態を選択してください。',
            'price.required' => '販売価格を入力してください。',
            'price.integer' => '販売価格は数値で入力してください。',
            'price.min' => '商品価格は0円以上で入力してください。',
            'img_url.required' => '商品画像を選択してください。',
            'description.max' => '商品説明は255文字以内で入力してください。',
            'img_url.max' => '画像サイズは2MB以内でアップロードしてください。',
            'img_url.mimes' => '画像形式はjpeg, png, jpgに対応しています。',
            'categories.required' => 'カテゴリーを選択してください。',
        ];
    }
}
