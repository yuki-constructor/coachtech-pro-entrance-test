<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;
use Illuminate\Foundation\Testing\DatabaseTransactions;



// ===================================================
//  （テスト項目）
// 13 ユーザー情報取得
// ===================================================


class ProfileTest extends TestCase
{
    // データベーストランザクションを利用
    use DatabaseTransactions;

    // ===================================================
    //  （テスト内容）必要な情報が取得できる（プロフィール画像、ユーザー名、出品した商品一覧、購入した商品一覧）
    // ===================================================
    public function test_information_display_on_profile_page()
    {
        // ログインユーザーを作成
        $user = User::factory()->create([
            'name' => 'テストユーザー(ProfileTest)',
            'profile_image' => 'test_user_image.png'
        ]);

        // 他のユーザーを作成
        $otherUser = User::factory()->create();

        // ログインユーザー出品の商品を作成
        $userItem =  Item::create([
            'user_id' => $user->id,
            'item_name' => 'テスト商品(ProfileTest・ログインユーザー出品)',
            'item_image' => 'test_item_image.png',
            'brand' => 'テストブランド',
            'price' => 1000,
            'description' => 'テスト商品の説明',
        ]);

        // 他のユーザーの出品商品を作成
        $otherUserItem = Item::create([
            'user_id' => $otherUser->id,
            'item_name' => 'テスト商品(ProfileTest・その他のユーザー出品・ログインユーザー購入済み)',
            'item_image' => 'test_item_image.png',
            'brand' => 'テストブランド',
            'price' => 1000,
            'description' => 'テスト商品の説明',
        ]);

        // 購入した商品を作成
        $purchase = Purchase::create([
            'user_id' => $user->id, // ログインユーザーが購入
            'item_id' => $otherUserItem->id, // その他のユーザーが出品した商品
            'purchase_status' => 1, // 完了
            'payment_method' => 1, // カード払い
            'stripe_payment_id' => 'test_stripe_id',
            'sending_address' => 'テスト住所',
        ]);

        // ユーザーとしてログイン
        $this->actingAs($user);

        // ---- 出品した商品一覧確認

        // 出品した商品ページを開く
        $response = $this->get(route('profile.show.sell'));

        // ステータスコード 200 を確認
        $response->assertStatus(200);

        // プロフィール画像が表示されていることを確認
        $response->assertSee(asset('storage/photos/profile_images/test_user_image.png'));

        // ユーザー名が表示されていることを確認
        $response->assertSee('テストユーザー(ProfileTest)');

        // 出品した商品が表示されていることを確認
        $response->assertSee($userItem->item_name);
        $response->assertSee(asset('storage/photos/item_images/' . $userItem->item_image));

        // ---- 購入した商品一覧確認

        // 購入した商品ページを開く
        $response = $this->get(route('profile.show.buy'));

        // ステータスコード 200 を確認
        $response->assertStatus(200);

        // プロフィール画像が表示されていることを確認
        $response->assertSee(asset('storage/photos/profile_images/test_user_image.png'));

        // ユーザー名が表示されていることを確認
        $response->assertSee('テストユーザー(ProfileTest)');

        // 購入した商品が表示されていることを確認
        $response->assertSee($purchase->item->item_name);
        $response->assertSee(asset('storage/photos/item_images/' . $purchase->item->item_image));
    }
}
