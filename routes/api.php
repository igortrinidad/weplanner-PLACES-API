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
    Route::post('/client/signup', 'Auth\RegisterController@registerClient');

    Route::post('/social', 'Auth\SocialAuthController@socialLogin');
    Route::get('/user', 'Auth\SocialAuthController@user');
    Route::get('/refresh', 'Auth\SocialAuthController@refresh');
});

Route::group(['prefix' => 'places'], function () {
    //admin resources
    Route::get('/list', 'PlacesController@index')->middleware('auth:admin');
    Route::get('/created_by', 'PlacesController@createdBy')->middleware('auth:admin');
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
    Route::post('/appointments/create', 'PlaceAppointmentsController@store')->middleware('auth:admin');
    Route::get('/appointments/{id}', 'PlaceAppointmentsController@index')->middleware('auth:admin');

    //calendar settings
    Route::get('/calendar_settings/show/{id}', 'PlaceCalendarSettingsController@show')->middleware('auth:admin');
    Route::post('/calendar_settings/update', 'PlaceCalendarSettingsController@update')->middleware('auth:admin');

    //Reservations
    Route::get('/reservations/cancel/{id}', 'PlaceReservationsController@cancel')->middleware('auth:admin');

    //Client reservation
    Route::post('/client/reservation', 'PlaceReservationsController@store')->middleware('auth:client');

    //Client place resrouces
    Route::get('/client/created_by', 'PlacesController@createdByClient')->middleware('auth:client');
    Route::post('/client/create', 'PlacesController@store')->middleware('auth:client');
    Route::post('/client/update', 'PlacesController@update')->middleware('auth:client');

    //Client photo upload
    Route::post('/client/media/upload', 'PlacePhotosController@store')->middleware('auth:client');
    Route::get('/client/media/destroy/{id}', 'PlacePhotosController@destroy')->middleware('auth:client');

    //Public resources
    Route::get('/search', 'PlacesController@nameSearch');
    Route::get('/check_url', 'PlacesController@checkUrl');
    Route::get('{category_slug}', 'PlacesController@listByCategory');
    Route::get('{category_slug}/search', 'PlacesController@search');
    Route::get('{category_slug}/{place_slug}', 'PlacesController@showPublic');

});

Route::group(['prefix' => 'client'], function () {
    Route::post('/auth/login', 'Auth\ClientLoginController@login');

    //profile update
    Route::post('/update', 'ClientsController@update')->middleware('auth:client');

    //reservations
    Route::get('/reservations', 'PlaceReservationsController@index')->middleware('auth:client');
    Route::get('/reservations/cancel/{id}', 'PlaceReservationsController@cancel')->middleware('auth:client');
});






Route::group(['prefix' => 'user','middleware' => 'auth:admin'], function () {
    Route::get('/index', 'UserController@index');
    Route::post('/show', 'UserController@show');
    Route::post('/create', 'UserController@create');
    Route::post('/update', 'UserController@update');
    Route::post('/destroy', 'UserController@destroy');
});