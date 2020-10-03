<?php

namespace Modules\Settings\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Modules\Settings\Entities\Company;
use Modules\Settings\Entities\Currency;
use Modules\Settings\Entities\PaymentMethod;
use Modules\Settings\Entities\ShippingRules;
use Modules\Settings\Entities\SystemNote;
use Modules\Settings\Entities\SystemSetting;
use Modules\Settings\Entities\TimeSection;
use Modules\Settings\Observers\CompanyObserver;
use Modules\Settings\Observers\CurrencyObserver;
use Modules\Settings\Observers\PaymentMethodObserver;
use Modules\Settings\Observers\ShippingRuleObserver;
use Modules\Settings\Observers\SystemNotesObserver;
use Modules\Settings\Observers\SystemSettingObserver;
use Modules\Settings\Observers\TimeSectionObserver;

class SettingsServiceProvider extends ServiceProvider
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
        $this->loadMigrationsFrom(module_path('Settings', 'Database/Migrations'));

        $this->generateObservers();
    }

    public function generateObservers()
    {
        Company::observe(CompanyObserver::class);
        Currency::observe(CurrencyObserver::class);
        PaymentMethod::observe(PaymentMethodObserver::class);
        ShippingRules::observe(ShippingRuleObserver::class);
        SystemSetting::observe(SystemSettingObserver::class);
        SystemNote::observe(SystemNotesObserver::class);
        TimeSection::observe(TimeSectionObserver::class);
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
            module_path('Settings', 'Config/config.php') => config_path('settings.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path('Settings', 'Config/config.php'),
            'settings'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/settings');

        $sourcePath = module_path('Settings', 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ], 'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/settings';
        }, \Config::get('view.paths')), [$sourcePath]), 'settings');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/settings');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'settings');
        } else {
            $this->loadTranslationsFrom(module_path('Settings', 'Resources/lang'), 'settings');
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
            app(Factory::class)->load(module_path('Settings', 'Database/factories'));
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
