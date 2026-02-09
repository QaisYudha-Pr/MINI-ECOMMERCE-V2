<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Voucher extends Model
{
    protected $fillable = [
        'code',
        'name',
        'description',
        'type',
        'value',
        'min_purchase',
        'max_discount',
        'usage_limit',
        'usage_count',
        'per_user_limit',
        'seller_id',
        'starts_at',
        'expires_at',
        'is_active',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
        'value' => 'decimal:2',
        'min_purchase' => 'decimal:2',
        'max_discount' => 'decimal:2',
    ];

    // Voucher types
    const TYPE_PERCENTAGE = 'percentage';
    const TYPE_FIXED = 'fixed';
    const TYPE_FREE_SHIPPING = 'free_shipping';

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function usages()
    {
        return $this->hasMany(VoucherUsage::class);
    }

    // Check if voucher is valid
    public function isValid(): bool
    {
        if (!$this->is_active) return false;
        
        $now = Carbon::now();
        
        if ($this->starts_at && $now->lt($this->starts_at)) return false;
        if ($this->expires_at && $now->gt($this->expires_at)) return false;
        if ($this->usage_limit && $this->usage_count >= $this->usage_limit) return false;
        
        return true;
    }

    // Check if user can use this voucher
    public function canBeUsedBy(int $userId): bool
    {
        if (!$this->isValid()) return false;
        
        $userUsageCount = $this->usages()->where('user_id', $userId)->count();
        
        return $userUsageCount < $this->per_user_limit;
    }

    // Calculate discount for given subtotal
    public function calculateDiscount(float $subtotal, float $shippingCost = 0): float
    {
        if ($subtotal < $this->min_purchase) return 0;

        switch ($this->type) {
            case self::TYPE_PERCENTAGE:
                $discount = $subtotal * ($this->value / 100);
                if ($this->max_discount) {
                    $discount = min($discount, $this->max_discount);
                }
                return $discount;
                
            case self::TYPE_FIXED:
                return min($this->value, $subtotal);
                
            case self::TYPE_FREE_SHIPPING:
                return $shippingCost;
                
            default:
                return 0;
        }
    }

    // Scope for active vouchers
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>=', now());
            })
            ->where(function ($q) {
                $q->whereNull('usage_limit')->orWhereColumn('usage_count', '<', 'usage_limit');
            });
    }

    // Platform vouchers (created by admin)
    public function scopePlatform($query)
    {
        return $query->whereNull('seller_id');
    }

    // Seller vouchers
    public function scopeBySeller($query, $sellerId)
    {
        return $query->where('seller_id', $sellerId);
    }
}
