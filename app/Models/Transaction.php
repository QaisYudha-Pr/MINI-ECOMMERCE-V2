<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'courier_id', 'invoice_number', 'total_price', 'status', 
        'payment_method', 'alamat', 'items_details', 'snap_token', 'shipping_fee',
        'courier_name', 'courier_service', 'destination_area_id', 'resi', 'completed_at'
    ];

    protected $casts = [
        'items_details' => 'array',
        'completed_at' => 'datetime',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function courier() {
        return $this->belongsTo(User::class, 'courier_id');
    }

    /**
     * Selesaikan transaksi dan distribusikan dana ke seller (Guarantor Logic)
     */
    public function completeTransaction()
    {
        if ($this->status === 'completed') {
            return;
        }

        \DB::transaction(function () {
            $this->update([
                'status' => 'completed',
                'completed_at' => now(),
            ]);

            // Distribusikan uang ke masing-masing seller
            $items = $this->items_details;
            foreach ($items as $item) {
                $sellerId = $item['seller_id'] ?? null;
                
                // Fallback untuk transaksi lama yang belum ada seller_id di JSON
                if (!$sellerId && isset($item['id'])) {
                    $product = ItemShop::find($item['id']);
                    if ($product) {
                        $sellerId = $product->user_id;
                    }
                }

                if ($sellerId) {
                    $seller = User::find($sellerId);
                    if ($seller) {
                        $seller->increment('balance', $item['total']);
                    }
                }
            }
        });
    }
}