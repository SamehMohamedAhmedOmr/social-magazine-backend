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


Route::group(['middleware' => ['auth:api']], function () {
    Route::namespace('CMS')->prefix('admins')->as('admins.')->group(function () {
        Route::prefix('gallery')->as('gallery.')->group(function () {
            Route::get('/', 'GalleryController@index')->name('index');
            Route::post('/', 'GalleryController@store')->name('store');
            Route::delete('/{gallery_id}', 'GalleryController@destroy')->name('destroy');
        });
    });
});
