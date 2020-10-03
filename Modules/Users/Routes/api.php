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

Route::namespace('Frontend')->prefix('v1')->group(function () {
    Route::prefix('users')->group(function () {
        Route::post('login', 'UserAuthenticationController@login');
        Route::post('login/facebook', 'SocialAuthenticationController@facebookLogin');
        Route::post('register', 'UserAuthenticationController@register');
        Route::prefix('passwords')->group(function () {
            Route::post('forget', 'UserAuthenticationController@forgetPassword');
            Route::post('change', 'UserAuthenticationController@forgetChangePassword');
        });
        Route::group(['middleware' => ['auth:api']], function () {
            Route::post('logout', 'UserAuthenticationController@logout');
            Route::post('reset/password', 'UserAuthenticationController@resetPassword');
            Route::apiResource('addresses', 'AddressController');
            Route::prefix('favorites')->group(function () {
                Route::get('/', 'FavoriteController@index');
                Route::post('/', 'FavoriteController@store');
                Route::delete('/{favorite_id?}', 'FavoriteController@destroy');
            });
            Route::prefix('profile')->group(function () {
                Route::get('/', 'UserController@show');
                Route::match(['PUT', 'PATCH'], '/', 'UserController@updateProfile');
                Route::get('list', 'UserController@userSummary');
            });
        });
    });
});


Route::namespace('CMS')->prefix('admins')->group(function () {
    Route::post('login', 'AdminAuthenticationController@login');
    Route::prefix('passwords')->group(function () {
        Route::post('forget', 'AdminAuthenticationController@forgetPassword');
        Route::post('change', 'AdminAuthenticationController@forgetChangePassword');
    });

    Route::middleware('auth:api')->as('admins.')->group(function () {
        Route::prefix('profile')->as('profile.')->group(function () {
            Route::get('/', 'AdminController@get')->name('show');
            Route::put('/', 'AdminController@updateProfile')->name('update');
        });
        Route::apiResource('admins', 'AdminController');
        Route::prefix('admins')->as('admins.')->group(function () {
            Route::get('/sheet/export', 'AdminController@export')->name('export');
        });

        Route::apiResource('users', 'ClientController');

        Route::prefix('users')->as('users.')->group(function () {
            Route::get('/orders/{user}', 'ClientController@clientOrders')->name('orders');
            Route::get('/sheet/export', 'ClientController@export')->name('export');
        });




        Route::apiResource('addresses', 'AddressController');

        Route::post('logout', 'AdminAuthenticationController@logout')->name('logout');
        Route::post('reset/password', 'AdminAuthenticationController@resetPassword')->name('admin.reset.password');
    });
});

/* Authenticated APIs */
Route::middleware('auth:api')->group(function () {
    Route::prefix('admins')->as('admins.')->group(function () {
        Route::namespace('Common')->group(function () {
        });
    });
});
