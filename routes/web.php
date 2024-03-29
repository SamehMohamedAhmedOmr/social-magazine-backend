<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('storage/{folder}/{subFolder}/{image}', function ($folder, $subFolder, $image)
{
    $filename = 'storage' .'/'. $folder .'/'. $subFolder . '/' . $image;
    $path = public_path($filename);

    if (!File::exists($path)) {
        abort(404);
    }


    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
});

Route::get('storage/{folder}/{subFolder}/{thumb}/{image}', function ($folder, $subFolder, $thumb, $image)
{
    $filename = 'storage' .'/'. $folder .'/'. $subFolder . '/' .  $thumb . '/' . $image;
    $path = public_path($filename);

    if (!File::exists($path)) {
        abort(404);
    }


    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
});
