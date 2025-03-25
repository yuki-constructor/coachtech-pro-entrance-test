<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;


// ===================================================
//  （テスト項目）
//  14 ユーザー情報変更
// ===================================================


class ProfileEditTest extends TestCase
{

    // データベーストランザクションを利用
    use DatabaseTransactions;

    // ===================================================
    //  （テスト内容）変更項目が初期値として過去設定されていること（プロフィール画像、ユーザー名、郵便番号、住所）
    // ===================================================

    public function test_profile_edit_page()
    {
        // ログインユーザーを作成
        $user = User::factory()->create([
            'name' => 'テストユーザー(ProfileEditTest)',
            'profile_image' => 'test_user_image.png',
            'postal_code' => '111-1111',
            'address' => 'テスト住所',
            'building' => 'テストビル',
        ]);

        // ユーザーでログイン
        $this->actingAs($user);

        // 送付先住所変更画面を開く
        $response = $this->get(route('profile.edit'));

        // ステータスコード 200 を確認
        $response->assertStatus(200);

        // ユーザーのプロフィール情報が初期値として表示されているか確認
        $response->assertSee(asset('storage/photos/profile_images/test_user_image.png')); // プロフィール画像
        $response->assertSee('テストユーザー(ProfileEditTest)'); // ユーザー名
        $response->assertSee('111-1111'); // 郵便番号
        $response->assertSee('テスト住所'); // 住所
        $response->assertSee('テストビル');  // 建物名
    }
}
