<?php

namespace App\Providers;

use App\Models\Notifikasi;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        Paginator::useTailwind();

        View::composer(['layouts.admin', 'layouts.petugas', 'layouts.peminjam', 'partials.notification_dropdown'], function ($view) {
            $notifications = [];
            $unreadCount = 0;

            if (Auth::check()) {
                $notifications = Notifikasi::where('user_id', Auth::id())
                    ->latest()
                    ->take(5)
                    ->get();

                $unreadCount = Notifikasi::where('user_id', Auth::id())
                    ->where('is_read', false)
                    ->count();
            }

            $view->with(compact('notifications', 'unreadCount'));
        });
    }
}
