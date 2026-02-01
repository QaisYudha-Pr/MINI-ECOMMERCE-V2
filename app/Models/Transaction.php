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
        'courier_name', 'courier_service', 'destination_area_id', 'resi', 'completed_at', 'delivery_proof'
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

            // --- HITUNG KOMISI SELLER (OPSI B) ---
            // Kita ambil persentase dari setting (Default 5%)
            $commissionPercent = (float)(\App\Models\SiteSetting::where('key', 'seller_commission_pct')->value('value') ?? 5);
            
            // Komisi dihitung dari HARGA BARANG (total_price - shipping_fee - admin_fee)
            $itemsPrice = $this->total_price - $this->shipping_fee - $this->admin_fee;
            $commissionAmount = ($itemsPrice * $commissionPercent) / 100;

            // Distribusikan uang ke seller
            // Seller menerima = Harga Barang + Ongkir - Komisi
            $netSellerAmount = ($itemsPrice + $this->shipping_fee) - $commissionAmount;
            
            if ($this->seller_id) {
                $seller = User::find($this->seller_id);
                if ($seller) {
                    $seller->increment('balance', $netSellerAmount);
                }
            }

            // Keuntungan Platform = Admin Fee + Komisi Seller
            $admin = User::role('admin')->first();
            if ($admin) {
                $admin->increment('platform_balance', $this->admin_fee + $commissionAmount);
            }
        });
    }
}