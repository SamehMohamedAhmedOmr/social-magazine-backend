<?php

namespace Modules\Sections\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Modules\Sections\Helpers\SectionsCache;

class SectionsServiceProvider extends ServiceProvider
{
    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->registerFactories();
        $this->loadMigrationsFrom(module_path('Sections', 'Database/Migrations'));
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);

        $this->registerHelpers();
    }

    private function registerHelpers(){
        \App::bind('SectionsCache', function()
        {
            return \App::make(SectionsCache::class);
        });
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            module_path('Sections', 'Config/config.php') => config_path('sections.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path('Sections', 'Config/config.php'), 'sections'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/sections');

        $sourcePath = module_path('Sections', 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/sections';
        }, \Config::get('view.paths')), [$sourcePath]), 'sections');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/sections');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'sections');
        } else {
            $this->loadTranslationsFrom(module_path('Sections', 'Resources/lang'), 'sections');
        }
    }

    /**
     * Register an additional directory of factories.
     *
     * @return void
     */
    public function registerFactories()
    {
        if (! app()->environment('production') && $this->app->runningInConsole()) {
            app(Factory::class)->load(module_path('Sections', 'Database/factories'));
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
