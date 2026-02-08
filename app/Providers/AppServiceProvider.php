<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator; 
use Carbon\Carbon; 


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
    Paginator::useBootstrapFive(); // <--- Tambahkan ini di dalam function boot
    // 2. Tambahkan pengaturan locale Carbon
        config(['app.locale' => 'id']);
        Carbon::setLocale('id');
        date_default_timezone_set('Asia/Jakarta');

}


}
