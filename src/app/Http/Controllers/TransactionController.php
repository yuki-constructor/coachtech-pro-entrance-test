<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionMessageRequest;
use App\Models\Transaction;
use App\Models\TransactionMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    // 取引チャット画面表示
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
                $query->where(function ($q) use ($user) {
                    // 購入者または出品者として関わる取引を取得
                    $q->where('user_id', $user->id) // 購入者として
                        ->orWhereHas('item', function ($q2) use ($user) {
                            $q2->where('user_id', $user->id); // 出品者として
                        });
                });
            })
            ->whereNull('completed_at') // 取引中のものだけ
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

        return view('transaction.show', [
            'transaction' => $transaction,
            'partner' => $partner,
            'messages' => $messages,
            'otherTransactions' => $otherTransactions,
        ]);
    }

    // メッセージ送信
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

    // メッセージ編集
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

    // メッセージ削除
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

    // 取引を完了にする処理
    public function complete($transactionId)
    {
        $transaction = Transaction::findOrFail($transactionId);

        // ログイン中のユーザーが購入者か確認
        if (Auth::id() === $transaction->purchase->user_id) {
            $transaction->status = 1;
            $transaction->completed_at = now();
            $transaction->save();
        }

        return redirect()->route('transaction.show', $transactionId);
    }
}
