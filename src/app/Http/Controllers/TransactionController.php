<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionMessageRequest;
use App\Models\Transaction;
use App\Models\TransactionMessage;
use App\Models\TransactionReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    /**
     *  取引チャット画面表示
     */
    public function show($transactionId)
    {
        $user = Auth::user();

        $transaction = Transaction::findOrFail($transactionId);

        // 取引相手のユーザーを特定
        $isSeller = $transaction->purchase->item->user_id === $user->id; // ログインユーザーが出品者か判定
        $partner = $isSeller ? $transaction->purchase->user : $transaction->purchase->item->user; // 取引相手ユーザーを特定

        // 他の取引を取得 (購入者 or 出品者として関与)
        $otherTransactions = Transaction::where('id', '!=', $transactionId)
            ->whereHas('purchase', function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhereHas('item', function ($q) use ($user) {
                        $q->where('user_id', $user->id);
                    });
            })
            ->where(function ($query) {
                $query->whereNull('buyer_completed_at')
                    ->orWhereNull('seller_completed_at');
            })
            ->get();

        // チャットメッセージ取得 (昇順)
        $messages = $transaction->transactionMessages()->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($message) use ($user, $transaction) {
                // ログインユーザーのID
                $authUserId = $user->id;

                // 取引中の商品を購入したユーザー
                $buyerId = $transaction->purchase->user_id;

                // 取引中の商品を出品したユーザー
                $sellerId = $transaction->purchase->item->user_id;

                // メッセージ送信者が取引相手ユーザーかを判定
                $isPartner = ($message->user_id !== $authUserId);

                // クラスの判定
                $chatClass = $isPartner ? 'chat-partner' : 'chat-myself';

                // メッセージ送信者情報
                $user = $message->user;

                return [
                    'chatClass' => $chatClass,
                    'user' => $user,
                    'message' => $message,
                ];
            });

        // 出品者の取引を取得
        $transactions = Transaction::whereHas('purchase.item', function ($query) use ($user) {
            $query->where('user_id', $user->id); // 出品者であること
        })->get();

        // 購入者が評価済みで出品者が未評価の取引があるかチェック
        $unreviewedTransaction = $transactions->first(function ($transaction) {
            return $transaction->buyer_completed_at && !$transaction->seller_completed_at;
        });

        if ($unreviewedTransaction) {
            session()->flash('showReviewModal', true);
        }

        return view('transaction.show', [
            'transaction' => $transaction,
            'partner' => $partner,
            'messages' => $messages,
            'otherTransactions' => $otherTransactions,
        ]);
    }

    /**
     *  メッセージ送信処理
     */
    public function store(StoreTransactionMessageRequest $request, $transactionId)
    {
        $transaction = Transaction::findOrFail($transactionId);
        $imagePath = null;

        // 画像がアップロードされた場合
        if ($request->hasFile('chat_image')) {
            $imagePath = $request->file('chat_image')->store('chat_images', 'public');
        }

        // メッセージをtransaction_messagesテーブルに保存
        TransactionMessage::create([
            'transaction_id' => $transaction->id,
            'user_id' => Auth::id(),
            'message' => $request->message,
            'image_path' => $imagePath,
        ]);

        return redirect()->route('transaction.show', $transaction->id);
    }

    /**
     *  メッセージ編集処理
     */
    public function update(StoreTransactionMessageRequest $request, $transactionId, $messageId)
    {
        $transaction = Transaction::findOrFail($transactionId);
        $message = TransactionMessage::findOrFail($messageId);

        // ログイン中のユーザーのメッセージのみ編集可能
        if ($message->user_id !== Auth::id()) {
            return redirect()->back();
        }

        $message->update([
            'message' => $request->message,
        ]);

        return redirect()->route('transaction.show', $transactionId);
    }

    /**
     *  メッセージ削除処理
     */
    public function destroy($transactionId, $messageId)
    {
        $transaction = Transaction::findOrFail($transactionId);
        $message = TransactionMessage::findOrFail($messageId);

        // ログイン中のユーザーのメッセージのみ削除可能
        if ($message->user_id !== Auth::id()) {
            return redirect()->back();
        }

        $message->delete();
        return redirect()->route('transaction.show', $transactionId);
    }

    /**
     *  購入者ユーザーが取引を完了にする処理（出品者ユーザーが購入者ユーザーを評価するまで取引完了ではない）
     */
    public function complete($transactionId)
    {
        $transaction = Transaction::findOrFail($transactionId);

        // ログイン中のユーザーが購入者か確認
        if (Auth::id() === $transaction->purchase->user_id) {
            // $transaction->status = 1;
            $transaction->buyer_completed_at = now();
            $transaction->save();
        }

        return redirect()->route('transaction.show', $transactionId)->with('showReviewModal', true);
    }

    /**
     *  取引相手の評価投稿処理
     */
    public function submitReview(Request $request, $transactionId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $transaction = Transaction::findOrFail($transactionId);
        $reviewerId = Auth::id();
        $revieweeId = ($reviewerId === $transaction->purchase->user_id) ? $transaction->purchase->item->user_id : $transaction->purchase->user_id;

        TransactionReview::create([
            'transaction_id' => $transactionId,
            'reviewer_id' => $reviewerId,
            'reviewee_id' => $revieweeId,
            'rating' => $request->rating,
        ]);

        if ($reviewerId === $transaction->purchase->user_id) {
            return redirect()->route('items.index')->with('reviewSubmitted', true);
        } else {
            $transaction->seller_completed_at = now();
            $transaction->status = 1;
            $transaction->save();
            return redirect()->route('items.index')->with('transactionCompleted', true);
        }
    }
}
