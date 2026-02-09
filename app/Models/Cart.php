<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = [
        'user_id',
        'item_shop_id',
        'quantity',
        'notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function item()
    {
        return $this->belongsTo(ItemShop::class, 'item_shop_id');
    }

    // Calculate subtotal for this cart item
    public function getSubtotalAttribute(): float
    {
        return $this->item->price * $this->quantity;
    }

    // Static method to get user's cart total
    public static function getCartTotal(int $userId): float
    {
        return self::where('user_id', $userId)
            ->with('item')
            ->get()
            ->sum(fn($cart) => $cart->subtotal);
    }

    // Static method to get cart count
    public static function getCartCount(int $userId): int
    {
        return self::where('user_id', $userId)->sum('quantity');
    }

    // Add to cart (or update quantity if exists)
    public static function addItem(int $userId, int $itemId, int $quantity = 1, ?string $notes = null): self
    {
        $cart = self::firstOrNew([
            'user_id' => $userId,
            'item_shop_id' => $itemId,
        ]);

        $cart->quantity = $cart->exists ? $cart->quantity + $quantity : $quantity;
        $cart->notes = $notes ?? $cart->notes;
        $cart->save();

        return $cart;
    }
}
