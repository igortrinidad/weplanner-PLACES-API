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

Route::group(['prefix' => 'places'], function () {
    //admin resources
    Route::get('/list', 'PlacesController@index')->middleware('jwt.auth');
    Route::get('/show/{id}', 'PlacesController@show')->middleware('jwt.auth');
    Route::post('/create', 'PlacesController@store')->middleware('jwt.auth');
    Route::post('/update', 'PlacesController@update')->middleware('jwt.auth');;
    Route::get('/destroy/{id}', 'PlacesController@destroy')->middleware('jwt.auth');

    //Photo upload
    Route::post('/media/upload', 'PlacePhotosController@store')->middleware('jwt.auth');
    Route::get('/media/destroy/{id}', 'PlacePhotosController@destroy')->middleware('jwt.auth');

    //Document upload
    Route::post('/document/upload', 'PlaceDocumentsController@store')->middleware('jwt.auth');
    Route::get('/document/destroy/{id}', 'PlaceDocumentsController@destroy')->middleware('jwt.auth');

    //Appointments
    Route::get('/appointments/{id}', 'PlaceAppointmentsController@index')->middleware('jwt.auth');

    //Public resources
    Route::get('{category_slug}', 'PlacesController@listByCategory');
    Route::get('{category_slug}/{place_slug}', 'PlacesController@showPublic');
});






Route::group(['prefix' => 'user','middleware' => 'jwt.auth'], function () {
    Route::get('/index', 'UserController@index');
    Route::post('/show', 'UserController@show');
    Route::post('/create', 'UserController@create');
    Route::post('/update', 'UserController@update');
    Route::post('/destroy', 'UserController@destroy');
});