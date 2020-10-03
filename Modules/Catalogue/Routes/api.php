<?php

use \Illuminate\Support\Facades\Route;

// TODO : // Check By Middleware, if it is admin
Route::namespace('Frontend')->prefix('v1')->middleware('check.logged_in')->group(function () {
    Route::resource('categories', 'CategoriesController')->only('index', 'show');
    Route::resource('brands', 'BrandsController')->only('index', 'show');
    Route::resource('products', 'ProductsController')->only('index', 'show');
    Route::resource('topping-menus', 'ToppingMenusController')->only('index', 'show');
    Route::resource('variants', 'VariantsController')->only('index', 'show');
    Route::get('search/settings', 'SearchSettingsController@settings');
    Route::get('home', 'HomeController@index');
});


Route::group(['middleware' => ['auth:api']], function () {
    Route::namespace('CMS')->prefix('v1/admins')->as('admins.')->group(function () {
        Route::match(
            ['PATCH', 'PUT'],
            'categories/{category}/images/remove',
            'CategoriesController@removeImages'
        )->name('categories.images.remove');
        Route::match(
            ['PATCH', 'PUT'],
            'categories/{category}/images/add',
            'CategoriesController@addImages'
        )->name('categories.images.add');
        Route::match(
            ['PATCH', 'PUT'],
            'categories/{category}/restore',
            'CategoriesController@restore'
        )->name('categories.restore');
        Route::apiResource('categories', 'CategoriesController');
        Route::prefix('categories')->as('categories.')->group(function () {
            Route::get('sheet/export', 'CategoriesController@export')->name('export');
        });

        Route::match(
            ['PATCH', 'PUT'],
            'brands/{brand}/images/remove',
            'BrandsController@removeImage'
        )->name('brands.images.remove');
        Route::match(
            ['PATCH', 'PUT'],
            'brands/{brand}/images/add',
            'BrandsController@addImage'
        )->name('brands.images.add');
        Route::match(
            ['PATCH', 'PUT'],
            'brands/{brand}/restore',
            'BrandsController@restore'
        )->name('brands.restore');
        Route::apiResource('brands', 'BrandsController');
        Route::prefix('brands')->as('brands.')->group(function () {
            Route::get('sheet/export', 'BrandsController@export')->name('export');
        });

        Route::match(
            ['PATCH', 'PUT'],
            'units-of-measure/{units_of_measure}/restore',
            'UnitsOfMeasureController@restore'
        )->name('units-of-measure.restore');
        Route::apiResource('units-of-measure', 'UnitsOfMeasureController');
        Route::prefix('units-of-measure')->as('units-of-measure.')->group(function () {
            Route::get('sheet/export', 'UnitsOfMeasureController@export')->name('export');
        });

        Route::match(
            ['PATCH', 'PUT'],
            'variants/{variant}/restore',
            'VariantsController@restore'
        )->name('variants.restore');
        Route::apiResource('variants', 'VariantsController');
        Route::prefix('variants')->as('variants.')->group(function () {
            Route::get('sheet/export', 'VariantsController@export')->name('export');
        });

        Route::match(
            ['PATCH', 'PUT'],
            'variant-values/{variant-value}/restore',
            'VariantValuesController@restore'
        )->name('variant-values.restore');
        Route::apiResource('variant-values', 'VariantValuesController')->except('update');
        Route::prefix('variant-values')->as('variant-values.')->group(function () {
            Route::get('sheet/export', 'VariantValuesController@export')->name('export');
        });
        Route::match(['PUT', 'POST'], 'variant-values/{variant_value}', 'VariantValuesController@update')->name('variant-values.update');

        Route::match(
            ['PATCH', 'PUT'],
            'topping-menus/{topping_menu}/restore',
            'ToppingMenusController@restore'
        )->name('topping-menus.restore');
        Route::apiResource('topping-menus', 'ToppingMenusController');
        Route::prefix('topping-menus')->as('topping-menus.')->group(function () {
            Route::get('sheet/export', 'ToppingMenusController@export')->name('export');
        });

        Route::match(
            ['PATCH', 'PUT'],
            'products/{product}/restore',
            'ProductsController@restore'
        )->name('products.restore');
        Route::post('products/images', 'ProductsController@storeImages')->name('products.images.store');
        Route::apiResource('products', 'ProductsController');
        Route::prefix('products')->as('products.')->group(function () {
            Route::get('sheet/export', 'ProductsController@export')->name('export');
        });
    });


    Route::namespace('Frontend')->prefix('v1')->group(function () {
        Route::post('notify-me/products/{product}', 'ProductNotificationController@store');
    });

});
