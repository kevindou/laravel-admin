<?php

namespace App\Providers;

use App\Console\Commands\AssetLinkCommand;
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
        if ($this->app->runningInConsole()) {
            $this->commands([
                AssetLinkCommand::class,
            ]);
        }

        $this->loadViewsFrom(admin_path('resources/views'), 'admin');
        $this->publishes([admin_path('config/admin.php') => config_path('config/admin.php')], 'config');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->publishes([admin_path('config/admin.php') => config_path('config/admin.php')], 'config');
    }
}
