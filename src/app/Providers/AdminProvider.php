<?php

namespace App\Providers;

use App\Commands\AssetLinkCommand;
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
            $this->commands([AssetLinkCommand::class]);
        }

        $this->loadViewsFrom(admin_path('resources/views'), 'admin');
        $this->loadMigrationsFrom(admin_path('database/migrations'));
        $this->publishes([
            admin_path('config/admin.php')               => config_path('admin.php'),
            admin_path('resources/lang/zh-CN/admin.php') => resource_path('lang/zh-CN/admin.php'),
            admin_path('resources/lang/zh-CN/error.php') => resource_path('lang/zh-CN/error.php')
        ], 'config');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->publishes([admin_path('config/admin.php') => config_path('admin.php')], 'config');
    }
}
