<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;
use App\Models\Purchase;
use Illuminate\Foundation\Testing\DatabaseTransactions;


// ===================================================
//  （テスト項目）
// 4 商品一覧取得
// ===================================================


class ItemIndexTest extends TestCase
{
    // データベーストランザクションを利用
    use DatabaseTransactions;

    // ===================================================
    //  （テスト内容）全商品を取得できる
    // ===================================================
    public function test_items_all()
    {
        // 商品一覧ページを開く
        $response = $this->get(route('items.index'));

        // ステータスコード 200 を確認
        $response->assertStatus(200);

        // 商品一覧画面に渡された商品の件数と、データベースにすでに登録されている商品の件数が一致するかを確認
        $response->assertViewHas('items', function ($items) {
            // データベースにすでに登録されている商品の数取得
            $allItem = Item::count();

            return $items->count() === $allItem;
        });
    }

    // ===================================================
    //  （テスト内容）購入済み商品は「Sold」と表示される
    // ===================================================
    /**
     * @dataProvider soldOutProvider
     */
    public function test_items_sold_out($itemData, $purchaseData)
    {
        // 商品データを作成
        $item = Item::create($itemData);

        // 購入データを作成（購入済み）
        Purchase::create(array_merge($purchaseData, ['item_id' => $item->id]));

        // 商品一覧ページを開く
        $response = $this->get(route('items.index'));

        // ステータスコード 200 を確認
        $response->assertStatus(200);

        // 購入済み商品に「Sold」のラベルが表示されているか確認
        $response->assertSee('SOLD ' . $item->item_name);
    }

    /**
     * 購入済み商品のデータプロバイダ
     */
    public static function soldOutProvider()
    {
        return [
            '購入済みのテスト商品' => [
                'itemData' => [
                    'user_id' => 1, // 出品者ID
                    'item_name' => 'テスト商品(ItemIndexTest・購入済み)',
                    'item_image' => 'test_image.jpg',
                    'brand' => 'テストブランド',
                    'price' => 1000,
                    'description' => 'テスト商品の説明',
                ],
                'purchaseData' => [
                    'user_id' => 2, // 購入者ID
                    'purchase_status' => 1, // 購入済み
                    'payment_method' => 1, // クレジットカード
                    'stripe_payment_id' => 'test_payment_123',
                    'sending_address' => '東京都渋谷区',
                ],
            ],
        ];
    }

    // ===================================================
    //  （テスト内容）自分が出品した商品は表示されない
    // ===================================================
    /**
     * @dataProvider soldByUserProvider
     */
    public function test_items_sold_by_users($sellData, $otherData)
    {
        // ユーザーを作成してログイン
        $user = User::factory()->create();
        $this->actingAs($user);

        // ログインユーザーの商品名をテスト毎に変更
        $sellData['item_name'] .= ' - ' . $user->name;

        // ログインユーザーの商品を作成
        $sellItem = Item::create(array_merge($sellData, ['user_id' => $user->id]));

        // 他のユーザーの商品を作成
        $otherUser = User::factory()->create();
        $otherItem = Item::create(array_merge($otherData, ['user_id' => $otherUser->id])); // 異なる user_id（他のユーザー）

        // 商品一覧ページを開く
        $response = $this->get(route('items.index'));

        // ステータスコード 200 を確認
        $response->assertStatus(200);

        // ログインユーザーの商品が表示されないことを確認
        $response->assertDontSee($sellItem->item_name);

        // その他のユーザーの商品が表示されることを確認
        $response->assertSee($otherItem->item_name);
    }
    /**
     * 出品した商品のデータプロバイダ
     */
    public static function soldByUserProvider()
    {
        return [
            'テスト商品' => [
                'sellData' => [
                    'item_name' => 'テスト商品(ItemIndexTest・ログインユーザー出品',
                    'item_image' => 'test_image.jpg',
                    'brand' => 'テストブランド',
                    'price' => 1000,
                    'description' => 'テスト商品の説明',
                ],
                'otherData' => [
                    'item_name' => 'テスト商品（ItemIndexTest・その他のユーザー出品）',
                    'item_image' => 'test_image.jpg',
                    'brand' => 'テストブランド',
                    'price' => 1000,
                    'description' => 'テスト商品の説明',
                ],
            ],
        ];
    }
}
