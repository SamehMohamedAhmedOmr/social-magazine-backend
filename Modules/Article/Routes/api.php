<?php



Route::namespace('Front')->group(function () {
    Route::middleware('auth:api')->group(function () {
        Route::prefix('manage-articles')->group(function (){
            Route::get('','ArticleManagementController@index');

            Route::post('','ArticleManagementController@store');

            Route::get('{id}','ArticleManagementController@get');

            Route::post('info','ArticleManagementController@updateInfo');

            Route::post('confirm','ArticleManagementController@confirm');

            Route::prefix('authors')->group(function (){
                Route::get('','ArticleAuthorsController@list');
                Route::post('','ArticleAuthorsController@store');
                Route::delete('','ArticleAuthorsController@delete');
            });

            Route::prefix('judges')->group(function (){
                Route::get('','ArticleSuggestedJudgesService@list');
                Route::post('','ArticleSuggestedJudgesService@store');
                Route::delete('','ArticleSuggestedJudgesService@delete');
            });

            Route::prefix('attachments')->group(function (){
                Route::get('','ArticleAttachmentsController@list');
                Route::post('','ArticleAttachmentsController@store');
                Route::delete('','ArticleAttachmentsController@delete');
            });

        });
    });

    // TODO
});

Route::namespace('CMS')->prefix('admins')->group(function () {
    Route::middleware('auth:api')->as('admins.')->group(function () {

    });
});
