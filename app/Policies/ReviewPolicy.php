<?php

namespace App\Policies;

use App\Models\Review;
use App\Models\User;

class ReviewPolicy
{
    public function create(User $user)
    {
        // Izinkan semua yang login untuk memberikan review
        return true;
    }

    public function delete(User $user, Review $review)
    {
        return $user->hasRole('admin') || $review->user_id === $user->id;
    }
}
