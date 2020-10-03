<?php

namespace Modules\Gallery\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Modules\Gallery\Helpers\GalleryErrorsHelper;
use Modules\Gallery\Helpers\GalleryHelper;

class GalleryServiceProvider extends ServiceProvider
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
        $this->loadMigrationsFrom(module_path('Gallery', 'Database/Migrations'));
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerFacade();

        $this->app->register(RouteServiceProvider::class);
    }


    public function registerFacade()
    {
        \App::bind('GalleryErrorsHelper', function () {
            return \App::make(GalleryErrorsHelper::class);
        });

        \App::bind('GalleryHelper', function () {
            return \App::make(GalleryHelper::class);
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
            module_path('Gallery', 'Config/config.php') => config_path('gallery.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path('Gallery', 'Config/config.php'),
            'gallery'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/gallery');

        $sourcePath = module_path('Gallery', 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ], 'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/gallery';
        }, \Config::get('view.paths')), [$sourcePath]), 'gallery');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/gallery');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'gallery');
        } else {
            $this->loadTranslationsFrom(module_path('Gallery', 'Resources/lang'), 'gallery');
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
            app(Factory::class)->load(module_path('Gallery', 'Database/factories'));
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
