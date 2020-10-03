<?php

namespace Modules\Catalogue\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Modules\Catalogue\Entities\Brand;
use Modules\Catalogue\Entities\Category;
use Modules\Catalogue\Entities\ProductLanguage;
use Modules\Catalogue\Entities\UnitOfMeasure;
use Modules\Catalogue\Observers\BrandObserver;
use Modules\Catalogue\Observers\CategoryObserver;
use Modules\Catalogue\Observers\ProductLanguageObserver;
use Modules\Catalogue\Observers\UnitOfMeasureObserver;
use Modules\Catalogue\Transformers\Repo\ProductResource as ProductResourceRepo;
use Modules\Catalogue\Transformers\Repo\ToppingMenuResource as ToppingMenuResourceRepo;
use Modules\Catalogue\Transformers\Repo\VariantResource as VariantResourceRepo;
use Modules\Catalogue\Transformers\Frontend\VariantByProductResource as VariantResourceFront;
use Modules\Catalogue\Transformers\Repo\VariantValueResource as VariantValueResourceRepo;
use Modules\Catalogue\Helpers\ProductHelper;

class CatalogueServiceProvider extends ServiceProvider
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
        $this->loadMigrationsFrom(module_path('Catalogue', 'Database/Migrations'));

        $this->generateObservers();
    }

    public function generateObservers()
    {
        ProductLanguage::observe(ProductLanguageObserver::class);

        Brand::observe(BrandObserver::class);
        Category::observe(CategoryObserver::class);
        UnitOfMeasure::observe(UnitOfMeasureObserver::class);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);

        App::bind('variant.resource.front', function () {
            return App::make(VariantResourceFront::class);
        });
        App::bind('variant.resource.repo', function () {
            return App::make(VariantResourceRepo::class);
        });
        App::bind('variant.value.resource.repo', function () {
            return App::make(VariantValueResourceRepo::class);
        });
        App::bind('product.resource.repo', function () {
            return App::make(ProductResourceRepo::class);
        });
        App::bind('topping.resource.repo', function () {
            return App::make(ToppingMenuResourceRepo::class);
        });

        App::bind('ProductHelper', function () {
            return App::make(ProductHelper::class);
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
            module_path('Catalogue', 'Config/config.php') => config_path('catalogue.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path('Catalogue', 'Config/config.php'),
            'catalogue'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/catalogue');

        $sourcePath = module_path('Catalogue', 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ], 'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/catalogue';
        }, \Config::get('view.paths')), [$sourcePath]), 'catalogue');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/catalogue');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'catalogue');
        } else {
            $this->loadTranslationsFrom(module_path('Catalogue', 'Resources/lang'), 'catalogue');
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
            app(Factory::class)->load(module_path('Catalogue', 'Database/factories'));
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
