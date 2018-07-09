<?php

namespace App\Providers;

use App\Commands\AssetLinkCommand;
use App\Commands\ModelCommand;
use App\Composers\BreadCrumbsComposer;
use App\Composers\DataTableComposer;
use App\Composers\MenusComposer;
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
        view()->composer(['admin::common.breadcrumbs'], BreadCrumbsComposer::class);
        view()->composer(['admin::common.datatable'], DataTableComposer::class);
        view()->composer(['admin::common.menu'], MenusComposer::class);

        if ($this->app->runningInConsole()) {
            $this->commands([AssetLinkCommand::class, ModelCommand::class]);
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
