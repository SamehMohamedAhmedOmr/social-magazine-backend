<?php

namespace Modules\PreArticle\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Modules\PreArticle\Helpers\ArticleSubjectCollection;
use Modules\PreArticle\Helpers\PreArticleCache;
use Modules\PreArticle\Helpers\StatusFilterCollection;
use Modules\PreArticle\Helpers\StatusFilterKey;
use Modules\PreArticle\Helpers\StatusListCollection;
use Modules\PreArticle\Helpers\StatusListHelper;
use Modules\PreArticle\Helpers\StatusTypesHelper;

class PreArticleServiceProvider extends ServiceProvider
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
        $this->loadMigrationsFrom(module_path('PreArticle', 'Database/Migrations'));
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

    protected function registerHelpers()
    {

        \App::bind('PreArticleCache', function () {
            return \App::make(PreArticleCache::class);
        });

        \App::bind('StatusTypesHelper', function () {
            return \App::make(StatusTypesHelper::class);
        });

        \App::bind('StatusListHelper', function () {
            return \App::make(StatusListHelper::class);
        });

        \App::bind('StatusListCollection', function () {
            return \App::make(StatusListCollection::class);
        });

        \App::bind('StatusFilterCollection', function () {
            return \App::make(StatusFilterCollection::class);
        });

        \App::bind('StatusFilterKey', function () {
            return \App::make(StatusFilterKey::class);
        });

        \App::bind('ArticleSubjectCollection', function () {
            return \App::make(ArticleSubjectCollection::class);
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
            module_path('PreArticle', 'Config/config.php') => config_path('prearticle.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path('PreArticle', 'Config/config.php'), 'prearticle'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/prearticle');

        $sourcePath = module_path('PreArticle', 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/prearticle';
        }, \Config::get('view.paths')), [$sourcePath]), 'prearticle');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/prearticle');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'prearticle');
        } else {
            $this->loadTranslationsFrom(module_path('PreArticle', 'Resources/lang'), 'prearticle');
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
            app(Factory::class)->load(module_path('PreArticle', 'Database/factories'));
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
