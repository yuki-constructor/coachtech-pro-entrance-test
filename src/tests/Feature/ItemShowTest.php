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
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Testing\DatabaseTransactions;


// ===================================================
//  （テスト項目）
// 7 商品詳細情報取得
// ===================================================


class ItemShowTest extends TestCase
{

    // データベーストランザクションを利用
    use DatabaseTransactions;

    // ===================================================
    // セットアップ
    // ===================================================

    protected $user;
    protected $item;
    protected $category;
    protected $condition;
    protected $comment1;
    protected $comment2;

    protected function setUp(): void
    {
        parent::setUp();

        // ユーザーを作成
        $this->user = User::factory()->create();
        // $this->actingAs($this->user);

        // 商品を作成
        $this->item = Item::create([
            'user_id' => $this->user->id,
            'item_name' => 'テスト商品(ItemShowTest)',
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

        // ２件コメントを作成
        $this->comment1 = Comment::create([
            'user_id' => $this->user->id,
            'item_id' => $this->item->id,
            'comment' => 'テストコメント１',
        ]);

        $this->comment2 = Comment::create([
            'user_id' => $this->user->id,
            'item_id' => $this->item->id,
            'comment' => 'テストコメント２',
        ]);

        // $itemにいいねを設定
        $this->user->likeItem()->attach($this->item->id);
    }


    // ===================================================
    //  （テスト内容）
    // 必要な情報が表示される（商品画像、商品名、ブランド名、価格、いいね数、コメント数、商品説明、商品情報（カテゴリ、商品の状態）、コメント数、コメントしたユーザー情報、コメント内容）
    // ===================================================

    public function test_item_information()
    {

        // 商品詳細ページを開く
        $response = $this->get(route('item.show', ['itemId' => $this->item->id]));

        // ステータスコード 200 を確認
        $response->assertStatus(200);

        // 商品画像が表示されていることを確認
        $response->assertSeeInOrder([
            '<img class="item-image"',
            'src="' . asset('storage/photos/item_images/' . $this->item->item_image) . '"'
        ], false);

        // 商品名が表示されていることを確認
        $response->assertSee($this->item->item_name);

        // ブランド名が表示されていることを確認
        $response->assertSee($this->item->brand);

        //価格が表示されていることを確認
        $response->assertSee("¥" . number_format($this->item->price));

        // 商品説明が表示されていることを確認
        $response->assertSee($this->item->description);

        //カテゴリが表示されていることを確認
        $response->assertSeeInOrder($this->item->categories->pluck('category_name')->toArray());

        // 商品の状態が表示されていることを確認
        $response->assertSee($this->item->conditions->first()->condition_name);

        // いいね数が表示されていることを確認
        $response->assertSee($this->item->userLike()->count());

        // コメント数（２件）が表示されていることを確認
        $response->assertSee($this->item->comments->count());

        //コメントしたユーザー情報が表示されていることを確認
        $response->assertSee($this->user->name);

        //コメント内容が表示されていることを確認
        $response->assertSee($this->comment1->comment);
        $response->assertSee($this->comment2->comment);
    }


    // ===================================================
    //  （テスト内容）複数選択されたカテゴリが表示されているか
    // ===================================================

    public function test_item_categories()
    {

        // 商品詳細ページを開く
        $response = $this->get(route('item.show', ['itemId' => $this->item->id]));

        // ステータスコード 200 を確認
        $response->assertStatus(200);

        //カテゴリが表示されていることを確認
        $response->assertSeeInOrder($this->item->categories->pluck('category_name')->toArray());
    }
}
