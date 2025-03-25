<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['category_name'];

    // Categoryは多対多の関係でItemと関連
    public function items()
    {
        return $this->belongsToMany(Item::class, 'categories_items')->withTimestamps();
    }
}
