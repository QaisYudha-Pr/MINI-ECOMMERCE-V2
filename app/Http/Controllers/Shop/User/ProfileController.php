<?php

namespace App\Http\Controllers\Shop\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        $user = $request->user();
        $followedSellers = $user->following()->withCount(['itemShops'])->get();
        
        return view('shop.user.profile', [
            'user' => $user,
            'followedSellers' => $followedSellers,
        ]);
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => ['required', 'image', 'max:1024'],
        ]);

        $user = $request->user();

        if ($request->hasFile('avatar')) {
            if ($user->avatar && file_exists(public_path($user->avatar))) {
                unlink(public_path($user->avatar));
            }

            $file = $request->file('avatar');
            $filename = 'avatar_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/avatars'), $filename);

            $user->avatar = 'uploads/avatars/' . $filename;
            $user->save();
        }

        return back()->with('status', 'profile-updated');
    }

    public function updateBanner(Request $request)
    {
        $request->validate([
            'banner' => ['required', 'image', 'max:2048'],
        ]);

        $user = $request->user();

        if ($request->hasFile('banner')) {
            if ($user->banner && file_exists(public_path($user->banner))) {
                unlink(public_path($user->banner));
            }

            $file = $request->file('banner');
            $filename = 'banner_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/banners'), $filename);

            $user->banner = 'uploads/banners/' . $filename;
            $user->save();
        }

        return back()->with('status', 'profile-updated');
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
