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

/* Authenticated APIs */
Route::group(['prefix' => 'v1', 'middleware' => ['auth:api']], function () {
    Route::prefix('reports')->namespace('Front')->as('reports.')->group(function () {
        Route::get('products/most-purchased', 'ReportsController@mostPurchased')->name('products.most-purchased');
    });

    Route::prefix('admins/reports')->namespace('CMS')->as('admins.reports.')->group(function () {
        Route::get('cms-dashboard', 'ReportsController@cmsDashboard')->name('cms.dashboard.statistics');
        Route::get('dashboard', 'ReportsController@dashboard')->name('dashboard');
        Route::get('districts', 'ReportsController@districts')->name('districts');
        Route::get('stock', 'ReportsController@stock')->name('stock.index');
        Route::get('stock/import', 'ReportsController@stockImport')->name('stock.import');
        Route::delete('stock/import/{log_id}', 'ReportsController@stockImportDelete')->name('stock.import.delete');
        Route::get('sales', 'ReportsController@sales')->name('sales');
        Route::get('end-of-day', 'ReportsController@endOfDay')->name('end-of-day');
        Route::get('financial', 'ReportsController@financial')->name('financial');
    });
});
