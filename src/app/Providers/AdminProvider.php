<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AdminProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([admin_path('config/admin.php') => config_path('config/admin.php')], 'config');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }
}
