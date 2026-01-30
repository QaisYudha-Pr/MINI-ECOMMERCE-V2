<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    public function toggle(User $user)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Please login first'], 401);
        }

        $me = Auth::user();
        
        if ($me->id === $user->id) {
            return response()->json(['message' => 'You cannot follow yourself'], 422);
        }

        if ($me->isFollowing($user->id)) {
            $me->following()->detach($user->id);
            $status = 'unfollowed';
        } else {
            $me->following()->attach($user->id);
            $status = 'followed';

            // Send notification to the seller
            Notification::create([
                'user_id' => $user->id,
                'title' => 'Pengikut Baru!',
                'message' => "{$me->name} baru saja mengikuti tokomu bolo!",
                'type' => 'follow'
            ]);
        }

        return response()->json([
            'status' => $status,
            'count' => $user->followers()->count()
        ]);
    }
}
