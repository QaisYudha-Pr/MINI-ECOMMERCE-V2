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
    ];
}
