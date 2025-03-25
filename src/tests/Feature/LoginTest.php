<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\DatabaseTransactions;


// ===================================================
//  （テスト項目）
// 2 ログイン機能
// ===================================================


class LoginTest extends TestCase
{
    // データベーストランザクションを利用
    use DatabaseTransactions;

    // ===================================================
    //  （テスト内容）
    // ・メールアドレスが入力されていない場合、バリデーションメッセージが表示される
    // ・パスワードが入力されていない場合、バリデーションメッセージが表示される
    // ・入力情報が間違っている場合、バリデーションメッセージが表示される
    // ===================================================
    /**
     * @dataProvider LoginValidationProvider
     */
    public function test_login_validation($field, $value, $expectedError)
    {
        // ログインページを開く
        $response = $this->get(route('login'));

        // ステータスコード 200 を確認
        $response->assertStatus(200);

        $data = [
            'email' => 'user@example.com',
            'password' => '123456789',
        ];

        // テスト対象のフィールドの値を変更
        $data[$field] = $value;

        $response = $this->post(route('login.store'), $data);

        $response->assertSessionHasErrors([$field => $expectedError]);
    }

    // データプロバイダ
    public static function loginValidationProvider()
    {
        return [
            'メールアドレスが必須' => ['email', '', 'メールアドレスを入力してください'],
            'パスワードが必須' => ['password', '', 'パスワードを入力してください'],
            // '正しい情報を入力' => ['email', 'error@example.com', 'ログイン情報が登録されていません'],
            // '正しい情報を入力' => ['password', 'errorpassword', 'ログイン情報が登録されていません'],

        ];
    }

    // ===================================================
    //  （テスト内容）
    // 入力情報が間違っている場合、バリデーションメッセージが表示される
    // ===================================================
    public function test_login_error()
    {
        // ログインページを開く
        $response = $this->get(route('login'));

        // ステータスコード 200 を確認
        $response->assertStatus(200);

        $response = $this->followingRedirects()->post(route('login.store'), [
            'email' => 'error@example.com',
            'password' => 'errorpassword',
        ]);

        $response->assertSee('ログイン情報が登録されていません。'); // ビューの中にエラーメッセージがあるか確認
    }


    // ===================================================
    //  （テスト内容）
    // 正しい情報が入力された場合、ログイン処理が実行される
    // ===================================================

    // setUp()を使用

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(); // テストごとに新しいユーザー情報を生成
    }

    public function test_login_success()
    {
        // ログインページを開く
        $response = $this->get(route('login'));

        // ステータスコード 200 を確認
        $response->assertStatus(200);

        // $response = $this->post(route('login.store'), $this->user);// 仮のユーザーデータでログイン

        $response = $this->post(route('login.store'), [
            'email' => $this->user->email,
            'password' => "123456789",
        ]);

        $response->assertRedirect(route('login')); // ログイン後のリダイレクト先（ログイン画面にメール認証を促すメッセージ表示）
        $this->assertAuthenticatedAs($this->user); // ログイン後の認証状態を確認
    }
}
