<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
    $language = 'en';
        return view('welcome', ['lang' => $language]);
});

Route::get('/{language}', function ($language) {
    if($language == 'es' || $language == 'en'){
        return view('welcome', ['lang' => $language]);
    }

    return view('errors.503');
});

Route::get('/local/{type}/{filename}', function ($type, $filename)
{
    $path = storage_path() . '/app/' . $type . '/' . $filename;

    if(!File::exists($path)) abort(404);

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
});

Route::get('/storage/{type}/{filename}', function ($type, $filename)
{
    $path = storage_path() . '/app/' . $type . '/' . $filename;

    if(!File::exists($path)) abort(404);

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
});

Route::group(['prefix' => 'auth'], function(){
    Route::get('login', 'Auth\LoginController@index');
    Route::post('login', 'Auth\LoginController@login');
    Route::post('/notify/email', 'Auth\LoginController@notify');
    Route::post('pre_launch', 'AdminController@upload_pre_launch');
    Route::get('logout', 'AdminController@logout');
    Route::get('token/{token}', 'Auth\LoginController@authenticate');
    Route::get('admin', 'AdminController@index');
    
    Route::put('/materials/reel/{id}', 'MaterialController@updateReel');

    Route::get('/contents/{type}', 'ContentController@showContentType');
    Route::resource('/materials', 'MaterialController');
    Route::resource('/contacts', 'ContactController');
    Route::resource('/contents', 'ContentController');
    Route::resource('/gallery', 'GalleryController');
    Route::resource('/social_media', 'MediaController');
});

