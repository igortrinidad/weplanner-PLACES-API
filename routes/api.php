<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
*/

/*
 * Auth
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

    //Protected routes for admin
    Route::group(['middleware' => 'auth:admin'], function () {
        Route::get('/list', 'PlacesController@index');
        Route::get('/created_by', 'PlacesController@createdBy');
        Route::get('/show/{id}', 'PlacesController@show');
        Route::post('/create', 'PlacesController@store');
        Route::post('/update', 'PlacesController@update');
        Route::get('/destroy/{id}', 'PlacesController@destroy');

        //Photo upload
        Route::post('/media/upload', 'PlacePhotosController@store');
        Route::get('/media/destroy/{id}', 'PlacePhotosController@destroy');

        //Document upload
        Route::post('/document/upload', 'PlaceDocumentsController@store');
        Route::get('/document/destroy/{id}', 'PlaceDocumentsController@destroy');

        //Appointments
        Route::post('/appointments/create', 'PlaceAppointmentsController@store');
        Route::get('/appointments/{id}', 'PlaceAppointmentsController@index');

        //calendar settings
        Route::get('/calendar_settings/show/{id}', 'PlaceCalendarSettingsController@show');
        Route::post('/calendar_settings/update', 'PlaceCalendarSettingsController@update');

        //Reservations
        Route::get('/reservations/cancel/{id}', 'PlaceReservationsController@cancel');

        //Owner request
        Route::group(['prefix' => 'owner_request'], function(){
            Route::post('/create', 'OwnerRequestsController@store');
            Route::post('/update', 'OwnerRequestsController@update');
            Route::post('/document/upload', 'OwnerRequestDocumentsController@store');
            Route::get('/document/destroy/{id}', 'OwnerRequestDocumentsController@destroy');
        });
    });

    //Protected routes for client
    Route::group(['middleware' => 'auth:client'], function () {
        //Client reservation
        Route::post('/client/reservation', 'PlaceReservationsController@store');

        //Client place resrouces
        Route::get('/client/created_by', 'PlacesController@createdByClient');
        Route::post('/client/create', 'PlacesController@store');
        Route::post('/client/update', 'PlacesController@update');

        //Client photo upload
        Route::post('/client/media/upload', 'PlacePhotosController@store');
        Route::get('/client/media/destroy/{id}', 'PlacePhotosController@destroy');
    });

    //Public resources
    Route::get('/search', 'PlacesController@nameSearch');
    Route::post('/searchByCity', 'PlacesController@searchByCity');
    Route::get('/check_url', 'PlacesController@checkUrl');
    Route::get('/public/show/{place_slug}', 'PlacesController@showPublic');
    Route::get('{category_slug}', 'PlacesController@listByCategory');
    Route::get('{category_slug}/search', 'PlacesController@search');

});

/*
 * Clients
 */
Route::group(['prefix' => 'client'], function () {
    Route::post('/auth/login', 'Auth\ClientLoginController@login');

    //Client protected routes
    Route::group(['middleware' => 'auth:client'], function () {

        //profile update
        Route::post('/update', 'ClientsController@update');

        //reservations
        Route::get('/reservations', 'PlaceReservationsController@index');
        Route::get('/reservations/cancel/{id}', 'PlaceReservationsController@cancel');

    });
});

/*
 * Users
 */
Route::group(['prefix' => 'user', 'middleware' => 'auth:admin'], function () {
    Route::get('/index', 'UserController@index');
    Route::post('/show', 'UserController@show');
    Route::post('/create', 'UserController@create');
    Route::post('/update', 'UserController@update');
    Route::post('/destroy', 'UserController@destroy');
});

/*
 * Oracle
 */
Route::group(['prefix' => 'oracle'], function () {
    Route::post('/auth/login', 'Auth\OracleLoginController@login');

    //Oracle protected routes
    Route::group(['middleware' => 'auth:oracle'], function () {

        //Places
        Route::group(['prefix' => 'places'], function () {
            Route::post('/list', 'OracleController@placesList');
            Route::get('/show/{id}', 'OracleController@placeShow');
            Route::post('/create', 'PlacesController@store');
            Route::post('/update', 'PlacesController@update');
            Route::post('/search', 'OracleController@search');

            //Photo upload
            Route::post('/media/upload', 'PlacePhotosController@store');
            Route::get('/media/destroy/{id}', 'PlacePhotosController@destroy');

            //Document upload
            Route::post('/document/upload', 'PlaceDocumentsController@store');
            Route::get('/document/destroy/{id}', 'PlaceDocumentsController@destroy');
        });

        //Owner request
        Route::group(['prefix' => 'owner_request'], function(){
            Route::get('/list', 'OwnerRequestsController@index');
        });

        //profile update
        Route::post('/user/update', 'OracleUsersController@update');

    });

});
