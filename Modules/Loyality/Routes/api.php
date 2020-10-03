<?php

use \Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1', 'middleware' => 'auth:api'], function () {
    ## Admins
    Route::namespace('CMS')->prefix('admins/loyality/')->as('admins.')->group(function () {
        Route::group(['prefix' => 'programs', 'as' => 'loyality-program.'], function () {
            Route::get('/', 'LoyalityProgramsController@show')->name('show');
            Route::match(['POST', 'PUT', 'PATCH'], '/', 'LoyalityProgramsController@updateOrCreate')->name('store');
        });
        Route::resource('products', 'LoyalityProductsController')->except('edit', 'create');
        Route::prefix('points')->as('points.')->group(function (){
            Route::post('add', 'UsersLoyalityController@add')->name('add');
            Route::post('remove', 'UsersLoyalityController@remove')->name('remove');
            Route::get('{user_id}/log', 'UserPointsController@log')->name('log');
            Route::get('users/{user_id}', 'UserPointsController@show')->name('users.show');
        });
    });

    $common_routes =  function () {
        Route::group(['prefix' => 'loyality/points', 'as' => 'points.'], function () {
            ## Purchase Controller
            Route::post('calculate', 'PurchaseController@calculatePoints')->name('calculate');
            Route::post('purchase', 'PurchaseController@purchasePoints')->name('purchase');
            ## Redeem Controller
            Route::get('available-levels', 'RedeemController@availableLevels')->name('available-levels');
            Route::post('validate', 'RedeemController@validatePoints')->name('validate');
            Route::post('redeem', 'RedeemController@redeem')->name('redeem');
            ## Transfer Controller
            Route::put('transfer', 'TransferController@transfer')->name('transfer');
        });
        ## Return Order Controller
        Route::put('loyality/order/{order_id}/return', 'ReturnOrdersController@returnOrder')->name('order.return');
    };

    ## Frontend AND CMS
    Route::namespace('Common')->group(function () use ($common_routes) {
        Route::group(['prefix' => 'admins', 'as' => 'admins.'], $common_routes);
        Route::group(['prefix' => '/'], $common_routes);
    });

    Route::namespace('Frontend')->group(function (){
        Route::prefix('loyality/programs')->as('loyality-program.')->group(function (){
            Route::get('/', 'LoyalityProgramsController@show')->name('show');
        });
        Route::prefix('loyality/points')->as('points.')->group(function (){
            Route::get('log', 'UserPointsController@log')->name('log');
            Route::get('users', 'UserPointsController@show')->name('users.show');
        });
    });
});
