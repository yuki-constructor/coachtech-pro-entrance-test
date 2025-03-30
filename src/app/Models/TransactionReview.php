<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'reviewer_id',
        'reviewee_id',
        'rating'
    ];

    /**
     *  TransactionReviewは1対多の関係でTransactionと関連(一つの取引きにつき、購入者と出品者の互いの評価)
     */
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    /**
     *  TransactionReviewは1対多の関係でUserと関連(userは、購入者として複数の取引で評価をする)
     */
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    /**
     *  TransactionReviewは1対多の関係でUserと関連(userは、出品者として複数の取引で評価を受ける)
     */
    public function reviewee()
    {
        return $this->belongsTo(User::class, 'reviewee_id');
    }
}
