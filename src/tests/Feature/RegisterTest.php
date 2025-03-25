<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;


// ===================================================
//  （テスト項目）
// １ 会員登録機能
// ===================================================


class RegisterTest extends TestCase
{
    // データベーストランザクションを利用
    use DatabaseTransactions;

    // ===================================================
    //  （テスト内容）
    // ・名前が入力されていない場合、バリデーションメッセージが表示される
    // ・メールアドレスが入力されていない場合、バリデーションメッセージが表示される
    // ・パスワードが入力されていない場合、バリデーションメッセージが表示される
    // ・パスワードが7文字以下の場合、バリデーションメッセージが表示される
    // ・パスワードが確認用パスワードと一致しない場合、バリデーションメッセージが表示される
    // ===================================================
    /**
     * @dataProvider registrationValidationProvider
     */
    public function test_user_registration_validation($field, $value, $expectedError)
    {
        // 会員登録ページを開く
        $response = $this->get(route('register'));

        // ステータスコード 200 を確認
        $response->assertStatus(200);

        $data = [
            'name' => 'test user',
            'email' => 'user@example.com',
            'password' => '123456789',
            'password_confirmation' => '123456789',
        ];

        // テスト対象のフィールドの値を変更
        $data[$field] = $value;

        $response = $this->post(route('user.store'), $data);

        $response->assertSessionHasErrors([$field => $expectedError]);
    }

    // データプロバイダ
    public static function registrationValidationProvider()
    {
        return [
            '名前が必須' => ['name', '', 'お名前を入力してください'],
            'メールアドレスが必須' => ['email', '', 'メールアドレスを入力してください'],
            'パスワードが必須' => ['password', '', 'パスワードを入力してください'],
            'パスワードが７文字以下' => ['password', '123', 'パスワードは8文字以上で入力してください'],
            'パスワードが確認用パスワードと不一致' => ['password', '987654321', 'パスワードと一致しません'],
        ];
    }

    // ===================================================
    //  （テスト内容）
    // 全ての項目が入力されている場合、会員情報が登録され、ログイン画面に遷移される
    // ===================================================
    protected $userData;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userData = User::factory()->make()->toArray(); // テストごとに新しいユーザー情報を生成
    }

    public function test_user_registration()
    {

        // 会員登録ページを開く
        $response = $this->get(route('register'));

        // ステータスコード 200 を確認
        $response->assertStatus(200);

        $this->userData['password'] = '123456789';
        $this->userData['password_confirmation'] = '123456789';

        $response = $this->post(route('user.store'), $this->userData); // 仮のユーザーデータをusersテーブルに登録

        $this->assertDatabaseHas('users', ['email' => $this->userData['email']]);
        $response->assertRedirect(route('login'));
    }
}
