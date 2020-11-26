<?php



Route::namespace('Front')->group(function () {
    Route::middleware('auth:api')->group(function () {
        Route::prefix('manage-articles')->group(function (){
            Route::get('','ArticleManagementController@index');

            Route::post('','ArticleManagementController@store');

            Route::get('{id}','ArticleManagementController@get');

            Route::post('info','ArticleManagementController@updateInfo');

            Route::post('confirm','ArticleManagementController@confirm');

        });

        Route::apiResource('article-authors','ArticleAuthorsController');
        Route::apiResource('article-judges','ArticleSuggestedJudgesController');
        Route::apiResource('article-attachments','ArticleAttachmentsController');

    });

    // TODO
});

Route::namespace('CMS')->prefix('admins')->group(function () {
    Route::middleware('auth:api')->as('admins.')->group(function () {

    });
});
