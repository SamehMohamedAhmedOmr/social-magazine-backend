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

Route::get('countries','CountryController@index');
Route::get('educational-degrees','EducationalDegreeController@index');
Route::get('educational-levels','EducationalLevelController@index');
Route::get('genders','GenderController@index');
Route::get('titles','TitleController@index');


Route::get('account-dependencies','AccountDependenciesController@index');
