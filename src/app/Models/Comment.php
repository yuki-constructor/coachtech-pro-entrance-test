<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_id',
        'comment',
    ];

    // Commentは1対多の関係でUserと関連（コメントの投稿者）
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Commentは1対多の関係でItemと関連（コメントが紐付いている商品）
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
