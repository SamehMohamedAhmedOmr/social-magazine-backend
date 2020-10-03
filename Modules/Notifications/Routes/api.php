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

Route::namespace('Frontend')->prefix('v1')->middleware('check.logged_in')->group(function () {
    Route::post('devices/tokens', 'DeviceTokenController@store');
});

Route::group(['middleware' => ['auth:api']], function () {
    Route::namespace('CMS')->prefix('admins')->as('admins.')->group(function () {
        Route::post('users/notify', 'NotificationsController@notify')->name('notification.notify');
        Route::prefix('notifications')->as('admin.notifications.')->group(function () {
            Route::get('/', 'CMSNotificationsController@index')->name('index');
            Route::match(['put', 'patch'], '/', 'CMSNotificationsController@update')->name('update');
            Route::delete('/', 'CMSNotificationsController@delete')->name('delete');
        });
    });
});
