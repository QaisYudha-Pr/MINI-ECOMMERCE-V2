<?php

namespace App\Http\Controllers\Shop\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        $user = $request->user();
        $followedSellers = $user->following()->withCount(['itemShops'])->get();

        // Most popular theme among all users
        $popularTheme = User::select('theme_color', DB::raw('count(*) as total'))
            ->whereNotNull('theme_color')
            ->where('theme_color', '!=', '')
            ->groupBy('theme_color')
            ->orderByDesc('total')
            ->first()?->theme_color ?? 'indigo';
        
        return view('shop.user.profile', [
            'user' => $user,
            'followedSellers' => $followedSellers,
            'popularTheme' => $popularTheme,
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

    /**
     * Update theme color via AJAX (instant switch)
     * Accepts preset names OR custom hex color
     */
    public function updateTheme(Request $request)
    {
        $request->validate([
            'theme_color' => ['required', 'string', 'regex:/^(emerald|rose|amber|slate|indigo|#[0-9a-fA-F]{6})$/'],
        ]);

        $request->user()->update(['theme_color' => $request->theme_color]);

        return response()->json([
            'status' => 'success',
            'theme_color' => $request->theme_color,
        ]);
    }

    /**
     * Update address quickly from navigation modal
     */
    public function updateQuickAddress(Request $request)

    {
        $request->validate([
            'alamat' => 'required|string',
            'area_id' => 'required|string'
        ]);

        $user = Auth::user();

        if ($user) {
            $user->alamat = $request->alamat;
            // Optionally store area_id if you have a column for it
            // For now we'll just store the string address
            $user->save();

            // Save to session so it persists for guest/checkout flow too
            session(['selected_address' => $request->alamat]);
            session(['destination_area_id' => $request->area_id]);

            return response()->json(['status' => 'success']);
        }

        // For guests, just save to session
        session(['selected_address' => $request->alamat]);
        session(['destination_area_id' => $request->area_id]);

        return response()->json(['status' => 'success']);
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
