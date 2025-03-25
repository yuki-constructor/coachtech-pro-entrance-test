<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Item;
use App\Models\Category;
use App\Models\Condition;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Requests\ExhibitionRequest;
use App\Http\Requests\CommentRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;



class ItemController extends Controller
{

    // 検索機能
    public function search(Request $request)
    {

        // $items = Item::paginate(6);
        $items = Item::query();

        $keyword = $request->input("item_name");

        if (!empty($keyword)) {
            $items->where("item_name", "LIKE", "%{$keyword}%");
        }

        $items = $items->get();

        // セッションに検索条件と、検索結果を保存（マイリストで表示）
        session([
            "search_keyword" => $keyword,
            "search_items" => $items,
        ]);

        return view("items.index", ["items" => $items]);
    }

    // 商品一覧画面表示
    public function index()
    {
        if (Auth::check()) {
            $items = Item::where('user_id', '!=', auth()->id())->get();
        } else {
            $items = Item::all();
        }

        return view("items.index", ["items" => $items]);
    }

    // 商品一覧画面表示（マイリスト）
    public function indexMylist()
    {
        if (Auth::check()) {

            // ログインしている場合
            //いいねした商品
            $items = Auth::user()->likeItem;
            // 商品検索欄に検索したキーワードと検索したキーワードに一致する商品
            $keyword = session("search_keyword");

            $searchItems = session("search_items");

            return view("items.index-mylist", [
                "items" => $items,
                "keyword" => $keyword,
                "searchItems" => $searchItems
            ]);
        } else {

            // 未ログインの場合
            return view("items.index-mylist");
        }
    }


    // 商品詳細画面表示
    public function show($itemId)
    {
        $item = Item::findOrFail($itemId);

        $categories = $item->categories;

        $condition = $item->conditions->first(); //  コレクション対応
        $comments = $item->comments;

        return view('items.show', ['item' => $item, "categories" => $categories, "condition" => $condition, "comments" => $comments]);
    }


    // 商品登録（出品）画面表示
    public function create()
    {
        $categories = Category::all();
        $conditions = Condition::all();

        return view("items.sell", ["categories" => $categories, "conditions" => $conditions]);
    }


    // 商品登録（出品）処理
    public function store(ExhibitionRequest $exhibitionRequest)
    {
        $savedfilepath = basename($exhibitionRequest->file("item_image")->store("photos/item_images", "public"));
        $item = new Item($exhibitionRequest->validated());
        $item["item_image"] = $savedfilepath;
        $item["user_id"] = auth()->id(); // ログイン中のユーザーIDを設定
        $item->save();

        $item->categories()->sync($exhibitionRequest->input('categories', []));
        $item->conditions()->sync($exhibitionRequest->input('condition', []));

        return to_route("profile.show.sell");
    }


    // いいねの処理
    public function like($itemId)
    {
        // 現在のユーザーを取得
        $user = auth()->user();

        // ユーザーがその商品を「いいね」していない場合
        if ($user->likeItem()->where('item_id', $itemId)->exists()) {
            // すでにいいねしているので解除
            $user->likeItem()->detach($itemId);
        } else {
            // まだいいねしていないので追加
            $user->likeItem()->attach($itemId);
        }

        // 商品の詳細ページにリダイレクト
        return back();
    }


    // コメントの処理
    public function comment(CommentRequest $commentRequest, $itemId)
    {
        Comment::create([
            'user_id' => auth()->id(),
            'item_id' => $itemId,
            'comment' => $commentRequest->input('comment'),
        ]);

        return redirect()->route('item.show', $itemId)->with('success', 'コメントを投稿しました！');
    }
}
