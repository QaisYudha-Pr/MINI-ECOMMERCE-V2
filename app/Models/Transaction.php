<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'seller_id', 'courier_id', 'courier_service_id', 'invoice_number', 'parent_invoice', 
        'total_price', 'status', 'payment_method', 'alamat', 'items_details', 
        'snap_token', 'shipping_fee', 'admin_fee',
        'courier_name', 'courier_service', 'destination_area_id', 'resi', 'completed_at'
    ];

    protected $casts = [
        'items_details' => 'array',
        'completed_at' => 'datetime',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function seller() {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function courier() {
        return $this->belongsTo(User::class, 'courier_id');
    }

    public function courierService() {
        return $this->belongsTo(Courier::class, 'courier_service_id');
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

            // Distribusikan uang ke seller
            // Yang masuk ke seller HANYA (Total - Admin Fee)
            // Karena total_price di tiap pesanan (setelah split) sudah termasuk ongkir + harga barang
            $netAmount = $this->total_price - $this->admin_fee;
            
            if ($this->seller_id) {
                $seller = User::find($this->seller_id);
                if ($seller) {
                    $seller->increment('balance', $netAmount);
                }
            } else {
                // Fallback untuk transaksi lama (split manual dari items_details)
                $items = $this->items_details;
                foreach ($items as $item) {
                    $sellerId = $item['seller_id'] ?? null;
                    if ($sellerId) {
                        $seller = User::find($sellerId);
                        if ($seller) {
                            $seller->increment('balance', $item['total']);
                        }
                    }
                }
            }
        });
    }
}