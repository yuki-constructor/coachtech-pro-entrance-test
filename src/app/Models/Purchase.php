<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Purchase extends Model
{
    protected $fillable = [
        'user_id',
        'item_id',
        'purchase_status',
        'payment_method',
        'stripe_payment_id',
        'sending_address',
    ];

    //   Userとのリレーション
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //    Itemとのリレーション
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
