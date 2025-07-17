<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View; // Import View Facade
use App\Models\Order; // Import model Order
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
    // Ganti 'admin.*' menjadi 'layouts.navigation'
    View::composer('layouts.navigation', function ($view) {
        
        // Cek dulu apakah ada user yang login
        if (Auth::check() && Auth::user()->role == 'admin') {
            // Hitung pesanan dengan status 'unpaid' atau 'pending'
            $newOrdersCount = Order::whereIn('status', ['unpaid', 'pending'])->count();
        } else {
            // Jika bukan admin atau tidak login, set hitungan ke 0
            $newOrdersCount = 0;
        }

        // Kirim variabel $newOrdersCount ke view
        $view->with('newOrdersCount', $newOrdersCount);
    });
}
}