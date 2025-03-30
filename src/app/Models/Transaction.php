<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'purchase_id',
        'status',
        'buyer_completed_at',
        'seller_completed_at',
    ];

    /**
     *  Transactionは1対1の関係でPurchaseと関連
     */
    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    /**
     *  Transactionは1対多の関係でTransactionMessageと関連(取引きに紐づいた複数のメッセージ)
     */
    public function transactionMessages()
    {
        return $this->hasMany(TransactionMessage::class);
    }

    /**
     *  Transactionは1対多の関係でTransactionReviewと関連(一つの取引きにつき、購入者と出品者の互いの評価)
     */
    public function transactionReview()
    {
        return $this->hasMany(TransactionReview::class);
    }
}
