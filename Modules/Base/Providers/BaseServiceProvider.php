<?php

namespace Modules\Base\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class BaseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        App::bind('pagination', function()
        {
            return App::make(\Modules\Base\Helpers\Pagination::class);
        });

        App::bind('UtilitiesHelper', function()
        {
            return App::make(\Modules\Base\Helpers\UtilitiesHelper::class);
        });

        App::bind('DbHelper', function()
        {
            return App::make(\Modules\Base\Helpers\DbHelper::class);
        });

        App::bind('ExcelExportHelper', function()
        {
            return App::make(\Modules\Base\Helpers\ExcelExportHelper::class);
        });

        App::bind('CacheHelper', function()
        {
            return App::make(\Modules\Base\Helpers\CacheHelper::class);
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(module_path('Base', 'Database/Migrations'));
        $this->registerConfig();
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            module_path('Base', 'Config/config.php') => config_path('base.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path('Base', 'Config/config.php'),
            'base'
        );
    }
}
