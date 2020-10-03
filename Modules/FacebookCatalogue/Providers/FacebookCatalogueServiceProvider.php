<?php

namespace Modules\FacebookCatalogue\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;

class FacebookCatalogueServiceProvider extends ServiceProvider
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
        $this->loadMigrationsFrom(module_path('FacebookCatalogue', 'Database/Migrations'));
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            module_path('FacebookCatalogue', 'Config/config.php') => config_path('facebookcatalogue.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path('FacebookCatalogue', 'Config/config.php'),
            'facebookcatalogue'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/facebookcatalogue');

        $sourcePath = module_path('FacebookCatalogue', 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ], 'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/facebookcatalogue';
        }, \Config::get('view.paths')), [$sourcePath]), 'facebookcatalogue');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/facebookcatalogue');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'facebookcatalogue');
        } else {
            $this->loadTranslationsFrom(module_path('FacebookCatalogue', 'Resources/lang'), 'facebookcatalogue');
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
            app(Factory::class)->load(module_path('FacebookCatalogue', 'Database/factories'));
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
