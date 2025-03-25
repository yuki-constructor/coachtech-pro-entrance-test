<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Condition extends Model
{
    protected $fillable = ['condition_name'];

    // Conditionは多対多の関係でItemと関連
    public function items()
    {
        return $this->belongsToMany(Item::class, 'conditions_items')->withTimestamps();
    }
}
