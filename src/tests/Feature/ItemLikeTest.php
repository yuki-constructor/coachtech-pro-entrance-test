<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Category;
use App\Models\Condition;
use App\Models\Comment;
use Illuminate\Foundation\Testing\DatabaseTransactions;


// ===================================================
//  （テスト項目）
// 8 いいね機能
// ===================================================


class ItemLikeTest extends TestCase
{

    // データベーストランザクションを利用
    use DatabaseTransactions;


    // ===================================================
    // セットアップ
    // ===================================================

    protected $user;
    protected $otherUser;
    protected $item;
    protected $category;
    protected $condition;


    protected function setUp(): void
    {
        parent::setUp();

        // ログインユーザーを作成
        $this->user = User::factory()->create();
        // $this->actingAs($this->user);

        // その他のユーザーを作成
        $this->otherUser = User::factory()->create();

        // その他のユーザー出品商品を作成
        $this->item = Item::create([
            'user_id' => $this->otherUser->id,
            'item_name' => 'テスト商品(ItemLikeTest)',
            'item_image' => 'test_item_image.png',
            'brand' => 'テストブランド',
            'price' => 1000,
            'description' => 'テスト商品の説明',
        ]);

        // 既存のカテゴリーテーブルからカテゴリーデータを取得
        $this->category = Category::all();

        // $itemにカテゴリーを紐づけ
        $this->item->categories()->attach($this->category);

        // 既存のコンディションテーブルからコンディションデータを取得
        $this->condition = Condition::all();

        // $itemにコンディションを紐づけ
        $this->item->conditions()->attach($this->condition);
    }


    // ===================================================
    //  （テスト内容）
    // いいねアイコンを押下することによって、いいねした商品として登録することができる。
    // ===================================================

    public function test_user_can_add_like()
    {

        // ユーザーでログイン
        $this->actingAs($this->user);

        // 商品詳細ページを開く
        $response = $this->get(route('item.show', ['itemId' => $this->item->id]));

        // ステータスコード 200 を確認
        $response->assertStatus(200);

        // いいねを押す
        $response = $this->post(route('like', ['itemId' => $this->item->id]));

        // likesテーブルにいいねした商品として登録されているか確認
        $this->assertDatabaseHas('likes', [
            'user_id' => $this->user->id,
            'item_id' => $this->item->id,
        ]);

        // 改めて商品詳細ページを開く
        $response = $this->get(route('item.show', ['itemId' => $this->item->id]));

        // いいね合計値が増加表示されたか確認
        $response->assertSeeInOrder([
            '<p class="item-like-count">',
            e($this->item->userLike()->count()),
            '</p>',
        ], false);

        // いいねを解除しておく
        $this->post(route('like', ['itemId' => $this->item->id]));
    }


    // ===================================================
    //  （テスト内容）追加済みのアイコンは色が変化する
    // ===================================================

    public function test_like_icon_changes_color()
    {
        // ユーザーでログイン
        $this->actingAs($this->user);

        // 商品詳細ページを開く
        $response = $this->get(route('item.show', ['itemId' => $this->item->id]));

        // ステータスコード 200 を確認
        $response->assertStatus(200);

        // いいねを押す
        $this->post(route('like', ['itemId' => $this->item->id]));

        // 最新データを取得（リレーションを再読み込み）
        $this->user->refresh();
        $this->user->load('likeItem');

        // 改めて商品詳細ページを開く
        $response = $this->get(route('item.show', ['itemId' => $this->item->id]));

        // いいね済みのアイコン(黄色の星)が表示されているか確認
        $response->assertSeeInOrder([
            '<img class="item-star"',
            'src="' . asset('storage/photos/logo_images/star-yellow.png') . '"'
        ], false);

        // いいねを解除しておく
        $this->post(route('like', ['itemId' => $this->item->id]));
    }

    // ===================================================
    //  （テスト内容）
    // 再度いいねアイコンを押下することによって、いいねを解除することができる。
    // ===================================================


    public function test_user_can_remove_like()
    {
        // ユーザーでログイン
        $this->actingAs($this->user);

        // いいねを押す
        $this->post(route('like', ['itemId' => $this->item->id]));

        // いいねを解除
        $this->post(route('like', ['itemId' => $this->item->id]));

        // データベースから「いいね」が削除されたか確認
        $this->assertDatabaseMissing('likes', [
            'user_id' => $this->user->id,
            'item_id' => $this->item->id,
        ]);
    }
}
