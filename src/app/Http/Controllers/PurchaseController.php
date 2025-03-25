<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;
use App\Http\Requests\PurchaseRequest;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Illuminate\Support\Facades\Auth;


class PurchaseController extends Controller
{
    // 商品購入画面表示
    public function purchase($itemId)
    {
        $item = Item::findOrFail($itemId);
        $user = Auth::user();

        return view('items.purchase', ['item' => $item, "user" => $user]);
    }


    // 商品購入処理
    public function payment(Request $request, $itemId)
    {
        // 購入者、商品情報などを取得
        $item = Item::findOrFail($itemId);
        $user = Auth::user();

        // 支払い方法の選択を取得
        $paymentMethod = $request->input('payment_method');

        // Stripeの秘密鍵を設定
        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

        // Stripeの決済セッションを作成
        $session = Session::create([

            'payment_method_types' => [$paymentMethod], // 支払い方法に応じて設定
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'jpy',
                        'product_data' => [
                            'name' => $item->item_name,
                        ],
                        'unit_amount' => $item->price, // 金額は最小単位（円単位）
                    ],
                    'quantity' => 1,
                ],
            ],
            'mode' => 'payment',
            'success_url' => url("/purchase/{$item->id}/success?purchase_id={CHECKOUT_SESSION_ID}"),
        ]);

        // 購入テーブルに新規データを作成
        $purchase = Purchase::create([

            'user_id' => $user->id,
            'item_id' => $item->id,
            'purchase_status' => 0, // 購入ステータスを「０：未完了」で登録
            'payment_method' => $paymentMethod == 'card' ? 1 : 2, // 1:カード, 2:コンビニ
            'stripe_payment_id' => $session->id, // Stripeの決済ID
            'sending_address' => '〒' . trim($user->postal_code) . ' ' . trim($user->address) . ' ' . trim($user->building),  // 郵便番号 + 住所 + 建物名を結合して保存
        ]);
        // Stripeの決済ページにリダイレクト
        return redirect($session->url);
    }



    public function success(Request $request)
    {
        // リクエストからpurchase_idを取得
        $purchase = Purchase::where('stripe_payment_id', $request->input('purchase_id'))->first();


        // Stripeの秘密鍵を設定
        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

        // セッションIDを使って決済情報を取得
        $session = Session::retrieve($purchase->stripe_payment_id);

        // 支払いが完了しているかをチェック
        if ($session->payment_status == 'paid') {

            // 購入ステータスを「完了」に更新
            $purchase->update(['purchase_status' => 1]); // 1: 完了
        }

        // プロフィール画面_購入した商品一覧を表示
        return to_route('profile.show.buy');
    }
}
