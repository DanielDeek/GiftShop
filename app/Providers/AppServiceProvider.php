<?php

namespace App\Providers;

use App\Models\Cart;
use Carbon\Laravel\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProviderr;
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
        Paginator::useBootstrap();
        View::composer('*', function ($view) {
        $count = 0;
        if (auth()->check()) {
            $count = Cart::where('user_id', auth()->id())->count();
        }
        $view->with('count', $count);
    });
    }
}
