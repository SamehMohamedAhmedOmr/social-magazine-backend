<?php

namespace Modules\WareHouse\Providers;

use App;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Modules\WareHouse\Entities\Order\Order;
use Modules\WareHouse\Entities\PaymentEntry;
use Modules\WareHouse\Entities\PurchaseInvoice;
use Modules\WareHouse\Entities\PurchaseOrder;
use Modules\WareHouse\Entities\PurchaseReceipt;
use Modules\WareHouse\Entities\Stock;
use Modules\WareHouse\Entities\Warehouse;
use Modules\WareHouse\Helpers\CartHelper;
use Modules\WareHouse\Helpers\CheckoutErrorsHelper;
use Modules\WareHouse\Helpers\CountryErrorsHelper;
use Modules\WareHouse\Helpers\StockHelper;
use Modules\WareHouse\Observers\OrderObserver;
use Modules\WareHouse\Observers\PaymentEntryObserver;
use Modules\WareHouse\Observers\PurchaseInvoiceObserver;
use Modules\WareHouse\Observers\PurchaseOrderObserver;
use Modules\WareHouse\Observers\PurchaseReceiptObserver;
use Modules\WareHouse\Observers\StockObserver;
use Modules\WareHouse\Observers\WarehouseObserver;

class WareHouseServiceProvider extends ServiceProvider
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
        $this->loadMigrationsFrom(module_path('WareHouse', 'Database/Migrations'));

        $this->generateObservers();
    }

    public function generateObservers()
    {
        Warehouse::observe(WarehouseObserver::class);
        Stock::observe(StockObserver::class);
        Order::observe(OrderObserver::class);

        PurchaseOrder::observe(PurchaseOrderObserver::class);
        PurchaseReceipt::observe(PurchaseReceiptObserver::class);
        PurchaseInvoice::observe(PurchaseInvoiceObserver::class);

        PaymentEntry::observe(PaymentEntryObserver::class);
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
        App::bind('CheckoutErrorsHelper', function () {
            return App::make(CheckoutErrorsHelper::class);
        });

        App::bind('CartHelper', function () {
            return App::make(CartHelper::class);
        });


        App::bind('CountryErrorsHelper', function () {
            return App::make(CountryErrorsHelper::class);
        });

        App::bind('StockHelper', function () {
            return App::make(StockHelper::class);
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
            module_path('WareHouse', 'Config/config.php') => config_path('warehouse.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path('WareHouse', 'Config/config.php'),
            'warehouse'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/warehouse');

        $sourcePath = module_path('WareHouse', 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ], 'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/warehouse';
        }, \Config::get('view.paths')), [$sourcePath]), 'warehouse');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/warehouse');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'warehouse');
        } else {
            $this->loadTranslationsFrom(module_path('WareHouse', 'Resources/lang'), 'warehouse');
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
            app(Factory::class)->load(module_path('WareHouse', 'Database/factories'));
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
