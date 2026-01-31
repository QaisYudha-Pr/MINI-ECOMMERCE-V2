<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use App\Models\SiteSetting;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Implicitly grant "admin" role all permissions
        // This works in the app by using gate-related functions like auth()->user->can() and @can()
        Gate::before(function ($user, $ability) {
            return $user->hasRole('admin') ? true : null;
        });

        // Share site settings globally
        if (Schema::hasTable('site_settings')) {
            $settings = SiteSetting::all()->pluck('value', 'key')->toArray();
            View::share('siteSettings', $settings);
        }

        // Global Notifications for All Users
        View::composer(['layouts.admin', 'layouts.courier'], function ($view) {
            if (Auth::check()) {
                $user = Auth::user();
                $notifications = Notification::where('user_id', $user->id)
                    ->where('is_read', false)
                    ->latest()
                    ->take(10)
                    ->get();
                
                $view->with('globalNotifications', $notifications);
            }
        });
    }
}
