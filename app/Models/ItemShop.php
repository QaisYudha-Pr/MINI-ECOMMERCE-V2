<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemShop extends Model
{
    //
    protected $table = 'item_shops';

    protected $fillable = [
        'nama_barang',
        'deskripsi',
        'harga',
        'stok',
        'kategori',
        'gambar',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'item_shop_id');
    }

    // Helper untuk ambil rata-rata rating
    public function getRatingsAvgAttribute()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }
}
