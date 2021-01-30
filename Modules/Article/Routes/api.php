<?php



Route::namespace('Front')->group(function () {
    Route::middleware('auth:api')->group(function () {
        Route::prefix('manage-articles')->group(function (){
            Route::get('','ArticleManagementController@index');

            Route::post('','ArticleManagementController@store');

            Route::get('{id}','ArticleManagementController@show');

            Route::post('info','ArticleManagementController@updateInfo');

            Route::put('confirm/{id}','ArticleManagementController@confirm');

        });

        Route::apiResource('article-authors','ArticleAuthorsController');
        Route::apiResource('article-judges','ArticleSuggestedJudgesController');
        Route::apiResource('article-attachments','ArticleAttachmentsController');

    });

    Route::prefix('articles')->group(function (){
        Route::get('','ArticleManagementController@index');

        Route::get('{slug}','ArticleManagementController@get');

    });
});

Route::namespace('CMS')->prefix('admins')->group(function () {
    Route::middleware('auth:api')->as('admins.')->group(function () {
        Route::get('manager/articles', 'ArticleController@articleForManager');
        Route::get('editor/articles', 'ArticleController@articleForEditor');
        Route::get('judge/articles', 'ArticleController@articleForJudges');
    });
});
