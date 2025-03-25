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
use Illuminate\Support\Str;


// ===================================================
//  （テスト項目）
// 9 コメント送信機能
// ===================================================


class ItemCommentTest extends TestCase
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
    protected $comment;


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
            'item_name' => 'テスト商品(ItemCommentTest)',
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
    // ログイン済みのユーザーはコメントを送信できる
    // ===================================================

    public function test_user_can_add_comment()
    {
        // ユーザーでログイン
        $this->actingAs($this->user);

        // 商品詳細ページを開く
        $response = $this->get(route('item.show', ['itemId' => $this->item->id]));

        // ステータスコード 200 を確認
        $response->assertStatus(200);

        // ログイン済みのユーザーはコメントを送信できるか確認
        // type="submit" のボタンが表示されてることを確認
        $response->assertSee('<button type="submit" class="comment-submit">コメントを送信する</button>', false);

        // 代わりに、type 属性なしのボタンが表示されていないことを確認
        $response->assertDontSee('<button class="comment-submit">コメントを送信する</button>', false);

        // コメントを送信
        $response = $this->post(route('comment', ['itemId' => $this->item->id]), [
            'comment' => 'テストコメント'
        ]);

        // コメントがDBに保存されているか確認
        $this->assertDatabaseHas('comments', [
            'user_id' => $this->user->id,
            'item_id' => $this->item->id,
            'comment' => 'テストコメント',
        ]);

        // 改めて商品詳細ページを開く
        $response = $this->get(route('item.show', ['itemId' => $this->item->id]));

        // 商品詳細ページにコメントが表示されたか確認
        $response->assertSee('テストコメント');
    }


    // ===================================================
    //  （テスト内容）
    // ログイン前のユーザーはコメントを送信できない
    // ===================================================

    public function test_unauthenticated_user()
    {

        // ログイン前のユーザーとして商品詳細ページを開く
        $response = $this->get(route('item.show', ['itemId' => $this->item->id]));

        // ステータスコード 200 を確認
        $response->assertStatus(200);

        // ログイン前のユーザーはコメントを送信できないことを確認
        // type="submit" のボタンが表示されていないことを確認
        $response->assertDontSee('<button type="submit" class="comment-submit">コメントを送信する</button>', false);

        // 代わりに、type 属性なしのボタンがあることを確認
        $response->assertSee('<button class="comment-submit">コメントを送信する</button>', false);
    }


    // ===================================================
    //  （テスト内容）
    // コメントが入力されていない場合、バリデーションメッセージが表示される
    // ===================================================

    public function test_comment_empty_validation()
    {
        // ログインする
        $this->actingAs($this->user);

        // 空のコメントを送信
        $response = $this->post(route('comment', ['itemId' => $this->item->id]), [
            'comment' => ''
        ]);

        // バリデーションエラーを確認
        $response->assertSessionHasErrors([
            'comment' => 'コメントを入力してください',
        ]);

        //商品詳細ページにエラーメッセージが表示されるか確認
        $this->get(route('item.show', ['itemId' => $this->item->id]))
            ->assertSee('コメントを入力してください');
    }


    // ===================================================
    //  （テスト内容）
    // コメントが255字以上の場合、バリデーションメッセージが表示される
    // ===================================================

    public function test_comment_longer_than_255_validation()
    {
        // ログインする
        $this->actingAs($this->user);

        // 256文字のコメントを作成
        $comment = Str::random(256);;

        // コメントを送信
        $response = $this->post(route('comment', ['itemId' => $this->item->id]), [
            'comment' => $comment
        ]);

        // バリデーションエラーを確認
        $response->assertSessionHasErrors(['comment' => 'コメントは255文字以下で入力してください']);

        //商品詳細ページにエラーメッセージが表示されるか確認
        $this->get(route('item.show', ['itemId' => $this->item->id]))
            ->assertSee('コメントは255文字以下で入力してください');
    }
}
