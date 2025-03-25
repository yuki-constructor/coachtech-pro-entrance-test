<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'user_id',
        'item_name',
        'item_image',
        'brand',
        'price',
        'description'
    ];

    // Itemは多対多の関係でCategoryと関連（アイテムのカテゴリー）
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'categories_items')->withTimestamps();
    }

    // Itemは多対多の関係でConditionと関連（アイテムのコンディション）
    public function conditions()
    {
        return $this->belongsToMany(Condition::class, 'conditions_items')->withTimestamps();
    }

    // Itemは1対多の関係でPurchaseと関連（アイテムが購入された情報）
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    // Itemは多対多の関係でUserと関連（アイテムにいいねしたユーザー）
    public function userLike()
    {
        return $this->belongsToMany(User::class, 'likes')->withTimestamps();
    }

    // Itemは１対多の関係でCommentと関連（アイテムに紐づいたコメント）
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
