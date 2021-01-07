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
    Route::get('who-is-us', 'WhoIsUsController@index');
    Route::get('magazine-goals', 'MagazineGoalsController@index');
    Route::get('magazine-information', 'MagazineInformationController@index');
    Route::get('publication-rules', 'PublicationRulesController@index');
    Route::get('advisory-bodies', 'AdvisoryBodyController@index');

    Route::get('magazine-categories', 'MagazineCategoryController@index');

    Route::prefix('magazine-news')->group(function () {
        Route::get('/', 'MagazineNewsController@index');
        Route::get('/{slug}', 'MagazineNewsController@get');
    });

    Route::get('latest-magazine-news', 'MagazineNewsController@LatestNews');

    Route::get('home', 'HomeController@index');

    Route::get('dependencies', 'HomeController@dependencies');

    Route::get('testimonials', 'TestimonialController@index');

    Route::get('latest-events', 'EventsController@latest');
    Route::prefix('events')->group(function () {
        Route::get('/', 'EventsController@index');
        Route::get('/{slug}', 'EventsController@get');
    });

    Route::get('latest-activities', 'ActivityController@latest');
    Route::prefix('activities')->group(function () {
        Route::get('/', 'ActivityController@index');
        Route::get('/{slug}', 'ActivityController@get');
    });

    Route::get('latest-videos', 'VideosController@latest');
    Route::prefix('videos')->group(function () {
        Route::get('/', 'VideosController@index');
        Route::get('/{slug}', 'VideosController@get');
    });

    Route::get('latest-photos', 'PhotosController@latest');
    Route::prefix('photos')->group(function () {
        Route::get('/', 'PhotosController@index');
        Route::get('/{slug}', 'PhotosController@get');
    });

});

Route::namespace('CMS')->prefix('admins')->group(function () {
    Route::middleware('auth:api')->as('admins.')->group(function () {

        Route::apiResource('who-is-us-sections', 'WhoIsUsController');
        Route::apiResource('publication-rules', 'PublicationRulesController');
        Route::apiResource('magazine-goals', 'MagazineGoalsController');
        Route::apiResource('advisory-bodies', 'AdvisoryBodyController');

        Route::apiResource('magazine-categories', 'MagazineCategoryController');
        Route::apiResource('magazine-news', 'MagazineNewsController');
        Route::apiResource('testimonials', 'TestimonialController');

        Route::apiResource('events', 'EventsController');
        Route::apiResource('activities', 'ActivityController');
        Route::apiResource('videos', 'VideosController');
        Route::apiResource('photos', 'PhotosController');

        Route::prefix('magazine-information')->group(function () {
            Route::get('/', 'MagazineInformationController@index');
            Route::post('/', 'MagazineInformationController@store');
        });

    });
});

Route::namespace('Common')->group(function () {

    Route::prefix('trackers')->group(function () {
        Route::get('/', 'TrackerController@index');
        Route::post('/', 'TrackerController@store');
    });

});
