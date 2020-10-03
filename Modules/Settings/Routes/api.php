<?php

use Illuminate\Support\Facades\Route;

/* Authenticated APIs */
Route::group(['middleware' => ['auth:api']], function () {
    Route::prefix('admins')->as('admins.')->group(function () {
        Route::namespace('CMS')->group(function () {
            Route::group(['prefix' => 'v1'], function () {
                Route::apiResource('languages', 'LanguagesController');
            });

            Route::apiResource('time-sections', 'TimeSectionController');
            Route::prefix('time-sections')->as('time-sections.')->group(function () {
                Route::get('sheet/export', 'TimeSectionController@export')->name('export');
            });

            Route::apiResource('price-lists', 'PriceListController');
            Route::prefix('price-lists')->as('price-lists.')->group(function () {
                Route::get('sheet/export', 'PriceListController@export')->name('export');
            });

            Route::apiResource('price-list-types', 'PriceListTypeController');

            Route::get('currencies', 'CurrencyController@index')->name('currencies.list');

            Route::apiResource('mobile-updates', 'MobileUpdateController');
            Route::prefix('mobile-updates')->as('mobile-updates.')->group(function () {
                Route::get('sheet/export', 'MobileUpdateController@export')->name('export');
            });

            Route::apiResource('taxes-lists', 'TaxesListController');
            Route::prefix('taxes-lists')->as('taxes-lists.')->group(function () {
                Route::get('sheet/export', 'TaxesListController@export')->name('export');
            });

            Route::get('taxes-list-type', 'TaxesListController@listTaxesType')->name('taxes-list-type.list');
            Route::get('taxes-list-amount-type', 'TaxesListController@listTaxesAmountType')
                ->name('taxes-list-amount-type.list');

            Route::apiResource('payment-methods', 'PaymentMethodsController');
            Route::prefix('payment-methods')->as('payment-methods.')->group(function () {
                Route::get('sheet/export', 'PaymentMethodsController@export')->name('export');
            });

            Route::apiResource('shipping-rules', 'ShippingRulesController');
            Route::prefix('shipping-rules')->as('shipping-rules.')->group(function () {
                Route::get('sheet/export', 'ShippingRulesController@export')->name('export');
            });

            Route::apiResource('companies', 'CompanyController');
            Route::prefix('companies')->as('companies.')->group(function () {
                Route::get('sheet/export', 'CompanyController@export')->name('export');
            });

            Route::prefix('system-settings')->group(function () {
                Route::get('/', 'SystemSettingController@show')->name('system-settings.show');
                Route::post('/', 'SystemSettingController@store')->name('system-settings.store');
            });

            Route::apiResource('system-notes', 'SystemNotesController');
            Route::prefix('system-notes')->as('system-notes.')->group(function () {
                Route::get('sheet/export', 'SystemNotesController@export')->name('export');
            });

            Route::prefix('frontend-settings')->namespace('FrontendSettings')
                ->as('frontend-settings.')->group(function () {
                    Route::get('/', 'FrontendSettingsController@show')->name('get');
                    Route::post('/', 'FrontendSettingsController@store')->name('store');
                });

            Route::prefix('fonts')->namespace('FrontendSettings')->as('fonts.')->group(function () {
                Route::get('/', 'FrontendFontsController@index')->name('get');
                Route::post('/', 'FrontendFontsController@store')->name('store');
            });

            Route::prefix('menu-navigation-type')->namespace('FrontendSettings')
                ->as('menu-navigation-type.')->group(function () {
                Route::get('/', 'FrontendMenuNavigationController@index')->name('get');
            });

            Route::apiResource('pages', 'PagesController');
            Route::prefix('pages')->as('pages.')->group(function () {
                Route::get('sheet/export', 'PagesController@export')->name('export');
            });

            Route::apiResource('menus', 'FrontendSettings\MenuController');
            Route::prefix('menus')->as('menus.')->namespace('FrontendSettings')->group(function () {
                Route::get('sheet/export', 'MenuController@export')->name('export');
            });

        });
    });
});


/*-------  Time Section Frontend Routes -------*/
Route::namespace('Frontend')->group(function () {
    Route::get('time-sections', 'TimeSectionController@index');

    Route::get('payment-methods', 'PaymentMethodController@index');

    Route::get('mobile-updates/configuration', 'MobileUpdateController@configuration');

    Route::get('configurations', 'ConfigurationController@index');

    Route::get('pages/{page_url}', 'PagesController@show');


    Route::prefix('frontend-settings')->namespace('FrontendSettings')->group(function () {
        Route::get('/', 'FrontendSettingsController@show');
    });
});
