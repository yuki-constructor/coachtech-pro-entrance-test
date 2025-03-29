<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'purchase_id',
        'status',
        'completed_at',
    ];

    // Transactionは1対1の関係でPurchaseと関連
    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    // Transactionは1対多の関係でMessageと関連(取引きに紐づいた複数のメッセージ)
    public function transactionMessages()
    {
        return $this->hasMany(TransactionMessage::class);
    }
}
