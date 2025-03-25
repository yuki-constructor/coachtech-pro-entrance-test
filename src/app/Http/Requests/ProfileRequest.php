<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;


class ProfileRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'profile_image' => ['mimes:jpeg,png'], // プロフィール画像: 拡張子が .jpeg もしくは .png
            'name' => ['required', 'string', 'max:255'], // お名前: 入力必須
            'postal_code' => ['required', 'regex:/^\d{3}-\d{4}$/'], // 郵便番号: 入力必須、ハイフンありの8文字
            'address' => ['required', 'string', 'max:255'], // 住所: 入力必須
            'building' => ['required', 'string', 'max:255'], // 建物名: 入力必須

        ];
    }

    public function messages()
    {
        return [
            'profile_image.mimes' => 'プロフィール画像はjpegまたはpng形式でアップロードしてください',
            'name.required' => 'お名前を入力してください',
            'postal_code.required' => '郵便番号を入力してください',
            'postal_code.regex' => '郵便番号はハイフンありの形式で入力してください',
            'address.required' => '住所を入力してください',
            'building.required' => '建物名を入力してください',
        ];
    }
}
