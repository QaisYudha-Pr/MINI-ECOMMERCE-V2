<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    // Kolom yang boleh diisi lewat Controller
    protected $fillable = [
        'user_id',
        'invoice_number',
        'total_price',
        'status',
        'items_details',
    ];

    /**
     * Casting System:
     * Ini penting bolo! Supaya kolom 'items_details' yang tadinya teks JSON 
     * otomatis berubah jadi 'Array' pas kita panggil di Laravel.
     */
    protected $casts = [
        'items_details' => 'array',
    ];

    /**
     * Relasi ke User:
     * Biar kita bisa tahu transaksi ini punya siapa.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}