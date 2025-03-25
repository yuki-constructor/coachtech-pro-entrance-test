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
// 12 配送先変更機能
// ===================================================


class EditAddressTest extends TestCase
{

    // データベーストランザクションを利用
    use DatabaseTransactions;

    // ===================================================
    //  （テスト内容）
    //送付先住所変更画面にて登録した住所が商品購入画面に反映されている
    // ===================================================

    public function test_changed_address_on_purchase_page()
    {
        // ユーザー作成
        $user = User::factory()->create([
            'postal_code' => '111-1111',
            'address' => 'テスト住所(変更前1)',
            'building' => 'テストビル(変更前1)'
        ]);

        // 商品を作成
        $item = Item::create([
            'user_id' => $user->id,
            'item_name' => 'テスト商品(EditAddressTest)',
            'item_image' => 'test_item_image.png',
            'brand' => 'テストブランド',
            'price' => 1000,
            'description' => 'テスト商品の説明',
        ]);

        // ユーザーでログイン
        $this->actingAs($user);

        // 送付先住所変更画面を開く
        $response = $this->get(route('profile.edit.address', ['itemId' => $item->id]));

        // ステータスコード 200 を確認
        $response->assertStatus(200);

        // 住所変更をリクエスト
        $response = $this->post(route('profile.update.address', ['itemId' => $item->id]), [
            'postal_code' => '222-2222',
            'address' => 'テスト住所(変更後1)',
            'building' => 'テストビル(変更後1)'
        ]);

        // 商品購入画面を開く
        $response = $this->get(route('item.purchase', ['itemId' => $item->id]));

        // ステータスコード 200 を確認
        $response->assertStatus(200);

        // 住所が更新されているか確認
        $response->assertSee('222-2222')
            ->assertSee('テスト住所(変更後1)')
            ->assertSee('テストビル(変更後1)');
    }


    // ===================================================
    //  （テスト内容）
    //購入した商品に送付先住所が紐づいて登録される
    // ===================================================

    public function test_shipping_address_link_to_purchase()
    {

        // ユーザー作成
        $user = User::factory()->create([
            'postal_code' => '111-1111',
            'address' => 'テスト住所(変更前2)',
            'building' => 'テストビル(変更前2)'
        ]);

        // 商品を作成
        $item = Item::create([
            'user_id' => $user->id,
            'item_name' => 'テスト商品(EditAddressTest)',
            'item_image' => 'test_item_image.png',
            'brand' => 'テストブランド',
            'price' => 1000,
            'description' => 'テスト商品の説明',
        ]);

        // ユーザーでログイン
        $this->actingAs($user);

        // 送付先住所変更画面を開く
        $response = $this->get(route('profile.edit.address', ['itemId' => $item->id]));

        // ステータスコード 200 を確認
        $response->assertStatus(200);

        // 住所変更をリクエスト
        $response = $this->post(route('profile.update.address', ['itemId' => $item->id]), [
            'postal_code' => '222-2222',
            'address' => 'テスト住所(変更後2)',
            'building' => 'テストビル(変更後2)'
        ]);

        // 購入処理を実行
        $purchaseData = [
            'payment_method' => 'card',
        ];

        $response = $this->post(route('item.purchase.payment', ['itemId' => $item->id]), $purchaseData);

        // データベースに購入情報が登録されているか確認
        $this->assertDatabaseHas('purchases', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'sending_address' => '〒222-2222 テスト住所(変更後2) テストビル(変更後2)',
            'payment_method' => 1,
        ]);
    }
}
