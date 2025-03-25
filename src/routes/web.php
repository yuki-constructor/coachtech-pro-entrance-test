<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Fortify;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\CommentController;

use App\Models\Purchase;


// ===================================================
//  認証関連
// ===================================================

Route::middleware('guest')->group(function () {

    // 会員登録
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');

    // 会員登録処理
    Route::post('/register', [RegisteredUserController::class, 'store'])->name('user.store');
});

// ログイン
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');

// ログイン認証処理
Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');

// メール認証のトークン検証
Route::get('/email/verify/{token}', [EmailVerificationController::class, 'verify'])->name('verification.verify');

Route::middleware('auth')->group(function () {

    // ログアウト
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});


// ===================================================
// ユーザー関連
// ===================================================

Route::middleware('auth')->group(function () {

    // ---- プロフィール関連

    // プロフィール設定画面（初回ログイン時）
    Route::get('/profile/create', [ProfileController::class, 'create'])->name('profile.create');

    // プロフィール登録処理
    Route::post('/profile/create', [ProfileController::class, 'store'])->name('profile.store');

    // プロフィール画面(購入した商品)
    Route::get('/profile/show/buy', [ProfileController::class, 'showBuy'])->name('profile.show.buy');

    // プロフィール画面(出品した商品)
    Route::get('/profile/show/sell', [ProfileController::class, 'showSell'])->name('profile.show.sell');

    // プロフィール編集画面
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');

    // プロフィール更新処理
    Route::post('/profile/edit', [ProfileController::class, 'update'])->name('profile.update');

    // 住所編集画面
    Route::get('/profile/edit/address/{itemId}', [ProfileController::class, 'editAddress'])->name('profile.edit.address');

    // 住所更新処理
    Route::post('/profile/edit/address/{itemId}', [ProfileController::class, 'updateAddress'])->name('profile.update.address');
});


// ===================================================
//  商品関連
// ===================================================

// ---- 商品一覧・検索・詳細

// 商品一覧画面表示（おすすめ）
Route::get("/items", [ItemController::class, "index"])->name("items.index");

// 商品一覧画面表示(マイリスト)
Route::get('/items/mylist', [ItemController::class, 'indexMylist'])->name('items.index.mylist');

// 検索処理
Route::get("/items/search", [ItemController::class, "search"])->name("items.search");

// 商品詳細画面表示
Route::get("/item/{itemId}", [ItemController::class, "show"])->name("item.show");

// ---- 商品操作（出品・購入・削除・いいね・コメント）

Route::middleware('auth')->group(function () {

    // 商品登録（出品）画面表示
    Route::get("/items/register", [ItemController::class, "create"])->name("item.create");

    // 商品登録（出品）処理
    Route::post("/items/register", [ItemController::class, "store"])->name("item.store");

    // 商品購入画面表示
    Route::get("/purchase/{itemId}", [PurchaseController::class, "purchase"])->name("item.purchase");

    // 商品決済処理
    Route::post("/purchase/{itemId}", [PurchaseController::class, "payment"])->name("item.purchase.payment");

    // 商品決済処理完了後の処理（Purchasesテーブル更新、プロフィール画面（購入した商品）画面表示）
    Route::get("/purchase/{itemId}/success", [PurchaseController::class, "success"])->name("item.purchase.success");

    // 商品詳細画面表示（いいねの処理）
    Route::post('items/{itemId}/like', [ItemController::class, 'like'])->name('like');

    // 商品詳細画面表示（コメントの処理）
    Route::post('/items/{itemId}/comment', [ItemController::class, 'comment'])->name('comment');
});
