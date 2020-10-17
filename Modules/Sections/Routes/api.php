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


Route::namespace('FRONT')->group(function () {
    Route::get('who-is-us','WhoIsUsController@index');
    Route::get('magazine-goals','MagazineGoalsController@index');
    Route::get('magazine-information','MagazineInformationController@index');
});

Route::namespace('CMS')->prefix('admins')->group(function () {
    Route::middleware('auth:api')->as('admins.')->group(function () {

        Route::apiResource('who-is-us-sections','WhoIsUsController');
        Route::apiResource('magazine-goals','MagazineGoalsController');
        Route::prefix('magazine-information')->group(function () {
            Route::get('/','MagazineInformationController@index');
            Route::post('/','MagazineInformationController@store');
        });

    });
});
