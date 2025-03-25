<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;


// ===================================================
//  （テスト項目）
// 3 ログアウト機能
// ===================================================


class LogoutTest extends TestCase
{
    // データベーストランザクションを利用
    use DatabaseTransactions;

    // ===================================================
    //  （テスト内容）ログアウトができる
    // ===================================================

    public function test_logout()
    {
        // ログインユーザーを作成
        $user = User::factory()->create();

        //ユーザーにログインをする
        $this->actingAs($user);

        //ログアウトリクエストを送信
        $response = $this->post(route('logout'));

        //ログアウト後のリダイレクト先を確認
        $response->assertRedirect(route('items.index'));

        //認証ユーザーがログアウトされたことを確認
        $this->assertGuest();
    }
}
