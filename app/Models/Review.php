<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    //
    protected $fillable = ['user_id', 'item_shop_id', 'rating', 'comment', 'photo'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function itemShop()
    {
        return $this->belongsTo(ItemShop::class, 'item_shop_id');
    }
}
