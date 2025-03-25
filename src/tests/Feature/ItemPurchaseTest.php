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
// 10 商品購入機能
// ===================================================


class ItemPurchaseTest extends TestCase
{

    // データベーストランザクションを利用
    use DatabaseTransactions;


    // ===================================================
    // セットアップ
    // ===================================================

    protected $user;
    protected $otherUser;
    protected $item1;
    protected $item2;
    protected $item3;

    protected function setUp(): void
    {
        parent::setUp();

        // ユーザーを作成
        $this->user = User::factory()->create();
        // $this->actingAs($this->user);

        // 他のユーザーを作成
        $this->otherUser = User::factory()->create();

        // 他のユーザーの出品商品を３つ作成
        $this->item1 = Item::create([
            'user_id' => $this->otherUser->id,
            'item_name' => 'テスト商品１(ItemPurchaseTest)',
            'item_image' => 'test_item_image.png',
            'brand' => 'テストブランド',
            'price' => 1000,
            'description' => 'テスト商品の説明',
        ]);

        $this->item2 = Item::create([
            'user_id' => $this->otherUser->id,
            'item_name' => 'テスト商品２(ItemPurchaseTest)',
            'item_image' => 'test_item_image.png',
            'brand' => 'テストブランド',
            'price' => 1000,
            'description' => 'テスト商品の説明',
        ]);

        $this->item3 = Item::create([
            'user_id' => $this->otherUser->id,
            'item_name' => 'テスト商品３(ItemPurchaseTest)',
            'item_image' => 'test_item_image.png',
            'brand' => 'テストブランド',
            'price' => 1000,
            'description' => 'テスト商品の説明',
        ]);
    }



    // ===================================================
    //  （テスト内容）
    // 「購入する」ボタンを押下すると購入が完了する
    // ===================================================

    public function test_complete_purchase()
    {
        // ユーザーでログイン
        $this->actingAs($this->user);

        // 商品購入画面を開く
        $response = $this->get(route('item.purchase', ['itemId' => $this->item1->id]));

        // ステータスコード 200 を確認
        $response->assertStatus(200);

        // 購入処理を実行
        $purchaseData = [
            'payment_method' => 'card',
        ];

        $response = $this->post(route('item.purchase.payment', ['itemId' => $this->item1->id]), $purchaseData);

        // リダイレクトが行われることを確認（Stripeの支払いページへ）
        $response->assertStatus(302);

        // 購入がデータベースに記録されたことを確認
        $this->assertDatabaseHas('purchases', [
            'user_id' => $this->user->id,
            'item_id' => $this->item1->id,
            'purchase_status' => 0, // Stripeの支払いが未完了のため、未完了
        ]);
    }



    // ===================================================
    //  （テスト内容）
    // 購入した商品は商品一覧画面にて「sold」と表示される
    // ===================================================


    public function test_purchased_item_displayed_sold()
    {
        // ユーザーでログイン
        $this->actingAs($this->user);

        // 商品を購入
        Purchase::create([
            'user_id' => $this->user->id,
            'item_id' => $this->item2->id,
            'purchase_status' => 1, // 完了
            'payment_method' => 1, // カード払い
            'stripe_payment_id' => 'test_stripe_id',
            'sending_address' => 'テスト住所',
        ]);

        // 商品一覧画面を開く
        $response = $this->get(route('items.index'));

        // ステータスコード 200 を確認
        $response->assertStatus(200);

        // 商品一覧画面で、商品名に"SOLD" が表示されていることを確認
        $response->assertSee('SOLD テスト商品２(ItemPurchaseTest)');
    }


    // ===================================================
    //  （テスト内容）
    // 「プロフィール/購入した商品一覧」に追加されている
    // ===================================================

    public function test_purchased_item_displayed_on_profile_page()
    {
        // ユーザーでログイン
        $this->actingAs($this->user);

        // 商品を購入
        Purchase::create([
            'user_id' => $this->user->id,
            'item_id' => $this->item3->id,
            'purchase_status' => 1, // 完了
            'payment_method' => 1, // カード払い
            'stripe_payment_id' => 'test_stripe_id',
            'sending_address' => 'テスト住所',
        ]);

        // プロフィール/購入した商品一覧画面を開く
        $response = $this->get(route('profile.show.buy'));

        // ステータスコード 200 を確認
        $response->assertStatus(200);

        // 購入した商品名が表示されていることを確認
        $response->assertSee($this->item3->item_name);
    }
}
