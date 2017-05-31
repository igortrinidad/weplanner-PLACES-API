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
    Route::get('/user', 'Auth\SocialAuthController@user');
    Route::get('/refresh', 'Auth\SocialAuthController@refresh');
});

Route::group(['prefix' => 'place_categories','middleware' => 'auth:admin'], function () {
    Route::get('/list', 'PlaceCategoriesController@index');
});

Route::group(['prefix' => 'places'], function () {
    //admin resources
    Route::get('/list', 'PlacesController@index')->middleware('auth:admin');
    Route::get('/show/{id}', 'PlacesController@show')->middleware('auth:admin');
    Route::post('/create', 'PlacesController@store')->middleware('auth:admin');
    Route::post('/update', 'PlacesController@update')->middleware('auth:admin');
    Route::get('/destroy/{id}', 'PlacesController@destroy')->middleware('auth:admin');

    //Photo upload
    Route::post('/media/upload', 'PlacePhotosController@store')->middleware('auth:admin');
    Route::get('/media/destroy/{id}', 'PlacePhotosController@destroy')->middleware('auth:admin');

    //Document upload
    Route::post('/document/upload', 'PlaceDocumentsController@store')->middleware('auth:admin');
    Route::get('/document/destroy/{id}', 'PlaceDocumentsController@destroy')->middleware('auth:admin');

    //Appointments
    Route::get('/appointments/{id}', 'PlaceAppointmentsController@index')->middleware('auth:admin');

    //Public resources
    Route::get('{category_slug}', 'PlacesController@listByCategory');
    Route::get('{category_slug}/{place_slug}', 'PlacesController@showPublic');

    //calendar settings
    Route::get('/calendar_settings/show/{id}', 'PlaceCalendarSettingsController@show')->middleware('auth:admin');
    Route::post('/calendar_settings/update', 'PlaceCalendarSettingsController@update')->middleware('auth:admin');
});

Route::group(['prefix' => 'client'], function () {
    Route::post('/auth/login', 'Auth\ClientLoginController@login');

    Route::post('/update', 'ClientsController@update')->middleware('auth:client');;
});






Route::group(['prefix' => 'user','middleware' => 'auth:admin'], function () {
    Route::get('/index', 'UserController@index');
    Route::post('/show', 'UserController@show');
    Route::post('/create', 'UserController@create');
    Route::post('/update', 'UserController@update');
    Route::post('/destroy', 'UserController@destroy');
});