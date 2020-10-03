<?php

namespace Modules\Configuration\Providers;

use App;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Modules\Configuration\Helpers\TenantHelper;

class ConfigurationServiceProvider extends ServiceProvider
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
        $this->loadMigrationsFrom(module_path('Configuration', 'Database/Migrations'));
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerHelpers();

        $this->app->register(RouteServiceProvider::class);
    }

    public function registerHelpers(){
        App::bind('TenantHelper', function () {
            return App::make(TenantHelper::class);
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
            module_path('Configuration', 'Config/config.php') => config_path('configuration.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path('Configuration', 'Config/config.php'), 'configuration'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/configuration');

        $sourcePath = module_path('Configuration', 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/configuration';
        }, \Config::get('view.paths')), [$sourcePath]), 'configuration');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/configuration');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'configuration');
        } else {
            $this->loadTranslationsFrom(module_path('Configuration', 'Resources/lang'), 'configuration');
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
            app(Factory::class)->load(module_path('Configuration', 'Database/factories'));
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
