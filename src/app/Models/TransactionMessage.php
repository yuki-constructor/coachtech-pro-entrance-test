<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionMessage extends Model
{
    protected $fillable = [
        'transaction_id',
        'user_id',
        'message',
        'image_path',
        'is_read',
        'is_sent',
    ];

    // TransactionMessageは1対多の関係でTransactionと関連(取引きに紐づいた複数のメッセージ)
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    // TransactionMessageは1対多の関係でUserと関連(userは複数のメッセージを投稿可能)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
