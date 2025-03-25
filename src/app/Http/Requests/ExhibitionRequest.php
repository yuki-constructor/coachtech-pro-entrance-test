<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule','array<mixed>','string>
     */
    public function rules(): array
    {
        return [
            'item_name' => ['required', 'string'],   // 商品名: 入力必須
            'description' => ['required', 'string', 'max:255'],   // 商品説明: 入力必須、最大文字数255
            'item_image' => ['required', 'image', 'mimes:jpeg,png'],    // 商品画像: 入力必須、 拡張子が .jpeg もしくは .png
            'categories.*' => ['required'],   // 商品カテゴリー: 入力必須
            'condition' => ['required', 'integer'], // 商品の状態: 入力必須
            'price' => ['required', 'numeric'],    // 商品価格: 入力必須
        ];
    }

    public function messages()
    {
        return [
            'item_name.required' => '商品名を入力してください',
            'description.required' => '商品説明を入力してください',
            'description.min' => '商品説明は255文字以下で入力してください',
            'item_image.required' => '商品画像をアップロードしてください',
            'profile_image.mimes' => '商品画像はjpegまたはpng形式でアップロードしてください',
            'categories.*.required' => '商品カテゴリーを入力してください',
            'condition.required' => '商品の状態を入力してください',
            'price.required' => '商品価格を入力してください',
        ];
    }
}
