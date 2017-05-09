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


Route::group(['prefix' => 'auth'], function () {
    Route::post('/login', 'Auth\LoginController@login');
    Route::post('/signup', 'Auth\RegisterController@register');

    Route::post('/{provider}', 'Auth\SocialAuthController@socialLogin');
    Route::get('/user', 'Auth\SocialAuthController@user')->middleware('jwt.auth');
    Route::get('/refresh', 'Auth\SocialAuthController@refresh')->middleware('jwt.auth');
});

Route::group(['prefix' => 'place_categories','middleware' => 'jwt.auth'], function () {
    Route::get('/list', 'PlaceCategoriesController@index');
});

Route::group(['prefix' => 'places','middleware' => 'jwt.auth'], function () {
    Route::get('/list', 'PlacesController@index');
    Route::post('/create', 'PlacesController@store');
    Route::post('/update', 'PlacesController@update');

    //Photo upload
    Route::post('/media/upload', 'PlacePhotosController@store');
});






Route::group(['prefix' => 'user','middleware' => 'jwt.auth'], function () {
    Route::get('/index', 'UserController@index');
    Route::post('/show', 'UserController@show');
    Route::post('/create', 'UserController@create');
    Route::post('/update', 'UserController@update');
    Route::post('/destroy', 'UserController@destroy');
});