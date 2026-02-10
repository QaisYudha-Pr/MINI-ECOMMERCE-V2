<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SellerReview extends Model
{
    protected $fillable = [
        'seller_id',
        'buyer_id',
        'transaction_id',
        'rating',
        'comment',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    // After creating a review, update seller's rating
    protected static function booted()
    {
        static::created(function ($review) {
            $review->updateSellerRating();
        });

        static::updated(function ($review) {
            $review->updateSellerRating();
        });

        static::deleted(function ($review) {
            $review->updateSellerRating();
        });
    }

    public function updateSellerRating(): void
    {
        $seller = $this->seller;
        if ($seller) {
            $seller->recalculateSellerRating();
        }
    }
}
