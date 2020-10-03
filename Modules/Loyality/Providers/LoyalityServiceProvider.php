<?php

namespace Modules\Loyality\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Modules\Loyality\Helpers\ValidationHelper;
use Illuminate\Support\Facades\App;

class LoyalityServiceProvider extends ServiceProvider
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
        $this->loadMigrationsFrom(module_path('Loyality', 'Database/Migrations'));
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);

        App::bind('loyalityValidation', function () {
            return App::make(ValidationHelper::class);
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
            module_path('Loyality', 'Config/config.php') => config_path('loyality.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path('Loyality', 'Config/config.php'),
            'loyality'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/loyality');

        $sourcePath = module_path('Loyality', 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ], 'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/loyality';
        }, \Config::get('view.paths')), [$sourcePath]), 'loyality');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/loyality');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'loyality');
        } else {
            $this->loadTranslationsFrom(module_path('Loyality', 'Resources/lang'), 'loyality');
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
            app(Factory::class)->load(module_path('Loyality', 'Database/factories'));
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
