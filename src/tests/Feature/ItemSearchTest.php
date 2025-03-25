<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use Illuminate\Foundation\Testing\DatabaseTransactions;


// ===================================================
//  （テスト項目）
// 6 商品検索機能
// ===================================================


class ItemSearchTest extends TestCase
{

    // データベーストランザクションを利用
    use DatabaseTransactions;


    // ===================================================
    // セットアップ
    // ===================================================

    protected $user;
    protected $item1;
    protected $item2;

    protected function setUp(): void
    {
        parent::setUp();

        // ユーザーを作成
        $this->user = User::factory()->create();

        // 商品を作成
        $this->item1 = Item::create([
            'user_id' => $this->user->id,
            'item_name' => 'テスト商品１(ItemSearchTest・指輪)',
            'item_image' => 'test_image.jpg',
            'brand' => 'テストブランド',
            'price' => 1000,
            'description' => 'テスト商品の説明',
        ]);

        $this->item2 = Item::create([
            'user_id' => $this->user->id,
            'item_name' => 'テスト商品２(ItemSearchTest・花)',
            'item_image' => 'test_image.jpg',
            'brand' => 'テストブランド',
            'price' => 1000,
            'description' => 'テスト商品の説明',
        ]);
    }



    // ===================================================
    //  （テスト内容）「商品名」で部分一致検索ができる
    // ===================================================

    public function test_search_by_name()
    {
        // 検索を行う（部分一致検索）
        $response = $this->get(route('items.search') . '?item_name=指');

        // ステータスコード 200 を確認
        $response->assertStatus(200);

        // 結果の検証：指輪が表示されていること
        $response->assertSee($this->item1->item_name);
        $response->assertDontSee($this->item2->item_name);
    }


    // ===================================================
    //  （テスト内容）検索状態がマイリストでも保持されている
    // ===================================================

    public function test_search_in_mylist()
    {
        // ログイン状態を作成
        $this->actingAs($this->user);

        // 商品を検索（部分一致検索）
        $response = $this->get(route('items.search') . '?item_name=指');
        $response->assertStatus(200);
        $response->assertSee($this->item1->item_name);
        $response->assertDontSee($this->item2->item_name);

        // マイリストページに遷移
        $response = $this->get(route('items.index.mylist'));
        $response->assertStatus(200);

        // 検索状態が保持されていることを確認（検索キーワードと検索結果）
        $response->assertSee('指'); // 検索キーワードが表示される
        $response->assertSee($this->item1->item_name); // 検索結果が表示される
        $response->assertDontSee($this->item2->item_name); // 検索結果に表示されない
    }
}
