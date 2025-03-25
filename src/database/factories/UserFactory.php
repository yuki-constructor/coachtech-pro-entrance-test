<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,  // ユーザー名
            'email' => $this->faker->unique()->safeEmail,  // メールアドレス
            'password' => Hash::make('123456789'),  // パスワードを「123456789」に設定（ハッシュ化）
            'profile_image' => $this->faker->imageUrl(),  // プロフィール画像（オプション）
            'postal_code' => $this->faker->postcode,  // 郵便番号（オプション）
            'address' => $this->faker->address,  // 住所（オプション）
            'building' => $this->faker->word,  // 建物名（オプション）
            'email_verification_token' => Str::random(32),  // メール認証用トークン
            'is_first_login' => $this->faker->boolean,  // 初回ログインフラグ
        ];
    }
}
