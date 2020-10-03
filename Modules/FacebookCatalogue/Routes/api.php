<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1/admins/facebook/catalogue')->as('admins.facebook.catalogue.')->group(function () {
    Route::namespace('Products')->prefix('products')->as('products.')->group(function () {
        Route::get('sync', 'ProductsController@sync')->name('sync');
        Route::prefix('dynamic-links')->as('dynamic-links.')->group(function () {
            Route::any('/', 'ProductsController@createDynamicLinkForProducts')->name('all');
            Route::any('/{product_id}', 'ProductsController@createDynamicLinkForOneProduct')->name('one');
        });
    });
    Route::prefix('settings')->as('settings.')->group(function () {
        Route::get('/', 'FacebookCatalogueSettingsController@show')->name('show');
        Route::match(['POST', 'PUT', 'PATCH'], '/', 'FacebookCatalogueSettingsController@updateOrCreate')->name('store');
    });
    Route::prefix('logs')->as('logs.')->group(function () {
        Route::get('/', 'FacebookCatalogueLogsController@paginate')->name('index');
        Route::delete('/{log_id}', 'FacebookCatalogueLogsController@delete')->name('delete');
    });
});
