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
    return view('welcome');
});


Route::group(['prefix' => 'auth'], function(){
    Route::get('login', 'Auth\LoginController@index');
    Route::post('login', 'Auth\LoginController@login');
    Route::post('pre_launch', 'AdminController@upload_pre_launch');
    Route::get('logout', 'AdminController@logout');
    Route::get('token/{token}', 'Auth\LoginController@authenticate');
    Route::get('admin', 'AdminController@index');

    Route::resource('/material', 'MaterialController');
});

