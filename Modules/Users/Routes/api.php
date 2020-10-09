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

Route::namespace('Common')->group(function () {
    Route::get('user-types','UserTypesController@index');
});

Route::namespace('Frontend')->group(function () {
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
            Route::get('/', 'CMSUsersController@get')->name('show');
            Route::put('/', 'CMSUsersController@updateProfile')->name('update');
        });
        Route::apiResource('cms-users', 'CMSUsersController');
        Route::prefix('cms-users')->as('cms-users.')->group(function () {
            Route::get('/sheet/export', 'CMSUsersController@export')->name('export');
        });

        Route::apiResource('researchers', 'ResearcherController');

        Route::prefix('users')->as('users.')->group(function () {
            Route::get('/sheet/export', 'ResearcherController@export')->name('export');
        });


        Route::post('logout', 'AdminAuthenticationController@logout')->name('logout');
        Route::post('reset/password', 'AdminAuthenticationController@resetPassword')->name('admin.reset.password');
    });
});
