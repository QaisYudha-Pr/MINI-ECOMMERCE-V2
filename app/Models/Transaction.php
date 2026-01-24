<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'invoice_number', 'total_price', 'status', 
        'payment_method', 'alamat', 'items_details', 'snap_token', 'shipping_fee'
    ];

    protected $casts = [
        'items_details' => 'array', // Supaya JSON otomatis jadi Array di PHP
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}