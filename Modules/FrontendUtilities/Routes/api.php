<?php

use Illuminate\Support\Facades\Route;

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

// 1 - require to finish Authentication and Authorization Modules

Route::group(['middleware' => ['auth:api']], function () {
    Route::namespace('CMS')->prefix('admins')->as('admins.')->group(function () {
        Route::apiResource('banners', 'BannerController');
        Route::prefix('banners')->as('banners.')->group(function () {
            Route::get('sheet/export', 'BannerController@export')->name('export');
        });

        Route::match(['PUT', 'PATCH'], 'banners/{banner_id}/restore', 'BannerController@restore')->name('banners.restore');

        Route::apiResource('collections', 'CollectionController');
        Route::prefix('collections')->as('collections.')->group(function () {
            Route::get('sheet/export', 'CollectionController@export')->name('export');
        });

        Route::apiResource('promocodes', 'PromocodeController');
        Route::prefix('promocodes')->as('promocodes.')->group(function () {
            Route::get('sheet/export', 'PromocodeController@export')->name('export');
        });
    });
});

/*-------  banner Front End Routes -------*/
Route::namespace('Frontend')->prefix('v1')->group(function () {
    Route::get('banners', 'BannerController@index');
    Route::get('collections', 'CollectionController@index');
    Route::group(['middleware' => ['auth:api']], function () {
        Route::post('promocode/validate', 'PromocodeController@validate');
    });
});
