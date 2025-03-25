<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;


class LoginRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => ['required', 'email'],  // メールアドレス: 入力必須、メール形式
            'password' => ['required', 'string', 'min:8'],  // パスワード: 入力必須、8文字以上
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'メールアドレスを入力してください',
            'email.email' => 'メールアドレスは正しい形式で入力してください',
            'password.required' => 'パスワードを入力してください',
            'password.min' => 'パスワードは8文字以上で入力してください',
        ];
    }
}
