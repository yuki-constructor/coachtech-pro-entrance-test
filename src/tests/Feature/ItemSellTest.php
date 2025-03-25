<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Category;
use App\Models\Condition;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\DatabaseTransactions;



// ===================================================
//  （テスト項目）
// 15 出品商品情報登録
// ===================================================


class ItemSellTest extends TestCase
{

    // データベーストランザクションを利用
    use DatabaseTransactions;

    // ===================================================
    //  （テスト内容）商品出品画面にて必要な情報が保存できること（カテゴリ、商品の状態、商品名、商品の説明、販売価格）
    // ===================================================

    public function test_item_sell()
    {
        // ログインユーザーを作成
        $user = User::factory()->create();

        // ユーザーとしてログイン
        $this->actingAs($user);

        // 商品出品ページを開く
        $response = $this->get(route('item.create'));

        // ステータスコード 200 を確認
        $response->assertStatus(200);

        // 既存のカテゴリーテーブルからカテゴリーデータを取得
        $category = Category::inRandomOrder()->first(); // ランダムなカテゴリを取得

        // 既存のコンディションテーブルからコンディションデータを取得
        $condition = Condition::inRandomOrder()->first(); // ランダムなコンディションを取得

        // フォームに送信するデータ
        $formData = [
            'item_name' => 'テスト商品(ItemSellTest.php)',
            'item_image' => 'test_item_image.png',
            // 'item_image' => UploadedFile::fake()->image( 'test_item_image.png'),
            'price' => 1000,
            'description' => 'テスト商品の説明',
            'categories' => [$category->id],
            'condition' => [$condition->id],
        ];

        // 出品処理を実行
        $response = $this->post(route('item.store'), $formData);

        // データベースに商品が登録されているか確認
        $this->assertDatabaseHas('items', [
            'user_id' => $user->id,
            'item_name' => 'テスト商品(ItemSellTest.php)',
            'brand' => 'テストブランド',
            'price' => 1000,
            'description' => 'テスト商品の説明',
        ]);

        // カテゴリーが正しく保存されているか確認
        $this->assertDatabaseHas('categories_items', [
            'item_id' => Item::first()->id,
            'category_id' => $category->id,
        ]);

        // コンディションが正しく保存されているか確認
        $this->assertDatabaseHas('conditions_items', [
            'item_id' => Item::first()->id,
            'condition_id' => $condition->id,
        ]);

        // 画像が正しく保存されているか確認
        Storage::disk('public')->assertExists('photos/item_images/' . Item::first()->item_image);
    }
}
