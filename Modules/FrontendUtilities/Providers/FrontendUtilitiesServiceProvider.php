<?php

namespace Modules\FrontendUtilities\Providers;

use App;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Modules\FrontendUtilities\Entities\Banner;
use Modules\FrontendUtilities\Entities\Collection;
use Modules\FrontendUtilities\Entities\Promocode;
use Modules\FrontendUtilities\Observers\BannersObserver;
use Modules\FrontendUtilities\Observers\CollectionObserver;
use Modules\FrontendUtilities\Observers\PromocodeObserver;

class FrontendUtilitiesServiceProvider extends ServiceProvider
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
        $this->loadMigrationsFrom(module_path('FrontendUtilities', 'Database/Migrations'));

        $this->generateObservers();
    }

    public function generateObservers()
    {
        Banner::observe(BannersObserver::class);
        Promocode::observe(PromocodeObserver::class);
        Collection::observe(CollectionObserver::class);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        App::bind('PromocodeValidationHelper', function () {
            return App::make(\Modules\FrontendUtilities\Helpers\PromocodeValidationHelper::class);
        });

        App::bind('PromocodeErrorsHelper', function () {
            return App::make(\Modules\FrontendUtilities\Helpers\PromocodeErrorsHelper::class);
        });

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
            module_path('FrontendUtilities', 'Config/config.php') => config_path('frontendutilities.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path('FrontendUtilities', 'Config/config.php'),
            'frontendutilities'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/frontendutilities');

        $sourcePath = module_path('FrontendUtilities', 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ], 'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/frontendutilities';
        }, \Config::get('view.paths')), [$sourcePath]), 'frontendutilities');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/frontendutilities');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'frontendutilities');
        } else {
            $this->loadTranslationsFrom(module_path('FrontendUtilities', 'Resources/lang'), 'frontendutilities');
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
            app(Factory::class)->load(module_path('FrontendUtilities', 'Database/factories'));
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
