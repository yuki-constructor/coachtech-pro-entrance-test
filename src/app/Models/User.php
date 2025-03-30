<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_image',
        'postal_code',
        'address',
        'building',
        'is_first_login',
        'email_verification_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Userは1対多の関係でPurchaseと関連（ユーザーの購入情報）
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    // Userは多対多の関係でItemと関連（ユーザーがいいねした商品）
    public function likeItem()
    {
        return $this->belongsToMany(Item::class, 'likes')->withTimestamps();
    }

    // Userは1対多の関係でItemと関連（ユーザーが出品した商品）
    public function items()
    {
        return $this->hasMany(Item::class);
    }

    //Userは1対多の関係でCommentと関連（ユーザーが投稿したコメント）
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // Userは1対多の関係でTransactionReviewと関連(Userが「レビュアー」 の立場で書いたレビュー一覧を取得)
    public function givenReviews()
    {
        return $this->hasMany(TransactionReview::class, 'reviewer_id');
    }

    // Userは1対多の関係でTransactionReviewと関連(Userが「レビュー対象」 の立場で受けたレビュー一覧を取得)
    public function receivedReviews()
    {
        return $this->hasMany(TransactionReview::class, 'reviewee_id');
    }
}
